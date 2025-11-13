@extends('layouts.admin-app')

@section('title', 'All Users')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<main>
    <!-- User Table -->
    <div class="bg-white p-3 sm:p-4 rounded-lg shadow-md mb-4 sm:mb-6 mt-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <button onclick="openAddUserModal()" class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M16 19h6" /><path d="M19 16v6" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /></svg>
                Add New User
            </button>

            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                
                <div class="relative w-full sm:w-auto">

                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <input type="text" id="nameFilter" placeholder="Search by name" 
                        class="w-full sm:w-auto border border-gray-300 rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <select id="companyFilter" class="w-full sm:w-auto border border-gray-300 rounded-lg px-6 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Companies</option>
                    @foreach ($companies->whereNotNull() as $company)
                        <option value="{{ $company }}">{{ $company }}</option>
                    @endforeach
                </select>
                
                <select id="branchFilter" class="w-full sm:w-auto border border-gray-300 rounded-lg px-6 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Branches</option>
                    @foreach ($branches->whereNotNull() as $branch)
                        <option value="{{ $branch }}">{{ $branch }}</option>
                    @endforeach
                </select>
                
                <select id="departmentFilter" class="w-full sm:w-auto border border-gray-300 rounded-lg px-6 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Departments</option>
                    @foreach ($departments->whereNotNull() as $department)
                        <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </select>

                <button id="resetFilters" class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg flex items-center justify-center gap-2" title="Reset Filters">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-reload"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.933 13.041a8 8 0 1 1 -9.925 -8.788c3.899 -1.002 7.935 1.007 9.425 4.747" /><path d="M20 4v5h-5" /></svg>
                    <span class="sm:hidden">Reset Filters</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle">
                <table class="min-w-full divide-y divide-gray-200" id="users-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Full Name</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone Number</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branch Location</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Access Level</th>
                            <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body" class="divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr class="align-middle hover:bg-gray-50" data-user-id="{{ $user->user_id }}">
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->full_name }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->user_email }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->phone_number }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->company }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->branch_location }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->user_dept }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $user->user_position }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">
                                    @if($user->access_level == 1)
                                        <span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-red-200 text-red-800">Creator</span>
                                    @elseif($user->access_level == 2)
                                        <span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-blue-200 text-blue-800">Client</span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-gray-200 text-gray-800">Basic User</span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-center">
                                    <div class="flex justify-center items-center space-x-2">
                                        <button 
                                            @if ($user->drive_folder_link)
                                                onclick="window.open('{{ $user->drive_folder_link }}', '_blank')"
                                            @else
                                                onclick="Swal.fire({icon: 'warning', title: 'No Drive Folder', text: 'No drive folder found for this user.', confirmButtonColor: '#4285F4'})"
                                            @endif
                                            class="text-blue-500 hover:text-blue-700" 
                                            title="Open Google Drive Folder">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" /></svg>
                                        </button>
                                                                            
                                        <button onclick='openEditModal(@json($user))' class="text-blue-500 hover:text-blue-700" title="Edit User">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                        </button>
                                        
                                        <button onclick="deleteUser('{{ $user->user_id }}')" class="text-red-500 hover:text-red-700" title="Delete User">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 sm:px-6 py-4 text-center text-gray-500">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

@include('admin.users._edit-modal')
@include('admin.users._add-modal')
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

    // --- Country Search (shared by modals) ---
    let countries = [];
    async function fetchCountries() {
        if (countries.length > 0) return countries;
        try {
            const response = await fetch('https://restcountries.com/v3.1/all?fields=name,cca2');
            const data = await response.json();
            countries = data.map(country => ({
                name: country.name.common,
                code: country.cca2,
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

        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const filteredCountries = countries.filter(c => c.name.toLowerCase().includes(searchValue));
            updateCountryDropdown(filteredCountries, dropdown, countryInput, countryCodeInput, searchInput);
        });
        searchInput.addEventListener('focus', function() {
            updateCountryDropdown(countries, dropdown, countryInput, countryCodeInput, searchInput);
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

    // --- Modal Controls ---
    function openAddUserModal() {
        const addUserForm = document.getElementById('addUserForm');
        if (addUserForm) {
            addUserForm.reset();
        }
        
        // Reset country search fields for add modal, check if elements exist first
        const addCountrySearch = document.getElementById('addCountrySearch');
        if (addCountrySearch) {
            addCountrySearch.value = '';
        }
        
        const addCountry = document.getElementById('addCountry');
        if (addCountry) {
            addCountry.value = '';
        }
        
        const addCountryCode = document.getElementById('addCountryCode');
        if (addCountryCode) {
            addCountryCode.value = '';
        }
        
        const addUserModal = document.getElementById('addUserModal');
        if (addUserModal) {
            addUserModal.classList.remove('hidden');
        } else {
            console.error('Add User Modal not found. Make sure _add-modal.blade.php is included and has id="addUserModal".');
            showToast('Error: Could not open modal.', 'error');
        }
    }
    function closeAddUserModal() {
        document.getElementById('addUserModal').classList.add('hidden');
    }
    function openEditModal(user) {
        document.getElementById('editUserForm').action = `{{ url('admin/users') }}/${user.user_id}`;
        document.getElementById('editUserId').value = user.user_id;
        document.getElementById('editFullName').value = user.full_name;
        document.getElementById('editEmail').value = user.user_email;
        document.getElementById('editPhone').value = user.phone_number;
        document.getElementById('editAddress').value = user.address;
        document.getElementById('editCountry').value = user.country || '';
        document.getElementById('editCountrySearch').value = user.country || '';
        document.getElementById('editCountryCode').value = user.country_code || '';
        document.getElementById('editCompany').value = user.company;
        document.getElementById('editBranch').value = user.branch_location;
        document.getElementById('editDept').value = user.user_dept;
        document.getElementById('editPosition').value = user.user_position;    
        document.getElementById('editEmploymentDate').value = user.employment_date;
        document.getElementById('editAccessLevel').value = user.access_level;
        document.getElementById('editPin').value = user.user_pin;
        document.getElementById('editUserModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }

    // --- Add User Form Submission ---
    document.getElementById('addUserForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: fetchHeaders,
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (!response.ok) throw result;

            showToast(result.message, 'success');
            form.reset();
            closeAddUserModal();
            // Add the new user to the table (simplified)
            location.reload(); // Easiest way to show new user
        } catch (error) {
            let errorMsg = error.message || 'An error occurred.';
            if (error.errors) {
                errorMsg = Object.values(error.errors).map(err => err[0]).join('<br>');
            }
            showToast(errorMsg, 'error');
        }
    });

    // --- Edit User Form Submission ---
    document.getElementById('editUserForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(form.action, {
                method: 'POST', // Using POST with _method spoofing
                headers: fetchHeaders,
                body: JSON.stringify({ ...data, _method: 'PUT' })
            });
            const result = await response.json();
            if (!response.ok) throw result;

            showToast(result.message, 'success');
            closeEditModal();
            // Update the user row in the table (simplified)
            location.reload(); // Easiest way to show changes
        } catch (error) {
            let errorMsg = error.message || 'An error occurred.';
            if (error.errors) {
                errorMsg = Object.values(error.errors).map(err => err[0]).join('<br>');
            }
            showToast(errorMsg, 'error');
        }
    });

    // --- Delete User Function ---
    function deleteUser(userId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this user? This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`{{ url('admin/users') }}/${userId}`, {
                        method: 'POST',
                        headers: fetchHeaders,
                        body: JSON.stringify({ _method: 'DELETE' })
                    });
                    const result = await response.json();
                    if (!response.ok) throw result;

                    showToast(result.message, 'success');
                    document.querySelector(`tr[data-user-id="${userId}"]`).remove();
                    filterUsers(); // Re-run filter to check for empty table
                } catch (error) {
                    showToast(error.message || 'Failed to delete user.', 'error');
                }
            }
        });
    }
    
    // --- Filtering Logic ---
    function filterUsers() {
        const tableBody = document.getElementById('users-table-body');
        if (!tableBody) return;
        
        const rows = tableBody.getElementsByTagName('tr');
        const nameFilter = document.getElementById('nameFilter').value.toLowerCase().trim();
        const companyFilter = document.getElementById('companyFilter').value;
        const branchFilter = document.getElementById('branchFilter').value;
        const departmentFilter = document.getElementById('departmentFilter').value;
        let hasVisibleRows = false;
        const colSpan = 9; // Number of columns in the table
        
        Array.from(rows).forEach(row => {
            const noResultCell = row.querySelector('td[colspan]');
            if (row.classList.contains('no-results-row') || (noResultCell && noResultCell.getAttribute('colspan') == colSpan)) {
                row.style.display = 'none';
                return;
            }

            const fullName = row.cells[0].textContent.toLowerCase();
            const company = row.cells[3].textContent;
            const branch = row.cells[4].textContent;
            const department = row.cells[5].textContent;
            
            const nameMatch = nameFilter === '' || fullName.includes(nameFilter);
            const companyMatch = companyFilter === '' || company === companyFilter;
            const branchMatch = branchFilter === '' || branch === branchFilter;
            const departmentMatch = departmentFilter === '' || department === departmentFilter;
            
            const matches = nameMatch && companyMatch && branchMatch && departmentMatch;
            row.style.display = matches ? '' : 'none';
            if(matches) hasVisibleRows = true;
        });

        // Show/hide "No results" message
        let noResultsRow = tableBody.querySelector('.no-results-row');
        if (!hasVisibleRows) {
            if (!noResultsRow) {
                noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                noResultsRow.innerHTML = `<td colspan="${colSpan}" class="px-6 py-4 text-center text-gray-500">No users found matching filters</td>`;
                tableBody.appendChild(noResultsRow);
            }
            noResultsRow.style.display = '';
        } else if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }

        // Handle original empty message
        const originalEmptyRow = tableBody.querySelector('td[colspan]');
        if (nameFilter === '' && companyFilter === '' && branchFilter === '' && departmentFilter === '' && !hasVisibleRows && originalEmptyRow) {
            if (!originalEmptyRow.parentElement.classList.contains('no-results-row')) {
                originalEmptyRow.parentElement.style.display = ''; // Show original "No users"
            }
        }
    }

    document.getElementById('nameFilter').addEventListener('input', filterUsers);
    document.getElementById('companyFilter').addEventListener('change', filterUsers);
    document.getElementById('branchFilter').addEventListener('change', filterUsers);
    document.getElementById('departmentFilter').addEventListener('change', filterUsers);
    
    document.getElementById('resetFilters').addEventListener('click', () => {
        document.getElementById('nameFilter').value = '';
        document.getElementById('companyFilter').value = '';
        document.getElementById('branchFilter').value = '';
        document.getElementById('departmentFilter').value = '';
        filterUsers();
    });

    // --- Initialize Country Pickers ---
    (async () => {
        await fetchCountries();
        setupCountrySearch('addCountrySearch', 'addCountry', 'addCountryCode', 'addCountryDropdown');
        setupCountrySearch('editCountrySearch', 'editCountry', 'editCountryCode', 'editCountryDropdown');
    })();
    
    // Initial filter run in case table is empty to begin with
    filterUsers();
</script>
@endpush