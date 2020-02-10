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

use App\Http\Requests\ReplyMessageRequest;
use App\Models\User;
use App\Models\Message;
use App\Notifications\ReplySent;
use Torann\LaravelMetaTags\Facades\MetaTag;

class ConversationsController extends AccountBaseController
{
	private $perPage = 10;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->perPage = (is_numeric(config('settings.listing.items_per_page'))) ? config('settings.listing.items_per_page') : $this->perPage;
	}
	
	/**
	 * Conversations List
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{

		$data = [];
		
		// Set the Page Path
		view()->share('pagePath', 'conversations');
		
		// Get the Conversations
        \Date::setLocale('ar');
		$data['conversations'] = $this->conversations->paginate($this->perPage);
        $i=0;
        foreach ($data['conversations'] as $post){

            \Date::setLocale('ar');

            $data['conversations'][$i]->created =     \Date::parse( $data['conversations'][$i]->created_at)->timezone(config('timezone.id'))->ago();


            $i++;
        }
//        $data['countMessagesUnread']   ='		<a{!! ($pagePath==\'conversations\') ? \' class="active"\' : \'\' !!} href="{{ lurl(\'account/conversations\') }}">
//							<i class="icon-mail-1"></i> {{ t(\'Conversations\') }}&nbsp;
//							<span class="badge badge-pill">
//								{{ isset($countConversations) ? \App\Helpers\Number::short($countConversations) : 0 }}
//							</span>&nbsp;
//							<span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>
//							</a>';
		// Meta Tags
		MetaTag::set('title', t('Conversations Received'));
		MetaTag::set('description', t('Conversations Received on :app_name', ['app_name' => config('settings.app.app_name')]));

        return response()->json([
            'status' => 'success',
            'data' =>$data,]);

	}
	
	/**
	 * Conversation Messages List
	 *
	 * @param $conversationId
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function messages($conversationId)
	{

		$data = [];
		
		// Set the Page Path
		view()->share('pagePath', 'conversations');
		
		// Get the Conversation
		$conversation = Message::where('id', $conversationId)
			->byUserId(auth()->user()->id)
			->firstOrFail();
		view()->share('conversation', $conversation);
		
		// Get the Conversation's Messages
		$data['messages'] = Message::where('parent_id', $conversation->id)
			->byUserId(auth()->user()->id)
			->orderByDesc('id');

		$data['countMessages'] = $data['messages']->count();
		$data['messages'] = $data['messages']->paginate($this->perPage);

        $i=0;
        foreach ($data['messages'] as $post){
            $result = explode("<br><br>", $post->message);
            $data['messages'][$i]->message= $result[0];
            $i++;
        }
		// Mark the Conversation as Read
		if ($conversation->is_read != 1) {
			if ($data['countMessages'] > 0) {
				// Check if the latest Message is from the current logged user
				if ($data['messages']->has(0)) {
					$latestMessage = $data['messages']->get(0);
					if ($latestMessage->from_user_id != auth()->user()->id) {
						$conversation->is_read = 1;
						$conversation->save();
					}
				}
			} else {
				if ($conversation->from_user_id != auth()->user()->id) {
					$conversation->is_read = 1;
					$conversation->save();
				}
			}
		}
        $result = explode("<br><br>", $conversation->message);
        $conversation->post_url=  $conversation->subject.'/'. $conversation->post_id ;
        $conversation->message= $result[0] ;


        \Date::setLocale('ar');

        $conversation->created_at_ta =     \Date::parse($conversation->created_at)->timezone(config('timezone.id'))->ago();
		// Meta Tags
		MetaTag::set('title', t('Messages Received'));
		MetaTag::set('description', t('Messages Received on :app_name', ['app_name' => config('settings.app.app_name')]));
        $isJson = json_decode($conversation->message);

        $conversation->message=$isJson[0];


        return response()->json([
            'status' => 'success',
            'pagePath' => 'conversations',
            'conversation' => $conversation,
            'data' =>$data,
            ]);

	}
	
	/**
	 * @param $conversationId
	 * @param ReplyMessageRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function reply($conversationId, ReplyMessageRequest $request)
	{
		// Get Conversation
		$conversation = Message::findOrFail($conversationId);
		
		// Get Recipient Data
		if ($conversation->from_user_id != auth()->user()->id) {
			$toUserId = $conversation->from_user_id;
			$toName = $conversation->from_name;
			$toEmail = $conversation->from_email;
			$toPhone = $conversation->from_phone;
		} else {
			$toUserId = $conversation->to_user_id;
			$toName = $conversation->to_name;
			$toEmail = $conversation->to_email;
			$toPhone = $conversation->to_phone;
		}
		
		// Don't reply to deleted (or non exiting) users
		if (config('settings.single.guests_can_post_ads') != 1 && config('settings.single.guests_can_contact_seller') != 1) {
			if (User::where('id', $toUserId)->count() <= 0) {
				flash(t("This user no longer exists.") . ' ' . t("Maybe the user's account has been disabled or deleted."))->error();
				return back();
			}
		}
		
		// New Message
		$message = new Message();
		$input = $request->only($message->getFillable());
		foreach ($input as $key => $value) {
			$message->{$key} = $value;
		}
		
		$message->post_id = $conversation->post->id;
		$message->parent_id = $conversation->id;
		$message->from_user_id = auth()->user()->id;
		$message->from_name = auth()->user()->name;
		$message->from_email = auth()->user()->email;
		$message->from_phone = auth()->user()->phone;
		$message->to_user_id = $toUserId;
		$message->to_name = $toName;
		$message->to_email = $toEmail;
		$message->to_phone = $toPhone;
		$message->subject = 'RE: ' . $conversation->subject;
		
		$attr = ['slug' => slugify($conversation->post->title), 'id' => $conversation->post->id];
		$message->message = $request->input('message')
			. '<br><br>'
			. t('Related to the ad')
			. ': <a href="' . lurl($conversation->post->uri, $attr) . '">' . t('Click here to see') . '</a>';
		
		// Save
		$message->save();
		
		// Save and Send user's resume
		if ($request->hasFile('filename')) {
			$message->filename = $request->file('filename');
			$message->save();
		}
		
		// Mark the Conversation as Unread
		if ($conversation->is_read != 0) {
			$conversation->is_read = 0;
			$conversation->save();
		}
		
		// Send Reply Email
		try {
			$conversation->notify(new ReplySent($message));
			flash(t("Your reply has been sent. Thank you!"))->success();
            return response()->json([
                'status' => 'success',
                'pagePath' => 'Your reply has been sent. Thank you!',
            ]);
		} catch (\Exception $e) {
			flash($e->getMessage())->error();
            return response()->json([
                'status' => 'error',
                'pagePath' => $e->getMessage() ,
            ]);
		}


	}
	
	/**
	 * Delete Conversation
	 *
	 * @param null $conversationId
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy($conversationId = null)
	{
		// Get Entries ID
		$ids = [];
		if (request()->filled('entries')) {
			$ids = request()->input('entries');
		} else {
			if (!is_numeric($conversationId) && $conversationId <= 0) {
				$ids = [];
			} else {
				$ids[] = $conversationId;
			}
		}
		
		// Delete
		$nb = 0;
		foreach ($ids as $item) {
			// Get the conversation
			$message = Message::where('id', $item)
				->byUserId(auth()->user()->id)
				->first();
			
			if (!empty($message)) {
				if (empty($message->deleted_by)) {
					// Delete the Entry for current user
					$message->deleted_by = auth()->user()->id;
					$message->save();
					$nb = 1;
				} else {
					// If the 2nd user delete the Entry,
					// Delete the Entry (definitely)
					if ($message->deleted_by != auth()->user()->id) {
						$nb = $message->delete();
					}
				}
			}
		}
		
		// Confirmation
		if ($nb == 0) {
			flash(t("No deletion is done. Please try again."))->error();
            return response()->json([
                'status' => 'error',
                'pagePath' => 'No deletion is done. Please try again.',
            ]);
		} else {
			$count = count($ids);
			if ($count > 1) {
				flash(t("x :entities has been deleted successfully.", ['entities' => t('messages'), 'count' => $count]))->success();
			} else {
				flash(t("1 :entity has been deleted successfully.", ['entity' => t('message')]))->success();
			}
		}

        return response()->json([
            'status' => 'success',
            'pagePath' => 'deleted successfully',
        ]);
	}
	
	/**
	 * Delete Message
	 *
	 * @param $conversationId
	 * @param null $messageId
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroyMessages($conversationId, $messageId = null)
	{
		// Get Entries ID
		$ids = [];
		if (request()->filled('entries')) {
			$ids = request()->input('entries');
		} else {
			if (!is_numeric($messageId) && $messageId <= 0) {
				$ids = [];
			} else {
				$ids[] = $messageId;
			}
		}
		
		// Delete
		$nb = 0;
		foreach ($ids as $item) {
			// Don't delete the main conversation
			if ($item == $conversationId) {
				continue;
			}
			
			// Get the message
			$message = Message::where('parent_id', $conversationId)->where('id', $item)
				->byUserId(auth()->user()->id)
				->first();
			
			if (!empty($message)) {
				if (empty($message->deleted_by)) {
					// Delete the Entry for current user
					$message->deleted_by = auth()->user()->id;
					$message->save();
					$nb = 1;
				} else {
					// If the 2nd user delete the Entry,
					// Delete the Entry (definitely)
					if ($message->deleted_by != auth()->user()->id) {
						$nb = $message->delete();
					}
				}
			}
		}
		
		// Confirmation
		if ($nb == 0) {
			flash(t("No deletion is done. Please try again."))->error();
            return response()->json([
                'status' => 'error',
                'pagePath' => 'No deletion is done. Please try again.',
            ]);
		} else {
			$count = count($ids);
			if ($count > 1) {
				flash(t("x :entities has been deleted successfully.", ['entities' => t('messages'), 'count' => $count]))->success();
			} else {
				flash(t("1 :entity has been deleted successfully.", ['entity' => t('message')]))->success();
			}
		}

        return response()->json([
            'status' => 'success',
            'pagePath' => 'deleted successfully',
        ]);
	}
}
