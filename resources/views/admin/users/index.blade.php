@extends('layouts.admin-app')

@section('title', 'All Users')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

{{-- THIS LINE IS MODIFIED --}}
<main class="flex-1 min-h-screen p-8 !overflow-y-auto lg:ml-[250px]">
    <div class="flex justify-between items-center">
        <div class="page-heading">
            {{-- This heading is now in the layout header --}}
        </div>
        <div class="flex gap-4 items-center">
            <button onclick="openAddUserModal()" class="hover:bg-[#1f5496] text-[#1f5496] hover:text-white bg-[#DDE5EF] p-2 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M16 19h6" /><path d="M19 16v6" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /></svg>
            </button>
            {{-- Sync button removed as requested --}}
        </div>
    </div>
    
    <div class="flex flex-col gap-2 mt-6 bg-white shadow-md rounded-lg p-4 md:p-6">
        <div class="flex justify-between gap-5">
            <h2 class="text-lg font-semibold mb-4">Filter Active Users</h2>
            <button id="resetFilters" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
                Reset Filters
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block mb-2 text-sm font-medium">Search by Name</label>
                <input type="text" id="nameFilter" placeholder="Enter name" class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">Company</label>
                <select id="companyFilter" class="w-full border p-[0.58rem] rounded">
                    <option value="">All Companies</option>
                    @foreach ($companies->whereNotNull() as $company)
                        <option value="{{ $company }}">{{ $company }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">Branch Location</label>
                <select id="branchFilter" class="w-full border p-[0.58rem] rounded">
                    <option value="">All Branches</option>
                    @foreach ($branches->whereNotNull() as $branch)
                        <option value="{{ $branch }}">{{ $branch }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">Department</label>
                <select id="departmentFilter" class="w-full border p-[0.58rem] rounded">
                    <option value="">All Departments</option>
                    @foreach ($departments->whereNotNull() as $department)
                        <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- User Table -->
    <div class="mt-6 bg-white shadow-md rounded-lg p-4 md:p-6 overflow-x-auto">
        <table class="w-full min-w-[1200px]">
            <thead>
                <tr>
                    <th class="table-head">Full Name</th>
                    <th class="table-head">Email</th>
                    <th class="table-head">Phone Number</th>
                    <th class="table-head">Company</th>
                    <th class="table-head">Branch Location</th>
                    <th class="table-head">Department</th>
                    <th class="table-head">Position</th>
                    <th class="table-head">Access Level</th>
                    <th class="table-head">Actions</th>
                </tr>
            </thead>
            <tbody id="users-table-body">
                @foreach ($users as $user)
                    <tr data-user-id="{{ $user->user_id }}">
                        <td class="table-data">{{ $user->full_name }}</td>
                        <td class="table-data">{{ $user->user_email }}</td>
                        <td class="table-data">{{ $user->phone_number }}</td>
                        <td class="table-data">{{ $user->company }}</td>
                        <td class="table-data">{{ $user->branch_location }}</td>
                        <td class="table-data">{{ $user->user_dept }}</td>
                        <td class="table-data">{{ $user->user_position }}</td>
                        <td class="table-data text-center">
                          {{ $user->access_level == 1 ? 'Creator' : ($user->access_level == 2 ? 'Client' : 'Basic User') }}
                        </td>
                        <td class="table-data text-center">
                            <div class="flex gap-3 items-center">
                                <button 
                                    @if ($user->drive_folder_link)
                                        onclick="window.open('{{ $user->drive_folder_link }}', '_blank')"
                                    @else
                                        onclick="Swal.fire({icon: 'warning', title: 'No Drive Folder', text: 'No drive folder found for this user.', confirmButtonColor: '#4285F4'})"
                                    @endif
                                    class="block w-[40px] text-left p-2 text-sm text-white bg-[#4285F4] rounded-[5px]" 
                                    title="Open Google Drive Folder">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-folder"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" /></svg>
                                </button>
                                                              
                                <button onclick='openEditModal(@json($user))' class="block w-[40px] text-left p-2 text-sm text-white bg-[#1f5496] rounded-[5px]" title="Edit User">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                </button>
                                
                                <button onclick="deleteUser('{{ $user->user_id }}')" class="block w-[40px] text-left p-2 text-white text-sm bg-red-700 rounded-[5px]" title="Delete User">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>

@include('admin.users._edit-modal')
@include('admin.users._add-modal')

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
        document.getElementById('addUserForm').reset();
        document.getElementById('addUserModal').classList.remove('hidden');
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
                errorMsg = Object.values(error.errors)[0][0];
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
                errorMsg = Object.values(error.errors)[0][0];
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
                } catch (error) {
                    showToast(error.message || 'Failed to delete user.', 'error');
                }
            }
        });
    }
    
    // --- Filtering Logic ---
    function filterUsers() {
        const rows = document.querySelectorAll('#users-table-body tr');
        const nameFilter = document.getElementById('nameFilter').value.toLowerCase().trim();
        const companyFilter = document.getElementById('companyFilter').value;
        const branchFilter = document.getElementById('branchFilter').value;
        const departmentFilter = document.getElementById('departmentFilter').value;
        
        rows.forEach(row => {
            const fullName = row.cells[0].textContent.toLowerCase();
            const company = row.cells[3].textContent;
            const branch = row.cells[4].textContent;
            const department = row.cells[5].textContent;
            
            const nameMatch = nameFilter === '' || fullName.includes(nameFilter);
            const companyMatch = companyFilter === '' || company === companyFilter;
            const branchMatch = branchFilter === '' || branch === branchFilter;
            const departmentMatch = departmentFilter === '' || department === departmentFilter;
            
            row.style.display = (nameMatch && companyMatch && branchMatch && departmentMatch) ? '' : 'none';
        });
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
</script>
@endpush