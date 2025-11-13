<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page based on their access level.
     */
    public function show()
    {
        $user = Auth::user();

        // Check access level and return the correct view
        switch ($user->access_level) {
            case 1:
                // Admin (Level 1)
                return view('admin.profile', compact('user'));
            case 2:
                // Client (Level 2)
                return view('client.profile', compact('user'));
            default:
                // Basic User (Level 3) and any other
                return view('profile', compact('user'));
        }
    }

    /**
     * Update the user's main profile details (email, contact, password, etc.).
     */
    public function updateDetails(Request $request)
    {
        $user = Auth::user();

        // Validate the incoming data
        $request->validate([
            'email' => 'required|email|max:255',
            'contact' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'old_password' => 'nullable|string',
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)],
        ]);

        // --- Password Update Logic ---
        $passwordUpdated = false;
        if ($request->filled('password')) {
            // If new password is set, old password is required
            if (!$request->filled('old_password')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Old password is required to set a new password.'
                ], 422);
            }

            // Check if the old password is correct
            if (!Hash::check($request->old_password, $user->user_password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Incorrect old password.'
                ], 422);
            }

            // Update the password (the User model will hash it automatically)
            $user->user_password = $request->password;
            $passwordUpdated = true;
        }

        // --- Update User Details ---
        $user->user_email = $request->email;
        $user->phone_number = $request->contact;
        $user->address = $request->address;
        $user->country = $request->country;
        
        $user->save();

        // --- Dynamic Company Table Update Logic ---
        $companyName = $user->company;
        if ($companyName) {
            $companyUsersTable = strtolower(str_replace(' ', '_', $companyName)) . "_users";

            if (Schema::hasTable($companyUsersTable)) {
                $companyData = [
                    'user_email' => $request->email,
                    'phone_number' => $request->contact,
                    'address' => $request->address,
                    'country' => $request->country,
                ];

                if ($passwordUpdated) {
                    $companyData['user_password'] = $user->user_password;
                }

                try {
                    DB::table($companyUsersTable)
                        ->where('user_id', $user->user_id)
                        ->update($companyData);
                } catch (\Exception $e) {
                    // Log the error, but don't fail the whole request
                    logger()->error('Failed to update company user table: ' . $e->getMessage());
                }
            }
        }

        $message = $passwordUpdated ? 'Profile and password updated successfully' : 'Profile updated successfully';
        return response()->json(['status' => 'success', 'message' => $message]);
    }

    /**
     * Update the user's profile picture.
     */
    public function updatePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png,gif,heic,jfif|max:2048', // 2MB Max
        ]);

        $user = Auth::user();
        $old_image_path = $user->profile_picture;

        // Store the new file in `storage/app/public/uploads`
        $path = $request->file('profile_picture')->store('uploads', 'public');

        // Update the user's profile
        $user->profile_picture = $path;
        $user->save();

        // Delete the old image if it's not the default one
        if ($old_image_path && $old_image_path !== 'default.png') {
            Storage::disk('public')->delete($old_image_path);
        }

        return response()->json([
            'status' => 'success',
            'image' => Storage::url($path) // Get the public URL
        ]);
    }

    /**
     * Reset the user's profile picture to the default.
     */
    public function deletePicture(Request $request)
    {
        $user = Auth::user();
        $old_image_path = $user->profile_picture;

        $user->profile_picture = 'default.png';
        $user->save();

        // Delete the old image if it's not the default one
        if ($old_image_path && $old_image_path !== 'default.png') {
            Storage::disk('public')->delete($old_image_path);
        }

        return response()->json([
            'status' => 'success',
            'image' => Storage::url('default.png') // Provide the default URL
        ]);
    }
}