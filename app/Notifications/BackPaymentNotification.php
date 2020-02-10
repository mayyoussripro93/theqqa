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

use App\Models\Package;
use App\Models\PaymentMethod;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class BackPaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $post;
    protected $entityRef;
    protected $package;
    protected $user_post;
    protected $payment ;
    public function __construct($post, $entityRef,$package,$user_post,$payment)
    {
        $this->user_post=$user_post;
        $this->post = $post;
        $this->entityRef = $entityRef;
        $this->package = $package;
        $this->payment = $payment;
    }

	public function via($notifiable)
	{
		return ['mail'];
	}
	
	public function toMail($notifiable)
	{

        $this->paymentMethod = PaymentMethod::find('2');
		$attr = ['slug' => slugify($this->post->title), 'id' => $this->post->id];
		$postUrl = lurl($this->post->uri, $attr);
		 $payment_user = \App\Models\User::where('id',$this->payment->user_id)->first();
        if ($this->package->id == '19') {
            return (new MailMessage)
                ->subject(trans('mail.payment_notification_title_deposit'))
                ->greeting(trans('mail.payment_notification_content_1'))
                ->line(trans('mail.payment_notification_content_deposit_car_2', [
                    'advertiserName' => $payment_user->name,
                    'postUrl'        => $postUrl,
                    'title'          => $this->post->title,
                ]))
                ->line('<br>')
                ->line(trans('mail.payment_notification_content_deposit_car_3', [
                    'adId'              => $this->post->id,
                    'packageName'       =>  $this->package->name,
                    'amount'            =>0.025 *$this->post->price,
                    'currency'          => $this->package->currency_code,
                    'totalamount'        =>$this->post->price,
                    'paymentMethodName' => $this->paymentMethod->display_name,
                ]));
        }
        else {
            return (new MailMessage)
                ->subject(trans('mail.payment_notification_title'))
                ->greeting(trans('mail.payment_notification_content_1'))
                ->line(trans('mail.payment_notification_content_2', [
                    'advertiserName' => $this->post->contact_name,
                    'postUrl' => $postUrl,
                    'title' => $this->post->title,
                ]))
                ->line('<br>')
                ->line(trans('mail.payment_notification_content_3', [
                    'adId' => $this->post->id,
                    'packageName' => (!empty($this->package->short_name)) ? $this->package->short_name : $this->package->name,
                    'amount' => $this->package->price,
                    'currency' => $this->package->currency_code,
                    'paymentMethodName' => $this->paymentMethod->display_name,
                ]));
        }
	}
}
