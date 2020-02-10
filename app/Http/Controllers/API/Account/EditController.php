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

use App\Http\Controllers\API\Auth\Traits\VerificationTrait;
use App\Http\Requests\UserRequest;
use App\Models\City;
use App\Models\PostType;
use App\Models\Scopes\VerifiedScope;
use App\Models\UserType;
use Creativeorange\Gravatar\Facades\Gravatar;
use App\Models\Post;
use App\Models\SavedPost;
use App\Models\Gender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Intervention\Image\Facades\Image;
class EditController extends AccountBaseController
{
	use VerificationTrait;
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{

		$data = [];
        $data['cities'] =  City::where('country_code','SA')->orderBy('name')->get();
//		$data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		$data['genders'] = Gender::where('translation_lang','ar')->get();

		$data['gravatar'] = (!empty(auth()->user()->email)) ? Gravatar::fallback(url('images/user.jpg'))->get(auth()->user()->email) : null;
		$data['userPhoto'] = $data['gravatar'];
        $data['user'] =User::where('id',Auth::user()->id)->get();

		if (!empty(auth()->user()->photo)) {
			$data['userPhoto'] = resize(auth()->user()->photo);
		}
		
		// Mini Stats
		$data['countPostsVisits'] = DB::table('posts')
			->select('user_id', DB::raw('SUM(visits) as total_visits'))
			->where('country_code', config('country.code'))
			->where('user_id', auth()->user()->id)
			->groupBy('user_id')
			->first();
		$data['countPosts'] = Post::where('country_code','SA')
			->where('user_id', auth()->user()->id)
			->count();
		$data['countFavoritePosts'] = SavedPost::whereHas('post', function ($query) {
			$query->where('country_code','SA');
		})->where('user_id', auth()->user()->id)
			->count();

		// Meta Tags
		MetaTag::set('title', t('My account'));
		MetaTag::set('description', t('My account on :app_name', ['app_name' => config('settings.app.app_name')]));


        $postTypes = PostType::orderBy('lft')->get();
        foreach ($postTypes as $posttype){
            $posttype->user_type_id = explode(',',$posttype->user_type_id);
        }

        foreach ( $postTypes as $postType) {

            if (!empty(auth()->user())){
                if (is_array($postType->user_type_id)) {

                    if (in_array(auth()->user()->user_type_id, $postType->user_type_id)) {
                        $data['post_type_id']=$postType->id ;
                    }
                }else{
                    if ($postType->user_type_id == auth()->user()->user_type_id) {
                        $data['post_type_id']=$postType->id ;                      }
                }
            } else{
                if (is_array($postType->user_type_id)) {
                    if (in_array(2, $postType->user_type_id)) {
                        $data['post_type_id'] = $postType->id;
                    }
                } else{
                    if ($postType->user_type_id == '2') {
                        $data['post_type_id']=$postType->id ;
                    }
                }
            }}
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);


	}
	
	/**
	 * @param UserRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function updateDetails(UserRequest $request)
	{
		// Check if these fields has changed
		$emailChanged = $request->filled('email') && $request->input('email') != auth()->user()->email;
		$phoneChanged = $request->filled('phone') && $request->input('phone') != auth()->user()->phone;
		$usernameChanged = $request->filled('username') && $request->input('username') != auth()->user()->username;
		
		// Conditions to Verify User's Email or Phone
		$emailVerificationRequired = config('settings.mail.email_verification') == 1 && $emailChanged;
		$phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $phoneChanged;
		
		// Get User
		$user = User::withoutGlobalScopes([VerifiedScope::class])->find(auth()->user()->id);
		
		// Update User
		$input = $request->only($user->getFillable());

            foreach ($input as $key => $value) {
                if (in_array($key, ['email', 'phone', 'username']) && empty($value)) {
                    continue;
                }
                $user->{$key} = $value;
            }

        if($user->user_type_id == 2){
        $user->id_number =$request->input('id_number');
        }else {
            $user->id_number_owner = $request->input('id_number_owner');
        }
		$user->phone_hidden = $request->input('phone_hidden');
		
		// Email verification key generation
		if ($emailVerificationRequired) {
			$user->email_token = md5(microtime() . mt_rand());
			$user->verified_email = 0;
		}
		
		// Phone verification key generation
		if ($phoneVerificationRequired) {
			$user->phone_token = mt_rand(100000, 999999);
			$user->verified_phone = 0;
		}
		
		// Don't logout the User (See User model)
		if ($emailVerificationRequired || $phoneVerificationRequired) {
			session(['emailOrPhoneChanged' => true]);
		}
		
		// Save
		$user->save();
		
		// Message Notification & Redirection
		flash(t("Your details account has updated successfully."))->success();
		$nextUrl = config('app.locale') . '/account';
		
		// Send Email Verification message
		if ($emailVerificationRequired) {
			$this->sendVerificationEmail($user);
			$this->showReSendVerificationEmailLink($user, 'user');
		}
		
		// Send Phone Verification message
		if ($phoneVerificationRequired) {
			// Save the Next URL before verification
			session(['itemNextUrl' => $nextUrl]);
			
			$this->sendVerificationSms($user);
			$this->showReSendVerificationSmsLink($user, 'user');
			
			// Go to Phone Number verification
			$nextUrl = config('app.locale') . '/verify/user/phone/';
		}
		
		// Redirection
		 return response()->json([
        'status' => 'success',
        'data' =>'saved data',]);
	}
	
	/**
	 * Store the User's photo.
	 *
	 * @param $userId
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function updatePhoto($userId, Request $request)
	{
		// Get User
		$user = User::find($userId);
		
		if (empty($user)) {
			if ($request->ajax()) {
				return response()->json(['error' => t('User not found')]);
			}
			abort(404);
		}
		
		// Save all pictures
//		$file = $request->file('photo');
        $file="test.jpg";
        $ifp = fopen( $file, 'wb' );


        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode($request->photo ) );

        // clean up the file resource
        fclose( $ifp );
        $extension_purchaser_id_image = getUploadedFileExtension($file);
        if (empty($extension_purchaser_id_image)) {
            $extension_purchaser_id_image = 'jpg';
        }
        // Make the image
        $image_purchaser_id_image = Image::make($file)->resize(400, 400, function ($constraint) {
            $constraint->aspectRatio();
        });
        // Generate a filename.
        $filename_purchaser_id_image = md5($file . time()) . '.' . $extension_purchaser_id_image;
        $destination_path = 'avatars/sa/'.$userId.'/';
        // Store the image on disk.
        Storage::disk('public')->put($destination_path . '/' . $filename_purchaser_id_image, $image_purchaser_id_image->stream());
		if (!empty($file)) {
			// Post Picture in database
			$user->photo =  '/'.$filename_purchaser_id_image;
			$user->save();
		}
		
		// Ajax response
		if ($request->ajax()) {
			$data = [];
			$data['initialPreview'] = [];
			$data['initialPreviewConfig'] = [];
			
			if (!empty($user->photo)) {
				// Get Deletion Url
				$initialPreviewConfigUrl = lurl('account/' . $user->id . '/photo/delete');
				
				// Build Bootstrap-Input plugin's parameters
				$data['initialPreview'][] = resize($user->photo);
				
				$data['initialPreviewConfig'][] = [
					'caption' => last(explode('/', $user->photo)),
					'size'    => (int)File::size(filePath($user->photo)),
					'url'     => $initialPreviewConfigUrl,
					'key'     => $user->id,
					'extra'   => ['id' => $user->id],
				];
			}
			
			return response()->json($data);
		}
		

        return response()->json([
            'status' => 'success',
            'data' =>'Your photo or avatar have been updated',
            'userPhoto' => asset('storage/'.$user->photo) ,]);


		

	}
	
	/**
	 * Delete the User's photo
	 *
	 * @param $userId
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function deletePhoto($userId, Request $request)
	{

		if (isDemo()) {
			$message = t('This feature has been turned off in demo mode.');
			
			if ($request->ajax()) {
				return response()->json(['error' => $message]);
			}
			
			flash($message)->info();
			
			return back();
		}
		
		// Get User
		$user = User::find($userId);
		
		if (empty($user)) {
			if ($request->ajax()) {
				return response()->json(['error' => t('User not found')]);
			}
			abort(404);
		}
		
		// Remove all the current user's photos, by removing his photo directory.
		$destinationPath = substr($user->photo, 0, strrpos($user->photo, '/'));
		Storage::deleteDirectory($destinationPath);
		
		// Delete the photo path from DB
		$user->photo = null;
		$user->save();
		
		if ($request->ajax()) {
			return response()->json([]);
		}

        return response()->json([
            'status' => 'success',
            'data' =>'Your photo or avatar has been deleted',]);
	}
	
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function updateSettings(Request $request)
	{

		// Validation
		if ($request->filled('password')) {

			$rules = ['password' => 'between:6,60|dumbpwd|confirmed'];
			$this->validate($request, $rules);
		}

		// Get User
		$user = User::find(auth()->user()->id);
//		$hash_password= Hash::make($request->input('current_password'));
		// Update
		$user->disable_comments = (int)$request->input('disable_comments');


        if (  Hash::check($request->input('current_password'),$user->password  )) {
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            // Save
            $user->save();

            return response()->json([
                'status' => 'success',
                'data' =>'password changed',]);

        }else{
            return response()->json([
                'status' => 'error',
                'data' =>'your current password is invalid',]);
        }

	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function updatePreferences()
	{
		$data = [];
		
		return view('account.edit', $data);
	}
}
