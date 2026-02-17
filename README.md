# Anadolu'nun Mirası - Web Sitesi

## 7 Bölge, 7 Okul, 4 Yıllık Kültür Yolculuğu (2025-2029)

Türkiye'nin yedi coğrafi bölgesinden birer lise ile gerçekleştirilen kapsamlı kültürel miras projesi. Her yıl farklı bir temaya odaklanan 4 yıllık bir yolculuk.

> **Bölgesel Gastronomi Mirasını Araştırmak, Belgelemek ve Genç Kuşaklara Aktarmak**

## Proje Hakkında

**Koordinatör Kurum:** Göztepe İhsan Kurşunoğlu Anadolu Lisesi

**Proje Süresi:** 2025-2029 (4 Yıl)

### Katılımcı Okullar

| Bölge            | Okul Adı                                | Şehir     | Öğrenci | Tema Rengi |
| ---------------- | --------------------------------------- | --------- | ------- | ---------- |
| **Marmara**      | Göztepe İhsan Kurşunoğlu Anadolu Lisesi | Bilecik   | 500     | `#D76A6A`  |
| **Ege**          | TEB Ataşehir Anadolu Lisesi             | İzmir     | 450     | `#C792EA`  |
| **Akdeniz**      | Atatürk Fen Lisesi                      | Antalya   | 600     | `#FFD54F`  |
| **İç Anadolu**   | Kadir Has Anadolu Lisesi                | Ankara    | 550     | `#FF9F4F`  |
| **Karadeniz**    | Kadıköy Anadolu Lisesi                  | Trabzon   | 400     | `#66BB6A`  |
| **Doğu Anadolu** | Erenköy Kız Anadolu Lisesi              | Elazığ    | 350     | `#81C4E8`  |
| **Güneydoğu**    | Hayrullah Kefoğlu Anadolu Lisesi        | Gaziantep | 480     | `#BC8F8F`  |

---

## 4 Yıllık Kültürel Yolculuk

### Yıl 1 (2025-2026): Gastronomi

- Yöresel mutfak mirası araştırması
- Atölyeler ve tadım etkinlikleri

### Yıl 2 (2026-2027): Müzik

- Yöresel müzik kültürü ve enstrümanlar
- Konserler ve belgeleme

### Yıl 3 (2027-2028): Halk Oyunları

- Yöresel halk oyunları araştırması
- Şölenler ve performanslar

### Yıl 4 (2028-2029): Sözlü Gelenek & Yayın

- Masal ve efsaneler derlenmesi
- Proje kitabı basımı

---

## Teknoloji Altyapısı

### Frontend

- **HTML5** - Semantik yapı ve responsive tasarım
- **CSS3** - Modern animasyonlar, Grid ve Flexbox
- **JavaScript (ES6+)** - Dinamik içerik yönetimi ve Fetch API

### Backend

- **PHP 8.0+** - Modüler handler yapısı
- **MySQL 8.0** - utf8mb4_turkish_ci karakter seti
- **Apache** - mod_rewrite etkin
- **REST API** - JSON tabanlı veri servisleri, ayrı handler dosyaları

### Altyapı

- **Docker & Docker Compose** - Konteyner tabanlı dağıtım
- **Ortam değişkenleri** - `.env` dosyasıyla yapılandırma

### Güvenlik

- PDO Prepared Statements (SQL injection koruması)
- Bcrypt şifre hash (admin şifresi env var üzerinden)
- Dosya tipi ve boyut validasyonu (MIME type kontrolü)
- CORS headers yapılandırması

---

## Hızlı Başlangıç

### A) Docker ile Kurulum (Önerilen)

**1. Ortam dosyasını oluşturun**

```bash
cp .env.example .env
```

`.env` dosyasını açıp şifre alanlarını doldurun:

```
DB_PASS=guclu_sifreniz
MYSQL_ROOT_PASSWORD=root_sifreniz
MYSQL_PASSWORD=guclu_sifreniz
ADMIN_PASSWORD_HASH=bcrypt_hash
```

Admin şifresi için bcrypt hash oluşturmak:

```bash
php -r "echo password_hash('sifreniz', PASSWORD_BCRYPT);"
```

**2. Konteynerleri başlatın**

```bash
docker-compose up -d
```

**3. Siteye erişin**

```
Ana Sayfa:     http://localhost:8080
Admin Paneli:  http://localhost:8080/admin.html
phpMyAdmin:    http://localhost:8081  (sadece dev profili)
```

phpMyAdmin'i dev modunda açmak için:

```bash
docker-compose --profile dev up -d
```

---

### B) XAMPP ile Kurulum

**Gereksinimler:**

```
PHP 8.0+
MySQL 5.7+ / MariaDB 10.2+
Apache (mod_rewrite etkin)
XAMPP / WAMP / LAMP
```

**1. Projeyi kopyalayın**

```bash
cp -r yedibolgeyediokul/ C:/xampp/htdocs/anadolunun-mirasi/
```

**2. Veritabanını oluşturun**

- `http://localhost/phpmyadmin` adresine gidin
- `anadolunun_mirasi` adında yeni veritabanı oluşturun (`utf8mb4_turkish_ci`)
- `database.sql` dosyasını içe aktarın (Import)

**3. Siteye erişin**

```
Ana Sayfa:     http://localhost/anadolunun-mirasi/index.html
Admin Paneli:  http://localhost/anadolunun-mirasi/admin.html
```

**Detaylı kurulum için:** [KURULUM.md](KURULUM.md)

---

## Admin Paneli

### Giriş

Kimlik bilgileri `.env` dosyasındaki `ADMIN_USERNAME` ve `ADMIN_PASSWORD_HASH` ortam değişkenleriyle yönetilir.

Varsayılan kullanıcı adı: `admin`

### Özellikler

- **Dashboard** - İstatistikler (içerik, okul, etkinlik sayıları)
- **Okul Yönetimi** - CRUD işlemleri
- **Bölge Yönetimi** - Renk ve bilgi düzenleme
- **İçerik Paylaşımı** - Fotoğraf, video, yazı, etkinlik ekleme
- **Etkinlik Yönetimi** - Etkinlik takibi
- **Galeri Yönetimi** - Medya yönetimi
- **Lig Tablosu** - Okul arası yarışma sıralamaları
- **Performans Tablosu** - Okulların etkinlik/içerik bazlı puanlama
- **Okul Bilgi Sayfaları** - Her okul için zengin içerik (metin, görsel, video)
- **Gelen Kutusu** - İletişim mesajlarını görüntüleme ve yönetme
- **Veri İçe/Dışa Aktarma** - JSON yedekleme

### Dosya Yükleme

- **Formatlar:** JPG, PNG, GIF, MP4, WebM, OGG
- **Maksimum boyut:** 20MB
- **Otomatik isimlendirme:** `uniqid_timestamp.ext`

---

## Proje Yapısı

```
anadolunun-mirasi/
├── index.html                      # Ana sayfa
├── admin.html                      # Admin paneli
├── admin_login.php                 # Admin giriş backend
├── db_config.php                   # Veritabanı bağlantısı (env'den okur)
├── create_database.php             # Veritabanı tablolarını oluştur
├── upload.php                      # Dosya yükleme API
├── database.sql                    # Veritabanı şeması + başlangıç verileri
├── .htaccess                       # Apache yapılandırması
├── .env.example                    # Ortam değişkenleri şablonu
├── styles.css                      # Global stil dosyası
├── performance-styles.css          # Performans tablosu stilleri
├── script.js                       # Ana JavaScript
├── README.md                       # Bu dosya
├── KURULUM.md                      # Detaylı kurulum kılavuzu
│
├── Handler Dosyaları (REST API)
│   ├── activities_handler.php      # Etkinlikler CRUD
│   ├── gallery_handler.php         # Galeri CRUD
│   ├── league_handler.php          # Lig tablosu CRUD
│   ├── performance_handler.php     # Performans puanlama (GET)
│   ├── regions_handler.php         # Bölgeler CRUD
│   ├── school_info_handler.php     # Okul bilgi sayfaları CRUD
│   └── schools_handler.php         # Okullar CRUD
│
├── schools/                        # Okul detay sayfaları
│   ├── goztepe-ihsan-kursunoglu.html   # Marmara - İstanbul
│   ├── teb-atasehir.html               # Ege - İzmir
│   ├── ataturk-fen.html                # Akdeniz - Antalya
│   ├── kadir-has.html                  # İç Anadolu - Ankara
│   ├── kadikoy.html                    # Karadeniz - Trabzon
│   ├── erenkoy-kiz.html                # Doğu Anadolu - Elazığ
│   └── hayrullah-kefoglu.html          # Güneydoğu - Gaziantep
│
├── docker/                         # Docker yapılandırması
│   ├── apache/vhost.conf           # Apache virtual host
│   ├── mysql/my.cnf                # MySQL ayarları
│   └── php/php.ini                 # PHP ayarları
│
├── Dockerfile                      # PHP+Apache imajı
├── docker-compose.yml              # Servis orkestrasyon
│
├── uploads/                        # Yüklenen medya dosyaları
└── logs/                           # Uygulama logları
```

---

## API Endpoints

### Dosya Yükleme

```
POST   /upload.php                          Dosya yükle (multipart/form-data)
```

### Okullar

```
GET    /schools_handler.php                 Tüm okulları listele
POST   /schools_handler.php                 Okul ekle
PUT    /schools_handler.php                 Okul güncelle
DELETE /schools_handler.php                 Okul sil
```

### Bölgeler

```
GET    /regions_handler.php                 Tüm bölgeleri listele
POST   /regions_handler.php                 Bölge ekle/güncelle
DELETE /regions_handler.php                 Bölge sil
```

### İçerik Paylaşımı

```
GET    /upload.php?school=...               Okula ait içerikleri getir
POST   /upload.php (JSON)                   İçerik kaydet
DELETE /upload.php                          İçerik sil
```

### Etkinlikler

```
GET    /activities_handler.php              Tüm etkinlikleri listele
POST   /activities_handler.php              Etkinlik ekle
DELETE /activities_handler.php              Etkinlik sil
```

### Galeri

```
GET    /gallery_handler.php                 Galeri öğelerini listele
POST   /gallery_handler.php                 Galeri öğesi ekle
DELETE /gallery_handler.php                 Galeri öğesi sil
```

### Lig Tablosu

```
GET    /league_handler.php                  Tüm ligleri listele
GET    /league_handler.php?league=...       Belirli ligi getir
POST   /league_handler.php                  Lig verisi ekle/güncelle (upsert)
DELETE /league_handler.php                  Lig verisi sil
```

### Performans

```
GET    /performance_handler.php             Okulların performans puanlarını hesapla
```

Puanlama formülü: `(Etkinlik × 10) + (Katılımcı / 10) + (İçerik × 5) + (Koordinatör bonusu: 50)`

### Okul Bilgi Sayfaları

```
GET    /school_info_handler.php?school=...  Okula ait bilgi içeriklerini getir
POST   /school_info_handler.php             İçerik ekle/güncelle (upsert)
DELETE /school_info_handler.php             İçerik sil
```

---

## Veritabanı Şeması

### `shared_content` - Paylaşılan İçerikler

```sql
id, type (photo/video/article/event), school, year (1-4),
date, title, url, description, author, content, created_at, updated_at
```

### `regions` - Bölgeler

```sql
id, name, color, description, created_at
```

### `schools` - Okullar

```sql
id, name, region, city, students, address, created_at
```

### `activities` - Etkinlikler

```sql
id, name, school, date, category, description, participants, created_at
```

### `gallery` - Galeri

```sql
id, url, title, category, created_at
```

### `contact_messages` - İletişim Mesajları

```sql
id, name, email, subject, message, is_read, created_at
```

### `leagues` - Lig Tablosu

```sql
id, league_name, school_name, played, won, drawn, lost, points,
created_at, updated_at
UNIQUE KEY (league_name, school_name)
```

### `school_info` - Okul Bilgi Sayfası İçerikleri

```sql
id, school_name, info_type, content (TEXT), images (JSON), videos (JSON),
created_at, updated_at
UNIQUE KEY (school_name, info_type)
```

---

## Güvenlik Özellikleri

- **PDO Prepared Statements** - SQL injection koruması
- **Bcrypt şifre hash** - Admin şifresi ortam değişkeniyle yönetilir
- **MIME Type Kontrolü** - Sadece izin verilen dosya tipleri
- **Dosya Boyut Limiti** - Maksimum 20MB
- **Güvenli Dosya Adları** - `uniqid()` + timestamp
- **Session Timeout** - Yapılandırılabilir (`SESSION_LIFETIME` env var)
- **CORS Headers** - `CORS_ALLOWED_ORIGINS` env var ile kontrol

---

## Renk Paleti

### Bölge Renkleri

```
Marmara:          #D76A6A  (Kırmızımsı)
Ege:              #C792EA  (Mor)
Akdeniz:          #FFD54F  (Sarı)
İç Anadolu:       #FF9F4F  (Turuncu)
Karadeniz:        #66BB6A  (Yeşil)
Doğu Anadolu:     #81C4E8  (Açık Mavi)
Güneydoğu:        #BC8F8F  (Kahverengi)
```

---

## Sorun Giderme

### Docker - Konteyner başlamıyor

```
.env dosyasındaki şifre alanlarını doldurduğunuzdan emin olun.
docker-compose logs app   → PHP/Apache logları
docker-compose logs mysql → MySQL logları
```

### Veritabanı bağlantı hatası (XAMPP)

```
XAMPP Control Panel'de MySQL'in çalıştığından emin olun.
db_config.php ortam değişkenlerini veya doğrudan bağlantı ayarlarını kontrol edin.
PhpMyAdmin'de anadolunun_mirasi veritabanının oluştuğunu doğrulayın.
```

### Dosya yüklenemiyor

```
uploads/ klasörünün var olduğunu ve yazma izninin açık olduğunu kontrol edin.
.env içindeki UPLOAD_MAX_SIZE değerini kontrol edin (varsayılan: 20MB).
Docker: docker exec anadolu_app chmod 777 /var/www/html/uploads
```

### 404 Not Found (XAMPP)

```
Proje klasörünün C:/xampp/htdocs/ altında olduğundan emin olun.
URL: http://localhost/anadolunun-mirasi/index.html
```

---

## İletişim

**Koordinatör Kurum:**
Göztepe İhsan Kurşunoğlu Anadolu Lisesi

**Adres:**
Tanzimat Sok. No. 55
Göztepe, Kadıköy / İstanbul

**Telefon:**
0216 355 56 69

**Web Sitesi:**
https://gikal.meb.k12.tr

---

## Eğitim Hedefleri

- Kültürel mirası araştırma ve belgeleme
- Öğrencilerde kültürel farkındalık oluşturma
- Bölgeler arası işbirliği geliştirme
- Proje tabanlı öğrenme deneyimi sağlama
- Kalıcı eğitim materyalleri oluşturma

---

**© 2025 Anadolu'nun Mirası - 7 Bölge, 7 Okul, 4 Yıllık Kültür Yolculuğu**

Bu proje eğitim amaçlı oluşturulmuştur.
