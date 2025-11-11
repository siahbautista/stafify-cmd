@extends('layouts.app')

@section('title', 'Payout Reports')
@section('description', 'Generate and manage employee payout reports and payroll summaries.')

@section('content')
<div class="rounded-shadow-box">  
    <div class="payroll-wrapper">
        <!-- Tab Buttons -->
        <div class="flex flex-col gap-5">
            <div class="tabs-container">
                <!-- Settings Button -->
                
            </div>
            <div class="quick-create-container" style="display: flex; justify-content: flex-end; margin-bottom: 16px;">
                <button id="quick-create-payroll-btn" onclick="quickCreatePayroll()" 
                    style="background: var(--primary-color, #3B82F6); color: white; border: none; border-radius: 100%; width: 60px; height: 60px; justify-content: center; align-items: center; font-size: 16px; font-weight: 600; cursor: pointer; display: flex; transition: all 0.2s; position: fixed; bottom: 32px; right: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"
                    onmouseover="this.style.backgroundColor='#1D4ED8'"
                    onmouseout="this.style.backgroundColor='var(--primary-color, #3B82F6)'"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                </button>
            </div>
            <!-- <div class="quick-create-container" style="display: flex; justify-content: flex-end; margin-bottom: 16px;">
                <button id="quick-create-payroll-btn" onclick="quickCreatePayroll()" 
                    style="background: var(--primary-color, #3B82F6); color: white; border: none; border-radius: 8px; padding: 12px 24px; font-size: 16px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;"
                    onmouseover="this.style.backgroundColor='#1D4ED8'"
                    onmouseout="this.style.backgroundColor='var(--primary-color, #3B82F6)'"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    <span>Create New Payroll</span>
                </button>
            </div> -->
            <div id="payroll-container" class="card-container">
                <div id="create-card" class="card create" onclick="openPopup('payroll')"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-file-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                        <path d="M12 11l0 6" />
                        <path d="M9 14l6 0" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="payroll-paginator-container" class="paginator-container">
    <div id="payroll-paginator-label"></div>

    <div class="btn-container">
        <button onclick="first()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevrons-left">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M11 7l-5 5l5 5" />
                <path d="M17 7l-5 5l5 5" />
            </svg>
        </button>
        <button onclick="paginatorBack()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M15 6l-6 6l6 6" />
            </svg>
        </button>
        <button onclick="paginatorNext()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevron-right">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M9 6l6 6l-6 6" />
            </svg>
        </button>
        <button onclick="last()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevrons-right">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M7 7l5 5l-5 5" />
                <path d="M13 7l5 5l-5 5" />
            </svg>
        </button>
    </div>
</div>

<!-- Modal Overlay -->
<div id="payroll-settings-overlay" class="modal-overlay" onclick="togglePayrollSettings(false)" style="display: none;"></div>

<!-- Payroll Settings Modal -->
<div id="payroll-settings" class="settings-modal" style="display: none;">
    <!-- Close Button -->
    <button 
        onclick="togglePayrollSettings(false)" 
        style="position: absolute; top: 16px; right: 16px; background: none; border: none; color: #6B7280; cursor: pointer; font-size: 24px; z-index: 1;"
    >
        ×
    </button>

    <div style="padding: 32px;">
        <div style="font-size: 24px; font-weight: bold; text-align: center; color: #1F2937; margin-bottom: 32px;">
            Payroll Settings
        </div>

        <form id="payroll-settings-form">
            <!-- Top Row: Payroll Frequency and Deduction Schedule -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <!-- Payroll Frequency -->
                <div>
                    <div style="font-size: 18px; font-weight: 600; color: #374151; margin-bottom: 12px;">
                        Payroll Frequency
                    </div>
                    <select 
                        name="frequency" 
                        id="frequency-select"
                        style="width: 100%; padding: 12px; border: 2px solid #D1D5DB; border-radius: 8px; background: white; font-size: 16px; color: #374151; cursor: pointer;"
                    >
                        <option value="0">Select frequency</option>
                        <option value="5">Weekly</option>
                        <option value="2">Bi-Weekly</option>
                        <option value="1">Monthly</option>
                    </select>
                </div>

                <!-- Deduction Schedule -->
                <div>
                    <div style="font-size: 18px; font-weight: 600; color: #374151; margin-bottom: 12px;">
                        Deduction Schedule
                    </div>
                    <select 
                        name="deduction_schedule" 
                        id="deduction-schedule-select"
                        style="width: 100%; padding: 12px; border: 2px solid #D1D5DB; border-radius: 8px; background: white; font-size: 16px; color: #374151; cursor: pointer;"
                    >
                        <option value="">Select schedule</option>
                        <option value="weekly">Weekly</option>
                        <option value="bi-weekly">Bi-Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
            </div>

            <!-- Disbursement Date -->
            <div style="margin-bottom: 32px;">
                <div style="font-size: 18px; font-weight: 600; color: #374151; margin-bottom: 12px;">
                    Disbursement Date
                </div>
                <select 
                    name="disbursement" 
                    id="disbursement-select"
                    style="width: 100%; padding: 12px; border: 2px solid #D1D5DB; border-radius: 8px; background: white; font-size: 16px; color: #374151; cursor: pointer;"
                >
                    <option value="">Select input</option>
                    <option value="0">Last payroll cycle</option>
                    <option value="1">Day after payroll cycle</option>
                    <option value="2">2 days after payroll cycle</option>
                </select>
            </div>

            <!-- Update Button -->
            <div style="text-align: center;">
                <button 
                    type="button" 
                    onclick="updatePayrollSettings()"
                    style="background: var(--primary-color, #3B82F6); color: white; font-size: 16px; font-weight: bold; padding: 12px 32px; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s;"
                    onmouseover="this.style.backgroundColor='#1D4ED8'"
                    onmouseout="this.style.backgroundColor='var(--primary-color, #3B82F6)'"
                >
                    Update
                </button>
            </div>
        </form>        
    </div>
</div>
@endsection

<link rel="stylesheet" href="{{ asset('css/payroll.css') }}">
<style>
    
/* Kebab Menu Container */
.kebab-menu-container {
    position: relative; 
    z-index: 10;
}

.kebab-dropdown {
    position: absolute;
    top: 30px;
    width: 150px;
    right: 0;
    background: #fff;
    border-radius: 10px;
    transform: translateY(-10px);
    transition: all 0.3s ease-in-out;
    visibility: hidden;
    opacity: 0;
}

a.kebab-dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 20px;
}
.kebab-dropdown-item:hover {
    background: #f3f4f6;
}
/* The 3-dot button */
.kebab-button {
    position: relative;
    background: rgba(255, 255, 255, 0.2); 
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
    z-index: 50;
}
.kebab-button:hover {
    background: rgba(255, 255, 255, 0.4); 
}
.kebab-button svg {
    width: 20px;
    height: 20px;
    color: #ffffff; 
}
/* The Dropdown Menu (hidden by default) */


/* The items inside the dropdown */
.kebab-dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 16px;
    font-size: 14px;
    color: #374151;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.2s;
}
.kebab-dropdown-item:hover {
    background: #f3f4f6; /* Light gray on hover */
}
.kebab-dropdown-item svg {
    width: 18px;
    height: 18px;
    stroke-width: 2;
}
/* Optional: Style for delete */
.kebab-dropdown-item.delete {
    color: #EF4444; /* Red color for delete */
}
.kebab-dropdown-item.delete:hover {
    background: #FEF2F2;
}
</style>

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SVG Icon Constants -->
<script>
const SEARCH_SVG = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>`;

const SETTINGS_SVG = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-settings"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>`;

const EDIT_SVG = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>`;

const TRASH_SVG = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>`;

// SweetAlert Templates
const SWAL_PAYROLL_TEMPLATE = `
<div class="input-row">
    <label for="Name" class="swal2-label">Payroll Name: </label>
    <input id="swal-input1" class="swal2-input" placeholder="Payroll Name">
</div>

<div class="input-row">
    <label for="Frequency" class="swal2-label">Payout Frequency: </label>
    <select id="swal-input2" class="swal2-select">
    <option value="">--Select--</option>
    <option value="5">Weekly</option>
    <option value="2">Bi-Weekly</option>
    <option value="1">Monthly</option>
    </select>
</div>
`;

const SWAL_PAYROLL_CATEGORY_TEMPLATE = `
    <input id="swal-input1" class="swal2-input" placeholder="Category Name">
`;
</script>

<!-- SweetAlert Functions -->
<script src="{{ asset('js/swal-functions.js') }}"></script>

<!-- Payroll Scripts -->
<script src="{{ asset('js/payroll-scripts.js') }}"></script>

<!-- Payroll Settings Scripts -->
<script src="{{ asset('js/payroll-settings.js') }}"></script>
@endpush

