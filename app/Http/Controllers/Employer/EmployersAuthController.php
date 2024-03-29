<?php

namespace App\Http\Controllers\Employer;

use \App\Http\Controllers\Controller;
use App\Http\Resources\LoginResource;
use App\Http\Resources\RegisterResource;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\URL;

class EmployersAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['logout']);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user_already_present = User::where('email', $request->email)->first();
        if ($user_already_present) {
            return response()->json(['error' => 'Email already in use'], 401);
        }

        $employer_already_present = Employer::where('email', $request->email)->first();
        if ($employer_already_present) {
            return response()->json(['error' => 'Email already in use'], 401);
        }

        $employer = Employer::create($data);
        VerifyEmail::createUrlUsing(function ($notifiable) {
            return URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                    'role' => 'employer', // Default role to Employer if not provided
                ]
            );
        });
        $employer->sendEmailVerificationNotification();
        $accessToken = $employer->createToken('api-token')->plainTextToken;

        return new RegisterResource($employer, $accessToken);
    }


    public function login(Request $request)
    {
        $employer = Employer::where('email', $request->email)->first();

        if (!$employer || !Hash::check($request->password, $employer->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        if(!$employer->hasVerifiedEmail()){
            return response()->json(['error' => 'Please verify your email'], 401);
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
        //dd($request->user());
        $user = $request->user();
        if ($user) {
            $user->tokens()->where('name', 'api-token')->delete();
        } else {
            return response()->json(['error' => 'Error occured']);
        }

        return response()->json(['message' => 'Successfully logged out']);
    }
}
