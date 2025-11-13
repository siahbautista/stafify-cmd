@extends('layouts.admin-app')

@section('title', 'Pending Users')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<main>
    <!-- Pending Users Table -->
    <div class="bg-white p-3 sm:p-4 rounded-lg shadow-md mb-4 sm:mb-6 mt-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="relative w-full sm:w-auto">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" id="searchInput" placeholder="Search Name or Email" 
                class="w-full sm:w-auto border border-gray-300 rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>
        
        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle">
                <table class="min-w-full divide-y divide-gray-200" id="pending-users-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Full Name</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone Number</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Country</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                            <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="pending-users-table-body" class="divide-y divide-gray-200">
                        @forelse ($pendingUsers as $user)
                            <tr class="align-middle hover:bg-gray-50" data-user-id="{{ $user->user_id }}">
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->user_name }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->full_name }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->user_email }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->phone_number }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->address }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->country }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->company }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-center">
                                    <div class="flex justify-center items-center space-x-2">
                                        <button onclick="approveUser('{{ $user->user_id }}')" class="text-green-500 hover:text-green-700" title="Approve User">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                        </button>
                                        <button onclick="rejectUser('{{ $user->user_id }}')" class="text-red-500 hover:text-red-700" title="Reject User">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 sm:px-6 py-4 text-center text-gray-500">No pending users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- CSRF Token for Fetch ---
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const fetchHeaders = {
        "X-CSRF-TOKEN": csrfToken,
        "Accept": "application/json",
        "Content-Type": "application/json",
    };

    // --- Toast Function ---
    function showToast(message, type = 'success') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            icon: type,
            title: message
        });
    }

    // --- Approve User Function ---
    function approveUser(userId) {
        Swal.fire({
            title: 'Approve User',
            text: "Are you sure you want to approve this user? This will create their Google Drive folder.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve!',
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                try {
                    const response = await fetch(`{{ url('admin/users') }}/${userId}/approve`, {
                        method: 'POST',
                        headers: fetchHeaders,
                    });
                    if (!response.ok) {
                        const error = await response.json();
                        throw new Error(error.message || 'Approval failed.');
                    }
                    return await response.json();
                } catch (error) {
                    Swal.showValidationMessage(`Request failed: ${error.message}`);
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                showToast(result.value.message, 'success');
                document.querySelector(`tr[data-user-id="${userId}"]`).remove();
                filterUsers(); // Re-run filter to check for empty table
            }
        });
    }

    // --- Reject User Function ---
    function rejectUser(userId) {
        Swal.fire({
            title: 'Reject User',
            text: "Are you sure you want to reject this user? They will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, reject!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`{{ url('admin/users') }}/${userId}/reject`, {
                        method: 'POST',
                        headers: fetchHeaders,
                    });
                    const result = await response.json();
                    if (!response.ok) throw result;

                    showToast(result.message, 'success');
                    document.querySelector(`tr[data-user-id="${userId}"]`).remove();
                    filterUsers(); // Re-run filter to check for empty table
                } catch (error) {
                    showToast(error.message || 'Failed to reject user.', 'error');
                }
            }
        });
    }

    // --- Filter Users Function ---
    function filterUsers() {
        const input = document.getElementById('searchInput');
        if (!input) return; // Guard clause if search input isn't present
        
        const filter = input.value.toLowerCase().trim();
        const tableBody = document.getElementById('pending-users-table-body');
        if (!tableBody) return; // Guard clause if table body isn't present
        
        const rows = tableBody.getElementsByTagName('tr');
        let hasVisibleRows = false;
        const colSpan = 8; // Number of columns in the table

        Array.from(rows).forEach(row => {
            // Ensure it's not the "no results" or original "no users" row
            const noResultCell = row.querySelector('td[colspan]');
            if (row.classList.contains('no-results-row') || (noResultCell && noResultCell.getAttribute('colspan') == colSpan)) {
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

        // Show/hide "No results" message
        let noResultsRow = tableBody.querySelector('.no-results-row');
        if (!hasVisibleRows) {
            if (!noResultsRow) {
                noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                noResultsRow.innerHTML = `<td colspan="${colSpan}" class="px-6 py-4 text-center text-gray-500">No users found matching search</td>`;
                tableBody.appendChild(noResultsRow);
            }
            noResultsRow.style.display = '';
        } else if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
        
        // Handle original empty message
        const originalEmptyRow = tableBody.querySelector('td[colspan]');
        if (filter === '' && !hasVisibleRows && originalEmptyRow) {
             // Check if it's the original "No pending users found"
            if (!originalEmptyRow.parentElement.classList.contains('no-results-row')) {
                originalEmptyRow.parentElement.style.display = ''; // Show original "No pending users"
            }
        }
    }

    // Add event listener for filter
    document.getElementById('searchInput')?.addEventListener('keyup', filterUsers);
    
    // Initial filter run in case table is empty to begin with
    filterUsers();

</script>
@endpush