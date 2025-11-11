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
                <img src="https://www.stafify.com/cdn/shop/files/e50lj9u5c9xat9j7z3ne_752x.png?v=1613708232" class="menu-text site-logo" alt="Stafify Logo">
                <img src="https://res.cloudinary.com/dt1vbprub/image/upload/v1741661073/Stafify_Icon_onet8q.jpg" class="site-icon" alt="Stafify Icon">
            </div>
            
            <!-- Profile Navigation Section -->
            <a href="#" class="flex gap-3 items-center card-profile">
                <div class="flex-shrink-0">
                    <img src="{{ asset('uploads/' . $user['profile_picture']) }}" alt="Profile Picture" class="w-9 h-9 rounded-full object-cover border border-gray-300">
                </div>
                <div class="details-profile menu-text">
                    <span class="profile-name">
                        <span id="username-display" class="profile-name">
                            {{ $user['full_name'] }}
                        </span>
                    </span>
                    <p class="profile-dept">
                        <span class="position-display">{{ $user['user_position'] }}</span>
                        <span class="separator"> - </span>
                        <span class="department-display">{{ $user['user_dept'] }}</span>
                    </p>
                </div>
            </a>
            
            <ul class="flex flex-col gap-2 sidebarMenuItems">
                <li class="sidebarMenuItem">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout-grid">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                        </svg>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                
                <li class="sidebarMenuItem has-dropdown">
                    <a href="#" class="justify-between nav-link" onclick="toggleDropdown(event, 'attendanceDropdown')">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 7v5l3 3"/>
                                <path d="M12 3a9 9 0 1 0 9 9"/>
                            </svg>
                            <span class="menu-text">Attendance</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevron-down">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M6 9l6 6l6 -6" />
                        </svg>
                    </a>
                    <ul class="dropdown attendanceDropdown desktop">
                        <li class="sidebarMenuItem">
                            <a href="{{ route('time-tracking') }}" class="nav-link {{ request()->routeIs('time-tracking') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-stopwatch">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 13a7 7 0 1 0 14 0a7 7 0 0 0 -14 0z" /><path d="M14.5 10.5l-2.5 2.5" /><path d="M17 8l1 -1" /><path d="M14 3h-4" />
                                </svg>
                                <span class="menu-text">Time Tracker</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('shift-management') }}" class="nav-link {{ request()->routeIs('shift-management') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout-kanban">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4l6 0" /><path d="M14 4l6 0" /><path d="M4 8m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M14 8m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                </svg>
                                <span class="menu-text">Shift Management</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('shift-adjustments') }}" class="nav-link {{ request()->routeIs('shift-adjustments') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-user">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21h-6a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4.5" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M19 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M22 22a2 2 0 0 0 -2 -2h-2a2 2 0 0 0 -2 2" />
                                </svg>
                                <span class="menu-text">Shift Adjustments</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebarMenuItem has-dropdown">
                    <a href="#" class="justify-between nav-link" onclick="toggleDropdown(event, 'hrManagementDropdown')">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-folders">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 3h3l2 2h5a2 2 0 0 1 2 2v7a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" /><path d="M17 16v2a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2h2" />
                            </svg>
                            <span class="menu-text">HR Management</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevron-down">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M6 9l6 6l6 -6" />
                        </svg>
                    </a>
                    <ul class="dropdown hrManagementDropdown desktop">
                        <li class="sidebarMenuItem">
                            <a href="{{ route('talent-acquisition.index') }}" class="nav-link {{ request()->routeIs('talent-acquisition.index') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-search">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h1.5"></path>
                                    <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                                    <path d="M20.2 20.2l1.8 1.8"></path>
                                </svg>
                                <span class="menu-text">Talent Acquisition</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('talent-management') }}" class="nav-link {{ request()->routeIs('talent-management') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-cog">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h2.5" /><path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M19.001 15.5v1.5" /><path d="M19.001 21v1.5" /><path d="M22.032 17.25l-1.299 .75" /><path d="M17.27 20l-1.3 .75" /><path d="M15.97 17.25l1.3 .75" /><path d="M20.733 20l1.3 .75" />
                                </svg>
                                <span class="menu-text">Talent Management</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('hr-toolkit') }}" class="nav-link {{ request()->routeIs('hr-toolkit') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-invoice">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    <path d="M9 7l1 0" /><path d="M9 13l6 0" />
                                    <path d="M13 17l2 0" />
                                </svg>
                                <span class="menu-text">HR Toolkit</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('benefits-and-taxes') }}" class="nav-link {{ request()->routeIs('benefits-and-taxes') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-receipt-tax">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14l6 -6" /><circle cx="9.5" cy="8.5" r=".5" fill="currentColor" /><circle cx="14.5" cy="13.5" r=".5" fill="currentColor" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" />
                                </svg>
                                <span class="menu-text">Benefits & Taxes</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('payout-reports') }}" class="nav-link {{ request()->routeIs('payout-reports') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-dollar">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                    <path d="M12 17v1m0 -8v1" />
                                </svg>
                                <span class="menu-text">Payout Reports</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('workforce-records') }}" class="nav-link {{ request()->routeIs('workforce-records') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-files">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" />
                                    <path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" />
                                </svg>
                                <span class="menu-text">Workforce Records</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebarMenuItem">
                    <a href="{{ route('legal-and-compliance') }}" class="nav-link {{ request()->routeIs('legal-and-compliance') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-contract">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 21h-2a3 3 0 0 1 -3 -3v-1h5.5" /><path d="M17 8.5v-3.5a2 2 0 1 1 2 2h-2" /><path d="M19 3h-11a3 3 0 0 0 -3 3v11" /><path d="M9 7h4" /><path d="M9 11h4" /><path d="M18.42 12.61a2.1 2.1 0 0 1 2.97 2.97l-6.39 6.42h-3v-3z" />
                        </svg>
                        <span class="menu-text">Legal & Compliance</span>
                    </a>
                </li>
                
                
                
                <li class="sidebarMenuItem">
                    <button class="toggle-btn" onclick="toggleSidebar()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout-sidebar-left-collapse">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
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
            <img src="https://www.stafify.com/cdn/shop/files/e50lj9u5c9xat9j7z3ne_752x.png?v=1613708232" class="site-logo" alt="Stafify Logo">
        </div>
    </div>
</div>

<!-- Sidebar Mobile Overlay -->
<div class="sidebar-overlay-mobile"></div>

<!-- Mobile Sidebar -->
<div class="sidebar-container-mobile">
    <div class="sidebar-mobile">
        <!-- Close Button -->
        <button class="sidebar-close-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M18 6l-12 12" />
                <path d="M6 6l12 12" />
            </svg>
        </button>
        
        <nav id="sidebarMenu">
            <div class="sidebarLogo">
                <img src="https://www.stafify.com/cdn/shop/files/e50lj9u5c9xat9j7z3ne_752x.png?v=1613708232" class="menu-text site-logo" alt="Stafify Logo">
                <img src="https://res.cloudinary.com/dt1vbprub/image/upload/v1741661073/Stafify_Icon_onet8q.jpg" class="site-icon" alt="Stafify Icon">
            </div>
            
            <!-- Profile Navigation Section -->
            <a href="#" class="flex gap-3 items-center card-profile">
                <div class="flex-shrink-0">
                    <img src="{{ asset('uploads/' . $user['profile_picture']) }}" alt="Profile Picture" class="w-9 h-9 rounded-full object-cover border border-gray-300">
                </div>
                <div class="details-profile menu-text">
                    <span class="profile-name">
                        <span id="username-display" class="profile-name">
                            {{ $user['full_name'] }}
                        </span>
                    </span>
                    <p class="profile-dept">
                        <span class="position-display">{{ $user['user_position'] }}</span>
                        <span class="separator"> - </span>
                        <span class="department-display">{{ $user['user_dept'] }}</span>
                    </p>
                </div>
            </a>
            
            <!-- Mobile navigation items (same as desktop but with mobile-specific dropdown classes) -->
            <ul class="flex flex-col gap-2 sidebarMenuItems">
                <li class="sidebarMenuItem">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout-grid">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                        </svg>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                
                <li class="sidebarMenuItem has-dropdown">
                    <a href="#" class="justify-between nav-link" onclick="toggleDropdown(event, 'attendanceMobileDropdown')">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 7v5l3 3"/>
                                <path d="M12 3a9 9 0 1 0 9 9"/>
                            </svg>
                            <span class="menu-text">Attendance</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevron-down">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M6 9l6 6l6 -6" />
                        </svg>
                    </a>
                    <ul class="dropdown attendanceMobileDropdown mobile">
                        <li class="sidebarMenuItem">
                            <a href="{{ route('time-tracking') }}" class="nav-link {{ request()->routeIs('time-tracking') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-stopwatch">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 13a7 7 0 1 0 14 0a7 7 0 0 0 -14 0z" /><path d="M14.5 10.5l-2.5 2.5" /><path d="M17 8l1 -1" /><path d="M14 3h-4" />
                                </svg>
                                <span class="menu-text">Time Tracker</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('shift-management') }}" class="nav-link {{ request()->routeIs('shift-management') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout-kanban">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4l6 0" /><path d="M14 4l6 0" /><path d="M4 8m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M14 8m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                </svg>
                                <span class="menu-text">Shift Management</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('shift-adjustments') }}" class="nav-link {{ request()->routeIs('shift-adjustments') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-user">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21h-6a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4.5" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M19 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M22 22a2 2 0 0 0 -2 -2h-2a2 2 0 0 0 -2 2" />
                                </svg>
                                <span class="menu-text">Shift Adjustments</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebarMenuItem has-dropdown">
                    <a href="#" class="justify-between nav-link" onclick="toggleDropdown(event, 'hrManagementMobileDropdown')">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-folders">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 3h3l2 2h5a2 2 0 0 1 2 2v7a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" /><path d="M17 16v2a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2h2" />
                            </svg>
                            <span class="menu-text">HR Management</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevron-down">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M6 9l6 6l6 -6" />
                        </svg>
                    </a>
                    <ul class="dropdown hrManagementMobileDropdown mobile">
                        <li class="sidebarMenuItem">
                            <a href="{{ route('talent-acquisition.index') }}" class="nav-link {{ request()->routeIs('talent-acquisition.index') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-search">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h1.5"></path>
                                    <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                                    <path d="M20.2 20.2l1.8 1.8"></path>
                                </svg>
                                <span class="menu-text">Talent Acquisition</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('talent-management') }}" class="nav-link {{ request()->routeIs('talent-management') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-cog">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h2.5" /><path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M19.001 15.5v1.5" /><path d="M19.001 21v1.5" /><path d="M22.032 17.25l-1.299 .75" /><path d="M17.27 20l-1.3 .75" /><path d="M15.97 17.25l1.3 .75" /><path d="M20.733 20l1.3 .75" />
                                </svg>
                                <span class="menu-text">Talent Management</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('hr-toolkit') }}" class="nav-link {{ request()->routeIs('hr-toolkit') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-invoice">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    <path d="M9 7l1 0" /><path d="M9 13l6 0" />
                                    <path d="M13 17l2 0" />
                                </svg>
                                <span class="menu-text">HR Toolkit</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('benefits-and-taxes') }}" class="nav-link {{ request()->routeIs('benefits-and-taxes') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-receipt-tax">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14l6 -6" /><circle cx="9.5" cy="8.5" r=".5" fill="currentColor" /><circle cx="14.5" cy="13.5" r=".5" fill="currentColor" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" />
                                </svg>
                                <span class="menu-text">Benefits & Taxes</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('payout-reports') }}" class="nav-link {{ request()->routeIs('payout-reports') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-dollar">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                    <path d="M12 17v1m0 -8v1" />
                                </svg>
                                <span class="menu-text">Payout Reports</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('workforce-records') }}" class="nav-link {{ request()->routeIs('workforce-records') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-files">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" />
                                    <path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" />
                                </svg>
                                <span class="menu-text">Workforce Records</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebarMenuItem">
                    <a href="{{ route('legal-and-compliance') }}" class="nav-link {{ request()->routeIs('legal-and-compliance') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-contract">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 21h-2a3 3 0 0 1 -3 -3v-1h5.5" /><path d="M17 8.5v-3.5a2 2 0 1 1 2 2h-2" /><path d="M19 3h-11a3 3 0 0 0 -3 3v11" /><path d="M9 7h4" /><path d="M9 11h4" /><path d="M18.42 12.61a2.1 2.1 0 0 1 2.97 2.97l-6.39 6.42h-3v-3z" />
                        </svg>
                        <span class="menu-text">Legal & Compliance</span>
                    </a>
                </li>
                
                <li class="sidebarMenuItem has-dropdown">
                    <a href="#" class="justify-between nav-link" onclick="toggleDropdown(event, 'emailNotificationMobileDropdown')">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail-cog">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 19h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v5" /><path d="M3 7l9 6l9 -6" /><path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M19.001 15.5v1.5" /><path d="M19.001 21v1.5" /><path d="M22.032 17.25l-1.299 .75" /><path d="M17.27 20l-1.3 .75" /><path d="M15.97 17.25l1.3 .75" /><path d="M20.733 20l1.3 .75" />
                            </svg>
                            <span class="menu-text">Email Notifications</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevron-down">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M6 9l6 6l6 -6" />
                        </svg>
                    </a>
                    <ul class="dropdown emailNotificationMobileDropdown mobile">
                        <li class="sidebarMenuItem">
                            <a href="{{ route('email-notification-time-tracker') }}" class="nav-link {{ request()->routeIs('email-notification-time-tracker') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail-exclamation">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M15 19h-10a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v5.5" /><path d="M3 7l9 6l9 -6" />
                                    <path d="M19 16v3" /><path d="M19 22v.01" />
                                </svg>
                                <span class="menu-text">Time Tracker</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('email-notification-talent-acquisition') }}" class="nav-link {{ request()->routeIs('email-notification-talent-acquisition') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail-exclamation">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M15 19h-10a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v5.5" /><path d="M3 7l9 6l9 -6" />
                                    <path d="M19 16v3" /><path d="M19 22v.01" />
                                </svg>
                                <span class="menu-text">Talent Acquisition</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('email-notification-shift-management') }}" class="nav-link {{ request()->routeIs('email-notification-shift-management') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail-exclamation">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M15 19h-10a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v5.5" /><path d="M3 7l9 6l9 -6" />
                                    <path d="M19 16v3" /><path d="M19 22v.01" />
                                </svg>
                                <span class="menu-text">Shift Management</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('email-notification-shift-adjustments') }}" class="nav-link {{ request()->routeIs('email-notification-shift-adjustments') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail-exclamation">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M15 19h-10a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v5.5" /><path d="M3 7l9 6l9 -6" />
                                    <path d="M19 16v3" /><path d="M19 22v.01" />
                                </svg>
                                <span class="menu-text">Shift Adjustments</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebarMenuItem">
                    <button class="toggle-btn" onclick="toggleSidebar()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout-sidebar-left-collapse">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                            <path d="M9 4v16" />
                            <path d="M15 10l-2 2l2 2" />
                        </svg>
                        <span class="menu-text">Collapse Sidebar</span>
                    </button>
                </li>
            </ul>
        </nav>
        
        <div class="flex flex-col items-center gap-10 poweredBy">
            <p>Powered By:</p>
            <img src="https://www.stafify.com/cdn/shop/files/e50lj9u5c9xat9j7z3ne_752x.png?v=1613708232" class="site-logo" alt="Stafify Logo">
        </div>
    </div>
</div>

<script src="https://cdn.tailwindcss.com"></script>
<script>
    // Sidebar
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

    // Sidebar Load icons
    document.addEventListener("DOMContentLoaded", function() {
        feather.replace();
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

        // Open Sidebar on Hamburger Click
        if (menuButton) {
            menuButton.addEventListener("click", openSidebar);
        }

        // Close Sidebar on Close Button Click
        if (closeButton) {
            closeButton.addEventListener("click", closeSidebar);
        }

        // Close Sidebar when clicking outside (on overlay)
        if (overlay) {
            overlay.addEventListener("click", closeSidebar);
        }
    });

    function toggleDropdown(event, dropdownClass) {
        event.preventDefault();
        event.stopPropagation();
        
        // Find both mobile and desktop versions of the dropdown
        const desktopDropdown = document.querySelector(`.${dropdownClass}.desktop`);
        const mobileDropdown = document.querySelector(`.${dropdownClass}.mobile`);
        
        // Determine which dropdown to use based on which one exists
        const dropdown = desktopDropdown || mobileDropdown;
        
        if (!dropdown) return;

        // Check if this dropdown is already open
        const isOpen = dropdown.classList.contains("open");
        
        // Close all other dropdowns first
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

    // Prevent dropdown items from closing the dropdown
    document.addEventListener("DOMContentLoaded", function() {
        // Add click event listeners to all dropdown items
        const dropdownItems = document.querySelectorAll('.dropdown .nav-link');
        dropdownItems.forEach(item => {
            item.addEventListener('click', function(event) {
                // Don't prevent the default link behavior, just stop propagation
                event.stopPropagation();
            });
        });
    });

    // Auto-expand parent dropdown when a page inside it is active
    document.addEventListener("DOMContentLoaded", function() {
        // Find active menu item in dropdown
        const activeMenuItemInDropdown = document.querySelector('.dropdown .nav-link.active');
        
        if (activeMenuItemInDropdown) {
            // Find the parent dropdown
            const parentDropdown = activeMenuItemInDropdown.closest('.dropdown');
            if (parentDropdown) {
                // Find the dropdown class name
                const dropdownClasses = parentDropdown.className.split(' ');
                let dropdownClass = '';
                for (let i = 0; i < dropdownClasses.length; i++) {
                    if (dropdownClasses[i] !== 'dropdown' && dropdownClasses[i] !== 'open' && dropdownClasses[i] !== 'mobile' && dropdownClasses[i] !== 'desktop') {
                        dropdownClass = dropdownClasses[i];
                        break;
                    }
                }
                
                // Auto-expand this dropdown
                if (dropdownClass) {
                    parentDropdown.style.maxHeight = parentDropdown.scrollHeight + "px";
                    parentDropdown.style.opacity = "1";
                    parentDropdown.style.marginBottom = "0px";
                    parentDropdown.classList.add("open");
                }
            }
        }
    });
</script>
