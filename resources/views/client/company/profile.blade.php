@extends('layouts.client-app')

@section('title', $firstTimeSetup ? 'Create Company Profile' : 'Company Profile')

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<main>
    <div class="page-heading flex justify-between items-center">
        <div>
            <p class="text-gray-600">
                @if ($firstTimeSetup)
                    Complete your company profile setup.
                @elseif ($companyData)
                    Update your company details.
                @else
                    Enter your company details to proceed.
                @endif
            </p>
        </div>
        
        @if ($isAdmin && !$firstTimeSetup)
        <button id="open-settings-modal" class="bg-gray-100 hover:bg-gray-200 p-2 rounded-full" title="Company Settings">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-settings"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
        </button>
        @endif
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
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col mt-10 bg-white shadow-lg rounded-lg p-6">
        @if (empty($companyName))
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded-md mb-6">
                You need to have a company name assigned to your account before creating a company profile.
            </div>
        @else
            <!-- Company Logo -->
            <div class="flex flex-col items-center space-y-4 mb-6">
                <form id="company-logo-form" class="flex flex-col gap-5 relative w-full">
                    @csrf
                    <div class="flex flex-col gap-4 w-full border border-zinc-200 p-5 rounded-[20px] bg-[#f8f9fa] lg:flex-row">
                        <div class="flex flex-col gap-2 lg:min-w-[270px] lg:w-[400px]">
                            <h3 class="text-[18px] font-bold">Company Logo</h3>
                            <p>Edit your company logo here</p>
                            
                            @if ($isAdmin)
                            <button type="button" id="edit-company-logo-btn" class="flex items-center justify-start w-[fit-content] gap-2 bg-[#1f5496] text-white px-4 py-2 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                Edit
                            </button>
                            
                            <div id="company-logo-options" class="absolute bottom-5 left-5 bg-white shadow-lg rounded-md p-2 hidden z-20">
                                <label for="company_logo_input" class="block px-4 py-2 text-gray-700 cursor-pointer hover:bg-gray-100">Upload Company Logo</label>
                                <button type="button" id="delete-company-logo" class="block px-4 py-2 text-red-600 w-full text-left hover:bg-gray-100">Delete Company Logo</button>
                            </div>
                            @endif
                        </div>
                        <div class="flex flex-col gap-2 w-full py-3 px-5 bg-[#ffffff] rounded-[10px] border border-[#f1f1f1] edit-profile items-center">
                            <div class="relative w-64 h-48 flex items-center justify-center">
                                @php
                                    $logoUrl = ($companyData && $companyData->company_logo && $companyData->company_logo !== 'default-company-logo.png')
                                        ? Storage::url($companyData->company_logo)
                                        : asset('uploads/company_logos/default-company-logo.png');
                                @endphp
                                <img id="company-logo-preview" 
                                     src="{{ $logoUrl }}" 
                                     alt="Company Logo" 
                                     class="max-w-full max-h-full object-contain rounded-lg"
                                     onerror="this.src='{{ asset('uploads/company_logos/default-company-logo.png') }}'">
                            </div>
                            <input type="file" id="company_logo_input" class="hidden" accept="image/jpeg,image/png,image/gif,image/svg+xml">
                        </div>
                    </div>
                </form>
            </div>
                                        
            <!-- Company Form -->
            <form action="{{ route('client.company.store') }}" method="POST" class="flex flex-col gap-5 relative w-full">
                @csrf
                <div class="flex flex-col gap-4 w-full border border-zinc-200 p-5 rounded-[20px] bg-[#f8f9fa] lg:flex-row">
                    <div class="flex flex-col gap-2 lg:min-w-[270px] lg:w-[400px]">
                        <h3 class="text-[18px] font-bold">Company details</h3>
                        <p>Edit company details here</p>
                    </div>
                    <div class="flex flex-col gap-4 w-full py-3 px-5 bg-[#ffffff] rounded-[10px] border border-[#f1f1f1] edit-profile">
                        <div class="field-group">
                            <label class="block text-sm font-medium text-gray-700">Company Name</label>
                            <input type="text" name="company_name" value="{{ $companyName }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 bg-gray-100" readonly>
                        </div>
                        <div class="field-group">
                            <label class="block text-sm font-medium text-gray-700">Company Address</label>
                            <input type="text" name="company_address" value="{{ old('company_address', $companyData->company_address ?? '') }}" required class="mt-1 block w-full border border-gray-300 rounded-md p-2" {{ !$isAdmin ? 'readonly' : '' }}>
                        </div>
                        <div class="field-group">
                            <label class="block text-sm font-medium text-gray-700">Company Phone</label>
                            <input type="text" name="company_phone" value="{{ old('company_phone', $companyData->company_phone ?? '') }}" required class="mt-1 block w-full border border-gray-300 rounded-md p-2" {{ !$isAdmin ? 'readonly' : '' }}>
                        </div>
                        <div class="field-group">
                            <label class="block text-sm font-medium text-gray-700">Company Email</label>
                            <input type="email" name="company_email" value="{{ old('company_email', $companyData->company_email ?? '') }}" required class="mt-1 block w-full border border-gray-300 rounded-md p-2" {{ !$isAdmin ? 'readonly' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    @if ($isAdmin)
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700">
                            {{ $firstTimeSetup ? 'Register Company' : 'Update Company Profile' }}
                        </button>
                    </div>
                    @endif
                </div>
            </form>
        @endif
    </div>

    <!-- Settings Modal -->
    @if ($isAdmin && !$firstTimeSetup)
    <div id="settings-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Company Settings</h3>
                <button id="close-settings-modal" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                </button>
            </div>
            
            <form id="company-settings-form" class="space-y-4">
                @csrf
                <div class="form-group">
                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                    <select id="timezone" name="timezone" class="w-full border border-gray-300 rounded-md p-2">
                        <option value="">Select Timezone</option>
                        {{-- Options populated by JS --}}
                    </select>
                </div>
                <div class="form-group">
                    <label for="week_start" class="block text-sm font-medium text-gray-700 mb-1">Week Starts On</label>
                    <select id="week_start" name="week_start" class="w-full border border-gray-300 rounded-md p-2">
                        <option value="0">Sunday</option>
                        <option value="1">Monday</option>
                        <option value="2">Tuesday</option>
                        <option value="3">Wednesday</option>
                        <option value="4">Thursday</option>
                        <option value="5">Friday</option>
                        <option value="6">Saturday</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="year_type" class="block text-sm font-medium text-gray-700 mb-1">Year Type</label>
                    <select id="year_type" name="year_type" class="w-full border border-gray-300 rounded-md p-2">
                        <option value="calendar">Calendar Year (Jan-Dec)</option>
                        <option value="fiscal">Fiscal Year</option>
                    </select>
                </div>
                <div id="fiscal-year-range" class="form-group hidden space-y-4">
                    <div>
                        <label for="fiscal_start_month" class="block text-sm font-medium text-gray-700 mb-1">Fiscal Year Start</label>
                        <div class="grid grid-cols-2 gap-4">
                            <select id="fiscal_start_month" name="fiscal_start_month" class="border border-gray-300 rounded-md p-2">
                                {{-- Month Options --}}
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                                @endfor
                            </select>
                            <select id="fiscal_start_day" name="fiscal_start_day" class="border border-gray-300 rounded-md p-2">
                                {{-- Day Options --}}
                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="fiscal_end_month" class="block text-sm font-medium text-gray-700 mb-1">Fiscal Year End</label>
                        <div class="grid grid-cols-2 gap-4">
                            <select id="fiscal_end_month" name="fiscal_end_month" class="border border-gray-300 rounded-md p-2">
                                {{-- Month Options --}}
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                                @endfor
                            </select>
                            <select id="fiscal_end_day" name="fiscal_end_day" class="border border-gray-300 rounded-md p-2">
                                {{-- Day Options --}}
                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const fetchHeaders = {
        "X-CSRF-TOKEN": csrfToken,
        "Accept": "application/json",
    };

    // --- Company Logo Logic ---
    const logoFileInput = document.getElementById('company_logo_input');
    const logoPreviewImage = document.getElementById('company-logo-preview');
    const logoEditButton = document.getElementById('edit-company-logo-btn');
    const logoOptions = document.getElementById('company-logo-options');
    const deleteLogoBtn = document.getElementById('delete-company-logo');

    if (logoEditButton) {
        logoEditButton.addEventListener('click', () => logoOptions.classList.toggle('hidden'));
    }
    
    if (logoFileInput) {
        logoFileInput.addEventListener('change', handleLogoUpload);
    }
    
    if (deleteLogoBtn) {
        deleteLogoBtn.addEventListener('click', handleLogoDelete);
    }

    document.addEventListener('click', (event) => {
        if (logoOptions && logoEditButton && !logoOptions.contains(event.target) && !logoEditButton.contains(event.target)) {
            logoOptions.classList.add('hidden');
        }
    });

    async function handleLogoUpload() {
        if (!this.files || !this.files[0]) return;
        const formData = new FormData();
        formData.append('company_logo', this.files[0]);

        try {
            const response = await fetch('{{ route("client.company.logo.update") }}', {
                method: 'POST',
                headers: fetchHeaders,
                body: formData
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Upload failed');

            logoPreviewImage.src = data.logo_url + '?t=' + new Date().getTime();
            showToast(data.message, 'success');
        } catch (error) {
            showToast(error.message, 'error');
        }
    }

    function handleLogoDelete() {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to remove the company logo?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch('{{ route("client.company.logo.delete") }}', {
                        method: 'POST',
                        headers: { ...fetchHeaders, 'Content-Type': 'application/json' },
                    });
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'Delete failed');

                    logoPreviewImage.src = "{{ asset('uploads/company_logos/default-company-logo.png') }}";
                    showToast(data.message, 'success');
                } catch (error) {
                    showToast(error.message, 'error');
                }
                logoOptions.classList.add('hidden');
            }
        });
    }

    // --- Settings Modal Logic ---
    const settingsModal = document.getElementById('settings-modal');
    const openSettingsBtn = document.getElementById('open-settings-modal');
    const closeSettingsBtn = document.getElementById('close-settings-modal');
    const yearTypeSelect = document.getElementById('year_type');
    const fiscalYearRange = document.getElementById('fiscal-year-range');
    const companySettingsForm = document.getElementById('company-settings-form');
    const timezoneSelect = document.getElementById('timezone');

    const currentSettings = @json($companyData ?? null);

    if (openSettingsBtn) {
        openSettingsBtn.addEventListener('click', () => settingsModal.classList.remove('hidden'));
    }
    
    if (closeSettingsBtn) {
        closeSettingsBtn.addEventListener('click', () => settingsModal.classList.add('hidden'));
    }
    
    if (settingsModal) {
        settingsModal.addEventListener('click', (e) => e.target === settingsModal && settingsModal.classList.add('hidden'));
    }
    
    if (yearTypeSelect) {
        yearTypeSelect.addEventListener('change', function() {
            if (fiscalYearRange) {
                fiscalYearRange.classList.toggle('hidden', this.value !== 'fiscal');
            }
        });
    }

    if (companySettingsForm) {
        loadSettings();
        companySettingsForm.addEventListener('submit', handleSettingsSubmit);
    }

    function loadSettings() {
        if (!currentSettings) return;
        
        const weekStartSelect = document.getElementById('week_start');
        const yearTypeSelectEl = document.getElementById('year_type');
        
        if (weekStartSelect) {
            weekStartSelect.value = currentSettings.week_start || '0';
        }
        
        if (yearTypeSelectEl) {
            yearTypeSelectEl.value = currentSettings.year_type || 'calendar';
            if (currentSettings.year_type === 'fiscal' && fiscalYearRange) {
                fiscalYearRange.classList.remove('hidden');
                
                const fiscalStartMonth = document.getElementById('fiscal_start_month');
                const fiscalStartDay = document.getElementById('fiscal_start_day');
                const fiscalEndMonth = document.getElementById('fiscal_end_month');
                const fiscalEndDay = document.getElementById('fiscal_end_day');
                
                if (fiscalStartMonth) fiscalStartMonth.value = currentSettings.fiscal_start_month || '1';
                if (fiscalStartDay) fiscalStartDay.value = currentSettings.fiscal_start_day || '1';
                if (fiscalEndMonth) fiscalEndMonth.value = currentSettings.fiscal_end_month || '12';
                if (fiscalEndDay) fiscalEndDay.value = currentSettings.fiscal_end_day || '31';
            }
        }
        
        // Populate Timezones
        if (timezoneSelect) {
            populateTimezones().then(() => {
                if (currentSettings.timezone) {
                    timezoneSelect.value = currentSettings.timezone;
                }
            }).catch(err => {
                console.error('Timezone loading error:', err);
            });
        }
    }

    async function handleSettingsSubmit(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const response = await fetch('{{ route("client.company.settings.update") }}', {
                method: 'POST',
                headers: fetchHeaders,
                body: formData
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Failed to save settings');

            showToast(data.message, 'success');
            if (settingsModal) {
                settingsModal.classList.add('hidden');
            }
            
            // Update currentSettings object
            if (currentSettings) {
                Object.assign(currentSettings, Object.fromEntries(formData.entries()));
            }
            
            // Reload page to reflect new settings
            setTimeout(() => window.location.reload(), 1500);
        } catch (error) {
            showToast(error.message, 'error');
        }
    }

    async function populateTimezones() {
        if (!timezoneSelect) return;
        
        try {
            // Use a static list of common timezones instead of API call
            const timezones = [
                'UTC',
                'America/New_York',
                'America/Chicago',
                'America/Denver',
                'America/Los_Angeles',
                'Europe/London',
                'Europe/Paris',
                'Europe/Berlin',
                'Asia/Tokyo',
                'Asia/Shanghai',
                'Asia/Hong_Kong',
                'Asia/Singapore',
                'Asia/Dubai',
                'Asia/Kolkata',
                'Asia/Manila',
                'Australia/Sydney',
                'Pacific/Auckland'
            ];
            
            timezoneSelect.innerHTML = '<option value="">Select Timezone</option>';
            
            timezones.forEach(tz => {
                const option = document.createElement('option');
                option.value = tz;
                option.textContent = tz.replace(/_/g, ' ');
                timezoneSelect.appendChild(option);
            });
        } catch (e) {
            console.error('Timezone population error:', e);
            // Silently fail - don't show error toast for timezone loading
            // Just set a default timezone option
            timezoneSelect.innerHTML = '<option value="UTC">UTC (Default)</option>';
        }
    }

    // --- Toast Function ---
    function showToast(message, type = 'success') {
        if (typeof Swal !== 'undefined') {
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
    }
});
</script>
@endpush