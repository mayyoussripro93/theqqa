<?php
/**
 * Theqqa - Geo Classified Ads Software
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers\API\Account;


use Torann\LaravelMetaTags\Facades\MetaTag;

class TransactionsController extends AccountBaseController
{
	private $perPage = 10;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->perPage = (is_numeric(config('settings.listing.items_per_page'))) ? config('settings.listing.items_per_page') : $this->perPage;
	}
	
	/**
	 * List Transactions
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$data = [];
        \Date::setLocale('ar');
		$data['transactions'] = $this->transactions->paginate($this->perPage);
        $i=0;
        foreach ($data['transactions'] as $transaction){
            \Date::setLocale('ar');
            $data['transactions'][$i]->created_at_ta = \Date::parse($transaction->created_at)->timezone(config('timezone.id'))->ago();


            $i++;
        }


		view()->share('pagePath', 'transactions');
		
		// Meta Tags
		MetaTag::set('title', t('My Transactions'));
		MetaTag::set('description', t('My Transactions on :app_name', ['app_name' => config('settings.app.app_name')]));
        return response()->json([
            'status' => 'success',
            'data' =>$data,]);
	
	}
}
