@php
    $user = Auth::user();
    // ... (User variable setup as in HRIS header.blade.php)
@endphp

<div class="flex justify-between items-center gap-10 header mb-0">

    <div class="flex flex-col gap-5 greetings">
        <h1 class="page-title font-bold text-2xl text-gray-800">@yield('title', 'Dashboard')</h1>
    </div>
    
    <div class="flex gap-5 items-center">
        <div class="menu-container">
            <button onclick="toggleMenu(event)" class="bento-menu mt-[4px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-grid-dots">
                    </svg>
            </button>
            <div class="menu">
                <a href="{{ route('admin.profile') }}" class="!flex gap-2 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-user">
                        </svg>
                    Profile Settings
                </a>
                <a href="{{ route('logout') }}" class="!flex gap-2 items-center" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-logout">
                        </svg>
                    Logout
                </a>
            </div>
        </div>
    </div>
</div>

<form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>