<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Models\CompanyProfile;
use App\Models\CompanyBranch;
use App\Models\User; 
use Exception;

class CompanyController extends Controller
{
    /**
     * Helper function to get the current user's company info and admin status.
     */
    private function getCompanyContext()
    {
        $user = Auth::user();
        $companyName = $user->company;
        $companyProfile = CompanyProfile::where('company_name', $companyName)->first();
        $companyUsersTable = strtolower(str_replace(' ', '_', $companyName)) . "_users";
        $isAdmin = false;
        $firstTimeSetup = false;

        if (!$companyProfile) {
            $firstTimeSetup = true;
            // On first-time setup, the user is implicitly the admin
            $isAdmin = true;
        } else {
            // Check if company table exists and get admin status
            if (Schema::hasTable($companyUsersTable)) {
                try {
                    $companyUser = DB::table($companyUsersTable)->where('user_id', $user->user_id)->first();
                    if ($companyUser) {
                        $isAdmin = (bool)$companyUser->is_admin;
                    }
                } catch (Exception $e) {
                     // Log if table query fails
                    logger()->error("Failed to query $companyUsersTable: ". $e->getMessage());
                }
            }
        }

        return (object)[
            'user' => $user,
            'companyName' => $companyName,
            'companyProfile' => $companyProfile,
            'companyUsersTable' => $companyUsersTable,
            'isAdmin' => $isAdmin,
            'firstTimeSetup' => $firstTimeSetup,
        ];
    }

    /**
     * Show the company profile page.
     */
    public function showProfile()
    {
        $context = $this->getCompanyContext();

        return view('client.company.profile', [
            'user' => $context->user,
            'companyName' => $context->companyName,
            'companyData' => $context->companyProfile,
            'isAdmin' => $context->isAdmin,
            'firstTimeSetup' => $context->firstTimeSetup,
        ]);
    }

    /**
     * Create or Update the company profile.
     * This handles the "first time setup" logic.
     */
    public function createOrUpdateProfile(Request $request)
    {
        $context = $this->getCompanyContext();
        if (!$context->isAdmin) {
            return back()->with('error', 'You do not have permission to perform this action.');
        }

        $request->validate([
            'company_address' => 'required|string|max:255',
            'company_phone' => 'required|string|max:20',
            'company_email' => 'required|email|max:255',
        ]);

        DB::beginTransaction();
        try {
            if ($context->firstTimeSetup) {
                // --- This is the "Big Bang" - First Time Creation ---
                
                // 1. Create the Company Profile
                $companyProfile = CompanyProfile::create([
                    'company_name' => $context->companyName,
                    'company_address' => $request->company_address,
                    'company_phone' => $request->company_phone,
                    'company_email' => $request->company_email,
                ]);

                // 2. Create the "Headquarters" Branch
                CompanyBranch::create([
                    'company_id' => $companyProfile->getKey(), // Use getKey() to get 'id'
                    'branch_location' => 'Headquarters',
                    'branch_address' => $request->company_address,
                    'branch_phone' => $request->company_phone,
                    'is_headquarters' => 1,
                ]);

                // 3. Create the company-specific users table
                $this->createCompanyUsersTable($context->companyUsersTable);

                // 4. Transfer all users from this company into the new table
                $this->transferUsersToCompanyTable($context->companyUsersTable, $context->companyName, $context->user->user_id);
                
                $message = 'Company profile created successfully! You have been set as the company administrator.';

            } else {
                // --- This is a simple Update ---
                $context->companyProfile->update([
                    'company_address' => $request->company_address,
                    'company_phone' => $request->company_phone,
                    'company_email' => $request->company_email,
                ]);

                // 5. Also update the "Headquarters" branch
                if ($context->companyProfile->headquartersBranch) {
                    $context->companyProfile->headquartersBranch()->update([
                        'branch_address' => $request->company_address,
                        'branch_phone' => $request->company_phone,
                    ]);
                }
                $message = 'Company profile updated successfully!';
            }

            DB::commit();
            return back()->with('success', $message);

        } catch (Exception $e) {
            DB::rollBack();
            logger()->error("Company Profile Save Failed: ". $e->getMessage());
            return back()->with('error', 'An error occurred while saving. Please try again. '. $e->getMessage());
        }
    }

    /**
     * Update the company's logo.
     */
    public function updateLogo(Request $request)
    {
        $context = $this->getCompanyContext();
        if (!$context->isAdmin) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'company_logo' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);

        $companyProfile = $context->companyProfile;
        if (!$companyProfile) {
            return response()->json(['status' => 'error', 'message' => 'Company profile not found.'], 404);
        }

        $old_logo = $companyProfile->company_logo;

        // Store in `storage/app/public/company_logos`
        $path = $request->file('company_logo')->store('company_logos', 'public');

        $companyProfile->company_logo = $path;
        $companyProfile->save();

        // Delete old logo if it's not the default
        if ($old_logo && $old_logo !== 'default-company-logo.png') {
            Storage::disk('public')->delete($old_logo);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logo updated successfully.',
            'logo_url' => Storage::url($path),
        ]);
    }

    /**
     * Delete the company's logo and reset to default.
     */
    public function deleteLogo()
    {
        $context = $this->getCompanyContext();
        if (!$context->isAdmin) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }
        
        $companyProfile = $context->companyProfile;
        if (!$companyProfile) {
            return response()->json(['status' => 'error', 'message' => 'Company profile not found.'], 404);
        }

        $old_logo = $companyProfile->company_logo;

        $companyProfile->company_logo = 'default-company-logo.png';
        $companyProfile->save();

        if ($old_logo && $old_logo !== 'default-company-logo.png') {
            Storage::disk('public')->delete($old_logo);
        }

        return response()->json([
            'status' => 'success', 
            'message' => 'Company logo reset to default.'
        ]);
    }

    /**
     * Update company-wide settings (timezone, fiscal year).
     */
    public function updateSettings(Request $request)
    {
        $context = $this->getCompanyContext();
        if (!$context->isAdmin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'timezone' => 'required|string|max:100',
            'week_start' => 'required|integer|between:0,6',
            'year_type' => 'required|in:calendar,fiscal',
            'fiscal_start_month' => 'nullable|required_if:year_type,fiscal|integer|between:1,12',
            'fiscal_start_day' => 'nullable|required_if:year_type,fiscal|integer|between:1,31',
            'fiscal_end_month' => 'nullable|required_if:year_type,fiscal|integer|between:1,12',
            'fiscal_end_day' => 'nullable|required_if:year_type,fiscal|integer|between:1,31',
        ]);

        if ($data['year_type'] === 'calendar') {
            $data['fiscal_start_month'] = null;
            $data['fiscal_start_day'] = null;
            $data['fiscal_end_month'] = null;
            $data['fiscal_end_day'] = null;
        }

        try {
            $context->companyProfile->update($data);
            return response()->json(['success' => true, 'message' => 'Company settings updated successfully.']);
        } catch (Exception $e) {
            logger()->error('Failed to update company settings: '. $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    /**
     * Show the branch management page.
     */
    public function showBranches()
    {
        $context = $this->getCompanyContext();
        
        $branches = [];
        if ($context->companyProfile) {
            $branches = $context->companyProfile->branches()->orderBy('is_headquarters', 'desc')->get();
        }

        return view('client.company.branches', [
            'companyName' => $context->companyName,
            'companyData' => $context->companyProfile,
            'isAdmin' => $context->isAdmin,
            'firstTimeSetup' => $context->firstTimeSetup,
            'branches' => $branches,
        ]);
    }

    /**
     * Store a new company branch.
     */
    public function storeBranch(Request $request)
    {
        $context = $this->getCompanyContext();
        if (!$context->isAdmin) {
            return back()->with('error', 'You do not have permission to add branches.');
        }

        $data = $request->validate([
            'branch_location' => 'required|string|max:255',
            'branch_address' => 'required|string|max:255',
            'branch_phone' => 'required|string|max:20',
        ]);
        
        $data['company_id'] = $context->companyProfile->getKey(); // Use getKey() to get 'id'
        $data['is_headquarters'] = 0; // Can't add a new HQ

        CompanyBranch::create($data);

        return back()->with('success', 'Branch added successfully!');
    }

    /**
     * Update an existing company branch.
     */
    public function updateBranch(Request $request, $branchId)
    {
        $context = $this->getCompanyContext();
        if (!$context->isAdmin) {
            return back()->with('error', 'You do not have permission to update branches.');
        }

        $data = $request->validate([
            'branch_location' => 'required|string|max:255',
            'branch_address' => 'required|string|max:255',
            'branch_phone' => 'required|string|max:20',
        ]);

        $branch = $context->companyProfile->branches()->findOrFail($branchId);

        $branch->update($data);

        // If this is the HQ, sync details back to the main profile
        if ($branch->is_headquarters) {
            $context->companyProfile->update([
                'company_address' => $data['branch_address'],
                'company_phone' => $data['branch_phone'],
            ]);
        }

        return back()->with('success', 'Branch updated successfully!');
    }

    /**
     * Delete a company branch.
     */
    public function destroyBranch($branchId)
    {
        $context = $this->getCompanyContext();
        if (!$context->isAdmin) {
            return back()->with('error', 'You do not have permission to delete branches.');
        }

        $branch = $context->companyProfile->branches()->findOrFail($branchId);

        if ($branch->is_headquarters) {
            return back()->with('error', 'You cannot delete the headquarters branch.');
        }

        // TODO: Add logic here to check if any users are assigned to this branch before deleting.

        $branch->delete();
        return back()->with('success', 'Branch deleted successfully!');
    }


    // --- Helper Methods for Company Creation ---

    private function createCompanyUsersTable(string $tableName)
{
    Schema::create($tableName, function ($table) {
        $table->integer('user_id')->primary();
        $table->string('user_name')->nullable();
        $table->string('full_name')->nullable();
        $table->string('user_email')->nullable();
        $table->string('phone_number', 20)->nullable();
        $table->integer('user_pin')->nullable()->default(null); // Explicitly allow NULL
        $table->string('company')->nullable();
        $table->string('user_dept')->nullable();
        $table->string('user_position')->nullable();
        $table->integer('access_level')->nullable()->default(1);
        $table->integer('is_admin')->default(0);
        $table->string('address')->nullable();
        $table->string('country', 100)->nullable();
        $table->string('country_code', 10)->nullable();
        $table->date('employment_date')->nullable();
        $table->string('branch_location', 100)->nullable();
        $table->string('engagement_status', 50)->nullable();
        $table->string('user_status', 50)->nullable();
        $table->string('user_type', 50)->nullable();
        $table->string('wage_type', 50)->nullable();
        $table->tinyInteger('sil_status')->default(0);
        $table->tinyInteger('statutory_benefits')->default(0);
        $table->string('user_password')->nullable();
    });
}
/**
 * Transfer users from the main users table to the company-specific table.
 * Handles nullable integer columns properly.
 */
private function transferUsersToCompanyTable(string $tableName, string $companyName, int $adminUserId)
{
    // Build the query with proper NULL handling for integer columns
    $query = DB::table('users')
        ->select([
            'user_id',
            'user_name',
            'full_name',
            'user_email',
            'phone_number',
            DB::raw('CASE 
                WHEN user_pin IS NULL OR user_pin = "" OR user_pin = 0 THEN NULL 
                ELSE user_pin 
            END as user_pin'),
            'company',
            'user_dept',
            'user_position',
            DB::raw('CASE 
                WHEN access_level IS NULL OR access_level = "" THEN 1 
                ELSE access_level 
            END as access_level'),
            'address',
            'country',
            'country_code',
            'employment_date',
            'branch_location',
            'engagement_status',
            'user_status',
            'user_type',
            'wage_type',
            'user_password',
            DB::raw('0 as sil_status'),
            DB::raw('0 as statutory_benefits'),
            DB::raw("CASE WHEN user_id = {$adminUserId} THEN 1 ELSE 0 END as is_admin")
        ])
        ->where('company', $companyName);
    
        // Define columns for insertion
        $insertColumns = [
            'user_id',
            'user_name',
            'full_name',
            'user_email',
            'phone_number',
            'user_pin',
            'company',
            'user_dept',
            'user_position',
            'access_level',
            'address',
            'country',
            'country_code',
            'employment_date',
            'branch_location',
            'engagement_status',
            'user_status',
            'user_type',
            'wage_type',
            'user_password',
            'sil_status',
            'statutory_benefits',
            'is_admin'
        ];
        
        // Insert into the company table
        DB::table($tableName)->insertUsing($insertColumns, $query);
    }
}