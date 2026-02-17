<?php
header('Content-Type: application/json');

// CORS ayarları - ortam değişkenlerinden oku
$allowedOrigins = getenv('CORS_ALLOWED_ORIGINS') ?: '*';
header('Access-Control-Allow-Origin: ' . $allowedOrigins);
header('Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// OPTIONS preflight isteği
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Veritabanı bağlantı ayarları - Ortam değişkenlerinden oku
define('DB_HOST',    getenv('DB_HOST') ?: 'localhost');
define('DB_PORT',    getenv('DB_PORT') ?: '3306');
define('DB_USER',    getenv('DB_USER') ?: 'root');
define('DB_PASS',    getenv('DB_PASS') ?: '');
define('DB_NAME',    getenv('DB_NAME') ?: 'anadolunun_mirasi');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// Upload klasörü
define('UPLOAD_DIR', getenv('UPLOAD_DIR') ?: __DIR__ . '/uploads/');

// Veritabanı bağlantısı
function getDB() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $conn = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        return $conn;
    } catch(PDOException $e) {
        error_log("DB Connection Error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Sunucu hatası. Lütfen daha sonra tekrar deneyin.']);
        exit;
    }
}

// Upload klasörünü oluştur (güvenli izinler)
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0775, true);
}

// REQUEST METHOD
$method = $_SERVER['REQUEST_METHOD'];

// Dosya yükleme
if ($method === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Dosya kontrolü - ortam değişkenlerinden oku
    $allowedTypesEnv = getenv('ALLOWED_FILE_TYPES') ?: 'image/jpeg,image/png,image/gif,video/mp4,video/webm,video/ogg';
    $allowed = array_map('trim', explode(',', $allowedTypesEnv));
    $maxSize = (int)(getenv('UPLOAD_MAX_SIZE') ?: 20971520); // varsayılan 20MB

    if (!in_array($file['type'], $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz dosya tipi. Sadece resim ve video dosyaları yüklenebilir.']);
        exit;
    }

    if ($file['size'] > $maxSize) {
        echo json_encode(['success' => false, 'message' => 'Dosya çok büyük. Maksimum 50MB yüklenebilir.']);
        exit;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Dosya yükleme hatası: ' . $file['error']]);
        exit;
    }

    // Güvenli dosya adı oluştur
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = UPLOAD_DIR . $filename;

    // Dosyayı kaydet
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $url = '/uploads/' . $filename;

        echo json_encode([
            'success' => true,
            'message' => 'Dosya başarıyla yüklendi',
            'url' => $url,
            'filename' => $filename
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Dosya kaydedilemedi']);
    }
    exit;
}

// İçerik kaydetme
if ($method === 'POST' && !isset($_FILES['file'])) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz veri']);
        exit;
    }

    // İletişim mesajı kaydetme
    if (isset($data['action']) && $data['action'] === 'contact') {
        try {
            $db = getDB();

            $stmt = $db->prepare("
                INSERT INTO contact_messages
                (name, email, subject, message, created_at)
                VALUES
                (:name, :email, :subject, :message, NOW())
            ");

            $stmt->execute([
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':subject' => $data['subject'],
                ':message' => $data['message']
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Mesajınız başarıyla gönderildi'
            ]);

        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        exit;
    }

    // Mesajı okundu olarak işaretle
    if (isset($data['action']) && $data['action'] === 'mark_read' && isset($data['id'])) {
        try {
            $db = getDB();
            $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = :id");
            $stmt->execute([':id' => $data['id']]);

            echo json_encode([
                'success' => true,
                'message' => 'Mesaj okundu olarak işaretlendi'
            ]);
            exit;
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
            exit;
        }
    }

    // Normal içerik kaydetme
    try {
        $db = getDB();

        $stmt = $db->prepare("
            INSERT INTO shared_content
            (type, school, year, date, title, url, description, author, content, created_at)
            VALUES
            (:type, :school, :year, :date, :title, :url, :description, :author, :content, NOW())
        ");

        $stmt->execute([
            ':type' => $data['type'],
            ':school' => $data['school'],
            ':year' => $data['year'],
            ':date' => $data['date'],
            ':title' => $data['title'],
            ':url' => $data['url'] ?? '',
            ':description' => $data['description'] ?? '',
            ':author' => $data['author'] ?? '',
            ':content' => $data['content'] ?? ''
        ]);

        $id = $db->lastInsertId();

        echo json_encode([
            'success' => true,
            'message' => 'İçerik başarıyla kaydedildi',
            'id' => $id
        ]);

    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
    exit;
}

// İçerikleri listeleme
if ($method === 'GET') {
    $action = $_GET['action'] ?? null;

    // İletişim mesajlarını listeleme
    if ($action === 'get_messages') {
        try {
            $db = getDB();
            $stmt = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'data' => $messages
            ]);

        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        exit;
    }

    // Okunmamış mesaj sayısı
    if ($action === 'unread_count') {
        try {
            $db = getDB();
            $stmt = $db->query("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'count' => $result['count']
            ]);

        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        exit;
    }

    $school = $_GET['school'] ?? null;

    try {
        $db = getDB();

        if ($school) {
            $stmt = $db->prepare("SELECT * FROM shared_content WHERE school = :school ORDER BY created_at DESC");
            $stmt->execute([':school' => $school]);
        } else {
            $stmt = $db->query("SELECT * FROM shared_content ORDER BY created_at DESC");
        }

        $contents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data' => $contents
        ]);

    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
    exit;
}

// İçerik silme ve mesaj işlemleri
if ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;
    $action = $data['action'] ?? null;

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID gerekli']);
        exit;
    }

    try {
        $db = getDB();

        // Mesaj silme
        if ($action === 'delete_message') {
            $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = :id");
            $stmt->execute([':id' => $id]);

            echo json_encode([
                'success' => true,
                'message' => 'Mesaj başarıyla silindi'
            ]);
            exit;
        }

        // Önce dosya bilgisini al
        $stmt = $db->prepare("SELECT url FROM shared_content WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $content = $stmt->fetch(PDO::FETCH_ASSOC);

        // Dosyayı sil
        if ($content && $content['url']) {
            $filepath = __DIR__ . $content['url'];
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }

        // Veritabanından sil
        $stmt = $db->prepare("DELETE FROM shared_content WHERE id = :id");
        $stmt->execute([':id' => $id]);

        echo json_encode([
            'success' => true,
            'message' => 'İçerik başarıyla silindi'
        ]);

    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Geçersiz istek']);
?>
