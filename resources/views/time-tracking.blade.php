@extends('layouts.app')

@section('title', 'Time Tracking')
@section('description', 'Track employee time and attendance with our comprehensive time tracking system.')

@section('content')
<div class="px-0">
    <div class="flex gap-5 max-[768px]:flex-col">
        <!-- Time Tracking Dashboard -->
        <div class="rounded-shadow-box w-4/12 max-[768px]:w-full">
            <!-- Today's Shift & Clock In/Out Section -->
            <div class="grid grid-cols-1 lg:grid-cols- gap-6 mb-8">
                <!-- Today's Shift Information -->
                <div class="card lg:col-span-2" id="todayShiftCard">
                    <div class="card-header mb-5">
                        <h2>Today's Shift</h2>
                        <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
                    </div>
                    <div class="card-body">
                        @if(empty($todayShifts))
                            <div class="text-center py-8 flex flex-col justify-center items-center h-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <p class="text-lg font-medium text-gray-600">No shifts assigned for today</p>
                                <p class="text-sm text-gray-500 mt-1">Check your schedule for upcoming shifts</p>
                            </div>
                        @else
                            @foreach($todayShifts as $index => $shift)
                                <div class="shift-card {{ $index > 0 ? 'mt-4 pt-4 border-t' : '' }}">
                                    <div class="flex flex-col md:flex-row md:justify-between md:items-center w-full">
                                        <div class="mb-3 md:mb-0 w-full !py-[10px] !px-[15px] rounded-[15px] 
                                            @if(isset($todayTimeTracking['status']))
                                                @if($todayTimeTracking['status'] == 'completed') bg-green-100 text-green-800 border-l-2 border-l-[#22A00E]
                                                @elseif($todayTimeTracking['status'] == 'incomplete') bg-yellow-100 text-yellow-800 border-l-2 border-l-[#b59813]
                                                @elseif($todayTimeTracking['status'] == 'absent') bg-red-100 text-red-800 border-l-2 border-l-[#b51313]
                                                @else bg-blue-100 text-blue-800 border-l-2 border-l-[#1F5497]
                                                @endif
                                            @else
                                                bg-gray-100
                                            @endif">
                                            <h3 class="text-lg font-semibold flex items-center w-full justify-between">
                                                {{ $shift['shift_type'] ?? 'Regular' }} Shift
                                                <span class="ml-2 px-2 py-1 text-xs rounded 
                                                    @if(isset($todayTimeTracking['status']))
                                                        @if($todayTimeTracking['status'] == 'completed') bg-green-100 text-green-800
                                                        @elseif($todayTimeTracking['status'] == 'incomplete') bg-yellow-100 text-yellow-800
                                                        @elseif($todayTimeTracking['status'] == 'absent') bg-red-100 text-red-800
                                                        @else bg-[#1F5497] text-white
                                                        @endif
                                                    @else
                                                        bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ isset($todayTimeTracking['status']) ? ucfirst($todayTimeTracking['status']) : 'Not Started' }}
                                                </span>
                                            </h3>
                                            <p class="text-gray-600">
                                                <span class="inline-block mr-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($shift['start_time'])->format('h:i A') }} - {{ \Carbon\Carbon::parse($shift['end_time'])->format('h:i A') }}
                                                </span>
                                                @if(!empty($shift['location']))
                                                <span class="inline-block">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    {{ $shift['location'] }}
                                                </span>
                                                @endif
                                            </p>
                                            @if(!empty($shift['notes']))
                                                <p class="text-gray-600 mt-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    {{ $shift['notes'] }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="rounded-shadow-box w-4/12 max-[768px]:w-full">
            <!-- Clock In/Out Card -->
            <div class="card" id="clockInOutCard">
                <div class="card-header">
                    <h2>Time Clock</h2>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="text-3xl font-bold" id="currentTime">
                            {{ now()->format('h:i:s A') }}
                        </div>
                        <div class="text-gray-500 text-sm" id="currentDate">
                            {{ now()->format('l, F j, Y') }}
                        </div>
                    </div>
                    
                    <div class="clock-buttons flex flex-col space-y-4">
                        @if(!empty($todayShifts))
                            @if(!$hasActiveClockIn)
                                <form method="post" action="{{ route('time-tracking.clock-in') }}" class="clock-in-form" id="clockInForm">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="location" class="block mb-1 font-medium">Location (optional):</label>
                                        <input type="text" name="location" id="location" class="form-control" placeholder="Enter your location">
                                    </div>
                                    <div class="mb-4">
                                        <label for="notes" class="block mb-1 font-medium">Notes (optional):</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Any notes for today's shift"></textarea>
                                    </div>
                                    <div class="flex justify-center w-full">
                                        <button type="submit" name="clock_in" class="primary-button flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-login-2">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M9 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" />
                                                <path d="M3 12h13l-3 -3" />
                                                <path d="M13 15l3 -3" />
                                            </svg>
                                            Clock In
                                        </button>
                                    </div>
                                </form>
                            @else
                                <form method="post" action="{{ route('time-tracking.clock-out') }}" class="clock-out-form" id="clockOutForm">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="notes" class="block mb-1 font-medium">Notes (optional):</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Any notes before clocking out"></textarea>
                                    </div>
                                    <div class="flex justify-center w-full">
                                        <button type="submit" name="clock_out" class="danger-button flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-logout">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                                <path d="M9 12h12l-3 -3" />
                                                <path d="M18 15l3 -3" />
                                            </svg>
                                            Clock Out
                                        </button>
                                    </div>
                                </form>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                <p class="text-lg font-medium text-gray-600">No Active Shift</p>
                                <p class="text-sm text-gray-500 mt-1">You don't have a shift scheduled for today</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="rounded-shadow-box w-4/12 max-[768px]:w-full">
            <div class="card">
                <div class="card-header">
                    <h2>Activities</h2>
                </div>
                <div class="card-body">
                    <div class="time-tracking-status">
                        @if(isset($todayTimeTracking['entries']) && !empty($todayTimeTracking['entries']))
                            <h4 class="font-medium mb-2">Time Entries:</h4>
                            @foreach($todayTimeTracking['entries'] as $index => $entry)
                                <div class="mb-3 p-2 bg-gray-50 rounded">
                                    <div class="mb-1">
                                        <span class="font-medium">Clock In:</span> 
                                        {{ \Carbon\Carbon::parse($entry['clock_in_time'])->format('h:i A') }}
                                    </div>
                                    
                                    @if(isset($entry['clock_out_time']))
                                        <div class="mb-1">
                                            <span class="font-medium">Clock Out:</span> 
                                            {{ \Carbon\Carbon::parse($entry['clock_out_time'])->format('h:i A') }}
                                        </div>
                                        
                                        <div class="mb-1">
                                            <span class="font-medium">Duration:</span> 
                                            @if($entry['total_hours'])
                                                @php
                                                    $totalHours = $entry['total_hours'];
                                                    $hours = floor($totalHours);
                                                    $minutes = floor(($totalHours - $hours) * 60);
                                                    $seconds = round((((($totalHours - $hours) * 60) - $minutes) * 60));
                                                    
                                                    $formattedDuration = '';
                                                    if ($hours > 0) $formattedDuration .= $hours . 'h ';
                                                    if ($minutes > 0) $formattedDuration .= $minutes . 'm ';
                                                    if ($seconds > 0 || $minutes == 0) $formattedDuration .= $seconds . 's';
                                                @endphp
                                                {{ $formattedDuration }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-blue-600 font-medium">Currently clocked in</div>
                                    @endif
                                    
                                    @if(!empty($entry['location']))
                                        <div class="text-xs text-gray-600">
                                            <span class="font-medium">Location:</span> {{ $entry['location'] }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            
                            @if(isset($todayTimeTracking['total_hours']) && $todayTimeTracking['total_hours'] > 0)
                                <div class="mt-2 pt-2 border-t border-gray-200">
                                    <span class="font-medium">Total Duration:</span> 
                                    @php
                                        $totalHours = $todayTimeTracking['total_hours'];
                                        $hours = floor($totalHours);
                                        $minutes = floor(($totalHours - $hours) * 60);
                                        $seconds = round((((($totalHours - $hours) * 60) - $minutes) * 60));
                                        
                                        $formattedDuration = '';
                                        if ($hours > 0) $formattedDuration .= $hours . 'h ';
                                        if ($minutes > 0) $formattedDuration .= $minutes . 'm ';
                                        if ($seconds > 0 || $minutes == 0) $formattedDuration .= $seconds . 's';
                                    @endphp
                                    {{ $formattedDuration }}
                                </div>
                            @endif
                        @else
                            <div class="text-gray-500">No time entries for today</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($accessLevel == 2)
        <div class="rounded-shadow-box mt-5">
            <!-- Time Tracking Records -->
            <div class="card">
                <div class="card-header flex flex-row gap-[10px] justify-between items-center max-[768px]:flex-col max-[768px]:items-start max-[768px]:gap-[20px] mb-5">
                    <h2>Time Tracking Dashboard</h2>
                    <div class="flex items-center space-x-4 items-center gap-[10px] max-[768px]:w-full max-[768px]:gap-[10px] max-[510px]:flex-col max-[510px]:items-start">
                        <div class="relative">
                            <div class="input-group">
                                <select id="userFilter">
                                    <option value="all">All Users</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->user_id }}" {{ $selectedUserId == $user->user_id ? 'selected' : '' }}>
                                            {{ $user->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="input-group">
                                <input type="month" id="monthFilter" value="{{ $currentMonth }}" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Summary Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        @php
                            // Calculate summary statistics based on filtered records
                            $filteredRecords = array_filter($timeTrackingRecords, function($record) use ($currentMonth) {
                                return substr($record['record_date'], 0, 7) === $currentMonth;
                            });
                            
                            // Initialize counters
                            $uniqueDays = [];
                            $totalHours = 0;
                            $onTimeCount = 0;
                            $lateCount = 0;
                            
                            foreach ($filteredRecords as $record) {
                                if ($record['status'] == 'completed') {
                                    // Extract the date part only (YYYY-MM-DD)
                                    $recordDate = substr($record['record_date'], 0, 10);
                                    
                                    // Add to unique days array if not already counted
                                    if (!in_array($recordDate, $uniqueDays)) {
                                        $uniqueDays[] = $recordDate;
                                    }
                                    
                                    $totalHours += $record['total_hours'];
                                    
                                    if (isset($record['clock_in_status']) && ($record['clock_in_status'] == 'on_time' || $record['clock_in_status'] == 'on_time_early')) {
                                        $onTimeCount++;
                                    } else if (isset($record['clock_in_status']) && ($record['clock_in_status'] == 'slightly_late' || $record['clock_in_status'] == 'late')) {
                                        $lateCount++;
                                    }
                                }
                            }
                            
                            // Use count of unique days for the display
                            $totalDays = count($uniqueDays);

                            // Format hours
                            $formattedHours = floor($totalHours);
                            $formattedMinutes = floor(($totalHours - $formattedHours) * 60);
                            $hoursDisplay = $formattedHours . 'h ' . $formattedMinutes . 'm';
                            
                            // Calculate on-time percentage
                            $onTimePercentage = $totalDays > 0 ? round(($onTimeCount / $totalDays) * 100) : 0;
                        @endphp
                        
                        <!-- Days Worked Card -->
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="text-sm font-medium text-gray-500">Days Worked</h3>
                            <p class="mt-1 text-3xl font-semibold">{{ $totalDays }}</p>
                            <div class="text-xs text-gray-500 mt-1">This month</div>
                        </div>
                        
                        <!-- Total Hours Card -->
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="text-sm font-medium text-gray-500">Total Hours</h3>
                            <p class="mt-1 text-3xl font-semibold">{{ $hoursDisplay }}</p>
                            <div class="text-xs text-gray-500 mt-1">This month</div>
                        </div>
                        
                        <!-- On Time Rate Card -->
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="text-sm font-medium text-gray-500">On Time Rate</h3>
                            <p class="mt-1 text-3xl font-semibold">{{ $onTimePercentage }}%</p>
                            <div class="text-xs text-gray-500 mt-1">{{ $onTimeCount }} day/s on time</div>
                        </div>
                        
                        <!-- Late Card -->
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="text-sm font-medium text-gray-500">Late Arrivals</h3>
                            <p class="mt-1 text-3xl font-semibold">{{ $lateCount }}</p>
                            <div class="text-xs text-gray-500 mt-1">This month</div>
                        </div>
                    </div>
                    
                    <!-- View Toggle -->
                    <div class="flex justify-between items-center gap-[10px] justify-between items-center max-[768px]:flex-col max-[768px]:items-start max-[768px]:gap-[20px] mb-5">
                        <h3 class="text-lg font-medium">Attendance Records</h3>
                        <div class="flex space-x-2">
                            <button id="viewSummary" class="btn-sm bg-blue-500 text-white px-3 py-1 rounded active">Summary</button>
                            <button id="viewDetailed" class="btn-sm bg-gray-200 text-gray-700 px-3 py-1 rounded">Detailed</button>
                        </div>
                    </div>
                    
                    <!-- Calendar View (Summary) -->
                    <div id="summaryView" class="mb-6">
                        <div class="grid grid-cols-7 gap-2">
                            <div class="text-center text-xs font-medium text-gray-500 p-1">Sun</div>
                            <div class="text-center text-xs font-medium text-gray-500 p-1">Mon</div>
                            <div class="text-center text-xs font-medium text-gray-500 p-1">Tue</div>
                            <div class="text-center text-xs font-medium text-gray-500 p-1">Wed</div>
                            <div class="text-center text-xs font-medium text-gray-500 p-1">Thu</div>
                            <div class="text-center text-xs font-medium text-gray-500 p-1">Fri</div>
                            <div class="text-center text-xs font-medium text-gray-500 p-1">Sat</div>
                            
                            @php
                                // Get first day of month and total days
                                $firstDay = date('N', strtotime($currentMonth . '-01'));
                                $daysInMonth = date('t', strtotime($currentMonth . '-01'));
                                
                                // Adjust Sunday as first day (1 -> 7)
                                $firstDay = $firstDay % 7;
                                
                                // Add empty cells for days before first of month
                                for ($i = 0; $i < $firstDay; $i++) {
                                    echo '<div class="p-1"></div>';
                                }
                                
                                // Create calendar cells for each day
                                for ($day = 1; $day <= $daysInMonth; $day++) {
                                    $date = $currentMonth . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                                    $dayRecords = array_filter($filteredRecords, function($record) use ($date) {
                                        return substr($record['record_date'], 0, 10) === $date;
                                    });
                                    
                                    $cellClass = 'text-center p-2 rounded-lg border';
                                    $statusIndicator = ''; // Default empty status indicator
                                    
                                    if (!empty($dayRecords)) {
                                        foreach ($dayRecords as $record) {
                                            if ($record['status'] == 'completed') {
                                                if (isset($record['clock_in_status']) && ($record['clock_in_status'] == 'on_time' || $record['clock_in_status'] == 'on_time_early')) {
                                                    $cellClass .= ' bg-green-100 border-green-200';
                                                    $statusIndicator = '<div class="h-2 w-2 rounded-full bg-green-500 mx-auto mt-1"></div>';
                                                } else if (isset($record['clock_in_status']) && ($record['clock_in_status'] == 'slightly_late' || $record['clock_in_status'] == 'late')) {
                                                    $cellClass .= ' bg-red-100 border-red-200';
                                                    $statusIndicator = '<div class="h-2 w-2 rounded-full bg-red-500 mx-auto mt-1"></div>';
                                                }
                                            } else if ($record['status'] == 'absent') {
                                                $cellClass .= ' bg-red-50 border-red-100';
                                                $statusIndicator = '<div class="h-2 w-2 rounded-full bg-red-400 mx-auto mt-1"></div>';
                                            }
                                        }
                                    } else {
                                        // Check if this day is in the future
                                        if (strtotime($date) > time()) {
                                            $cellClass .= ' bg-gray-50 text-gray-400';
                                        } else {
                                            $cellClass .= ' bg-white';
                                        }
                                        // Add default gray status indicator for days without records
                                        $statusIndicator = '<div class="h-2 w-2 rounded-full bg-gray-300 mx-auto mt-1"></div>';
                                    }
                                    
                                    echo '<div class="' . $cellClass . '" data-date="' . $date . '">';
                                    echo '<div class="font-medium">' . $day . '</div>';
                                    echo $statusIndicator;
                                    echo '</div>';
                                }

                                // Add empty cells for days after end of month to complete the grid
                                $remainingDays = (7 - (($firstDay + $daysInMonth) % 7)) % 7;
                                for ($i = 0; $i < $remainingDays; $i++) {
                                    echo '<div class="p-1"></div>';
                                }
                            @endphp
                        </div>
                        
                        <div class="flex justify-center space-x-8 mt-4">
                            <div class="flex items-center space-x-2">
                                <div class="h-3 w-3 rounded-full bg-green-500"></div>
                                <span class="text-xs text-gray-600">On Time</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="h-3 w-3 rounded-full bg-red-500"></div>
                                <span class="text-xs text-gray-600">Late</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="h-3 w-3 rounded-full bg-red-400"></div>
                                <span class="text-xs text-gray-600">Absent</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="h-3 w-3 rounded-full bg-gray-300"></div>
                                <span class="text-xs text-gray-600">No Record</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detailed Table View (Hidden by default) -->
                    <div id="detailedView" class="overflow-x-auto hidden">
                        <table class="table-auto w-full">
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-left">Date</th>
                                    <th class="px-3 py-2 text-left">Employee</th>
                                    <th class="px-3 py-2 text-left">Shift Time</th>
                                    <th class="px-3 py-2 text-left">Clock In</th>
                                    <th class="px-3 py-2 text-left">Clock Out</th>
                                    <th class="px-3 py-2 text-left">Duration</th>
                                    <th class="px-3 py-2 text-left">Attendance</th>
                                    <th class="px-3 py-2 text-left">Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(empty($filteredRecords))
                                    <tr>
                                        <td colspan="8" class="px-3 py-4 text-center text-gray-500">
                                            No records found for the selected period.
                                        </td>
                                    </tr>
                                @else
                                    @foreach($filteredRecords as $record)
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="px-3 py-3">
                                            {{ \Carbon\Carbon::parse($record['record_date'])->format('M d, Y') }}
                                            <div class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($record['record_date'])->format('l') }}
                                            </div>
                                        </td>
                                        
                                        <td class="px-3 py-3">
                                            {{ $record['employee_name'] }}
                                        </td>
                                        
                                        <td class="px-3 py-3">
                                            {{ \Carbon\Carbon::parse($record['shift_start'])->format('h:i A') }} - 
                                            {{ \Carbon\Carbon::parse($record['shift_end'])->format('h:i A') }}
                                            <div class="text-xs text-gray-500">
                                                {{ $record['shift_type'] ?? 'Regular' }}
                                            </div>
                                        </td>
                                        
                                        <td class="px-3 py-3">
                                            @if($record['clock_in_time'])
                                                {{ \Carbon\Carbon::parse($record['clock_in_time'])->format('h:i A') }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        
                                        <td class="px-3 py-3">
                                            @if($record['clock_out_time'])
                                                {{ \Carbon\Carbon::parse($record['clock_out_time'])->format('h:i A') }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        
                                        <td class="px-3 py-3">
                                            @if($record['total_hours'])
                                                @php
                                                    $totalHours = $record['total_hours'];
                                                    $hours = floor($totalHours);
                                                    $minutes = floor(($totalHours - $hours) * 60);
                                                    $seconds = floor((((($totalHours - $hours) * 60) - $minutes) * 60));

                                                    $formattedDuration = '';
                                                    if ($hours > 0) {
                                                        $formattedDuration .= $hours . 'h ';
                                                    }
                                                    if ($minutes > 0) {
                                                        $formattedDuration .= $minutes . 'm ';
                                                    }
                                                    if ($seconds > 0) {
                                                        $formattedDuration .= $seconds . 's';
                                                    }
                                                @endphp
                                                {{ $formattedDuration }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        
                                        <td class="px-3 py-3">
                                            <span class="px-2 py-1 text-xs rounded 
                                                @if($record['status'] == 'completed') bg-green-100 text-green-800
                                                @elseif($record['status'] == 'incomplete') bg-yellow-100 text-yellow-800
                                                @elseif($record['status'] == 'absent') bg-red-100 text-red-800
                                                @else bg-blue-100 text-blue-800
                                                @endif">
                                                {{ ucfirst($record['status']) }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-3 py-3">
                                            {{ !empty($record['location']) ? $record['location'] : 
                                                (!empty($record['shift_location']) ? $record['shift_location'] : 
                                                '<span class="text-gray-400">-</span>') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Success/Error Message Modal -->
<div id="popupMessage" class="popup-overlay hidden">
    <div class="popup-content">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <p id="popupMessageText"></p>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update time display every second
    function updateTime() {
        const now = new Date();
        const timeElement = document.getElementById('currentTime');
        const dateElement = document.getElementById('currentDate');
        
        if (timeElement) {
            timeElement.textContent = now.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
        }
        
        if (dateElement) {
            dateElement.textContent = now.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    }
    
    // Update time immediately and then every second
    updateTime();
    setInterval(updateTime, 1000);
    
    // Handle clock in/out forms with AJAX
    const clockInForm = document.getElementById('clockInForm');
    const clockOutForm = document.getElementById('clockOutForm');
    
    if (clockInForm) {
        clockInForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleClockAction(this, 'clock-in');
        });
    }
    
    if (clockOutForm) {
        clockOutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleClockAction(this, 'clock-out');
        });
    }
    
    function handleClockAction(form, action) {
        const formData = new FormData(form);
        const url = action === 'clock-in' ? 
            '{{ route("time-tracking.clock-in") }}' : 
            '{{ route("time-tracking.clock-out") }}';
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            showMessage(data.message, data.success ? 'success' : 'error');
            if (data.success) {
                // Reload page after successful clock in/out
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred. Please try again.', 'error');
        });
    }
    
    function showMessage(message, type) {
        const popup = document.getElementById('popupMessage');
        const popupText = document.getElementById('popupMessageText');
        
        if (popup && popupText) {
            popupText.textContent = message;
            popup.classList.remove('hidden');
            
            // Auto-hide after 3 seconds
            setTimeout(() => {
                popup.classList.add('hidden');
            }, 3000);
        }
    }
    
    // Handle filter changes
    const userFilter = document.getElementById('userFilter');
    const monthFilter = document.getElementById('monthFilter');
    
    if (userFilter) {
        userFilter.addEventListener('change', function() {
            updateFilters();
        });
    }
    
    if (monthFilter) {
        monthFilter.addEventListener('change', function() {
            updateFilters();
        });
    }
    
    function updateFilters() {
        const userId = userFilter ? userFilter.value : 'all';
        const month = monthFilter ? monthFilter.value : '{{ $currentMonth }}';
        
        const url = new URL(window.location);
        url.searchParams.set('user_id', userId);
        url.searchParams.set('month', month);
        
        window.location.href = url.toString();
    }
    
    // Handle view toggle
    const viewSummaryBtn = document.getElementById('viewSummary');
    const viewDetailedBtn = document.getElementById('viewDetailed');
    const summaryView = document.getElementById('summaryView');
    const detailedView = document.getElementById('detailedView');
    
    if (viewSummaryBtn && viewDetailedBtn && summaryView && detailedView) {
        viewSummaryBtn.addEventListener('click', function() {
            summaryView.classList.remove('hidden');
            detailedView.classList.add('hidden');
            viewSummaryBtn.classList.add('active', 'bg-blue-500', 'text-white');
            viewSummaryBtn.classList.remove('bg-gray-200', 'text-gray-700');
            viewDetailedBtn.classList.remove('active', 'bg-blue-500', 'text-white');
            viewDetailedBtn.classList.add('bg-gray-200', 'text-gray-700');
        });
        
        viewDetailedBtn.addEventListener('click', function() {
            detailedView.classList.remove('hidden');
            summaryView.classList.add('hidden');
            viewDetailedBtn.classList.add('active', 'bg-blue-500', 'text-white');
            viewDetailedBtn.classList.remove('bg-gray-200', 'text-gray-700');
            viewSummaryBtn.classList.remove('active', 'bg-blue-500', 'text-white');
            viewSummaryBtn.classList.add('bg-gray-200', 'text-gray-700');
        });
    }
});

function closePopup() {
    const popup = document.getElementById('popupMessage');
    if (popup) {
        popup.classList.add('hidden');
    }
}
</script>
@endpush