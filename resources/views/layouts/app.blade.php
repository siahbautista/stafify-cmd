<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Primary Meta Tags -->
    <title>@yield('title', 'Dashboard') - Stafify HRIS</title>
    <meta name="title" content="@yield('title', 'Dashboard') - Stafify HRIS">
    <meta name="description" content="@yield('description', 'Stafify HRIS - Professional Customer Relationship Management System')">
    <meta name="keywords" content="@yield('keywords', 'HRIS, customer relationship management, sales, leads, deals, contacts, business management')">
    <meta name="author" content="Stafify">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Dashboard') - Stafify HRIS">
    <meta property="og:description" content="@yield('description', 'Stafify HRIS - Professional Customer Relationship Management System')">
    <meta property="og:site_name" content="Stafify HRIS">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Dashboard') - Stafify HRIS">
    <meta property="twitter:description" content="@yield('description', 'Stafify HRIS - Professional Customer Relationship Management System')">
    
    <!-- Favicon and other assets -->
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('assets/images/Stafify Favicon.png') }}"/>
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Scripts -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    
    <style>
        table th, td, p, span {
            font-family: 'Quicksand', sans-serif !important;
        }
    </style>
    
</head>
<body class="bg-light">
    @include('layouts.sidebar')
    
    <div class="main-content">
        @include('layouts.mobile-menu')
        @include('layouts.header')
        
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/mobile-menu.js') }}"></script>
    <script src="https://unpkg.com/@material-tailwind/html@latest/scripts/script-name.js"></script>
    
    @stack('scripts')
</body>
</html>
