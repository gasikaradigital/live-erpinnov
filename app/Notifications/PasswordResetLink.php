<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetLink extends ResetPassword
{
    use Queueable;


    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }
    
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $baseUrl = "http://localhost:8000";
        $resetUrl = sprintf(
            '%s/reset-password?token=%s&email=%s',
            $baseUrl,
            $this->token,
            urlencode($notifiable->email)
        );

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe')
            ->line('Vous avez demandé la réinitialisation de votre mot de passe.')
            ->action('Réinitialiser le mot de passe', $resetUrl)
            ->line('Ce lien expirera dans 60 minutes.')
            ->line('Si vous n\'êtes pas à l\'origine de cette demande, aucune action n’est requise.');
    }
}
