<?php
/**
 * Theqqa - Geo Classified Ads Software
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
 * Theqqa
 */

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class UserWillApproved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        if (!empty($this->user->email)) {
            return ['mail'];
        } else {
            if (config('settings.sms.driver') == 'twilio') {
                return [TwilioChannel::class];
            }

            return ['nexmo'];
        }
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(trans('mail.user_activated_title', ['appName' => config('app.name'), 'userName' => $this->user->name]))
            ->greeting(trans('mail.user_activated_content_1', ['appName' => config('app.name'), 'userName' => $this->user->name]))
            ->line(trans('mail.Your account will be active after admin approve on your Account.'))
            ->line(trans('mail.user_activated_content_4', ['appName' => config('app.name')]));
    }

    public function toNexmo($notifiable)
    {
        return (new NexmoMessage())->content($this->smsMessage())->unicode();
    }

    public function toTwilio($notifiable)
    {
        return (new TwilioSmsMessage())->content($this->smsMessage());
    }

    protected function smsMessage()
    {
        return trans('sms.user_activated_content', ['appName' => config('app.name'), 'userName' => $this->user->name]);
    }
}
