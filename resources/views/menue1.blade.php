<!doctype html>
<head>
    <title>Menu</title>

    <style>
        :root {
            --bg: #ffffff;
            --text: #111827;
            --muted: #6b7280;
            --line: #e5e7eb;
            --accent: #111827;
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font: 16px/1.45 ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji"
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 12px 16px
        }

        header {
            position: sticky;
            top: 0;
            background: linear-gradient(#fff, rgba(255, 255, 255, .92));
            backdrop-filter: saturate(1.2) blur(6px);
            z-index: 10;
            border-bottom: 1px solid var(--line)
        }

        .tabs {
            display: flex;
            gap: 24px;
            overflow: auto;
            padding: 10px 4px
        }

        .tab {
            padding: 10px 4px;
            white-space: nowrap;
            color: #6b7280;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            font-weight: 600
        }

        .tab:hover {
            color: #111827
        }

        .tab.active {
            color: var(--accent);
            border-bottom-color: var(--accent)
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            margin: 8px 0 12px
        }


        /* Item list */
        .list {
            display: grid;
            gap: 22px
        }

        .item {
            display: flex;
            gap: 16px;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid var(--line)
        }

        .item:last-child {
            border-bottom: none
        }

        .item-info {
            flex: 1 1 auto;
            min-width: 0
        }

        .item-title {
            font-size: 18px;
            font-weight: 800;
            margin: 2px 0 6px
        }

        .item-desc {
            color: var(--muted)
        }

        .item-price {
            margin-top: 14px;
            font-weight: 700
        }


        .thumb {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 18px;
            overflow: hidden;
            flex: 0 0 auto
        }

        .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block
        }

        .chevron {
            position: absolute;
            right: 10px;
            bottom: 10px;
            width: 44px;
            height: 44px;
            border-radius: 9999px;
            background: #fff;
            box-shadow: 0 10px 18px rgba(0, 0, 0, .12);
            display: grid;
            place-items: center
        }

        .chevron svg {
            width: 22px;
            height: 22px
        }

        .badge {
            font-size: 12px;
            color: var(--muted);
            text-align: center;
            margin-top: 6px
        }


        /* Desktop enhancements */
        @media (min-width: 1024px) {
            .page-title {
                font-size: 36px
            }

            .tabs {
                gap: 32px
            }

            .list {
                grid-template-columns:1fr 1fr;
                gap: 28px
            }

            .item {
                border: 1px solid var(--line);
                padding: 18px;
                border-radius: 16px
            }

            .thumb {
                width: 160px;
                height: 140px
            }

            .badge {
                text-align: right
            }
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <nav class="tabs" aria-label="Categories">
            @foreach($categories as $c)
                <a class="tab {{ $category && $category->id === $c->id ? 'active' : '' }}"
                   href="{{ route('menu1.category', $c->slug) }}">{{ $c->name }}</a>
            @endforeach
        </nav>
    </div>
</header>


<main class="container">
    <h1 class="page-title">{{ $category?->name ?? 'Menu' }}</h1>
    @yield('content')
</main>
</body>
</html>
