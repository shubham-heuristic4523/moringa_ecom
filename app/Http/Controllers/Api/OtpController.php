<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\EmailOtp;
use App\Models\Referral;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\PendingRegistration;

class OtpController extends Controller
{
    // public function verifyRegistrationOtp(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'otp' => 'required|digits:6',
    //     ]);

    //     $otp = EmailOtp::where('email', $request->email)
    //         ->where('purpose', 'register')
    //         ->first();

    //     if (!$otp) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'OTP not found.'
    //         ], 404);
    //     }

    //     if (now()->gt($otp->expires_at)) {
    //         $otp->delete();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'OTP has expired.'
    //         ], 400);
    //     }

    //     if ($otp->otp != $request->otp) {

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid OTP.'
    //         ], 400);
    //     }

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'User not found.'
    //         ], 404);
    //     }

    //    $user->email_verified_at = now();
    //     $user->save();

    //     // Delete OTP after successful verification
    //     $otp->delete();

    //     // Login automatically
    //     $token = $user->createToken('api-token')->plainTextToken;

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Email verified successfully.',
    //         'token' => $token,
    //         'user' => $user
    //     ]);
    // }
    public function verifyRegistrationOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        DB::beginTransaction();

        try {

            $otp = EmailOtp::where('email', $request->email)
                ->where('purpose', 'register')
                ->first();

            if (!$otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP not found.'
                ], 404);
            }

            if (now()->gt($otp->expires_at)) {

                $otp->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired.'
                ], 400);
            }

            if ($otp->otp != $request->otp) {

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP.'
                ], 400);
            }

            $pending = PendingRegistration::where('email', $request->email)->first();

            if (!$pending) {

                return response()->json([
                    'success' => false,
                    'message' => 'Registration data not found.'
                ], 404);
            }

            // Safety check
            if (User::where('email', $pending->email)->exists()) {

                return response()->json([
                    'success' => false,
                    'message' => 'User already exists.'
                ], 409);
            }

            // Create User
            $user = User::create([
            'name'                     => $pending->name,
            'email'                    => $pending->email,
            'password'                 => $pending->password,
            'role'                     => 'user',
            'status'                   => 'active',
            'email_verified_at'        => now(),
            'registered_via_admin_id'  => $pending->admin_id,
        ]);

            // Link OTP to user (optional)
            $otp->update([
                'user_id' => $user->id,
                'verified_at' => now(),
            ]);

            // Log the referral, if this signup used a valid referral code
            if ($pending->referrer_id) {
                Referral::create([
                    'referrer_id' => $pending->referrer_id,
                    'referred_id' => $user->id,
                    'referral_code' => User::whereKey($pending->referrer_id)->value('referral_code'),
                    'status' => 'pending',
                ]);
            }

            // Delete temporary registration
            $pending->delete();

            // Delete OTP
            $otp->delete();

            DB::commit();

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Registration completed successfully.',
                'token' => $token,
                'user' => $user
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Verification failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
