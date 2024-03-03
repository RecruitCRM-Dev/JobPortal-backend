@component('mail::message')
# Password Reset Link

Hello {{ $user->name }},

Click the button below to reset your password:

@component('mail::button', ['url' => env('FRONTEND_URL') . '/resetpassword?resetToken=' . $token])
Reset Password
@endcomponent

If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
