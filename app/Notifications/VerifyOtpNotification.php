<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyOtpNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $otp) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode OTP - Katalog TEFA SMKN 4 Tanjungpinang')
            ->line('Masukkan kode OTP berikut untuk mereset password Anda:')
            ->line($this->otp)
            ->line('Kode berlaku selama 5 menit.')
            ->line('Jika Anda tidak meminta kode ini, abaikan email ini.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
