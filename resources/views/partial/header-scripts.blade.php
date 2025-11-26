<script>
    // Mobile Menu Toggle
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    const mobileMenuClose = document.getElementById('mobileMenuClose');

    function openMobileMenu() {
        mobileMenu.classList.add('active');
        mobileMenuOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        mobileMenu.classList.remove('active');
        mobileMenuOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    menuToggle.addEventListener('click', openMobileMenu);
    mobileMenuClose.addEventListener('click', closeMobileMenu);
    mobileMenuOverlay.addEventListener('click', closeMobileMenu);

    // Close mobile menu when clicking a link
    document.querySelectorAll('.mobile-nav-link').forEach(link => {
        link.addEventListener('click', closeMobileMenu);
    });

    // Dark Mode Toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    const darkModeIcon = darkModeToggle.querySelector('i');

    // Check for saved dark mode preference
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
        darkModeIcon.classList.remove('fa-moon');
        darkModeIcon.classList.add('fa-sun');
        darkModeToggle.classList.add('active');
    }

    darkModeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        
        if (document.body.classList.contains('dark-mode')) {
            darkModeIcon.classList.remove('fa-moon');
            darkModeIcon.classList.add('fa-sun');
            darkModeToggle.classList.add('active');
            localStorage.setItem('darkMode', 'enabled');
        } else {
            darkModeIcon.classList.remove('fa-sun');
            darkModeIcon.classList.add('fa-moon');
            darkModeToggle.classList.remove('active');
            localStorage.setItem('darkMode', 'disabled');
        }
    });

    // Language Toggle
    const langToggle = document.getElementById('langToggle');
    langToggle.addEventListener('click', () => {
        const url = new URL(window.location.href);
        const currentLang = url.searchParams.get('lang') || 'en';
        const newLang = currentLang === 'en' ? 'ar' : 'en';
        url.searchParams.set('lang', newLang);
        window.location.href = url.toString();
    });
</script>
