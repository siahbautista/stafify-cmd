@extends('layouts.app')

@section('title', 'Workforce Records')
@section('description', 'Manage comprehensive employee records and workforce data.')

@section('content')
<div class="px-0">
    <div class="flex flex-col gap-5 rounded-shadow-box animate__animated animate__fadeInUp w-full">
        <div class="flex gap-3">
            <div class="search-box flex-1">
                <i class="ti ti-search"></i>
                <input type="text" id="searchUser" placeholder="Search employee...">
            </div>
            <select id="departmentFilter" class="department-filter">
                <option value="all">All Departments</option>
                @foreach($departments as $department)
                    <option value="{{ $department }}">{{ $department }}</option>
                @endforeach
            </select>
        </div>
        
        <div id="userCardsContainer" class="user-cards-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($users as $user)
                @php
                    // Default profile picture if none exists
                    $profilePic = !empty($user->profile_picture) ? $user->profile_picture : asset('uploads/default.png');
                    
                    // If the profile picture doesn't contain a path but is just a filename, add the path
                    if (!empty($profilePic) && strpos($profilePic, '/') === false && strpos($profilePic, '\\') === false) {
                        $profilePic = asset('uploads/' . $profilePic);
                    }
                    
                    // Prepare user details
                    $userId = $user->user_id ?? '';
                    $userName = $user->full_name ?? 'N/A';
                    $userDept = $user->user_dept ?? 'N/A';
                    $userPosition = $user->user_position ?? 'N/A';
                    $userEmail = $user->user_email ?? '';
                    $userPhone = $user->phone_number ?? 'N/A';
                    
                    // Get user status and other settings
                    $userStatus = $user->user_status ?? 'active';
                    $engagementStatus = $user->engagement_status ?? 'full_time';
                    $userType = $user->user_type ?? 'employee';
                    $silStatus = $user->sil_status ?? '0';
                    $wageType = $user->wage_type ?? 'non_mwe';
                    
                    // Define status color and icon classes
                    $statusColor = 'bg-gray-200 text-gray-800'; // Default
                    $statusDot = 'bg-gray-400';
                    
                    switch($userStatus) {
                        case 'active':
                            $statusColor = 'bg-green-100 text-green-800';
                            $statusDot = 'bg-green-500';
                            break;
                        case 'awol':
                            $statusColor = 'bg-red-100 text-red-800';
                            $statusDot = 'bg-red-500';
                            break;
                        case 'blacklisted':
                            $statusColor = 'bg-black text-white';
                            $statusDot = 'bg-black';
                            break;
                        case 'resigned':
                            $statusColor = 'bg-yellow-100 text-yellow-800';
                            $statusDot = 'bg-yellow-500';
                            break;
                        case 'transferred':
                            $statusColor = 'bg-blue-100 text-blue-800';
                            $statusDot = 'bg-blue-500';
                            break;
                        case 'disengaged':
                            $statusColor = 'bg-purple-100 text-purple-800';
                            $statusDot = 'bg-purple-500';
                            break;
                        case 'engaged':
                            $statusColor = 'bg-teal-100 text-teal-800';
                            $statusDot = 'bg-teal-500';
                            break;
                    }
                @endphp
                
                <div class="user-card" data-department="{{ $userDept }}" data-name="{{ $userName }}">
                    <div class="card bg-white rounded-lg shadow-md p-4 relative">
                        <!-- Status Badge -->
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }} flex items-center">
                                <span class="w-2 h-2 rounded-full {{ $statusDot }} mr-1"></span>
                                {{ ucfirst($userStatus) }}
                            </span>
                        </div>
                        
                        <div class="flex items-center mb-4">
                            <div class="profile-pic w-16 h-16 rounded-full overflow-hidden mr-4">
                                <img src="{{ $profilePic }}" alt="{{ $userName }}" class="w-full h-full object-cover">
                            </div>
                            <div class="user-info flex-1">
                                <h3 class="text-lg font-semibold">{{ $userName }}</h3>
                                <p class="text-sm text-gray-600">{{ $userPosition }}</p>
                                <p class="text-sm text-gray-600">{{ $userDept }}</p>
                            </div>
                            <!-- Kebab dropdown menu -->
                            <div class="kebab-dropdown relative">
                                <button class="dropdown-toggle p-1 hover:bg-gray-100 rounded-full" onclick="toggleKebabDropdown(this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-dots-vertical">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                        <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                        <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                    </svg>
                                </button>
                                <div class="dropdown-menu absolute right-0 mt-2 hidden bg-white shadow-lg rounded-md py-1 z-10 min-w-48">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="openEvaluationModal('{{ $userId }}', '{{ $userName }}')">
                                        <i class="ti ti-star mr-2"></i>Performance Eval
                                    </a>
                                    
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="openRatesModal('{{ $userId }}', '{{ $userName }}')">
                                        <i class="ti ti-cash mr-2"></i>User Rates
                                    </a>
                
                                    @if($userType !== 'isp')
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="openFringeBenefitsModal('{{ $userId }}', '{{ $userName }}')">
                                        <i class="ti ti-gift mr-2"></i>Fringe Benefits
                                    </a>
                                    @endif
                                    
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="openDeMinimisModal('{{ $userId }}', '{{ $userName }}')">
                                        <i class="ti ti-package mr-2"></i>De Minimis Benefits
                                    </a>
                            
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="openSettingsModal('{{ $userId }}', '{{ $userName }}')">
                                        <i class="ti ti-settings mr-2"></i>User Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="settings-badges flex flex-wrap gap-2 mb-4">
                            <!-- Engagement Status Badge -->
                            <div class="badge {{ $engagementStatus == 'full_time' ? 'bg-blue-100 text-blue-800' : 'bg-indigo-100 text-indigo-800' }} px-2 py-1 rounded text-xs font-medium">
                                {{ $engagementStatus == 'full_time' ? 'Full Time' : 'Part Time' }}
                            </div>
                            
                            <!-- User Type Badge -->
                            <div class="badge {{ $userType == 'employee' ? 'bg-cyan-100 text-cyan-800' : 'bg-orange-100 text-orange-800' }} px-2 py-1 rounded text-xs font-medium">
                                {{ $userType == 'employee' ? 'Employee' : 'ISP' }}
                            </div>
                                                            
                            <!-- SIL Status Badge -->
                            <div class="badge {{ $silStatus == '1' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }} px-2 py-1 rounded text-xs font-medium">
                                SIL: {{ $silStatus == '1' ? 'On' : 'Off' }}
                            </div>
                            
                            <!-- Wage Type Badge -->
                            <div class="badge {{ $wageType == 'mwe' ? 'bg-amber-100 text-amber-800' : 'bg-violet-100 text-violet-800' }} px-2 py-1 rounded text-xs font-medium">
                                {{ $wageType == 'mwe' ? 'MWE' : 'Non-MWE' }}
                            </div>
                        </div>
                        
                        <div class="contact-info mb-4">
                            <div class="flex items-center mb-2">
                                <i class="ti ti-mail text-blue-500 mr-2"></i>
                                <span class="text-sm truncate">{{ $userEmail }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="ti ti-phone text-blue-500 mr-2"></i>
                                <span class="text-sm">{{ $userPhone }}</span>
                            </div>
                        </div>
                        
                        <div class="actions flex gap-2">
                            @if(!empty($userEmail))
                            <a href="mailto:{{ $userEmail }}" class="btn btn-primary flex-1 py-2 px-3 text-center rounded" style="background-color: #3b82f6 !important; color: white !important; border: none !important;">
                                <i class="ti ti-mail mr-1"></i> Email
                            </a>
                            @endif
                            
                            <button onclick="openUserFilesModal('{{ $userId }}', '{{ $userName }}', '{{ $userEmail }}')" class="btn btn-secondary flex-1 py-2 px-3 text-center rounded" style="background-color: #f3f4f6 !important; color: #374151 !important; border: none !important;">
                                <i class="ti ti-files mr-1"></i> Files
                            </button>
                </div>
                </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8">No users found</div>
            @endforelse
        </div>
    </div>
</div>

@include('workforce-records-modals')

@endsection

@push('styles')
<style>
/* Enhanced styles for user cards */
.user-cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
}

.user-card .card {
    transition: all 0.3s ease;
    border: 1px solid #eaeaea;
    position: relative;
    overflow: hidden;
}

.user-card .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.user-card .card:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(to right, #3b82f6, #60a5fa);
}

.profile-pic {
    background-color: #f0f0f0;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.user-card:hover .profile-pic {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

.user-info h3 {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;
}

.contact-info {
    font-size: 0.875rem;
    color: #4b5563;
}

.contact-info div {
    padding: 4px 0;
}

.contact-info i {
    width: 20px;
    text-align: center;
}

.settings-badges {
    transition: all 0.3s ease;
}

.badge {
    display: inline-flex;
    align-items: center;
    transition: all 0.2s ease;
}

.badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn {
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    border-radius: 6px;
    font-weight: 500;
}

.btn-primary {
    background-color: #3b82f6;
    color: white;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
}

.btn-primary:hover {
    background-color: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(59, 130, 246, 0.4);
}

/* Email button specific styling */
.btn-primary {
    background-color: #3b82f6 !important;
    color: white !important;
    border: none;
}

.btn-primary:hover {
    background-color: #2563eb !important;
    color: white !important;
}

.btn-secondary {
    background-color: #f3f4f6;
    color: #374151;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.btn-secondary:hover {
    background-color: #e5e7eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.filters {
    margin-bottom: 1.5rem;
    padding: 16px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
}

.search-box input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.2s;
}

.search-box input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

.search-box i {
    position: absolute;
    left: 0.75rem;
    color: #9ca3af;
}

.department-filter {
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    min-width: 180px;
    transition: all 0.2s;
}

.department-filter:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

/* Dropdown menu styling */
.dropdown-menu {
    border: 1px solid #e5e7eb;
    min-width: 220px;
    border-radius: 8px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.dropdown-menu a {
    transition: all 0.2s;
    white-space: nowrap;
}

.dropdown-menu a:hover {
    background-color: #f3f4f6;
}

/* Star rating styles */
.star-rating {
    display: flex;
    gap: 2px;
    margin-bottom: 4px;
}

.star {
    font-size: 24px;
    color: #d1d5db;
    cursor: pointer;
    transition: color 0.2s;
    user-select: none;
}

.star:hover,
.star.active {
    color: #fbbf24;
}

.star.active {
    color: #f59e0b;
}

.evaluation-item {
    padding: 12px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background-color: #fafafa;
}

.evaluation-item:hover {
    background-color: #f3f4f6;
}

@media (max-width: 768px) {
    .user-cards-container {
        grid-template-columns: 1fr;
    }
    
    .filters {
        flex-direction: column;
    }
    
    .department-filter {
        width: 100%;
        margin-top: 0.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/workforce-records.js') }}"></script>
@endpush
