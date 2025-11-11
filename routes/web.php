<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TimeTrackingController;
use App\Http\Controllers\ShiftManagementController;
use App\Http\Controllers\ShiftAdjustmentsController;
use App\Http\Controllers\HrToolkitController;
use App\Http\Controllers\BenefitsAndTaxesController;
use App\Http\Controllers\PayoutReportsController;
use App\Http\Controllers\LegalComplianceController;
use App\Http\Controllers\WorkforceRecordsController;

// --- ADD THESE CONTROLLERS ---
use App\Http\Controllers\TalentAcquisitionController;
use App\Http\Controllers\TalentToolkitController;

// Make login the landing page
Route::get('/', [AuthController::class, 'showLogin'])->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/pending', function () {
        return view('pending');
    })->name('pending');
    
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Attendance Routes
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
    
    // HR Management Routes
    // This is the main page route.
    Route::get('/talent-acquisition', [App\Http\Controllers\TalentAcquisitionController::class, 'index'])
         ->name('talent-acquisition.index'); // --- RENAMED BACK ---
    
    // --- ADD THESE TWO ROUTES FOR THE MODAL FORM ---
    Route::post('/talent-acquisition-toolkit', [App\Http\Controllers\TalentAcquisitionController::class, 'store'])
         ->name('talent-acquisition-toolkit.store');
    
    Route::put('/talent-acquisition-toolkit/{id}', [App\Http\Controllers\TalentAcquisitionController::class, 'update'])
         ->name('talent-acquisition-toolkit.update');
    // --- END OF ADDITIONS ---
    
    // These routes are for the 'talent-management' page
    Route::get('/talent-management', [App\Http\Controllers\TalentToolkitController::class, 'index'])->name('talent-management');
    Route::post('/talent-toolkit', [App\Http\Controllers\TalentToolkitController::class, 'store'])->name('talent-toolkit.store');
    Route::put('/talent-toolkit/{id}', [App\Http\Controllers\TalentToolkitController::class, 'update'])->name('talent-toolkit.update');
    
    Route::get('/hr-toolkit', [HrToolkitController::class, 'index'])->name('hr-toolkit');
    Route::post('/hr-toolkit', [HrToolkitController::class, 'store'])->name('hr-toolkit.store');
    Route::put('/hr-toolkit/{id}', [HrToolkitController::class, 'update'])->name('hr-toolkit.update');
    
    Route::get('/benefits-and-taxes', [BenefitsAndTaxesController::class, 'index'])->name('benefits-and-taxes');
    Route::post('/benefits-and-taxes', [BenefitsAndTaxesController::class, 'store'])->name('benefits-and-taxes.store');
    Route::put('/benefits-and-taxes/{id}', [BenefitsAndTaxesController::class, 'update'])->name('benefits-and-taxes.update');
    
    Route::get('/payout-reports', [PayoutReportsController::class, 'index'])->name('payout-reports');
    Route::any('/api/payroll', [PayoutReportsController::class, 'api'])->name('api.payroll');
    
    Route::get('/workforce-records', [WorkforceRecordsController::class, 'index'])->name('workforce-records');
    
    // Workforce Records API endpoints
    Route::get('/api/workforce-records/user-rates', [WorkforceRecordsController::class, 'getUserRates'])->name('api.workforce-records.user-rates');
    Route::post('/api/workforce-records/user-rates', [WorkforceRecordsController::class, 'updateUserRates'])->name('api.workforce-records.update-user-rates');
    Route::get('/api/workforce-records/user-settings', [WorkforceRecordsController::class, 'getUserSettings'])->name('api.workforce-records.user-settings');
    Route::post('/api/workforce-records/user-settings', [WorkforceRecordsController::class, 'updateUserSettings'])->name('api.workforce-records.update-user-settings');
    Route::get('/api/workforce-records/user-files', [WorkforceRecordsController::class, 'getUserFiles'])->name('api.workforce-records.user-files');
    Route::get('/api/workforce-records/fringe-benefits', [WorkforceRecordsController::class, 'getFringeBenefits'])->name('api.workforce-records.fringe-benefits');
    Route::post('/api/workforce-records/fringe-benefits', [WorkforceRecordsController::class, 'updateFringeBenefits'])->name('api.workforce-records.update-fringe-benefits');
    Route::get('/api/workforce-records/deminimis-benefits', [WorkforceRecordsController::class, 'getDeMinimisBenefits'])->name('api.workforce-records.deminimis-benefits');
    Route::post('/api/workforce-records/deminimis-benefits', [WorkforceRecordsController::class, 'updateDeMinimisBenefits'])->name('api.workform-records.update-deminimis-benefits');
    Route::get('/api/workforce-records/get-evaluation', [WorkforceRecordsController::class, 'getEvaluation'])->name('api.workforce-records.get-evaluation');
    Route::post('/api/workforce-records/save-evaluation', [WorkforceRecordsController::class, 'saveEvaluation'])->name('api.workforce-records.save-evaluation');
    Route::post('/api/workforce-records/update-evaluation', [WorkforceRecordsController::class, 'updateEvaluation'])->name('api.workforce-records.update-evaluation');
    Route::post('/api/workforce-records/delete-evaluation', [WorkforceRecordsController::class, 'deleteEvaluation'])->name('api.workforce-records.delete-evaluation');
    
    // --- TALENT ACQUISITION API ENDPOINTS ---
    Route::get('/api/talent-acquisition/data', [App\Http\Controllers\TalentAcquisitionController::class, 'getStaffData'])->name('api.talent-acquisition.data');
    Route::post('/api/talent-acquisition/update-status', [App\Http\Controllers\TalentAcquisitionController::class, 'updateStatus'])->name('api.talent-acquisition.update-status');
    // --- END OF API ROUTES ---

    // Legal & Compliance Routes
    Route::resource('legal-documents', LegalComplianceController::class);
    Route::get('/legal-documents/{legalDocument}/download', [LegalComplianceController::class, 'download'])->name('legal-documents.download');
    Route::get('/legal-documents-by-type', [LegalComplianceController::class, 'getByType'])->name('legal-documents.by-type');
    Route::post('/legal-documents/{legalDocument}/update-status', [LegalComplianceController::class, 'updateStatus'])->name('legal-documents.update-status');
    
    Route::get('/legal-and-compliance', [LegalComplianceController::class, 'index'])->name('legal-and-compliance');
    Route::post('/legal-toolkit', [LegalComplianceController::class, 'store'])->name('legal-toolkit.store');
    
    // Email Notification Routes
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