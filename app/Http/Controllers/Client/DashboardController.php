<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the client dashboard.
     */
    public function index()
    {
        // You can pass any data to the view here if needed
        $user = Auth::user();
        
        return view('client.dashboard', [
            'user' => $user
        ]);
    }
}