@php
    $locale = app()->getLocale();
    $dir = $locale == 'ar' ? 'rtl' : 'ltr';
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', $restaurant['business_name']->value ?? 'Restaurant')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Header Styles --}}
    @include('partial.header-styles')

    {{-- Page-specific styles --}}
    @stack('styles')
</head>
<body>

    {{-- Header Navigation --}}
    @include('partial.header')

    {{-- Main Content --}}
    @yield('content')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Header Scripts --}}
    @include('partial.header-scripts')

    {{-- Page-specific scripts --}}
    @stack('scripts')
</body>
</html>
