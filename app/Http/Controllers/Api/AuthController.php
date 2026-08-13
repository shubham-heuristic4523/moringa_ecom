<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\EmailOtp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\PendingRegistration;

class AuthController extends Controller
{
    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|min:6'
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'role' => 'user',
    //         'status' => 'active'
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Registration successful',
    //         'user' => $user
    //     ]);
    // }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'referral_code' => 'nullable|string',
            'store' => 'nullable|string',
        ]);

        // Check if user already exists
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email is already registered.'
            ], 422);
        }

        DB::beginTransaction();

        try {

            // Silently ignore an unknown/invalid referral code rather than blocking registration
            $referrerId = $request->filled('referral_code')
                ? User::where('referral_code', $request->referral_code)->value('id')
                : null;

            // Which admin's storefront (if any) this signup happened on
            $adminId = $request->filled('store')
                ? User::where('store_slug', $request->store)->whereIn('role', ['admin', 'super_admin'])->value('id')
                : null;

            // Save pending registration
            PendingRegistration::updateOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'password' => Hash::make($request->password),
                    'referrer_id' => $referrerId,
                    'admin_id' => $adminId,
                ]
            );

            // Generate OTP
            $otp = random_int(100000, 999999);

            // Save OTP
            EmailOtp::updateOrCreate(
                [
                    'email' => $request->email,
                    'purpose' => 'register',
                ],
                [
                    'user_id' => null,
                    'otp' => $otp,
                    'expires_at' => now()->addMinutes(10),
                    'verified_at' => null,
                ]
            );

            // Send Email
            Mail::raw(
                "Your OTP for registration is: {$otp}\n\nThis OTP is valid for 10 minutes.",
                function ($message) use ($request) {
                    $message->to($request->email)
                        ->subject('Registration OTP');
                }
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully.'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Registration failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Account inactive'
            ], 403);
        }
        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip()
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.'
        ]);
    }
}
