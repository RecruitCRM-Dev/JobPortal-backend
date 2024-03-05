<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
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
        ->where('read_at', null)
        ->orderBy('created_at', 'desc')
        ->get();
        return response()->json(['notifications' => NotificationResource::collection($notifications)]);
    }

    public function markAsRead(Request $request, User $user, string $notification)

    {

        $notificationn = $user->notifications()->find($notification);

        if ($notificationn) {
            $notificationn->update(['read_at' => now()]);
        }

        return response()->json(['message' => 'Notification marked as read']);

    }
}   
