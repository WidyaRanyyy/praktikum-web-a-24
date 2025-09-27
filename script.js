// script.js - JavaScript untuk Dari Bontang Website
// DOM Manipulation dan Event Listeners untuk website wisata dan produk Bontang

document.addEventListener('DOMContentLoaded', function() {
    console.log('🌅 Dari Bontang - JavaScript Loaded');
    
    try {
        // Initialize all features
        initializeDarkMode();
        initializeProductInteractions();
        initializeSmoothScrolling();
        initializeContactForm();
        initializeSearchFeature();
        initializeWeatherDisplay();
        initializeVideoControls();
        
        console.log('✅ All features initialized successfully');
    } catch (error) {
        console.error('❌ Error initializing features:', error);
    }
});

// =============================
// DARK MODE FUNCTIONALITY
// =============================

function initializeDarkMode() {
    try {
        createDarkModeToggle();
        loadDarkModePreference();
    } catch (error) {
        console.error('Error initializing dark mode:', error);
    }
}

function createDarkModeToggle() {
    const header = document.querySelector('header');
    if (!header) {
        console.warn('Header not found for dark mode toggle');
        return;
    }
    
    // Check if header controls already exist
    let headerControls = header.querySelector('.header-controls');
    if (!headerControls) {
        headerControls = document.createElement('div');
        headerControls.className = 'header-controls';
        headerControls.style.cssText = `
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 100;
        `;
        header.appendChild(headerControls);
    }
    
    const toggleContainer = document.createElement('div');
    toggleContainer.className = 'dark-mode-toggle';
    toggleContainer.innerHTML = `
        <button id="darkModeBtn" class="btn btn-secondary" aria-label="Toggle Dark Mode" style="
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        ">
            <span id="darkModeIcon">🌙</span>
            <span>Mode Gelap</span>
        </button>
    `;
    
    headerControls.appendChild(toggleContainer);
    
    const darkModeBtn = document.getElementById('darkModeBtn');
    if (darkModeBtn) {
        darkModeBtn.addEventListener('click', toggleDarkMode);
        darkModeBtn.addEventListener('mouseenter', function() {
            this.style.background = 'rgba(255,255,255,0.3)';
        });
        darkModeBtn.addEventListener('mouseleave', function() {
            this.style.background = 'rgba(255,255,255,0.2)';
        });
    }
}

function toggleDarkMode() {
    try {
        const body = document.body;
        const html = document.documentElement;
        const darkModeIcon = document.getElementById('darkModeIcon');
        const darkModeBtn = document.getElementById('darkModeBtn');
        
        // Toggle pada HTML dan BODY
        body.classList.toggle('dark-mode');
        html.classList.toggle('dark-mode');
        
        if (body.classList.contains('dark-mode')) {
            if (darkModeIcon) darkModeIcon.textContent = '☀️';
            if (darkModeBtn) {
                const span = darkModeBtn.querySelector('span:last-child');
                if (span) span.textContent = 'Mode Terang';
            }
            localStorage.setItem('darkMode', 'enabled');
            showNotification('Mode gelap diaktifkan!');
            applyDarkModeStyles();
        } else {
            if (darkModeIcon) darkModeIcon.textContent = '🌙';
            if (darkModeBtn) {
                const span = darkModeBtn.querySelector('span:last-child');
                if (span) span.textContent = 'Mode Gelap';
            }
            localStorage.setItem('darkMode', 'disabled');
            showNotification('Mode terang diaktifkan!');
            removeDarkModeStyles();
        }
    } catch (error) {
        console.error('Error toggling dark mode:', error);
    }
}

function applyDarkModeStyles() {
    const darkModeCSS = `
        .dark-mode {
            background-color: #1a1a1a !important;
            color: #e0e0e0 !important;
        }
        .dark-mode .section {
            background-color: #2a2a2a !important;
            color: #e0e0e0 !important;
        }
        .dark-mode .card {
            background-color: #333 !important;
            color: #e0e0e0 !important;
            box-shadow: 0 4px 12px rgba(255,255,255,0.1) !important;
        }
        .dark-mode header {
            background: linear-gradient(135deg, #000000ff, #380d24ff) !important;
        }
        .dark-mode footer {
            background: linear-gradient(135deg, #000000ff, #380d25ff) !important;
        }
        .dark-mode .btn-primary {
            background-color: #4c0b1dff !important;
        }
        .dark-mode .input-wrapper {
            background-color: rgba(255, 255, 255, 0.25) !important;
        }
        .dark-mode .container {
            background-color: #333 !important;
            color: #e0e0e0 !important;
        }
        .dark-mode #myVideo {
            filter: brightness(30%) !important;
        }
    `;
    
    let styleElement = document.getElementById('darkModeStyles');
    if (!styleElement) {
        styleElement = document.createElement('style');
        styleElement.id = 'darkModeStyles';
        document.head.appendChild(styleElement);
    }
    styleElement.textContent = darkModeCSS;
}

function removeDarkModeStyles() {
    const styleElement = document.getElementById('darkModeStyles');
    if (styleElement) {
        styleElement.remove();
    }
}

function loadDarkModePreference() {
    try {
        const darkMode = localStorage.getItem('darkMode');
        if (darkMode === 'enabled') {
            document.body.classList.add('dark-mode');
            document.documentElement.classList.add('dark-mode');
            applyDarkModeStyles();
            
            // Update button after DOM ready
            setTimeout(() => {
                const darkModeIcon = document.getElementById('darkModeIcon');
                const darkModeBtn = document.getElementById('darkModeBtn');
                if (darkModeIcon && darkModeBtn) {
                    darkModeIcon.textContent = '☀️';
                    const span = darkModeBtn.querySelector('span:last-child');
                    if (span) span.textContent = 'Mode Terang';
                }
            }, 100);
        }
    } catch (error) {
        console.error('Error loading dark mode preference:', error);
    }
}

// =============================
// PRODUCT INTERACTIONS
// =============================

function initializeProductInteractions() {
    try {
        const cards = document.querySelectorAll('.card');
        
        cards.forEach(card => {
            const btn = card.querySelector('.btn-primary');
            const title = card.querySelector('h3')?.textContent || 'Item';
            const img = card.querySelector('img');
            
            if (btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    showItemDetail(title, card);
                    animateButton(this);
                });
            }
            
            // Add hover effects
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
                this.style.boxShadow = '0 8px 20px rgba(0,0,0,0.2)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
                this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
            });
        });
    } catch (error) {
        console.error('Error initializing product interactions:', error);
    }
}

function showItemDetail(name, cardElement) {
    try {
        const modal = createModal();
        const img = cardElement.querySelector('img');
        
        // Detailed information based on item name
        const itemInfo = getItemInfo(name);
        
        modal.innerHTML = `
            <div class="modal-content" style="
                background: white;
                border-radius: 15px;
                padding: 0;
                max-width: 600px;
                max-height: 80vh;
                overflow-y: auto;
                position: relative;
            ">
                <div class="modal-header" style="
                    background: linear-gradient(135deg, #841751, #db2796);
                    color: white;
                    padding: 20px;
                    border-radius: 15px 15px 0 0;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                ">
                    <h3 style="margin: 0; font-size: 1.5rem;">${name}</h3>
                    <button class="modal-close" aria-label="Tutup" style="
                        background: none;
                        border: none;
                        color: white;
                        font-size: 1.5rem;
                        cursor: pointer;
                        padding: 5px;
                    ">&times;</button>
                </div>
                <div class="modal-body" style="padding: 20px; color: #333;">
                    <img src="${img?.src || ''}" alt="${name}" style="
                        width: 100%;
                        max-width: 300px;
                        height: 200px;
                        object-fit: cover;
                        border-radius: 10px;
                        margin-bottom: 15px;
                        display: block;
                        margin-left: auto;
                        margin-right: auto;
                    ">
                    <div style="text-align: left;">
                        <p><strong>📍 Lokasi:</strong> ${itemInfo.location}</p>
                        <p><strong>📝 Deskripsi:</strong> ${itemInfo.description}</p>
                        <p><strong>⭐ Keunggulan:</strong> ${itemInfo.features}</p>
                        <p><strong>💡 Tips:</strong> ${itemInfo.tips}</p>
                    </div>
                </div>
                <div class="modal-footer" style="
                    padding: 20px;
                    border-top: 1px solid #eee;
                    display: flex;
                    gap: 10px;
                    justify-content: flex-end;
                ">
                    <button class="btn modal-contact-btn" style="
                        background-color: #25D366;
                        color: white;
                        padding: 10px 20px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        font-weight: 600;
                    " data-name="${escapeHtml(name)}">
                        📞 Hubungi Kami
                    </button>
                    <button class="btn btn-secondary modal-close-btn" style="
                        background-color: #6c757d;
                        color: white;
                        padding: 10px 20px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                    ">
                        Tutup
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Event listeners
        const closeBtn = modal.querySelector('.modal-close');
        const closeBtnFooter = modal.querySelector('.modal-close-btn');
        const contactBtn = modal.querySelector('.modal-contact-btn');
        
        if (closeBtn) {
            closeBtn.addEventListener('click', () => modal.remove());
        }
        
        if (closeBtnFooter) {
            closeBtnFooter.addEventListener('click', () => modal.remove());
        }
        
        if (contactBtn) {
            contactBtn.addEventListener('click', function() {
                const itemName = this.getAttribute('data-name');
                contactViaInstagram(itemName);
                modal.remove();
            });
        }
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.remove();
        });
        
        // ESC key to close modal
        const escHandler = function(e) {
            if (e.key === 'Escape') {
                modal.remove();
                document.removeEventListener('keydown', escHandler);
            }
        };
        document.addEventListener('keydown', escHandler);
        
    } catch (error) {
        console.error('Error showing item detail:', error);
        showNotification('Error menampilkan detail item', 'error');
    }
}

function getItemInfo(itemName) {
    const itemDatabase = {
        'Udeng Batik Kuntul Perak': {
            location: 'Toko Souvenir Bontang',
            description: 'Udeng tradisional dengan motif batik khas Kuntul Perak, simbol kebanggaan Kota Bontang. Dibuat dengan kain berkualitas tinggi dan pewarnaan alami.',
            features: 'Motif eksklusif, kain premium, tahan lama, cocok untuk acara formal',
            tips: 'Cocok digunakan untuk acara adat, pernikahan, atau sebagai oleh-oleh khas Bontang'
        },
        'Keripik Bawis': {
            location: 'Home Industry Bontang',
            description: 'Keripik ikan bawis khas Bontang yang gurih dan renyah. Diolah dengan resep turun temurun menggunakan ikan segar dari perairan Bontang.',
            features: 'Rasa gurih autentik, tanpa MSG, kemasan higienis, tahan lama',
            tips: 'Simpan di tempat kering, cocok sebagai camilan atau oleh-oleh'
        },
        'Pakaian Batik Kuntul Perak': {
            location: 'Sentra Batik Bontang',
            description: 'Pakaian batik dengan motif Kuntul Perak yang menjadi ciri khas Bontang. Tersedia dalam berbagai model untuk pria dan wanita.',
            features: 'Motif original Bontang, bahan adem, jahitan rapi, berbagai ukuran',
            tips: 'Cuci dengan air dingin, jemur tidak langsung di bawah sinar matahari'
        },
        'Pantai Beras Basah': {
            location: 'Bontang Utara, Kota Bontang',
            description: 'Pantai dengan pasir putih bersih dan air laut jernih. Tempat favorit untuk menikmati sunset dan berbagai aktivitas pantai.',
            features: 'Pemandangan sunset, pasir putih, fasilitas lengkap, spot foto instagramable',
            tips: 'Berkunjung sore hari untuk sunset terbaik, bawa kamera, pakai sunscreen'
        },
        'Hutan Mangrove': {
            location: 'Tanjung Laut, Bontang',
            description: 'Kawasan konservasi hutan mangrove dengan jembatan kayu yang membelah hutan. Tempat edukasi dan wisata alam yang menarik.',
            features: 'Edukasi lingkungan, jembatan kayu, spot foto unik, udara segar',
            tips: 'Gunakan alas kaki yang nyaman, bawa air minum, jaga kebersihan lingkungan'
        },
        'Gammi Bawis': {
            location: 'Rumah Makan Tradisional Bontang',
            description: 'Mi kuning khas Bontang dengan topping ikan bawis dan kuah gurih. Cita rasa autentik yang menjadi kebanggaan kuliner Bontang.',
            features: 'Rasa khas Bontang, ikan segar, kuah gurih, porsi mengenyangkan',
            tips: 'Nikmati selagi hangat, tambahkan sambal untuk rasa lebih pedas'
        }
    };
    
    return itemDatabase[itemName] || {
        location: 'Bontang, Kalimantan Timur',
        description: `${itemName} merupakan salah satu keunikan yang dapat ditemukan di Kota Bontang.`,
        features: 'Khas Bontang, berkualitas, autentik',
        tips: 'Hubungi kami untuk informasi lebih detail'
    };
}

function contactViaInstagram(itemName) {
    try {
        const igUrl = "https://www.instagram.com/rany.janoor/";
        window.open(igUrl, "_blank");
        showNotification(`Mengarahkan ke Instagram untuk info lebih lanjut tentang ${itemName}...`);
    } catch (error) {
        console.error("Error opening Instagram:", error);
        showNotification("Error menghubungi via Instagram", "error");
    }
}

function animateButton(button) {
    try {
        button.style.transform = 'scale(0.95)';
        setTimeout(() => {
            button.style.transform = '';
        }, 150);
    } catch (error) {
        console.error('Error animating button:', error);
    }
}

// =============================
// SMOOTH SCROLLING
// =============================

function initializeSmoothScrolling() {
    try {
        const navLinks = document.querySelectorAll('.nav-links a');
        
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                // Only handle internal links
                if (href && href.startsWith('#')) {
                    e.preventDefault();
                    const targetId = href.substring(1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                        
                        // Add highlight effect
                        const originalBackground = targetElement.style.background;
                        targetElement.style.background = 'rgba(132,23,81,0.1)';
                        setTimeout(() => {
                            targetElement.style.background = originalBackground;
                        }, 2000);
                        
                        showNotification(`Menuju ke bagian ${targetElement.querySelector('h2')?.textContent || targetId}`);
                    }
                }
            });
        });
    } catch (error) {
        console.error('Error initializing smooth scrolling:', error);
    }
}

// =============================
// CONTACT FORM
// =============================

function initializeContactForm() {
    try {
        // Create a simple contact form if it doesn't exist
        const contactSection = document.getElementById('kontak');
        if (!contactSection) return;
        
        // Check if form already exists
        if (contactSection.querySelector('form')) return;
        
        const formHTML = `
            <form class="contact-form" style="
                max-width: 500px;
                margin: 2rem auto;
                background: white;
                padding: 2rem;
                border-radius: 10px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            ">
                <div style="margin-bottom: 1rem;">
                    <label for="contactName" style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Nama:</label>
                    <input type="text" id="contactName" name="name" required style="
                        width: 100%;
                        padding: 10px;
                        border: 2px solid #ddd;
                        border-radius: 5px;
                        box-sizing: border-box;
                    ">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label for="contactEmail" style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Email:</label>
                    <input type="email" id="contactEmail" name="email" required style="
                        width: 100%;
                        padding: 10px;
                        border: 2px solid #ddd;
                        border-radius: 5px;
                        box-sizing: border-box;
                    ">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label for="contactMessage" style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Pesan:</label>
                    <textarea id="contactMessage" name="message" rows="4" style="
                        width: 100%;
                        padding: 10px;
                        border: 2px solid #ddd;
                        border-radius: 5px;
                        box-sizing: border-box;
                        resize: vertical;
                    "></textarea>
                </div>
                <div style="text-align: center;">
                    <button type="submit" class="btn btn-primary" style="margin-right: 10px;">Kirim Pesan</button>
                    <button type="reset" class="btn btn-secondary" style="background-color: #6c757d;">Reset</button>
                </div>
            </form>
        `;
        
        contactSection.insertAdjacentHTML('beforeend', formHTML);
        
        const contactForm = contactSection.querySelector('.contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', handleContactSubmit);
            
            const resetBtn = contactForm.querySelector('button[type="reset"]');
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    setTimeout(() => {
                        showNotification('Form direset');
                    }, 100);
                });
            }
        }
    } catch (error) {
        console.error('Error initializing contact form:', error);
    }
}

function handleContactSubmit(e) {
    try {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const name = formData.get('name')?.trim();
        const email = formData.get('email')?.trim();
        const message = formData.get('message')?.trim();
        
        // Validation
        if (!name || !email) {
            showNotification('Nama dan email harus diisi!', 'error');
            return;
        }
        
        if (!isValidEmail(email)) {
            showNotification('Format email tidak valid!', 'error');
            return;
        }
        
        // Simulate submission
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        submitBtn.textContent = 'Mengirim...';
        submitBtn.disabled = true;
        
        setTimeout(() => {
            showNotification(`Terima kasih ${name}! Pesan Anda telah diterima. Tim Dari Bontang akan segera merespon.`);
            e.target.reset();
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            
        setTimeout(() => {
            if (confirm('Ingin menghubungi kami langsung via Instagram?')) {
                window.open("https://www.instagram.com/rany.janoor/", "_blank");
            }
        }, 1000);
            
        }, 1500);
    } catch (error) {
        console.error('Error handling contact submit:', error);
        showNotification('Error mengirim pesan', 'error');
    }
}

function isValidEmail(email) {
    try {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    } catch (error) {
        console.error('Error validating email:', error);
        return false;
    }
}

// =============================
// SEARCH FUNCTIONALITY
// =============================

function initializeSearchFeature() {
    try {
        const searchInput = document.querySelector('input[type="text"]');
        const searchBtn = document.querySelector('.input-wrapper button');
        
        if (searchInput && searchBtn) {
            // Real-time search
            searchInput.addEventListener('input', performSearch);
            
            // Search on button click
            searchBtn.addEventListener('click', function(e) {
                e.preventDefault();
                performSearch();
            });
            
            // Search on Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performSearch();
                }
            });
        }
    } catch (error) {
        console.error('Error initializing search feature:', error);
    }
}

function performSearch() {
    try {
        const searchInput = document.querySelector('input[type="text"]');
        if (!searchInput) return;
        
        const searchTerm = searchInput.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.card');
        let visibleCount = 0;
        
        cards.forEach(card => {
            const cardTitle = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const cardSection = card.closest('.section');
            const sectionTitle = cardSection?.querySelector('h2')?.textContent.toLowerCase() || '';
            
            if (cardTitle.includes(searchTerm) || sectionTitle.includes(searchTerm) || searchTerm === '') {
                card.style.display = 'flex';
                visibleCount++;
                
                // Highlight matching cards
                if (searchTerm !== '') {
                    card.style.border = '2px solid #db2796';
                    card.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        card.style.border = '';
                        card.style.transform = '';
                    }, 3000);
                }
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show search results
        if (searchTerm !== '') {
            if (visibleCount === 0) {
                showNotification(`Tidak ditemukan hasil untuk "${searchTerm}". Coba kata kunci lain.`, 'error');
            } else {
                showNotification(`Ditemukan ${visibleCount} hasil untuk "${searchTerm}"`);
            }
        }
    } catch (error) {
        console.error('Error performing search:', error);
    }
}

// =============================
// VIDEO CONTROLS
// =============================

function initializeVideoControls() {
    try {
        const backgroundVideo = document.getElementById('myVideo');
        const videoPlayer = document.querySelector('.video-player');
        
        if (backgroundVideo) {
            // Add video controls
            const controlsContainer = document.createElement('div');
            controlsContainer.innerHTML = `
                <button id="videoToggle" style="
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: rgba(0,0,0,0.7);
                    color: white;
                    border: none;
                    padding: 10px;
                    border-radius: 50%;
                    cursor: pointer;
                    font-size: 1.2rem;
                    z-index: 1000;
                    transition: all 0.3s ease;
                " title="Toggle Background Video">⏸️</button>
            `;
            document.body.appendChild(controlsContainer);
            
            const videoToggle = document.getElementById('videoToggle');
            if (videoToggle) {
                videoToggle.addEventListener('click', function() {
                    if (backgroundVideo.paused) {
                        backgroundVideo.play();
                        this.textContent = '⏸️';
                        this.title = 'Pause Background Video';
                        showNotification('Video background diputar');
                    } else {
                        backgroundVideo.pause();
                        this.textContent = '▶️';
                        this.title = 'Play Background Video';
                        showNotification('Video background dijeda');
                    }
                });
                
                videoToggle.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.1)';
                    this.style.background = 'rgba(0,0,0,0.9)';
                });
                
                videoToggle.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                    this.style.background = 'rgba(0,0,0,0.7)';
                });
            }
        }
    } catch (error) {
        console.error('Error initializing video controls:', error);
    }
}

// =============================
// WEATHER DISPLAY
// =============================

function initializeWeatherDisplay() {
    try {
        const weatherContainer = document.createElement('div');
        weatherContainer.innerHTML = `
            <div id="weatherInfo" style="
                position: fixed;
                bottom: 20px;
                left: 20px;
                background: rgba(255,255,255,0.2);
                padding: 8px 12px;
                border-radius: 5px;
                color: white;
                font-size: 0.9rem;
                z-index: 100;
            ">
                🌅 Bontang, Kalimantan Timur
            </div>
        `;
        
        document.body.appendChild(weatherContainer);
        
        // Try to fetch weather data
        fetchWeatherData();
    } catch (error) {
        console.error('Error initializing weather display:', error);
    }
}

async function fetchWeatherData() {
    try {
        // Using a simple weather API alternative
        const weatherInfo = document.getElementById('weatherInfo');
        if (weatherInfo) {
            // Fallback weather display for Bontang
            weatherInfo.innerHTML = '🌅 Bontang - Cuaca Cerah 28°C';
            
            // Optional: Try to get actual weather data
            fetch('https://wttr.in/Bontang?format=%C+%t', {
                method: 'GET',
                headers: { 'User-Agent': 'curl/7.0' }
            })
            .then(response => response.text())
            .then(data => {
                if (data && data.trim()) {
                    weatherInfo.innerHTML = `🌅 Bontang - ${data.trim()}`;
                }
            })
            .catch(() => {
                // Keep fallback weather
                console.log('Using fallback weather data');
            });
        }
    } catch (error) {
        console.error('Error fetching weather data:', error);
    }
}

// =============================
// UTILITY FUNCTIONS
// =============================

function createModal() {
    try {
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            animation: fadeIn 0.3s ease;
        `;
        return modal;
    } catch (error) {
        console.error('Error creating modal:', error);
        return null;
    }
}

function showNotification(message, type = 'success') {
    try {
        // Remove existing notification
        const existingNotification = document.querySelector('.notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const notification = document.createElement('div');
        notification.className = `notification notification--${type}`;
        
        const bgColor = type === 'error' ? '#e74c3c' : '#27ae60';
        const icon = type === 'error' ? '❌' : '✅';
        
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${bgColor};
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            font-weight: 600;
            z-index: 10001;
            max-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            animation: slideInRight 0.3s ease;
            cursor: pointer;
        `;
        
        notification.textContent = `${icon} ${message}`;
        document.body.appendChild(notification);
        
        // Auto remove after 4 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 4000);
        
        // Click to dismiss
        notification.addEventListener('click', () => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        });
    } catch (error) {
        console.error('Error showing notification:', error);
    }
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// =============================
// ANIMATIONS & EFFECTS
// =============================

function initializeAnimations() {
    try {
        // Add CSS animations
        const animationCSS = `
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            @keyframes slideInRight {
                from { 
                    transform: translateX(100%);
                    opacity: 0;
                }
                to { 
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from { 
                    transform: translateX(0);
                    opacity: 1;
                }
                to { 
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
            
            .card {
                transition: all 0.3s ease !important;
            }
            
            .btn:hover {
                animation: pulse 0.6s ease-in-out;
            }
        `;
        
        const styleElement = document.createElement('style');
        styleElement.textContent = animationCSS;
        document.head.appendChild(styleElement);
        
        // Intersection Observer for scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        // Observe cards for animation
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            observer.observe(card);
        });
    } catch (error) {
        console.error('Error initializing animations:', error);
    }
}

// =============================
// EVENT LISTENERS
// =============================

// Handle online/offline status
window.addEventListener('online', function() {
    try {
        showNotification('Koneksi internet tersambung!');
    } catch (error) {
        console.error('Error handling online status:', error);
    }
});

window.addEventListener('offline', function() {
    try {
        showNotification('Koneksi internet terputus!', 'error');
    } catch (error) {
        console.error('Error handling offline status:', error);
    }
});

// Handle image loading errors
document.addEventListener('error', function(e) {
    try {
        if (e.target.tagName === 'IMG') {
            e.target.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDMwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xNTAgMTAwQzE2NS42IDEwMCAxNzggODcuNiAxNzggNzJDMTc4IDU2LjQgMTY1LjYgNDQgMTUwIDQ0QzEzNC40IDQ0IDEyMiA1Ni40IDEyMiA3MkMxMjIgODcuNiAxMzQuNCAxMDAgMTUwIDEwMFoiIGZpbGw9IiM5Q0EzQUYiLz4KPHA+PC9wPg=';
            e.target.alt = 'Gambar tidak dapat dimuat';
            e.target.style.border = '2px dashed #ccc';
        }
    } catch (error) {
        console.error('Error handling image error:', error);
    }
}, true);

// Scroll to top functionality
window.addEventListener('scroll', function() {
    try {
        if (!document.getElementById('scrollToTop')) {
            createScrollToTopButton();
        }
        
        const scrollToTop = document.getElementById('scrollToTop');
        if (scrollToTop) {
            if (window.pageYOffset > 300) {
                scrollToTop.style.display = 'block';
                scrollToTop.style.opacity = '1';
            } else {
                scrollToTop.style.opacity = '0';
                setTimeout(() => {
                    if (window.pageYOffset <= 300) {
                        scrollToTop.style.display = 'none';
                    }
                }, 300);
            }
        }
    } catch (error) {
        console.error('Error handling scroll:', error);
    }
});

function createScrollToTopButton() {
    try {
        const scrollButton = document.createElement('button');
        scrollButton.id = 'scrollToTop';
        scrollButton.innerHTML = '↑';
        scrollButton.style.cssText = `
            position: fixed;
            bottom: 80px;
            right: 20px;
            background: linear-gradient(135deg, #841751, #db2796);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 20px;
            cursor: pointer;
            display: none;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        `;
        
        scrollButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            showNotification('Kembali ke atas');
        });
        
        scrollButton.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
        });
        
        scrollButton.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
        
        document.body.appendChild(scrollButton);
    } catch (error) {
        console.error('Error creating scroll to top button:', error);
    }
}

// Initialize animations after DOM content is fully loaded
setTimeout(() => {
    try {
        initializeAnimations();
    } catch (error) {
        console.error('Error in delayed animation initialization:', error);
    }
}, 500);

// =============================
// RESPONSIVE ENHANCEMENTS
// =============================

function handleResponsiveFeatures() {
    try {
        // Mobile menu toggle (if needed)
        const nav = document.querySelector('.nav-links');
        if (nav && window.innerWidth <= 768) {
            nav.style.flexDirection = 'column';
            nav.style.gap = '1rem';
        }
        
        // Adjust video controls for mobile
        const videoToggle = document.getElementById('videoToggle');
        if (videoToggle && window.innerWidth <= 480) {
            videoToggle.style.bottom = '10px';
            videoToggle.style.right = '10px';
            videoToggle.style.padding = '8px';
            videoToggle.style.fontSize = '1rem';
        }
    } catch (error) {
        console.error('Error handling responsive features:', error);
    }
}

// Handle window resize
window.addEventListener('resize', handleResponsiveFeatures);

// Initialize responsive features
handleResponsiveFeatures();

// =============================
// FINAL INITIALIZATION
// =============================

// Log successful initialization
console.log('Dari Bontang website fully initialized!');
console.log('Features available:');
console.log('- Dark/Light mode toggle');
console.log('- Product/Tourism detail modals');
console.log('- Smooth scrolling navigation');
console.log('- Contact form with WhatsApp integration');
console.log('- Search functionality');
console.log('- Weather display with Fetch API');
console.log('- Video background controls');
console.log('- Public API integration');
console.log('- Responsive design enhancements');
console.log('- Scroll to top button');
console.log('- Interactive animations');

// Welcome message
setTimeout(() => {
    if (window.location.hash === '' || window.location.hash === '#beranda') {
        showNotification('Selamat datang di Dari Bontang! Jelajahi keindahan dan keunikan Kota Bontang.');
    }
}, 2000);
