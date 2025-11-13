@extends('layouts.admin-app')

@section('title', $selectedCompany ? 'Company: ' . e($selectedCompany) : 'Creator Dashboard')

@push('styles')
    {{-- Pushing styles stack for consistency --}}
@endpush

@section('content')
<main class="flex-1 min-h-screen p-8 !overflow-y-auto" style="
    padding-top: 0px;
    padding-bottom: 0px;
    padding-right: 0px;
    padding-left: 0px;
">
    {{-- 
      Note: The main sidebar toggle button is now in layouts/admin/mobile_menu.blade.php
      The page heading is now in layouts/admin/header.blade.php
      This replicates the HRIS layout structure.
    --}}
    
    @if ($selectedCompany)
        <a href="{{ route('admin.dashboard') }}" class="inline-block m-4 text-blue-600 hover:underline">
            &larr; Back to Companies List
        </a>
        
        @if ($companyData)
        <div class="bg-white shadow-md rounded-lg p-4 md:p-6 mb-6 mx-4">
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
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-blue-700">Total Users</p>
                            <p class="text-2xl font-bold">{{ $totalCompanyUsers }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-sm text-green-700">Admins</p>
                            <p class="text-2xl font-bold">{{ $adminCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <div class="bg-white p-3 sm:p-4 rounded-lg shadow-md mb-4 sm:mb-6 mt-6 mx-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h2 class="text-xl font-semibold">Users in {{ $selectedCompany }}</h2>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    
                    <div class="relative w-full sm:w-auto">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="userSearchInput" placeholder="Search User" 
                            class="w-full sm:w-auto border border-gray-300 rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    </div>
            </div>

            @if (empty($users) || $users->isEmpty())
                <p class="text-gray-600 p-4 text-center">No users found for this company.</p>
                @if (!$tableExists)
                    <div class="mt-4 bg-yellow-100 text-yellow-800 p-4 rounded-md">
                        <p>Company users table hasn't been created yet. The company may not have completed setup.</p>
                    </div>
                @endif
            @else
                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <table class="min-w-full divide-y divide-gray-200" id="company-users-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Full Name</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone Number</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Country</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Admin Status</th>
                                </tr>
                            </thead>
                            <tbody id="company-users-table-body" class="divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr class="align-middle hover:bg-gray-50">
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->user_name }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->full_name }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->user_email }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->phone_number }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->address ?? 'N/A' }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->country ?? 'N/A' }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->user_dept }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->user_position }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">
                                            @if ($user->access_level == 1)
                                                <span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-green-200 text-green-800">Admin</span>
                                            @elseif ($user->access_level == 2)
                                                @if ($user->is_admin == 1)
                                                    <span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-yellow-200 text-yellow-800 whitespace-nowrap">Super Admin</span>
                                                @else
                                                    <span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-blue-200 text-blue-800">Admin</span>
                                                @endif
                                            @elseif ($user->access_level == 3)
                                                <span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-gray-200 text-gray-800">User</span>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-gray-200 text-gray-800">Unknown</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
        
    @else
        <div class="bg-white p-3 sm:p-4 rounded-lg shadow-md mb-4 sm:mb-6 mt-6 mx-4">
             <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h2 class="text-xl font-semibold">Registered Companies</h2>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    
                    <div class="relative w-full sm:w-auto">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="companySearchInput" placeholder="Search Company" 
                               class="w-full sm:w-auto border border-gray-300 rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    </div>
            </div>

            @if (empty($companies) || $companies->isEmpty())
                <p class="text-gray-600 p-4 text-center">No companies registered yet.</p>
            @else
                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <table class="min-w-full divide-y divide-gray-200" id="companies-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company Name</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                                    <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">User Count</th>
                                    <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="companies-table-body" class="divide-y divide-gray-200">
                                @foreach ($companies as $company)
                                    <tr class="align-middle hover:bg-gray-50">
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-700 font-medium">{{ $company->company_name }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $company->company_email }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $company->company_phone }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $company->company_address }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-700 text-center">{{ $company->user_count ?? 0 }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-center">
                                            <a href="{{ route('admin.dashboard', ['company' => $company->company_name]) }}" 
                                               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm inline-block">
                                                View Users
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    @endif
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- Filter for Company Users Table ---
    const userSearchInput = document.getElementById('userSearchInput');
    if (userSearchInput) {
        userSearchInput.addEventListener('keyup', filterCompanyUsers);
    }

    function filterCompanyUsers() {
        const input = document.getElementById('userSearchInput');
        const filter = input.value.toLowerCase().trim();
        const rows = document.getElementById('company-users-table-body').getElementsByTagName('tr');
        let hasVisibleRows = false;
        const colSpan = 9; // Number of columns in the users table

        Array.from(rows).forEach(row => {
            if (row.classList.contains('no-results-row')) {
                row.style.display = 'none';
                return;
            }

            const nameCell = row.querySelector('td:nth-child(2)'); // Full Name
            const emailCell = row.querySelector('td:nth-child(3)'); // Email

            if (nameCell && emailCell) {
                const name = (nameCell.textContent || nameCell.innerText).toLowerCase();
                const email = (emailCell.textContent || emailCell.innerText).toLowerCase();
                
                const matches = name.includes(filter) || email.includes(filter);
                row.style.display = matches ? '' : 'none';
                if (matches) hasVisibleRows = true;
            }
        });

        let noResultsRow = document.querySelector('#company-users-table-body .no-results-row');
        if (!hasVisibleRows) {
            if (!noResultsRow) {
                noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                noResultsRow.innerHTML = `<td colspan="${colSpan}" class="px-6 py-4 text-center text-gray-500">No users found matching search</td>`;
                document.getElementById('company-users-table-body').appendChild(noResultsRow);
            }
            noResultsRow.style.display = '';
        } else if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
    }

    // --- Filter for Companies List Table ---
    const companySearchInput = document.getElementById('companySearchInput');
    if (companySearchInput) {
        companySearchInput.addEventListener('keyup', filterCompanies);
    }

    function filterCompanies() {
        const input = document.getElementById('companySearchInput');
        const filter = input.value.toLowerCase().trim();
        const rows = document.getElementById('companies-table-body').getElementsByTagName('tr');
        let hasVisibleRows = false;
        const colSpan = 6; // Number of columns in the companies table

        Array.from(rows).forEach(row => {
            if (row.classList.contains('no-results-row')) {
                row.style.display = 'none';
                return;
            }

            const nameCell = row.querySelector('td:nth-child(1)'); // Company Name

            if (nameCell) {
                const name = (nameCell.textContent || nameCell.innerText).toLowerCase();
                
                const matches = name.includes(filter);
                row.style.display = matches ? '' : 'none';
                if (matches) hasVisibleRows = true;
            }
        });

        let noResultsRow = document.querySelector('#companies-table-body .no-results-row');
        if (!hasVisibleRows) {
            if (!noResultsRow) {
                noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                noResultsRow.innerHTML = `<td colspan="${colSpan}" class="px-6 py-4 text-center text-gray-500">No companies found matching search</td>`;
                document.getElementById('companies-table-body').appendChild(noResultsRow);
            }
            noResultsRow.style.display = '';
        } else if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
    }

});
</script>
@endpush