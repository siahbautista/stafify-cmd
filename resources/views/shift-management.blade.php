@extends('layouts.app')

@section('title', 'Shift Management')
@section('description', 'Manage employee shifts and work schedules efficiently.')

@section('content')
<div class="px-0">
    <!-- Include the libraries in the header -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/vis-timeline@7.7.2/standalone/umd/vis-timeline-graph2d.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/vis-timeline@7.7.2/styles/vis-timeline-graph2d.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/shift-management.css') }}"/>

    <script>
        // Pass PHP variables to JavaScript
        const userAccessLevel = {{ $accessLevel }};
    </script>

    @if($accessLevel == 2)
        <div class="flex items-center justify-between gap-2 mb-5">
            <div class="flex gap-2">
                <button type="button" id="openAssignShiftBtn" class="primary-button flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Assign New Shift
                </button>

                <button type="button" id="openAddEventBtn" class="secondary-button flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5" />
                        <path d="M16 3v4" />
                        <path d="M8 3v4" />
                        <path d="M4 11h16" />
                        <path d="M16 19h6" />
                        <path d="M19 16v6" />
                    </svg>
                    Add Event
                </button>
                
                <button type="button" id="openLateSettingsBtn" class="outline-button flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock-cog">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M21 12a9 9 0 1 0 -9.002 9" />
                        <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M19.001 15.5v1.5" />
                        <path d="M19.001 21v1.5" />
                        <path d="M22.032 17.25l-1.299 .75" />
                        <path d="M17.27 20l-1.3 .75" />
                        <path d="M15.97 17.25l1.3 .75" />
                        <path d="M20.733 20l1.3 .75" />
                        <path d="M12 7v5l2 2" />
                    </svg>
                    Clock-In Settings
                </button>
            </div>
            <div class="view-toggle-buttons">
                <div class="btn-group flex gap-2" role="group" aria-label="View toggle">
                    <button type="button" id="calendar-view-btn" class="btn btn-primary active flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-week">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                            <path d="M16 3v4" />
                            <path d="M8 3v4" />
                            <path d="M4 11h16" />
                            <path d="M7 14h.013" />
                            <path d="M10.01 14h.005" />
                            <path d="M13.01 14h.005" />
                            <path d="M16.015 14h.005" />
                            <path d="M13.015 17h.005" />
                            <path d="M7.01 17h.005" />
                            <path d="M10.01 17h.005" />
                        </svg>
                        Calendar View
                    </button>
                    <button type="button" id="timeline-view-btn" class="btn btn-outline-primary flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-timeline-event">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 20m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M10 20h-6" />
                            <path d="M14 20h6" />
                            <path d="M12 15l-2 -2h-3a1 1 0 0 1 -1 -1v-8a1 1 0 0 1 1 -1h10a1 1 0 0 1 1 1v8a1 1 0 0 1 -1 1h-3l-2 2z" />
                        </svg>
                        Timeline View
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Late Settings Modal -->
    <div id="lateSettingsModal">
        <div class="flex flex-col modal-content">
            <div class="modal-header">
                <h2>Clock-In Settings</h2>
                <span class="close" id="closeLateSettingsModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-x">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M18 6l-12 12" />
                        <path d="M6 6l12 12" />
                    </svg>
                </span>
            </div>
            <div class="modal-body mt-5">
                <form id="lateSettingsForm" method="POST" action="{{ route('shift-management.save-settings') }}">
                    @csrf
                
                    <div class="form-group">
                        <div class="flex gap-2 items-center">
                            <label for="early_clock_in_minutes mb-[0px]">Early Clock-In Grace Period (Minutes):</label>
                            <input type="number" id="early_clock_in_minutes" name="early_clock_in_minutes"
                                min="0" max="60" value="{{ $companySettings['early_clock_in_minutes'] ?? 15 }}" required>
                        </div>
                        <small class="form-text text-muted">
                            Maximum minutes staff can clock in before shift start while still being considered on-time (default: 15).
                        </small>
                    </div>
                
                    <div class="form-group">
                        <div class="flex gap-2 items-center">
                            <label for="on_time_late_minutes">Late Grace Period (Minutes):</label>
                            <input type="number" id="on_time_late_minutes" name="on_time_late_minutes"
                                min="0" max="30" value="{{ $companySettings['on_time_late_minutes'] ?? 5 }}" required>
                        </div>
                        
                        <small class="form-text text-muted">
                            Maximum minutes staff can clock in after shift start while still being considered on-time (default: 5).
                        </small>
                    </div>
                
                    <button type="submit" class="secondary-button">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
        
    <div class="rounded-shadow-box p-0">
        <button onclick="toggleSidebar()" class="lg:hidden text-primary rounded">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M4 8l16 0"/>
                <path d="M4 16l16 0"/>
            </svg>
        </button>

        @if(session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tab content -->
        <div id="tab-content">
            <!-- Calendar View for both Access Levels -->
            <div class="block" id="calendar-content" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div id="shift-calendar" 
                             data-shifts='{{ json_encode($shifts) }}' 
                             data-events='{{ json_encode($events) }}' 
                             data-access-level="{{ $accessLevel }}" 
                             style="height: 600px;">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Timeline View for Access Level 2 only -->
            @if($accessLevel == 2)
                <div class="hidden" id="timeline-content" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div id="shift-timeline" 
                                 data-shifts='{{ json_encode($shifts) }}' 
                                 class="dynamic-timeline-container">
                                 <!-- Height will be set dynamically by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($accessLevel == 2)
        <!-- Shift Assignment Modal - Two-Step Process -->
        <div id="assignShiftModal">
            <div class="flex flex-col modal-content">
                <div class="modal-header">
                    <div class="steps-indicator">
                        <span id="step1Indicator" class="step active">
                            <div class="number">1</div>
                            <span>
                                <h2>Assign New Shift</h2>
                                <p>Select Users</p>
                            </span>
                        </span>
                        <span id="step2Indicator" class="step">
                            <div class="number">2</div>
                            <span>
                                <h2>Assign New Shift</h2>
                                <p>Assign Shifts</p>
                            </span>
                        </span>
                    </div>
                    <button id="closeAssignShiftModal" class="cancel-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-x">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M18 6l-12 12" />
                            <path d="M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="modal-body">
                    <form method="post" action="{{ route('shift-management.assign-shifts') }}" id="shiftAssignmentForm" class="modal-form">
                        @csrf
                        <!-- Step 1: Search & Select Users -->
                        <div id="step1Content" class="step-content">
                            <div class="input-group">
                                <label for="userSearch" class="block ">Search Users:</label>
                                <input type="text" id="userSearch" placeholder="Search by name, department, or position...">
                            </div>
                                
                            <div class="input-group user-filter-options mt-4">
                                <label class="block ">Filter by Department:</label>
                                <div class="flex flex-wrap gap-2" id="departmentFilters">
                                    <!-- Will be populated dynamically -->
                                </div>
                            </div>
                            
                            <div class="max-h-60 overflow-y-auto border rounded-md mt-4">
                                <table class="w-full" id="userSelectionTable">
                                    <thead class="sticky top-0 bg-white">
                                        <tr>
                                            <th class="p-2 text-center">Select</th>
                                            <th class="p-2">Name</th>
                                            <th class="p-2">Department</th>
                                            <th class="p-2">Position</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr class="user-row border-t">
                                                <td class="p-2 text-center">
                                                    <input type="checkbox" name="selected_users[]" value="{{ $user->user_id }}" class="user-checkbox">
                                                </td>
                                                <td class="p-2">{{ $user->full_name }}</td>
                                                <td class="p-2">{{ $user->user_dept }}</td>
                                                <td class="p-2">{{ $user->user_position }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-5 flex justify-between items-center">
                                <div>
                                    <span class="selected-count">0</span> users selected
                                </div>
                                <div class="modal-buttons">
                                    <button type="button" id="cancelStep1" class="outline-button">Cancel</button>
                                    <button type="button" id="goToStep2" class="secondary-button">Proceed</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 2: Assign Shifts -->
                        <div id="step2Content" class="step-content hidden">
                            <div class="input-group mb-4">
                                <label for="weekPicker" class="block">Select Week:</label>
                                <input type="week" id="weekPicker" required>
                            </div>
                            
                            <div class="shift-type-templates input-group mb-4">
                                <label class="block">Quick Templates:</label>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-sm btn-outline template-btn" data-template="morning">Morning Shift</button>
                                    <button type="button" class="btn btn-sm btn-outline template-btn" data-template="afternoon">Afternoon Shift</button>
                                    <button type="button" class="btn btn-sm btn-outline template-btn" data-template="night">Night Shift</button>
                                    <button type="button" class="btn btn-sm btn-outline template-btn" data-template="custom">Custom</button>
                                </div>
                            </div>
                            
                            <div class="weekly-schedule border rounded-md overflow-x-auto">
                                <table class="w-full" id="weeklyScheduleTable">
                                    <thead>
                                        <tr>
                                            <th class="p-2">Day</th>
                                            <th class="p-2">Date</th>
                                            <th class="p-2">Assign</th>
                                            <th class="p-2">Start Time</th>
                                            <th class="p-2">End Time</th>
                                            <th class="p-2">Break</th>
                                            <th class="p-2">Location</th>
                                            <th class="p-2">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Will be populated dynamically based on selected week -->
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4 user-summary">
                                <h3 class="!font-500 !text-[#737373]">Selected Users:</h3>
                                <div id="selectedUsersSummary" class="mt-2">
                                    <!-- Will be populated dynamically -->
                                </div>
                            </div>
                            <div class="mt-5 flex justify-between items-center">
                                <div>
                                    <span class="selected-count">0</span> users selected
                                </div>
                                <div class="modal-buttons">
                                    <button type="button" id="backToStep1" class="outline-button">Back to User Selection</button>
                                    <button type="submit" name="assign_shifts" class="primary-button save-btn">Assign Shifts</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Event Modal -->
        <div id="addEventModal">
            <div class="flex flex-col modal-content relative">
                <div class="modal-header">
                    <h2>Add Calendar Event</h2>
                    <button id="closeAddEventModal" class="cancel-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6L6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('shift-management.add-event') }}" id="eventAddForm" class="modal-form">
                        @csrf
                        <div class="input-group">
                            <label for="eventTitle" class="block ">Event Title:</label>
                            <input type="text" id="eventTitle" name="event_title" class="form-control" required>
                        </div>
                        
                        <div class="input-group">
                            <label for="eventDate" class="block ">Event Date:</label>
                            <input type="date" id="eventDate" name="event_date" class="form-control" required>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="input-group">
                                <label for="eventStartTime" class="block ">Start Time:</label>
                                <input type="time" id="eventStartTime" name="event_start_time" class="form-control" required>
                            </div>
                            <div class="input-group">
                                <label for="eventEndTime" class="block ">End Time:</label>
                                <input type="time" id="eventEndTime" name="event_end_time" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="input-group">
                            <label for="eventLocation" class="block ">Location:</label>
                            <input type="text" id="eventLocation" name="event_location" class="form-control">
                        </div>
                        
                        <div class="input-group">
                            <label for="eventType" class="block ">Event Type:</label>
                            <select id="eventType" name="event_type" class="form-control" required>
                                <option value="meeting">Meeting</option>
                                <option value="training">Training</option>
                                <option value="holiday">Holiday</option>
                                <option value="announcement">Announcement</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="input-group">
                            <label for="eventVisibility" class="block ">Visibility:</label>
                            <select id="eventVisibility" name="event_visibility" class="form-control" required>
                                <option value="all">All Users</option>
                                <option value="management">Management Only</option>
                            </select>
                        </div>
                        
                        <div class="input-group">
                            <label for="eventDescription" class="block ">Description:</label>
                            <textarea id="eventDescription" name="event_description" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <div class="mt-4 flex justify-center">
                            <div class="modal-buttons">
                                <button type="button" id="cancelAddEvent" class="outline-button">Cancel</button>
                                <button type="submit" name="add_event" class="primary-button">Add Event</button>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    // Late Settings Modal Fix
    document.addEventListener('DOMContentLoaded', function() {
        // Late Settings Modal
        const lateSettingsModal = document.getElementById("lateSettingsModal");
        const openLateSettingsBtn = document.getElementById("openLateSettingsBtn");
        const closeLateSettingsModal = document.getElementById("closeLateSettingsModal");
        
        // Check if elements exist to prevent errors
        if (lateSettingsModal && openLateSettingsBtn && closeLateSettingsModal) {
            // Make sure the modal is initially hidden with proper CSS
            lateSettingsModal.style.opacity = "0";
            lateSettingsModal.style.transform = "translateY(20px)";
            lateSettingsModal.style.visibility = "hidden";
            
            openLateSettingsBtn.addEventListener("click", function() {
                lateSettingsModal.style.opacity = "1";
                lateSettingsModal.style.visibility = "visible";
                lateSettingsModal.style.transform = "translateY(0)";
                console.log("Late settings modal opened");
            });
            
            closeLateSettingsModal.addEventListener("click", function() {
                lateSettingsModal.style.opacity = "0";
                lateSettingsModal.style.visibility = "hidden";
                lateSettingsModal.style.transform = "translateY(20px)";
                console.log("Late settings modal closed by X button");
            });
            
            window.addEventListener("click", function(event) {
                if (event.target == lateSettingsModal) {
                    lateSettingsModal.style.opacity = "0";
                    lateSettingsModal.style.visibility = "hidden";
                    lateSettingsModal.style.transform = "translateY(20px)";
                    console.log("Late settings modal closed by outside click");
                }
            });
        } else {
            console.error("One or more late settings modal elements not found");
            // Log which elements are missing for debugging
            if (!lateSettingsModal) console.error("- lateSettingsModal element not found");
            if (!openLateSettingsBtn) console.error("- openLateSettingsBtn element not found");
            if (!closeLateSettingsModal) console.error("- closeLateSettingsModal element not found");
        }
    });
    
document.addEventListener('DOMContentLoaded', function() {
        // Function to calculate and set timeline height based on data
        function setDynamicTimelineHeight() {
            const timelineContainer = document.getElementById('shift-timeline');
            if (!timelineContainer) return;
            
            try {
                // Get the shifts data
                const shiftsData = JSON.parse(timelineContainer.getAttribute('data-shifts') || '[]');
                
                // Count unique users to determine vertical space needed
                const uniqueUsers = new Set();
                shiftsData.forEach(shift => {
                    if (shift.user_id) {
                        uniqueUsers.add(shift.user_id);
                    }
                });
                
                // Calculate height based on unique users and entries
                const userCount = uniqueUsers.size;
                const entryCount = shiftsData.length;
                
                // Base calculation
                const baseHeight = 150; // Minimum container height
                const heightPerUser = 60; // Height allocated per user row
                const minHeight = 200; // Absolute minimum height
                const maxHeight = 800; // Maximum height
                
                // Calculate the dynamic height with a minimum and maximum constraint
                let dynamicHeight;
                if (userCount === 0) {
                    // If no users, use minimum height
                    dynamicHeight = minHeight;
                } else {
                    // Calculate based on number of users
                    dynamicHeight = Math.min(
                        Math.max(minHeight, baseHeight + (userCount * heightPerUser)),
                        maxHeight
                    );
                    
                    // Add extra height if there are many entries per user
                    const entriesPerUser = entryCount / userCount;
                    if (entriesPerUser > 3) {
                        // Add extra height for many entries
                        const extraHeight = Math.min((entriesPerUser - 3) * 10, 100);
                        dynamicHeight += extraHeight;
                    }
                }
                
                // Set the container height
                timelineContainer.style.height = `${dynamicHeight}px`;
                
                // If using vis-timeline library, update its height as well
                if (window.timeline) {
                    window.timeline.setOptions({ height: `${dynamicHeight}px` });
                }
                
                console.log(`Timeline height set to ${dynamicHeight}px based on ${userCount} users and ${entryCount} entries`);
            } catch (error) {
                console.error('Error setting dynamic timeline height:', error);
                // Fallback to default height
                timelineContainer.style.height = '400px';
            }
        }
        
        // Call the function when the timeline view is shown
        const timelineViewBtn = document.getElementById('timeline-view-btn');
        if (timelineViewBtn) {
            timelineViewBtn.addEventListener('click', function() {
                // Small delay to ensure the timeline is rendered
                setTimeout(setDynamicTimelineHeight, 100);
            });
        }
        
        // Also set initial height when the page loads if timeline is visible
        if (document.getElementById('timeline-content') && 
            !document.getElementById('timeline-content').classList.contains('hidden')) {
            setTimeout(setDynamicTimelineHeight, 100);
        }
        
        // For any filter buttons or operations that might change visible data
        // Add this event listener to those elements
        document.querySelectorAll('.filter-button').forEach(button => {
            button.addEventListener('click', () => {
                setTimeout(setDynamicTimelineHeight, 100);
            });
        });
        
        // If you have an existing timeline initialization function,
        // call setDynamicTimelineHeight() at the end of that function
});
</script>

<!-- Include the JavaScript for calendar and timeline -->
<script src="{{ asset('js/shift-management.js') }}"></script>
@endsection