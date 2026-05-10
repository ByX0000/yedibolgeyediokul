<?php
// Veritabanı ve tabloları oluştur

$host     = getenv('DB_HOST') ?: 'localhost';
$port     = getenv('DB_PORT') ?: '3306';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$dbname   = getenv('DB_NAME') ?: 'anadolunun_mirasi';

try {
    // Önce veritabanı olmadan bağlan
    $pdo = new PDO("mysql:host=$host;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Veritabanını oluştur
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Veritabanı '$dbname' oluşturuldu veya zaten mevcut.\n\n";

    // Veritabanını seç
    $pdo->exec("USE `$dbname`");

    // schools tablosu
    $pdo->exec("CREATE TABLE IF NOT EXISTS `schools` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `region` VARCHAR(100) NOT NULL,
        `city` VARCHAR(100) NOT NULL,
        `coordinator_name` VARCHAR(255),
        `coordinator_email` VARCHAR(255),
        `coordinator_phone` VARCHAR(50),
        `address` TEXT,
        `is_coordinator` BOOLEAN DEFAULT FALSE,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✅ Tablo 'schools' oluşturuldu.\n";

    // school_info tablosu
    $pdo->exec("CREATE TABLE IF NOT EXISTS `school_info` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `school_name` VARCHAR(255) NOT NULL,
        `info_type` VARCHAR(50) NOT NULL,
        `content` TEXT,
        `images` TEXT,
        `videos` TEXT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY `unique_school_info` (`school_name`, `info_type`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✅ Tablo 'school_info' oluşturuldu.\n";

    // forum_topics tablosu
    $pdo->exec("CREATE TABLE IF NOT EXISTS `forum_topics` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `author` VARCHAR(100) NOT NULL,
        `school` VARCHAR(255) DEFAULT NULL,
        `category` VARCHAR(100) DEFAULT 'Genel',
        `content` TEXT NOT NULL,
        `reply_count` INT DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX `idx_category` (`category`),
        INDEX `idx_created_at` (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✅ Tablo 'forum_topics' oluşturuldu.\n";

    // forum_replies tablosu
    $pdo->exec("CREATE TABLE IF NOT EXISTS `forum_replies` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `topic_id` INT NOT NULL,
        `author` VARCHAR(100) NOT NULL,
        `school` VARCHAR(255) DEFAULT NULL,
        `content` TEXT NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX `idx_topic_id` (`topic_id`),
        FOREIGN KEY (`topic_id`) REFERENCES `forum_topics`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✅ Tablo 'forum_replies' oluşturuldu.\n";

    // contact_messages tablosu
    $pdo->exec("CREATE TABLE IF NOT EXISTS `contact_messages` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255) NOT NULL,
        `subject` VARCHAR(255) NOT NULL,
        `message` TEXT NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✅ Tablo 'contact_messages' oluşturuldu.\n";

    // Örnek okul verileri ekle
    $checkSchool = $pdo->query("SELECT COUNT(*) FROM schools")->fetchColumn();

    if ($checkSchool == 0) {
        $pdo->exec("INSERT INTO `schools` (`name`, `region`, `city`, `coordinator_name`, `is_coordinator`) VALUES
            ('Göztepe İhsan Kurşunoğlu Anadolu Lisesi', 'Marmara', 'Bilecik', 'Koordinatör Adı', TRUE),
            ('Ege Bölgesi Okulu', 'Ege', 'İzmir', '', FALSE),
            ('Akdeniz Bölgesi Okulu', 'Akdeniz', 'Antalya', '', FALSE),
            ('İç Anadolu Bölgesi Okulu', 'İç Anadolu', 'Ankara', '', FALSE),
            ('Karadeniz Bölgesi Okulu', 'Karadeniz', 'Trabzon', '', FALSE),
            ('Doğu Anadolu Bölgesi Okulu', 'Doğu Anadolu', 'Erzurum', '', FALSE),
            ('Güneydoğu Anadolu Bölgesi Okulu', 'Güneydoğu Anadolu', 'Gaziantep', '', FALSE)
        ");
        echo "✅ Örnek okul verileri eklendi.\n";
    } else {
        echo "ℹ️  Okul verileri zaten mevcut.\n";
    }

    echo "\n🎉 Tüm işlemler başarıyla tamamlandı!\n";
    echo "\n📋 Oluşturulan Tablolar:\n";
    echo "   - schools (Okul bilgileri)\n";
    echo "   - school_info (Okul detay bilgileri)\n";
    echo "   - forum_topics (EN Forum konuları)\n";
    echo "   - forum_replies (EN Forum cevapları)\n";
    echo "   - contact_messages (İletişim mesajları)\n";

} catch(PDOException $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
?>
