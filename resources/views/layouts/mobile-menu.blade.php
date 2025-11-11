@php
    $user = Auth::user();
    $user = $user ? [
        'profile_picture' => $user->profile_picture ?? 'default.png',
        'full_name' => $user->full_name ?? 'Unknown User',
        'user_position' => $user->user_position ?? '',
        'user_dept' => $user->user_dept ?? ''
    ] : [
        'profile_picture' => 'default.png',
        'full_name' => 'Unknown User',
        'user_position' => '',
        'user_dept' => ''
    ];
@endphp

<div class="min-[531px]:hidden justify-between items-center gap-20 w-full mobile-nav flex">
    <!-- Mobile Menu -->
    <button class="hamburger-menu">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-menu">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M4 8l16 0" />
            <path d="M4 16l16 0" />
        </svg>
    </button>
    
    <div class="menu-container">
        <button onclick="toggleMenu(event)" class="bento-menu mt-[4px]">
            <div class="min-[531px]:hidden flex-shrink-0">
                <img src="{{ asset('uploads/' . $user['profile_picture']) }}" alt="Profile Picture" class="w-11 h-11 rounded-full object-cover border border-gray-300">
            </div>
        </button>
        <div class="dropdownMenu menu">
            <a href="#" class="!flex gap-2 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-buildings">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M4 21v-15c0 -1 1 -2 2 -2h5c1 0 2 1 2 2v15" />
                    <path d="M16 8h2c1 0 2 1 2 2v11" />
                    <path d="M3 21h18" />
                    <path d="M10 12v0" />
                    <path d="M10 16v0" />
                    <path d="M10 8v0" />
                    <path d="M7 12v0" />
                    <path d="M7 16v0" />
                    <path d="M7 8v0" />
                    <path d="M17 12v0" />
                    <path d="M17 16v0" />
                    <path d="M17 8v0" />
                </svg>
                Company Settings
            </a>
            <a href="#" class="!flex gap-2 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-user">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                </svg>
                Profile Settings
            </a>
            <a href="{{ route('logout') }}" class="!flex gap-2 items-center" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-logout">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                    <path d="M9 12h12l-3 -3" />
                    <path d="M18 15l3 -3" />
                </svg>
                Logout
            </a>
        </div>
    </div>
</div>

<!-- Hidden logout form for mobile -->
<form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

