<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AccountNote extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $user;

    /**
     * SendVerificationCode constructor.
     * @param User $user
     */

    public function __construct($user)
    {

        $this->user = $user;
    }

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
        return (new MailMessage)
            ->subject('Kenya Rapid HIV PT Welcome Note')
            ->line('Use the link below to get started.')
            ->greeting('Hello '.$this->user->name)
            ->action('Get Started', url('password/reset', $this->user->token))
            ->line('In case of any challenges, please use our help desk for assitance.')
            ->line('Thank you for using our application!')
            ->line('In case of any challenges, please use the PT help desk http://nphls.or.ke/helpdesk/index.php?a=add.');
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
