<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username/Email and Password are required!'
            ]);
        }

        $uid = $request->uid;
        $password = $request->password;

        // Find user by email or username
        $user = User::where('user_email', $uid)
                   ->orWhere('user_name', $uid)
                   ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found!'
            ]);
        }

        // Check password (support both SHA-256 and bcrypt)
        $passwordValid = false;
        
        // First try SHA-256 (for legacy passwords)
        if (hash('sha256', $password) === $user->user_password) {
            $passwordValid = true;
        } else {
            // Then try bcrypt (for new passwords)
            try {
                if (Hash::check($password, $user->user_password)) {
                    $passwordValid = true;
                }
            } catch (Exception $e) {
                // If bcrypt fails, password is not bcrypt format
                $passwordValid = false;
            }
        }

        if (!$passwordValid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect password!'
            ]);
        }

        // Login the user
        Auth::login($user);

        // Determine redirect URL based on access level
        $redirectUrl = '';
        switch ($user->access_level) {
            case 1:
                $redirectUrl = '/admin/dashboard';
                break;
            case 2:
            case 3:
                $redirectUrl = '/dashboard';
                break;
            case 0:
                $redirectUrl = '/pending';
                break;
            default:
                $redirectUrl = '/dashboard';
        }

        return response()->json([
            'status' => 'success',
            'redirect' => $redirectUrl
        ]);
    }

    /**
     * Show the registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|unique:users,user_name',
            'email' => 'required|email|unique:users,user_email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|string',
            'company' => 'required|string',
            'address_line1' => 'required|string',
            'address_line2' => 'nullable|string',
            'address' => 'required|string',
            'country' => 'required|string',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        // Create user
        $user = User::create([
            'user_name' => $request->user_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'user_email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'country' => $request->country,
            'company' => $request->company,
            'user_password' => Hash::make($request->password),
            'user_pin' => '',
            'user_dept' => 'Unassigned',
            'user_position' => 'Unassigned',
            'is_archived' => 0,
            'access_level' => 0, // Pending approval
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Account created successfully! You can now log in.',
            'redirect' => '/login'
        ]);
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
