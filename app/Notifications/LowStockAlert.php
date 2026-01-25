<?php

namespace App\Notifications;

use App\Models\Medicine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification
{
    use Queueable;

    public $medicine;

    /**
     * Create a new notification instance.
     */
    public function __construct(Medicine $medicine)
    {
        $this->medicine = $medicine;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Keeping it simple with DB notifications first
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Low Stock Warning',
            'message' => "Medicine '{$this->medicine->name}' is running low (Current Stock: {$this->medicine->stock_quantity}).",
            'medicine_id' => $this->medicine->id,
            'link' => route('admin.medicines.index')
        ];
    }
}
