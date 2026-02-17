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
    $school = $_GET['school'] ?? '';
    $category = $_GET['category'] ?? '';

    $sql = "SELECT * FROM activities WHERE 1=1";
    $params = [];

    if ($id) {
        $sql = "SELECT * FROM activities WHERE id = ?";
        $params = [$id];
    } else {
        if ($school) {
            $sql .= " AND school = ?";
            $params[] = $school;
        }
        if ($category) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }
        $sql .= " ORDER BY date DESC";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    if ($id) {
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode(['success' => true, 'data' => $data]);

} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $name = $data['name'] ?? '';
    $school = $data['school'] ?? '';
    $date = $data['date'] ?? '';
    $category = $data['category'] ?? '';
    $description = $data['description'] ?? '';
    $participants = intval($data['participants'] ?? 0);

    if (!$name || !$school || !$date || !$category) {
        echo json_encode(['success' => false, 'message' => 'Etkinlik adı, okul, tarih ve kategori gerekli']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO activities (name, school, date, category, description, participants) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $school, $date, $category, $description, $participants ?: null]);

    echo json_encode([
        'success' => true,
        'message' => 'Etkinlik başarıyla eklendi',
        'id' => $pdo->lastInsertId()
    ]);

} elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'] ?? 0;
    $name = $data['name'] ?? '';
    $school = $data['school'] ?? '';
    $date = $data['date'] ?? '';
    $category = $data['category'] ?? '';
    $description = $data['description'] ?? '';
    $participants = intval($data['participants'] ?? 0);

    if (!$id || !$name) {
        echo json_encode(['success' => false, 'message' => 'ID ve etkinlik adı gerekli']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE activities SET name = ?, school = ?, date = ?, category = ?, description = ?, participants = ? WHERE id = ?");
    $stmt->execute([$name, $school, $date, $category, $description, $participants ?: null, $id]);

    echo json_encode(['success' => true, 'message' => 'Etkinlik başarıyla güncellendi']);

} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? 0;

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID gerekli']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM activities WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Etkinlik başarıyla silindi']);
}
?>
