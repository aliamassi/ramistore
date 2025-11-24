<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Menu - {{ $selectedCategory }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #D4A574;
            --primary-dark: #b88b5c;
            --secondary: #2D3436;
            --bg-color: #F8F9FA;
            --card-bg: #FFFFFF;
            --text-main: #2D3436;
            --text-light: #636E72;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
            --shadow-md: 0 8px 24px rgba(0,0,0,0.08);
            --shadow-lg: 0 16px 48px rgba(0,0,0,0.12);
            --radius-sm: 12px;
            --radius-md: 16px;
            --radius-lg: 24px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            line-height: 1.5;
            padding-bottom: 100px; /* Space for mobile cart bar */
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ===== Header ===== */
        .header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .header.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-sm);
        }

        .restaurant-banner {
            background: linear-gradient(135deg, #2D3436 0%, #000000 100%);
            color: white;
            padding: 40px 0;
            position: relative;
            overflow: hidden;
            margin-bottom: -20px;
            padding-bottom: 60px;
        }

        .restaurant-banner::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at top right, rgba(212, 165, 116, 0.2), transparent 60%);
            pointer-events: none;
        }

        .banner-content {
            display: flex;
            align-items: center;
            gap: 24px;
            position: relative;
            z-index: 1;
        }

        .logo-wrapper {
            width: 80px;
            height: 80px;
            border-radius: var(--radius-md);
            background: white;
            padding: 4px;
            box-shadow: var(--shadow-md);
            flex-shrink: 0;
        }

        .logo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        .logo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            color: white;
            font-size: 32px;
            font-weight: 700;
            border-radius: 12px;
        }

        .restaurant-info h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .tags-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag-pill {
            background: rgba(255,255,255,0.15);
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 500;
            backdrop-filter: blur(4px);
        }

        /* ===== Contact Info ===== */
        .contact-info-bar {
            margin-top: 24px;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.9);
            font-size: 14px;
            text-decoration: none;
            background: rgba(255,255,255,0.1);
            padding: 8px 16px;
            border-radius: 50px;
            transition: all 0.2s;
            backdrop-filter: blur(4px);
        }

        .contact-item:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
            color: white;
        }

        .contact-item i {
            color: var(--primary);
        }

        /* ===== Navigation ===== */
        .nav-scroller {
            background: white;
            padding: 16px 0;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            position: relative;
            z-index: 10;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.05);
        }

        .nav-list {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding: 0 20px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .nav-list::-webkit-scrollbar {
            display: none;
        }

        .nav-pill {
            padding: 10px 20px;
            background: #F0F2F5;
            color: var(--text-light);
            border-radius: 100px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .nav-pill.active {
            background: var(--secondary);
            color: white;
            box-shadow: 0 4px 12px rgba(45, 52, 54, 0.2);
        }

        .nav-pill:hover:not(.active) {
            background: #E4E7EB;
            color: var(--text-main);
        }

        /* ===== Menu Grid ===== */
        .menu-section {
            padding: 30px 0;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 24px;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: var(--primary);
            border-radius: 4px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
        }

        .product-card {
            background: var(--card-bg);
            border-radius: var(--radius-md);
            padding: 20px;
            display: flex;
            gap: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0,0,0,0.03);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: rgba(0,0,0,0.08);
        }

        .product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-main);
            line-height: 1.3;
        }

        .product-desc {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.5;
        }

        .product-meta {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .price {
            font-size: 18px;
            font-weight: 700;
            color: var(--secondary);
        }

        .product-img-wrapper {
            width: 120px;
            height: 120px;
            border-radius: var(--radius-sm);
            overflow: hidden;
            flex-shrink: 0;
            position: relative;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-img {
            transform: scale(1.08);
        }

        .add-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            bottom: 8px;
            right: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            color: var(--primary);
            font-size: 18px;
            transition: all 0.2s ease;
        }

        .product-card:hover .add-btn {
            background: var(--primary);
            color: white;
            transform: scale(1.1);
        }

        /* ===== Floating Cart ===== */
        .floating-cart {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 40px);
            max-width: 500px;
            background: var(--secondary);
            color: white;
            padding: 16px 24px;
            border-radius: 100px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 12px 32px rgba(45, 52, 54, 0.3);
            z-index: 1000;
            cursor: pointer;
            transition: transform 0.2s ease;
            backdrop-filter: blur(10px);
        }

        .floating-cart:active {
            transform: translateX(-50%) scale(0.98);
        }

        .cart-info {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
        }

        .cart-count-badge {
            background: var(--primary);
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
        }

        .view-cart-text {
            font-size: 16px;
            font-weight: 600;
        }

        .cart-arrow {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ===== Empty State ===== */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--text-light);
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
            color: var(--primary);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .restaurant-banner {
                padding: 30px 0 50px;
            }

            .logo-wrapper {
                width: 64px;
                height: 64px;
            }

            .restaurant-info h1 {
                font-size: 24px;
            }

            .product-grid {
                grid-template-columns: 1fr;
            }

            .product-card {
                padding: 16px;
            }

            .product-img-wrapper {
                width: 100px;
                height: 100px;
            }

            .contact-info-bar {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .contact-item {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="restaurant-banner">
        <div class="container">
            <div class="banner-content">
                <div class="logo-wrapper">
                    @if(!empty($user->logo))
                        <img src="{{ $user->logo }}" alt="Logo" class="logo-img">
                    @else
                        <div class="logo-placeholder">
                            {{ strtoupper(substr($restaurant['business_name']->value ?? 'R', 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="restaurant-info">
                    <h1>{{ $restaurant['business_name']->value ?? 'Restaurant Name' }}</h1>
                    <div class="tags-wrapper">
                        @if(isset($restaurant['tags']) && is_array($restaurant['tags']->value))
                            @foreach($restaurant['tags']->value as $tag)
                                <span class="tag-pill">{{ $tag }}</span>
                            @endforeach
                        @else
                            <span class="tag-pill">Delicious Food</span>
                            <span class="tag-pill">Best Service</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Contact Info Section --}}
            @if(isset($restaurant['phone_number']) || isset($restaurant['email']) || isset($restaurant['address']))
                <div class="contact-info-bar">
                    @if(isset($restaurant['phone_number']))
                        <a href="tel:{{ $restaurant['phone_number']->value }}" class="contact-item">
                            <i class="fas fa-phone-alt"></i>
                            <span>{{ $restaurant['phone_number']->value }}</span>
                        </a>
                    @endif

                    @if(isset($restaurant['contact_email']))
                        <a href="mailto:{{ $restaurant['contact_email']->value }}" class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $restaurant['contact_email']->value }}</span>
                        </a>
                    @endif

                    @if(isset($restaurant['address']))
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $restaurant['address']->value }}</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="nav-scroller">
        <div class="container">
            <div class="nav-list">
                @foreach($categories as $category)
                    <a href="{{ route('menu.index', ['name'=>$name, 'category' => $category->name]) }}"
                       class="nav-pill {{ $category->name === $selectedCategory ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="container">
        <div class="menu-section">
            <h2 class="section-title">{{ $selectedCategory }}</h2>

            @if($products->count() > 0)
                <div class="product-grid" id="product-grid">
                    @include('menu.partials.products')
                </div>

                <!-- Load More Trigger -->
                <div id="load-more-trigger" style="text-align: center; padding: 40px; display: none;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: var(--primary);"></i>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h3>No items available</h3>
                    <p>Check back soon for delicious new additions!</p>
                </div>
            @endif
        </div>
    </div>

    @if(!empty($cartCount) && $cartCount > 0)
        <div class="floating-cart" onclick="window.location.href='{{ route('cart.index', $name) }}'">
            <div class="cart-info">
                <div class="cart-count-badge">{{ $cartCount }}</div>
                <span>Items in Cart</span>
            </div>
            <div class="view-cart-text">View Cart</div>
            <div class="cart-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    @endif

    <script>
        // Simple scroll effect for header if we had one separate from banner
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('.nav-scroller');
            if (window.scrollY > 150) {
                nav.style.position = 'sticky';
                nav.style.top = '0';
                nav.style.borderRadius = '0';
            } else {
                nav.style.position = 'relative';
                nav.style.borderRadius = '24px 24px 0 0';
            }
        });

        // Infinite Scroll
        let page = 1;
        let hasMore = {{ $products->hasMorePages() ? 'true' : 'false' }};
        let isLoading = false;
        const trigger = document.getElementById('load-more-trigger');
        const grid = document.getElementById('product-grid');

        if (hasMore && trigger) {
            trigger.style.display = 'block';

            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && hasMore && !isLoading) {
                    loadMoreProducts();
                }
            }, { threshold: 0.1 });

            observer.observe(trigger);
        }

        async function loadMoreProducts() {
            if (isLoading) return;
            isLoading = true;
            page++;

            try {
                const url = new URL(window.location.href);
                url.searchParams.set('page', page);
                
                const response = await fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const html = await response.text();
                    if (html.trim().length > 0) {
                        grid.insertAdjacentHTML('beforeend', html);
                    } else {
                        hasMore = false;
                        trigger.style.display = 'none';
                    }
                } else {
                    hasMore = false;
                    trigger.style.display = 'none';
                }
            } catch (error) {
                console.error('Error loading more products:', error);
            } finally {
                isLoading = false;
            }
        }
    </script>
</body>
</html>
