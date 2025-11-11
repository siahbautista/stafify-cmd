@extends('layouts.admin-app')

@section('title', $selectedCompany ? 'Company: ' . e($selectedCompany) : 'Creator Dashboard')

@section('content')
<main class="flex-1 min-h-screen p-8 !overflow-y-auto">
    {{-- 
      Note: The main sidebar toggle button is now in layouts/admin/mobile_menu.blade.php
      The page heading is now in layouts/admin/header.blade.php
      This replicates the HRIS layout structure.
    --}}
    
    @if ($selectedCompany)
        <a href="{{ route('admin.dashboard') }}" class="inline-block mb-4 text-blue-600 hover:underline">
            &larr; Back to Companies List
        </a>
        
        @if ($companyData)
        <div class="bg-white shadow-md rounded-lg p-4 md:p-6 mb-6">
            <div class="flex flex-wrap justify-between">
                <div class="w-full md:w-1/2 lg:w-1/3 mb-4">
                    <h3 class="text-lg font-semibold mb-2">Company Details</h3>
                    <div class="grid grid-cols-1 gap-2">
                        <div class="flex">
                            <span class="font-medium w-24">Name:</span>
                            <span>{{ $companyData->company_name }}</span>
                        </div>
                        <div class="flex">
                            <span class="font-medium w-24">Email:</span>
                            <span>{{ $companyData->company_email }}</span>
                        </div>
                        <div class="flex">
                            <span class="font-medium w-24">Phone:</span>
                            <span>{{ $companyData->company_phone }}</span>
                        </div>
                        <div class="flex">
                            <span class="font-medium w-24">Address:</span>
                            <span>{{ $companyData->company_address }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="w-full md:w-1/3 mb-4">
                    <h3 class="text-lg font-semibold mb-2">User Statistics</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded">
                            <p class="text-sm text-blue-700">Total Users</p>
                            <p class="text-2xl font-bold">{{ $totalCompanyUsers }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded">
                            <p class="text-sm text-green-700">Admins</p>
                            <p class="text-2xl font-bold">{{ $adminCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <div class="mt-6 bg-white shadow-md rounded-lg p-4 md:p-6 overflow-x-auto">
            <h2 class="text-xl font-semibold mb-4">Users in {{ $selectedCompany }}</h2>
            
            @if (empty($users) || $users->isEmpty())
                <p class="text-gray-600">No users found for this company.</p>
                @if (!$tableExists)
                    <div class="mt-4 bg-yellow-100 text-yellow-800 p-4 rounded-md">
                        <p>Company users table hasn't been created yet. The company may not have completed setup.</p>
                    </div>
                @endif
            @else
                <table class="w-full min-w-[1200px]">
                    <thead>
                        <tr>
                            <th class="table-head">Username</th>
                            <th class="table-head">Full Name</th>
                            <th class="table-head">Email</th>
                            <th class="table-head">Phone Number</th>
                            <th class="table-head">Address</th>
                            <th class="table-head">Country</th>
                            <th class="table-head">Department</th>
                            <th class="table-head">Position</th>
                            <th class="table-head">Admin Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="table-data">{{ $user->user_name }}</td>
                                <td class="table-data">{{ $user->full_name }}</td>
                                <td class="table-data">{{ $user->user_email }}</td>
                                <td class="table-data">{{ $user->phone_number }}</td>
                                <td class="table-data">{{ $user->address ?? 'N/A' }}</td>
                                <td class="table-data">{{ $user->country ?? 'N/A' }}</td>
                                <td class="table-data">{{ $user->user_dept }}</td>
                                <td class="table-data">{{ $user->user_position }}</td>
                                <td class="table-data text-center">
                                    @if ($user->access_level == 1)
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Admin</span>
                                    @elseif ($user->access_level == 2)
                                        @if ($user->is_admin == 1)
                                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded">Super Admin</span>
                                        @else
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Admin</span>
                                        @endif
                                    @elseif ($user->access_level == 3)
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">User</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">Unknown</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        
    @else
        <div class="mt-6 bg-white shadow-md rounded-lg p-4 md:p-6 overflow-x-auto">
            @if (empty($companies) || $companies->isEmpty())
                <p class="text-gray-600">No companies registered yet.</p>
            @else
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="table-head">Company Name</th>
                            <th class="table-head">Email</th>
                            <th class="table-head">Phone</th>
                            <th class="table-head">Address</th>
                            <th class="table-head">User Count</th>
                            <th class="table-head">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($companies as $company)
                            <tr>
                                <td class="table-data font-medium">{{ $company->company_name }}</td>
                                <td class="table-data">{{ $company->company_email }}</td>
                                <td class="table-data">{{ $company->company_phone }}</td>
                                <td class="table-data">{{ $company->company_address }}</td>
                                <td class="table-data text-center">{{ $company->user_count ?? 0 }}</td>
                                <td class="table-data">
                                    <a href="{{ route('admin.dashboard', ['company' => $company->company_name]) }}" 
                                       class="bg-blue-500 text-white px-3 py-1 rounded text-sm inline-block">
                                        View Users
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif
</main>
@endsection