<?php
/**
 * Theqqa - #1 Cars Services Platform in KSA
 * Copyright (c) ProCrew. All Rights Reserved
 *
 * Website: http://www.procrew.pro
 *
 * Theqqa
 */

namespace App\Notifications;

use App\Models\City;
use App\Models\Post;
use App\Models\SubAdmin1;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\ImageService;

class User_Mail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $msg;

    public function __construct($request)
    {

        $this->msg = $request;

    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        if ($this->msg->service_type == t( 'mogaz service') or $this->msg->service_type == 'mogaz service')
        {

            return (new MailMessage)
                ->subject(trans('mail.payment_sent_title'))
                ->greeting(trans('mail.payment_sent_content_1'))
                ->line(trans('mail.payment_sent_content_user').t( 'mogaz service'))
                ->line(trans('mail.payment_sent_content_3'));


        }
        elseif ($this->msg->service_type == t( 'estimation title') or $this->msg->service_type ==  'estimation service')
        {

            return (new MailMessage)
                ->subject(trans('mail.payment_sent_title'))
                ->greeting(trans('mail.payment_sent_content_1'))
                ->line(trans('mail.payment_sent_content_user').t( 'estimation title'))
                ->line(trans('mail.payment_sent_content_3'));

        }
        elseif ($this->msg->service_type == t( 'ownership_title') or $this->msg->service_type == 'ownership service')
        {
            return (new MailMessage)
                ->subject(trans('mail.payment_sent_title'))
                ->greeting(trans('mail.payment_sent_content_1'))
                ->line(trans('mail.payment_sent_content_user').t( 'ownership_title') )
                ->line(trans('mail.payment_sent_content_3'));

        }
        elseif ($this->msg->service_type == t( 'checking_title') or $this->msg->service_type == 'checking service')
        {
            return (new MailMessage)
                ->subject(trans('mail.payment_sent_title'))
                ->greeting(trans('mail.payment_sent_content_1'))
                ->line(trans('mail.payment_sent_content_user').t( 'checking_title'))
                ->line(trans('mail.payment_sent_content_3'));
        }
        elseif ($this->msg->service_type == t( 'shipping_title') or $this->msg->service_type ==  'shipping service')
        {
            return (new MailMessage)
                ->subject(trans('mail.payment_sent_title'))
                ->greeting(trans('mail.payment_sent_content_1'))
                ->line(trans('mail.payment_sent_content_user').t( 'shipping_title'))
                ->line(trans('mail.payment_sent_content_3'));

        }

        elseif ($this->msg->service_type ==  t( 'maintenance_title') or $this->msg->service_type == 'maintenance service')
        {
            return (new MailMessage)
                ->subject(trans('mail.payment_sent_title'))
                ->greeting(trans('mail.payment_sent_content_1'))
                ->line(trans('mail.payment_sent_content_user').t( 'maintenance_title'))
                ->line(trans('mail.payment_sent_content_3'));

        }

        else
        {

            return (new MailMessage)
                ->subject(trans('mail.payment_sent_title'))
                ->greeting(trans('mail.payment_sent_content_1'))
                ->line(trans('mail.payment_sent_content_user'))
                ->line(trans('mail.payment_sent_content_3'));
        }



    }
}