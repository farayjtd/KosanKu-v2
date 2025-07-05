<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Buat instance notifikasi baru.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Tentukan channel notifikasi.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Format email notifikasi.
     */
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ], false));

        return (new MailMessage)
            ->subject('Reset Password Akun Anda')
            ->greeting('Halo!')
            ->line('Kami menerima permintaan untuk mereset password akun Anda.')
            ->action('Reset Password', $url)
            ->line('Jika Anda tidak meminta reset password, abaikan email ini.');
    }

    /**
     * Representasi array (opsional, untuk log atau database).
     */
    public function toArray($notifiable)
    {
        return [];
    }
}
