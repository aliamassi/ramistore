<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            overflow-x: hidden;
        }

        /* Hero Image Section */
        .hero-section {
            position: relative;
            width: 100%;
            height: 60vh;
            min-height: 400px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0) 100%);
        }

        /* Header Controls */
        .header-controls {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 50px 20px 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            z-index: 10;
        }

        .back-btn, .menu-btn {
            width: 50px;
            height: 50px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            border: none;
        }

        .back-btn:hover, .menu-btn:hover {
            transform: scale(1.05);
        }

        .back-btn::before {
            content: '‹';
            font-size: 32px;
            color: #333;
            font-weight: 300;
        }

        .menu-btn::after {
            content: '⋯';
            font-size: 28px;
            color: #333;
            font-weight: 600;
            letter-spacing: 2px;
        }

        /* Time Badge */
        .time-badge {
            position: absolute;
            top: 120px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(255, 255, 255, 0.95);
            padding: 12px 30px;
            border-radius: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .time-label {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            text-align: center;
        }

        .time-value {
            font-size: 14px;
            color: #666;
            text-align: center;
            margin-top: 2px;
        }

        /* Content Section */
        .content-section {
            background-color: #fff;
            border-radius: 30px 30px 0 0;
            margin-top: -30px;
            position: relative;
            z-index: 5;
            padding: 30px 20px;
        }

        .item-title {
            font-size: 32px;
            font-weight: 700;
            color: #222;
            margin-bottom: 15px;
        }

        .item-description {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        /* Quick Choices Section */
        .quick-choices {
            background-color: #fdf4ed;
            padding: 25px 20px;
            border-radius: 20px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #222;
            margin-bottom: 5px;
        }

        .section-subtitle {
            font-size: 14px;
            color: #999;
            margin-bottom: 20px;
        }

        .choices-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .choice-card {
            background-color: #fff;
            border-radius: 15px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .choice-card:hover {
            border-color: #d4a574;
        }

        .choice-card.selected {
            border-color: #d4a574;
            background-color: #fffbf7;
        }

        .choice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .orders-badge {
            display: flex;
            align-items: center;
            font-size: 13px;
            color: #d4a574;
            font-weight: 600;
        }

        .orders-badge::before {
            content: '🔥';
            margin-right: 5px;
        }

        .radio-btn {
            width: 24px;
            height: 24px;
            border: 2px solid #ddd;
            border-radius: 50%;
            position: relative;
            transition: all 0.2s;
        }

        .choice-card.selected .radio-btn {
            border-color: #d4a574;
        }

        .choice-card.selected .radio-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 12px;
            height: 12px;
            background-color: #d4a574;
            border-radius: 50%;
        }

        .choice-name {
            font-size: 16px;
            font-weight: 600;
            color: #222;
            margin-bottom: 5px;
        }

        .choice-details {
            font-size: 13px;
            color: #999;
        }

        /* Desktop Styles */
        @media (min-width: 768px) {
            .hero-section {
                height: 70vh;
                min-height: 500px;
            }

            .content-section {
                max-width: 800px;
                margin-left: auto;
                margin-right: auto;
                padding: 40px;
            }

            .item-title {
                font-size: 42px;
            }

            .item-description {
                font-size: 18px;
            }

            .choices-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .choices-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Add to Cart Button */
        .cart-section {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 100;
        }

        .add-to-cart-btn {
            width: 100%;
            background-color: #d4a574;
            color: #fff;
            border: none;
            padding: 18px;
            border-radius: 15px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .add-to-cart-btn:hover {
            background-color: #c49564;
            transform: translateY(-2px);
        }

        .price-display {
            margin-left: 10px;
            font-weight: 700;
        }

        @media (min-width: 768px) {
            .cart-section {
                position: static;
                max-width: 800px;
                margin: 0 auto;
                box-shadow: none;
                padding: 20px 40px 40px;
            }
        }
    </style>
</head>
<body>
<!-- Hero Image Section -->
<div class="hero-section" style="background-image: url('{{ $item->image ? asset($item->image) : 'https://via.placeholder.com/800x600?text=Menu+Item' }}');">
    <div class="hero-overlay"></div>

    <!-- Header Controls -->
    <div class="header-controls">
        <button class="back-btn" onclick="window.history.back()"></button>
        <button class="menu-btn"></button>
    </div>

    <!-- Time Badge -->
    <div class="time-badge">
        <div class="time-label">Today</div>
        <div class="time-value">{{ date('g:i A') }}</div>
    </div>
</div>

<!-- Content Section -->
<div class="content-section">
    <h1 class="item-title">{{ $item->name }}</h1>
    <p class="item-description">{{ $item->description }}</p>

    <!-- Quick Choices Section -->
    @if($item->customizable && $item->customizationOptions->count() > 0)
        <div class="quick-choices">
            <h2 class="section-title">Try these quick choices</h2>
            <p class="section-subtitle">You can edit your choices from the list</p>

            <div class="choices-grid">
                @foreach($item->customizationOptions as $index => $option)
                    <div class="choice-card {{ $option->is_default ? 'selected' : '' }}"
                         data-option-id="{{ $option->id }}"
                         onclick="selectChoice(this)">
                        <div class="choice-header">
                            <span class="orders-badge">{{ $option->formatted_orders }}</span>
                            <div class="radio-btn"></div>
                        </div>
                        <div class="choice-name">{{ $option->name }}</div>
                        <div class="choice-details">{{ $option->detail }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div style="height: 100px;"></div>
</div>

<!-- Add to Cart Section -->
<div class="cart-section">
    <button class="add-to-cart-btn" onclick="addToCart()">
        Add to cart
        <span class="price-display">{{ $setting['currency']->value??"$"}} {{ number_format($item->price, 2) }}</span>
    </button>
</div>

<script>
    let selectedOptionId = null;

    function selectChoice(element) {
        // Remove selected class from all cards
        document.querySelectorAll('.choice-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Add selected class to clicked card
        element.classList.add('selected');

        // Store the selected option ID (from data attribute)
        selectedOptionId = element.dataset.optionId;
    }

    async function addToCart() {
        const btn = document.querySelector('.add-to-cart-btn');
        const originalText = btn.innerHTML;

        // Disable button and show loading
        btn.disabled = true;
        btn.innerHTML = 'Adding...';

        try {
            const formData = new FormData();
            formData.append('product_id', '{{ $item->id }}');
            formData.append('quantity', 1);

            if (selectedOptionId) {
                formData.append('customization_option_id', selectedOptionId);
            }

            const response = await fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Show success message
                btn.innerHTML = '✓ Added to Cart!';
                btn.style.backgroundColor = '#28a745';

                // Update cart count if you have a cart icon
                updateCartCount(data.cart_count);

                // Redirect to cart after 1 second
                setTimeout(() => {
                    window.location.href = '{{ route("cart.index") }}';
                }, 1000);
            } else {
                throw new Error(data.message || 'Failed to add item to cart');
            }
        } catch (error) {
            console.error('Error:', error);
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('Failed to add item to cart. Please try again.');
        }
    }

    function updateCartCount(count) {
        // Update cart count badge if exists
        const cartBadge = document.querySelector('.cart-count');
        if (cartBadge) {
            cartBadge.textContent = count;
        }
    }

    // Initialize with default selected option
    document.addEventListener('DOMContentLoaded', function() {
        const defaultSelected = document.querySelector('.choice-card.selected');
        if (defaultSelected) {
            selectedOptionId = defaultSelected.dataset.optionId;
        }
    });

    // Prevent body scroll when at top
    let lastScrollTop = 0;
    window.addEventListener('scroll', function() {
        let st = window.pageYOffset || document.documentElement.scrollTop;
        lastScrollTop = st <= 0 ? 0 : st;
    }, false);
</script>
</body>
</html>
