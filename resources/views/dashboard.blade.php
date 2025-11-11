@extends('layouts.app')

@section('title', 'Dashboard')
@section('description', 'HRIS Dashboard - Analytics and Overview')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="px-0">
    <div class="flex flex-col gap-5 rounded-shadow-box animate__animated animate__fadeInUp w-full">


        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Employees Card -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Employees</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalEmployees }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-4">
                        <i class="fas fa-users text-blue-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Active Employees Card -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Active Employees</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $activeEmployees }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-4">
                        <i class="fas fa-user-check text-green-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Recent Hires Card -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Recent Hires (30 days)</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $recentHires }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-4">
                        <i class="fas fa-user-plus text-purple-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Time Tracking Card -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Time Records</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $timeTrackingCount }}</p>
                    </div>
                    <div class="bg-orange-100 rounded-full p-4">
                        <i class="fas fa-clock text-orange-500 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 1 - 3 Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Employees by Department Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-building text-blue-500 mr-2"></i>Employees by Department
                </h3>
                <canvas id="departmentChart" height="300"></canvas>
            </div>

            <!-- Employees by Engagement Status Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-briefcase text-green-500 mr-2"></i>Engagement Status
                </h3>
                <canvas id="engagementChart" height="300"></canvas>
            </div>

            <!-- Employees by Branch Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>Employees by Branch
                </h3>
                <canvas id="branchChart" height="300"></canvas>
            </div>
        </div>

        <!-- Charts Row 2 - 3 Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Employees by User Type Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-user-tag text-purple-500 mr-2"></i>Employees by Type
                </h3>
                <canvas id="userTypeChart" height="300"></canvas>
            </div>

            <!-- Monthly Hiring Trend Chart -->
            <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-chart-line text-indigo-500 mr-2"></i>Monthly Hiring Trend (Last 12 Months)
                </h3>
                <canvas id="monthlyHiringChart" height="100"></canvas>
            </div>
        </div>

        <!-- Charts Row 3 - Attendance Analytics (3 Columns) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Absent Employees Chart -->
            <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-user-times text-red-500 mr-2"></i>Absent Employees (Last 30 Days)
                </h3>
                <canvas id="absentChart" height="100"></canvas>
            </div>

            <!-- Late Employees Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-clock text-yellow-500 mr-2"></i>Late Arrivals (Last 30 Days)
                </h3>
                <canvas id="lateChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js configuration
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                enabled: true,
            }
        }
    };

    // Employees by Department Chart (Bar Chart)
    const deptCtx = document.getElementById('departmentChart');
    if (deptCtx) {
        new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: @json($chartData['departments']['labels']),
                datasets: [{
                    label: 'Employees',
                    data: @json($chartData['departments']['data']),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Engagement Status Chart (Doughnut Chart)
    const engagementCtx = document.getElementById('engagementChart');
    if (engagementCtx) {
        new Chart(engagementCtx, {
            type: 'doughnut',
            data: {
                labels: @json($chartData['engagement']['labels']),
                datasets: [{
                    label: 'Employees',
                    data: @json($chartData['engagement']['data']),
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(249, 115, 22, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                    ],
                    borderColor: [
                        'rgba(34, 197, 94, 1)',
                        'rgba(249, 115, 22, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(168, 85, 247, 1)',
                        'rgba(236, 72, 153, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: chartOptions
        });
    }

    // Employees by Branch Chart (Bar Chart)
    const branchCtx = document.getElementById('branchChart');
    if (branchCtx) {
        new Chart(branchCtx, {
            type: 'bar',
            data: {
                labels: @json($chartData['branches']['labels']),
                datasets: [{
                    label: 'Employees',
                    data: @json($chartData['branches']['data']),
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Employees by User Type Chart (Pie Chart)
    const userTypeCtx = document.getElementById('userTypeChart');
    if (userTypeCtx) {
        new Chart(userTypeCtx, {
            type: 'pie',
            data: {
                labels: @json($chartData['userTypes']['labels']),
                datasets: [{
                    label: 'Employees',
                    data: @json($chartData['userTypes']['data']),
                    backgroundColor: [
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(249, 115, 22, 0.8)',
                    ],
                    borderColor: [
                        'rgba(168, 85, 247, 1)',
                        'rgba(236, 72, 153, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(249, 115, 22, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: chartOptions
        });
    }

    // Monthly Hiring Trend Chart (Line Chart)
    const monthlyHiringCtx = document.getElementById('monthlyHiringChart');
    if (monthlyHiringCtx) {
        new Chart(monthlyHiringCtx, {
            type: 'line',
            data: {
                labels: @json($chartData['monthlyHiring']['labels']),
                datasets: [{
                    label: 'New Hires',
                    data: @json($chartData['monthlyHiring']['data']),
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Absent Employees Chart (Line Chart)
    const absentCtx = document.getElementById('absentChart');
    if (absentCtx) {
        const absentLabels = @json($chartData['absent']['labels'] ?? []);
        const absentData = @json($chartData['absent']['data'] ?? []);
        
        new Chart(absentCtx, {
            type: 'line',
            data: {
                labels: absentLabels,
                datasets: [{
                    label: 'Absent Employees',
                    data: absentData,
                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Late Arrivals Chart (Bar Chart)
    const lateCtx = document.getElementById('lateChart');
    if (lateCtx) {
        const lateLabels = @json($chartData['late']['labels'] ?? []);
        const lateData = @json($chartData['late']['data'] ?? []);
        
        new Chart(lateCtx, {
            type: 'bar',
            data: {
                labels: lateLabels,
                datasets: [{
                    label: 'Late Arrivals',
                    data: lateData,
                    backgroundColor: 'rgba(234, 179, 8, 0.8)',
                    borderColor: 'rgba(234, 179, 8, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection

