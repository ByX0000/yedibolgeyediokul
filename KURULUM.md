# Anadolu'nun Mirası - Kurulum Kılavuzu

## 📋 Gereksinimler

- **PHP 7.4+** (PHP 8.0 önerilir)
- **MySQL 5.7+** veya **MariaDB 10.2+**
- **Apache** web sunucusu (mod_rewrite etkin)
- **XAMPP**, **WAMP** veya **LAMP** (önerilir)

## 🚀 Kurulum Adımları

### 1. XAMPP İndirme ve Kurma

1. **XAMPP'i indirin:** https://www.apachefriends.org/
2. Kurulumu tamamlayın
3. XAMPP Control Panel'i açın

### 2. Proje Dosyalarını Kopyalama

1. `D:\İNDİRİLENLER\denemeproje` klasörünün tamamını kopyalayın
2. `C:\xampp\htdocs\` klasörüne yapıştırın
3. Klasör adını `anadolunun-mirasi` olarak değiştirin (opsiyonel)

**Son konum:** `C:\xampp\htdocs\anadolunun-mirasi\`

### 3. Veritabanını Oluşturma

1. XAMPP Control Panel'den **Apache** ve **MySQL**'i başlatın
2. Tarayıcıda `http://localhost/phpmyadmin` adresine gidin
3. Sol menüden **"Yeni"** (New) butonuna tıklayın
4. Veritabanı adı: `anadolunun_mirasi`
5. Karakter seti: `utf8mb4_turkish_ci`
6. **Oluştur** butonuna tıklayın

### 4. Tabloları İçe Aktarma

1. Sol menüden `anadolunun_mirasi` veritabanını seçin
2. Üst menüden **"İçe Aktar" (Import)** sekmesine tıklayın
3. **"Dosya Seç"** butonuna tıklayın
4. `C:\xampp\htdocs\anadolunun-mirasi\database.sql` dosyasını seçin
5. **"Git"** (Go) butonuna tıklayın
6. ✅ "İçe aktarım başarıyla tamamlandı" mesajını görmelisiniz

### 5. Uploads Klasörü İzinleri

Windows'ta genellikle otomatik çalışır, ancak sorun yaşarsanız:

1. `C:\xampp\htdocs\anadolunun-mirasi\uploads` klasörüne sağ tıklayın
2. **Özellikler** → **Güvenlik** sekmesi
3. **Everyone** kullanıcısına **Tam Denetim** verin

### 6. PHP Ayarları (Opsiyonel)

Büyük dosyalar yüklemek için `C:\xampp\php\php.ini` dosyasını düzenleyin:

```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
max_input_time = 300
```

Değişiklikleri kaydedip Apache'yi yeniden başlatın.

### 7. Veritabanı Bağlantı Ayarları

`upload.php` dosyasını açın ve gerekirse değiştirin:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // XAMPP varsayılan şifre boş
define('DB_NAME', 'anadolunun_mirasi');
```

## 🌐 Siteyi Açma

### Ana Sayfa
```
http://localhost/anadolunun-mirasi/index.html
```

### Admin Paneli
```
http://localhost/anadolunun-mirasi/admin.html
```

**Giriş Bilgileri:**
- Kullanıcı Adı: `admin`
- Şifre: `anadolu2025`

## 📁 Dosya Yapısı

```
anadolunun-mirasi/
├── index.html          # Ana sayfa
├── admin.html          # Admin paneli
├── upload.php          # Dosya yükleme ve API
├── database.sql        # Veritabanı şeması
├── .htaccess           # Apache ayarları
├── styles.css          # Stil dosyası
├── script.js           # JavaScript
├── schools/            # Okul detay sayfaları
│   ├── goztepe-ihsan-kursunoglu.html
│   ├── teb-atasehir.html
│   ├── ataturk-fen.html
│   ├── kadir-has.html
│   ├── kadikoy.html
│   ├── erenkoy-kiz.html
│   └── hayrullah-kefoglu.html
└── uploads/            # Yüklenen dosyalar (otomatik oluşur)
```

## ✅ Kurulum Testi

1. **Ana sayfayı açın:**
   ```
   http://localhost/anadolunun-mirasi/index.html
   ```
   ✅ Sayfa düzgün yüklenmeli

2. **Admin paneline giriş yapın:**
   ```
   http://localhost/anadolunun-mirasi/admin.html
   ```
   ✅ Giriş sayfası görünmeli

3. **Dosya yükleme testi:**
   - Admin paneline giriş yapın
   - İçerik Paylaşımı bölümüne gidin
   - Bir fotoğraf seçip yükleyin
   - ✅ "İçerik başarıyla eklendi" mesajını görmelisiniz

## 🔧 Sorun Giderme

### "Veritabanı bağlantı hatası"
- MySQL'in çalıştığından emin olun (XAMPP Control Panel)
- `upload.php` dosyasındaki veritabanı ayarlarını kontrol edin

### "Dosya yüklenemedi"
- `uploads` klasörünün var olduğundan emin olun
- Klasör izinlerini kontrol edin
- `php.ini` ayarlarını kontrol edin

### "404 Not Found"
- Proje klasörünün `htdocs` altında olduğundan emin olun
- Apache'nin çalıştığından emin olun

### Sayfa açılmıyor
- XAMPP Control Panel'de Apache ve MySQL'in çalıştığından emin olun
- URL'yi kontrol edin: `http://localhost/anadolunun-mirasi/index.html`

## 📞 Destek

Sorun yaşarsanız:
1. `uploads` klasörünün oluştuğundan emin olun
2. Tarayıcı konsolunda hata mesajlarını kontrol edin (F12)
3. Apache error log'larını kontrol edin: `C:\xampp\apache\logs\error.log`

## 🎉 Tamamlandı!

Artık projeniz hazır! Admin panelinden içerik ekleyebilir, okulların detay sayfalarında paylaşımları görebilirsiniz.
