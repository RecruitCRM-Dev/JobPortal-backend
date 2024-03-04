<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\RegisterResource;
use App\Models\Employer;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\LoginResource;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['logout']);
    }
    public function register(RegisterRequest $request)
    {
        //dd($request);
        $data = $request->validated();
        $user_already_present = User::where('email', $request->email)->first();
        if ($user_already_present) {
            return response()->json(['error' => 'Email already in use'], 401);
        }

        $employer_already_present = Employer::where('email', $request->email)->first();
        if ($employer_already_present) {
            return response()->json(['error' => 'Email already in use'], 401);
        }

        $user = User::create($data);
        // dd($user);
        VerifyEmail::createUrlUsing(function ($notifiable) {
            return URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                    'role' => 'candidate', // Default role to Employer if not provided
                ]
            );
        });
        $user->sendEmailVerificationNotification();
        // Mail::to($user->email)->send(new WelcomeMail($title, $body));

        $accessToken = $user->createToken('api-token')->plainTextToken;

        return new RegisterResource($user, $accessToken);
    }
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        if(!$user->hasVerifiedEmail()){
            return response()->json(['error' => 'Please verify your email'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return new LoginResource($user, $token);
    }


    /**
     * Logout the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->tokens()->where('name', 'api-token')->delete();
        } else {
            return response()->json(['error' => 'Error occured']);
        }

        return response()->json(['message' => 'Successfully logged out']);
    }
}
