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
use App\Models\Package;
use App\Models\PaymentMethod;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Post;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class PaymentSent extends Notification implements ShouldQueue
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
	{ if ($this->payment->package_id == '19') {

        $attr = ['slug' => slugify($this->post->title), 'id' => $this->post->id];
        $preview = !isVerifiedPost($this->post) ? '?preview=1' : '';
        $postUrl = lurl($this->post->uri, $attr) . $preview;

        return (new MailMessage)
            ->subject(trans('mail.payment_sent_title'))
            ->greeting(trans('mail.payment_sent_content_1'))
            ->line(trans('mail.payment_sent_content_2_deposit', [
                'postUrl' => $postUrl,
                'title'   => $this->post->title,
            ]))
            ->line(trans(' mail.Booking will be confirmed within 48 hours via email'))
            ->line(trans('mail.If not confirmed, we will contact you to refund the payment'))
            ->line(trans('<br>'))
            ->line(trans('mail.payment_sent__deposit_3', [
                'adId'              => $this->post->id,
                'packageName'       => (!empty($this->package->short_name)) ? $this->package->short_name : $this->package->name,
                'amount'            =>0.025 *$this->post->price,
                'currency'          => $this->package->currency_code,
                'totalamount'        =>$this->post->price,
                'paymentMethodName' => $this->paymentMethod->display_name
            ]))
            ->line(trans('mail.payment_sent_content_3'));

    }else {
        $attr = ['slug' => slugify($this->post->title), 'id' => $this->post->id];
        $preview = !isVerifiedPost($this->post) ? '?preview=1' : '';
        $postUrl = lurl($this->post->uri, $attr) . $preview;

        return (new MailMessage)
            ->subject(trans('mail.payment_sent_title'))
            ->greeting(trans('mail.payment_sent_content_1'))
            ->line(trans('mail.payment_sent_content_2', [
                'postUrl' => $postUrl,
                'title'   => $this->post->title,
            ]))
            ->line(trans('mail.payment_sent_content_3'));
    }

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
		return trans('sms.payment_sent_content', ['appName' => config('app.name'), 'title' => $this->post->title]);
	}
}
