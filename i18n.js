// TR/EN dil desteği — metin düğümlerini sözlükle eşleştirip yerinde çevirir.
// Yeni metin eklerken: Türkçesi sayfadaki metinle BİREBİR aynı olmalı (trim edilmiş hali).
(function () {
    'use strict';

    var PAIRS = [
        // Nav + logo
        ["Ana Sayfa", "Home"],
        ["Proje Hakkında", "About the Project"],
        ["Bölgeler", "Regions"],
        ["Kültürel Yolculuk", "Cultural Journey"],
        ["Okullar", "Schools"],
        ["Performans Göstergeleri", "Performance Indicators"],
        ["Ligler", "Leagues"],
        ["ZipTide Araçları", "ZipTide Tools"],
        ["Anadolu'nun Mirası", "Anatolia's Heritage"],

        // Hero
        ["7 Bölge, 7 Okul, 4 Yıllık Kültür Yolculuğu", "7 Regions, 7 Schools, a 4-Year Cultural Journey"],
        ["Anadolu'nun kültürel mirasını araştıran dört yıllık bir okul gelişim projesi", "A four-year school development project exploring Anatolia's cultural heritage"],
        ["Projeyi Keşfet", "Explore the Project"],
        ["Aşağı Kaydır", "Scroll Down"],
        ["Kapadokya — İç Anadolu", "Cappadocia — Central Anatolia"],
        ["Efes — Ege Bölgesi", "Ephesus — Aegean Region"],
        ["Pamukkale — Ege Bölgesi", "Pamukkale — Aegean Region"],
        ["Nemrut Dağı — Güneydoğu Anadolu", "Mount Nemrut — Southeastern Anatolia"],
        ["İstanbul — Marmara Bölgesi", "Istanbul — Marmara Region"],
        ["Sümela — Karadeniz Bölgesi", "Sümela — Black Sea Region"],

        // Proje bölümü
        ["Projenin Künyesi ve Ortakları", "Project Profile and Partners"],
        ["7 Bölge, 7 Kültür, 7 Okul", "7 Regions, 7 Cultures, 7 Schools"],
        ["Anadolu'nun Büyüsüne Yolculuk", "A Journey into Anatolia's Magic"],
        ["7 ortak okulun büyüleyici Anadolu kültürel yolculuğu — 7 bölgeden 7 okul, Anadolu'nun eşsiz mirasını birlikte keşfediyor.", "The enchanting Anatolian cultural journey of 7 partner schools — 7 schools from 7 regions exploring Anatolia's unique heritage together."],
        ["4 Yıllık Kültürel Yolculuk", "4-Year Cultural Journey"],
        ["2025-2029 yılları arasında gastronomi, müzik, halk oyunları ve sözlü gelenek", "Gastronomy, music, folk dances and oral tradition between 2025 and 2029"],

        // Yol haritası
        ["4 Yıllık Proje Yol Haritası", "4-Year Project Roadmap"],
        ["7 Okul, 4 Yıl, 7 Bölge - Anadolu'nun Kültürel Mirasını Keşfediyoruz", "7 Schools, 4 Years, 7 Regions - Exploring Anatolia's Cultural Heritage"],
        ["1. YIL", "YEAR 1"],
        ["2. YIL", "YEAR 2"],
        ["3. YIL", "YEAR 3"],
        ["4. YIL", "YEAR 4"],
        ["🍽️ Gastronomi", "🍽️ Gastronomy"],
        ["🎵 Müzik", "🎵 Music"],
        ["💃 Halk Oyunları", "💃 Folk Dances"],
        ["📖 Sözlü Gelenek & Yayın", "📖 Oral Tradition & Publishing"],
        ["Anadolu'nun zengin mutfak kültürü ve yemek gelenekleri araştırılıyor", "Researching Anatolia's rich culinary culture and food traditions"],
        ["Geleneksel Türk müziği, halk ezgileri ve yerel çalgılar inceleniyor", "Studying traditional Turkish music, folk melodies and local instruments"],
        ["Bölgesel halk oyunları, kostümler ve dans gelenekleri keşfediliyor", "Exploring regional folk dances, costumes and dance traditions"],
        ["Masallar, destanlar, atasözleri derleniyor ve dijital platform yayınlanıyor", "Compiling tales, epics and proverbs, and publishing the digital platform"],
        ["Ege", "Aegean"],
        ["Akdeniz", "Mediterranean"],
        ["İç Anadolu", "Central Anatolia"],
        ["Karadeniz", "Black Sea"],
        ["Doğu Anadolu", "Eastern Anatolia"],
        ["G. Doğu Anadolu", "SE Anatolia"],

        // Kültürel yolculuk zaman çizelgesi
        ["2025-2029 Arasında Gerçekleşecek Kültürel Keşif Programı", "A Cultural Discovery Program Running from 2025 to 2029"],
        ["YIL 1 (2025-2026)", "YEAR 1 (2025-2026)"],
        ["YIL 2 (2026-2027)", "YEAR 2 (2026-2027)"],
        ["YIL 3 (2027-2028)", "YEAR 3 (2027-2028)"],
        ["YIL 4 (2028-2029)", "YEAR 4 (2028-2029)"],
        ["GASTRONOMİ", "GASTRONOMY"],
        ["MÜZİK", "MUSIC"],
        ["HALK OYUNLARI", "FOLK DANCES"],
        ["SÖZLÜ GELENEK & YAYIN", "ORAL TRADITION & PUBLISHING"],
        ["Yöresel mutfak mirası araştırılacak, atölyeler ve tadım etkinlikleri düzenlenecek.", "Local culinary heritage will be researched; workshops and tasting events will be held."],
        ["Bölgesel Gastronomi Mirasını Araştırma", "Researching Regional Gastronomic Heritage"],
        ["Yöresel Yemek Atölyeleri", "Local Cuisine Workshops"],
        ["Çoklu tarif kitapçığı", "Joint recipe booklet"],
        ["Yöresel müzik kültürü ve enstrümanlar belgelenecek, konserler verilecek.", "Local music culture and instruments will be documented; concerts will be given."],
        ["Bölgesel Müzik Kültürünün İzini Sürmek ve Belgelemek", "Tracing and Documenting Regional Music Culture"],
        ["Müzik Atölyeleri (Enstrüman/Ses Eğitimi)", "Music Workshops (Instrument/Voice Training)"],
        ["Çoklu Yöresel Müzik Konseri", "Joint Regional Music Concert"],
        ["Yöresel halk oyunları araştırılıp sahnelencek, şölenler düzenlenecek.", "Regional folk dances will be researched and staged; festivals will be held."],
        ["Halk Oyunları Mirasını Sahnelendirmek", "Staging the Folk Dance Heritage"],
        ["Eğitim Atölyeleri (Figür ve Kostüm Tasarımları)", "Training Workshops (Figure and Costume Design)"],
        ["Çoklu Yöresel Halk Oyunları Şöleni", "Joint Regional Folk Dance Festival"],
        ["Masal ve efsaneler derlenip proje kitabı basılarak kalıcı bir eser oluşturulacak.", "Tales and legends will be compiled and the project book printed as a lasting work."],
        ["Sözlü Gelenekler Derlemesi (Masal/Hikaye)", "Oral Tradition Collection (Tales/Stories)"],
        ["Dijital Folklor ve Bilim Animasyonu", "Digital Folklore and Science Animation"],
        ["Çoklu Ders Yılı Proje Kitabının Yayımlanması (ISBN/ISSN)", "Publishing the Multi-Year Project Book (ISBN/ISSN)"],

        // Proje kapsamı ve yapısı
        ["Proje Kapsamı ve Yapısı", "Project Scope and Structure"],
        ["Anadolu'nun İrsiyılı Mirasını \"7 Bölge + 7 Kültür\" Projesi", "The \"7 Regions + 7 Cultures\" Project on Anatolia's Ancestral Heritage"],
        ["Proje Kapısı ve Kapsamı Bilgileri", "Project Identity and Scope Details"],
        ["Proje Tema", "Project Type"],
        ["Okul Geliştirme Projesi", "School Development Project"],
        ["Proje Süresi", "Project Duration"],
        ["4 Yıl (2025-2029)", "4 Years (2025-2029)"],
        ["Koordinatör Kurum", "Coordinating Institution"],
        ["Genel Amaç", "Overall Goal"],
        ["7 Bölge+7 Kültür Mirasını Araştırmak, Belgelemek ve Genç Kuşak Aktarmak", "To Research, Document and Pass On the 7 Regions + 7 Cultures Heritage to Young Generations"],
        ["Numaralı Hedefe ve Vizile Temalar", "Yearly Themes and Goals"],
        ["Yıl 1", "Year 1"],
        ["Yıl 2", "Year 2"],
        ["Yıl 3", "Year 3"],
        ["Yıl 4", "Year 4"],
        ["Bölgesel Gastronomi Mirasını Araştırmak, Belgelemek ve Genç Kuşaklara Aktarmak", "Researching, Documenting and Passing On Regional Gastronomic Heritage to Young Generations"],
        ["Yöresel Yemek Yapım Atölyeleri", "Local Cooking Workshops"],
        ["Çıktı:", "Output:"],
        ["Tarif Kitapçığı", "Recipe Booklet"],
        ["Yöresel Müzik Konseri", "Regional Music Concert"],
        ["Yöresel Halk Oyunları Şöleni", "Regional Folk Dance Festival"],
        ["Proje Kitabının Yayımlanması (ISBN/ISSN)", "Publishing the Project Book (ISBN/ISSN)"],

        // Performans
        ["📊 Performans Göstergeleri", "📊 Performance Indicators"],
        ["Her okulun proje sürecindeki katkıları ve performans metrikleri", "Each school's contributions and performance metrics throughout the project"],
        ["📈 Performans Metrikleri Nasıl Hesaplanır?", "📈 How Are Performance Metrics Calculated?"],
        ["Etkinlik Skoru", "Event Score"],
        ["Düzenlenen etkinlik sayısı × 10 puan", "Number of events held × 10 points"],
        ["Katılım Skoru", "Participation Score"],
        ["Toplam katılımcı sayısı ÷ 10", "Total participants ÷ 10"],
        ["İçerik Skoru", "Content Score"],
        ["Paylaşılan içerik sayısı × 5 puan", "Number of shared items × 5 points"],
        ["Koordinatör Bonusu", "Coordinator Bonus"],
        ["Koordinatör okul +50 puan", "Coordinator school +50 points"],

        // Ligler
        ["Spor Ligleri", "Sports Leagues"],
        ["7 Bölge 7 Okul Kızlar Voleybol ve Erkekler Basketbol Ligi puan durumları", "7 Regions 7 Schools Girls' Volleyball and Boys' Basketball League standings"],
        ["🏐 Kızlar Voleybol Ligi", "🏐 Girls' Volleyball League"],
        ["🏀 Erkekler Basketbol Ligi", "🏀 Boys' Basketball League"],
        ["🏐 7 Okul 7 Bölge Kızlar Voleybol Ligi", "🏐 7 Schools 7 Regions Girls' Volleyball League"],
        ["🏀 7 Okul 7 Bölge Erkekler Basketbol Ligi", "🏀 7 Schools 7 Regions Boys' Basketball League"],
        ["Sıra", "Rank"],
        ["Okul", "School"],
        ["O", "P"],
        ["G", "W"],
        ["B", "D"],
        ["M", "L"],
        ["P", "Pts"],
        ["= Oynanan", "= Played"],
        ["= Galibiyet", "= Won"],
        ["= Beraberlik", "= Drawn"],
        ["= Mağlubiyet", "= Lost"],
        ["= Puan", "= Points"],
        ["Yükleniyor...", "Loading..."],

        // iGEM & ZipTide
        ["Bilimsel Dönüşüm — ZipTide (iGEM 2026)", "Scientific Transformation — ZipTide (iGEM 2026)"],
        ["GİKAL iGEM 2026 takımının", "The GİKAL iGEM 2026 team's"],
        ["projesi, 7 Bölge 7 Okul'un kültürel mirasına bilimsel bir mercek kazandırdı: geleneksel tarifler artık hem bağırsak sağlığı hem gezegen açısından okunabiliyor.", "project gave the cultural heritage of 7 Regions 7 Schools a scientific lens: traditional recipes can now be read for both gut health and the planet."],
        ["Bilimsel bir katman", "A scientific layer"],
        ["Sentetik biyoloji takımımız, bağırsak bariyerini hedefleyen ZipTide peptidini geliştirirken 7 Bölge'nin 49 geleneksel tarifini bilimsel bir çerçevede yeniden okudu. Böylece kültürel bir arşiv, analiz edilebilir bir veri setine dönüştü.", "While developing the gut-barrier-targeting ZipTide peptide, our synthetic biology team re-read the 7 Regions' 49 traditional recipes in a scientific framework. A cultural archive thus became an analyzable dataset."],
        ["İki eksenli skorlama", "Two-axis scoring"],
        ["Her tarif iki bağımsız eksende değerlendirildi: bağırsak-bariyeri desteği ve sürdürülebilirlik. Şaşırtıcı bulgu — bir yemeğin sağlıklı olması, sürdürülebilir olduğu anlamına gelmiyor. Bu yüzden iki ayrı ölçü tutuyoruz.", "Each recipe was scored on two independent axes: gut-barrier support and sustainability. The surprising finding — a dish being healthy does not mean it is sustainable. That is why we keep two separate measures."],
        ["Keşif araçları", "Discovery tools"],
        ["Aynı 49-tarif verisinden bir etkileşimli gösterge paneli ve bir Türkiye haritası doğdu. Bu araçlar tarifleri hem sağlık hem gezegen açısından keşfetmeyi sağlıyor ve platformumuzda yaşıyorlar.", "From the same 49-recipe dataset came an interactive dashboard and a map of Turkey. These tools let you explore the recipes for both health and the planet, and they live on our platform."],
        ["Dürüst bir çerçeve", "An honest framework"],
        ["Bu skorlar keşifsel bir farkındalık aracıdır, tıbbi tavsiye değildir. Yemekler bir hastalığı \"iyileştirmez\"; literatürde yalnızca bariyer sağlığıyla ilişkilendirilir. Bölge puanları da o bölgede yaşayanların sağlığı hakkında bir şey söylemez.", "These scores are an exploratory awareness tool, not medical advice. Foods do not \"cure\" a disease; in the literature they are only associated with barrier health. Regional scores say nothing about the health of the people living there."],
        ["Not:", "Note:"],
        ["ZipTide, geleneksel mutfağı bilimsel bir merceğe taşıyan bir iGEM 2026 projesidir. Buradaki skorlar keşifsel bir çerçevedir; tıbbi tavsiye, tanı veya tedavi amacı taşımaz.", "ZipTide is an iGEM 2026 project that brings traditional cuisine under a scientific lens. The scores here are an exploratory framework; they are not medical advice, diagnosis or treatment."],
        ["Kaynak Kod", "Source Code"],
        ["GİKAL · iGEM 2026 · Team #6490 · ZipTide × 7 Bölge 7 Okul", "GİKAL · iGEM 2026 · Team #6490 · ZipTide × 7 Regions 7 Schools"],

        // ZipTide Araçları
        ["ZipTide Keşif Araçları", "ZipTide Discovery Tools"],
        ["49 geleneksel tarifi bağırsak sağlığı ve sürdürülebilirlik açısından keşfet. Aynı veriden doğan etkileşimli gösterge paneli ve Türkiye haritası burada.", "Explore 49 traditional recipes through gut health and sustainability. The interactive dashboard and Turkey map born from the same data are here."],
        ["Her bölge, tariflerinin ortalama puanıyla renklendirilmiştir. Skorlar keşifseldir; tıbbi veri değildir.", "Each region is colored by the mean score of its recipes. Scores are exploratory; not medical data."],
        ["7 Bölge × ZipTide — bölgesel gut-destek ve sürdürülebilirlik haritası", "7 Regions × ZipTide — regional gut-support and sustainability map"],
        ["Etkileşimli Gösterge Paneli", "Interactive Dashboard"],
        ["49 tarifi iki eksende keşfet: sırala, çeyreklere göz at, sürdürülebilirlik ve bağırsak-destek ilişkisini gör.", "Explore the 49 recipes on two axes: sort, browse the quadrants, and see how sustainability relates to gut support."],
        ["Paneli Aç", "Open the Dashboard"],
        ["Bölgesel Harita", "Regional Map"],
        ["7 bölgenin gut-destek ve sürdürülebilirlik ortalamaları, Türkiye haritası üzerinde.", "The 7 regions' gut-support and sustainability averages on a map of Turkey."],
        ["Tam Boyut", "Full Size"],
        ["GİKAL · iGEM 2026 · Team #6490 · ZipTide × 7 Bölge 7 Okul — skorlar keşifsel bir çerçevedir, tıbbi tavsiye değildir.", "GİKAL · iGEM 2026 · Team #6490 · ZipTide × 7 Regions 7 Schools — scores are an exploratory framework, not medical advice."],

        // Fermuar (20.10.2026)
        ["Fermuar — ZipTide'ın Mobil Uygulaması", "Fermuar — ZipTide's Mobile App"],
        ["Fermuar, bağırsak bariyerini sofra diliyle anlatan bir mobil uygulama ve GİKAL iGEM 2026 takımının ZipTide projesinin halka dönük yüzü. 7 Bölge 7 Okul kitabındaki 49 tarif, uygulamanın Sofra bölümünde yaşıyor.", "Fermuar (Turkish for \"zipper\") is a mobile app that explains the gut barrier in the language of the dinner table, and the public-facing side of the GİKAL iGEM 2026 team's ZipTide project. The 49 recipes of the 7 Regions 7 Schools cookbook live in the app's Table section."],
        ["Uygulama bitmek üzere. Önümüzdeki günlerde Google Play (Android) ve App Store (iOS) üzerinden yayımlanacak. Uygulama verileri iGEM değerlendirme (judging) döneminde paylaşılacak.", "The app is nearly finished. It will be released on Google Play (Android) and the App Store (iOS) in the coming days. App data will be shared during the iGEM judging period."],
        ["Fermuar uygulamasının kapak görseli ve üç ekranı", "The Fermuar app's cover image and three of its screens"],
        ["Fermuar tanı koymaz, tedavi önermez; insanlara bağırsak bariyeri hakkında anlaşılır bir kelime dağarcığı verir.", "Fermuar does not diagnose and does not recommend treatment; it gives people clear words for the gut barrier."],
        ["Uygulamanın dört bölümü", "The app's four sections"],
        ["Keşfet", "Explore"],
        ["Parmakla sürüklenen \"günün fermuarı\", puansız \"günün sorusu\" ve üç kısa öğrenme modülü: altyazılı video, dokununca açılan sözlük, dokunarak keşfedilen şemalar ve puan tutmayan bir sınav.", "A \"zipper of the day\" you drag with your finger, an unscored \"question of the day\" and three short learning modules: subtitled video, a tap-to-open glossary, diagrams explored by touch and a quiz that keeps no score."],
        ["Sofra", "Table"],
        ["7 Bölge 7 Okul kitabındaki 49 tarif, bariyer gözüyle. Her etiketin notu PubMed'de doğrulanmış bir kaynağa dayanır; puanların hesabı açıktır. Etiket Oku, bir paketin içindekiler listesini sözlükle eşler; Sofra Nehri birikimi hedef koymadan gösterir.", "The 49 recipes of the 7 Regions 7 Schools cookbook, read through the barrier. Each tag's note rests on a PubMed-verified source and every score shows its calculation. Label Reader matches a package's ingredient list to the glossary; Table River shows what accumulates, with no target."],
        ["Ben", "Me"],
        ["Fermuar koleksiyonu ve kişisel defter. Defter yalnız telefonda kalır; hiçbir sunucuya gönderilmez.", "A zipper collection and a personal journal. The journal stays on the phone and is never sent to a server."],
        ["Topluluk", "Community"],
        ["Bu durumu yaşayanların deneyim paylaştığı alan: takma ad, 18 yaş sınırı, KVKK açık rızası, bildirme, engelleme ve moderasyon. iGEM takımlarının buluştuğu bir takım alanı da var.", "A space where people living with these conditions share experiences: nicknames, an 18+ age limit, explicit consent under Turkish data protection law (KVKK), reporting, blocking and moderation. There is also a space where iGEM teams can meet."],
        ["Fikir nereden çıktı?", "Where did the idea come from?"],
        ["Uygulamanın fikri, birbirinden bağımsız üç paydaşın aynı boşluğu göstermesinden doğdu.", "The idea came from three independent stakeholders pointing at the same gap."],
        ["Gölpazarı halk anketi", "Gölpazarı public survey"],
        ["21 Haziran 2026, 10 kişi. Farkındalık yüksek, kelime dağarcığı düşüktü; katılımcılar ZipTide'ı probiyotikle karıştırıyordu.", "21 June 2026, 10 people. Awareness was high but vocabulary was low; participants confused ZipTide with probiotics."],
        ["Diyetisyen görüşmesi", "Interview with a dietitian"],
        ["Diyetisyen Göç: hastalarının 6-7/10'u bağırsak şikâyetiyle geliyor, ancak yalnız 3/10'u doğru bilgiye sahip.", "Dietitian Göç: 6–7 in 10 of her patients come with gut complaints, but only 3 in 10 have accurate information."],
        ["Gıda mühendisi görüşmesi", "Interview with a food engineer"],
        ["Gıda mühendisi Uçar: \"eğitimli bilinçsizler\" ve sosyal medyadaki bilgi kirliliği.", "Food engineer Uçar: \"educated but uninformed\" people, and misinformation on social media."],
        ["Üç kaynağın vardığı sonuç aynıydı: sorun ilgisizlik değil, dil. Fermuar bu yüzden bir tavsiye aracı olarak değil, bir kelime dağarcığı aracı olarak tasarlandı.", "All three reached the same conclusion: the problem is not a lack of interest, it is language. That is why Fermuar was built as a vocabulary tool rather than an advice tool."],
        ["Neye dayanarak hazırlandı?", "What is it based on?"],
        ["Uygulamadaki her özellik bir saha dönütüne dayanıyor. Keşfet, Sofra ve Ben bölümleri Eylül 2026'daki 36 halk anketi ve 6 danışma görüşmesinden; Topluluk bölümü çölyak derneği aracılığıyla yapılan 20 kişilik anketten; fermuar metaforu takımın \"ZipTide ve Altın Fermuar\" masalından geliyor. Veri setleri birbirinden ayrı tutuldu.", "Every feature traces back to field feedback. The Explore, Table and Me sections rest on 36 public surveys and 6 consultation interviews from September 2026; the Community section on a 20-person survey run through a celiac association; the zipper metaphor comes from the team's own story, \"ZipTide and the Golden Zipper\". The data sets were kept separate."],
        ["Fermuar uygulamasından on ekran", "Ten screens from the Fermuar app"],
        ["Ekran görüntüleri uygulamanın kendi derlemesinden, telefon ölçüsünde alındı. Takma ad, defter ve topluluk kayıtları örnek veridir.", "Screenshots were taken from the app's own build at phone size. The nickname, journal and community entries are sample data. The app's interface is in Turkish."],
        ["Fermuar tanı koymaz, tedavi ya da beslenme tavsiyesi vermez. Uygulama \"şunu ye\" demez; bir besinin bariyerle ilişkisini yalnız literatürde ilişkilendirildiği ölçüde ve kaynağıyla anlatır.", "Fermuar does not diagnose and gives no treatment or nutrition advice. It never says \"eat this\"; it describes a food's link to the barrier only as far as the literature associates them, and always with a source."],
        ["iGEM Wiki'de Fermuar", "Fermuar on the iGEM Wiki"],
        ["Ekranları Büyüt", "Enlarge the Screens"],
        ["GİKAL · iGEM 2026 · Team #6490 · ZipTide × 7 Bölge 7 Okul · Fermuar", "GİKAL · iGEM 2026 · Team #6490 · ZipTide × 7 Regions 7 Schools · Fermuar"],

        // International Bridge
        ["7 Bölge 7 Okul artık uluslararası bir kültür köprüsü: Almanya ve Bosna Hersek'ten ortak okullar projeye katıldı.", "7 Regions 7 Schools is now an international cultural bridge: partner schools from Germany and Bosnia and Herzegovina have joined the project."],
        ["🇩🇪 Almanya", "🇩🇪 Germany"],
        ["Almanya'dan ortaklarımız 7 Bölge 7 Okul ailesine katıldı; Anadolu'nun kültürel mirası artık Avrupa'da da keşfediliyor.", "Our partners from Germany have joined the 7 Regions 7 Schools family; Anatolia's cultural heritage is now being explored in Europe as well."],
        ["🇧🇦 Bosna Hersek", "🇧🇦 Bosnia and Herzegovina"],
        ["Bosna Hersek'ten ortaklarımızla paylaştığımız köklü tarihî ve kültürel bağlar, projeye yeni bir derinlik katıyor.", "The deep historical and cultural ties we share with our partners from Bosnia and Herzegovina add a new depth to the project."],
        ["Kültür Köprüsü", "Cultural Bridge"],
        ["Ulusal bir kültür yolculuğu, uluslararası bir buluşmaya dönüşüyor: ortak etkinlikler, karşılıklı öğrenme ve kültür paylaşımı.", "A national cultural journey is becoming an international meeting point: joint events, mutual learning and cultural exchange."],
        ["3B Kültür Küresi", "3D Culture Globe"],
        ["7 bölgeyi, 49 tarifi ve uluslararası ortaklarımızı dönen bir dünya üzerinde keşfet.", "Explore the 7 regions, 49 recipes and our international partners on a spinning globe."],
        ["Küreyi Aç", "Open the Globe"],

        // Footer
        ["Hızlı Linkler", "Quick Links"],
        ["İletişim", "Contact"],
        ["Tel: 0216 355 56 69", "Phone: 0216 355 56 69"],
        ["© 2025 Anadolu'nun Mirası. Tüm hakları saklıdır.", "© 2025 Anatolia's Heritage. All rights reserved."]
    ];

    var TITLES = {
        tr: "Anadolu'nun Mirası - 7 Bölge, 7 Okul, 4 Yıllık Kültür Yolculuğu",
        en: "Anatolia's Heritage - 7 Regions, 7 Schools, a 4-Year Cultural Journey"
    };

    var TR2EN = new Map(), EN2TR = new Map();
    PAIRS.forEach(function (p) {
        if (p[0] !== p[1]) { TR2EN.set(p[0], p[1]); EN2TR.set(p[1], p[0]); }
    });

    var ATTRS = ["placeholder", "title", "alt", "aria-label"];
    var lang = "tr";
    try { lang = localStorage.getItem("site_lang") || "tr"; } catch (e) {}

    function swapNode(node, map) {
        var raw = node.nodeValue;
        if (!raw) return;
        var trimmed = raw.trim();
        if (!trimmed) return;
        var rep = map.get(trimmed);
        if (rep !== undefined) node.nodeValue = raw.replace(trimmed, rep);
    }

    function swapTree(root, map) {
        var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null);
        var n;
        while ((n = walker.nextNode())) swapNode(n, map);
        if (root.querySelectorAll) {
            root.querySelectorAll("[placeholder],[title],[alt],[aria-label]").forEach(function (el) {
                ATTRS.forEach(function (a) {
                    var v = el.getAttribute(a);
                    if (v) {
                        var rep = map.get(v.trim());
                        if (rep !== undefined) el.setAttribute(a, rep);
                    }
                });
            });
        }
    }

    // Dile göre değişen görsel/bağlantı (ör. ZipTide bölgesel haritası TR/EN sürümü)
    function swapAssets(target) {
        document.querySelectorAll("[data-src-tr],[data-href-tr]").forEach(function (el) {
            var src = el.getAttribute("data-src-" + target);
            if (src) el.setAttribute("src", src);
            var href = el.getAttribute("data-href-" + target);
            if (href) el.setAttribute("href", href);
        });
    }

    function apply(target) {
        var map = target === "en" ? TR2EN : EN2TR;
        swapTree(document.body, map);
        swapAssets(target);
        document.documentElement.lang = target;
        document.title = TITLES[target] || document.title;
        lang = target;
        try { localStorage.setItem("site_lang", target); } catch (e) {}
        var btn = document.getElementById("lang-toggle");
        if (btn) {
            btn.textContent = target === "en" ? "TR" : "EN";
            btn.setAttribute("title", target === "en" ? "Türkçe'ye geç" : "Switch to English");
        }
    }

    // AJAX ile sonradan eklenen içerik de (ör. lig tablosu "Yükleniyor...") çevrilsin
    var observer = new MutationObserver(function (muts) {
        if (lang !== "en") return;
        muts.forEach(function (m) {
            m.addedNodes.forEach(function (node) {
                if (node.nodeType === 3) swapNode(node, TR2EN);
                else if (node.nodeType === 1) swapTree(node, TR2EN);
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        var btn = document.getElementById("lang-toggle");
        if (btn) {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                apply(lang === "en" ? "tr" : "en");
            });
        }
        observer.observe(document.body, { childList: true, subtree: true });
        if (lang === "en") apply("en");
    });
})();
