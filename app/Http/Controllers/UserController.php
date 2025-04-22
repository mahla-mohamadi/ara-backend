<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
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
