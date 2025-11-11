@extends('layouts.admin-app')

@section('title', 'Pending Users')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

{{-- THIS LINE IS MODIFIED --}}
<main class="flex-1 min-h-screen p-8 !overflow-y-auto lg:ml-[250px]">
    <div class="flex justify-between items-center">
        <div class="page-heading">
            {{-- This heading is now in the layout header --}}
        </div>
    </div>

    <!-- Pending Users Table -->
    <div class="flex flex-col gap-5 mt-6 bg-white shadow-md rounded-lg p-4 md:p-6 overflow-x-auto">
        <table class="w-full min-w-[1200px]">
            <thead>
                <tr>
                    <th class="table-head">Username</th>
                    <th class="table-head">Full Name</th>
                    <th class="table-head">Email</th>
                    <th class="table-head">Phone Number</th>
                    <th class="table-head">Address</th>
                    <th class="table-head">Country</th>
                    <th class="table-head">Company</th>
                    <th class="table-head">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendingUsers as $user)
                    <tr data-user-id="{{ $user->user_id }}">
                        <td class="table-data">{{ $user->user_name }}</td>
                        <td class="table-data">{{ $user->full_name }}</td>
                        <td class="table-data">{{ $user->user_email }}</td>
                        <td class="table-data">{{ $user->phone_number }}</td>
                        <td class="table-data">{{ $user->address }}</td>
                        <td class="table-data">{{ $user->country }}</td>
                        <td class="table-data">{{ $user->company }}</td>
                        <td class="table-data">
                            <div class="flex gap-3">
                                <button onclick="approveUser('{{ $user->user_id }}')" class="block w-[40px] text-left p-2 text-sm text-white bg-green-700 rounded-[5px]" title="Approve User">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                </button>
                                <button onclick="rejectUser('{{ $user->user_id }}')" class="block w-[40px] text-left p-2 text-sm text-white bg-red-700 rounded-[5px]" title="Reject User">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center pt-4 text-gray-500">No pending users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>

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
                } catch (error) {
                    showToast(error.message || 'Failed to reject user.', 'error');
                }
            }
        });
    }
</script>
@endpush