<style>
    :root {
        --primary: #3B82F6;
        --primary-dark: #2563EB;
        --secondary: #8B5CF6;
        --accent: #EC4899;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #F8FAFC 0%, #E2E8F0 100%);
        min-height: 100vh;
    }

    [dir="rtl"] body {
        font-family: 'Cairo', sans-serif;
    }

    /* Top Navigation Bar */
    .top-navbar {
        background: white;
        padding: 1rem 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .nav-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .nav-btn {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        border: none;
        background: #F8FAFC;
        color: #64748B;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 18px;
    }

    .nav-btn:hover {
        background: #E2E8F0;
        color: var(--primary);
        transform: translateY(-2px);
    }

    .nav-btn.active {
        background: var(--primary);
        color: white;
    }

    .brand-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
    }

    .brand-logo-img {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .brand-logo-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .brand-name {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .brand-name-main {
        font-size: 18px;
        font-weight: 700;
        color: #1E293B;
        line-height: 1;
    }

    [dir="rtl"] .brand-name {
        text-align: right;
    }

    /* Navigation Menu */
    .nav-menu {
        display: flex;
        align-items: center;
        gap: 2rem;
        margin: 0 auto;
    }

    .nav-link {
        text-decoration: none;
        color: #64748B;
        font-weight: 500;
        font-size: 15px;
        transition: all 0.3s;
        position: relative;
        padding: 8px 0;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--primary);
        transition: width 0.3s;
    }

    .nav-link:hover {
        color: var(--primary);
    }

    .nav-link:hover::after {
        width: 100%;
    }

    .nav-link.active {
        color: var(--primary);
    }

    .nav-link.active::after {
        width: 100%;
    }

    /* Mobile Menu */
    .mobile-menu-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
    }

    .mobile-menu-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .mobile-menu {
        position: fixed;
        top: 0;
        right: -100%;
        width: 280px;
        height: 100vh;
        background: white;
        z-index: 1000;
        padding: 2rem;
        box-shadow: -4px 0 20px rgba(0, 0, 0, 0.1);
        transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-y: auto;
    }

    [dir="rtl"] .mobile-menu {
        right: auto;
        left: -100%;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
    }

    .mobile-menu.active {
        right: 0;
    }

    [dir="rtl"] .mobile-menu.active {
        right: auto;
        left: 0;
    }

    .mobile-menu-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #E2E8F0;
    }

    .mobile-menu-close {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: #F8FAFC;
        color: #64748B;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .mobile-menu-close:hover {
        background: #E2E8F0;
        color: var(--primary);
    }

    .mobile-nav-links {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .mobile-nav-link {
        text-decoration: none;
        color: #1E293B;
        font-weight: 500;
        font-size: 16px;
        padding: 12px 16px;
        border-radius: 12px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .mobile-nav-link:hover {
        background: #F8FAFC;
        color: var(--primary);
    }

    .mobile-nav-link.active {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
    }

    @media (max-width: 768px) {
        .nav-menu {
            display: none;
        }

        .brand-name {
            display: none;
        }
    }

    @media (min-width: 769px) {
        .nav-btn#menuToggle {
            display: none;
        }
    }

    /* Dark mode for navigation */
    body.dark-mode .top-navbar {
        background: #334155;
    }

    body.dark-mode .nav-link {
        color: #94A3B8;
    }

    body.dark-mode .nav-link:hover,
    body.dark-mode .nav-link.active {
        color: #38BDF8;
    }

    body.dark-mode .mobile-menu {
        background: #334155;
    }

    body.dark-mode .mobile-menu-header {
        border-bottom-color: #475569;
    }

    body.dark-mode .mobile-nav-link {
        color: #E2E8F0;
    }

    body.dark-mode .mobile-nav-link:hover {
        background: #475569;
    }

    body.dark-mode .mobile-menu-close {
        background: #475569;
        color: #94A3B8;
    }

    body.dark-mode .mobile-menu-close:hover {
        background: #64748B;
        color: white;
    }

    body.dark-mode .brand-name-main {
        color: white;
    }

    body.dark-mode .nav-btn {
        background: #475569;
        color: #94A3B8;
    }

    body.dark-mode .nav-btn:hover {
        background: #64748B;
        color: white;
    }
</style>
