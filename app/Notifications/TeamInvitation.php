<?php

namespace App\Notifications;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamInvitation extends Notification implements ShouldQueue
{
    use Queueable;

    public $invitation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
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
        $acceptUrl = route('invitations.show', $this->invitation->token);
        $tenantName = $this->invitation->tenant->name;
        $inviterName = $this->invitation->inviter->name;
        $role = ucfirst($this->invitation->role);

        return (new MailMessage)
            ->subject('You\'re invited to join ' . $tenantName . ' on FreighterX')
            ->greeting('Hello!')
            ->line($inviterName . ' has invited you to join ' . $tenantName . ' on FreighterX as a ' . $role . '.')
            ->line('Click the button below to accept the invitation and create your account.')
            ->action('Accept Invitation', $acceptUrl)
            ->line('This invitation will expire in 7 days.')
            ->line('If you did not expect this invitation, you can safely ignore this email.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'invitation_id' => $this->invitation->id,
            'tenant_name' => $this->invitation->tenant->name,
            'role' => $this->invitation->role,
        ];
    }
}
