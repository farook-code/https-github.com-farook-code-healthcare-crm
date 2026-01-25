<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Prescription;
use App\Models\User;

class RefillRequested extends Notification
{
    use Queueable;

    public $prescription;
    public $patient;

    /**
     * Create a new notification instance.
     */
    public function __construct(Prescription $prescription, User $patient)
    {
        $this->prescription = $prescription;
        $this->patient = $patient;
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
                    ->subject('Prescription Refill Request - ' . $this->patient->name)
                    ->line('Patient ' . $this->patient->name . ' has requested a refill for:')
                    ->line('Medicine: ' . $this->prescription->medicine_name)
                    ->line('Current Dosage: ' . $this->prescription->dosage)
                    ->action('View Patient Profile', url('/doctor/patients')) // Ideally link to specific patient or prescription
                    ->line('Please review and process this request.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'patient_id' => $this->patient->id,
            'patient_name' => $this->patient->name,
            'prescription_id' => $this->prescription->id,
            'medicine_name' => $this->prescription->medicine_name,
            'message' => 'Refill requested for ' . $this->prescription->medicine_name
        ];
    }
}
