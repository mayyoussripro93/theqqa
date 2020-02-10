<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Observer;

use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Scopes\StrictActiveScope;
use Illuminate\Support\Facades\Cache;

class PaymentMethodObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  PaymentMethod $paymentMethod
     * @return void
     */
    public function deleting(PaymentMethod $paymentMethod)
    {
    	/*
		// Delete the payments of this PaymentMethod
		$payments = Payment::withoutGlobalScope(StrictActiveScope::class)->where('payment_method_id', $paymentMethod->id)->get();
		if ($payments->count() > 0) {
			foreach ($payments as $payment) {
				// NOTE: Take account the payment plugins install/uninstall
				$payment->delete();
			}
		}
    	*/
    }
	
	/**
	 * Listen to the Entry saved event.
	 *
	 * @param  PaymentMethod $paymentMethod
	 * @return void
	 */
	public function saved(PaymentMethod $paymentMethod)
	{
		// Removing Entries from the Cache
		$this->clearCache($paymentMethod);
	}
	
	/**
	 * Listen to the Entry deleted event.
	 *
	 * @param  PaymentMethod $paymentMethod
	 * @return void
	 */
	public function deleted(PaymentMethod $paymentMethod)
	{
		// Removing Entries from the Cache
		$this->clearCache($paymentMethod);
	}
	
	/**
	 * Removing the Entity's Entries from the Cache
	 *
	 * @param $paymentMethod
	 */
	private function clearCache($paymentMethod)
	{
		Cache::forget('paymentMethods.all');
	}
}
