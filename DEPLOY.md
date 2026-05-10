# Hetzner'e Deploy

`master` dalına her push'ta `.github/workflows/deploy.yml` çalışır ve `rsync` ile Hetzner sunucunuza dosyaları gönderir.

## 1. SSH anahtarı oluştur (yerel makinenizde, bir kere)

```bash
ssh-keygen -t ed25519 -f ~/.ssh/hetzner_deploy -N ""
```

İki dosya çıkar:
- `~/.ssh/hetzner_deploy` → **özel anahtar** (GitHub secret olarak)
- `~/.ssh/hetzner_deploy.pub` → **public key** (sunucuya kurulur)

## 2. Public key'i Hetzner sunucusuna ekle

### Hetzner Cloud (VPS) için
SSH ile bağlanın ve public key'i `authorized_keys`'e ekleyin:
```bash
ssh kullanici@SUNUCU_IP
mkdir -p ~/.ssh && chmod 700 ~/.ssh
echo "ssh-ed25519 AAAA... (hetzner_deploy.pub içeriği)" >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

### Hetzner Webhosting (KonsoleH) için
- KonsoleH paneli → **SSH** sekmesi → **SSH erişimini etkinleştir**
- **SSH Anahtarları** kısmına `hetzner_deploy.pub` içeriğini yapıştırın
- SSH bağlantısı için sunucu adresi: `ssh.your-server.de` (paneldeki bilgiyi kullanın)

## 3. GitHub Secrets ekle

Repo → **Settings → Secrets and variables → Actions → New repository secret**:

| Secret | Açıklama | Örnek |
| --- | --- | --- |
| `SSH_HOST` | Sunucu adresi | `123.45.67.89` veya `ssh.your-server.de` |
| `SSH_USER` | SSH kullanıcısı | `root` (Cloud) veya hosting kullanıcı adı |
| `SSH_PORT` *(opsiyonel)* | Varsayılan `22`. Webhosting için genelde `23` | `23` |
| `SSH_PRIVATE_KEY` | `~/.ssh/hetzner_deploy` dosyasının **tüm içeriği** (BEGIN/END satırları dahil) | `-----BEGIN OPENSSH PRIVATE KEY-----...` |
| `REMOTE_DIR` | Hedef klasör (sonunda `/`) | `/var/www/html/` (Cloud) veya `/public_html/` |

## 4. Deploy'u çalıştır

- **Otomatik:** `master`'a push yapın (PR merge yeterli).
- **Manuel:** Repo → **Actions → Deploy to Hetzner → Run workflow**.

İlerlemeyi **Actions** sekmesinden takip edin.

## 5. Veritabanı (sadece ilk kez veya schema değişiminde)

Sunucudaki phpMyAdmin'den veya MySQL CLI ile `database.sql` içindeki `forum_topics` ve `forum_replies` tablolarını oluşturun. Workflow `create_database.php`'yi sunucuya kopyalamaz.

```bash
# SSH ile bağlıyken:
mysql -u KULLANICI -p VERITABANI < /tmp/database.sql
```

## Hariç tutulanlar

`.git`, `.github`, `logs/`, `uploads/`, `.env*`, `docker/`, `Dockerfile`, `docker-compose.yml`, `KURULUM.md`, `DEPLOY.md`, `README.md`, `create_database.php` deploy edilmez.

`uploads/` hariç tutulur ki sunucudaki yüklenmiş dosyalar silinmesin. (`rsync --delete` kullanılır; sunucuda fazladan dosya bırakmaz, ancak hariç tutulan klasörlere dokunmaz.)
