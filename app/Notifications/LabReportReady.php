<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LabReportReady extends Notification implements ShouldQueue
{
    use Queueable;

    public $report;
    public $uploader;

    /**
     * Create a new notification instance.
     */
    public function __construct($report, $uploader)
    {
        $this->report = $report;
        $this->uploader = $uploader;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Lab Report Available')
            ->line('A new lab report "' . $this->report->title . '" has been uploaded to your account.')
            ->action('View Report', route('lab-reports.index', $notifiable->id))
            ->line('Thank you for using our Healthcare Portal.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'lab_report',
            'title' => 'Lab Report Ready',
            'message' => 'New report "' . $this->report->title . '" uploaded by ' . $this->uploader->name,
            'url' => route('lab-reports.index', $notifiable->id),
        ];
    }
}
