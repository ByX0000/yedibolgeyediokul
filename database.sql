-- Veritabanı oluştur
CREATE DATABASE IF NOT EXISTS anadolunun_mirasi CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci;

USE anadolunun_mirasi;

-- Paylaşılan içerikler tablosu
CREATE TABLE IF NOT EXISTS shared_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL COMMENT 'photo, video, article, event',
    school VARCHAR(255) NOT NULL,
    year VARCHAR(10) NOT NULL COMMENT '1, 2, 3, 4',
    date DATE NOT NULL,
    title VARCHAR(255) NOT NULL,
    url VARCHAR(500) DEFAULT NULL COMMENT 'Dosya yolu',
    description TEXT DEFAULT NULL,
    author VARCHAR(255) DEFAULT NULL,
    content TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_school (school),
    INDEX idx_year (year),
    INDEX idx_type (type),
    INDEX idx_date (date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Bölgeler tablosu
CREATE TABLE IF NOT EXISTS regions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    color VARCHAR(7) NOT NULL,
    description TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Okullar tablosu
CREATE TABLE IF NOT EXISTS schools (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    region VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    students INT DEFAULT NULL,
    address TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Etkinlikler tablosu
CREATE TABLE IF NOT EXISTS activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    school VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    participants INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_school (school),
    INDEX idx_date (date),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Galeri tablosu
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(500) NOT NULL,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- İletişim mesajları tablosu
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan 7 bölgeyi ekle
INSERT INTO regions (name, color, description) VALUES
('Marmara Bölgesi', '#D76A6A', 'Türkiye\'nin kuzeybatısında yer alan, sanayi ve kültür merkezi'),
('Ege Bölgesi', '#C792EA', 'Batı Anadolu\'nun bereketli toprakları ve antik medeniyetler'),
('Akdeniz Bölgesi', '#FFD54F', 'Güney kıyıları boyunca uzanan, tarım ve turizm bölgesi'),
('İç Anadolu Bölgesi', '#FF9F4F', 'Anadolu\'nun kalbi, başkent ve step kültürü'),
('Karadeniz Bölgesi', '#66BB6A', 'Kuzey kıyıları, yemyeşil doğası ve zengin folkloru'),
('Doğu Anadolu Bölgesi', '#81C4E8', 'Yüksek platoları ve köklü kültürel mirası'),
('Güneydoğu Anadolu Bölgesi', '#BC8F8F', 'Mezopotamya mirası ve zengin gastronomi kültürü');

-- Varsayılan 7 okulu ekle
INSERT INTO schools (name, region, city, students, address) VALUES
('Göztepe İhsan Kurşunoğlu Anadolu Lisesi', 'Marmara Bölgesi', 'Bilecik', 500, 'Bilecik'),
('TEB Ataşehir Anadolu Lisesi', 'Ege Bölgesi', 'İzmir', 450, 'İzmir'),
('Atatürk Fen Lisesi', 'Akdeniz Bölgesi', 'Antalya', 600, 'Antalya'),
('Kadir Has Anadolu Lisesi', 'İç Anadolu Bölgesi', 'Ankara', 550, 'Ankara'),
('Kadıköy Anadolu Lisesi', 'Karadeniz Bölgesi', 'Trabzon', 400, 'Trabzon'),
('Erenköy Kız Anadolu Lisesi', 'Doğu Anadolu Bölgesi', 'Elazığ', 350, 'Elazığ'),
('Hayrullah Kefoğlu Anadolu Lisesi', 'Güneydoğu Anadolu Bölgesi', 'Gaziantep', 480, 'Gaziantep');
