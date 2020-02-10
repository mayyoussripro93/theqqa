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

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Jenssegers\Date\Date;

class UserNotification extends Notification implements ShouldQueue
{
	use Queueable;
	
	protected $user;
	
	public function __construct($user)
	{

		$this->user = $user;
	}
	
	public function via($notifiable)
	{
		return ['mail'];
	}
	
	public function toMail($notifiable)
	{

        $mailMessage=(new MailMessage)
            ->subject(trans('mail.user_notification_title'))
            ->greeting(trans('mail.user_notification_content_1'))
            ->line(trans('mail.user_notification_content_2', ['name' => $this->user->name]))
			->line(trans('mail.user_notification_content_3', [
        'now'   => Date::now(config('timezone.id'))->formatLocalized(config('settings.app.default_date_format')),
        'time'  => Date::now(config('timezone.id'))->format('H:i'),
        'email' => $this->user->email
            ]));


        if (!empty($this->user->image_data) ) {

            $isJson = json_decode($this->user->image_data);
            if ($isJson instanceof \stdClass || is_array($isJson)) {
                $mailMessage->line(t('A Copy of the owner\'s or agent\'s identity and the copy of the Commercial Record') . ': ');

                foreach (json_decode($this->user->image_data) as $key => $mass)
                {
                    $mailMessage->line( "<img src=".url('/storage/app/'.$mass).">");



                }
            }

        }

        return $mailMessage;

	}
}
