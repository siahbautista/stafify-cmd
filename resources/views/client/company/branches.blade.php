@extends('layouts.client-app')

@section('title', 'Branch Management')

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<main>
    <div class="page-heading">
        <p class="text-gray-600">
            @if ($firstTimeSetup)
                You need to complete your company profile setup first.
            @elseif ($companyData)
                Manage your company branches and locations.
            @else
                No company profile found. Please create your company profile first.
            @endif
        </p>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success', title: 'Success!', text: '{{ session('success') }}',
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
                });
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'error', title: 'Oops...', text: '{{ session('error') }}',
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
                });
            });
        </script>
    @endif

    <div class="bg-white p-3 sm:p-4 rounded-lg shadow-md mb-4 sm:mb-6 mt-6">
        @if (empty($companyName))
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded-md mb-6">
                You need to have a company name assigned to your account before managing branches.
            </div>
        @elseif (!$companyData)
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded-md mb-6">
                You need to create a company profile before managing branches. 
                <a href="{{ route('client.company.profile') }}" class="text-blue-600 hover:underline">Create Company Profile</a>
            </div>
        @else
            <!-- Branch Management Section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                @if ($isAdmin)
                <button id="add-branch-btn" class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Add New Branch
                </button>
                @endif
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <input type="text" id="searchInput" placeholder="Search by location" 
                           class="w-full sm:w-auto border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <!-- Branches List -->
            <div class="overflow-x-auto">
                <div class="min-w-full inline-block align-middle">
                    <table class="min-w-full divide-y divide-gray-200" id="branches-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branch Location</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                @if ($isAdmin)
                                <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="branches-table-body" class="divide-y divide-gray-200">
                            @forelse ($branches as $branch)
                            <tr class="align-middle hover:bg-gray-50 {{ $branch->is_headquarters ? 'bg-blue-50' : '' }}">
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $branch->branch_location }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $branch->branch_address }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">{{ $branch->branch_phone }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm {{ $branch->is_headquarters ? 'font-semibold text-blue-600' : 'text-gray-700' }}">
                                    @if($branch->is_headquarters)
                                        <span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-blue-200 text-blue-800">Headquarters</span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-gray-200 text-gray-800">Branch</span>
                                    @endif
                                </td>
                                @if ($isAdmin)
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-700">
                                    <div class="flex justify-center items-center space-x-2">
                                        <button type="button" class="edit-branch-btn text-blue-500 hover:text-blue-700"
                                                title="Edit Branch"
                                                data-action="{{ route('client.company.branch.update', $branch->branch_id) }}"
                                                data-location="{{ $branch->branch_location }}"
                                                data-address="{{ $branch->branch_address }}"
                                                data-phone="{{ $branch->branch_phone }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" 
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </button>
                                        @if (!$branch->is_headquarters)
                                        <button type="button" class="delete-branch-btn text-red-500 hover:text-red-700"
                                                title="Delete Branch"
                                                data-action="{{ route('client.company.branch.destroy', $branch->branch_id) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $isAdmin ? 5 : 4 }}" class="px-4 sm:px-6 py-4 text-center text-sm text-gray-500">No branches added yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-6 text-sm text-gray-600">
                <p>The headquarters branch details are synchronized with your company profile.</p>
                @if($isAdmin)
                <p class="mt-2">Only administrators can add, edit, or delete branches.</p>
                @endif
            </div>
        @endif
    </div>
</main>

<!-- Branch Modal -->
<div id="branch-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative min-h-screen px-2 sm:px-4 text-center">
        <div class="fixed inset-0" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>
        <div class="inline-block w-full max-w-md p-4 sm:p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="branch-modal-title">Add New Branch</h3>
                <button type="button" id="close-branch-modal" class="text-gray-400 hover:text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                </button>
            </div>
            
            <form action="" method="POST" id="branch-form" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" id="branch-form-method" value="POST">
                
                <div>
                    <label for="branch-location" class="block text-sm font-medium text-gray-700">Location</label>
                    <input type="text" name="branch_location" id="branch-location" required 
                           class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 mt-1">
                </div>
                
                <div>
                    <label for="branch-address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="branch_address" id="branch-address" required rows="3"
                              class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 mt-1"></textarea>
                </div>
                
                <div>
                    <label for="branch-phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="branch_phone" id="branch-phone" required 
                           class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 mt-1">
                </div>
                
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" id="cancel-branch-btn" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        <span id="branch-submit-text">Add Branch</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Delete Form -->
<form id="delete-branch-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const branchModal = document.getElementById('branch-modal');
    const addBranchBtn = document.getElementById('add-branch-btn');
    const closeBranchModal = document.getElementById('close-branch-modal');
    const cancelBranchBtn = document.getElementById('cancel-branch-btn');
    const branchForm = document.getElementById('branch-form');
    const branchFormMethod = document.getElementById('branch-form-method');
    const branchModalTitle = document.getElementById('branch-modal-title');
    const branchSubmitText = document.getElementById('branch-submit-text');
    
    const branchLocation = document.getElementById('branch-location');
    const branchAddress = document.getElementById('branch-address');
    const branchPhone = document.getElementById('branch-phone');

    function openModal() { branchModal.classList.remove('hidden'); }
    function closeModal() { 
        branchModal.classList.add('hidden'); 
        branchForm.reset();
    }

    addBranchBtn?.addEventListener('click', () => {
        branchForm.reset();
        branchForm.action = '{{ route("client.company.branch.store") }}';
        branchFormMethod.value = 'POST';
        branchModalTitle.textContent = 'Add New Branch';
        branchSubmitText.textContent = 'Add Branch';
        openModal();
    });

    closeBranchModal?.addEventListener('click', closeModal);
    cancelBranchBtn?.addEventListener('click', closeModal);
    
    // Close modal on outside click
    branchModal.addEventListener('click', (event) => {
        // Check if the click is on the modal backdrop (the div with 'fixed inset-0')
        if (event.target === branchModal) {
            closeModal();
        }
    });


    document.querySelectorAll('.edit-branch-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            branchForm.action = btn.dataset.action;
            branchFormMethod.value = 'PUT';
            branchLocation.value = btn.dataset.location;
            branchAddress.value = btn.dataset.address;
            branchPhone.value = btn.dataset.phone;
            branchModalTitle.textContent = 'Edit Branch';
            branchSubmitText.textContent = 'Update Branch';
            openModal();
        });
    });

    document.querySelectorAll('.delete-branch-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            Swal.fire({
                title: 'Are you sure?',
                text: "This branch will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const deleteForm = document.getElementById('delete-branch-form');
                    deleteForm.action = btn.dataset.action;
                    deleteForm.submit();
                }
            });
        });
    });

    // --- Filter Branches Function ---
    function filterBranches() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase().trim();
        const rows = document.getElementById('branches-table-body').getElementsByTagName('tr');
        let hasVisibleRows = false;
        const colSpan = @json($isAdmin ? 5 : 4);

        // Loop through all rows and hide/show based on filters
        Array.from(rows).forEach(row => {
            // Ensure it's not the "no results" row or the "no branches" row
            if (row.classList.contains('no-results-row') || row.querySelector('td[colspan]')) {
                row.style.display = 'none'; // Hide special rows during filtering
                return;
            }

            const locationCell = row.querySelector('td:nth-child(1)');

            if (locationCell) {
                const location = (locationCell.textContent || locationCell.innerText).toLowerCase();
                const matches = location.includes(filter);
                
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
                    <td colspan="${colSpan}" class="px-6 py-4 text-center text-gray-500">No results found</td>
                `;
                document.getElementById('branches-table-body').appendChild(noResultsRow);
            }
            noResultsRow.style.display = '';
        } else if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }

        // If no filter and no rows, show the original "No branches" message
        const originalEmptyRow = document.querySelector('td[colspan]');
        if (filter === '' && !hasVisibleRows && originalEmptyRow) {
             originalEmptyRow.style.display = '';
        }
    }

    // Add event listener for filter
    const searchInput = document.getElementById('searchInput');
    if(searchInput) {
        searchInput.addEventListener('keyup', filterBranches);
    }
});
</script>
@endpush