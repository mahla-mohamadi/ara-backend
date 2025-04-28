<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required',
            'password' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 403,
                'message' => 'Invalid number or password'
            ], 403);
        }

        $phone = Phone::query()->where('number',$request->number)->first();
        if ($phone) {
            if ($phone->user && Hash::check($request->password, $phone->user->password)) {
                $token = $phone->user->createToken('ara')->accessToken;
                return response()->json([
                    'code' => 200,
                    'message' => 'Login Sucessfully',
                    'token' => $token
                ]);

            }
        }

        return '404';
    }

    public function check(Request $request)
    {
        return auth()->user();
    }

    public function checkPermission(Request $request)
    {
        return 'yes';
    }
    /////////////////////////////////////////////////////////////////////////////




    public function listUsers(Request $request){
        // Validate input
        $validated = $request->validate([
            'offset' => 'integer|min:0',
            'limit' => 'integer|min:1|max:100',
        ]);

        // Use validated input or fallback defaults
        $offset = $validated['offset'] ?? 0;
        $limit = $validated['limit'] ?? 15;

        // Fetch users
        $users = User::offset($offset)
                    ->limit($limit)
                    ->get();

        $total = User::count();

        return response()->json([
            'data' => $users,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total
        ]);
    }
}
