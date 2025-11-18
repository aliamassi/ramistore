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
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0) 100%);
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

        /* ===== Variants layout (type = 'variants') ===== */

        .variant-section {
            margin-top: 24px;
        }

        .variant-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            font-size: 14px;
            color: #777;
        }

        .badge-required {
            background-color: #f3f4f6;
            color: #444;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .variant-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        /* the outer container also keeps class "choice-card" so existing JS works */
        .variant-card {
            cursor: pointer;
        }

        .variant-card-inner {
            border-radius: 16px;
            border: 2px solid #e0e0e0;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
            background-color: #fff;
        }

        .variant-text {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .variant-name {
            font-size: 16px;
            font-weight: 600;
            color: #222;
        }

        .variant-price {
            font-size: 14px;
            color: #555;
        }

        .variant-radio {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #cfd4e6;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .variant-radio-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #3366ff;
            opacity: 0;
            transition: opacity 0.2s;
        }

        /* Selected state */
        .variant-card.choice-card.selected .variant-card-inner {
            border-color: #3366ff;
            background-color: #f7f9ff;
            box-shadow: 0 0 0 1px rgba(51, 102, 255, 0.15);
        }

        .variant-card.choice-card.selected .variant-radio {
            border-color: #3366ff;
        }

        .variant-card.choice-card.selected .variant-radio-dot {
            opacity: 1;
        }

        /* Comments */
        .comments-section {
            margin-top: 20px;
        }

        .comments-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #222;
        }

        .comments-input {
            width: 100%;
            border-radius: 14px;
            border: 1px solid #e0e0e0;
            padding: 10px 12px;
            font-size: 14px;
            resize: vertical;
            min-height: 80px;
            outline: none;
        }

        .comments-input::placeholder {
            color: #b3b3b3;
        }

        .comments-input:focus {
            border-color: #3366ff;
            box-shadow: 0 0 0 1px rgba(51, 102, 255, 0.12);
        }

        /* Bottom bar for variants */
        .variant-bottom-bar {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ffffff;
            border-top: 1px solid #e5e5e5;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 90;
        }

        .variant-qty-control {
            display: flex;
            align-items: center;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            overflow: hidden;
            background-color: #fff;
        }

        .variant-qty-btn {
            width: 40px;
            height: 40px;
            border: none;
            background-color: #ffffff;
            font-size: 20px;
            font-weight: 400;
            cursor: pointer;
        }

        .variant-qty-btn:hover {
            background-color: #f5f5f5;
        }

        .variant-qty-value {
            min-width: 32px;
            text-align: center;
            font-weight: 600;
            font-size: 16px;
            padding: 0 10px;
        }

        .variant-add-btn {
            flex: 1;
            border: none;
            border-radius: 15px;
            background-color: #3366ff;
            color: #ffffff;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .variant-add-btn:hover {
            background-color: #2853d4;
            transform: translateY(-1px);
        }

        /* Add to Cart Button */
        .cart-section {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
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

            .variant-bottom-bar {
                position: static;
                margin-top: 24px;
                border-top: none;
                padding: 0;
            }

            .cart-section {
                position: static;
                max-width: 800px;
                margin: 0 auto;
                box-shadow: none;
                padding: 20px 40px 40px;
            }
        }

        @media (min-width: 1024px) {
            .choices-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

    </style>
</head>
<body>
<!-- Hero Image Section -->
<div class="hero-section"
     style="background-image: url('{{ $item->image ? asset($item->image) : 'https://via.placeholder.com/800x600?text=Menu+Item' }}');">
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

    {{-- VARIANTS LAYOUT (type = variants) --}}
    @if($item->type === 'variants' && $item->variants->count() > 0)
        <div class="variant-section">
            <div class="variant-header">
                <span>Select minimum 1 option</span>
                <span class="badge-required">Required</span>
            </div>

            <div class="variant-list">
                @foreach($item->variants as $index => $variant)
                    <div class="variant-card choice-card {{ $index === 0 ? 'selected' : '' }}"
                         data-option-id="{{ $variant->id }}"
                         data-price="{{ $variant->price }}">
                        <div class="variant-card-inner" onclick="selectChoice(this.parentElement)">
                            <div class="variant-text">
                                <div class="variant-name">{{ $variant->name }}</div>
                                <div class="variant-price">
                                    {{ $setting['currency']->value ?? '$' }}
                                    {{ number_format($variant->price, 2) }}
                                </div>
                            </div>
                            <div class="variant-radio">
                                <span class="variant-radio-dot"></span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Comments --}}
            <div class="comments-section">
                <label for="variant-comments" class="comments-label">Comments</label>
                <textarea
                        id="variant-comments"
                        name="comments"
                        class="comments-input"
                        rows="3"
                        placeholder="(Optional)"
                ></textarea>
            </div>
        </div>
    @else
        {{-- OLD quick-choices block for simple items – keep as it is --}}
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
    @endif


    <div style="height: 100px;"></div>
</div> {{-- end .content-section --}}


@if($item->type === 'variants' && $item->customizationOptions->count() > 0)
    <div class="variant-bottom-bar">
        <div class="variant-qty-control">
            <button type="button" class="variant-qty-btn" onclick="changeVariantQty(-1)">−</button>
            <span id="variant-qty" class="variant-qty-value">1</span>
            <button type="button" class="variant-qty-btn" onclick="changeVariantQty(1)">+</button>
        </div>

        <button type="button" class="variant-add-btn" onclick="addToCart()">
            Add {{ $setting['currency']->value ?? '$' }}
            <span id="variant-add-price">0.00</span>
        </button>

        <input type="hidden" id="variant-qty-input" value="1">
    </div>
@else
    <!-- Add to Cart Section (simple items – unchanged) -->
    @if($item->type === 'variants' && $item->variants->count() > 0)
        {{-- VARIANTS bottom bar --}}
        <div class="variant-bottom-bar">
            <div class="variant-qty-control">
                <button type="button" class="variant-qty-btn" onclick="changeVariantQty(-1)">−</button>
                <span id="variant-qty" class="variant-qty-value">1</span>
                <button type="button" class="variant-qty-btn" onclick="changeVariantQty(1)">+</button>
            </div>

            <button type="button" class="variant-add-btn" onclick="addToCart()">
                Add {{ $setting['currency']->value ?? '$' }}
                <span id="variant-add-price">0.00</span>
            </button>

            <input type="hidden" id="variant-qty-input" value="1">
        </div>
    @else
        {{-- SIMPLE product – keep old button --}}
        <div class="cart-section">
            <button class="add-to-cart-btn" onclick="addToCart()">
                Add to cart
                <span class="price-display">
                {{ $setting['currency']->value??"$"}} {{ number_format($item->price, 2) }}
            </span>
            </button>
        </div>
    @endif

@endif


<script>
    // true if this product uses variants (product->variants)
    const hasVariants = @json($item->type === 'variants' && $item->variants->count() > 0);

    let selectedOptionId = null;   // used for quick choices (simple)
    let selectedVariantId = null;  // used for variants

    function selectChoice(element) {
        // Remove selected class from all cards
        document.querySelectorAll('.choice-card').forEach(card => {
            card.classList.remove('selected');
        });

        // For variants, element may be inner div
        const card = element.classList.contains('choice-card')
            ? element
            : element.closest('.choice-card');

        if (!card) return;

        card.classList.add('selected');
        selectedOptionId = card.dataset.optionId;
        selectedVariantId = card.dataset.optionId; // same value for variants

        if (hasVariants) {
            updateVariantPrice();
        }
    }

    function changeVariantQty(delta) {
        if (!hasVariants) return;

        const qtyEl    = document.getElementById('variant-qty');
        const qtyInput = document.getElementById('variant-qty-input');
        if (!qtyEl || !qtyInput) return;

        let current = parseInt(qtyEl.textContent) || 1;
        current += delta;
        if (current < 1) current = 1;

        qtyEl.textContent = current;
        qtyInput.value = current;

        updateVariantPrice();
    }

    function updateVariantPrice() {
        if (!hasVariants) return;

        const qtyEl      = document.getElementById('variant-qty');
        const addPriceEl = document.getElementById('variant-add-price');
        if (!qtyEl || !addPriceEl) return;

        const qty = parseInt(qtyEl.textContent) || 1;
        const selectedCard = document.querySelector('.choice-card.selected');
        const price = selectedCard ? parseFloat(selectedCard.dataset.price || '0') : 0;

        const total = (qty * price).toFixed(2);
        addPriceEl.textContent = total;
    }

    async function addToCart() {
        const btn = document.querySelector('.variant-add-btn') || document.querySelector('.add-to-cart-btn');
        if (!btn) return;

        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = 'Adding...';

        try {
            const formData = new FormData();
            formData.append('product_id', '{{ $item->id }}');

            if (hasVariants) {
                const qtyInput = document.getElementById('variant-qty-input');
                let qty = qtyInput ? parseInt(qtyInput.value) || 1 : 1;
                formData.append('quantity', qty);

                if (selectedVariantId) {
                    // backend: treat this as variant_id
                    formData.append('variant_id', selectedVariantId);
                }

                const commentsEl = document.getElementById('variant-comments');
                if (commentsEl && commentsEl.value.trim() !== '') {
                    formData.append('comments', commentsEl.value.trim());
                }
            } else {
                formData.append('quantity', 1);

                if (selectedOptionId) {
                    // backend: your old customization option id
                    formData.append('customization_option_id', selectedOptionId);
                }
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
                btn.innerHTML = '✓ Added to Cart!';
                btn.style.backgroundColor = '#28a745';

                updateCartCount(data.cart_count ?? 0);

                setTimeout(() => {
                    window.location.href = '{{ route("cart.index") }}';
                }, 800);
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
        const cartBadge = document.querySelector('.cart-count');
        if (cartBadge) {
            cartBadge.textContent = count;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // default selected: first variant or default quick choice
        const defaultSelected = document.querySelector('.choice-card.selected');
        if (defaultSelected) {
            selectedOptionId = defaultSelected.dataset.optionId;
            selectedVariantId = defaultSelected.dataset.optionId;
        }

        if (hasVariants) {
            updateVariantPrice();
        }
    });

    // keep your scroll handler
    let lastScrollTop = 0;
    window.addEventListener('scroll', function () {
        let st = window.pageYOffset || document.documentElement.scrollTop;
        lastScrollTop = st <= 0 ? 0 : st;
    }, false);
</script>

</body>
</html>
