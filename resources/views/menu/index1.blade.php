<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $restaurant['business_name']->value??'restaurant' }} - Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .cart-slide { transition: transform 0.3s ease-in-out; }
        .cart-open { transform: translateX(0); }
        .cart-closed { transform: translateX(100%); }
        .modal { transition: opacity 0.3s ease; }
        .variant-option { transition: all 0.2s ease; }
        .variant-option:hover { transform: scale(1.02); }
        .badge-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
    </style>
</head>
<body class="bg-gray-50">
<!-- Header -->
<header class="gradient-bg text-white shadow-lg sticky top-0 z-40">
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">{{ $restaurant['business_name']->value??'restaurant' }}</h1>
                <p class="text-gray-200 text-sm mt-1">{{ $restaurant['description']->value??'description' }}</p>
            </div>
            <button onclick="toggleCart()" class="relative bg-white text-purple-600 px-6 py-3 rounded-full font-semibold hover:bg-gray-100 transition-all shadow-lg">
                <i class="fas fa-shopping-cart mr-2"></i>
                Cart
                <span id="cartCount" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold badge-pulse">0</span>
            </button>
        </div>
    </div>
</header>

<div class="container mx-auto px-4 py-8">
    <!-- Categories Navigation -->
    <div class="mb-8 bg-white rounded-2xl shadow-md p-4 sticky top-24 z-30">
        <div class="flex gap-3 overflow-x-auto pb-2">
            <button onclick="filterCategory('all')" class="category-btn px-6 py-3 rounded-full font-semibold whitespace-nowrap transition-all bg-purple-600 text-white">
                All Items
            </button>
            @foreach($categories as $category)
                <button onclick="filterCategory('{{ $category->id }}')" class="category-btn px-6 py-3 rounded-full font-semibold whitespace-nowrap transition-all bg-gray-200 text-gray-700 hover:bg-purple-100">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Products Grid -->
    @foreach($categories as $category)
        <div class="category-section mb-12" data-category="{{ $category->id }}">
            <h2 class="text-3xl font-bold mb-6 text-gray-800 flex items-center">
                <span class="bg-purple-600 w-2 h-8 rounded-full mr-3"></span>
                {{ $category->name }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($category->products as $product)
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden card-hover">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center">
                                <i class="fas fa-utensils text-white text-5xl"></i>
                            </div>
                        @endif

                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ $product->description }}</p>

                            @if($product->type === 'simple')
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-purple-600">${{ number_format($product->price, 2) }}</span>
                                    <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, 'simple')"
                                            class="bg-purple-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-purple-700 transition-all shadow-md hover:shadow-lg">
                                        <i class="fas fa-plus mr-2"></i>Add to Cart
                                    </button>
                                </div>
                            @else
                                <button onclick="showVariantModal({{ json_encode($product) }})"
                                        class="w-full bg-purple-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-purple-700 transition-all shadow-md hover:shadow-lg">
                                    <i class="fas fa-list mr-2"></i>Choose Options
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<!-- Variant Modal -->
<div id="variantModal" class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl transform transition-all">
        <div class="gradient-bg text-white p-6 rounded-t-2xl">
            <h3 class="text-2xl font-bold" id="modalProductName"></h3>
            <p class="text-gray-200 text-sm mt-1">Choose your preferred size</p>
        </div>
        <div class="p-6">
            <div id="variantOptions" class="space-y-3 mb-6"></div>
            <div class="flex gap-3">
                <button onclick="closeVariantModal()" class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-300 transition-all">
                    Cancel
                </button>
                <button onclick="addVariantToCart()" class="flex-1 bg-purple-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-purple-700 transition-all shadow-md">
                    Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cart Sidebar -->
<div id="cartSidebar" class="cart-slide cart-closed fixed right-0 top-0 h-full w-full md:w-96 bg-white shadow-2xl z-50 flex flex-col">
    <div class="gradient-bg text-white p-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold">Your Cart</h2>
        <button onclick="toggleCart()" class="text-white hover:text-gray-200 transition-colors">
            <i class="fas fa-times text-2xl"></i>
        </button>
    </div>

    <div id="cartItems" class="flex-1 overflow-y-auto p-6">
        <div class="text-center text-gray-400 mt-20">
            <i class="fas fa-shopping-cart text-6xl mb-4"></i>
            <p class="text-lg">Your cart is empty</p>
        </div>
    </div>

    <div class="border-t p-6 bg-gray-50">
        <div class="flex justify-between items-center mb-4">
            <span class="text-xl font-semibold text-gray-700">Total:</span>
            <span id="cartTotal" class="text-3xl font-bold text-purple-600">$0.00</span>
        </div>
        <button onclick="checkout()" class="w-full bg-purple-600 text-white py-4 rounded-full font-bold text-lg hover:bg-purple-700 transition-all shadow-lg hover:shadow-xl">
            Checkout <i class="fas fa-arrow-right ml-2"></i>
        </button>
    </div>
</div>

<!-- Cart Overlay -->
<div id="cartOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40" onclick="toggleCart()"></div>

<script>
    let cart = [];
    let selectedProduct = null;
    let selectedVariant = null;

    // Filter categories
    function filterCategory(categoryId) {
        const sections = document.querySelectorAll('.category-section');
        const buttons = document.querySelectorAll('.category-btn');

        buttons.forEach(btn => {
            btn.classList.remove('bg-purple-600', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-700');
        });
        event.target.classList.add('bg-purple-600', 'text-white');
        event.target.classList.remove('bg-gray-200', 'text-gray-700');

        if (categoryId === 'all') {
            sections.forEach(section => section.style.display = 'block');
        } else {
            sections.forEach(section => {
                section.style.display = section.dataset.category === categoryId ? 'block' : 'none';
            });
        }
    }

    // Add simple product to cart
    function addToCart(productId, productName, price, type) {
        const item = {
            id: productId,
            name: productName,
            price: parseFloat(price),
            type: type,
            variant: null,
            quantity: 1
        };

        const existingIndex = cart.findIndex(i => i.id === productId && i.type === 'simple');
        if (existingIndex > -1) {
            cart[existingIndex].quantity++;
        } else {
            cart.push(item);
        }

        updateCart();
        showNotification('Added to cart!');
    }

    // Show variant modal
    function showVariantModal(product) {
        selectedProduct = product;
        document.getElementById('modalProductName').textContent = product.name;

        const optionsHtml = product.variants.map(variant => `
                <div class="variant-option border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-purple-600"
                     onclick="selectVariant(${variant.id}, '${variant.name}', ${variant.price})">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-semibold text-lg text-gray-800">${variant.name}</span>
                        </div>
                        <span class="text-xl font-bold text-purple-600">$${parseFloat(variant.price).toFixed(2)}</span>
                    </div>
                </div>
            `).join('');

        document.getElementById('variantOptions').innerHTML = optionsHtml;
        document.getElementById('variantModal').classList.remove('hidden');
        document.getElementById('variantModal').classList.add('flex');
    }

    // Select variant
    function selectVariant(variantId, size, price) {
        document.querySelectorAll('.variant-option').forEach(opt => {
            opt.classList.remove('border-purple-600', 'bg-purple-50');
            opt.classList.add('border-gray-200');
        });
        event.currentTarget.classList.add('border-purple-600', 'bg-purple-50');

        selectedVariant = { id: variantId, size: size, price: parseFloat(price) };
    }

    // Add variant to cart
    function addVariantToCart() {
        if (!selectedVariant) {
            alert('Please select a size');
            return;
        }

        const item = {
            id: selectedProduct.id,
            name: selectedProduct.name,
            price: selectedVariant.price,
            type: 'variant',
            variant: selectedVariant,
            quantity: 1
        };

        const existingIndex = cart.findIndex(i =>
            i.id === selectedProduct.id &&
            i.type === 'variant' &&
            i.variant.id === selectedVariant.id
        );

        if (existingIndex > -1) {
            cart[existingIndex].quantity++;
        } else {
            cart.push(item);
        }

        closeVariantModal();
        updateCart();
        showNotification('Added to cart!');
    }

    // Close variant modal
    function closeVariantModal() {
        document.getElementById('variantModal').classList.add('hidden');
        document.getElementById('variantModal').classList.remove('flex');
        selectedProduct = null;
        selectedVariant = null;
    }

    // Update cart display
    function updateCart() {
        const cartItemsContainer = document.getElementById('cartItems');
        const cartCount = document.getElementById('cartCount');
        const cartTotal = document.getElementById('cartTotal');

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = `
                    <div class="text-center text-gray-400 mt-20">
                        <i class="fas fa-shopping-cart text-6xl mb-4"></i>
                        <p class="text-lg">Your cart is empty</p>
                    </div>
                `;
            cartCount.textContent = '0';
            cartTotal.textContent = '$0.00';
            return;
        }

        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        cartCount.textContent = totalItems;
        cartTotal.textContent = `$${total.toFixed(2)}`;

        cartItemsContainer.innerHTML = cart.map((item, index) => `
                <div class="bg-gray-50 rounded-xl p-4 mb-3 shadow-sm">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">${item.name}</h4>
                            ${item.variant ? `<p class="text-sm text-gray-600">${item.variant.size}</p>` : ''}
                            <p class="text-lg font-bold text-purple-600 mt-1">$${item.price.toFixed(2)}</p>
                        </div>
                        <button onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="updateQuantity(${index}, -1)" class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full transition-colors">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="font-semibold text-lg w-8 text-center">${item.quantity}</span>
                        <button onclick="updateQuantity(${index}, 1)" class="bg-purple-600 hover:bg-purple-700 text-white w-8 h-8 rounded-full transition-colors">
                            <i class="fas fa-plus"></i>
                        </button>
                        <span class="ml-auto font-semibold text-gray-700">$${(item.price * item.quantity).toFixed(2)}</span>
                    </div>
                </div>
            `).join('');
    }

    // Update quantity
    function updateQuantity(index, change) {
        cart[index].quantity += change;
        if (cart[index].quantity <= 0) {
            cart.splice(index, 1);
        }
        updateCart();
    }

    // Remove from cart
    function removeFromCart(index) {
        cart.splice(index, 1);
        updateCart();
        showNotification('Removed from cart');
    }

    // Toggle cart
    function toggleCart() {
        const sidebar = document.getElementById('cartSidebar');
        const overlay = document.getElementById('cartOverlay');

        if (sidebar.classList.contains('cart-closed')) {
            sidebar.classList.remove('cart-closed');
            sidebar.classList.add('cart-open');
            overlay.classList.remove('hidden');
        } else {
            sidebar.classList.add('cart-closed');
            sidebar.classList.remove('cart-open');
            overlay.classList.add('hidden');
        }
    }

    // Checkout
    function checkout() {
        if (cart.length === 0) {
            alert('Your cart is empty');
            return;
        }

        // Here you would send cart data to server
        console.log('Checkout with:', cart);
        alert('Proceeding to checkout...');

        // Example: Send to Laravel backend
        // fetch('/checkout', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        //     },
        //     body: JSON.stringify({ cart: cart })
        // });
    }

    // Show notification
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-24 right-4 bg-green-500 text-white px-6 py-3 rounded-full shadow-lg z-50 animate-bounce';
        notification.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${message}`;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 2000);
    }
</script>
</body>
</html>
