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

<!-- Desktop Sidebar -->
<div class="sidebar-container">
    <div class="sidebar">
        <nav id="sidebarMenu">
            <div class="sidebarLogo">
                <img src="{{ asset('assets/images/Stafify-Logo.png') }}" class="menu-text site-logo" alt="Stafify Logo">
                <img src="{{ asset('assets/images/Archive-Favicon.png') }}" class="site-icon" alt="Stafify Icon">
            </div>
            
            <!-- Profile Navigation Section -->
            <a href="{{ route('admin.profile') }}" class="flex gap-3 items-center card-profile">
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
                <!-- UMS Admin Links -->
                <li class="sidebarMenuItem">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="25"  height="25"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1.1"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-layout-dashboard"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1" /><path d="M5 16h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1" /><path d="M15 12h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1" /><path d="M15 4h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1" /></svg>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebarMenuItem">
                    <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                        <span class="menu-text">Profile</span>
                    </a>
                </li>
                
                {{-- THIS IS THE DESKTOP DROPDOWN --}}
                <li class="sidebarMenuItem has-dropdown">
                    <a href="#" class="justify-between nav-link" onclick="toggleDropdown(event, 'usersDropdown')">
                        <span class="flex items-center">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                            <span class="menu-text">Users</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevron-down">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9l6 6l6 -6" />
                        </svg>
                    </a>
                    <ul class="dropdown usersDropdown desktop">
                        <li class="sidebarMenuItem">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /><path d="M15 19l2 2l4 -4" /></svg>
                                <span class="menu-text">Active</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('admin.users.pending') }}" class="nav-link {{ request()->routeIs('admin.users.pending') ? 'active' : '' }}">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-question"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" /><path d="M19 22v.01" /><path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" /></svg>
                                <span class="menu-text">Pending</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Logout Form -->
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

<!-- Hidden Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Sidebar Mobile Overlay -->
<div class="sidebar-overlay-mobile"></div>

<!-- Mobile Sidebar -->
<div class="sidebar-container-mobile">
    <div class="sidebar-mobile">
        <!-- Close Button -->
        <button class="sidebar-close-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" />
            </svg>
        </button>

        <nav id="sidebarMenu">
            <div class="sidebarLogo">
                <img src="{{ asset('assets/images/Stafify-Logo.png') }}" class="menu-text site-logo" alt="Stafify Logo">
                <img src="{{ asset('assets/images/Archive-Favicon.png') }}" class="site-icon" alt="Stafify Icon">
            </div>
            
            <!-- Profile Navigation Section -->
            <a href="{{ route('admin.profile') }}" class="flex gap-3 items-center card-profile">
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
            
            <!-- Mobile navigation items -->
            <ul class="flex flex-col gap-2 sidebarMenuItems">
                <li class="sidebarMenuItem">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout-dashboard"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1" /><path d="M5 16h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1" /><path d="M15 12h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1" /><path d="M15 4h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1" /></svg>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebarMenuItem">
                    <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                        <span class="menu-text">Profile</span>
                    </a>
                </li>

                {{-- THIS IS THE MOBILE DROPDOWN --}}
                <li class="sidebarMenuItem has-dropdown">
                    <a href="#" class="justify-between nav-link" onclick="toggleDropdown(event, 'usersMobileDropdown')">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                            <span class="menu-text">Users</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevron-down">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9l6 6l6 -6" />
                        </svg>
                    </a>
                    <ul class="dropdown usersMobileDropdown mobile">
                        <li class="sidebarMenuItem">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /><path d="M15 19l2 2l4 -4" /></svg>
                                <span class="menu-text">Active</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('admin.users.pending') }}" class="nav-link {{ request()->routeIs('admin.users.pending') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-question"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" /><path d="M19 22v.01" /><path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" /></svg>
                                <span class="menu-text">Pending</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebarMenuItem">
                     <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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

{{-- THIS SCRIPT SECTION IS THE FIX --}}
<script src="https://cdn.tailwindcss.com"></script>
<script>
    // Sidebar Collapse
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        sidebar.classList.toggle('collapsed');

        if (sidebar.classList.contains('collapsed')) {
            mainContent.style.marginLeft = "70px";
        } else {
            mainContent.style.marginLeft = "250px";
        }
    }

    // Load icons
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });

    // Sidebar Mobile
    document.addEventListener("DOMContentLoaded", function () {
        const menuButton = document.querySelector(".hamburger-menu");
        const closeButton = document.querySelector(".sidebar-close-btn");
        const sidebar = document.querySelector(".sidebar-container-mobile");
        const overlay = document.querySelector(".sidebar-overlay-mobile");

        // Function to open sidebar
        function openSidebar() {
            sidebar.classList.add("active");
            overlay.classList.add("active");
        }

        // Function to close sidebar
        function closeSidebar() {
            sidebar.classList.remove("active");
            overlay.classList.remove("active");
        }

        if (menuButton) {
            menuButton.addEventListener("click", openSidebar);
        }
        if (closeButton) {
            closeButton.addEventListener("click", closeSidebar);
        }
        if (overlay) {
            overlay.addEventListener("click", closeSidebar);
        }
    });

    // Dropdown Toggle
    function toggleDropdown(event, dropdownClass) {
        event.preventDefault();
        event.stopPropagation();
        
        const desktopDropdown = document.querySelector(`.${dropdownClass}.desktop`);
        const mobileDropdown = document.querySelector(`.${dropdownClass}.mobile`);
        const dropdown = desktopDropdown || mobileDropdown;
        
        if (!dropdown) return;

        const isOpen = dropdown.classList.contains("open");
        
        // Close all other dropdowns
        const allDropdowns = document.querySelectorAll('.dropdown');
        allDropdowns.forEach(item => {
            if (item !== dropdown) {
                item.style.maxHeight = null;
                item.style.opacity = "0";
                item.style.marginBottom = "-5px";
                item.classList.remove("open");
            }
        });
        
        // Toggle the clicked dropdown
        if (isOpen) {
            dropdown.style.maxHeight = null;
            dropdown.style.opacity = "0";
            dropdown.style.marginBottom = "-5px";
            dropdown.classList.remove("open");
        } else {
            dropdown.style.maxHeight = dropdown.scrollHeight + "px";
            dropdown.style.opacity = "1";
            dropdown.style.marginBottom = "0px";
            dropdown.classList.add("open");
        }
    }

    // Prevent dropdown items from closing dropdown
    document.addEventListener("DOMContentLoaded", function() {
        const dropdownItems = document.querySelectorAll('.dropdown .nav-link');
        dropdownItems.forEach(item => {
            item.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });
    });

    // Auto-expand parent dropdown when a page inside it is active
    document.addEventListener("DOMContentLoaded", function() {
        const activeMenuItemInDropdown = document.querySelector('.dropdown .nav-link.active');
        
        if (activeMenuItemInDropdown) {
            const parentDropdown = activeMenuItemInDropdown.closest('.dropdown');
            if (parentDropdown) {
                parentDropdown.style.maxHeight = parentDropdown.scrollHeight + "px";
                parentDropdown.style.opacity = "1";
                parentDropdown.style.marginBottom = "0px";
                parentDropdown.classList.add("open");
            }
        }
    });
</script>