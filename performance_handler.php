<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . (getenv('CORS_ALLOWED_ORIGINS') ?: '*'));
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'db_config.php';

// Okulların performans verilerini veritabanından hesapla
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Tüm okulları al
    $stmt = $pdo->query("SELECT * FROM schools ORDER BY id ASC");
    $schools = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $performanceData = [];

    foreach ($schools as $school) {
        $schoolName = $school['name'];

        // Etkinlik sayısını al
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM activities WHERE school = ?");
        $stmt->execute([$schoolName]);
        $activityCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Toplam katılımcı sayısını al
        $stmt = $pdo->prepare("SELECT COALESCE(SUM(participants), 0) as total FROM activities WHERE school = ?");
        $stmt->execute([$schoolName]);
        $participantCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // İçerik sayısını al (shared_content tablosundan)
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM shared_content WHERE school = ?");
        $stmt->execute([$schoolName]);
        $contentCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Koordinatör okul mu kontrol et
        $isCoordinator = (strpos($school['name'], 'Göztepe') !== false);

        // Performans skoru hesapla
        $activityScore = $activityCount * 10;
        $participantScore = floor($participantCount / 10);
        $contentScore = $contentCount * 5;
        $coordinatorBonus = $isCoordinator ? 50 : 0;
        $totalScore = $activityScore + $participantScore + $contentScore + $coordinatorBonus;

        // Bölge adını belirle
        $regionName = str_replace(' Bölgesi', '', $school['region']);

        $performanceData[] = [
            'school' => $schoolName,
            'region' => $regionName,
            'activities' => (int)$activityCount,
            'participants' => (int)$participantCount,
            'content' => (int)$contentCount,
            'totalScore' => $totalScore,
            'activityScore' => $activityScore,
            'participantScore' => $participantScore,
            'contentScore' => $contentScore,
            'coordinatorBonus' => $coordinatorBonus,
            'isCoordinator' => $isCoordinator
        ];
    }

    // En yüksek skordan sırala
    usort($performanceData, function($a, $b) {
        return $b['totalScore'] - $a['totalScore'];
    });

    echo json_encode(['success' => true, 'data' => $performanceData]);
}
?>
