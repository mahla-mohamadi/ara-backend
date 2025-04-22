<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Token;

use App\Models\User;
use App\Models\Phone;

class AuthController extends Controller
{
    public function sendOtp(Request $request){
        $request->validate([
            'phone_number' => ['required', 'string', 'regex:/^09\d{9}$/'],
        ]);

        $phone = Phone::firstOrCreate(
            ['number' => $request->phone_number],
            ['user_id' => null] 
        );

        $existingOtp = $phone->otps()->latest()->first();

        if ($existingOtp && $existingOtp->updated_at->diffInSeconds(now()) < 60) {
            $secondsRemaining = 60 - $existingOtp->updated_at->diffInSeconds(now());
        
            return response()->json([
                'message' => 'You can only request a new OTP after 1 minute.',
                'seconds_remaining' => (int) $secondsRemaining,
            ], 400);
        }
        

        $phone->otps()->delete();

        $otpCode = rand(100000, 999999);

        $phone->otps()->create([
            'otp_code'   => $otpCode,
            'expires_at' => now()->addMinutes(2), 
        ]);

        return response()->json([
            'message'   => 'OTP sent successfully',
            'otp_debug' => $otpCode,
        ]);
    }
    public function loginWithPhoneOtp(Request $request){
        $request->validate([
            'phone_number' => ['required', 'string', 'regex:/^09\d{9}$/'],
            'otp_code'     => ['required', 'string', 'min:6', 'max:6'],
        ]);

        $phone = Phone::where('number', $request->phone_number)->first();

        if (!$phone) {
            return response()->json(['message' => 'Phone number not found'], 404);
        }

        $otp = $phone->otps()
            ->where('otp_code', $request->otp_code)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) {
            return response()->json(['message' => 'Invalid or expired OTP'], 401);
        }

        $user = $phone->user;
        if (!$user) {
            $user = User::create();
            $phone->user_id = $user->id;
            $phone->save();
        }

        $user->tokens()->delete();

        $token = $user->createToken('Personal Access Token')->accessToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ]);
    }
}
