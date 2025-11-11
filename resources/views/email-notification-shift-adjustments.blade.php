@extends('layouts.app')

@section('title', 'Email Notifications - Shift Adjustments')
@section('description', 'Configure email notifications for shift adjustment activities.')

@section('content')
<div class="px-0">
    <!-- Email Notifications - Shift Adjustments Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Email Notifications - Shift Adjustments</h2>
            <p class="text-gray-600 mb-6">Manage email notifications for shift adjustment requests.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Adjustment Requests</h3>
                    <p class="text-sm text-gray-600">Notifications for shift change requests</p>
                </div>
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Approval Status</h3>
                    <p class="text-sm text-gray-600">Updates on request approvals</p>
                </div>
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Manager Alerts</h3>
                    <p class="text-sm text-gray-600">Notifications for managers</p>
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
    console.log('Email Notifications - Shift Adjustments page loaded successfully');
});
</script>
@endpush
