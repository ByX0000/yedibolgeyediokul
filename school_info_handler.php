<?php
header('Content-Type: application/json');
require_once 'db_config.php';

// Veritabanında school_info tablosunu oluştur
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS school_info (
        id INT AUTO_INCREMENT PRIMARY KEY,
        school_name VARCHAR(255) NOT NULL,
        info_type VARCHAR(50) NOT NULL,
        content TEXT,
        images TEXT,
        videos TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_school_info (school_name, info_type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
} catch(PDOException $e) {
    // Tablo zaten varsa hata vermez
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // İçerikleri getir
    $schoolName = $_GET['school'] ?? '';
    $infoType = $_GET['type'] ?? '';

    $sql = "SELECT * FROM school_info WHERE school_name = ?";
    $params = [$schoolName];

    if ($infoType) {
        $sql .= " AND info_type = ?";
        $params[] = $infoType;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // JSON verilerini parse et
    foreach ($data as &$item) {
        $item['images'] = $item['images'] ? json_decode($item['images'], true) : [];
        $item['videos'] = $item['videos'] ? json_decode($item['videos'], true) : [];
    }

    echo json_encode(['success' => true, 'data' => $data]);

} elseif ($method === 'POST') {
    // İçerik ekle veya güncelle
    $data = json_decode(file_get_contents('php://input'), true);

    $schoolName = $data['school_name'] ?? '';
    $infoType = $data['info_type'] ?? '';
    $content = $data['content'] ?? '';
    $images = isset($data['images']) ? json_encode($data['images']) : json_encode([]);
    $videos = isset($data['videos']) ? json_encode($data['videos']) : json_encode([]);

    if (!$schoolName || !$infoType) {
        echo json_encode(['success' => false, 'message' => 'Okul adı ve bilgi tipi gerekli']);
        exit;
    }

    // INSERT ... ON DUPLICATE KEY UPDATE
    $sql = "INSERT INTO school_info (school_name, info_type, content, images, videos)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            content = VALUES(content),
            images = VALUES(images),
            videos = VALUES(videos),
            updated_at = CURRENT_TIMESTAMP";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$schoolName, $infoType, $content, $images, $videos]);

    echo json_encode(['success' => true, 'message' => 'İçerik kaydedildi']);

} elseif ($method === 'DELETE') {
    // İçerik sil
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'] ?? 0;

    $stmt = $pdo->prepare("DELETE FROM school_info WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'İçerik silindi']);
}
?>
