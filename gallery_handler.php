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

if ($method === 'GET') {
    $id = $_GET['id'] ?? '';
    $category = $_GET['category'] ?? '';

    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        $sql = "SELECT * FROM gallery";
        $params = [];

        if ($category) {
            $sql .= " WHERE category = ?";
            $params[] = $category;
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $data]);
    }

} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $url = $data['url'] ?? '';
    $title = $data['title'] ?? '';
    $category = $data['category'] ?? '';

    if (!$url || !$title || !$category) {
        echo json_encode(['success' => false, 'message' => 'URL, başlık ve kategori gerekli']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO gallery (url, title, category) VALUES (?, ?, ?)");
    $stmt->execute([$url, $title, $category]);

    echo json_encode([
        'success' => true,
        'message' => 'Görsel başarıyla eklendi',
        'id' => $pdo->lastInsertId()
    ]);

} elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'] ?? 0;
    $url = $data['url'] ?? '';
    $title = $data['title'] ?? '';
    $category = $data['category'] ?? '';

    if (!$id || !$title) {
        echo json_encode(['success' => false, 'message' => 'ID ve başlık gerekli']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE gallery SET url = ?, title = ?, category = ? WHERE id = ?");
    $stmt->execute([$url, $title, $category, $id]);

    echo json_encode(['success' => true, 'message' => 'Görsel başarıyla güncellendi']);

} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? 0;

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID gerekli']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Görsel başarıyla silindi']);
}
?>
