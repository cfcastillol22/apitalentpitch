<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $users = User::select('id', 'name', 'email', 'email_verified_at', 'created_at')->paginate(10);
        return response()->json($users);
    }


    public function show($id)
    {
        try {
            $user = User::select('id', 'name', 'email', 'email_verified_at', 'created_at')->findOrFail($id);

            return response()->json($user);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::select('id', 'name', 'email', 'email_verified_at', 'created_at')->findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            ]);

            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];

            $user->update($validatedData);

            return response()->json($user);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
