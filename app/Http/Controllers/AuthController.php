<?php

namespace App\Http\Controllers;

use App\Rules\IranNationalCodeRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Models\User;
use App\Models\Phone;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function getPhones(Request $request){
        $validator = Validator::make($request->all(), [
            'nationalCode' => ['required', 'integer','regex:/^\d{10}$/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 403,
                'message' => $validator->errors()
            ], 403);
        }

        $user = User::query()->where('national_code',$request->nationalCode)->first();
        if ($user) {
            if ($user->phones->count()) {
                return response()->json([
                    'message'   => "user have phones",
                    'code' => '200',
                    'phones' => array_column($user->phones->toArray(),'number')
                ],200);
            } else {
                return response()->json([
                    'message'   => "user don't have phone",
                    'code' => '403'
                ],403);
            }
        } else {
            return response()->json([
                'message'   => 'user not found',
                'code' => 404
            ],404);
        }
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nationalCode' => ['required', 'integer','regex:/^\d{10}$/'],
            'phone' => ['required', 'string','regex:/^09\d{9}$/']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 403,
                'message' => $validator->errors()
            ], 403);
        }

        $user = User::query()->where('national_code',$request->nationalCode)->first();
        if ($user) {
            if ($user->phones->count()) {
                $phone = Phone::query()->where('number',$request->phone)->where('user_id',$user->id)->first();
                if ($phone) {
                    $otp = mt_rand(100000,999999);
                    $phone->otp = $otp;
                    $phone->expire_at = Carbon::now()->addMinute(Config::get('added.optExpireTime'));
                    $phone->save();
                    return response()->json([
                        'message'   => "otp send to your phone",
                        'code' => '200',
                        'otp' => $otp
                    ],200);
                } else {
                    return response()->json([
                        'message'   => "this phone does not belong to this user!",
                        'code' => '403'
                    ],403);
                }
            } else {
                return response()->json([
                    'message'   => "user don't have phone",
                    'code' => '403'
                ],403);
            }
        } else {
            return response()->json([
                'message'   => 'user not found',
                'code' => 404
            ],404);
        }
    }

    public function loginWithOtp(Request $request){
        $validator = Validator::make($request->all(), [
            'otp' => ['required', 'integer','regex:/^\d{6}$/'],
            'phone' => ['required', 'string','regex:/^09\d{9}$/']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 403,
                'message' => $validator->errors()
            ], 403);
        }

        $phone = Phone::where('number', $request->phone)->first();

        if ($phone) {
            if ($phone->otp == $request->otp && Carbon::create($phone->expire_at)->greaterThan(Carbon::now())) {
                $token = $phone->user->createToken('ara')->accessToken;
                return response()->json([
                    'code' => 200,
                    'message' => 'Login Sucessfully',
                    'token' => $token
                ],200);
            } else {
                return response()->json([
                    'message'   => 'otp expired!',
                    'code' => 403
                ],403);
            }
        } else {
            return response()->json([
                'message'   => 'phone not found',
                'code' => 404
            ],404);
        }
    }



    public function logout(Request $request){
        $user = Auth::user();

        if ($user) {
            // Revoke the current access token
            $request->user()->token()->revoke();

            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }

        return response()->json([
            'message' => 'User not authenticated'
        ], 401);
    }
    public function getAuthenticatedUser(Request $request){
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Load related phones
        $user->load('phones');

        return response()->json([
            'user' => $user
        ]);
    }
    public function updateProfile(Request $request){
        $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'national_code'  => ['required', 'string', 'regex:/^\d{10}$/'],
            'email'          => 'nullable|email|max:255',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user->update([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'national_code' => $request->national_code,
            'email'         => $request->email,
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user'    => $user
        ]);
    }
    public function checkToken(Request $request){
        if (Auth::check()) {
            return response()->json([
                'valid'   => true,
                'message' => 'Token is valid.'
            ]);
        }

        return response()->json([
            'valid'   => false,
            'message' => 'Token is invalid or expired.'
        ], 401);
    }
}
