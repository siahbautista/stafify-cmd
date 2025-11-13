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

<div class="sidebar-container">
    <div class="sidebar">
        <nav id="sidebarMenu">
            <div class="sidebarLogo">
                <img src="{{ asset('assets/images/Stafify-Logo.png') }}" class="menu-text site-logo" alt="Stafify Logo">
                <img src="{{ asset('assets/images/Archive-Favicon.png') }}" class="site-icon" alt="Stafify Icon">
            </div>
            
            <a href="{{ route('client.profile') }}" class="flex gap-3 items-center card-profile">
                <div class="flex-shrink-0">
                    <img src="{{ $user['profile_picture'] == 'default.png' ? asset('uploads/default.png') : Storage::url($user['profile_picture']) }}" 
                         alt="Profile Picture" 
                         class="w-9 h-9 rounded-full object-cover border border-gray-300 sidebar-profile-pic"
                         onerror="this.src='{{ asset('uploads/default.png') }}'">
                </div>
                <div class="details-profile menu-text">
                    <span class="profile-name">
                        <span id="username-display" class="profile-name">
                            {{ $user['full_name'] }}
                        </span>
                    </span>
                    <p class="profile-dept">
                        <span class="position-display">{{ $user['user_position'] }}</span>
                        @if($user['user_position'] && $user['user_dept'])
                            <span class="separator"> - </span>
                        @endif
                        <span class="department-display">{{ $user['user_dept'] }}</span>
                    </p>
                </div>
            </a>
            
            <ul class="flex flex-col gap-2 sidebarMenuItems">
                <li class="sidebarMenuItem">
                    <a href="{{ route('client.dashboard') }}" class="nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="25"  height="25"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1.1"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-layout-dashboard"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1" /><path d="M5 16h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1" /><path d="M15 12h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1" /><path d="M15 4h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1" /></svg>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebarMenuItem">
                    <a href="{{ route('client.profile') }}" class="nav-link {{ request()->routeIs('client.profile') ? 'active' : '' }}">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                        <span class="menu-text">Profile</span>
                    </a>
                </li>

                {{-- ***** NEW LINK ADDED HERE ***** --}}
                <li class="sidebarMenuItem">
                    <a href="{{ route('client.users.index') }}" class="nav-link {{ request()->routeIs('client.users.index') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                        <span class="menu-text">Users</span>
                    </a>
                </li>
                {{-- ***** END OF NEW LINK ***** --}}

                <li class="sidebarMenuItem">
                    <button onclick="toggleSidebarDropdown('desktop')" class="nav-link w-full flex justify-between items-center {{ request()->routeIs('client.company.*') ? 'active' : '' }}">
                        <span class="flex items-center gap-3"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M9 8l1 0" /><path d="M9 12l1 0" /><path d="M9 16l1 0" /><path d="M14 8l1 0" /><path d="M14 12l1 0" /><path d="M14 16l1 0" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /></svg>
                            <span class="menu-text">Company</span>
                        </span>
                        <svg id="dropdownIconDesktop" class="ml-auto transition-transform duration-300 transform" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9l6 6l6 -6" />
                        </svg>
                    </button>
                    <div id="dropdownMenuDesktop" class="overflow-hidden transition-all duration-300 max-h-0 opacity-0 scale-y-95 ml-5">
                        <ul class="flex flex-col gap-2 mt-2">
                            <li class="sidebarMenuItem">
                                <a href="{{ route('client.company.profile') }}" class="nav-link {{ request()->routeIs('client.company.profile') ? 'active' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-id"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M15 8l2 0" /><path d="M15 12l2 0" /><path d="M7 16l10 0" /></svg>
                                    <span class="menu-text">Main</span>
                                </a>
                            </li>
                            <li class="sidebarMenuItem">
                                <a href="{{ route('client.company.branches') }}" class="nav-link {{ request()->routeIs('client.company.branches') ? 'active' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-store"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" /><path d="M5 21l0 -10.15" /><path d="M19 21l0 -10.15" /><path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" /></svg>
                                    <span class="menu-text">Branches</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="sidebarMenuItem">
                     <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-logout-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" /><path d="M15 12h-12l3 -3" /><path d="M6 15l-3 -3" /></svg>
                        <span class="menu-text">Sign Out</span>
                    </a>
                </li>
                
                <li class="sidebarMenuItem">
                    <button class="toggle-btn" onclick="toggleSidebar()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout-sidebar-left-collapse">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                            <path d="M9 4v16" />
                            <path d="M15 10l-2 2l2 2" />
                        </svg>
                        <span class="menu-text">Collapse Sidebar</span>
                    </button>
                </li>
            </ul>
        </nav>
        
        <div class="flex max-[1024px]:hidden flex-col items-start gap-3 poweredBy">
            <p>Powered By:</p>
            <img src="{{ asset('assets/images/Stafify-Logo.png') }}" class="site-logo" alt="Stafify Logo">
        </div>
    </div>
</div>

{{-- This form is for the desktop sidebar link --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<div class="sidebar-overlay-mobile"></div>

<div class="sidebar-container-mobile">
    <div class="sidebar-mobile">
        <button class="sidebar-close-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
        </button>

        <nav id="sidebarMenu">
            <div class="sidebarLogo">
                <img src="{{ asset('assets/images/Stafify-Logo.png') }}" class="menu-text site-logo" alt="Stafify Logo">
                <img src="{{ asset('assets/images/Archive-Favicon.png') }}" class="site-icon" alt="Stafify Icon">
            </div>
            
            <a href="{{ route('client.profile') }}" class="flex gap-3 items-center card-profile">
                <div class="flex-shrink-0">
                    <img src="{{ $user['profile_picture'] == 'default.png' ? asset('uploads/default.png') : Storage::url($user['profile_picture']) }}" 
                         alt="Profile Picture" 
                         class="w-9 h-9 rounded-full object-cover border border-gray-300 sidebar-profile-pic"
                         onerror="this.src='{{ asset('uploads/default.png') }}'">
                </div>
                <div class="details-profile menu-text">
                    <span class="profile-name">
                        <span id="username-display" class="profile-name">
                            {{ $user['full_name'] }}
                        </span>
                    </span>
                    <p class="profile-dept">
                        <span class="position-display">{{ $user['user_position'] }}</span>
                        @if($user['user_position'] && $user['user_dept'])
                            <span class="separator"> - </span>
                        @endif
                        <span class="department-display">{{ $user['user_dept'] }}</span>
                    </p>
                </div>
            </a>
            
            <ul class="flex flex-col gap-2 sidebarMenuItems">
                <li class="sidebarMenuItem">
                    <a href="{{ route('client.dashboard') }}" class="nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout-dashboard"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1" /><path d="M5 16h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1" /><path d="M15 12h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1" /><path d="M15 4h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1" /></svg>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebarMenuItem">
                    <a href="{{ route('client.profile') }}" class="nav-link {{ request()->routeIs('client.profile') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                        <span class="menu-text">Profile</span>
                    </a>
                </li>

                {{-- ***** NEW LINK ADDED HERE ***** --}}
                <li class="sidebarMenuItem">
                    <a href="{{ route('client.users.index') }}" class="nav-link {{ request()->routeIs('client.users.index') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                        <span class="menu-text">Manage Employees</span>
                    </a>
                </li>
                {{-- ***** END OF NEW LINK ***** --}}

                <li class="sidebarMenuItem">
                    <button onclick="toggleSidebarDropdown('mobile')" class="nav-link w-full flex justify-between items-center {{ request()->routeIs('client.company.*') ? 'active' : '' }}">
                        <span class="flex items-center gap-3"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M9 8l1 0" /><path d="M9 12l1 0" /><path d="M9 16l1 0" /><path d="M14 8l1 0" /><path d="M14 12l1 0" /><path d="M14 16l1 0" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /></svg>
                            <span class="menu-text">Company</span>
                        </span>
                        <svg id="dropdownIconMobile" class="ml-auto transition-transform duration-300 transform" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9l6 6l6 -6" />
                        </svg>
                    </button>
                    <div id="dropdownMenuMobile" class="overflow-hidden transition-all duration-300 max-h-0 opacity-0 scale-y-95 ml-5">
                        <ul class="flex flex-col gap-2 mt-2">
                            <li class="sidebarMenuItem">
                                <a href="{{ route('client.company.profile') }}" class="nav-link {{ request()->routeIs('client.company.profile') ? 'active' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-id"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M15 8l2 0" /><path d="M15 12l2 0" /><path d="M7 16l10 0" /></svg>
                                    <span class="menu-text">Main Profile</span>
                                </a>
                            </li>
                            <li class="sidebarMenuItem">
                                <a href="{{ route('client.company.branches') }}" class="nav-link {{ request()->routeIs('client.company.branches') ? 'active' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-store"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" /><path d="M5 21l0 -10.15" /><path d="M19 21l0 -10.15" /><path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" /></svg>
                                    <span class="menu-text">Branches</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="sidebarMenuItem">
                     {{-- UPDATED: Points to the mobile-specific form ID --}}
                     <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-logout-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" /><path d="M15 12h-12l3 -3" /><path d="M6 15l-3 -3" /></svg>
                        <span class="menu-text">Sign Out</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="flex flex-col items-center gap-10 poweredBy">
            <p>Powered By:</p>
            <img src="{{ asset('assets/images/Stafify-Logo.png') }}" class="site-logo" alt="Stafify Logo">
        </div>
    </div>
</div>

{{-- UPDATED: This form is for the mobile sidebar link --}}
<form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

{{-- This script is now wrapped in @push('scripts') and the duplicate Tailwind script is removed --}}
@push('scripts')
<script>
 // Sidebar Collapse
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        // UPDATED: Check if mainContent exists before trying to use it
        const mainContent = document.querySelector('.main-content');
        
        if (sidebar) {
            sidebar.classList.toggle('collapsed');
        }

        if (mainContent) { // Only change style if the element exists
            if (sidebar && sidebar.classList.contains('collapsed')) {
                mainContent.style.marginLeft = "70px";
            } else {
                mainContent.style.marginLeft = "250px";
            }
        }
    }

    // Load icons
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        // Auto-open active dropdown
        checkActiveDropdown('desktop');
        checkActiveDropdown('mobile');
    });

    // Sidebar Mobile
    document.addEventListener("DOMContentLoaded", function () {
        const menuButton = document.querySelector(".hamburger-menu");
        const closeButton = document.querySelector(".sidebar-close-btn");
        const sidebar = document.querySelector(".sidebar-container-mobile");
        const overlay = document.querySelector(".sidebar-overlay-mobile");

        function openSidebar() {
            if (sidebar) sidebar.classList.add("active");
            if (overlay) overlay.classList.add("active");
        }
        function closeSidebar() {
            if (sidebar) sidebar.classList.remove("active");
            if (overlay) overlay.classList.remove("active");
        }

        if (menuButton) menuButton.addEventListener("click", openSidebar);
        if (closeButton) closeButton.addEventListener("click", closeSidebar);
        if (overlay) overlay.addEventListener("click", closeSidebar);
    });

    // --- COMPANY DROPDOWN SCRIPT (Renamed to avoid conflicts) ---
    function toggleSidebarDropdown(type) {
        const menuId = `dropdownMenu${type === 'desktop' ? 'Desktop' : 'Mobile'}`;
        const iconId = `dropdownIcon${type === 'desktop' ? 'Desktop' : 'Mobile'}`;
        
        const menu = document.getElementById(menuId);
        const icon = document.getElementById(iconId);

        if (!menu || !icon) {
            console.error('Sidebar dropdown elements not found for:', type);
            return;
        }
    
        if (menu.classList.contains('max-h-0')) {
            // Open
            menu.classList.remove('max-h-0', 'opacity-0', 'scale-y-95');
            menu.classList.add('max-h-[500px]', 'opacity-100', 'scale-y-100', 'mt-2');
            icon.classList.add('rotate-180');
        } else {
            // Close
            menu.classList.remove('max-h-[500px]', 'opacity-100', 'scale-y-100', 'mt-2');
            menu.classList.add('max-h-0', 'opacity-0', 'scale-y-95');
            icon.classList.remove('rotate-180');
        }
    }

    // Auto-open dropdown if a child link is active
    function checkActiveDropdown(type) {
        const menuId = `dropdownMenu${type === 'desktop' ? 'Desktop' : 'Mobile'}`;
        const iconId = `dropdownIcon${type === 'desktop' ? 'Desktop' : 'Mobile'}`;
        
        const menu = document.getElementById(menuId);
        const icon = document.getElementById(iconId);

        // Check if menu, icon, and an active child link exist
        if (menu && icon && menu.querySelector('.nav-link.active')) {
            // Also set the parent button to active
            const parentButton = menu.previousElementSibling;
            if (parentButton && parentButton.classList.contains('nav-link')) {
                parentButton.classList.add('active');
            }
            
            menu.classList.remove('max-h-0', 'opacity-0', 'scale-y-95');
            menu.classList.add('max-h-[500px]', 'opacity-100', 'scale-y-100', 'mt-2');
            icon.classList.add('rotate-180');
        }
    }

    // Update active state for parent dropdown - Run after DOM is ready
    document.addEventListener("DOMContentLoaded", function() {
        // Check both desktop and mobile dropdowns
        ['Desktop', 'Mobile'].forEach(function(type) {
            const dropdown = document.getElementById(`dropdownMenu${type}`);
            if (!dropdown) return;
            
            const companyLinks = dropdown.querySelectorAll('a.nav-link');
            let companyLinkActive = false;
            
            companyLinks.forEach(function(link) {
                if (link.classList.contains('active')) {
                    companyLinkActive = true;
                }
            });

            if (companyLinkActive) {
                const parentButton = dropdown.previousElementSibling;
                if (parentButton && parentButton.classList.contains('nav-link')) {
                    parentButton.classList.add('active');
                }
            }
        });
    });
</script>
@endpush