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
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Post;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class PaymentApproved extends Notification implements ShouldQueue
{
	use Queueable;
	
	protected $payment;
	protected $post;
	protected $package;
	protected $paymentMethod;
	
	public function __construct(Payment $payment, Post $post)
	{
		$this->payment = $payment;
		$this->post = $post;
		$this->package = Package::findTrans($payment->package_id);
		$this->paymentMethod = PaymentMethod::find($payment->payment_method_id);
	}
	
	public function via($notifiable)
	{
		if ($this->payment->active != 1) {
			return false;
		}
		
		if (!empty($this->post->email)) {
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

		$attr = ['slug' => slugify($this->post->title), 'id' => $this->post->id];
		$preview = !isVerifiedPost($this->post) ? '?preview=1' : '';
		$postUrl = lurl($this->post->uri, $attr) . $preview;
        if ($this->package->id == '19') {
            $this->paymentMethod = PaymentMethod::find('2');

            $attr = ['slug' => slugify($this->post->title), 'id' => $this->post->id];
            $preview = !isVerifiedPost($this->post) ? '?preview=1' : '';
            $postUrl = lurl($this->post->uri, $attr) . $preview;

            return (new MailMessage)
                ->subject(trans('mail.payment_sent_for_booking_title'))
                ->greeting(trans('mail.payment_sent_for_booking_content_1'))
                ->line(trans('mail.payment_sent_for_booking_content_2_deposit', [
                    'postUrl' => $postUrl,
                    'title'   => $this->post->title,
                ]))
                ->line(trans('mail.payment_sent_for_booking_content_2_deposit_2'))
                ->line(trans('<br>'))
                ->line(trans('mail.payment_sent_for_booking_deposit_3', [
                    'adId'              => $this->post->id,
                    'packageName'       => (!empty($this->package->short_name)) ? $this->package->short_name : $this->package->name,
                    'amount'            =>0.025 *$this->post->price,
                    'currency'          => $this->package->currency_code,
                    'totalamount'        =>$this->post->price,
                    'paymentMethodName' => $this->paymentMethod->display_name,
                ]))
                ->line(trans('mail.payment_sent_for_booking_content_3'));

        }else{
            if ($this->post->id != '0') {
                return (new MailMessage)
                    ->subject(trans('mail.payment_approved_title'))
                    ->greeting(trans('mail.payment_approved_content_1'))
                    ->line(trans('mail.payment_approved_content_2', [
                        'postUrl' => $postUrl,
                        'title' => $this->post->title,
                    ]))
                    ->line(trans('mail.payment_approved_content_3'))
                    ->line(trans('mail.payment_approved_content_4', [
                        'adId' => $this->post->id,
                        'packageName' => (!empty($this->package->short_name)) ? $this->package->short_name : $this->package->name,
                        'amount' => $this->package->price,
                        'currency' => $this->package->currency_code,
                        'paymentMethodName' => $this->paymentMethod->display_name
                    ]));
            }
	}}
	
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
		return trans('sms.payment_approved_content', ['appName' => config('app.name'), 'title' => $this->post->title]);
	}
}
