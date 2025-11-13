<div id="addUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-10">
    <div class="bg-white p-6 rounded-lg h-[90vh] relative overflow-y-auto">
        <div>
            <h2 class="text-2xl font-semibold mb-4">Add User</h2>
            <button type="button" onclick="closeAddUserModal()" class="text-zinc-500 rounded absolute top-0 right-0 mt-7 mr-5">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
            </button>
        </div>
        <style>
            .edit-profile .field-group label { color: #575757; }
        </style>
        <form id="addUserForm" action="{{ route('client.users.store') }}" method="post" class="flex flex-col gap-5">
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
                        <input type="email" name="user_email" class="w-full border p-2 rounded mb-2" required placeholder="e.g. john@example.com" autocomplete="email">
                    </div>
                    <div class="field-group">
                        <label class="block mb-2">Phone Number</label>
                        <input type="text" name="phone_number" class="w-full border p-2 rounded mb-2" placeholder="e.g. +63 912 345 6789">
                    </div>
                    <div class="field-group">
                        <label class="block mb-2">Address</label>
                        <input type="text" name="address" class="w-full border p-2 rounded mb-2" placeholder="e.g. 123 Main St, City">
                    </div>
                    <div class="field-group">
                        <label class="block mb-2">Country</label>
                        <div class="relative">
                            <input type="text" id="addCountrySearch" class="w-full border p-2 rounded mb-2" placeholder="Search country..." autocomplete="off">
                            <input type="hidden" name="country" id="addCountry">
                            <input type="hidden" name="country_code" id="addCountryCode">
                            <div id="addCountryDropdown" class="hidden absolute z-10 w-full bg-white max-h-40 overflow-y-auto border border-gray-300 rounded-md shadow-lg"></div>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="block mb-2">PIN</label>
                        <input type="text" name="user_pin" class="w-full border p-2 rounded mb-2" placeholder="e.g. 000000" autocomplete="pin">
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col gap-4 w-full border border-zinc-200 p-5 rounded-[20px] bg-[#f8f9fa] lg:flex-row">
                <div class="flex flex-col gap-2 lg:min-w-[270px] lg:w-[400px]">
                    <h3 class="text-[18px] font-bold">Password</h3>
                    <p>Set a password for this user.</p>
                </div>
                <div class="flex flex-col gap-2 w-full py-3 px-5 bg-[#ffffff] rounded-[10px] border border-[#f1f1f1] edit-profile">
                    <div class="field-group">
                        <label class="block mb-2">Password</label>
                        <input type="password" name="user_password" class="w-full border p-2 rounded mb-2" required placeholder="Enter the password" autocomplete="new-password">
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
                        <input type="text" name="company" class="w-full border p-2 rounded mb-2 bg-gray-100 text-gray-500" required value="{{ $companyName }}" readonly>
                    </div>
                    <div class="field-group">
                        <label class="block mb-2">Branch Location</label>
                        <select name="branch_location" class="w-full border p-2 rounded mb-2">
                            <option value="">Select Branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->branch_location }}">{{ $branch->branch_location }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field-group">
                        <label class="block mb-2">Department</label>
                        <div class="relative">
                            <div class="flex">
                                <select name="user_dept" class="w-full border p-2 rounded-r-none rounded-l mb-2">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->department_name }}">{{ $dept->department_name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openAddDepartmentModal()" class="bg-gray-200 border border-l-0 rounded-l-none rounded-r p-2 mb-2 flex items-center justify-center" title="Add New Department">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                   <div class="field-group">
                        <label class="block mb-2">Position</label>
                        <div class="relative">
                            <div class="flex">
                                <select name="user_position" class="w-full border p-2 rounded-r-none rounded-l mb-2">
                                    <option value="">Select Position</option>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->position_name }}">{{ $position->position_name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openAddPositionModal()" class="bg-gray-200 border border-l-0 rounded-l-none rounded-r p-2 mb-2 flex items-center justify-center" title="Add New Position">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="block mb-2">Employment Date</label>
                        <input type="date" name="employment_date" class="w-full border p-2 rounded mb-2">
                    </div>
                    <div class="field-group">
                        <label class="block mb-2">Access Level</label>
                        <select name="access_level" class="w-full border p-2 rounded mb-2" required>
                            <option value="" disabled selected>Select Access Level</option>
                            <option value="2">Admin</option>
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