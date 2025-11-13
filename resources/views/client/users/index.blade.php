@extends('layouts.client-app')

@section('title', 'Manage Employees')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<section>

    <main>
        <div class="flex justify-between mb-2"> {{-- Fixed typo 'mb -2' to 'mb-2' --}}
            <button onclick="toggleSidebar()" class="lg:hidden text-primary rounded">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M4 8l16 0" />
                    <path d="M4 16l16 0" />
                </svg>
            </button>
                
            <div class="flex lg:hidden gap-4">
                <div class="total_users">
                    <p class="!text-[12px]">Total Users:</p>
                    <span class="!text-[20px]">{{ count($users) }}</span>
                </div>
            </div>        
        </div>
        
        <!-- Updated Table Section -->
        <div class="bg-white p-3 sm:p-4 rounded-lg shadow-md mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                @if ($isAdmin >= 1)
                    <button onclick="openAddUserModal()" class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        Add New User
                    </button>
                @endif
                
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <input type="text" id="searchInput" placeholder="Search by name" 
                           class="w-full sm:w-auto border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    
                    <select id="statusFilter"
                            class="w-full sm:w-auto border border-gray-300 rounded-lg px-6 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">All Status</option>
                        <option value="1">Super Admin</option>
                        <option value="2">Admin</option>
                        <option value="0">Basic User</option>
                    </select>

                    <select id="departmentFilter"
                            class="w-full sm:w-auto border border-gray-300 rounded-lg px-6 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">All Departments</option>
                        @php
                            $uniqueDepts = $users->pluck('user_dept')->unique()->filter()->sort();
                        @endphp
                        @foreach($uniqueDepts as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Users Table -->
            <div class="overflow-x-auto">
                <div class="min-w-full inline-block align-middle">
                    <table class="min-w-full divide-y divide-gray-200" id="users-table">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Full Name</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone Number</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branch Location</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Starting Date</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Admin Status</th>
                                <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="users-table-body" class="divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr id="user-row-{{ $user->user_id }}" class="align-middle hover:bg-gray-50 {{ $user->is_admin == 1 ? 'bg-yellow-50 font-semibold' : '' }}">
                                    <td class="px-4 sm:px-6 py-4 text-sm">{{ $user->full_name }}</td>
                                    <td class="px-4 sm:px-6 py-4 text-sm">{{ $user->user_email }}</td>
                                    <td class="px-4 sm:px-6 py-4 text-sm">{{ $user->phone_number }}</td>
                                    <td class="px-4 sm:px-6 py-4 text-sm">{{ $user->company }}</td>
                                    <td class="px-4 sm:px-6 py-4 text-sm">{{ $user->branch_location }}</td>
                                    <td class="px-4 sm:px-6 py-4 text-sm">{{ $user->user_dept }}</td>
                                    <td class="px-4 sm:px-6 py-4 text-sm">{{ $user->user_position }}</td>
                                    <td class="px-4 sm:px-6 py-4 text-sm">{{ $user->employment_date }}</td>
                                    <td class="px-4 sm:px-6 py-4 text-sm text-left">
                                        @if ($user->is_admin == 1)
                                            <span style="white-space: nowrap;" class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-yellow-200 text-yellow-800">Super Admin</span>
                                        @elseif ($user->is_admin == 2)
                                            <span style="white-space: nowrap;"class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-blue-200 text-blue-800">Admin</span>
                                        @else
                                            <span style="white-space: nowrap;"class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-green-200 text-green-800">Basic User</span>
                                        @endif
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-sm text-center">
                                        <div class="flex justify-center items-center space-x-2">
                                            @if ( $isAdmin == 1 || ($isAdmin == 2 && ($user->is_admin == 0 || $user->user_id == Auth::id())) )
                                                <button onclick='openEditModal(@json($user))' class="text-blue-500 hover:text-blue-700" title="Edit User">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                </button>
                                            @endif

                                            @if ($user->user_id != Auth::id() && $user->is_admin != 1)
                                                @if ( $isAdmin == 1 || ($isAdmin == 2 && $user->is_admin == 0) )
                                                    <button onclick="deleteUser({{ $user->user_id }}, '{{ $user->full_name }}')" class="text-red-500 hover:text-red-700" title="Delete User">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                    </button>
                                                @endif
                                            @endif

                                            @if ($isAdmin == 1)
                                                @if ($user->is_admin == 0)
                                                    <button onclick="promoteUser({{ $user->user_id }}, '{{ $user->full_name }}')" class="text-green-500 hover:text-green-700" title="Promote to Admin">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-up"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /><path d="M19 22v-6" /><path d="M22 19l-3 -3l-3 3" /></svg>
                                                    </button>
                                                @elseif ($user->is_admin == 2)
                                                    <button onclick="demoteUser({{ $user->user_id }}, '{{ $user->full_name }}')" class="text-yellow-500 hover:text-yellow-700" title="Demote to Basic User">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-down"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /><path d="M19 16v6" /><path d="M22 19l-3 3l-3 -3" /></svg>
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if($users->count() == 0)
                                <tr>
                                    <td colspan="10" class="px-4 sm:px-6 py-4 text-center text-gray-500">No users found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main> 
</section>

@include('client.users._add-user-modal')
@include('client.users._edit-user-modal')
@include('client.users._add-department-modal')
@include('client.users._add-position-modal')

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// --- Global Toast Function ---
function showToast(message, type = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
}

// --- Filter Users Function ---
function filterUsers() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase().trim();
    const statusFilter = document.getElementById('statusFilter').value;
    const deptFilter = document.getElementById('departmentFilter').value;
    const rows = document.getElementById('users-table-body').getElementsByTagName('tr');
    let hasVisibleRows = false;

    // Loop through all rows and hide/show based on filters
    Array.from(rows).forEach(row => {
        // Ensure it's not the "no results" row
        if (row.classList.contains('no-results-row')) {
            row.style.display = 'none'; // Hide "no results" row during filtering
            return;
        }

        const nameCell = row.querySelector('td:nth-child(1)');
        const deptCell = row.querySelector('td:nth-child(6)');
        const statusCell = row.querySelector('td:nth-child(9) span'); // Get the span inside

        if (nameCell && deptCell && statusCell) {
            const name = (nameCell.textContent || nameCell.innerText).toLowerCase();
            const department = (deptCell.textContent || deptCell.innerText).trim();
            
            // Get status from the span's text content
            let status = (statusCell.textContent || statusCell.innerText).trim();
            let statusValue = '0'; // Basic User
            if (status === 'Super Admin') {
                statusValue = '1';
            } else if (status === 'Admin') {
                statusValue = '2';
            }

            const matchesName = name.includes(filter);
            const matchesStatus = statusFilter === 'all' || statusValue === statusFilter;
            const matchesDept = deptFilter === 'all' || department === deptFilter;
            
            const matches = matchesName && matchesStatus && matchesDept;
            row.style.display = matches ? '' : 'none';
            if (matches) hasVisibleRows = true;
        }
    });

    // Show/hide "No results" message
    let noResultsRow = document.querySelector('.no-results-row');
    if (!hasVisibleRows) {
        if (!noResultsRow) {
            noResultsRow = document.createElement('tr');
            noResultsRow.className = 'no-results-row';
            noResultsRow.innerHTML = `
                <td colspan="10" class="px-6 py-4 text-center text-gray-500">No results found</td>
            `;
            document.getElementById('users-table-body').appendChild(noResultsRow);
        }
        noResultsRow.style.display = '';
    } else if (noResultsRow) {
        noResultsRow.style.display = 'none';
    }
}


// --- Add/Edit User Modals ---
function openAddUserModal() {
    document.getElementById('addUserModal').classList.remove('hidden');
}

function closeAddUserModal() {
    document.getElementById('addUserModal').classList.add('hidden');
    document.getElementById('addUserForm').reset();
}

function openEditModal(user) {
    const form = document.getElementById('editUserForm');
    form.action = `/client/users/${user.user_id}`;
    
    document.getElementById('editUserId').value = user.user_id;
    document.getElementById('editFullName').value = user.full_name || '';
    document.getElementById('editEmail').value = user.user_email || '';
    document.getElementById('editPhone').value = user.phone_number || '';
    document.getElementById('editAddress').value = user.address || '';
    document.getElementById('editCountry').value = user.country || '';
    document.getElementById('editCountrySearch').value = user.country || '';
    document.getElementById('editCountryCode').value = user.country_code || '';
    document.getElementById('editPin').value = user.user_pin || '';
    document.getElementById('editCompany').value = user.company || '';
    document.getElementById('editBranch').value = user.branch_location || '';
    document.getElementById('editDept').value = user.user_dept || '';
    document.getElementById('editPosition').value = user.user_position || '';    
    document.getElementById('editEmploymentDate').value = user.employment_date || '';
    document.getElementById('editAccessLevel').value = user.access_level || '';
    
    document.getElementById('editUserModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editUserModal').classList.add('hidden');
}

// --- Add Dept/Position Modals ---
function openAddDepartmentModal() {
    document.getElementById('addDepartmentModal').classList.remove('hidden');
    document.getElementById('newDepartmentName').focus();
}

function closeAddDepartmentModal() {
    document.getElementById('addDepartmentModal').classList.add('hidden');
    document.getElementById('addDepartmentForm').reset();
}

function openAddPositionModal() {
    document.getElementById('addPositionModal').classList.remove('hidden');
    document.getElementById('newPositionName').focus();
}

function closeAddPositionModal() {
    document.getElementById('addPositionModal').classList.add('hidden');
    document.getElementById('addPositionForm').reset();
}

// --- Dynamic Table Row Functions ---
function getUserAccessBadge(user) {
    let badge = '';
    if (user.is_admin == 1) {
        badge = '<span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-yellow-200 text-yellow-800 whitespace-nowrap">Super Admin</span>';
    } else if (user.is_admin == 2) {
        badge = `<span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-blue-200 text-blue-800">Admin</span>`;
    } else {
        badge = `<span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-green-200 text-green-800">Basic User</span>`;
    }
    return badge;
}

const loggedInUser = {
    isAdmin: @json($isAdmin),
    id: @json(Auth::id())
};

function getActionButtons(user) {
    let buttons = '';
    const userJson = JSON.stringify(user).replace(/"/g, '&quot;');

    // Edit Button
    if (loggedInUser.isAdmin == 1 || (loggedInUser.isAdmin == 2 && (user.is_admin == 0 || user.user_id == loggedInUser.id))) {
        buttons += `
            <button onclick='openEditModal(${userJson})' class="text-blue-500 hover:text-blue-700" title="Edit User">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
            </button>`;
    }

    // Delete Button
    if (user.user_id != loggedInUser.id && user.is_admin != 1) {
        if (loggedInUser.isAdmin == 1 || (loggedInUser.isAdmin == 2 && user.is_admin == 0)) {
            buttons += `
                <button onclick="deleteUser(${user.user_id}, '${user.full_name}')" class="text-red-500 hover:text-red-700" title="Delete User">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                </button>`;
        }
    }

    // Promote/Demote Buttons
    if (loggedInUser.isAdmin == 1) {
        if (user.is_admin == 0) {
            buttons += `
                <button onclick="promoteUser(${user.user_id}, '${user.full_name}')" class="text-green-500 hover:text-green-700" title="Promote to Admin">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-up"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /><path d="M19 22v-6" /><path d="M22 19l-3 -3l-3 3" /></svg>
                </button>`;
        } else if (user.is_admin == 2) {
            buttons += `
                <button onclick="demoteUser(${user.user_id}, '${user.full_name}')" class="text-yellow-500 hover:text-yellow-700" title="Demote to Basic User">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-down"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /><path d="M19 16v6" /><path d="M22 19l-3 3l-3 -3" /></svg>
                </button>`;
        }
    }

    return `<div class="flex justify-center items-center space-x-2">${buttons}</div>`;
}

function createRowHtml(user) {
    return `
        <td class="px-4 sm:px-6 py-4 text-sm">${user.full_name || ''}</td>
        <td class="px-4 sm:px-6 py-4 text-sm">${user.user_email || ''}</td>
        <td class="px-4 sm:px-6 py-4 text-sm">${user.phone_number || ''}</td>
        <td class="px-4 sm:px-6 py-4 text-sm">${user.company || ''}</td>
        <td class="px-4 sm:px-6 py-4 text-sm">${user.branch_location || ''}</td>
        <td class="px-4 sm:px-6 py-4 text-sm">${user.user_dept || ''}</td>
        <td class="px-4 sm:px-6 py-4 text-sm">${user.user_position || ''}</td>
        <td class="px-4 sm:px-6 py-4 text-sm">${user.employment_date || ''}</td>
        <td class="px-4 sm:px-6 py-4 text-sm text-left">${getUserAccessBadge(user)}</td>
        <td class="px-4 sm:px-6 py-4 text-sm text-center">${getActionButtons(user)}</td>
    `;
}

function appendNewUserToTable(user) {
    const tableBody = document.querySelector('#users-table-body');
    const newRow = document.createElement('tr');
    newRow.id = `user-row-${user.user_id}`;
    newRow.className = `align-middle hover:bg-gray-50 ${user.is_admin == 1 ? 'bg-yellow-50 font-semibold' : ''}`;
    newRow.innerHTML = createRowHtml(user);
    
    // Remove "no results" row if it exists
    const noResultsRow = tableBody.querySelector('.no-results-row');
    if (noResultsRow) {
        noResultsRow.remove();
    }

    tableBody.appendChild(newRow);
}

function updateUserRow(user) {
    const row = document.getElementById(`user-row-${user.user_id}`);
    if (row) {
        row.className = `align-middle hover:bg-gray-50 ${user.is_admin == 1 ? 'bg-yellow-50 font-semibold' : ''}`;
        row.innerHTML = createRowHtml(user);
    }
}

function updateTotalUsersCount() {
    const rows = document.querySelectorAll('#users-table-body tr:not(.no-results-row)');
    const totalUserElements = document.querySelectorAll('.total_users span');
    const userCount = rows.length;
    
    totalUserElements.forEach(el => {
        el.textContent = userCount;
    });
}

// --- Main AJAX Handlers ---
document.addEventListener('DOMContentLoaded', function() {

    // Initialize country dropdowns
    initCountryDropdowns();

    // Add event listeners for filters
    document.getElementById('searchInput').addEventListener('keyup', filterUsers);
    document.getElementById('statusFilter').addEventListener('change', filterUsers);
    document.getElementById('departmentFilter').addEventListener('change', filterUsers);


    // --- Add/Edit User Form Handler ---
    document.getElementById('addUserForm').addEventListener('submit', handleUserFormSubmit);
    document.getElementById('editUserForm').addEventListener('submit', handleUserFormSubmit);

    async function handleUserFormSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const action = form.action;
        const isEdit = form.id === 'editUserForm';
        
        if (isEdit) {
            formData.append('_method', 'PUT');
        }

        const result = await Swal.fire({
            title: isEdit ? 'Update User' : 'Add User',
            text: `Are you sure you want to ${isEdit ? 'update' : 'add'} this user?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!'
        });

        if (!result.isConfirmed) return;

        try {
            const response = await fetch(action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                if (isEdit) {
                    closeEditModal();
                    updateUserRow(data.user);
                } else {
                    closeAddUserModal();
                    appendNewUserToTable(data.user);
                    updateTotalUsersCount();
                }
                // Re-run filter in case the added/edited user matches current filters
                filterUsers();
                showToast(data.message, 'success');
            } else {
                let errorMessage = data.message;
                if (data.errors) {
                    errorMessage = Object.values(data.errors).map(err => err[0]).join('<br>');
                }
                showToast(errorMessage, 'error');
            }
        } catch (error) {
            showToast('An error occurred.', 'error');
            console.error('Error:', error);
        }
    }

    // --- Add Department Form Handler ---
    document.getElementById('addDepartmentForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const departmentName = document.getElementById('newDepartmentName').value.trim();
        
        if (!departmentName) {
            showToast('Department name is required', 'error');
            return;
        }

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ department_name: departmentName })
            });

            const data = await response.json();

            if (data.success) {
                const newOption = new Option(data.department.department_name, data.department.department_name);
                
                document.querySelector('select[name="user_dept"]').appendChild(newOption.cloneNode(true));
                document.getElementById('editDept').appendChild(newOption.cloneNode(true));
                
                // Add to department filter dropdown
                document.getElementById('departmentFilter').appendChild(newOption.cloneNode(true));
                
                showToast(data.message, 'success');
                closeAddDepartmentModal();
            } else {
                showToast(data.message || 'Failed to add department', 'error');
            }
        } catch (error) {
            showToast('An error occurred.', 'error');
            console.error('Error:', error);
        }
    });

    // --- Add Position Form Handler ---
    document.getElementById('addPositionForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const positionName = document.getElementById('newPositionName').value.trim();
        
        if (!positionName) {
            showToast('Position name is required', 'error');
            return;
        }

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ position_name: positionName })
            });

            const data = await response.json();

            if (data.success) {
                const newOption = new Option(data.position.position_name, data.position.position_name);
                
                document.querySelector('select[name="user_position"]').appendChild(newOption.cloneNode(true));
                document.getElementById('editPosition').appendChild(newOption);
                
                showToast(data.message, 'success');
                closeAddPositionModal();
            } else {
                showToast(data.message || 'Failed to add position', 'error');
            }
        } catch (error) {
            showToast('An error occurred.', 'error');
            console.error('Error:', error);
        }
    });
});

// --- Standalone Action Functions ---

async function deleteUser(userId, userName) {
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete ${userName}? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    });

    if (!result.isConfirmed) return;

    try {
        const response = await fetch(`/client/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            document.getElementById(`user-row-${userId}`).remove();
            updateTotalUsersCount();
            filterUsers(); // Re-check if "no results" row is needed
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('An error occurred.', 'error');
        console.error('Error:', error);
    }
}

async function promoteUser(userId, userName) {
    const result = await Swal.fire({
        title: 'Promote User?',
        text: `Are you sure you want to promote ${userName} to Admin?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, promote!'
    });

    if (!result.isConfirmed) return;

    try {
        const response = await fetch(`/client/users/${userId}/promote`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        });

        const data = await response.json();

        if (data.success) {
            updateUserRow(data.user);
            filterUsers(); // Re-apply filters
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('An error occurred.', 'error');
        console.error('Error:', error);
    }
}

async function demoteUser(userId, userName) {
    const result = await Swal.fire({
        title: 'Demote User?',
        text: `Are you sure you want to demote ${userName} to a Basic User?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, demote!'
    });

    if (!result.isConfirmed) return;

    try {
        const response = await fetch(`/client/users/${userId}/demote`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        });

        const data = await response.json();

        if (data.success) {
            updateUserRow(data.user);
            filterUsers(); // Re-apply filters
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('An error occurred.', 'error');
        console.error('Error:', error);
    }
}

// --- Country Search Functions ---
let countries = [];

async function fetchCountries() {
    try {
        const response = await fetch('https://restcountries.com/v3.1/all?fields=name,cca2,flags');
        if (!response.ok) throw new Error('Failed to fetch countries');
        const data = await response.json();
        countries = data.map(country => ({
            name: country.name.common,
            code: country.cca2,
            flag: country.flags.png
        })).sort((a, b) => a.name.localeCompare(b.name));
        return countries;
    } catch (error) {
        console.error('Error fetching countries:', error);
        return [];
    }
}

function setupCountrySearch(searchInputId, countryInputId, countryCodeInputId, dropdownId) {
    const searchInput = document.getElementById(searchInputId);
    const countryInput = document.getElementById(countryInputId);
    const countryCodeInput = document.getElementById(countryCodeInputId);
    const dropdown = document.getElementById(dropdownId);

    if (!searchInput || !countryInput || !countryCodeInput || !dropdown) {
        console.error('Country search elements not found for ID prefix:', searchInputId);
        return; // Gracefully exit if elements aren't found
    }

    searchInput.addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const filteredCountries = countries.filter(country => 
            country.name.toLowerCase().includes(searchValue)
        );
        updateCountryDropdown(filteredCountries, dropdown, countryInput, countryCodeInput, searchInput);
    });

    searchInput.addEventListener('focus', function() {
        if(countries.length > 0) {
            updateCountryDropdown(countries, dropdown, countryInput, countryCodeInput, searchInput);
        }
    });

    document.addEventListener('click', function(event) {
        if (!event.target.closest(`#${searchInputId}`) && !event.target.closest(`#${dropdownId}`)) {
            dropdown.classList.add('hidden');
        }
    });
}

function updateCountryDropdown(filteredCountries, dropdown, countryInput, countryCodeInput, searchInput) {
    dropdown.innerHTML = '';
    dropdown.classList.remove('hidden');

    if (filteredCountries.length === 0) {
        dropdown.innerHTML = '<div class="p-2 text-gray-500">No countries found</div>';
        return;
    }

    filteredCountries.forEach(country => {
        const item = document.createElement('div');
        item.className = 'p-2 hover:bg-gray-100 cursor-pointer';
        item.innerHTML = `<span>${country.name}</span>`;
        item.addEventListener('click', function() {
            countryInput.value = country.name;
            countryCodeInput.value = country.code;
            searchInput.value = country.name;
            dropdown.classList.add('hidden');
        });
        dropdown.appendChild(item);
    });
}

async function initCountryDropdowns() {
    await fetchCountries();
    setupCountrySearch('editCountrySearch', 'editCountry', 'editCountryCode', 'editCountryDropdown');
    setupCountrySearch('addCountrySearch', 'addCountry', 'addCountryCode', 'addCountryDropdown');
}
</script>
@endpush