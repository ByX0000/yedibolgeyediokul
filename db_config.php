<?php
// Veritabanı bağlantı ayarları - Ortam değişkenlerinden oku
$host     = getenv('DB_HOST') ?: 'localhost';
$port     = getenv('DB_PORT') ?: '3306';
$dbname   = getenv('DB_NAME') ?: 'anadolunun_mirasi';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$charset  = getenv('DB_CHARSET') ?: 'utf8mb4';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch(PDOException $e) {
    // Prod'da detaylı hata mesajı gösterme
    if (getenv('APP_DEBUG') === 'true') {
        die("Veritabanı bağlantı hatası: " . $e->getMessage());
    }
    error_log("DB Connection Error: " . $e->getMessage());
    die(json_encode(['success' => false, 'message' => 'Sunucu hatası. Lütfen daha sonra tekrar deneyin.']));
}
?>
