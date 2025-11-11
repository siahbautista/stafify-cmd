<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TalentAcquisitionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // API endpoint to fetch staff data from Google Sheets
    Route::get('/talent-acquisition/data', [TalentAcquisitionController::class, 'getStaffData']);
    
    // API endpoint to update candidate status
    Route::post('/talent-acquisition/update-status', [TalentAcquisitionController::class, 'updateStatus']);
});