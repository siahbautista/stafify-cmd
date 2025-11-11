@extends('layouts.app')

@section('title', 'Shift Adjustments')
@section('description', 'Make adjustments to employee shifts and handle schedule changes.')

@section('content')
<div class="px-0">
    <!-- Header -->
    <div class="flex items-center justify-between gap-2 mb-5">
        <h1 class="text-xl font-semibold">Overtime Management</h1>
        
        @if($accessLevel == 2 || $accessLevel == 1)
            <!-- Employee actions -->
            <button type="button" id="requestOvertimeBtn" class="primary-button flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Request Overtime
            </button>
        @endif
        
        @if($accessLevel == 2)
            <!-- Manager view selector -->
            <div class="view-toggle-buttons">
                <div class="btn-group flex gap-2" role="group" aria-label="View toggle">
                    <button type="button" id="requests-view-btn" class="btn btn-primary active flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            <path d="M9 14h6"></path>
                            <path d="M9 18h6"></path>
                            <path d="M9 10h6"></path>
                        </svg>
                        Requests
                        @if($pendingCount > 0)
                            <span class="badge badge-warning">{{ $pendingCount }}</span>
                        @endif
                    </button>
                    <button type="button" id="reports-view-btn" class="btn btn-outline-primary flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="3" y1="9" x2="21" y2="9"></line>
                            <line x1="9" y1="21" x2="9" y2="9"></line>
                        </svg>
                        Reports
                    </button>
                </div>
            </div>
        @endif
    </div>

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
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-shadow-box p-4">
        <!-- Tab content -->
        <div id="tab-content">
            <!-- Overtime Requests View -->
            <div class="block" id="requests-content" role="tabpanel">
                @if($accessLevel == 2)
                    <!-- Manager View -->
                    <!-- Filter Controls -->
                    <div class="mb-4 flex flex-wrap gap-3 items-center">
                        <div class="flex-1">
                            <input type="text" id="requestSearch" class="form-control" placeholder="Search by employee name/department">
                        </div>
                        <div>
                            <select id="statusFilter" class="form-select">
                                <option value="all">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div>
                            <select id="dateFilter" class="form-select">
                                <option value="all">All Dates</option>
                                <option value="this-week">This Week</option>
                                <option value="last-week">Last Week</option>
                                <option value="this-month">This Month</option>
                                <option value="last-month">Last Month</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Requests Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto" id="overtimeRequestsTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Employee</th>
                                    <th class="px-4 py-2 text-left">Department</th>
                                    <th class="px-4 py-2 text-left">Date</th>
                                    <th class="px-4 py-2 text-left">Time</th>
                                    <th class="px-4 py-2 text-left">Duration</th>
                                    <th class="px-4 py-2 text-left">Reason</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">Requested</th>
                                    <th class="px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($overtimeRequests->isEmpty())
                                    <tr>
                                        <td colspan="9" class="px-4 py-4 text-center">No overtime requests found.</td>
                                    </tr>
                                @else
                                    @foreach($overtimeRequests as $request)
                                        <tr class="border-b hover:bg-gray-50 {{ $request['status'] === 'pending' ? 'bg-yellow-50' : '' }}">
                                            <td class="px-4 py-3">{{ $request['employee_name'] }}</td>
                                            <td class="px-4 py-3">{{ $request['department'] }}</td>
                                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($request['ot_date'])->format('M d, Y') }}</td>
                                            <td class="px-4 py-3">
                                                {{ \Carbon\Carbon::parse($request['start_time'])->format('h:i A') }} - 
                                                {{ \Carbon\Carbon::parse($request['end_time'])->format('h:i A') }}
                                            </td>
                                            <td class="px-4 py-3">{{ number_format($request['duration'], 2) }} hrs</td>
                                            <td class="px-4 py-3">
                                                <span class="truncate block max-w-xs" title="{{ $request['reason'] }}">
                                                    {{ Str::limit($request['reason'], 50) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                                    @if($request['status'] === 'approved') bg-green-100 text-green-800
                                                    @elseif($request['status'] === 'rejected') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800
                                                    @endif">
                                                    {{ ucfirst($request['status']) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($request['requested_date'])->format('M d, Y') }}</td>
                                            <td class="px-4 py-3">
                                                @if($request['status'] === 'pending')
                                                    <button type="button" class="text-blue-600 hover:text-blue-800 mr-2 review-btn"
                                                            data-ot-id="{{ $request['ot_id'] }}"
                                                            data-employee="{{ $request['employee_name'] }}"
                                                            data-date="{{ $request['ot_date'] }}"
                                                            data-start="{{ $request['start_time'] }}"
                                                            data-end="{{ $request['end_time'] }}"
                                                            data-duration="{{ $request['duration'] }}"
                                                            data-reason="{{ $request['reason'] }}">
                                                        Review
                                                    </button>
                                                @else
                                                    <button type="button" class="text-gray-600 hover:text-gray-800 mr-2 details-btn"
                                                            data-ot-id="{{ $request['ot_id'] }}"
                                                            data-employee="{{ $request['employee_name'] }}"
                                                            data-date="{{ $request['ot_date'] }}"
                                                            data-start="{{ $request['start_time'] }}"
                                                            data-end="{{ $request['end_time'] }}"
                                                            data-duration="{{ $request['duration'] }}"
                                                            data-reason="{{ $request['reason'] }}"
                                                            data-status="{{ $request['status'] }}"
                                                            data-approver="{{ $request['approver_name'] ?? 'N/A' }}"
                                                            data-notes="{{ $request['approval_notes'] ?? '' }}">
                                                        Details
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Employee View -->
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto" id="myOvertimeTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Date</th>
                                    <th class="px-4 py-2 text-left">Time</th>
                                    <th class="px-4 py-2 text-left">Duration</th>
                                    <th class="px-4 py-2 text-left">Reason</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">Requested</th>
                                    <th class="px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($overtimeRequests->isEmpty())
                                    <tr>
                                        <td colspan="7" class="px-4 py-4 text-center">No overtime requests found.</td>
                                    </tr>
                                @else
                                    @foreach($overtimeRequests as $request)
                                        <tr class="border-b hover:bg-gray-50 {{ $request['status'] === 'pending' ? 'bg-yellow-50' : '' }}">
                                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($request['ot_date'])->format('M d, Y') }}</td>
                                            <td class="px-4 py-3">
                                                {{ \Carbon\Carbon::parse($request['start_time'])->format('h:i A') }} - 
                                                {{ \Carbon\Carbon::parse($request['end_time'])->format('h:i A') }}
                                            </td>
                                            <td class="px-4 py-3">{{ number_format($request['duration'], 2) }} hrs</td>
                                            <td class="px-4 py-3">
                                                <span class="truncate block max-w-xs" title="{{ $request['reason'] }}">
                                                    {{ Str::limit($request['reason'], 50) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                                    @if($request['status'] === 'approved') bg-green-100 text-green-800
                                                    @elseif($request['status'] === 'rejected') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800
                                                    @endif">
                                                    {{ ucfirst($request['status']) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($request['requested_date'])->format('M d, Y') }}</td>
                                            <td class="px-4 py-3">
                                                <button type="button" class="text-blue-600 hover:text-blue-800 details-btn"
                                                        data-ot-id="{{ $request['ot_id'] }}"
                                                        data-date="{{ $request['ot_date'] }}"
                                                        data-start="{{ $request['start_time'] }}"
                                                        data-end="{{ $request['end_time'] }}"
                                                        data-duration="{{ $request['duration'] }}"
                                                        data-reason="{{ $request['reason'] }}"
                                                        data-status="{{ $request['status'] }}"
                                                        data-approver="{{ $request['approver_name'] ?? 'N/A' }}"
                                                        data-notes="{{ $request['approval_notes'] ?? '' }}">
                                                    View Details
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            
            <!-- Overtime Reports View (Manager Only) -->
            @if($accessLevel == 2)
            <div class="hidden" id="reports-content" role="tabpanel">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="rounded-shadow-box p-4 bg-blue-50 border-l-4 border-blue-500">
                        <h3 class="text-sm uppercase text-gray-600 mb-1">Total Requests</h3>
                        <p class="text-2xl font-bold">{{ $overtimeStats['overall']['total_requests'] }}</p>
                    </div>
                    <div class="rounded-shadow-box p-4 bg-green-50 border-l-4 border-green-500">
                        <h3 class="text-sm uppercase text-gray-600 mb-1">Approved</h3>
                        <p class="text-2xl font-bold">{{ $overtimeStats['overall']['approved_count'] }}</p>
                    </div>
                    <div class="rounded-shadow-box p-4 bg-red-50 border-l-4 border-red-500">
                        <h3 class="text-sm uppercase text-gray-600 mb-1">Rejected</h3>
                        <p class="text-2xl font-bold">{{ $overtimeStats['overall']['rejected_count'] }}</p>
                    </div>
                    <div class="rounded-shadow-box p-4 bg-purple-50 border-l-4 border-purple-500">
                        <h3 class="text-sm uppercase text-gray-600 mb-1">Total Hours</h3>
                        <p class="text-2xl font-bold">{{ number_format($overtimeStats['overall']['total_approved_hours'], 2) }}</p>
                    </div>
                </div>
                
                <!-- Date Range Selector -->
                <div class="rounded-shadow-box p-4 mb-6">
                    <h3 class="text-lg font-semibold mb-3">Report Period</h3>
                    <div class="flex flex-wrap gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" id="reportStartDate" class="form-control" 
                                value="{{ $overtimeStats['date_range']['start'] }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" id="reportEndDate" class="form-control" 
                                value="{{ $overtimeStats['date_range']['end'] }}">
                        </div>
                        <div>
                            <button id="updateReportBtn" class="primary-button">Update Report</button>
                        </div>
                    </div>
                </div>
                
                <!-- Department Statistics -->
                <div class="rounded-shadow-box p-4 mb-6">
                    <h3 class="text-lg font-semibold mb-3">Overtime by Department</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Department</th>
                                    <th class="px-4 py-2 text-left">Total Hours</th>
                                    <th class="px-4 py-2 text-left">Employees</th>
                                    <th class="px-4 py-2 text-left">Avg Hours/Employee</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($overtimeStats['departments']->isEmpty())
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center">No data available for this period.</td>
                                    </tr>
                                @else
                                    @foreach($overtimeStats['departments'] as $dept)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-4 py-3">{{ $dept['department'] }}</td>
                                            <td class="px-4 py-3">{{ number_format($dept['total_hours'], 2) }} hrs</td>
                                            <td class="px-4 py-3">{{ $dept['employee_count'] }}</td>
                                            <td class="px-4 py-3">
                                                {{ number_format($dept['total_hours'] / $dept['employee_count'], 2) }} hrs
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Top Employees -->
                <div class="rounded-shadow-box p-4">
                    <h3 class="text-lg font-semibold mb-3">Top Employees by Overtime</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Employee</th>
                                    <th class="px-4 py-2 text-left">Department</th>
                                    <th class="px-4 py-2 text-left">Total Hours</th>
                                    <th class="px-4 py-2 text-left">Requests</th>
                                    <th class="px-4 py-2 text-left">Avg Hours/Request</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($overtimeStats['employees']->isEmpty())
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center">No data available for this period.</td>
                                    </tr>
                                @else
                                    @foreach($overtimeStats['employees'] as $emp)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-4 py-3">{{ $emp['full_name'] }}</td>
                                            <td class="px-4 py-3">{{ $emp['user_dept'] }}</td>
                                            <td class="px-4 py-3">{{ number_format($emp['total_hours'], 2) }} hrs</td>
                                            <td class="px-4 py-3">{{ $emp['request_count'] }}</td>
                                            <td class="px-4 py-3">
                                                {{ number_format($emp['total_hours'] / $emp['request_count'], 2) }} hrs
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Request Overtime Modal -->
<div id="requestOvertimeModal" class="modal">
    <div class="modal-content max-w-lg">
        <div class="modal-header">
            <h2>Request Overtime</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <form id="overtimeRequestForm" method="post" action="{{ route('shift-adjustments.request-overtime') }}">
                @csrf
                
                @if($userShifts->isNotEmpty())
                <div class="form-group mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Related Shift <span class="text-red-500">*</span></label>
                    <select name="shift_id" class="form-select" required>
                        <option value="">Select a shift</option>
                        @foreach($userShifts as $shift)
                        <option value="{{ $shift->shift_id }}">
                            {{ \Carbon\Carbon::parse($shift->shift_date)->format('M d, Y') }} 
                            ({{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }} - 
                            {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }})
                        </option>
                        @endforeach
                    </select>
                    <div class="text-xs text-gray-500 mt-1">You must select a related shift to request overtime</div>
                </div>
                @else
                <input type="hidden" name="shift_id" value="">
                @endif
                
                <div class="form-group mb-4">
                    <label for="ot_date" class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                    <input type="date" id="ot_date" name="ot_date" class="form-control bg-gray-100" required readonly
                        min="{{ \Carbon\Carbon::now()->subDays(30)->format('Y-m-d') }}" 
                        max="{{ \Carbon\Carbon::now()->addDays(30)->format('Y-m-d') }}" 
                        value="">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="form-group">
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time <span class="text-red-500">*</span></label>
                        <input type="time" id="start_time" name="start_time" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time <span class="text-red-500">*</span></label>
                        <input type="time" id="end_time" name="end_time" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group mb-4">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason <span class="text-red-500">*</span></label>
                    <textarea id="reason" name="reason" class="form-control" rows="3" required
                            placeholder="Please explain why you need overtime"></textarea>
                </div>
                
                <div class="mt-4 text-right">
                    <button type="button" class="secondary-button mr-2 close-modal">Cancel</button>
                    <button type="submit" class="primary-button">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Review Overtime Modal (Manager) -->
@if($accessLevel == 2)
<div id="reviewOvertimeModal" class="modal">
    <div class="modal-content max-w-lg">
        <div class="modal-header">
            <h2>Review Overtime Request</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                <div class="mb-2">
                    <span class="font-medium">Employee:</span>
                    <span id="reviewEmployeeName"></span>
                </div>
                <div class="mb-2">
                    <span class="font-medium">Date:</span>
                    <span id="reviewDate"></span>
                </div>
                <div class="mb-2">
                    <span class="font-medium">Time:</span>
                    <span id="reviewTime"></span>
                </div>
                <div class="mb-2">
                    <span class="font-medium">Duration:</span>
                    <span id="reviewDuration"></span>
                </div>
                <div class="mb-2">
                    <span class="font-medium">Reason:</span>
                    <div id="reviewReason" class="mt-1 text-sm text-gray-600"></div>
                </div>
            </div>
            
            <form id="reviewForm" method="post" action="{{ route('shift-adjustments.update-status') }}">
                @csrf
                <input type="hidden" id="reviewOtId" name="ot_id" value="">
                
                <div class="form-group mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Decision <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <div class="flex items-center">
                            <input type="radio" id="statusApprove" name="status" value="approved" class="mr-2" required>
                            <label for="statusApprove" class="text-green-600 font-medium">Approve</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="statusReject" name="status" value="rejected" class="mr-2" required>
                            <label for="statusReject" class="text-red-600 font-medium">Reject</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-4">
                    <label for="approval_notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="approval_notes" name="approval_notes" class="form-control" rows="3"
                            placeholder="Optional: Add any notes regarding your decision"></textarea>
                </div>
                
                <div class="mt-4 text-right">
                    <button type="button" class="secondary-button mr-2 close-modal">Cancel</button>
                    <button type="submit" class="primary-button">Submit Decision</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- View Details Modal -->
<div id="viewDetailsModal" class="modal">
    <div class="modal-content max-w-lg">
        <div class="modal-header">
            <h2>Overtime Request Details</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="mb-2 p-3 rounded-lg text-center status-banner" id="detailsStatusBanner">
                <!-- Status banner will be filled with JavaScript -->
            </div>
            
            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                @if($accessLevel == 2)
                <div class="mb-2">
                    <span class="font-medium">Employee:</span>
                    <span id="detailsEmployeeName"></span>
                </div>
                @endif
                <div class="mb-2">
                    <span class="font-medium">Date:</span>
                    <span id="detailsDate"></span>
                </div>
                <div class="mb-2">
                    <span class="font-medium">Time:</span>
                    <span id="detailsTime"></span>
                </div>
                <div class="mb-2">
                    <span class="font-medium">Duration:</span>
                    <span id="detailsDuration"></span>
                </div>
                <div class="mb-2">
                    <span class="font-medium">Reason:</span>
                    <div id="detailsReason" class="mt-1 text-sm text-gray-600"></div>
                </div>
                <div class="mb-2 decision-info">
                    <span class="font-medium">Decision by:</span>
                    <span id="detailsApprover"></span>
                </div>
                <div class="mb-2 decision-info">
                    <span class="font-medium">Notes:</span>
                    <div id="detailsNotes" class="mt-1 text-sm text-gray-600"></div>
                </div>
                </div>
            
            <div class="mt-4 text-right">
                <button type="button" class="secondary-button close-modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const requestsViewBtn = document.getElementById('requests-view-btn');
    const reportsViewBtn = document.getElementById('reports-view-btn');
    const requestsContent = document.getElementById('requests-content');
    const reportsContent = document.getElementById('reports-content');
    
    if (requestsViewBtn && reportsViewBtn) {
        requestsViewBtn.addEventListener('click', function() {
            requestsViewBtn.classList.add('active');
            requestsViewBtn.classList.remove('btn-outline-primary');
            requestsViewBtn.classList.add('btn-primary');
            reportsViewBtn.classList.remove('active');
            reportsViewBtn.classList.add('btn-outline-primary');
            reportsViewBtn.classList.remove('btn-primary');
            
            requestsContent.classList.remove('hidden');
            requestsContent.classList.add('block');
            reportsContent.classList.remove('block');
            reportsContent.classList.add('hidden');
        });
        
        reportsViewBtn.addEventListener('click', function() {
            reportsViewBtn.classList.add('active');
            reportsViewBtn.classList.remove('btn-outline-primary');
            reportsViewBtn.classList.add('btn-primary');
            requestsViewBtn.classList.remove('active');
            requestsViewBtn.classList.add('btn-outline-primary');
            requestsViewBtn.classList.remove('btn-primary');
            
            reportsContent.classList.remove('hidden');
            reportsContent.classList.add('block');
            requestsContent.classList.remove('block');
            requestsContent.classList.add('hidden');
        });
    }
    
    // Modal functionality
    const modals = document.querySelectorAll('.modal');
    const modalCloseButtons = document.querySelectorAll('.modal .close, .modal .close-modal');
    
    // Open modal function
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
        }
    }
    
    // Close modal function
    function closeModal() {
        modals.forEach(modal => {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        });
    }
    
    // Add close functionality to close buttons
    modalCloseButtons.forEach(button => {
        button.addEventListener('click', closeModal);
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        modals.forEach(modal => {
            if (event.target === modal) {
                closeModal();
            }
        });
    });

    // Get the shift select dropdown and date input
    const shiftSelect = document.querySelector('select[name="shift_id"]');
    const otDateInput = document.getElementById('ot_date');
    const overtimeForm = document.getElementById('overtimeRequestForm');
    
    // Add event listener to the shift select dropdown
    if (shiftSelect && otDateInput) {
        shiftSelect.addEventListener('change', function() {
            // If a shift is selected
            if (shiftSelect.value) {
                // Get the selected option
                const selectedOption = shiftSelect.options[shiftSelect.selectedIndex];
                
                // Extract the date from the option text
                // The format is "MMM dd, YYYY (h:mm AM/PM - h:mm AM/PM)"
                const dateText = selectedOption.text.split('(')[0].trim();
                
                // Parse the date string manually to avoid timezone issues
                const months = {
                    'Jan': 0, 'Feb': 1, 'Mar': 2, 'Apr': 3, 'May': 4, 'Jun': 5,
                    'Jul': 6, 'Aug': 7, 'Sep': 8, 'Oct': 9, 'Nov': 10, 'Dec': 11
                };
                
                // Extract month, day, and year
                const parts = dateText.split(' ');
                const month = months[parts[0]];
                const day = parseInt(parts[1].replace(',', ''));
                const year = parseInt(parts[2]);
                
                // Create date object (use UTC to avoid timezone issues)
                const date = new Date(Date.UTC(year, month, day));
                
                // Format as YYYY-MM-DD
                const formattedDate = date.toISOString().split('T')[0];
                
                // Set the date input value
                otDateInput.value = formattedDate;
            } else {
                // If no shift is selected, clear the date field
                otDateInput.value = '';
            }
        });
    }
    
    // Form validation
    if (overtimeForm) {
        overtimeForm.addEventListener('submit', function(e) {
            if (!shiftSelect.value) {
                e.preventDefault();
                alert('Please select a related shift before submitting');
                return false;
            }
        });
    }

    // Request Overtime Button
    const requestOvertimeBtn = document.getElementById('requestOvertimeBtn');
    if (requestOvertimeBtn) {
        requestOvertimeBtn.addEventListener('click', function() {
            openModal('requestOvertimeModal');
        });
    }
    
    // Calculate duration in overtime request form
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    
    if (startTimeInput && endTimeInput) {
        function updateDuration() {
            const startTime = startTimeInput.value;
            const endTime = endTimeInput.value;
            
            if (startTime && endTime) {
                const start = new Date(`2000-01-01T${startTime}`);
                const end = new Date(`2000-01-01T${endTime}`);
                
                if (end <= start) {
                    // Handle when end time is earlier than start time (next day)
                    end.setDate(end.getDate() + 1);
                }
                
                const diffMs = end - start;
                const diffHrs = diffMs / (1000 * 60 * 60);
                
                // You could display this somewhere if needed
                console.log(`Duration: ${diffHrs.toFixed(2)} hours`);
            }
        }
        
        startTimeInput.addEventListener('change', updateDuration);
        endTimeInput.addEventListener('change', updateDuration);
    }
    
    // Review buttons for managers
    const reviewButtons = document.querySelectorAll('.review-btn');
    reviewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const otId = button.getAttribute('data-ot-id');
            const employee = button.getAttribute('data-employee');
            const date = button.getAttribute('data-date');
            const start = button.getAttribute('data-start');
            const end = button.getAttribute('data-end');
            const duration = button.getAttribute('data-duration');
            const reason = button.getAttribute('data-reason');
            
            // Fill review modal
            document.getElementById('reviewOtId').value = otId;
            document.getElementById('reviewEmployeeName').textContent = employee;
            document.getElementById('reviewDate').textContent = formatDate(date);
            document.getElementById('reviewTime').textContent = `${formatTime(start)} - ${formatTime(end)}`;
            document.getElementById('reviewDuration').textContent = `${duration} hours`;
            document.getElementById('reviewReason').textContent = reason;
            
            // Clear any previous selection
            document.getElementById('statusApprove').checked = false;
            document.getElementById('statusReject').checked = false;
            document.getElementById('approval_notes').value = '';
            
            openModal('reviewOvertimeModal');
        });
    });
    
    // Details buttons
    const detailsButtons = document.querySelectorAll('.details-btn');
    detailsButtons.forEach(button => {
        button.addEventListener('click', function() {
            const otId = button.getAttribute('data-ot-id');
            const employee = button.getAttribute('data-employee'); // May be undefined for employees
            const date = button.getAttribute('data-date');
            const start = button.getAttribute('data-start');
            const end = button.getAttribute('data-end');
            const duration = button.getAttribute('data-duration');
            const reason = button.getAttribute('data-reason');
            const status = button.getAttribute('data-status');
            const approver = button.getAttribute('data-approver');
            const notes = button.getAttribute('data-notes');
            
            // Fill details modal
            if (document.getElementById('detailsEmployeeName')) {
                document.getElementById('detailsEmployeeName').textContent = employee;
            }
            document.getElementById('detailsDate').textContent = formatDate(date);
            document.getElementById('detailsTime').textContent = `${formatTime(start)} - ${formatTime(end)}`;
            document.getElementById('detailsDuration').textContent = `${duration} hours`;
            document.getElementById('detailsReason').textContent = reason;
            
            // Set status banner
            const statusBanner = document.getElementById('detailsStatusBanner');
            statusBanner.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            statusBanner.className = 'mb-2 p-3 rounded-lg text-center status-banner ' + status;
            
            // Show decision info if approved or rejected
            const decisionInfoItems = document.querySelectorAll('.decision-info');
            if (status === 'approved' || status === 'rejected') {
                decisionInfoItems.forEach(item => {
                    item.style.display = 'block';
                });
                document.getElementById('detailsApprover').textContent = approver;
                document.getElementById('detailsNotes').textContent = notes ? notes : 'No notes provided';
            } else {
                decisionInfoItems.forEach(item => {
                    item.style.display = 'none';
                });
            }
            
            openModal('viewDetailsModal');
        });
    });
    
    // Format date function
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
    }
    
    // Format time function
    function formatTime(timeStr) {
        const [hours, minutes] = timeStr.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const formattedHour = hour % 12 || 12;
        return `${formattedHour}:${minutes} ${ampm}`;
    }
    
    // Filter functionality for managers
    const requestSearch = document.getElementById('requestSearch');
    const statusFilter = document.getElementById('statusFilter');
    const dateFilter = document.getElementById('dateFilter');
    
    if (requestSearch && statusFilter && dateFilter) {
        function filterTable() {
            const searchValue = requestSearch.value.toLowerCase();
            const statusValue = statusFilter.value;
            const dateValue = dateFilter.value;
            
            const rows = document.querySelectorAll('#overtimeRequestsTable tbody tr');
            rows.forEach(row => {
                const employeeCol = row.querySelector('td:nth-child(1)');
                const departmentCol = row.querySelector('td:nth-child(2)');
                const dateCol = row.querySelector('td:nth-child(3)');
                const statusCol = row.querySelector('td:nth-child(7) span');
                
                if (!employeeCol || !departmentCol || !dateCol || !statusCol) return;
                
                const employee = employeeCol.textContent.toLowerCase();
                const department = departmentCol.textContent.toLowerCase();
                const requestDate = new Date(dateCol.textContent);
                const status = statusCol.textContent.toLowerCase();
                
                // Search filter
                const matchesSearch = searchValue === '' || 
                                    employee.includes(searchValue) || 
                                    department.includes(searchValue);
                
                // Status filter
                const matchesStatus = statusValue === 'all' || status.includes(statusValue);

                // Date filter
                let matchesDate = true;
                const today = new Date();
                const requestDateObj = new Date(dateCol.textContent);
                
                if (dateValue === 'this-week') {
                    // Get first day of current week (Sunday)
                    const firstDay = new Date(today);
                    const day = today.getDay(); // 0 = Sunday
                    firstDay.setDate(today.getDate() - day);
                    
                    matchesDate = requestDateObj >= firstDay && requestDateObj <= today;
                } else if (dateValue === 'last-week') {
                    // Get first day of last week
                    const firstDayLastWeek = new Date(today);
                    const day = today.getDay();
                    firstDayLastWeek.setDate(today.getDate() - day - 7);
                    
                    // Get last day of last week
                    const lastDayLastWeek = new Date(firstDayLastWeek);
                    lastDayLastWeek.setDate(firstDayLastWeek.getDate() + 6);
                    
                    matchesDate = requestDateObj >= firstDayLastWeek && requestDateObj <= lastDayLastWeek;
                } else if (dateValue === 'this-month') {
                    // First day of current month
                    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                    
                    matchesDate = requestDateObj >= firstDay && requestDateObj <= today;
                } else if (dateValue === 'last-month') {
                    // First day of last month
                    const firstDayLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    
                    // Last day of last month
                    const lastDayLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                    
                    matchesDate = requestDateObj >= firstDayLastMonth && requestDateObj <= lastDayLastMonth;
                }
                
                // Apply filters
                if (matchesSearch && matchesStatus && matchesDate) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        // Add event listeners for filters
        requestSearch.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);
        dateFilter.addEventListener('change', filterTable);
    }
    
    // Reports date range functionality
    const reportStartDate = document.getElementById('reportStartDate');
    const reportEndDate = document.getElementById('reportEndDate');
    const updateReportBtn = document.getElementById('updateReportBtn');
    
    if (updateReportBtn) {
        updateReportBtn.addEventListener('click', function() {
            if (reportStartDate && reportEndDate) {
                // Redirect with date parameters
                const startDate = reportStartDate.value;
                const endDate = reportEndDate.value;
                
                if (startDate && endDate) {
                    window.location.href = `{{ route('shift-adjustments') }}?tab=reports&start_date=${startDate}&end_date=${endDate}`;
                } else {
                    alert('Please select both start and end dates.');
                }
            }
        });
    }
    
    // Set active tab on page load from URL parameters
    function setActiveTabFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        
        if (tab === 'reports' && reportsViewBtn) {
            reportsViewBtn.click();
        }
    }
    
    setActiveTabFromUrl();
});
</script>
@endpush

@push('styles')
<style>
/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modal.show {
    opacity: 1;
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(-50px);
    transition: transform 0.3s ease;
}

.modal.show .modal-content {
    transform: translateY(0);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    line-height: 1;
}

.close:hover,
.close:focus {
    color: #000;
}

.modal-body {
    padding: 20px;
}

/* Status banner styles */
.status-banner.pending {
    background-color: #fef3c7;
    color: #92400e;
}

.status-banner.approved {
    background-color: #d1fae5;
    color: #065f46;
}

.status-banner.rejected {
    background-color: #fee2e2;
    color: #991b1b;
}

/* Button styles */
.primary-button {
    background-color: #3b82f6;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    border: none;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
}

.primary-button:hover {
    background-color: #2563eb;
}

.secondary-button {
    background-color: #6b7280;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    border: none;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
}

.secondary-button:hover {
    background-color: #4b5563;
}

/* Form styles */
.form-control {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
}

.form-select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    background-color: white;
}

.form-group {
    margin-bottom: 1rem;
}

/* Alert styles */
.alert {
    padding: 1rem;
    border-radius: 0.375rem;
    margin-bottom: 1rem;
}

.alert-success {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.alert-error {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

/* Badge styles */
.badge {
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-warning {
    background-color: #fbbf24;
    color: #92400e;
}

/* Button group styles */
.btn-group {
    display: flex;
}

.btn {
    padding: 0.5rem 1rem;
    border: 1px solid #d1d5db;
    background-color: white;
    color: #374151;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary {
    background-color: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.btn-outline-primary {
    background-color: white;
    color: #3b82f6;
    border-color: #3b82f6;
}

.btn:hover {
    opacity: 0.9;
}

.btn.active {
    background-color: #3b82f6;
    color: white;
}

/* Table styles */
.table-auto {
    width: 100%;
    border-collapse: collapse;
}

.table-auto th,
.table-auto td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.table-auto th {
    background-color: #f9fafb;
    font-weight: 600;
    color: #374151;
}

/* Card styles */
.rounded-shadow-box {
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Responsive utilities */
@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        margin: 10% auto;
    }
    
    .grid {
        grid-template-columns: 1fr;
    }
    
    .flex-wrap {
        flex-wrap: wrap;
    }
}
</style>
@endpush