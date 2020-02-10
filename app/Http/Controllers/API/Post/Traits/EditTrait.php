<?php
/**
 * Theqqa - Geo Classified Ads Software
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Http\Controllers\API\Post\Traits;

use App\Helpers\Ip;
use App\Http\Requests\PostApiRequest;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Payment as PaymentModel;

use App\Models\Picture;
use App\Models\Post;
use App\Models\City;
use App\Models\PostType;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use PHPUnit\Util\Filesystem;
use Torann\LaravelMetaTags\Facades\MetaTag;

trait EditTrait
{
    /**
     * Show the form the create a new ad post.
     *
     * @param $postIdOrToken
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUpdateForm($postIdOrToken)
    {
        $data = [];

        // Get Post
        if (getSegment(2) == 'create') {

            if (!session()->has('tmpPostId')) {
                return redirect('posts/create');
            }
            $post = Post::with(['city'])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('id', session('tmpPostId'))
				->where('tmp_token', $postIdOrToken)
         ->with([
             'category' => function ($builder) { $builder->with(['parent']); },
             'postType',
             'city',
             'pictures',
             'latestPayment' => function ($builder) { $builder->with(['package']); },
         ])
				->first();
            $pictures = \App\Models\Picture::where('post_id', $postIdOrToken)->orderBy('position')->orderBy('id');
            if ($pictures->count() > 0) {
                $postImg = resize($pictures->first()->filename, 'medium');
            } else {
                $postImg = resize(config('larapen.core.picture.default'));}
            $post->url_picture=$postImg;



    } else {
            $post = Post::with(['city'])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
//				->where('user_id', auth()->user()->id)
				->where('id', $postIdOrToken)
                ->with([
                    'category' => function ($builder) { $builder->with(['parent']); },
                    'postType',
                    'city',
                    'pictures',
                    'latestPayment' => function ($builder) { $builder->with(['package']); },
                ])
				->first();
            $pictures = \App\Models\Picture::where('post_id', $postIdOrToken)->orderBy('position')->orderBy('id');
            if ($pictures->count() > 0) {
                $postImg = resize($pictures->first()->filename, 'medium');
            } else {
                $postImg = resize(config('larapen.core.picture.default'));}
            $post->url_picture=$postImg;
        }

        if (empty($post)) {
            abort(404);
        }
        // Get Category nested IDs
        $catNestedIds = (object)[
            'parentId' => $post->category->parent_id,
            'id'       => $post->category->tid,
        ];

        $customFields = $this->getPostFieldsValues($catNestedIds, $postIdOrToken);
 
//        view()->share('post', $post);
        
        // Get the Post's Administrative Division
//        if (config('country.admin_field_active') == 1 && in_array(config('country.admin_type'), ['1', '2'])) {
//            if (!empty($post->city)) {
//                $adminType = config('country.admin_type');
//                $adminModel = '\App\Models\SubAdmin' . $adminType;
//
//                // Get the City's Administrative Division
//                $admin = $adminModel::where('code', $post->city->{'subadmin' . $adminType . '_code'})->first();
//                if (!empty($admin)) {
//                    view()->share('admin', $admin);
//                }
//            }
//        }
        
        // Meta Tags
        MetaTag::set('title', t('Update My Ad'));
        MetaTag::set('description', t('Update My Ad'));
        $post->bank_url=PaymentModel::where("post_id", $post->id)->where("user_id",  auth()->user()->id)->first();
        unset($post->lon,$post->lat,$post->py_package_id,$post->calculatedPrice,$post->partner,$post->fb_profile,$post->deletion_mail_sent_at,$post->country_code
           , $post->ip_addr,$post->phone_token,$post->tmp_token, $post->archived_at,$post->updated_at,$post->deleted_at);
        unset($post->city->latitude,$post->city->longitude,$post->city->feature_class,$post->city->feature_code,$post->city->subadmin1_code,$post->city->subadmin2_code,$post->city->population,$post->city->time_zone
        );

        unset($post->category->rgt,$post->category->lft,$post->category->type,$post->category->icon_class,$post->category->depth,$post->category->active,$post->category->tid);
//        unset($post_type->rgt,$post_type->lft,$post_type->icon_class,$post_type->depth,$post_type->active,$post_type->tid);

        // unset($post->category->parent->rgt,$post->category->parent->lft,$post->category->parent->type,$post->category->parent->icon_class,$post->category->parent->depth,$post->category->parent->active,$post->category->parent->tid);
        $reqdocuments = '<p>
									<div style="font-size: 16px"><i class="icon-docs"></i>'.
            t('Now you can publish your ad for free or special on Theqqa .There are no documents required in the case of free advertising').'
									</div>
								</p>
								<h3>
									<strong><i class="fa fa-plus-circle"></i>'.  t('Featured Ad (Paid)').'</strong>
								</h3>

								<p>'. html_entity_decode(t('For_service_alert_2'), ENT_COMPAT).'</p>';
       return response()->json([
            'status' => 'success',
            'data' => $post,
            'customFields'=>$customFields,
           'reqdocuments'=>$reqdocuments,
        ]);
    }
    
    /**
     * Update the Post
     *
     * @param $postIdOrToken
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postUpdateForm($postIdOrToken, PostApiRequest $request)
    {

        // Get Post
        if (getSegment(2) == 'create') {
            if (!session()->has('tmpPostId')) {
                return redirect('posts/create');
            }
            $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('id', session('tmpPostId'))
				->where('tmp_token', $postIdOrToken)
				->first();
        } else {
            $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('user_id', auth()->user()->id)
				->where('id', $postIdOrToken)
				->first();
        }
        
        if (empty($post)) {
            abort(404);
        }
        
        // Get the Post's City
        $city = City::find($request->input('city_id', 0));
        if (empty($city)) {
            flash(t("Posting Ads was disabled for this time. Please try later. Thank you."))->error();
            
            return back()->withInput();
        }
        
        // Conditions to Verify User's Email or Phone
        $emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email') && $request->input('email') != $post->email;
        $phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone') && $request->input('phone') != $post->phone;
	
		/*
		 * Allow admin users to approve the changes,
		 * If the ads approbation option is enable,
		 * And if important data have been changed.
		 */
		if (config('settings.single.posts_review_activation')) {
			if (
				md5($post->title) != md5($request->input('title')) ||
				md5($post->description) != md5($request->input('description'))
			) {
				$post->reviewed = 0;
			}
		}
        
        // Update Post
		$input = $request->only($post->getFillable());
		foreach ($input as $key => $value) {
			$post->{$key} = $value;
		}
//        $post->post_type_id =$request->post_type_id ;
//        $post->negotiable = $request->input('negotiable');
//		$post->phone_hidden = $request->input('phone_hidden');
//		$post->lat = $city->latitude;
//        $post->lon = $city->longitude;
//        $post->ip_addr = Ip::get();
        $post->country_code ='SA';
        $post->user_id = (auth()->check()) ? auth()->user()->id : 0;
        $post->negotiable = $request->input('negotiable');
        $post->post_type_id =$request->post_type_id ;
        $post->phone_hidden = $request->input('phone_hidden');
        $post->tags =$request->tags ;
        $post->lat = $city->latitude;
        $post->lon = $city->longitude;
        $post->ip_addr = Ip::get();
        $post->price = $request->price ;
        $post->tmp_token = md5(microtime() . mt_rand(100000, 999999));
        $post->verified_email = 1;
        $post->verified_phone = 1;
        if ($request->bank_transfer_in != 0) {
            $post->featured = 1 ;
        }
        // Email verification key generation
        if ($emailVerificationRequired) {
            $post->email_token = md5(microtime() . mt_rand());
            $post->verified_email = 0;
        }
        
        // Phone verification key generation
        if ($phoneVerificationRequired) {
            $post->phone_token = mt_rand(100000, 999999);
            $post->verified_phone = 0;
        }
        
        // Save
        $post->save();
    
        // Custom Fields
        $this->createPostFieldsValues($post, $request);
//
//        $pictures = [];
//        $files = $request->file('pictures');
//
//
////        if (count($files) > 0) {
////            foreach ($files as $key1 => $file) {
////                if (empty($file)) {
////                    continue;
////                }}}
//        $picture = new Picture([
//            'post_id'  => $post->id,
//            'filename' => $request->filename,
//            'position' => (int)$key + 1,
//        ]);
//        $picture->save();
        // Save all pictures
        $pictures = [];
        $files = $request->pictures;
        if(!empty($files)){
            \File::deleteDirectory(base_path('public/storage/files/sa'."/$post->id"));
            \DB::table('pictures')->where('post_id', '=', $post->id)->delete();
            if (count($files) > 0) {
                foreach ($files as $key => $file) {
                    if (empty($file)) {
                        continue;
                    }


                    $name=$file->getClientOriginalName();
                    $file->move(public_path()."/storage/files/sa"."/$post->id/", $name);
                    // Give the Complete Path of the folder where you want to save the image
                    $folder=public_path()."/storage/files/sa"."/$post->id/";

                    $file=$folder.$name;
                    $uploadimage=$file;
                    $newname=$name;
                    // Set the thumbnail name
                    $thumbnail = $folder.$newname."_thumbnail.jpg";
                    // Load the mian image
                    $ext = pathinfo($uploadimage, PATHINFO_EXTENSION);


                    if($ext == 'jpeg' or $ext == 'jpg')
                        $source = imagecreatefromjpeg($uploadimage);
                    elseif ($ext == 'png')
                        $source = imagecreatefrompng($uploadimage);
                    elseif ($ext == 'gif')
                        $source = imagecreatefromgif($uploadimage);



                    // load the image you want to you want to be watermarked
                    $watermark = imagecreatefrompng(storage_path().'/files/'.'watermark.png');

                    // get the width and height of the watermark image
                    $water_width = imagesx($watermark);
                    $water_height = imagesy($watermark);

                    // Set the dimension of the area you want to place your watermark we use 0
                    // from x-axis and 0 from y-axis
                    $dime_x = 0;
                    $dime_y = 0;

                    // copy both the images
                    imagecopy($source, $watermark, $dime_x, $dime_y, 0, 0, $water_width, $water_height);

                    // Final processing Creating The Image
                    imagejpeg($source, $thumbnail, 100);
                    $imgwm = 'simpletext'.time().".png";

                    $f1=imagepng($source, $folder.$imgwm);
                    imagedestroy($source);


                    // Delete old file if new file has uploaded
                    // Check if current Post have a pictures
                    $picture = Picture::find($key);
                    if (!empty($picture)) {
                        // Delete old file
                        $picture->delete($picture->id);
                    }

                    // Post Picture in database
                    $picture = new Picture([
                        'post_id'  => $post->id,
                        'filename' => "/".$imgwm,
                        'position' => (int)$key + 1,
                    ]);
                    $picture->save();

                    $pictures[] = $picture;

                    // Check the pictures limit
//                if ($key >= ($picturesLimit - 1)) {
//                    break;
//                }
                }
            }}

//        $Payment = new Payment([
//            'post_id'  => $post->id,
//            'package_id' => $request->package_id,
//            'payment_method_id' => $request->payment_method_id,
//            'transaction_id' => $request->transaction_id,
//            'active' => 1,
//        ]);
//        $Payment->save();


if( $request->bank_transfer_in != 0){

    if ($request->payment_method_id == 2 && $request->package_id != 5){
        $extension_booking_bank_image = getUploadedFileExtension($request->bank_transfer);
        if (empty($extension_booking_bank_image)) {
            $extension_booking_bank_image = 'jpg';
        }
        // Make the image
        $booking_bank_image = Image::make($request->bank_transfer_in)->resize(400, 400, function ($constraint) {
            $constraint->aspectRatio();
        });
        // Generate a filename.
        $filename_booking_bank_image = md5($request->bank_transfer_in. time()) . '.' . $extension_booking_bank_image;
        $destination_path = 'app/booking';
        // Store the image on disk.
        Storage::disk('public')->put($destination_path . '/' . $filename_booking_bank_image, $booking_bank_image->stream());

        $package = Package::find($request->input('package_id'));
        $paymentInfo = [

            'post_id'           =>  $post->id,
            'user_id'           =>  auth()->user()->id ,
            'price'            =>  $package->price,
            'package_id'        =>  $request->package_id,
            'payment_method_id' => $request->payment_method_id,
            'transaction_id'    => t('bank_transfer'),
            'active'               => 0,
            'image'     => $filename_booking_bank_image ,
        ];

        // Check the uniqueness of the payment
        if($post->id != NULL){
            $paymentmodel = PaymentModel::where('post_id', $post->id)
                ->where('user_id',auth()->user()->id)
                ->where('package_id', $request->package_id)
                ->where('payment_method_id', $request->payment_method_id)
                ->first();
        }
        // Save the payment
        $payment =!empty($paymentmodel)?$paymentmodel: new PaymentModel($paymentInfo);
        $payment->save();
//            if(!empty($paymentmodel)){
//                // Successful transaction
//                flash(t("You will be assured of bank transfer and confirmation of your booking by your email and by massages on Theqqa"))->success();
//
//                $nextStepUrl =config('app.locale') . '/';
//            }
//            else{
        // Successful transaction
        flash(t("You will be assured of bank transfer and confirmation of your booking by your email and by massages on Theqqa"))->success();

        $nextStepUrl =config('app.locale') . '/';
//            }


    }
    else {
        // MAKE A PAYMENT (IF NEEDED)

        // Check if the selected Package has been already paid for this Post
        $alreadyPaidPackage = false;
        if (!empty($post->latestPayment)) {
            if ($post->latestPayment->package_id == $request->input('package_id')) {
                $alreadyPaidPackage = true;
            }
        }

        // Check if Payment is required
        $package = Package::find($request->input('package_id'));
        if (!empty($package)) {
            if ($package->price > 0 && $request->filled('payment_method_id') && !$alreadyPaidPackage) {
                // Send the Payment
                return $this->sendPayment($request, $post);
            }
        }

        // IF NO PAYMENT IS MADE (CONTINUE)

        // Get the next URL
        if (getSegment(2) == 'create') {
            $request->session()->flash('message', t('Your ad has been created.'));
            $nextStepUrl = config('app.locale') . '/posts/create/' . $postIdOrToken . '/finish';
        } else {
            flash(t("Your ad has been updated."))->success();

            $nextStepUrl = config('app.locale') . '/' . $post->uri . '?preview=1';
        }
    }

}
        // Save ad Id in session (for next steps)
        session(['tmpPostId' => $post->id]);

        // Custom Fields
        $this->createPostFieldsValues($post, $request);

        // Redirection
        return response()->json([
            'status' => 'success',
            'data' => 'saved data'
        ]);
    }
}
