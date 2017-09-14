<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeNote extends Notification
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
            ->subject('PT Participation Confirmation')
            ->line('Your request to participate in the Rapid HIV Proficiency Testing has been approved. Click on the button below to get started.')
            ->greeting('Hello '.$this->user->name)
            ->action('Get Started', url('password/reset', $this->user->token))
            ->line('Your tester ID/username is '.$this->user->username)
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
