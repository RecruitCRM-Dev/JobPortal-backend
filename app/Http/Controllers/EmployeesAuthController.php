<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoginResource;
use App\Http\Resources\RegisterResource;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class EmployeesAuthController extends Controller
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

        $employer = Employee::create($data);
        $accessToken = $employer->createToken('api-token')->plainTextToken;

        return new RegisterResource($employer, $accessToken);
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // if (Auth::attempt($credentials)) {
        //     $employer = Employer::where('email', $request->email)->first();
        //     $token = $employer->createToken('api-token')->plainTextToken;

        //     return response()->json(['id' => $employer->id, 'access_token' => $token]);

        // }

        $employer = Employee::where('email', $request->email)->first();

        if (!$employer) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        if (!Hash::check($request->password, $employer->password)) {
            return response()->json(['error' => 'Invalid password'], 401);
        }


        $token = $employer->createToken('api-token')->plainTextToken;

        return new LoginResource($employer, $token);
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
