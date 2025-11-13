<div id="addDepartmentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg w-[400px] relative">
        <div>
            <h2 class="text-xl font-semibold mb-4">Add New Department</h2>
            <button type="button" onclick="closeAddDepartmentModal()" class="text-zinc-500 rounded absolute top-0 right-0 mt-6 mr-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
            </button>
        </div>
        <form id="addDepartmentForm" action="{{ route('client.users.storeDepartment') }}" method="POST">
            @csrf
            <div class="field-group">
                <label class="block mb-2">Department Name</label>
                <input type="text" id="newDepartmentName" class="w-full border p-2 rounded mb-4" required>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeAddDepartmentModal()" class="mr-2 bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-[#1f5496] text-white px-4 py-2 rounded">Add Department</button>
            </div>
        </form>
    </div>
</div>