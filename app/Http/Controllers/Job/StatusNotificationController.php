<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\StatusNotification;
use Illuminate\Support\Facades\Notification;

class StatusNotificationController extends Controller
{
    public function getLatestNotifications(Request $request, User $user)
    {
        $notifications = $user->notifications()
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
        return response()->json(['notifications' => $notifications]);
    }
}
