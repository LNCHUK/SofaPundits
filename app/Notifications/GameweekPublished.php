<?php

namespace App\Notifications;

use App\Models\Gameweek;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GameweekPublished extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        private Gameweek $gameweek,
        private string $recipientName
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $group = $this->gameweek->group;

        $url = route('gameweeks.show', ['group' => $group, 'gameweek' => $this->gameweek]);

        return (new MailMessage)
                ->greeting("Hi {$this->recipientName},")
                ->line("A new Gameweek for the '{$group->name}' group has been published on "
                    ."SofaPundits and is ready for your predictions")
                ->action('View Gameweek', $url)
                ->line('Good luck!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
