<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Make sure this is your UMS User model
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display the active users list.
     */
    public function index()
    {
        // Fetch active users (levels 1, 2, 3)
        $users = User::whereIn('access_level', [1, 2, 3])->get();
        
        // Fetch data for filters
        $companies = User::whereIn('access_level', [1, 2, 3])->distinct()->pluck('company');
        $branches = User::whereIn('access_level', [1, 2, 3])->distinct()->pluck('branch_location');
        $departments = User::whereIn('access_level', [1, 2, 3])->distinct()->pluck('user_dept');

        return view('admin.users.index', compact('users', 'companies', 'branches', 'departments'));
    }

    /**
     * Display the pending users list.
     */
    public function pending()
    {
        $pendingUsers = User::where('access_level', 0)->get();
        return view('admin.users.pending', compact('pendingUsers'));
    }

    /**
     * Store a new user (replaces add-user.php).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_name' => 'required|string|unique:users,user_name',
            'full_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,user_email',
            'user_password' => ['required', Password::min(8)],
            'company' => 'required|string|max:255',
            'access_level' => 'required|integer|in:2,3', // Admin can only add Client or Basic
            'phone_number' => 'nullable|string',
            'user_pin' => 'nullable|string',
            'user_dept' => 'nullable|string',
            'user_position' => 'nullable|string',
        ]);

        // IMPORTANT: Use Hash::make() for password
        // Your old code used sha256, which is not compatible with Laravel's auth
        $data['user_password'] = Hash::make($data['user_password']);

        // Create the user in the main 'users' table
        $user = User::create($data);

        // --- Dynamic Company Table Logic ---
        $this->syncUserToCompanyTable($user);

        return response()->json([
            'success' => true,
            'message' => 'User added successfully',
            'user' => $user
        ]);
    }

    /**
     * Update an existing user (replaces update-user.php).
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,user_email,' . $user->user_id . ',user_id',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
            'country' => 'nullable|string',
            'country_code' => 'nullable|string',
            'user_pin' => 'nullable|string',
            'company' => 'required|string',
            'branch_location' => 'nullable|string',
            'user_dept' => 'nullable|string',
            'user_position' => 'nullable|string',
            'employment_date' => 'nullable|date',
            'access_level' => 'required|integer|in:1,2,3',
        ]);

        $user->update($data);

        // --- Dynamic Company Table Logic ---
        $this->syncUserToCompanyTable($user);
        
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => $user->fresh() // Get the updated user data
        ]);
    }

    /**
     * Delete a user (replaces delete-user.php).
     */
    public function destroy(User $user)
    {
        if ($user->user_id == Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
        }

        if ($user->access_level == 1) {
            return response()->json(['success' => false, 'message' => 'Cannot delete a Creator/Admin account.'], 403);
        }

        DB::beginTransaction();
        try {
            // Delete from dynamic company table first
            $companyTable = strtolower(str_replace(' ', '_', $user->company)) . "_users";
            if (Schema::hasTable($companyTable)) {
                DB::table($companyTable)->where('user_id', $user->user_id)->delete();
            }

            // Delete from main users table
            $user->delete();
            
            DB::commit();
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete user {$user->user_id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error deleting user.'], 500);
        }
    }

    /**
     * Approve a pending user (replaces update-access.php).
     */
    public function approve(Request $request, User $user, GoogleDriveService $driveService)
    {
        if ($user->access_level != 0) {
            return response()->json(['success' => false, 'message' => 'User is not pending.'], 422);
        }

        DB::beginTransaction();
        try {
            // 1. Approve user and set access level
            $user->access_level = 2; // Default approval to Client
            $user->save();

            // 2. Create and share Google Drive folder
            $folderName = $user->company ?? "User Folder {$user->user_id}";
            $driveData = $driveService->duplicateFolderForUser($folderName, $user->user_email);

            // 3. Save Drive folder link to user
            $user->drive_folder_id = $driveData['folderId'];
            $user->drive_folder_link = $driveData['folderLink'];
            $user->save();

            // 4. Sync to company table
            $this->syncUserToCompanyTable($user);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'User approved successfully. Drive folder created and shared.',
                'user' => $user->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to approve user {$user->user_id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'User approval failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a pending user (replaces update-access.php).
     */
    public function reject(User $user)
    {
        if ($user->access_level != 0) {
            return response()->json(['success' => false, 'message' => 'User is not pending.'], 422);
        }

        // Rejecting just deletes the user record
        $user->delete();
        
        return response()->json(['success' => true, 'message' => 'User rejected and removed.']);
    }

    /**
     * Helper function to sync user data to their dynamic company table.
     */
    private function syncUserToCompanyTable(User $user)
    {
        $companyTable = strtolower(str_replace(' ', '_', $user->company)) . "_users";

        if (Schema::hasTable($companyTable)) {
            // Map User model fields to the company table fields
            // This is based on your add-user.php and update-user.php
            $companyData = [
                'user_id' => $user->user_id,
                'user_name' => $user->user_name,
                'full_name' => $user->full_name,
                'user_email' => $user->user_email,
                'phone_number' => $user->phone_number,
                'user_pin' => $user->user_pin,
                'company' => $user->company,
                'user_dept' => $user->user_dept,
                'user_position' => $user->user_position,
                'access_level' => $user->access_level,
                'is_admin' => $user->is_admin ?? 0,
                'address' => $user->address,
                'country' => $user->country,
                'country_code' => $user->country_code,
                'employment_date' => $user->employment_date,
                'branch_location' => $user->branch_location,
                // Add other fields from your company table as needed
            ];

            // Use updateOrInsert to either create or update the record
            DB::table($companyTable)->updateOrInsert(
                ['user_id' => $user->user_id], // Find by user_id
                $companyData // Data to insert or update
            );
        } else {
            // Log if the company table doesn't exist
            Log::warning("Company table not found for user {$user->user_id}: {$companyTable}");
        }
    }
}