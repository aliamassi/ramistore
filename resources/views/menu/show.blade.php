@php
    $locale = app()->getLocale();
    $dir = $locale == 'ar' ? 'rtl' : 'ltr';
@endphp
        <!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $item->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3B82F6;
            --primary-dark: #2563EB;
            --primary-light: #60A5FA;
            --secondary: #8B5CF6;
            --secondary-dark: #7C3AED;
            --accent: #EC4899;
            --bg-color: #F8FAFC;
            --card-bg: #FFFFFF;
            --text-main: #1E293B;
            --text-light: #64748B;
            --success: #10B981;
            --shadow-sm: 0 2px 8px rgba(59, 130, 246, 0.1);
            --shadow-md: 0 8px 24px rgba(59, 130, 246, 0.15);
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-xl: 32px;
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
            padding-bottom: 100px; /* Space for bottom bar */
        }

        [dir="rtl"] body {
            font-family: 'Cairo', sans-serif;
            text-align: right;
        }

        /* ===== Hero Section ===== */
        .hero-section {
            position: relative;
            width: 100%;
            height: 45vh;
            min-height: 350px;
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
            background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0) 50%, rgba(0,0,0,0.6) 100%);
        }

        .header-controls {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            z-index: 10;
        }

        .control-btn {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .control-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        [dir="rtl"] .fa-arrow-left {
            transform: rotate(180deg);
        }

        /* ===== Content Card ===== */
        .content-card {
            background: var(--card-bg);
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
            margin-top: -40px;
            position: relative;
            z-index: 5;
            padding: 32px 24px;
            min-height: 60vh;
            box-shadow: 0 -10px 40px rgba(0,0,0,0.1);
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .item-header {
            margin-bottom: 24px;
        }

        .item-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .item-desc {
            font-size: 16px;
            color: var(--text-light);
            line-height: 1.6;
        }

        /* ===== Options / Variants ===== */
        .section-label {
            font-size: 18px;
            font-weight: 700;
            margin: 32px 0 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .required-badge {
            background: #F0F2F5;
            color: var(--text-light);
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 100px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .options-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .option-card {
            background: white;
            border: 2px solid #F0F2F5;
            border-radius: var(--radius-md);
            padding: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .option-card:hover {
            border-color: #e0e0e0;
        }

        .option-card.selected {
            border-color: var(--primary);
            background: rgba(212, 165, 116, 0.05);
        }

        .option-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .option-name {
            font-weight: 600;
            font-size: 16px;
            color: var(--text-main);
        }

        .option-price {
            font-size: 14px;
            color: var(--text-light);
            font-weight: 500;
        }

        .check-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #D1D5DB;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .option-card.selected .check-circle {
            border-color: var(--primary);
            background: var(--primary);
        }

        .check-circle::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: white;
            font-size: 12px;
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.2s;
        }

        [dir="rtl"] .option-card {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .option-info {
            align-items: flex-end;
            text-align: right;
        }

        .option-card.selected .check-circle::after {
            opacity: 1;
            transform: scale(1);
        }

        /* ===== Comments ===== */
        .comments-wrapper {
            margin-top: 32px;
        }

        .comments-input {
            width: 100%;
            background: #F8F9FA;
            border: 2px solid #F0F2F5;
            border-radius: var(--radius-md);
            padding: 16px;
            font-family: inherit;
            font-size: 15px;
            resize: vertical;
            min-height: 100px;
            outline: none;
            transition: border-color 0.2s;
        }

        .comments-input:focus {
            border-color: var(--primary);
            background: white;
        }

        /* ===== Bottom Bar ===== */
        .bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 16px 24px 32px;
            box-shadow: 0 -4px 30px rgba(0,0,0,0.08);
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .qty-control {
            display: flex;
            align-items: center;
            background: #F0F2F5;
            border-radius: 14px;
            padding: 4px;
            height: 56px;
        }

        .qty-btn {
            width: 48px;
            height: 100%;
            border: none;
            background: transparent;
            font-size: 18px;
            color: var(--text-main);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-val {
            width: 32px;
            text-align: center;
            font-weight: 700;
            font-size: 18px;
        }

        .add-btn {
            flex: 1;
            height: 56px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            transition: transform 0.2s;
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.35);
        }

        .add-btn:active {
            transform: scale(0.98);
        }

        .add-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* Desktop Responsive */
        @media (min-width: 768px) {
            .hero-section {
                height: 50vh;
            }

            .content-card {
                max-width: 900px;
                margin: -60px auto 0;
                border-radius: var(--radius-xl);
                padding: 48px;
            }

            .item-title {
                font-size: 36px;
            }

            .options-list {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .bottom-bar {
                padding: 24px 40px;
                justify-content: center;
            }

            .bottom-bar-inner {
                width: 100%;
                max-width: 900px;
                display: flex;
                gap: 24px;
            }
        }
    </style>
</head>
<body>

<!-- Hero Section -->
<div class="hero-section" style="background-image: url('{{ $item->image ? asset($item->image) : 'https://via.placeholder.com/800x600?text=Delicious+Food' }}');">
    <div class="hero-overlay"></div>
    <div class="header-controls">
        <button class="control-btn" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i>
        </button>
        <button class="control-btn">
            <i class="fas fa-share-alt"></i>
        </button>
    </div>
</div>

<!-- Content Card -->
<div class="content-card">
    <div class="container">
        <div class="item-header">
            <h1 class="item-title">{{ $item->name }}</h1>
            <p class="item-desc">{{ $item->description }}</p>
        </div>

        <form id="add-to-cart-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $item->id }}">
            <input type="hidden" name="quantity" id="form-qty" value="1">

            {{-- VARIANTS (type = variants) --}}
            @if($item->type === 'variants' && $item->variants->count() > 0)
                <div class="section-label">
                    <span>Choose Option</span>
                    <span class="required-badge">Required</span>
                </div>
                <div class="options-list">
                    @foreach($item->variants as $index => $variant)
                        <div class="option-card {{ $index === 0 ? 'selected' : '' }}"
                             onclick="selectOption(this, '{{ $variant->id }}', {{ $variant->price }})">
                            <div class="option-info">
                                <span class="option-name">{{ $variant->name }}</span>
                                <span class="option-price">{{ $setting['currency']->value ?? '$' }} {{ number_format($variant->price, 2) }}</span>
                            </div>
                            <div class="check-circle"></div>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="variant_id" id="selected-variant-id" value="{{ $item->variants->first()->id }}">
                <input type="hidden" id="current-price" value="{{ $item->variants->first()->price }}">

                {{-- SIMPLE PRODUCT OPTIONS --}}
            @elseif($item->customizable && $item->customizationOptions->count() > 0)
                <div class="section-label">
                    <span>Quick Choices</span>
                    <span class="required-badge">Optional</span>
                </div>
                <div class="options-list">
                    @foreach($item->customizationOptions as $option)
                        <div class="option-card {{ $option->is_default ? 'selected' : '' }}"
                             onclick="selectOption(this, '{{ $option->id }}', {{ $item->price }})">
                            <div class="option-info">
                                <span class="option-name">{{ $option->name }}</span>
                                <span class="option-price">{{ $option->detail }}</span>
                            </div>
                            <div class="check-circle"></div>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="customization_option_id" id="selected-option-id" value="{{ $item->customizationOptions->where('is_default', true)->first()->id ?? '' }}">
                <input type="hidden" id="current-price" value="{{ $item->price }}">
            @else
                <input type="hidden" id="current-price" value="{{ $item->price }}">
            @endif

            <div class="comments-wrapper">
                <div class="section-label">Special Instructions</div>
                <textarea name="comments" class="comments-input" placeholder="Add a note for the kitchen (e.g. no onions, extra spicy)..."></textarea>
            </div>
        </form>
    </div>
</div>

<!-- Bottom Bar -->
<div class="bottom-bar">
    <div class="bottom-bar-inner" style="width: 100%; display: flex; gap: 16px; max-width: 800px; margin: 0 auto;">
        <div class="qty-control">
            <button class="qty-btn" onclick="updateQty(-1)">
                <i class="fas fa-minus" style="font-size: 14px;"></i>
            </button>
            <div class="qty-val" id="display-qty">1</div>
            <button class="qty-btn" onclick="updateQty(1)">
                <i class="fas fa-plus" style="font-size: 14px;"></i>
            </button>
        </div>

        <button class="add-btn" onclick="addToCart()">
            <span>Add to Cart</span>
            <span id="total-price">{{ $setting['currency']->value ?? '$' }} {{ number_format($item->type === 'variants' ? $item->variants->first()->price : $item->price, 2) }}</span>
        </button>
    </div>
</div>

<script>
    let quantity = 1;

    function updateQty(change) {
        quantity += change;
        if (quantity < 1) quantity = 1;

        document.getElementById('display-qty').textContent = quantity;
        document.getElementById('form-qty').value = quantity;
        updateTotal();
    }

    function selectOption(element, id, price) {
        // Deselect all
        document.querySelectorAll('.option-card').forEach(el => el.classList.remove('selected'));

        // Select clicked
        element.classList.add('selected');

        // Update hidden inputs
        const variantInput = document.getElementById('selected-variant-id');
        if (variantInput) variantInput.value = id;

        const optionInput = document.getElementById('selected-option-id');
        if (optionInput) optionInput.value = id;

        // Update price
        document.getElementById('current-price').value = price;
        updateTotal();
    }

    function updateTotal() {
        const price = parseFloat(document.getElementById('current-price').value);
        const total = price * quantity;
        document.getElementById('total-price').textContent = `{{ $setting['currency']->value ?? '$' }} ${total.toFixed(2)}`;
    }

    async function addToCart() {
        const btn = document.querySelector('.add-btn');
        const originalContent = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<span>Adding...</span>';

        try {
            const form = document.getElementById('add-to-cart-form');
            const formData = new FormData(form);

            const response = await fetch('{{ route("cart.add", $name) }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                btn.style.background = '#00b894';
                btn.innerHTML = '<span><i class="fas fa-check"></i> Added</span>';

                const langParam = new URLSearchParams(window.location.search).get('lang');
                const cartUrl = '{{ route("cart.index", $name) }}' + (langParam ? '?lang=' + langParam : '');

                setTimeout(() => {
                    window.location.href = cartUrl;
                }, 800);
            } else {
                throw new Error(data.message || 'Failed to add to cart');
            }
        } catch (error) {
            console.error(error);
            btn.style.background = '#ff7675';
            btn.innerHTML = '<span>Error</span>';
            setTimeout(() => {
                btn.disabled = false;
                btn.style.background = ''; // Reset to CSS default (primary)
                btn.innerHTML = originalContent;
            }, 2000);
        }
    }
</script>
</body>
</html>
