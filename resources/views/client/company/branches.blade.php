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

    <div class="flex flex-col mt-10 bg-white shadow-lg rounded-lg p-6">
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
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Company Branches</h2>
                @if ($isAdmin)
                <button id="add-branch-btn" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700">
                    Add New Branch
                </button>
                @endif
            </div>
            
            <!-- Branches List -->
            <div class="overflow-x-auto mb-8">
                <table class="min-w-full bg-white border border-gray-200 shadow-sm rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="table-head">Branch Location</th>
                            <th class="table-head">Address</th>
                            <th class="table-head">Phone</th>
                            <th class="table-head">Type</th>
                            @if ($isAdmin)
                            <th class="table-head">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($branches as $branch)
                        <tr class="{{ $branch->is_headquarters ? 'bg-blue-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $branch->branch_location }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $branch->branch_address }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $branch->branch_phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $branch->is_headquarters ? 'font-semibold text-blue-600' : 'text-gray-500' }}">
                                {{ $branch->is_headquarters ? 'Headquarters' : 'Branch' }}
                            </td>
                            @if ($isAdmin)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex space-x-2">
                                    <button type="button" class="edit-branch-btn text-blue-600 hover:text-blue-800"
                                            data-action="{{ route('client.company.branch.update', $branch->branch_id) }}"
                                            data-location="{{ $branch->branch_location }}"
                                            data-address="{{ $branch->branch_address }}"
                                            data-phone="{{ $branch->branch_phone }}">
                                        Edit
                                    </button>
                                    @if (!$branch->is_headquarters)
                                    <button type="button" class="delete-branch-btn text-red-600 hover:text-red-800"
                                            data-action="{{ route('client.company.branch.destroy', $branch->branch_id) }}">
                                        Delete
                                    </button>
                                    @endif
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? 5 : 4 }}" class="px-6 py-4 text-center text-sm text-gray-500">No branches added yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 text-sm text-gray-600">
                <p>The headquarters branch details are synchronized with your company profile.</p>
                <p class="mt-2">Only administrators can add, edit, or delete branches.</p>
            </div>
        @endif
    </div>
</main>

<!-- Branch Modal -->
<div id="branch-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl mx-4">
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
                <input type="text" name="branch_location" id="branch-location" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>
            
            <div>
                <label for="branch-address" class="block text-sm font-medium text-gray-700">Address</label>
                <textarea name="branch_address" id="branch-address" required class="mt-1 block w-full border border-gray-300 rounded-md p-2" rows="3"></textarea>
            </div>
            
            <div>
                <label for="branch-phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" name="branch_phone" id="branch-phone" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" id="cancel-branch-btn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md shadow-sm hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700">
                    <span id="branch-submit-text">Add Branch</span>
                </button>
            </div>
        </form>
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
    function closeModal() { branchModal.classList.add('hidden'); }

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
    window.addEventListener('click', (e) => e.target === branchModal && closeModal());

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
});
</script>
@endpush