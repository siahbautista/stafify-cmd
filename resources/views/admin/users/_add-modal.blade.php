<!-- Add User Modal -->
<div id="addUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-10">
    <div class="bg-white p-6 rounded-lg h-[90vh] w-[100%] max-w-4xl relative overflow-y-auto">
        <div>
            <h2 class="text-2xl font-semibold mb-4">Add User</h2>
            <button type="button" onclick="closeAddUserModal()" class="text-zinc-500 rounded absolute top-0 right-0 mt-7 mr-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
            </button>
        </div>
        <form id="addUserForm" action="{{ route('admin.users.store') }}" method="post" class="flex flex-col gap-5">
            @csrf
            <div class="flex flex-col gap-4 w-full border border-zinc-200 p-5 rounded-[20px] bg-[#f8f9fa] lg:flex-row">
                <div class="flex flex-col gap-2 lg:min-w-[270px] lg:w-[400px]">
                    <h3 class="text-[18px] font-bold">Personal Information</h3>
                    <p>Add personal details here</p>
                </div>
                <div class="flex flex-col gap-2 w-full py-3 px-5 bg-[#ffffff] rounded-[10px] border border-[#f1f1f1] edit-profile">
                    <div class="field-group">
                        <label class="block mb-2">Username</label>
                        <input type="text" name="user_name" class="w-full border p-2 rounded mb-2" required placeholder="e.g. johndoe123">
                    </div>
                    <div class="field-group">
                        <label class="block mb-2">Full Name</label>
                        <input type="text" name="full_name" class="w-full border p-2 rounded mb-2" required placeholder="e.g. John Doe">
                    </div>
                    <div class="field-group">
                        <label class="block mb-2">Email</label>
                        <input type="email" name="user_email" class="w-full border p-2 rounded mb-2" required placeholder="e.g. john@example.com">
                    </div>
                    <div class="field-group">
                        <label class="block mb-2">Phone Number</label>
                        <input type="text" name="phone_number" class="w-full border p-2 rounded mb-2" placeholder="e.g. +63 912 345 6789">
                    </div>
                    <div class="field-group">
                        <label class="block mb-2">PIN</label>
                        <input type="text" name="user_pin" class="w-full border p-2 rounded mb-2" placeholder="e.g. 000000">
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col gap-4 w-full border border-zinc-200 p-5 rounded-[20px] bg-[#f8f9fa] lg:flex-row">
                <div class="flex flex-col gap-2 lg:min-w-[270px] lg:w-[400px]">
                    <h3 class="text-[18px] font-bold">Password</h3>
                    <p>Set a temporary password for this user.</p>
                </div>
                <div class="flex flex-col gap-2 w-full py-3 px-5 bg-[#ffffff] rounded-[10px] border border-[#f1f1f1] edit-profile">
                    <div class="field-group">
                        <label class="block mb-2">Temporary Password</label>
                        <input type="password" name="user_password" class="w-full border p-2 rounded mb-2" required placeholder="Enter the temporary password">
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col gap-4 w-full border border-zinc-200 p-5 rounded-[20px] bg-[#f8f9fa] lg:flex-row">
                <div class="flex flex-col gap-2 lg:min-w-[270px] lg:w-[400px]">
                    <h3 class="text-[18px] font-bold">Company Information</h3>
                    <p>Provide company-related details to set up the user's profile accurately.</p>
                </div>
                <div class="flex flex-col gap-2 w-full py-3 px-5 bg-[#ffffff] rounded-[10px] border border-[#f1f1f1] edit-profile">
                    <div class="field-group">
                        <label class="block mb-2">Company</label>
                        <input type="text" name="company" class="w-full border p-2 rounded mb-2" required placeholder="e.g. ABC Company">
                    </div>
                    <div class="field-group">
                        <label class="block mb-2">Department</label>
                        <input type="text" name="user_dept" class="w-full border p-2 rounded mb-2" placeholder="e.g. Human Resources, IT, Marketing">
                    </div>
                    <div class="field-group">
                        <label class="block mb-2">Position</label>
                        <input type="text" name="user_position" class="w-full border p-2 rounded mb-2" placeholder="e.g. Software Engineer, Manager">
                    </div>
                    <div class="field-group">
                        <label class="block mb-2">Access Level</label>
                        <select name="access_level" class="w-full border p-2 rounded mb-2" required>
                            <option value="2">Client</option>
                            <option value="3">Basic User</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-[#1f5496] text-white px-4 py-2 rounded">Add User</button>
            </div>
        </form>
    </div>
</div>