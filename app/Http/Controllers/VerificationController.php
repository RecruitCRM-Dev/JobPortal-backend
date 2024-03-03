<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify(Request $request, $user_id)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(["msg" => "Invalid/Expired url provided."], 401);
        }

        // dd($user_id);

        $user = User::find($user_id);
        $employer = Employer::find($user_id);

        // dd($user,$employer);

        if ($user) {
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();

                return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/login?message=Email verified successfully. Please login using your registered email address and password');
            } else {
                return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/login?message=Email already verified');
            }
        } else if ($employer) {
            if (!$employer->hasVerifiedEmail()) {
                $employer->markEmailAsVerified();

                return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/login?message=Email verified successfully. Please login using your registered email address and password');
            } else {
                return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/login?message=Email already verified');
            }
        } else {
            return response()->json(['message' => 'Error occured'], 400);
        }

    }

    public function resend(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'role' => 'required|string'
        ]);

        // dd($request->input('email'));
        $userRole = $request->input('role');

        if ($userRole == 'candidate') {
            $user = User::where('email', $request->input('email'))->first();

            if (!$user) {
                return response()->json(['message' => 'Please register first'], 401);
            }

            if ($user->hasVerifiedEmail()) {
                return response()->json(["msg" => "Email already verified."], 400);
            }

            $user->sendEmailVerificationNotification();
            return response()->json(["msg" => "Email verification link sent on your email id"]);
        } else {
            $employer = Employer::where('email', $request->input('email'))->first();

            if (!$employer) {
                return response()->json(['message' => 'Please register first'], 401);
            }

            if ($employer->hasVerifiedEmail()) {
                return response()->json(["msg" => "Email already verified."], 400);
            }

            $employer->sendEmailVerificationNotification();
            return response()->json(["msg" => "Email verification link sent on your email id"]);
        }
    }
}
