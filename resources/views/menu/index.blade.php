@php
    $locale = app()->getLocale();
    $dir = $locale == 'ar' ? 'rtl' : 'ltr';
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Menu - {{ $selectedCategory }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #3B82F6;
            --primary-dark: #2563EB;
            --secondary: #8B5CF6;
            --accent: #EC4899;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            padding-bottom: 100px;
        }

        [dir="rtl"] body {
            font-family: 'Cairo', sans-serif;
        }

        /* Slider Styles */
        .hero-carousel {
            height: 400px;
            position: relative;
            overflow: hidden;
        }

        .hero-carousel .carousel-item {
            height: 400px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        .hero-carousel .carousel-item::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0) 50%, rgba(0,0,0,0.5) 100%);
            z-index: 1;
        }

        .carousel-indicators {
            z-index: 2;
            margin-bottom: 20px;
        }

        .carousel-indicators [data-bs-target] {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.4);
            border: none;
            transition: all 0.3s ease;
        }

        .carousel-indicators .active {
            width: 30px;
            border-radius: 5px;
            background-color: white;
        }

        /* Restaurant Info Card */
        .restaurant-info-section {
            background: white;
            padding: 2rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .logo-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 14px;
            background: white;
            padding: 4px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .logo-wrapper::after {
            content: '';
            position: absolute;
            inset: -3px;
            background: linear-gradient(135deg, rgba(255,255,255,0.3), rgba(255,255,255,0.05));
            border-radius: 18px;
            z-index: -1;
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

        .tag-pill {
            background: #F8FAFC;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 500;
            border: 1px solid rgba(0,0,0,0.08);
        }

        .contact-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #F8FAFC;
            padding: 8px 16px;
            border-radius: 50px;
            border: 1px solid rgba(0,0,0,0.08);
            text-decoration: none;
            color: #1E293B;
            transition: all 0.3s;
        }

        .contact-item:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .contact-item i {
            color: var(--primary);
        }

        .contact-item:hover i {
            color: white;
        }

        /* Category Pills */
        .category-nav {
            background: white;
            padding: 1rem 0;
            border-radius: 24px 24px 0 0;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-pills .nav-link {
            padding: 10px 20px;
            background: #F0F2F5;
            color: #64748B;
            border-radius: 100px;
            font-weight: 600;
            white-space: nowrap;
            transition: all 0.3s;
            border: 1px solid transparent;
        }

        .nav-pills .nav-link:hover {
            background: #E4E7EB;
            color: #1E293B;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.35);
        }

        /* Product Cards */
        .product-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.15);
            border-color: rgba(0,0,0,0.08);
        }

        .product-img-wrapper {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            overflow: hidden;
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
            transition: all 0.2s;
        }

        .product-card:hover .add-btn {
            background: var(--primary);
            color: white;
            transform: scale(1.1);
        }

        /* Floating Cart */
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
            box-shadow: 0 12px 32px rgba(45, 52, 54, 0.3);
            z-index: 1000;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .floating-cart:active {
            transform: translateX(-50%) scale(0.98);
        }

        .cart-count-badge {
            background: var(--primary);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .hero-carousel,
            .hero-carousel .carousel-item {
                height: 300px;
            }
        }
    </style>
</head>
<body>

    {{-- Bootstrap Carousel Slider --}}
    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @if(!empty($sliders) && count($sliders) > 0)
                @foreach($sliders as $index => $slider)
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" 
                            class="{{ $index === 0 ? 'active' : '' }}" 
                            aria-current="{{ $index === 0 ? 'true' : 'false' }}" 
                            aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            @else
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
            @endif
        </div>
        
        <div class="carousel-inner">
            @if(!empty($sliders) && count($sliders) > 0)
                @foreach($sliders as $index => $slider)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" 
                         style="background-image: url('/storage/{{ $slider->image_path }}');">
                    </div>
                @endforeach
            @else
                {{-- Fallback slides --}}
                <div class="carousel-item active" style="background-image: url('https://images.unsplash.com/photo-1529006557810-274b9b2fc783?w=1200');"></div>
                <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=1200');"></div>
                <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1601050690597-df0568f70950?w=1200');"></div>
                <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1603360946369-dc9bb6258143?w=1200');"></div>
            @endif
        </div>
    </div>

    {{-- Restaurant Info Section --}}
    <div class="restaurant-info-section">
        <div class="container">
            <div class="d-flex align-items-start gap-3">
                <div class="logo-wrapper flex-shrink-0">
                    @if(!empty($user->logo))
                        <img src="{{ $user->logo }}" alt="Logo" class="logo-img">
                    @else
                        <div class="logo-placeholder">
                            {{ strtoupper(substr($restaurant['business_name']->value ?? 'R', 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <h1 class="fs-4 fw-bold mb-2">{{ $restaurant['business_name']->value ?? 'Restaurant Name' }}</h1>
                    <div class="d-flex flex-wrap gap-2">
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

            {{-- Contact Info --}}
            @if(isset($restaurant['phone_number']) || isset($restaurant['contact_email']) || isset($restaurant['address']))
                <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
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

    {{-- Category Navigation --}}
    <div class="category-nav">
        <div class="container">
            <ul class="nav nav-pills flex-nowrap overflow-auto">
                @foreach($categories as $category)
                    <li class="nav-item">
                        <a href="{{ route('menu.index', ['name'=>$name, 'category' => $category->name] + request()->only('lang')) }}"
                           class="nav-link {{ $category->name === $selectedCategory ? 'active' : '' }}">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Products Section --}}
    <div class="container py-4">
        <h2 class="fs-3 fw-bold mb-4 d-flex align-items-center gap-3">
            <span style="width: 4px; height: 24px; background: var(--primary); border-radius: 4px;"></span>
            {{ $selectedCategory }}
        </h2>

        @if($products->count() > 0)
            <div class="row g-4" id="product-grid">
                @include('menu.partials.products')
            </div>

            <!-- Load More Trigger -->
            <div id="load-more-trigger" class="text-center py-5" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-utensils fs-1 mb-3 opacity-50"></i>
                <h3 class="fs-5 fw-bold">No items available</h3>
                <p>Check back soon for delicious new additions!</p>
            </div>
        @endif
    </div>

    {{-- Floating Cart --}}
    @if(!empty($cartCount) && $cartCount > 0)
        <div class="floating-cart" onclick="window.location.href='{{ route('cart.index', $name) }}{{ request()->has('lang') ? '?lang=' . request('lang') : '' }}'">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3 fw-semibold">
                    <div class="cart-count-badge">{{ $cartCount }}</div>
                    <span>Items in Cart</span>
                </div>
                <div class="fw-semibold">View Cart</div>
                <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </div>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Initialize carousel with autoplay
        const carousel = new bootstrap.Carousel(document.getElementById('heroCarousel'), {
            interval: 5000,
            ride: 'carousel'
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
