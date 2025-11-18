<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - {{ $selectedCategory }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"/>
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

        .cart-icon-symbol i {
            color: #fff;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header Navigation */
        .header {
            background-color: #fff;
            border-bottom: 1px solid #e0e0e0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-wrapper {
            display: flex;
            align-items: center;
            padding: 0 15px;
        }

        .menu-icon {
            width: 40px;
            height: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            margin-right: 10px;
        }

        .menu-icon span {
            width: 25px;
            height: 2px;
            background-color: #333;
            margin: 3px 0;
            transition: 0.3s;
        }

        .nav-categories {
            display: flex;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            flex: 1;
        }

        .nav-categories::-webkit-scrollbar {
            display: none;
        }

        .nav-item {
            padding: 18px 20px;
            color: #999;
            text-decoration: none;
            white-space: nowrap;
            font-size: 18px;
            font-weight: 400;
            transition: color 0.3s;
            border-bottom: 3px solid transparent;
        }

        .nav-item.active {
            color: #333;
            font-weight: 500;
            border-bottom-color: #333;
        }

        .nav-item:hover {
            color: #555;
        }

        /* Cart icon in header (desktop/tablet) */
        .cart-icon {
            width: 40px;
            height: 40px;
            margin-left: 10px;
            border-radius: 50%;
            background-color: #d4a574;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            box-shadow: 0 2px 8px #d4a574;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .cart-icon:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Base: HIDE on non-mobile */
        .cart-bar-mobile {
            display: none; /* hidden by default */
            position: fixed;
            left: 50%;
            transform: translateX(-50%);
            bottom: 12px;
            width: calc(100% - 40px);
            max-width: 480px;
            background-color: #d4a574;
            color: #111;
            padding: 12px 18px;
            border-radius: 999px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
            align-items: center;
            justify-content: space-between;
            font-weight: 600;
            font-size: 15px;
            z-index: 250;
        }

        .cart-bar-text {
            flex: 1;
            text-align: center;
        }

        .cart-bar-pill {
            width: 30px;
            height: 30px;
            margin-left: 12px;
            border-radius: 50%;
            background-color: #fff;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
        }

        /* Make header layout nice on small screen */
        /*.nav-wrapper {*/
        /*    display: flex;*/
        /*    align-items: center;*/
        /*}*/


        /* Content */
        .content {
            padding: 30px 15px;
        }

        .page-title {
            font-size: 42px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #222;
        }

        /* Menu Items */
        .menu-items {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0;
        }

        .menu-item {
            background-color: #fff;
            padding: 25px 15px;
            border-bottom: 1px solid #e8e8e8;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.2s;
            cursor: pointer;
        }

        .menu-item:hover {
            background-color: #fafafa;
        }

        .item-info {
            flex: 1;
            padding-right: 20px;
        }

        .item-name {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #222;
        }

        .item-description {
            font-size: 15px;
            color: #666;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .item-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-price {
            font-size: 18px;
            font-weight: 600;
            color: #222;
        }

        .item-customizable {
            font-size: 14px;
            color: #999;
        }

        .item-image-wrapper {
            position: relative;
            flex-shrink: 0;
        }

        .item-image {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 8px;
            background-color: #e8e8e8;
        }

        .arrow-btn,
        .plus-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 45px;
            height: 45px;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: transform 0.2s;
        }

        .arrow-btn {
            right: 10px; /* arrow on the far right */
        }

        .plus-btn {
            right: 60px; /* plus button a bit to the left of the arrow */
        }

        .arrow-btn:hover,
        .plus-btn:hover {
            transform: scale(1.05);
        }

        .arrow-btn::after {
            content: '›';
            font-size: 28px;
            color: #d4a574;
            font-weight: 300;
        }

        .plus-btn::after {
            content: '+';
            font-size: 28px;
            color: #d4a574;
            font-weight: 400;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state-title {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .empty-state-text {
            font-size: 16px;
        }

        /* Desktop Styles */
        @media (min-width: 768px) {
            .nav-wrapper {
                padding: 0 40px;
            }

            .content {
                padding: 50px 40px;
            }

            .page-title {
                font-size: 52px;
                margin-bottom: 40px;
            }

            .menu-items {
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
            }

            .menu-item {
                border-radius: 12px;
                border: 1px solid #e8e8e8;
                padding: 30px;
            }

            .item-image {
                width: 200px;
                height: 200px;
            }

            .item-name {
                font-size: 26px;
            }

            .item-description {
                font-size: 16px;
            }
        }

        @media (min-width: 1200px) {
            .menu-items {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1600px) {
            .menu-items {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 767px) {
            .cart-bar-mobile {
                display: flex; /* show ONLY on mobile */
            }

            /* optional: hide header cart icon on mobile */
            .cart-icon {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="header">
    <div class="container">
        <div class="nav-wrapper">
            {{--            <div class="menu-icon">--}}
            {{--                <span></span>--}}
            {{--                <span></span>--}}
            {{--                <span></span>--}}
            {{--            </div>--}}
            <nav class="nav-categories">
                @foreach($categories as $category)
                    <a href="{{ route('menu.index', ['category' => $category->name]) }}"
                       class="nav-item {{ $category->name === $selectedCategory ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </nav>
            <div class="cart-icon"
                 onclick="window.location.href='{{ route('cart.index') }}'">
                <span class="cart-icon-symbol">
                    <i class="fa-solid fa-cart-shopping"></i>
                </span>
                @if(!empty($cartCount))
                    <span class="cart-count">{{ $cartCount }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="content">
        <h1 class="page-title">{{ $selectedCategory }}</h1>

        @if($products->count() > 0)
            <div class="menu-items">
                @foreach($products as $item)
                    <div class="menu-item" onclick="window.location.href='{{ route('menu.show', $item->id) }}'">
                        <div class="item-info">
                            <h2 class="item-name">{{ $item->name }}</h2>
                            <p class="item-description">{{ $item->description }}</p>
                            <div class="item-footer">
                                <div class="item-price">{{ $setting['currency']->value??"$"}}  {{ number_format($item->price, 2) }}</div>
                                @if($item->customizable)
                                    <div class="item-customizable">Customizable</div>
                                @endif
                            </div>
                        </div>
                        <div class="item-image-wrapper">
                            @if($item->image)
                                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="item-image">
                            @else
                                <div class="item-image"></div>
                            @endif
                            @if($item->type == 'simple')
                                <div class="plus-btn"
                                     onclick="event.stopPropagation(); window.location.href='{{ route('menu.show', $item->id) }}'"></div>
                            @else
                                <div class="arrow-btn"
                                     onclick="event.stopPropagation(); window.location.href='{{ route('menu.show', $item->id) }}'"></div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-title">No items available</div>
                <div class="empty-state-text">Check back soon for new menu items!</div>
            </div>
        @endif
    </div>
</div>


<div class="cart-bar-mobile"
     onclick="window.location.href='{{ route('cart.index') }}'">
    <span class="cart-bar-text">See Your Cart</span>
    <span class="cart-bar-pill">
        @if(!empty($cartCount))
            {{ $cartCount }}
        @else
            🛒
        @endif
    </span>
</div>

</body>
</html>
