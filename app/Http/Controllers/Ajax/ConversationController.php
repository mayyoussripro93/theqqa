<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers\Ajax;

use App\Models\Message;
use App\Http\Controllers\FrontController;
use Illuminate\Http\Request;

class ConversationController extends FrontController
{
	/**
	 * MessageController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function checkNewMessages(Request $request)
	{
		$countLimit = 20;
		$countConversationsWithNewMessages = 0;
		$oldValue = $request->input('oldValue');
		$languageCode = $request->input('languageCode');
		
		if (auth()->check()) {
			$countConversationsWithNewMessages = Message::countConversationsWithNewMessages($countLimit);
		}
		
		$result = [
			'logged'                            => (auth()->check()) ? auth()->user()->id : 0,
			'countLimit'                        => (int)$countLimit,
			'countConversationsWithNewMessages' => (int)$countConversationsWithNewMessages,
			'oldValue'                          => (int)$oldValue,
			'loginUrl'                          => url(config('lang.abbr') . '/' . trans('routes.login')),
		];
		
		return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
	}
}
