<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\CompanyBranch;
use App\Models\CompanyProfile;
use App\Models\CompanyDepartment; // <-- New
use App\Models\CompanyPosition;   // <-- New
use Exception;

class UserController extends Controller
{
    /**
     * Helper function to get the current user's company context and admin status.
     * This is crucial for all operations.
     */
    private function getCompanyContext()
    {
        $user = Auth::user();
        $companyName = $user->company;
        $companyProfile = CompanyProfile::where('company_name', $companyName)->first();
        
        if (!$companyProfile) {
            return null; // Company not set up yet
        }

        $companyUsersTable = strtolower(str_replace(' ', '_', $companyName)) . "_users";

        if (!Schema::hasTable($companyUsersTable)) {
            return null; // Company table doesn't exist
        }

        $companyUser = DB::table($companyUsersTable)->where('user_id', $user->user_id)->first();
        
        return (object)[
            'user' => $user,
            'companyName' => $companyName,
            'companyProfile' => $companyProfile,
            'companyUsersTable' => $companyUsersTable,
            'companyUserId' => $companyUser ? $companyUser->user_id : null,
            'isAdmin' => $companyUser ? $companyUser->is_admin : 0, // 0 = Basic, 1 = Super Admin, 2 = Admin
        ];
    }

    /**
     * Display the user management page.
     * Replaces users.php
     */
    public function index()
    {
        $context = $this->getCompanyContext();

        if (!$context) {
            // Redirect to company profile setup if it's not done
            return redirect()->route('client.company.profile')->with('error', 'Please complete your company profile setup to manage users.');
        }

        // Fetch all data needed for the view
        $users = DB::table($context->companyUsersTable)->get();
        $branches = $context->companyProfile->branches;
        $departments = $context->companyProfile->departments()->orderBy('department_name')->get(); // Dynamic
        $positions = $context->companyProfile->positions()->orderBy('position_name')->get(); // Dynamic

        return view('client.users.index', [
            'users' => $users,
            'branches' => $branches,
            'departments' => $departments,
            'positions' => $positions,
            'companyName' => $context->companyName,
            'isAdmin' => $context->isAdmin, // Pass the logged-in user's admin level (0, 1, or 2)
        ]);
    }

    /**
     * Store a new user in the database.
     * Replaces add-user.php
     */
    public function store(Request $request)
    {
        $context = $this->getCompanyContext();
        if ($context->isAdmin < 1) { // Only admins (1 or 2) can add users
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'user_name' => 'required|string|max:255|unique:users,user_name',
            'full_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255|unique:users,user_email',
            'user_password' => ['required', 'string', Password::min(8)],
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'country_code' => 'nullable|string|max:10',
            'user_pin' => 'nullable|string|max:11',
            'branch_location' => 'nullable|string|max:100',
            'user_dept' => 'nullable|string|max:255',
            'user_position' => 'nullable|string|max:255',
            'employment_date' => 'nullable|date',
            'access_level' => 'required|integer|in:2,3', // Clients can only create other Admins (2) or Basic Users (3)
        ]);

        DB::beginTransaction();
        try {
            // 1. Create in main 'users' table
            $newUser = User::create([
                'user_name' => $validated['user_name'],
                'full_name' => $validated['full_name'],
                'user_email' => $validated['user_email'],
                'user_password' => $validated['user_password'], // Hashing is done by the User model
                'company' => $context->companyName,
                'access_level' => $validated['access_level'], // 2 or 3
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
                'country' => $validated['country'],
                'country_code' => $validated['country_code'],
                'user_pin' => $validated['user_pin'],
                'branch_location' => $validated['branch_location'],
                'user_dept' => $validated['user_dept'],
                'user_position' => $validated['user_position'],
                'employment_date' => $validated['employment_date'],
            ]);

            // 2. Create in company '_users' table
            // New admin rule: 2 for Admin, 0 for Basic
            $isNewUserAdmin = ($validated['access_level'] == 2) ? 2 : 0; 
            
            DB::table($context->companyUsersTable)->insert([
                'user_id' => $newUser->user_id,
                'user_name' => $newUser->user_name,
                'full_name' => $newUser->full_name,
                'user_email' => $newUser->user_email,
                'user_password' => $newUser->user_password, // Store hashed password
                'company' => $newUser->company,
                'access_level' => $newUser->access_level,
                'is_admin' => $isNewUserAdmin, // Set admin status (2 or 0)
                'phone_number' => $newUser->phone_number,
                'address' => $newUser->address,
                'country' => $newUser->country,
                'country_code' => $newUser->country_code,
                'user_pin' => $newUser->user_pin,
                'branch_location' => $newUser->branch_location,
                'user_dept' => $newUser->user_dept,
                'user_position' => $newUser->user_position,
                'employment_date' => $newUser->employment_date,
            ]);

            DB::commit();

            // Refetch the user from the company table to return all fields
            $userForView = DB::table($context->companyUsersTable)->where('user_id', $newUser->user_id)->first();

            return response()->json([
                'success' => true,
                'message' => 'User added successfully',
                'user' => $userForView
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            logger()->error("Failed to add user: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to add user: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing user.
     * Replaces update-user.php
     */
    public function update(Request $request, $userId)
    {
        $context = $this->getCompanyContext();
        if ($context->isAdmin < 1) { // Only admins can update users
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255|unique:users,user_email,' . $userId . ',user_id',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'country_code' => 'nullable|string|max:10',
            'user_pin' => 'nullable|string|max:11',
            'branch_location' => 'nullable|string|max:100',
            'user_dept' => 'nullable|string|max:255',
            'user_position' => 'nullable|string|max:255',
            'employment_date' => 'nullable|date',
            'access_level' => 'required|integer|in:2,3',
        ]);

        $userToUpdate = DB::table($context->companyUsersTable)->where('user_id', $userId)->first();
        if (!$userToUpdate) {
            return response()->json(['success' => false, 'message' => 'User not found in this company.'], 404);
        }

        // Apply permission rules
        if ($context->isAdmin == 2) { // If logged-in user is an Admin (2)
            if ($userToUpdate->is_admin == 1) {
                 return response()->json(['success' => false, 'message' => 'Admins cannot edit the Super Admin.'], 403);
            }
            if ($userToUpdate->is_admin == 2 && $userToUpdate->user_id != $context->companyUserId) {
                 return response()->json(['success' => false, 'message' => 'Admins cannot edit other Admins.'], 403);
            }
        }
        // Super Admin (1) can edit anyone

        DB::beginTransaction();
        try {
            // 1. Find and update main 'users' table
            $user = User::findOrFail($userId);
            $user->update($validated);

            // 2. Update company '_users' table
            // New admin rule: 2 for Admin, 0 for Basic
            $isNewUserAdmin = ($validated['access_level'] == 2) ? 2 : 0; 

            // Prevent demoting the Super Admin
            if ($userToUpdate->is_admin == 1) {
                $isNewUserAdmin = 1; 
            }

            DB::table($context->companyUsersTable)
                ->where('user_id', $userId)
                ->update([
                    'full_name' => $validated['full_name'],
                    'user_email' => $validated['user_email'],
                    'phone_number' => $validated['phone_number'],
                    'address' => $validated['address'],
                    'country' => $validated['country'],
                    'country_code' => $validated['country_code'],
                    'user_pin' => $validated['user_pin'],
                    'branch_location' => $validated['branch_location'],
                    'user_dept' => $validated['user_dept'],
                    'user_position' => $validated['user_position'],
                    'employment_date' => $validated['employment_date'],
                    'access_level' => $validated['access_level'],
                    'is_admin' => $isNewUserAdmin,
                ]);

            DB::commit();
            
            $userForView = DB::table($context->companyUsersTable)->where('user_id', $userId)->first();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => $userForView
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            logger()->error("Failed to update user: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update user: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a user.
     * Replaces delete-user.php
     */
    public function destroy($userId)
    {
        $context = $this->getCompanyContext();
        if ($context->isAdmin < 1) { // Only admins (1 or 2) can delete
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $userToDelete = DB::table($context->companyUsersTable)->where('user_id', $userId)->first();

        if (!$userToDelete) {
            return response()->json(['success' => false, 'message' => 'User not found in this company'], 404);
        }

        // --- NEW PERMISSION LOGIC ---
        // 1. Check if user is trying to delete themself
        if ($userToDelete->user_id == $context->companyUserId) {
             return response()->json(['success' => false, 'message' => 'You cannot delete yourself.'], 403);
        }

        // 2. Check if user is Super Admin
        if ($userToDelete->is_admin == 1) {
            return response()->json(['success' => false, 'message' => 'The Super Admin cannot be deleted.'], 403);
        }

        // 3. Check if an Admin (2) is trying to delete another Admin (2)
        if ($context->isAdmin == 2 && $userToDelete->is_admin == 2) {
            return response()->json(['success' => false, 'message' => 'Admins cannot delete other Admins.'], 403);
        }
        // --- END OF NEW LOGIC ---

        DB::beginTransaction();
        try {
            // 1. Delete from company table
            DB::table($context->companyUsersTable)->where('user_id', $userId)->delete();

            // 2. Delete from main users table
            User::destroy($userId);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'User deleted successfully']);

        } catch (Exception $e) {
            DB::rollBack();
            logger()->error("Failed to delete user: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete user.'], 500);
        }
    }

    /**
     * Promote a user to an Admin (is_admin = 2).
     * Replaces transfer-admin.php
     */
    public function promote(Request $request, $userId)
    {
        $context = $this->getCompanyContext();
        if ($context->isAdmin != 1) { // Only Super Admin (1) can promote others
            return response()->json(['success' => false, 'message' => 'Only the Super Admin can promote other users.'], 403);
        }

        $userToPromote = DB::table($context->companyUsersTable)->where('user_id', $userId)->first();

        if (!$userToPromote) {
            return response()->json(['success' => false, 'message' => 'User not found in this company'], 404);
        }

        if ($userToPromote->is_admin == 1) {
            return response()->json(['success' => false, 'message' => 'This user is already the Super Admin.'], 400);
        }

        if ($userToPromote->is_admin == 2) {
            return response()->json(['success' => false, 'message' => 'This user is already an Admin.'], 400);
        }

        DB::beginTransaction();
        try {
            // 1. Update company table
            DB::table($context->companyUsersTable)
                ->where('user_id', $userId)
                ->update(['is_admin' => 2, 'access_level' => 2]);
            
            // 2. Update main users table
            $user = User::find($userId);
            if ($user) {
                $user->access_level = 2;
                $user->save();
            }

            DB::commit();
            
            $userForView = DB::table($context->companyUsersTable)->where('user_id', $userId)->first();

            return response()->json([
                'success' => true, 
                'message' => $userForView->full_name . ' has been promoted to Admin.',
                'user' => $userForView
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            logger()->error("Failed to promote user: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to promote user.'], 500);
        }
    }

    /**
     * Demote an Admin (is_admin = 2) to a Basic User (is_admin = 0).
     */
    public function demote(Request $request, $userId)
    {
        $context = $this->getCompanyContext();
        if ($context->isAdmin != 1) { // Only Super Admin (1) can demote others
            return response()->json(['success' => false, 'message' => 'Only the Super Admin can demote other users.'], 403);
        }

        $userToDemote = DB::table($context->companyUsersTable)->where('user_id', $userId)->first();

        if (!$userToDemote) {
            return response()->json(['success' => false, 'message' => 'User not found in this company'], 404);
        }

        if ($userToDemote->is_admin == 1) {
            return response()->json(['success' => false, 'message' => 'Cannot demote the Super Admin.'], 403);
        }

        if ($userToDemote->is_admin == 0) {
            return response()->json(['success' => false, 'message' => 'This user is already a Basic User.'], 400);
        }

        DB::beginTransaction();
        try {
            // 1. Update company table
            DB::table($context->companyUsersTable)
                ->where('user_id', $userId)
                ->update(['is_admin' => 0, 'access_level' => 3]);
            
            // 2. Update main users table
            $user = User::find($userId);
            if ($user) {
                $user->access_level = 3;
                $user->save();
            }

            DB::commit();
            
            $userForView = DB::table($context->companyUsersTable)->where('user_id', $userId)->first();

            return response()->json([
                'success' => true, 
                'message' => $userForView->full_name . ' has been demoted to Basic User.',
                'user' => $userForView
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            logger()->error("Failed to demote user: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to demote user.'], 500);
        }
    }

    /**
     * Store a new department for the company.
     * Replaces add-department.php
     */
    public function storeDepartment(Request $request)
    {
        $context = $this->getCompanyContext();
        if ($context->isAdmin < 1) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'department_name' => [
                'required',
                'string',
                'max:255',
                // Ensure the name is unique *for this company*
                Rule::unique('company_departments')->where(function ($query) use ($context) {
                    return $query->where('company_id', $context->companyProfile->getKey());
                }),
            ]
        ]);

        try {
            $department = $context->companyProfile->departments()->create($validated);
            return response()->json(['success' => true, 'message' => 'Department added successfully', 'department' => $department]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a new position for the company.
     * Replaces add-position.php
     */
    public function storePosition(Request $request)
    {
        $context = $this->getCompanyContext();
        if ($context->isAdmin < 1) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'position_name' => [
                'required',
                'string',
                'max:255',
                // Ensure the name is unique *for this company*
                Rule::unique('company_positions')->where(function ($query) use ($context) {
                    return $query->where('company_id', $context->companyProfile->getKey());
                }),
            ]
        ]);

        try {
            $position = $context->companyProfile->positions()->create($validated);
            return response()->json(['success' => true, 'message' => 'Position added successfully', 'position' => $position]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}