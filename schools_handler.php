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
    $region = $_GET['region'] ?? '';

    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM schools WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $data]);
    } elseif ($region) {
        $stmt = $pdo->prepare("SELECT * FROM schools WHERE region = ? ORDER BY name ASC");
        $stmt->execute([$region]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        $stmt = $pdo->query("SELECT * FROM schools ORDER BY id ASC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $data]);
    }

} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $name = $data['name'] ?? '';
    $region = $data['region'] ?? '';
    $city = $data['city'] ?? '';
    $students = intval($data['students'] ?? 0);
    $address = $data['address'] ?? '';

    if (!$name || !$region || !$city) {
        echo json_encode(['success' => false, 'message' => 'Okul adı, bölge ve şehir gerekli']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO schools (name, region, city, students, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $region, $city, $students ?: null, $address]);

    echo json_encode([
        'success' => true,
        'message' => 'Okul başarıyla eklendi',
        'id' => $pdo->lastInsertId()
    ]);

} elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'] ?? 0;
    $name = $data['name'] ?? '';
    $region = $data['region'] ?? '';
    $city = $data['city'] ?? '';
    $students = intval($data['students'] ?? 0);
    $address = $data['address'] ?? '';

    if (!$id || !$name) {
        echo json_encode(['success' => false, 'message' => 'ID ve okul adı gerekli']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE schools SET name = ?, region = ?, city = ?, students = ?, address = ? WHERE id = ?");
    $stmt->execute([$name, $region, $city, $students ?: null, $address, $id]);

    echo json_encode(['success' => true, 'message' => 'Okul başarıyla güncellendi']);

} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? 0;

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID gerekli']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM schools WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Okul başarıyla silindi']);
}
?>
