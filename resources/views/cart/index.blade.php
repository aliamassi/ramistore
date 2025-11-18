<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shopping Cart</title>
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
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background-color: #fff;
            border-bottom: 1px solid #e0e0e0;
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 15px 20px;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .back-btn {
            width: 40px;
            height: 40px;
            background-color: transparent;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 28px;
            color: #333;
        }

        .header-title {
            font-size: 22px;
            font-weight: 600;
            color: #222;
        }

        .clear-btn {
            background: none;
            border: none;
            color: #d4a574;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
        }

        /* Content */
        .content {
            padding: 20px;
            min-height: calc(100vh - 200px);
        }

        .empty-cart {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-cart-icon {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-cart-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #222;
        }

        .empty-cart-text {
            font-size: 16px;
            color: #999;
            margin-bottom: 30px;
        }

        .browse-btn {
            background-color: #d4a574;
            color: #fff;
            border: none;
            padding: 14px 40px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .browse-btn:hover {
            background-color: #c49564;
            transform: translateY(-2px);
        }

        /* Cart Items */
        .cart-items {
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .cart-item {
            display: flex;
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 15px;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            background-color: #f0f0f0;
            flex-shrink: 0;
        }

        .item-details {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .item-name {
            font-size: 18px;
            font-weight: 600;
            color: #222;
            margin-bottom: 5px;
        }

        .item-customization {
            font-size: 14px;
            color: #999;
            margin-bottom: 10px;
        }

        .item-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 15px;
            background-color: #f5f5f5;
            border-radius: 8px;
            padding: 5px 10px;
        }

        .qty-btn {
            background: none;
            border: none;
            font-size: 20px;
            color: #d4a574;
            cursor: pointer;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover {
            opacity: 0.7;
        }

        .quantity {
            font-size: 16px;
            font-weight: 600;
            color: #222;
            min-width: 30px;
            text-align: center;
        }

        .item-price {
            font-size: 18px;
            font-weight: 600;
            color: #222;
        }

        .remove-btn {
            background: none;
            border: none;
            color: #ff4444;
            font-size: 14px;
            cursor: pointer;
            margin-top: 10px;
        }

        /* Summary */
        .cart-summary {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            font-size: 16px;
        }

        .summary-row.total {
            border-top: 2px solid #f0f0f0;
            margin-top: 10px;
            padding-top: 20px;
            font-size: 20px;
            font-weight: 700;
        }

        /* Checkout Button */
        .checkout-section {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 100;
        }

        .checkout-btn {
            width: 100%;
            background-color: #25D366; /* WhatsApp green */
            color: #fff;
            border: none;
            padding: 18px;
            border-radius: 15px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .checkout-btn:hover {
            background-color: #1ebe5b;
            transform: translateY(-2px);
        }

        .checkout-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        /* Desktop */
        @media (min-width: 768px) {
            .content {
                padding: 40px;
                display: grid;
                grid-template-columns: 1fr 400px;
                gap: 30px;
                align-items: start;
            }

            .cart-items {
                margin-bottom: 0;
            }

            .item-image {
                width: 120px;
                height: 120px;
            }

            .checkout-section {
                position: static;
                box-shadow: none;
                padding: 0;
                grid-column: 2;
            }

            .cart-summary {
                margin-bottom: 20px;
            }
        }

        .success-message {
            background-color: #d4f4dd;
            color: #1e7e34;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<!-- Header -->
<div class="header">
    <div class="container">
        <div class="header-content">
            <button class="back-btn" onclick="window.location.href='{{ route('menu.index') }}'">‹</button>
            <h1 class="header-title">Shopping Cart</h1>
            @if($cartItems->count() > 0)
                <button class="clear-btn" onclick="clearCart()">Clear</button>
            @else
                <span style="width: 40px;"></span>
            @endif
        </div>
    </div>
</div>

<div class="container">
    <div class="content">
        @if(session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        @if($cartItems->count() > 0)
            <!-- Cart Items -->
            <div>
                <div class="cart-items">
                    @foreach($cartItems as $item)
                        <div class="cart-item" id="cart-item-{{ $item->id }}">
                            <img src="{{ $item->product->image ? asset($item->product->image) : 'https://via.placeholder.com/120' }}"
                                 alt="{{ $item->product->name }}"
                                 class="item-image">

                            <div class="item-details">
                                <div class="item-name">{{ $item->product->name }}</div>
                                @if($item->customization_name)
                                    <div class="item-customization">
                                        {{ $item->customization_name }} - {{ $item->customization_detail }}
                                    </div>
                                @endif

                                <div class="item-actions">
                                    <div class="quantity-control">
                                        <button class="qty-btn" onclick="updateQuantity({{ $item->id }}, -1)">−</button>
                                        <span class="quantity" id="qty-{{ $item->id }}">{{ $item->quantity }}</span>
                                        <button class="qty-btn" onclick="updateQuantity({{ $item->id }}, 1)">+</button>
                                    </div>
                                    <div class="item-price" id="price-{{ $item->id }}">
                                        {{ $setting['currency']->value??"$"}} {{ number_format($item->subtotal, 2) }}
                                    </div>
                                </div>

                                <button class="remove-btn" onclick="removeItem({{ $item->id }})">Remove</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Summary & Checkout -->
            <div>
                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="subtotal">{{ $setting['currency']->value??"$"}} {{ number_format($cart->total, 2) }}</span>
                    </div>
{{--                    <div class="summary-row">--}}
{{--                        <span>Delivery Fee</span>--}}
{{--                        <span>{{ $setting['currency']->value??"$"}} 0.50</span>--}}
{{--                    </div>--}}
                    <div class="summary-row total">
                        <span>Total</span>
                        <span id="total">{{ $setting['currency']->value??"$"}} {{ number_format($cart->total + 0.50, 2) }}</span>
                    </div>
                </div>

{{--                <div class="checkout-section">--}}
{{--                    <button class="checkout-btn" onclick="checkout()">Proceed to Checkout</button>--}}
{{--                </div>--}}
                <div class="checkout-section">
                    <button class="checkout-btn" onclick="shareCartOnWhatsApp()">Share Cart on WhatsApp</button>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="empty-cart" style="grid-column: 1 / -1;">
                <div class="empty-cart-icon">🛒</div>
                <h2 class="empty-cart-title">Your cart is empty</h2>
                <p class="empty-cart-text">Add some delicious items to get started!</p>
                <button class="browse-btn" onclick="window.location.href='{{ route('menu.index') }}'">
                    Browse Menu
                </button>
            </div>
        @endif
    </div>
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
                body: JSON.stringify({ quantity: newQty })
            });

            const data = await response.json();

            if (data.success) {
                qtyElement.textContent = newQty;
                document.getElementById(`price-${itemId}`).textContent = `{{ $setting['currency']->value??"$"}} ${data.subtotal}`;
                updateTotals(data.cart_total);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to update quantity');
        }
    }

    async function removeItem(itemId) {
        if (!confirm('Remove this item from cart?')) return;

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
                document.getElementById(`cart-item-${itemId}`).remove();

                if (data.cart_count === 0) {
                    location.reload();
                } else {
                    updateTotals(data.cart_total);
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to remove item');
        }
    }

    async function clearCart() {
        if (!confirm('Clear all items from cart?')) return;

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
            alert('Failed to clear cart');
        }
    }

    function updateTotals(subtotal) {
        const deliveryFee = 0.50;
        const total = parseFloat(subtotal) + deliveryFee;

        document.getElementById('subtotal').textContent = `{{ $setting['currency']->value??"$"}} ${subtotal}`;
        document.getElementById('total').textContent = `{{ $setting['currency']->value??"$"}} ${total.toFixed(2)}`;
    }

    function checkout() {
        alert('Checkout functionality would be implemented here!\n\nThis would typically:\n- Collect delivery info\n- Process payment\n- Create order');
    }

    function shareCartOnWhatsApp() {
        const items = document.querySelectorAll('.cart-item');
        if (!items.length) {
            alert('Your cart is empty.');
            return;
        }

        let message = '🛒 *New Cart Order*\n\n';

        items.forEach((item, index) => {
            const name = item.querySelector('.item-name')?.textContent.trim() || '';
            const customization = item.querySelector('.item-customization')?.textContent.trim() || '';
            const qty = item.querySelector('.quantity')?.textContent.trim() || '';
            const price = item.querySelector('.item-price')?.textContent.trim() || '';
            const imgEl = item.querySelector('.item-image');
            const imgSrc = imgEl ? imgEl.src : '';
            console.log(imgEl,imgSrc);
            message += `${index + 1}. *${name}*  x${qty}\n`;
            if (customization) {
                message += `   ${customization}\n`;
            }
            message += `   ${price}\n`;
            if (imgSrc) {
                message += `   Image: ${imgSrc}\n`;
            }
            message += `\n`;
        });

        const subtotal = document.getElementById('subtotal')?.textContent.trim() || '';
        const total    = document.getElementById('total')?.textContent.trim() || '';

        message += `Subtotal: ${subtotal}\n`;
        message += `Total: *${total}*\n`;
        message += `\nPlease confirm this order.`;

        const encoded = encodeURIComponent(message);

        // Your specific WhatsApp number in international format (no +, no spaces)
        const phone = "{{env('WHATSAPP_PHONE')}}";

        const whatsappURL = `https://wa.me/${phone}?text=${encoded}`;
        window.open(whatsappURL, '_blank');
    }

</script>
</body>
</html>
