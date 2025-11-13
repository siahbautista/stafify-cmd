@php
    $user = Auth::user();
    // ... (User variable setup as in HRIS mobile-menu.blade.php)
@endphp

<div class="min-[531px]:hidden justify-between items-center gap-20 w-full mobile-nav flex">
    <button class="hamburger-menu">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-menu">
            </svg>
    </button>
    
    <div class="menu-container">
        <button onclick="toggleMenu(event)" class="bento-menu mt-[4px]">
            <div class="min-[531px]:hidden flex-shrink-0">
                <img src="{{ asset('uploads/' . $user['profile_picture']) }}" alt="Profile Picture" class="w-11 h-11 rounded-full object-cover border border-gray-300">
            </div>
        </button>
        <div class="dropdownMenu menu">
            <a href="{{ route('admin.profile') }}" class="!flex gap-2 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-user">
                    </svg>
                Profile Settings
            </a>
            
            {{-- UPDATED: The onclick handler now points to 'logout-form-top-bar' --}}
            <a href="{{ route('logout') }}" class="!flex gap-2 items-center" onclick="event.preventDefault(); document.getElementById('logout-form-top-bar').submit();">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-logout">
                    </svg>
                Logout
            </a>
        </div>
    </div>
</div>

<form id="logout-form-top-bar" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>