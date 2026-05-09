# Sunucuya Deploy

`master` dalına her push edildiğinde site otomatik olarak FTP üzerinden sunucuya gönderilir (`.github/workflows/deploy.yml`).

## 1. GitHub Secrets ekle

Repo → **Settings → Secrets and variables → Actions → New repository secret**:

| Secret | Açıklama | Örnek |
| --- | --- | --- |
| `FTP_SERVER` | Sunucu adresi | `ftp.siteniz.com` |
| `FTP_USERNAME` | FTP kullanıcı adı | `kullanici@siteniz.com` |
| `FTP_PASSWORD` | FTP parolası | `********` |
| `FTP_SERVER_DIR` | Hedef klasör (sonunda `/`) | `/public_html/` |
| `FTP_PROTOCOL` *(opsiyonel)* | `ftps` (önerilir) veya `ftp` | `ftps` |
| `FTP_PORT` *(opsiyonel)* | Varsayılan `21` | `21` |

## 2. Deploy'u tetikle

- **Otomatik:** `master`'a push.
- **Manuel:** Repo → **Actions → Deploy to FTP → Run workflow**.

## 3. Veritabanı

İlk deploy'dan sonra veya `database.sql` değiştiğinde sunucuda phpMyAdmin'den:

- `forum_topics`
- `forum_replies`

tablolarını oluşturmak için `database.sql`'in ilgili `CREATE TABLE` bloklarını çalıştırın. Alternatif olarak `php create_database.php` (workflow tarafından deploy edilmez).

## Hariç tutulanlar

`.git`, `.github`, `logs/`, `uploads/`, `.env*`, `docker/`, `Dockerfile`, `docker-compose.yml`, `KURULUM.md`, `README.md`, `create_database.php` deploy edilmez.
