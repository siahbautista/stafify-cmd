@extends('layouts.app')

@section('title', 'Email Notifications - Time Tracker')
@section('description', 'Configure email notifications for time tracking activities.')

@section('content')
<div class="px-0">
    <!-- Email Notifications - Time Tracker Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Email Notifications - Time Tracker</h2>
            <p class="text-gray-600 mb-6">Manage email notifications for time tracking events.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Clock In/Out Alerts</h3>
                    <p class="text-sm text-gray-600">Notifications for time tracking events</p>
                </div>
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Overtime Warnings</h3>
                    <p class="text-sm text-gray-600">Alerts for overtime hours</p>
                </div>
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Weekly Reports</h3>
                    <p class="text-sm text-gray-600">Automated time tracking summaries</p>
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
    console.log('Email Notifications - Time Tracker page loaded successfully');
});
</script>
@endpush
