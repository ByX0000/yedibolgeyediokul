<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . (getenv('CORS_ALLOWED_ORIGINS') ?: '*'));
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

$adminUsername     = getenv('ADMIN_USERNAME') ?: 'admin';
$adminPasswordHash = getenv('ADMIN_PASSWORD_HASH') ?: '';
$adminPassword     = getenv('ADMIN_PASSWORD') ?: '';

if (!$username || !$password) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Kullanıcı adı ve şifre gerekli']);
    exit;
}

// Hash varsa password_verify, yoksa düz karşılaştırma (geçiş dönemi için)
$passwordOk = false;
if ($adminPasswordHash && strpos($adminPasswordHash, '$2y$') === 0) {
    $passwordOk = password_verify($password, $adminPasswordHash);
} elseif ($adminPassword) {
    $passwordOk = hash_equals($adminPassword, $password);
}

if ($username === $adminUsername && $passwordOk) {
    session_start();
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user'] = $username;
    echo json_encode(['success' => true, 'message' => 'Giriş başarılı']);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Kullanıcı adı veya şifre hatalı']);
}
?>
