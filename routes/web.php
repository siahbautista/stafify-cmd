<?php

use Illuminate\Support\Facades\Route;

// --- Controller Imports ---

// Auth
use App\Http\Controllers\AuthController;

// HRIS Controllers
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TimeTrackingController;
use App\Http\Controllers\ShiftManagementController;
use App\Http\Controllers\ShiftAdjustmentsController;
use App\Http\Controllers\HrToolkitController;
use App\Http\Controllers\BenefitsAndTaxesController;
use App\Http\Controllers\PayoutReportsController;
use App\Http\Controllers\LegalComplianceController;
use App\Http\Controllers\WorkforceRecordsController;
use App\Http\Controllers\TalentAcquisitionController;
use App\Http\Controllers\TalentToolkitController;

// UMS Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;

// UMS Client Controllers
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\CompanyController;
use App\Http\Controllers\Client\UserController as ClientUserController; // <-- ADD THIS

// Shared Controllers
use App\Http\Controllers\ProfileController;


// --- Route Definitions ---

// Make login the landing page
Route::get('/', [AuthController::class, 'showLogin'])->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========================================================
// UMS Admin Routes (Access Level 1)
// ========================================================
Route::middleware(['auth', 'admin.access'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    Route::get('/pending-users', [UserController::class, 'pending'])->name('users.pending');
    Route::post('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');

});

// ========================================================
// UMS Client Routes (Access Level 2)
// ========================================================
Route::middleware(['auth', 'client.access'])->prefix('client')->name('client.')->group(function () {
    
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
    
    // This re-uses the global profile controller, but gives it a unique route name
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    // --- Company & Branches ---
    Route::prefix('company')->name('company.')->group(function() {
        // Company Profile
        Route::get('/profile', [CompanyController::class, 'showProfile'])->name('profile');
        Route::post('/profile', [CompanyController::class, 'createOrUpdateProfile'])->name('store');
        
        // Company Logo
        Route::post('/logo-update', [CompanyController::class, 'updateLogo'])->name('logo.update');
        Route::post('/logo-delete', [CompanyController::class, 'deleteLogo'])->name('logo.delete');
        
        // Company Settings
        Route::post('/settings-update', [CompanyController::class, 'updateSettings'])->name('settings.update');

        // Branch Management
        Route::get('/branches', [CompanyController::class, 'showBranches'])->name('branches');
        Route::post('/branches', [CompanyController::class, 'storeBranch'])->name('branch.store');
        Route::put('/branches/{branchId}', [CompanyController::class, 'updateBranch'])->name('branch.update');
        Route::delete('/branches/{branchId}', [CompanyController::class, 'destroyBranch'])->name('branch.destroy');
    });

    // --- User Management (Manage Employees) ---
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [ClientUserController::class, 'index'])->name('index');
        Route::post('/', [ClientUserController::class, 'store'])->name('store');
        Route::put('/{user}', [ClientUserController::class, 'update'])->name('update');
        Route::delete('/{user}', [ClientUserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/promote', [ClientUserController::class, 'promote'])->name('promote');
        Route::post('/{user}/demote', [ClientUserController::class, 'demote'])->name('demote');

        // Routes for adding departments/positions from the user modal
        Route::post('/store-department', [ClientUserController::class, 'storeDepartment'])->name('storeDepartment');
        Route::post('/store-position', [ClientUserController::class, 'storePosition'])->name('storePosition');
    });
});


// ========================================================
// Authenticated Routes (All Levels)
// ========================================================
Route::middleware('auth')->group(function () {
    
    // --- HRIS Dashboard (For level 3+ or all auth users) ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // --- Shared Profile Routes ---
    // Note: The '/profile' route is defined here to be accessible by all roles
    // The specific admin/client prefix routes point to the same controller but use different names
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/details', [ProfileController::class, 'updateDetails'])->name('profile.updateDetails');
    Route::post('/profile/picture', [ProfileController::class, 'updatePicture'])->name('profile.updatePicture');
    Route::post('/profile/picture/delete', [ProfileController::class, 'deletePicture'])->name('profile.deletePicture');

    // --- Pending Account Page ---
    Route::get('/pending', function () {
        return view('pending');
    })->name('pending');
    
    // --- HRIS Attendance Routes ---
    Route::get('/time-tracking', [TimeTrackingController::class, 'index'])->name('time-tracking');
    Route::post('/time-tracking/clock-in', [TimeTrackingController::class, 'clockIn'])->name('time-tracking.clock-in');
    Route::post('/time-tracking/clock-out', [TimeTrackingController::class, 'clockOut'])->name('time-tracking.clock-out');
    
    Route::get('/shift-management', [ShiftManagementController::class, 'index'])->name('shift-management');
    Route::post('/shift-management/assign-shifts', [ShiftManagementController::class, 'assignShifts'])->name('shift-management.assign-shifts');
    Route::post('/shift-management/add-event', [ShiftManagementController::class, 'addEvent'])->name('shift-management.add-event');
    Route::post('/shift-management/save-settings', [ShiftManagementController::class, 'saveSettings'])->name('shift-management.save-settings');
    
    Route::get('/shift-adjustments', [ShiftAdjustmentsController::class, 'index'])->name('shift-adjustments');
    Route::post('/shift-adjustments/request-overtime', [ShiftAdjustmentsController::class, 'requestOvertime'])->name('shift-adjustments.request-overtime');
    Route::post('/shift-adjustments/update-status', [ShiftAdjustmentsController::class, 'updateOvertimeStatus'])->name('shift-adjustments.update-status');
    
    // --- HRIS HR Management Routes ---
    Route::get('/talent-acquisition', [TalentAcquisitionController::class, 'index'])->name('talent-acquisition.index');
    Route::post('/talent-acquisition-toolkit', [TalentAcquisitionController::class, 'store'])->name('talent-acquisition-toolkit.store');
    Route::put('/talent-acquisition-toolkit/{id}', [TalentAcquisitionController::class, 'update'])->name('talent-acquisition-toolkit.update');
    
    Route::get('/talent-management', [TalentToolkitController::class, 'index'])->name('talent-management');
    Route::post('/talent-toolkit', [TalentToolkitController::class, 'store'])->name('talent-toolkit.store');
    Route::put('/talent-toolkit/{id}', [TalentToolkitController::class, 'update'])->name('talent-toolkit.update');
    
    Route::get('/hr-toolkit', [HrToolkitController::class, 'index'])->name('hr-toolkit');
    Route::post('/hr-toolkit', [HrToolkitController::class, 'store'])->name('hr-toolkit.store');
    Route::put('/hr-toolkit/{id}', [HrToolkitController::class, 'update'])->name('hr-toolkit.update');
    
    Route::get('/benefits-and-taxes', [BenefitsAndTaxesController::class, 'index'])->name('benefits-and-taxes');
    Route::post('/benefits-and-taxes', [BenefitsAndTaxesController::class, 'store'])->name('benefits-and-taxes.store');
    Route::put('/benefits-and-taxes/{id}', [BenefitsAndTaxesController::class, 'update'])->name('benefits-and-taxes.update');
    
    Route::get('/payout-reports', [PayoutReportsController::class, 'index'])->name('payout-reports');
    Route::any('/api/payroll', [PayoutReportsController::class, 'api'])->name('api.payroll');
    
    Route::get('/workforce-records', [WorkforceRecordsController::class, 'index'])->name('workforce-records');
    
    // --- HRIS API Routes ---
    Route::get('/api/workforce-records/user-rates', [WorkforceRecordsController::class, 'getUserRates'])->name('api.workforce-records.user-rates');
    Route::post('/api/workforce-records/user-rates', [WorkforceRecordsController::class, 'updateUserRates'])->name('api.workforce-records.update-user-rates');
    Route::get('/api/workforce-records/user-settings', [WorkforceRecordsController::class, 'getUserSettings'])->name('api.workforce-records.user-settings');
    Route::post('/api/workforce-records/user-settings', [WorkforceRecordsController::class, 'updateUserSettings'])->name('api.workforce-records.update-user-settings');
    Route::get('/api/workforce-records/user-files', [WorkforceRecordsController::class, 'getUserFiles'])->name('api.workforce-records.user-files');
    Route::get('/api/workforce-records/fringe-benefits', [WorkforceRecordsController::class, 'getFringeBenefits'])->name('api.workforce-records.fringe-benefits');
    Route::post('/api/workforce-records/fringe-benefits', [WorkforceRecordsController::class, 'updateFringeBenefits'])->name('api.workF-records.update-fringe-benefits');
    Route::get('/api/workforce-records/deminimis-benefits', [WorkforceRecordsController::class, 'getDeMinimisBenefits'])->name('api.workforce-records.deminimis-benefits');
    Route::post('/api/workforce-records/deminimis-benefits', [WorkforceRecordsController::class, 'updateDeMinimisBenefits'])->name('api.workforce-records.update-deminimis-benefits');
    Route::get('/api/workforce-records/get-evaluation', [WorkforceRecordsController::class, 'getEvaluation'])->name('api.workforce-records.get-evaluation');
    Route::post('/api/workforce-records/save-evaluation', [WorkforceRecordsController::class, 'saveEvaluation'])->name('api.workforce-records.save-evaluation');
    Route::post('/api/workforce-records/update-evaluation', [WorkforceRecordsController::class, 'updateEvaluation'])->name('api.workforce-records.update-evaluation');
    Route::post('/api/workforce-records/delete-evaluation', [WorkforceRecordsController::class, 'deleteEvaluation'])->name('api.workforce-records.delete-evaluation');
    
    Route::get('/api/talent-acquisition/data', [TalentAcquisitionController::class, 'getStaffData'])->name('api.talent-acquisition.data');
    Route::post('/api/talent-acquisition/update-status', [TalentAcquisitionController::class, 'updateStatus'])->name('api.talent-acquisition.update-status');

    // --- HRIS Legal & Compliance Routes ---
    Route::resource('legal-documents', LegalComplianceController::class);
    Route::get('/legal-documents/{legalDocument}/download', [LegalComplianceController::class, 'download'])->name('legal-documents.download');
    Route::get('/legal-documents-by-type', [LegalComplianceController::class, 'getByType'])->name('legal-documents.by-type');
    Route::post('/legal-documents/{legalDocument}/update-status', [LegalComplianceController::class, 'updateStatus'])->name('legal-documents.update-status');
    
    Route::get('/legal-and-compliance', [LegalComplianceController::class, 'index'])->name('legal-and-compliance');
    Route::post('/legal-toolkit', [LegalComplianceController::class, 'store'])->name('legal-toolkit.store');
    
    // --- HRIS Email Notification Routes ---
    Route::get('/email-notification-time-tracker', function () {
        return view('email-notification-time-tracker');
    })->name('email-notification-time-tracker');
    
    Route::get('/email-notification-talent-acquisition', function () {
        return view('email-notification-talent-acquisition');
    })->name('email-notification-talent-acquisition');
    
    Route::get('/email-notification-shift-management', function () {
        return view('email-notification-shift-management');
    })->name('email-notification-shift-management');
    
    Route::get('/email-notification-shift-adjustments', function () {
        return view('email-notification-shift-adjustments');
    })->name('email-notification-shift-adjustments');
});