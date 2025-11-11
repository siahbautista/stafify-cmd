<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use App\Models\User; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all companies
        $companies = CompanyProfile::orderBy('company_name')->get();
        $totalCompanies = $companies->count();

        // Check if viewing a specific company's users
        $selectedCompany = $request->input('company');
        $users = [];
        $companyData = null;
        $adminCount = 0;
        $userCount = 0; // Regular users
        $totalCompanyUsers = 0;
        $tableExists = false;

        if ($selectedCompany) {
            // Get company details
            $companyData = CompanyProfile::where('company_name', $selectedCompany)->first();
            
            if ($companyData) {
                // Convert company name to table name
                $companyTable = strtolower(str_replace(' ', '_', $selectedCompany)) . "_users";
                $tableExists = Schema::hasTable($companyTable);

                if ($tableExists) {
                    // Fetch users from the company's table
                    $users = DB::table($companyTable)->orderBy('full_name')->get();
                    $totalCompanyUsers = $users->count();

                    // Count admins and regular users
                    // Adjust this logic based on your UMS dashboard.php
                    // This is based on the logic in your dashboard.php lines 201-217
                    foreach ($users as $user) {
                        if ($user->access_level == 1) {
                            $adminCount++;
                        } elseif ($user->access_level == 2) {
                            if ($user->is_admin == 1) {
                                $adminCount++; // Super Admin
                            } else {
                                $adminCount++; // Admin
                            }
                        } elseif ($user->access_level == 3) {
                            $userCount++; // User
                        }
                    }
                } else {
                    // Fallback to main users table
                    $totalCompanyUsers = User::where('company', $selectedCompany)->count();
                    // Assuming fallback can't determine admins
                    $adminCount = 0;
                    $userCount = $totalCompanyUsers;
                }
            }

        } else {
            // Not viewing a single company, so let's get counts for the main table
            $companies = $companies->map(function ($company) {
                $companyName = $company->company_name;
                $companyTable = strtolower(str_replace(' ', '_', $companyName)) . "_users";
                $userCount = 0;

                if (Schema::hasTable($companyTable)) {
                    try {
                        $userCount = DB::table($companyTable)->count();
                    } catch (\Exception $e) {
                        // Fallback if DB query fails
                        $userCount = User::where('company', $companyName)->count();
                    }
                } else {
                    // Fallback to main users table
                    $userCount = User::where('company', $companyName)->count();
                }
                
                $company->user_count = $userCount;
                return $company;
            });
        }

        return view('admin.dashboard', [
            'totalCompanies' => $totalCompanies,
            'selectedCompany' => $selectedCompany,
            'companyData' => $companyData,
            'users' => $users,
            'tableExists' => $tableExists,
            'adminCount' => $adminCount,
            'userCount' => $userCount,
            'totalCompanyUsers' => $totalCompanyUsers,
            'companies' => $companies,
        ]);
    }
}