@php
    $locale = app()->getLocale();
    $dir = $locale == 'ar' ? 'rtl' : 'ltr';
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #D4A574;
            --primary-dark: #b88b5c;
            --secondary: #2D3436;
            --bg-color: #F8F9FA;
            --card-bg: #FFFFFF;
            --text-main: #2D3436;
            --text-light: #636E72;
            --success: #00b894;
            --danger: #ff7675;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
            --shadow-md: 0 8px 24px rgba(0,0,0,0.08);
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
            padding-bottom: 120px; /* Space for fixed checkout button */
        }

        [dir="rtl"] body {
            font-family: 'Cairo', sans-serif;
            text-align: right;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ===== Header ===== */
        .header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 16px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: #F0F2F5;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.2s;
        }

        .back-btn:hover {
            background: #E4E7EB;
        }

        [dir="rtl"] .back-btn i {
            transform: rotate(180deg);
        }

        .header-title {
            font-size: 18px;
            font-weight: 700;
        }

        .clear-btn {
            background: none;
            border: none;
            color: var(--danger);
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            padding: 8px;
        }

        /* ===== Cart Items ===== */
        .cart-list {
            margin-top: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .cart-item {
            background: var(--card-bg);
            border-radius: var(--radius-md);
            padding: 16px;
            display: flex;
            gap: 16px;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .item-img {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            background: #f0f0f0;
        }

        .item-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 4px;
        }

        .item-name {
            font-weight: 600;
            font-size: 16px;
            color: var(--text-main);
            padding-right: 20px;
        }

        [dir="rtl"] .item-name {
            padding-right: 0;
            padding-left: 20px;
        }

        .item-variant {
            font-size: 13px;
            color: var(--text-light);
            margin-bottom: 8px;
        }

        .item-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
        }

        .qty-wrapper {
            display: flex;
            align-items: center;
            background: #F0F2F5;
            border-radius: 8px;
            padding: 4px;
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            border: none;
            background: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-main);
            font-size: 14px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .qty-btn:active {
            transform: scale(0.95);
        }

        .qty-val {
            width: 32px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
        }

        .item-price {
            font-weight: 700;
            color: var(--text-main);
            font-size: 16px;
        }

        .remove-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            color: #ccc;
            cursor: pointer;
        }

        .remove-btn:hover {
            color: var(--danger);
        }

        [dir="rtl"] .remove-btn {
            right: auto;
            left: 10px;
        }

        /* ===== Summary ===== */
        .summary-card {
            background: var(--card-bg);
            border-radius: var(--radius-md);
            padding: 20px;
            margin-top: 24px;
            box-shadow: var(--shadow-sm);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 15px;
            color: var(--text-light);
        }

        .summary-row.total {
            margin-top: 16px;
            margin-bottom: 0;
            padding-top: 16px;
            border-top: 1px dashed #e0e0e0;
            font-weight: 700;
            font-size: 18px;
            color: var(--text-main);
        }

        /* ===== Checkout Bar ===== */
        .checkout-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 16px 20px 30px; /* Extra padding for safe area */
            box-shadow: 0 -4px 20px rgba(0,0,0,0.05);
            z-index: 90;
        }

        .checkout-btn {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background: #25D366; /* WhatsApp Green */
            color: white;
            border: none;
            padding: 16px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: transform 0.2s;
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
        }

        .checkout-btn:active {
            transform: scale(0.98);
        }

        /* ===== Empty State ===== */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
        }

        .empty-icon {
            font-size: 64px;
            margin-bottom: 20px;
            color: #e0e0e0;
        }

        .browse-btn {
            margin-top: 24px;
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 100px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(212, 165, 116, 0.4);
        }

        .success-msg {
            background: rgba(0, 184, 148, 0.1);
            color: var(--success);
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 16px;
            text-align: center;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="container">
        <div class="header-content">
            <button class="back-btn" onclick="window.location.href='{{ route('menu.index', $name) }}'">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="header-title">My Cart</div>
            @if($cartItems->count() > 0)
                <button class="clear-btn" onclick="clearCart()">Clear</button>
            @else
                <div style="width: 40px;"></div>
            @endif
        </div>
    </div>
</div>

<div class="container">
    @if(session('success'))
        <div class="success-msg">{{ session('success') }}</div>
    @endif

    @if($cartItems->count() > 0)
        <div class="cart-list">
            @foreach($cartItems as $item)
                <div class="cart-item" id="cart-item-{{ $item->id }}">
                    <button class="remove-btn" onclick="removeItem({{ $item->id }})">
                        <i class="fas fa-times"></i>
                    </button>
                    
                    <img src="{{ $item->product->image ? asset($item->product->image) : 'https://via.placeholder.com/120' }}"
                         alt="{{ $item->product->name }}"
                         class="item-img">
                    
                    <div class="item-info">
                        <div>
                            <div class="item-header">
                                <div class="item-name">{{ $item->product->name }}</div>
                            </div>
                            @if($item->variant)
                                <div class="item-variant">
                                    {{ $item->variant->name }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="item-controls">
                            <div class="qty-wrapper">
                                <button class="qty-btn" onclick="updateQuantity({{ $item->id }}, -1)">
                                    <i class="fas fa-minus" style="font-size: 10px;"></i>
                                </button>
                                <div class="qty-val" id="qty-{{ $item->id }}">{{ $item->quantity }}</div>
                                <button class="qty-btn" onclick="updateQuantity({{ $item->id }}, 1)">
                                    <i class="fas fa-plus" style="font-size: 10px;"></i>
                                </button>
                            </div>
                            <div class="item-price" id="price-{{ $item->id }}">
                                {{ $setting['currency']->value ?? "$" }} {{ number_format($item->subtotal, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="summary-card">
            <div class="summary-row">
                <span>Subtotal</span>
                <span id="subtotal">{{ $setting['currency']->value ?? "$" }} {{ number_format($cart->total, 2) }}</span>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <span id="total">{{ $setting['currency']->value ?? "$" }} {{ number_format($cart->total, 2) }}</span>
            </div>
        </div>

        <div class="checkout-bar">
            <button class="checkout-btn" onclick="shareCartOnWhatsApp()">
                <i class="fab fa-whatsapp" style="font-size: 20px;"></i>
                Send Order via WhatsApp
            </button>
        </div>

    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-shopping-basket"></i>
            </div>
            <h2>Your cart is empty</h2>
            <p>Looks like you haven't added anything yet.</p>
            <button class="browse-btn" onclick="window.location.href='{{ route('menu.index', $name) }}'">
                Start Ordering
            </button>
        </div>
    @endif
</div>

<script>
    async function updateQuantity(itemId, change) {
        const qtyElement = document.getElementById(`qty-${itemId}`);
        let currentQty = parseInt(qtyElement.textContent);
        let newQty = currentQty + change;

        if (newQty < 1) {
            removeItem(itemId);
            return;
        }

        try {
            const response = await fetch(`/cart/${itemId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({quantity: newQty})
            });

            const data = await response.json();

            if (data.success) {
                qtyElement.textContent = newQty;
                document.getElementById(`price-${itemId}`).textContent = `{{ $setting['currency']->value??"$"}} ${data.subtotal}`;
                updateTotals(data.cart_total);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function removeItem(itemId) {
        if (!confirm('Remove this item?')) return;

        try {
            const response = await fetch(`/cart/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                const itemEl = document.getElementById(`cart-item-${itemId}`);
                itemEl.style.opacity = '0';
                itemEl.style.transform = 'translateX(20px)';
                setTimeout(() => {
                    itemEl.remove();
                    if (data.cart_count === 0) {
                        location.reload();
                    } else {
                        updateTotals(data.cart_total);
                    }
                }, 300);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function clearCart() {
        if (!confirm('Clear your entire cart?')) return;

        try {
            const response = await fetch('/cart', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function updateTotals(subtotal) {
        // Assuming no extra fees for now based on previous code logic
        const total = parseFloat(subtotal); 

        document.getElementById('subtotal').textContent = `{{ $setting['currency']->value??"$"}} ${subtotal}`;
        document.getElementById('total').textContent = `{{ $setting['currency']->value??"$"}} ${total.toFixed(2)}`;
    }

    function shareCartOnWhatsApp() {
        const items = document.querySelectorAll('.cart-item');
        if (!items.length) {
            alert('Your cart is empty.');
            return;
        }

        let message = '🛒 *New Order*\n\n';

        items.forEach((item, index) => {
            const name = item.querySelector('.item-name')?.textContent.trim() || '';
            
            let variantName = '';
            const variantEl = item.querySelector('.item-variant');
            if (variantEl) {
                variantName = variantEl.textContent.trim();
            }

            const qty = item.querySelector('.qty-val')?.textContent.trim() || '';
            const price = item.querySelector('.item-price')?.textContent.trim() || '';

            message += `*${qty}x ${name}*\n`;
            if (variantName) message += `   ${variantName}\n`;
            message += `   ${price}\n\n`;
        });

        const total = document.getElementById('total')?.textContent.trim() || '';
        message += `*Total: ${total}*\n`;
        message += `\nPlease confirm my order.`;

        const encoded = encodeURIComponent(message);
        const phone = "{{ env('WHATSAPP_PHONE') }}";
        const whatsappURL = `https://wa.me/${phone}?text=${encoded}`;
        window.open(whatsappURL, '_blank');
    }
</script>
</body>
</html>
