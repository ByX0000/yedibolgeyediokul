// Mobile Menu Toggle
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

if (hamburger && navMenu) {
    hamburger.addEventListener('click', () => {
        navMenu.classList.toggle('active');
        hamburger.classList.toggle('active');
    });

    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
            hamburger.classList.remove('active');
        });
    });
}

// Navbar scroll effect
let lastScroll = 0;
const navbar = document.querySelector('.navbar');

if (navbar) {
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;

        if (currentScroll <= 0) {
            navbar.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
        } else {
            navbar.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
        }

        lastScroll = currentScroll;
    });
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            const navbarHeight = navbar ? navbar.offsetHeight : 0;
            const targetPosition = target.offsetTop - navbarHeight;

            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    });
});

// Intersection Observer for fade-in animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Animate elements on scroll
const animateOnScroll = () => {
    const elements = document.querySelectorAll('.project-card, .school-card, .timeline-item, .branch, .contact-item');

    elements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(element);
    });
};

// Region data for modal content
const regionData = {
    'marmara': {
        name: 'Marmara Bölgesi',
        school: 'Göztepe İhsan Kurşunoğlu Anadolu Lisesi',
        location: 'Bilecik',
        coordinator: true,
        description: 'Marmara Bölgesi, Türkiye\'nin en kalabalık ve ekonomik açıdan en gelişmiş bölgesidir. İstanbul, Bursa, Edirne, Bilecik gibi tarihi şehirleri barındıran bu bölge, zengin kültürel mirasa sahiptir.',
        activities: [
            'Bölgesel mutfak mirasının zenginlikleri ve lezzet araştırması',
            'Rumeli ve Anadolu müzik geleneklerinin belgelenmesi',
            'Karşılama, zeybek gibi yöresel oyunların sahnelenmesi',
            'Osmanlı mutfak gelenekleri ve kültürel miras'
        ]
    },
    'ege': {
        name: 'Ege Bölgesi',
        school: 'TEB Ataşehir Anadolu Lisesi',
        location: 'İzmir',
        coordinator: false,
        description: 'Ege Bölgesi, Akdeniz iklimi ve zengin zeytinlikleriyle ünlüdür. Antik çağlardan günümüze uzanan zengin tarih ve kültür mirası barındırır.',
        activities: [
            'Ege mutfağının zeytinyağlı yemekleri ve deniz ürünleri',
            'Zeybek müziği ve ezgileri',
            'Zeybek oyunları ve kültürel gösterileri',
            'Efes, Bergama gibi antik kentlerin hikâyeleri'
        ]
    },
    'akdeniz': {
        name: 'Akdeniz Bölgesi',
        school: 'Atatürk Fen Lisesi',
        location: 'Antalya',
        coordinator: false,
        description: 'Akdeniz Bölgesi, masmavi denizi, antik kentleri ve bereketli topraklarıyla ünlüdür. Antalya, Mersin, Adana gibi önemli şehirleri barındırır.',
        activities: [
            'Akdeniz mutfağının taze sebze ve meyveleriyle zengin lezzetleri',
            'Türkü ve halk müziği gelenekleri',
            'Silifke, Mut karşılaması gibi yöresel oyunlar',
            'Narenciye kültürü ve yerel gastronomi'
        ]
    },
    'ic-anadolu': {
        name: 'İç Anadolu Bölgesi',
        school: 'Kadir Has Anadolu Lisesi',
        location: 'Ankara',
        coordinator: false,
        description: 'İç Anadolu, Türkiye\'nin merkezi bölgesidir. Ankara başta olmak üzere Konya, Kayseri gibi tarihi şehirleri barındırır. Bozkır kültürü ve zengin mutfak geleneği vardır.',
        activities: [
            'Mantı, keşkek, tarhana gibi geleneksel Anadolu yemekleri',
            'Türkü ve uzun hava gelenekleri',
            'Karşılama, Halay gibi yöresel oyunlar',
            'Nasreddin Hoca fıkraları ve Anadolu masalları'
        ]
    },
    'karadeniz': {
        name: 'Karadeniz Bölgesi',
        school: 'Kadıköy Anadolu Lisesi',
        location: 'Trabzon',
        coordinator: false,
        description: 'Karadeniz Bölgesi, yemyeşil doğası, yaylalarıyla ve özgün kültürüyle dikkat çeker. Hamsi ve mısır bu bölgenin en önemli besin kaynaklarıdır.',
        activities: [
            'Hamsi, lahana dolması, mıhlama gibi Karadeniz lezzetleri',
            'Kemençe ve horon müziği',
            'Karadeniz horonu ve kolbastı',
            'Yayla kültürü ve dağ masalları'
        ]
    },
    'dogu-anadolu': {
        name: 'Doğu Anadolu Bölgesi',
        school: 'Erenköy Kız Anadolu Lisesi',
        location: 'Elazığ',
        coordinator: false,
        description: 'Doğu Anadolu, Türkiye\'nin zengin kültürel mirasına sahip bölgesidir. Elazığ, Erzurum, Van, Ağrı gibi tarihi şehirleri barındırır.',
        activities: [
            'Harput mutfağı ve yöresel lezzetler',
            'Dengbêj geleneği ve halk türküleri',
            'Halay ve yöresel oyunlar',
            'Dede Korkut hikayeleri ve aşık edebiyatı'
        ]
    },
    'guneydogu-anadolu': {
        name: 'Güneydoğu Anadolu Bölgesi',
        school: 'Hayrullah Kefoğlu Anadolu Lisesi',
        location: 'Güneydoğu Anadolu',
        coordinator: false,
        description: 'Güneydoğu Anadolu, zengin gastronomi kültürü ve tarihi dokusuyla ünlüdür. Gaziantep, Şanlıurfa, Mardin gibi önemli kültür şehirlerini barındırır.',
        activities: [
            'Güneydoğu mutfağı: kebap çeşitleri, çiğ köfte, katmer',
            'Dengbêj müziği ve yöresel türküler',
            'Delilo, halay gibi geleneksel oyunlar',
            'Fırat ve Dicle nehirleri etrafında gelişen kültür'
        ]
    }
};

// Wait for SVG to load and initialize interactions
const svgObject = document.getElementById('turkeyMapSVG');
const schoolCards = document.querySelectorAll('.school-card');
const modal = document.getElementById('regionModal');
const modalClose = document.querySelector('.modal-close');
const regions = document.querySelectorAll('.region');

function populateAndShowModal(data) {
    const modalRegionName = document.getElementById('modalRegionName');
    const modalSchoolName = document.getElementById('modalSchoolName');
    const modalSchoolLocation = document.getElementById('modalSchoolLocation');
    const modalDescription = document.getElementById('modalDescription');
    const coordinatorBadge = document.getElementById('modalCoordinatorBadge');
    const activitiesList = document.getElementById('modalActivities');

    if (modalRegionName) modalRegionName.textContent = data.name;
    if (modalSchoolName) modalSchoolName.textContent = data.school;
    if (modalSchoolLocation) modalSchoolLocation.textContent = data.location;
    if (modalDescription) modalDescription.textContent = data.description;

    if (coordinatorBadge) {
        coordinatorBadge.style.display = data.coordinator ? 'inline-block' : 'none';
    }

    if (activitiesList) {
        activitiesList.innerHTML = '';
        data.activities.forEach(activity => {
            const li = document.createElement('li');
            li.textContent = activity;
            activitiesList.appendChild(li);
        });
    }

    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

if (svgObject) svgObject.addEventListener('load', function() {
    const svgDoc = svgObject.contentDocument;
    if (svgDoc) {
        const regionIds = ['marmara', 'ege', 'akdeniz', 'ic-anadolu', 'karadeniz', 'dogu-anadolu', 'guneydogu-anadolu'];

        regionIds.forEach(regionKey => {
            const region = svgDoc.getElementById(regionKey);
            if (!region) return;

            region.addEventListener('click', () => {
                const data = regionData[regionKey];
                if (data) populateAndShowModal(data);
            });

            region.addEventListener('mouseenter', () => {
                region.style.opacity = '0.9';
                region.style.filter = 'brightness(1.2)';

                schoolCards.forEach(card => {
                    if (card.getAttribute('data-region') === regionKey) {
                        card.style.borderColor = 'var(--primary-color)';
                        card.style.transform = 'translateY(-5px) scale(1.05)';
                        card.style.boxShadow = '0 10px 30px rgba(201, 48, 44, 0.2)';
                    } else {
                        card.style.opacity = '0.5';
                    }
                });
            });

            region.addEventListener('mouseleave', () => {
                region.style.opacity = '0.8';
                region.style.filter = 'brightness(1)';

                schoolCards.forEach(card => {
                    card.style.borderColor = '#e9ecef';
                    card.style.transform = 'translateY(0)';
                    card.style.boxShadow = 'none';
                    card.style.opacity = '1';
                });
            });
        });
    }
});

// Close modal handlers
if (modalClose) modalClose.addEventListener('click', closeModal);

if (modal) modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        closeModal();
    }
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal && modal.classList.contains('show')) {
        closeModal();
    }
});

function closeModal() {
    if (modal) modal.classList.remove('show');
    document.body.style.overflow = 'auto';
    if (regions) regions.forEach(r => r.classList.remove('active'));
}

// School card hover and click effects for regions
schoolCards.forEach(card => {
    card.addEventListener('click', () => {
        const regionName = card.getAttribute('data-region');
        const data = regionData[regionName];

        if (data) {
            populateAndShowModal(data);

            regions.forEach(r => r.classList.remove('active'));
            const correspondingRegion = document.querySelector(`.region[data-region="${regionName}"]`);
            if (correspondingRegion) {
                correspondingRegion.classList.add('active');
            }

            const mapSection = document.getElementById('regions');
            if (mapSection) {
                mapSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    card.addEventListener('mouseenter', () => {
        const regionName = card.getAttribute('data-region');
        card.style.cursor = 'pointer';

        regions.forEach(region => {
            if (region.getAttribute('data-region') === regionName) {
                const path = region.querySelector('.region-path');
                if (path) {
                    path.style.opacity = '0.9';
                    path.style.filter = 'brightness(1.1)';
                }
            }
        });
    });

    card.addEventListener('mouseleave', () => {
        regions.forEach(region => {
            const path = region.querySelector('.region-path');
            if (path && !region.classList.contains('active')) {
                path.style.opacity = '0.7';
                path.style.filter = 'brightness(1)';
            }
        });
    });
});

// Timeline animation on scroll
const timelineItems = document.querySelectorAll('.timeline-item');

const timelineObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate');
        }
    });
}, {
    threshold: 0.3
});

timelineItems.forEach(item => {
    timelineObserver.observe(item);
});

// Form submission
const contactForm = document.querySelector('.contact-form form');

if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(contactForm);
        alert('Mesajınız başarıyla gönderildi! En kısa sürede size dönüş yapacağız.');
        contactForm.reset();
    });
}

// Counter animation for statistics
const animateCounter = (element, target, duration = 2000) => {
    let start = 0;
    const increment = target / (duration / 16);

    const updateCounter = () => {
        start += increment;
        if (start < target) {
            element.textContent = Math.floor(start);
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = target;
        }
    };

    updateCounter();
};

// Parallax effect for hero section (sadece hafif hareket, opacity sabit)
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const hero = document.querySelector('.hero-content');

    if (hero && scrolled < window.innerHeight) {
        hero.style.transform = `translateY(${scrolled * 0.3}px)`;
    }
});

// Add active state to current section in navigation
const sections = document.querySelectorAll('section[id]');

const highlightNav = () => {
    const scrollY = window.pageYOffset;

    sections.forEach(section => {
        const sectionHeight = section.offsetHeight;
        const sectionTop = section.offsetTop - 100;
        const sectionId = section.getAttribute('id');

        if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
            document.querySelectorAll('.nav-menu a').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${sectionId}`) {
                    link.classList.add('active');
                }
            });
        }
    });
};

window.addEventListener('scroll', highlightNav);

// Lazy loading for images
const lazyImages = document.querySelectorAll('img[data-src]');

const imageObserver = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.getAttribute('data-src');
            img.removeAttribute('data-src');
            obs.unobserve(img);
        }
    });
});

lazyImages.forEach(img => imageObserver.observe(img));

// Add ripple effect to buttons
const buttons = document.querySelectorAll('.btn');

buttons.forEach(button => {
    button.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');

        this.appendChild(ripple);

        setTimeout(() => ripple.remove(), 600);
    });
});

// Add CSS for ripple effect
const style = document.createElement('style');
style.textContent = `
    .btn {
        position: relative;
        overflow: hidden;
    }

    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }

    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Initialize animations when page loads
window.addEventListener('load', () => {
    animateOnScroll();
    highlightNav();
});

// Add smooth reveal for timeline items
const revealTimeline = () => {
    timelineItems.forEach((item, index) => {
        const itemTop = item.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;

        if (itemTop < windowHeight * 0.8) {
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, index * 200);
        }
    });
};

window.addEventListener('scroll', revealTimeline);

// Set initial state for timeline items
timelineItems.forEach((item, index) => {
    item.style.opacity = '0';
    if (index % 2 === 0) {
        item.style.transform = 'translateX(-50px)';
    } else {
        item.style.transform = 'translateX(50px)';
    }
    item.style.transition = 'all 0.6s ease';
});

// Console message
console.log('%c Anadolu\'nun Mirası ', 'background: #c9302c; color: white; font-size: 20px; padding: 10px;');
console.log('%c 7 Bölge, 7 Okul, 4 Yıllık Kültür Yolculuğu ', 'background: #d4a574; color: white; font-size: 14px; padding: 5px;');
