<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create and save the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Generate access token
        $accessToken = $user->createToken('authToken')->accessToken;

        // Return response with user details and access token
        return response()->json(['user' => $user, 'access_token' => $accessToken], 201);
    }
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        // Authentication passed...
        $user = Auth::user();
        $token = $user->createToken('AuthToken')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    // Check if the email exists in the database
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    // Check if the password is incorrect
    if (!Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Invalid password'], 401);
    }

    return response()->json(['error' => 'Authentication failed'], 401);
}

}
