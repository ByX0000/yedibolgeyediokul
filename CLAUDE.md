# CLAUDE.md — 7 Bölge 7 Okul web sitesi

> Bu dosyayı deponun **köküne** `CLAUDE.md` adıyla koy. Repo'da Claude entegrasyonu
> (`.github/workflows/claude.yml`) zaten kurulu olduğu için Claude bu dosyayı otomatik okur ve
> değişiklikleri buradaki kurallara göre yapar.
> Repo: `github.com/ByX0000/yedibolgeyediokul` · Platform: 7 Bölge 7 Okul · Dil: **Türkçe**.

---

## 1. Bu repo nedir

7 Bölge 7 Okul projesinin **topluluk web platformu**. Tam-yığın (full-stack):
- **Ön yüz:** tek sayfa `index.html` (bölümlere kaydırmalı navigasyon) + `admin.html` (yönetim paneli).
- **Arka uç:** PHP handler'lar + **MySQL** veritabanı.
- **Barındırma:** Docker; production = **Hetzner** sunucusu (GitHub Actions ile otomatik deploy).

Bu site, iGEM/ZipTide projesinin çıktılarını (dashboard, oyun, kitap) barındıran **"kalıcı dijital ev"dir**.
iGEM yarışma kodu AYRI bir repoda (`gitlab.igem.org/2026/software/gikal`, statik). İkisini karıştırma.

---

## 2. Nasıl çalışır (yerel + production)

**Yerel geliştirme (Docker):**
```bash
docker compose up        # → http://localhost:8080
```
`.env.example`'ı `.env`'e kopyalayıp DB ve admin bilgilerini doldur (KURULUM.md).

**Production (Hetzner):**
- `master`'a **her push → `.github/workflows/deploy.yml` → rsync ile Hetzner'e** (bkz. §7).

---

## 3. Site yapısı

**`index.html` tek sayfadır.** Navigasyon `<nav class="navbar">` içindeki `<ul class="nav-menu">`,
her link bir bölüme (`#id`) kaydırır. Bölümler (sıra önemli — nav sırası = bölüm sırası):

| # id | Nav etiketi | İçerik |
|---|---|---|
| `home` | Ana Sayfa | Hero |
| `project` | Proje Hakkında | Künye, ortaklar |
| `regions` | Bölgeler | 7 bölge yol haritası |
| `journey` | Kültürel Yolculuk | — |
| `schools` | Okullar | Katılan okullar |
| `international` | International Bridge | Almanya + Bosna Hersek katılımı |
| `performance` | Performans Göstergeleri | — |
| `leagues` | Ligler | Okullar-arası lig |
| `ziptide` | iGEM & ZipTide | **Bilimsel dönüşüm (§6 kurallarına tabi)** |
| `araclar` | ZipTide Araçları | Dashboard + harita (§6 kurallarına tabi) |

Nav'ın en sonunda **EN/TR dil düğmesi** (`#lang-toggle`) durur — bölüm linki değildir.
**Kaldırılan bölümler:** `forum` (arka uç `forum_handler.php` + DB duruyor, yalnız sayfadan çıkarıldı)
ve `contact` (iletişim bilgileri footer'da yaşıyor). Geri eklemek istersen git geçmişinde (`6f3b9ad` öncesi).

**Dosya haritası (kaba):**
- `index.html` · `admin.html` — sayfalar
- `styles.css` (ana), `performance-styles.css` — stiller
- `script.js` — nav, hamburger menü, etkileşimler
- `i18n.js` — TR/EN dil desteği (metin sözlüğü; aşağıda §5-D)
- `ziptide/` — `dashboard.html` (49 tarifin etkileşimli paneli, Chart.js CDN), `harita.png` (bölgesel harita)
- `*_handler.php` (forum, gallery, activities, league, regions, schools, performance, school_info) — AJAX uç noktaları
- `admin_login.php`, `upload.php` — kimlik/dosya yükleme
- `db_config.php`, `database.sql`, `create_database.php` — veritabanı
- `Dockerfile`, `docker-compose.yml`, `docker/` — konteyner
- `.github/workflows/` — `deploy.yml` (Hetzner), `claude.yml` (Claude entegrasyonu)

---

## 4. Tasarım sistemi (değişiklik yaparken buna uy)

**Renk değişkenleri (`styles.css` `:root`):**
```
--primary-color:#c9302c   (kırmızı)     --secondary-color:#8b1e1e (koyu kırmızı)
--accent-color:#d4a574    (altın)       --text-dark:#2c3e50       --text-light:#7f8c8d
--bg-light:#f8f9fa        --white:#ffffff
```

**Yeniden kullanılabilir sınıflar (yeni içerik için yenisini yazma, bunları kullan):**
- `.container` — ortalanmış içerik sarmalayıcı
- `.section-header` — içinde `<h2>` + `<div class="underline"></div>`
- `.project-grid` — kart ızgarası; `.project-card` — tek kart
- `.card-icon` — SVG ikon (stroke stili: `viewBox="0 0 24 24" fill="none" stroke="currentColor"`)
- `.btn .btn-primary` — birincil buton

**Standart bölüm iskeleti (kopyala-uyarla):**
```html
<section id="YENI_ID" class="project-overview">
  <div class="container">
    <div class="section-header">
      <h2>Başlık</h2>
      <div class="underline"></div>
    </div>
    <div class="project-grid">
      <div class="project-card">
        <div class="card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">…</svg></div>
        <h3>Kart başlığı</h3>
        <p>Metin.</p>
      </div>
    </div>
  </div>
</section>
```
İkonlar için Lucide stili (stroke, 24×24) tercih et — sitenin geri kalanıyla tutarlı olur.

**Bilimsel katman yeşili:** `#ziptide` ve `#araclar` bölümlerinde kart ikonları, başlıklar,
underline ve butonlar **dashboard yeşili** (#1d9e75 / koyu #0f6e56) kullanır (scoped CSS,
`#ziptide` style bloğunda). Kırmızı = kültürel katman, yeşil = bilimsel katman. Bu ayrımı koru;
yeşili site geneline yayma.

**Navbar:** `max-width: 1800px`, linkler `white-space: nowrap` — geniş ekranda tek sıra,
dar ekranda düzgün sarar. Logo (`Anadolu'nun Mirası`) tek satırdır.

---

## 5. Yaygın değişiklikler — adım adım

**A. Yeni sekme + bölüm eklemek:**
1. `<ul class="nav-menu">` içine, doğru sıraya: `<li><a href="#YENI_ID">Etiket</a></li>`
2. Bölümü, nav sırasına denk gelen yere ekle (§4 iskeleti).
3. Gerekirse küçük özel CSS'i bölümün hemen üstünde `<style>#YENI_ID …</style>` ile scope'la
   (ya da `styles.css`'e ekle). Mümkünse mevcut sınıfları kullan, yeni CSS'ten kaçın.
4. Not: nav bazı linkleri **footer'da tekrar** eder; ana nav `<nav class="navbar">` altındakidir.

**B. Metin/içerik düzenlemek:** ilgili `#id` bölümünü bul, metni değiştir. Türkçe yaz.
**Sonra `i18n.js`'i güncelle** (bkz. D) — yoksa yeni metin EN modunda Türkçe kalır.

**C. Görsel eklemek:** `uploads/` veya uygun klasöre koy, göreli yolla (`./uploads/...`) referansla.
`uploads/` deploy'da **korunur** (silinmez).

**D. Dil desteği (`i18n.js`):** Site TR/EN'dir; nav sonundaki EN/TR düğmesi metin düğümlerini
`i18n.js`'teki `PAIRS` sözlüğüyle yerinde çevirir (localStorage'da kalıcı, dinamik içerik için
MutationObserver var). Kurallar:
- Sayfaya **metin eklediğinde/değiştirdiğinde** `PAIRS`'e `["Türkçesi", "İngilizcesi"]` çifti ekle.
- Anahtar, sayfadaki metnin **trim edilmiş haliyle birebir aynı** olmalı (satır içi `<strong>` gibi
  etiketler metni böler; her parça ayrı anahtar olur).
- Özel isimler (okul adları, şehirler) çevrilmez; dashboard zaten İngilizce'dir.

---

## 6. İçerik bütünlüğü kuralları (ZipTide/bilim içeriği — BOZULMAZ)

`#ziptide` ve iGEM/sağlık ile ilgili her metinde:
- **Tıbbi iddia yok.** "İyileştirir / tedavi eder / hastalığı geçirir" YAZMA. Doğrusu: literatürde
  bariyer sağlığıyla **"ilişkilendirilmiştir"**. Yemek ilaç değildir.
- **Ekolojik yanılgı yok.** Bir bölgenin puanı, orada yaşayanların sağlığı hakkında bir şey söylemez.
- **Disclaimer koru:** "keşifsel farkındalık aracı, tıbbi tavsiye değil".
- **Nesting = barındırma, yaratılış değil.** "iGEM ekibi 7B7O'ya bilimsel katman *kazandırdı*" de;
  "7B7O peptidi/iGEM'i yarattı" DEME (origin story: peptit önce → tariflere sonra uzandı).
- Skorlar **keşifsel çerçeve**; kesin bilimsel gerçek gibi sunma.

---

## 7. Deploy (canlıya alma)

- **Tetikleyici:** `master`'a push → `.github/workflows/deploy.yml` → **rsync ile Hetzner'e**.
- **Kontrol:** GitHub → **Actions** sekmesi. Yeşil ✓ = canlıda. Tarayıcıda **Ctrl+F5** (önbellek).
- **İlk kurulum (bir kez):** deploy secret'ları gerekli — `SSH_HOST`, `SSH_USER`, `SSH_PRIVATE_KEY`,
  `REMOTE_DIR` (+ ops. `SSH_PORT`). Repo → Settings → Secrets → Actions (bkz. `DEPLOY.md`).
- **Deploy EDİLMEYEN dosyalar:** `.git`, `.github`, `logs/`, `uploads/`, `.env*`, `docker/`,
  `Dockerfile`, `docker-compose.yml`, `*.md`, `create_database.php`. (Yani CLAUDE.md ve README sunucuya gitmez — sorun değil.)
- **Veritabanı değişimi** (schema): otomatik DEĞİL. SSH ile `mysql … < database.sql` elle çalıştırılır.

---

## 8. Güvenlik & gizlilik (dikkat)

- **`.env` ve secret'ları ASLA commit etme.** Sadece `.env.example` repoda durur.
- Forum/lig/upload özellikleri **kullanıcı verisi** tutar (MySQL). Herhangi bir sayfaya veya iGEM
  wiki'sine erişim/istatistik koyarken **kişisel veri paylaşma** (isim, e-posta, mesaj); yalnız
  toplam/anonim sayı. KVKK'ya dikkat.
- `upload.php` dosya türü/boyut sınırlarını korur — gevşetme.
- Admin paneli (`admin.html`, `admin_login.php`) herkese açık olmamalı.

---

## 9. iGEM ile ilişki (bağlam)

- Bu site = **7B7O platformu** (orta katman). iGEM/ZipTide = bilimsel motor (iç katman).
  Sustainable Development anlatısı = dış mercek. Ayrıntı: iGEM proje planında (`PLAN.md`).
- Site, iGEM çıktılarını (dashboard, oyun, v2 kitap) **barındırır**; iGEM wiki bunları buraya *linkler*.
- İki repo, iki iş: **bu repo = dinamik platform (PHP/Hetzner)**; iGEM wiki = statik (GitLab).

---

## 10. Tuzaklar (gotchas)

- Yeni bölüm eklerken **nav sırası = bölüm sırası** tut (kaydırma UX'i bozulmasın).
- Nav linkleri footer'da tekrar edebilir; değiştirirken ikisini de kontrol et.
- Mutlak asset yolu (`/uploads/..`) yerine **göreli** (`./uploads/..`) kullan.
- Büyük dosya yükleme = repo şişer; medyayı mümkünse `uploads/` (deploy'da korunan) üzerinden yönet.
- Değişiklikten sonra **Actions'ı kontrol et**; yeşil değilse canlı güncellenmemiştir.
- **CSS değiştirince** `index.html`'deki `styles.css?v=X.Y` sürümünü artır (önbellek kırma; şu an v=2.5).
- **Metin değiştirince** `i18n.js` sözlüğünü senkron tut (§5-D) — anahtarlar birebir eşleşmezse çeviri sessizce atlanır.
- **GitHub web'den dosya yüklerken** tarayıcı indirmeleri `ad (1).uzantı` diye yeniden adlandırır —
  yüklemeden önce adı düzelt (daha önce `index (1).html` ve `harita (1).png` böyle geldi).
- VS Code Source Control'ün yarım kalmış değişiklikleri commit'lemesine dikkat; push = anında canlı.

---

## 11. Değişiklik günlüğü

**01.08.2026 — büyük yenileme (Claude ile):**
- Yeni ana sayfa yayına alındı (`index (1).html` → `index.html`).
- Deploy hariç-tutmaları `*.md` kalıbına çevrildi (CLAUDE.md sunucuya gitmez).
- **ZipTide Araçları** sekmesi eklendi: `ziptide/dashboard.html` (49 tarif, gömülü veri, Chart.js CDN)
  + `ziptide/harita.png`. Strateji oyunu kartı şimdilik çıkarıldı — oyun hazır olunca
  `ziptide/game/` altına build edilip `#araclar`'a üçüncü kart olarak bağlanacak (statik build,
  `base: './'` ile).
- **Navbar yenilendi:** ZipTide sekmeleri sona taşındı, genişlik 1800px, tek sıra düzen,
  etiketlerde `nowrap`.
- **Kaldırılanlar:** EN Forum (bölüm + script; arka uç duruyor), İletişim bölümü (bilgiler footer'da).
- **Yeşil bilim vurgusu:** `#ziptide` + `#araclar` ikon/başlık/buton/underline dashboard yeşili.
- **TR/EN dil desteği:** `i18n.js` + nav'da EN/TR düğmesi (bkz. §5-D).
- **International Bridge** sekmesi eklendi (`#international`): Almanya 🇩🇪 ve Bosna Hersek 🇧🇦
  projeye katıldı. Ortak okulların adları henüz girilmedi — isimler netleşince kartlara eklenecek.
