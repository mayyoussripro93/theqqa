<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers\API\Account;

use App\Helpers\Arr;
use App\Helpers\Search;
use App\Http\Controllers\API\Search\Traits\PreSearchTrait;
use App\Models\Post;
use App\Models\Category;
use App\Models\SavedPost;
use App\Models\SavedSearch;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use App\Notifications\PostDeleted;
use App\Notifications\PostRepublished;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Notification;
use Jenssegers\Date\Date;
use Torann\LaravelMetaTags\Facades\MetaTag;

class PostsController extends AccountBaseController
{
	use PreSearchTrait;
	
	private $perPage = 12;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->perPage = (is_numeric(config('settings.listing.items_per_page'))) ? config('settings.listing.items_per_page') : $this->perPage;
	}
	
	/**
	 * @param $pagePath
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function getPage($pagePath)
	{
		view()->share('pagePath', $pagePath);
		
		switch ($pagePath) {
			case 'my-posts':
				return $this->getMyPosts();
				break;
			case 'archived':
				return $this->getArchivedPosts($pagePath);
				break;
			case 'favourite':
				return $this->getFavouritePosts();
				break;
			case 'pending-approval':
				return $this->getPendingApprovalPosts();
				break;
			default:
				abort(404);
		}
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getMyPosts()
	{
		$data = [];

		$data['posts'] = $this->myPosts->paginate($this->perPage);
        $i=0;
        $result = explode('/1',url(!currentLocaleShouldBeHiddenInUrl() ));

        foreach ($data['posts'] as $post){
            $data['posts'][$i]->url_picture = isset($post->pictures[0]) ? ($result[0]."/storage/".$post->pictures[0]->file_name) : resize(config('larapen.core.picture.default'));
            \Date::setLocale('ar');

            $i++;
        }
		$data['type'] = 'my-posts';

//        die(var_dump($data['posts'][0]));
		// Meta Tags
		MetaTag::set('title', t('My ads'));
		MetaTag::set('description', t('My ads on :app_name', ['app_name' => config('settings.app.app_name')]));
        return response()->json([
            'status' => 'success',
            'data' =>$data,]);

	}
	
	/**
	 * @param $pagePath
	 * @param null $postId
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function getArchivedPosts($pagePath, $postId = null)
	{
		// If repost
		if (str_contains(url()->current(), $pagePath . '/' . $postId . '/repost')) {
			$post = null;
			if (is_numeric($postId) && $postId > 0) {
				$post = Post::findOrFail($postId);
				
				$attr = ['slug' => slugify($post->title), 'id' => $post->id];
				$preview = !isVerifiedPost($post) ? '?preview=1' : '';
				$postUrl = lurl($post->uri, $attr) . $preview;
				
				if ($post->archived != 0) {
					$post->archived = 0;
					$post->archived_at = null;
					$post->deletion_mail_sent_at = null;
					$post->created_at = Date::now();
					$post->save();
					
					if ($post->archived == 0) {
						flash(t("The repost has done successfully."))->success();
						$mass=t("The repost has done successfully.");
						// Send Confirmation Email or SMS
						if (config('settings.mail.confirmation') == 1) {
							try {
								$post->notify(new PostRepublished($post));
							} catch (\Exception $e) {
								flash($e->getMessage())->error();
							}
						}
					} else {
                        $mass=t("The repost has failed. Please try again.");
						flash(t("The repost has failed. Please try again."))->error();
					}
				} else {
                    $mass=t("The ad is already online.");
					flash(t("The ad is already online."))->error();
				}

                return response()->json([
                    'status' => 'success',
                    'data' =>$mass,
                   ]);

			} else {
                $mass=t("The repost has failed. Please try again.");
//				flash(t("The repost has failed. Please try again."))->error();
			}

            return response()->json([
                'status' => 'success',
                'data' =>$mass,
            ]);
		}
		
		$data = [];
		$data['posts'] = $this->archivedPosts->paginate($this->perPage);
        $i=0;
        $result = explode('/1',url(!currentLocaleShouldBeHiddenInUrl() ));
        foreach ($data['posts'] as $post){
            \Date::setLocale('ar');
            $data['posts'][$i]->url_picture = isset($post->pictures[0]) ? ($result[0]."/storage/".$post->pictures[0]->file_name)  : resize(config('larapen.core.picture.default'));
            $i++;
        }

		// Meta Tags
		MetaTag::set('title', t('My archived ads'));
		MetaTag::set('description', t('My archived ads on :app_name', ['app_name' => config('settings.app.app_name')]));
		
		view()->share('pagePath', $pagePath);
        return response()->json([
            'status' => 'success',
            'data' =>$data,
            'pagePath' =>$pagePath,]);

	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getFavouritePosts()
	{

		$data = [];
		$data['posts'] = $this->favouritePosts->paginate($this->perPage);

          $i=0;
        $result = explode('/1',url(!currentLocaleShouldBeHiddenInUrl() ));

        foreach ($data['posts'] as $post){

            \Date::setLocale('ar');
            $data['posts'][$i]->post->url_picture = isset($post->pictures[0]) ? ($result[0]."/storage/".$post->pictures[0]->file_name)  : resize(config('larapen.core.picture.default'));
            $i++;
        }

		// Meta Tags
		MetaTag::set('title', t('My favourite ads'));
		MetaTag::set('description', t('My favourite ads on :app_name', ['app_name' => config('settings.app.app_name')]));
        return response()->json([
            'status' => 'success',
            'data' =>$data,
            ]);

	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getPendingApprovalPosts()
	{
		$data = [];
		$data['posts'] = $this->pendingPosts->paginate($this->perPage);
        $i=0;
        $result = explode('/1',url(!currentLocaleShouldBeHiddenInUrl() ));
        foreach ($data['posts'] as $post){
            \Date::setLocale('ar');
            $data['posts'][$i]->url_picture = isset($post->pictures[0]) ? ($result[0]."/storage/".$post->pictures[0]->file_name) : resize(config('larapen.core.picture.default'));
            $i++;
        }
        
		// Meta Tags
		MetaTag::set('title', t('My pending approval ads'));
		MetaTag::set('description', t('My pending approval ads on :app_name', ['app_name' => config('settings.app.app_name')]));
        return response()->json([
            'status' => 'success',
            'data' =>$data,
          ]);

	}
	
	/**
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getSavedSearch(HttpRequest $request)
	{

		$data = [];
		
		// Get QueryString
		$tmp = parse_url(url(request()->getRequestUri()));
		$queryString = (isset($tmp['query']) ? $tmp['query'] : 'false');
		$queryString = preg_replace('|\&pag[^=]*=[0-9]*|i', '', $queryString);
		
		// CATEGORIES COLLECTION
		$cats = Category::trans()->orderBy('lft')->get();
		$cats = collect($cats)->keyBy('translation_of');
		view()->share('cats', $cats);
		
		// Search
		$savedSearch = SavedSearch::where('country_code', 'SA')
			->where('user_id', auth()->user()->id)
			->orderBy('created_at', 'DESC')
			->simplePaginate($this->perPage, ['*'], 'pag');

		if (collect($savedSearch->getCollection())->keyBy('query')->keys()->contains($queryString)) {
			parse_str($queryString, $queryArray);
			
			// QueryString vars
			$cityId = isset($queryArray['l']) ? $queryArray['l'] : null;
			$location = isset($queryArray['location']) ? $queryArray['location'] : null;
			$adminName = (isset($queryArray['r']) && !isset($queryArray['l'])) ? $queryArray['r'] : null;
			
			// Pre-Search
			$preSearch = [
				'city'  => $this->getCity($cityId, $location),
				'admin' => $this->getAdmin($adminName),
			];

			if ($savedSearch->getCollection()->count() > 0) {

				// Search
				$search = new Search($preSearch);

				$data = $search->fechAll();
			}
		}
		$data['savedSearch'] = $savedSearch;
		
		// Meta Tags
		MetaTag::set('title', t('My saved search'));
		MetaTag::set('description', t('My saved search on :app_name', ['app_name' => config('settings.app.app_name')]));
		
		view()->share('pagePath', 'saved-search');
        return response()->json([
            'status' => 'success',
            'data' =>$data,
            'cats'  =>$cats,
            'pagePath' => 'saved-search']);

	}
	
	/**
	 * @param $pagePath
	 * @param null $id
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function destroy($pagePath, $id = null)
	{
		// Get Entries ID
		$ids = [];
		if (request()->filled('entries')) {
			$ids = request()->input('entries');
		} else {
			if (!is_numeric($id) && $id <= 0) {
				$ids = [];
			} else {
				$ids[] = $id;
			}
		}
		
		// Delete
		$nb = 0;
		if ($pagePath == 'favourite') {
			$savedPosts = SavedPost::where('user_id', auth()->user()->id)->whereIn('post_id', $ids);
			if ($savedPosts->count() > 0) {
				$nb = $savedPosts->delete();
			}
		} elseif ($pagePath == 'saved-search') {
			$nb = SavedSearch::destroy($ids);
		} else {
			foreach ($ids as $item) {
				$post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->find($item);
				if (!empty($post)) {
					$tmpPost = Arr::toObject($post->toArray());
					
					// Delete Entry
					$nb = $post->delete();
					
					// Send an Email confirmation
					if (!empty($tmpPost->email)) {
						if (config('settings.mail.confirmation') == 1) {
							try {
								Notification::route('mail', $tmpPost->email)->notify(new PostDeleted($tmpPost));
							} catch (\Exception $e) {
								flash($e->getMessage())->error();
							}
						}
					}
				}
			}
		}
		
		// Confirmation
		if ($nb == 0) {
			flash(t("No deletion is done. Please try again."))->error();
		} else {
			$count = count($ids);
			if ($count > 1) {
				$message = t("x :entities has been deleted successfully.", ['entities' => t('ads'), 'count' => $count]);
			} else {
				$message = t("1 :entity has been deleted successfully.", ['entity' => t('ad')]);
			}
			flash($message)->success();
		}
        return response()->json([
            'status' => 'success',
            'data' =>'deleted done',
            'pagePath' =>$pagePath,
       ]);
		return redirect(config('app.locale') . '/account/' . $pagePath);
	}
}
