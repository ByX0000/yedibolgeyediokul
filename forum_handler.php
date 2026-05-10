<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . (getenv('CORS_ALLOWED_ORIGINS') ?: '*'));
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'db_config.php';

$method = $_SERVER['REQUEST_METHOD'];
$resource = $_GET['resource'] ?? 'topics'; // 'topics' veya 'replies'

function clean_text($text, $max = 5000) {
    $text = trim($text);
    if (mb_strlen($text) > $max) {
        $text = mb_substr($text, 0, $max);
    }
    return $text;
}

if ($resource === 'topics') {

    if ($method === 'GET') {
        $id = $_GET['id'] ?? '';
        $category = $_GET['category'] ?? '';

        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM forum_topics WHERE id = ?");
            $stmt->execute([$id]);
            $topic = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$topic) {
                echo json_encode(['success' => false, 'message' => 'Konu bulunamadı']);
                exit;
            }

            $rstmt = $pdo->prepare("SELECT * FROM forum_replies WHERE topic_id = ? ORDER BY created_at ASC");
            $rstmt->execute([$id]);
            $topic['replies'] = $rstmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => $topic]);
            exit;
        }

        $sql = "SELECT * FROM forum_topics WHERE 1=1";
        $params = [];
        if ($category && $category !== 'all') {
            $sql .= " AND category = ?";
            $params[] = $category;
        }
        $sql .= " ORDER BY updated_at DESC LIMIT 200";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $data]);

    } elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true) ?: [];

        $title = clean_text($data['title'] ?? '', 255);
        $author = clean_text($data['author'] ?? '', 100);
        $school = clean_text($data['school'] ?? '', 255);
        $category = clean_text($data['category'] ?? 'Genel', 100);
        $content = clean_text($data['content'] ?? '', 5000);

        if ($title === '' || $author === '' || $content === '') {
            echo json_encode(['success' => false, 'message' => 'Başlık, yazar ve içerik gerekli']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO forum_topics (title, author, school, category, content) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $author, $school ?: null, $category ?: 'Genel', $content]);

        echo json_encode([
            'success' => true,
            'message' => 'Konu başarıyla eklendi',
            'id' => $pdo->lastInsertId()
        ]);

    } elseif ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true) ?: [];
        $id = intval($data['id'] ?? 0);

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID gerekli']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM forum_topics WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['success' => true, 'message' => 'Konu silindi']);
    }

} elseif ($resource === 'replies') {

    if ($method === 'GET') {
        $topic_id = intval($_GET['topic_id'] ?? 0);
        if (!$topic_id) {
            echo json_encode(['success' => false, 'message' => 'topic_id gerekli']);
            exit;
        }
        $stmt = $pdo->prepare("SELECT * FROM forum_replies WHERE topic_id = ? ORDER BY created_at ASC");
        $stmt->execute([$topic_id]);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    } elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true) ?: [];

        $topic_id = intval($data['topic_id'] ?? 0);
        $author = clean_text($data['author'] ?? '', 100);
        $school = clean_text($data['school'] ?? '', 255);
        $content = clean_text($data['content'] ?? '', 3000);

        if (!$topic_id || $author === '' || $content === '') {
            echo json_encode(['success' => false, 'message' => 'Konu, yazar ve içerik gerekli']);
            exit;
        }

        $check = $pdo->prepare("SELECT id FROM forum_topics WHERE id = ?");
        $check->execute([$topic_id]);
        if (!$check->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Konu bulunamadı']);
            exit;
        }

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO forum_replies (topic_id, author, school, content) VALUES (?, ?, ?, ?)");
            $stmt->execute([$topic_id, $author, $school ?: null, $content]);
            $newId = $pdo->lastInsertId();

            $pdo->prepare("UPDATE forum_topics SET reply_count = reply_count + 1, updated_at = CURRENT_TIMESTAMP WHERE id = ?")
                ->execute([$topic_id]);
            $pdo->commit();

            echo json_encode(['success' => true, 'message' => 'Cevap eklendi', 'id' => $newId]);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Cevap eklenemedi']);
        }

    } elseif ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true) ?: [];
        $id = intval($data['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID gerekli']);
            exit;
        }

        $r = $pdo->prepare("SELECT topic_id FROM forum_replies WHERE id = ?");
        $r->execute([$id]);
        $row = $r->fetch();

        $pdo->prepare("DELETE FROM forum_replies WHERE id = ?")->execute([$id]);

        if ($row) {
            $pdo->prepare("UPDATE forum_topics SET reply_count = GREATEST(reply_count - 1, 0) WHERE id = ?")
                ->execute([$row['topic_id']]);
        }

        echo json_encode(['success' => true, 'message' => 'Cevap silindi']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Geçersiz kaynak']);
}
?>
