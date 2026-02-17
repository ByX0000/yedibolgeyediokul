<?php
header('Content-Type: application/json');
require_once 'db_config.php';

// Ligler tablosunu oluştur
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS leagues (
        id INT AUTO_INCREMENT PRIMARY KEY,
        league_name VARCHAR(255) NOT NULL,
        school_name VARCHAR(255) NOT NULL,
        played INT DEFAULT 0,
        won INT DEFAULT 0,
        drawn INT DEFAULT 0,
        lost INT DEFAULT 0,
        points INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_league_school (league_name, school_name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
} catch(PDOException $e) {
    // Tablo zaten varsa hata vermez
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $leagueName = $_GET['league'] ?? '';

    if ($leagueName) {
        // Belirli bir lig
        $stmt = $pdo->prepare("SELECT * FROM leagues WHERE league_name = ? ORDER BY points DESC, won DESC, (won - lost) DESC");
        $stmt->execute([$leagueName]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Tüm ligler
        $stmt = $pdo->query("SELECT * FROM leagues ORDER BY league_name, points DESC, won DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode(['success' => true, 'data' => $data]);

} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $leagueName = $data['league_name'] ?? '';
    $schoolName = $data['school_name'] ?? '';
    $played = intval($data['played'] ?? 0);
    $won = intval($data['won'] ?? 0);
    $drawn = intval($data['drawn'] ?? 0);
    $lost = intval($data['lost'] ?? 0);
    $points = intval($data['points'] ?? 0);

    if (!$leagueName || !$schoolName) {
        echo json_encode(['success' => false, 'message' => 'Lig adı ve okul adı gerekli']);
        exit;
    }

    $sql = "INSERT INTO leagues (league_name, school_name, played, won, drawn, lost, points)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            played = VALUES(played),
            won = VALUES(won),
            drawn = VALUES(drawn),
            lost = VALUES(lost),
            points = VALUES(points),
            updated_at = CURRENT_TIMESTAMP";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$leagueName, $schoolName, $played, $won, $drawn, $lost, $points]);

    echo json_encode(['success' => true, 'message' => 'Lig verisi kaydedildi']);

} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'] ?? 0;

    $stmt = $pdo->prepare("DELETE FROM leagues WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Lig verisi silindi']);
}
?>
