<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetLink;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str; // Import the Str helper class

class PasswordResetController extends Controller
{
    public function sendResetPasswordLink(Request $request)
    {
        $request->validate(['email' => 'required|email', 'role' => 'required|string']);
        $userRole = $request->input('role');

        if ($userRole == 'candidate') {
            $user = User::where('email', $request->email)->first();

            if ($user) {
                $token = Password::broker('users')->createToken($user);
                Mail::to($user)->send(new PasswordResetLink($user, $token)); // Example using Laravel mail
                return response()->json(['message' => 'Password reset link sent successfully.'], 200);
            } else {
                return response()->json(['message' => 'Not registered yet'], 400);
            }
        } else {
            $employer = Employer::where('email', $request->email)->first();

            if ($employer) {
                $token = Password::broker('employers')->createToken($employer);
                Mail::to($employer)->send(new PasswordResetLink($employer, $token)); // Example using Laravel mail
                return response()->json(['message' => 'Password reset link sent successfully.'], 200);
            } else {
                return response()->json(['error' => 'Not registered yet'], 400);
            }

        }
    }

    public function reset(Request $request)
    {
        $token = $request->route('token'); // Extract token from URL

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:5',
        ]);

        $status = $this->resetPassword($request->email, $request->password, $token);

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully'], 200);
        }

        return response()->json(['message' => __($status)], 422);
    }

    private function resetPassword($email, $password, $token)
    {
        $user = User::where('email', $email)->first();
        $employer = Employer::where('email', $email)->first();

        if ($user) {
            return Password::broker('users')->reset(
                compact('email', 'password', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();
                }
            );
        } else if ($employer) {
            return Password::broker('employers')->reset(
                compact('email', 'password', 'token'),
                function ($employer, $password) {
                    $employer->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();
                }
            );
        }

        return Password::INVALID_USER; // User or employer not found
    }
}
