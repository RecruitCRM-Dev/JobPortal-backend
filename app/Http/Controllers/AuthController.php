<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegisterResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\LoginResource;
use App\Http\Requests\LoginRequest;
class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user_already_present = User::where('email', $request->email)->first();
        if($user_already_present) {
            return response()->json(['error' => 'Email already in use'], 401);
        }

        $employer_already_present = Employee::where('email', $request->email)->first();
        if($employer_already_present) {
            return response()->json(['error' => 'Email already in use'], 401);
        }

        $user = User::create($data);
        $accessToken = $user->createToken('api-token')->plainTextToken;

        return new RegisterResource($user, $accessToken);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;

            return new LoginResource($user, $token);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid password'], 401);
        }

        return response()->json(['error' => 'Authentication failed'], 401);
    }


    /**
     * Logout the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
