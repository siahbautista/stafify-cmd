<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Creator Dashboard') - UMS</title>
    
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('assets/images/Archive-Favicon.png') }}"/>
    
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css">
    
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    
    <style>
		/* Copied styles from admin-layout-header.php */
        table th, td, p, span {
            font-family: 'Quicksand', sans-serif !important;
        }
        .card-profile {
            background: var(--alt-white);
            border-radius: 10px;
            padding: 10px 10px 10px 10px;
            border: 1px solid #e9e9e9 !important;
        }
        .details-profile.menu-text {
            margin-top: -5px;
        }
        .image-profile {
            margin-bottom: -8px;
        }
        
        .card-profile .image-profile img {
            width: 30px;
            height: 30px;
            border-radius: 100px;
        }
        
        .profile-name {
            font-size: 12px;
            font-weight: 600;
            color: #393939;
        }
        
        .profile-dept {
            font-size: 10px;
            font-weight: 400;
            color: #575757;
            margin-top: 2px;
        }
        .edit-profile .field-group label {
            color: #575757;
        }
    </style>
    
</head>
<body class="bg-light">
    @include('layouts.admin.sidebar')
    
    <div class="main-content">
        @include('layouts.admin.mobile-menu')
        @include('layouts.admin.header')
        
        @yield('content')
    </div>

    <script src="{{ asset('js/mobile-menu.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
    
    @stack('scripts')
</body>
</html>