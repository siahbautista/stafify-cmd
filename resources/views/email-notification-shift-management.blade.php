@extends('layouts.app')

@section('title', 'Email Notifications - Shift Management')
@section('description', 'Configure email notifications for shift management activities.')

@section('content')
<div class="px-0">
    <!-- Email Notifications - Shift Management Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Email Notifications - Shift Management</h2>
            <p class="text-gray-600 mb-6">Manage email notifications for shift-related activities.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Shift Assignments</h3>
                    <p class="text-sm text-gray-600">Notifications for new shift assignments</p>
                </div>
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Schedule Changes</h3>
                    <p class="text-sm text-gray-600">Alerts for schedule modifications</p>
                </div>
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Shift Reminders</h3>
                    <p class="text-sm text-gray-600">Upcoming shift notifications</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize email notifications functionality
    console.log('Email Notifications - Shift Management page loaded successfully');
});
</script>
@endpush
