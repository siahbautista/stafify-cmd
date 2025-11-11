@extends('layouts.app')

@section('title', 'Email Notifications - Talent Acquisition')
@section('description', 'Configure email notifications for talent acquisition activities.')

@section('content')
<div class="px-0">
    <!-- Email Notifications - Talent Acquisition Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Email Notifications - Talent Acquisition</h2>
            <p class="text-gray-600 mb-6">Manage email notifications for recruitment activities.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">New Applications</h3>
                    <p class="text-sm text-gray-600">Alerts for new job applications</p>
                </div>
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Interview Reminders</h3>
                    <p class="text-sm text-gray-600">Schedule interview notifications</p>
                </div>
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Status Updates</h3>
                    <p class="text-sm text-gray-600">Application status changes</p>
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
    console.log('Email Notifications - Talent Acquisition page loaded successfully');
});
</script>
@endpush
