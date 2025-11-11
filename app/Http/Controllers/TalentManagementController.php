<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class TalentManagementController extends Controller
{
    /**
     * Display the talent management page
     */
    public function index(Request $request): View
    {
        return view('talent-management');
    }
}
