<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstanceCreated extends Notification
{
    use Queueable;
    protected $instanceDetails;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($instanceDetails)
    {
        $this->instanceDetails = $instanceDetails;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Votre nouvelle instance Dolibarr a été créée avec succès.')
                    ->line('Nom de l\'instance: ' . $this->instanceDetails['name'])
                    ->line('URL: ' . $this->instanceDetails['url'])
                    ->line('Nom d\'utilisateur: ' . $this->instanceDetails['login'])
                    ->line('Mot de passe: ' . $this->instanceDetails['password'])
                    ->action('Accéder à votre instance', url($this->instanceDetails['url']))
                    ->line('Merci d\'utiliser notre service!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Nouvelle instance Dolibarr créée',
            'instance_name' => $this->instanceDetails['name'],
            'instance_url' => $this->instanceDetails['url'],
        ];
    }
}
