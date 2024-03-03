<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusUpdateNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $jobApplication;
    public function __construct(JobApplication $jobApplication)
    {
        $this->jobApplication = $jobApplication;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = sprintf(
            'Your status for the job role %s has been changed to %s.',
            $this->jobApplication->job->title,
            $this->jobApplication->status
        );

        $url = 'http://127.0.0.1:5173/job/' . $this->jobApplication->job->id . '/apply';
    
        return (new MailMessage)
            ->line($message)
            ->action('View Job', $url)
            ->line('Thank you for applying!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable)
    {
        return [
            'status' => $this->jobApplication->status,
            'job_id' =>$this->jobApplication->job->id,
            'job_profile' =>$this->jobApplication->job->employer->profile_pic,
            'job_title' => $this->jobApplication->job->title,
            'message' => "Your application status for " . $this->jobApplication->job->title . " has been updated to " . $this->jobApplication->status, 
        ];
    }
}
