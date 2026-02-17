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

    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM regions WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        $stmt = $pdo->query("SELECT * FROM regions ORDER BY id ASC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $data]);
    }

} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $name = $data['name'] ?? '';
    $color = $data['color'] ?? '#000000';
    $description = $data['description'] ?? '';

    if (!$name) {
        echo json_encode(['success' => false, 'message' => 'Bölge adı gerekli']);
        exit;
    }

    // Aynı isimde bölge var mı kontrol et
    $stmt = $pdo->prepare("SELECT id FROM regions WHERE name = ?");
    $stmt->execute([$name]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Bu isimde bir bölge zaten mevcut']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO regions (name, color, description) VALUES (?, ?, ?)");
    $stmt->execute([$name, $color, $description]);

    echo json_encode([
        'success' => true,
        'message' => 'Bölge başarıyla eklendi',
        'id' => $pdo->lastInsertId()
    ]);

} elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'] ?? 0;
    $name = $data['name'] ?? '';
    $color = $data['color'] ?? '#000000';
    $description = $data['description'] ?? '';

    if (!$id || !$name) {
        echo json_encode(['success' => false, 'message' => 'ID ve bölge adı gerekli']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE regions SET name = ?, color = ?, description = ? WHERE id = ?");
    $stmt->execute([$name, $color, $description, $id]);

    echo json_encode(['success' => true, 'message' => 'Bölge başarıyla güncellendi']);

} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? 0;

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID gerekli']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM regions WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Bölge başarıyla silindi']);
}
?>
