<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
 * Theqqa
 */

namespace App\Http\Controllers\API\Auth;

use App\Helpers\Ip;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Http\Controllers\API\Auth\Traits\VerificationTrait;
use App\Http\Controllers\FrontController;
use App\Http\Requests\RegisterApiRequest;
use App\Models\City;
use App\Models\Gender;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserType;
use App\Notifications\UserActivated;
use App\Notifications\UserNotification;
use App\Notifications\UserWillApproved;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Torann\LaravelMetaTags\Facades\MetaTag;

class RegisterController extends FrontController
{
    use RegistersUsers, VerificationTrait;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/account';

    /**
     * @var array
     */
    public $msg = [];

    /**
     * RegisterController constructor.
     */
    public function __construct()
    {
//		parent::__construct();
//
//		// From Laravel 5.3.4 or above
//		$this->middleware(function ($request, $next) {
//			$this->commonQueries();
//
//			return $next($request);
//		});
    }

    /**
     * Common Queries
     */
    public function commonQueries()
    {
        $this->redirectTo = config('app.locale') . '/account';
    }

    /**
     * Show the form the create a new user account.
     *
     * @return View
     */
    public function showRegistrationForm()
    {
        $data = [];

        // References
        $data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());

        $data['genders'] = Gender::trans()->get();
        $cities = City::where('country_code',"SA")->orderBy('name')->get();

        MetaTag::set('title', getMetaTag('title', 'register'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'register')));
        MetaTag::set('keywords', getMetaTag('keywords', 'register'));

        return response()->json([
            'status' => 'success',
            'cities' => $cities,
        ]);
    }

    /**
     * Register a new user account.
     *
     * @param RegisterRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(RegisterApiRequest $request)
    {

        // Conditions to Verify User's Email or Phone
        $emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email');
        $phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone');

        // New User
        $user = new User();
        $input = $request->only($user->getFillable());
        foreach ($input as $key => $value) {
            $user->{$key} = $value;
        }


        if ($request->input('client') == 'user'  ) {
            $user->country_code   ='SA';
            $user->language_code  = 'ar';
            $user->password = Hash::make($request->input('password'));
            $user->phone_hidden = $request->input('phone_hidden');
            $user->ip_addr = Ip::get();
            $user->verified_email = 0;
            $user->verified_phone = 1;
            $user->user_type_id = '2';
            $user->id_number =$request->input('id_number');
        }elseif ($request->input('client') == 'company'){

            $user->country_code   ='SA';
            $user->language_code  = 'ar';
            $user->password       = Hash::make($request->input('password'));
            $user->phone_hidden   = $request->input('phone_hidden');
            $user->user_type_id   = $request->input('company');
            $user->ip_addr        = Ip::get();
            $user->ip_addr        = Ip::get();
            $user->cities_ids     = (is_array($request->subladmin1) ? implode(",",$request->subladmin1) : "");
            $user->verified_email = 0;
            $user->verified_phone = 1;
            $user->id_number_owner =$request->input('id_number_owner');

//            $files= explode(',', $request->file);
            $files=  $request->file;
            $pictures=[];
//            if (count($files) > 0) {
//                foreach ($files as $key => $file) {
//                    $output_file=str_random(6).'jpg';
//                    $ifp = fopen( $output_file, 'wb' );
//
//
//                    // we could add validation here with ensuring count( $data ) > 1
//                    fwrite( $ifp, base64_decode( $file ) );
//
//                    // clean up the file resource
//                    fclose( $ifp );
//                    $extension = getUploadedFileExtension($output_file);
//                    if (empty($extension)) {
//                        $extension = 'jpg';
//                    }
//
//                    // Make the image
//                    $image = Image::make($output_file)->resize(400, 400, function ($constraint) {
//                        $constraint->aspectRatio();
//                    });
//
//                    // Generate a filename.
//                    $filename = md5($output_file . time()) . '.' . $extension;
//                    array_push($pictures, $filename);
//
//
//
//                    $destination_path = 'app';
//                    // Store the image on disk.
//                    Storage::disk('public')->put($destination_path . '/' . $filename, $image->stream());
//
//                }}

            if (count($files) > 0) {
                foreach ($files as $key => $file) {

                    $extension = getUploadedFileExtension($file);
                    if (empty($extension)) {
                        $extension = 'jpg';
                    }

                    // Make the image
                    $image = Image::make($file)->resize(400, 400, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    // Generate a filename.
                    $filename = md5($file . time()) . '.' . $extension;
                    array_push($pictures, $filename);



                    $destination_path = 'app';
                    // Store the image on disk.
                    Storage::disk('public')->put($destination_path . '/' . $filename, $image->stream());

                }}
            $user->image_data =!empty($pictures)?json_encode($pictures):'';
        }
        elseif ($request->input('client') == 'exhibition'){
            $user->country_code   ='SA';
            $user->language_code  = 'ar';
            $user->password       = Hash::make($request->input('password'));
            $user->phone_hidden   = $request->input('phone_hidden');
            $user->user_type_id   = '6';
            $user->ip_addr        = Ip::get();
            $user->ip_addr        = Ip::get();
            $user->cities_ids     = (is_array($request->subladmin1) ? implode(",",$request->subladmin1) : "");
            $user->verified_email = 0;
            $user->verified_phone = 1;
            $user->id_number_owner =$request->input('id_number_owner');
            $files=  $request->file;
//            $files= explode(',', $request->file);
            $pictures=[];


//            if (count($files) > 0) {
//                foreach ($files as $key => $file) {
//
//                    $output_file=str_random(6).'jpg';
//                    $ifp = fopen( $output_file, 'wb' );
//
//
//                    // we could add validation here with ensuring count( $data ) > 1
//                    fwrite( $ifp, base64_decode( $file ) );
//
//                    // clean up the file resource
//                    fclose( $ifp );
//                    $extension = getUploadedFileExtension($output_file);
//                    if (empty($extension)) {
//                        $extension = 'jpg';
//                    }
//
//                    // Make the image
//                    $image = Image::make($output_file)->resize(400, 400, function ($constraint) {
//                        $constraint->aspectRatio();
//                    });
//
//                    // Generate a filename.
//                    $filename = md5($output_file . time()) . '.' . $extension;
//                    array_push($pictures, $filename);
//
//
//
//                    $destination_path = 'app';
//                    // Store the image on disk.
//                    Storage::disk('public')->put($destination_path . '/' . $filename, $image->stream());
//
//                }}


            if (count($files) > 0) {
                foreach ($files as $key => $file) {

                    $extension = getUploadedFileExtension($file);
                    if (empty($extension)) {
                        $extension = 'jpg';
                    }

                    // Make the image
                    $image = Image::make($file)->resize(400, 400, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    // Generate a filename.
                    $filename = md5($file . time()) . '.' . $extension;
                    array_push($pictures, $filename);



                    $destination_path = 'app';
                    // Store the image on disk.
                    Storage::disk('public')->put($destination_path . '/' . $filename, $image->stream());

                }}
            $user->image_data =!empty($pictures)?json_encode($pictures):'';}
        elseif ($request->input('client') == 'shop'){
            $user->country_code   ='SA';
            $user->language_code  = 'ar';
            $user->password       = Hash::make($request->input('password'));
            $user->phone_hidden   = $request->input('phone_hidden');
            $user->user_type_id   = $request->input('company');
            $user->ip_addr        = Ip::get();
            $user->ip_addr        = Ip::get();
            $user->cities_ids     = (is_array($request->subladmin1) ? implode(",",$request->subladmin1) : "");
            $user->verified_email = 0;
            $user->verified_phone = 1;
            $user->id_number_owner =$request->input('id_number_owner');
            $files=$request->file;
//            $files= explode(',', $request->file);
            $pictures=[];
//            if (count($files) > 0) {
//                foreach ($files as $key => $file) {
//                    $output_file=str_random(6).'jpg';
//                    $ifp = fopen( $output_file, 'wb' );
//
//
//                    // we could add validation here with ensuring count( $data ) > 1
//                    fwrite( $ifp, base64_decode( $file ) );
//
//                    // clean up the file resource
//                    fclose( $ifp );
//                    $extension = getUploadedFileExtension($output_file);
//                    if (empty($extension)) {
//                        $extension = 'jpg';
//                    }
//
//                    // Make the image
//                    $image = Image::make($output_file)->resize(400, 400, function ($constraint) {
//                        $constraint->aspectRatio();
//                    });
//
//                    // Generate a filename.
//                    $filename = md5($output_file . time()) . '.' . $extension;
//                    array_push($pictures, $filename);
//
//
//
//                    $destination_path = 'app';
//                    // Store the image on disk.
//                    Storage::disk('public')->put($destination_path . '/' . $filename, $image->stream());
//
//                }}

            if (count($files) > 0) {
                foreach ($files as $key => $file) {

                    $extension = getUploadedFileExtension($file);
                    if (empty($extension)) {
                        $extension = 'jpg';
                    }

                    // Make the image
                    $image = Image::make($file)->resize(400, 400, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    // Generate a filename.
                    $filename = md5($file . time()) . '.' . $extension;
                    array_push($pictures, $filename);



                    $destination_path = 'app';
                    // Store the image on disk.
                    Storage::disk('public')->put($destination_path . '/' . $filename, $image->stream());

                }}
            $user->image_data =!empty($pictures)?json_encode($pictures):'';
        }
        // Email verification key generation
//        if ($emailVerificationRequired) {
        $user->email_token = md5(microtime() . mt_rand());
//            $user->verified_email = 0;
//        }

//		 Mobile activation key generation
//        if ($phoneVerificationRequired) {
        $user->phone_token = mt_rand(100000, 999999);
//        $user->verified_phone = 1;
//        }

        // Save
        $user->save();

        // Message Notification & Redirection
        $request->session()->flash('message', t("Your account has been created."));
        $nextUrl = config('app.locale') . '/register/finish';

        // Send Admin Notification Email
        if (config('settings.mail.admin_notification') == 1) {
            try {
                // Get all admin users
                $admins = User::permission(Permission::getStaffPermissions())->get();
                if ($admins->count() > 0) {
                    Notification::send($admins, new UserNotification($user));
                    /*
                    foreach ($admins as $admin) {
                        Notification::route('mail', $admin->email)->notify(new UserNotification($user));
                    }
                    */
                }
            } catch (\Exception $e) {
                flash($e->getMessage())->error();
            }
        }
        if ($request->input('client') != 'exhibition'){
            // Send Verification Link or Code
            if ($emailVerificationRequired || $phoneVerificationRequired) {

                // Save the Next URL before verification
                session(['userNextUrl' => $nextUrl]);

                // Email
                if ($emailVerificationRequired) {
                    // Send Verification Link by Email
                    $this->sendVerificationEmail($user);

                    // Show the Re-send link
                    $this->showReSendVerificationEmailLink($user, 'user');
                }

                // Phone
                if ($phoneVerificationRequired) {
                    // Send Verification Code by SMS
                    $this->sendVerificationSms($user);

                    // Show the Re-send link
                    $this->showReSendVerificationSmsLink($user, 'user');

                    // Go to Phone Number verification
                    $nextUrl = config('app.locale') . '/verify/user/phone/';
                }

                // Send Confirmation Email or SMS,
                // When User clicks on the Verification Link or enters the Verification Code.
                // Done in the "app/Observers/UserObserver.php" file.

            } else {

//			 Send Confirmation Email or SMS
                if (config('settings.mail.confirmation') == 1) {
                    try {
                        $user->notify(new UserActivated($user));
                    } catch (\Exception $e) {
                        flash($e->getMessage())->error();
                    }
                }

                // Redirect to the user area If Email or Phone verification is not required
                if (Auth::loginUsingId($user->id)) {
                    return redirect()->intended(config('app.locale') . '/account');
                }

            }

        }else{
            // Message Notification & Redirection
            $request->session()->flash('message', t("Your account will be active after admin approve on your Account."));
            $nextUrl = config('app.locale') . '/register/finish';
            if (config('settings.mail.confirmation') == 1) {
                try {
                    $user->notify(new UserWillApproved($user));
                } catch (\Exception $e) {
                    flash($e->getMessage())->error();
                }
            }

            // Redirect to the user area If Email or Phone verification is not required
//            if (Auth::loginUsingId($user->id)) {
//                return redirect()->intended(config('app.locale') . '/account');
//            }
        }

        $success['token'] =  $user->createToken('theqqaPassport')->accessToken;
        $success['name'] =  $user->name;

        return response()->json([
            'status' => 'success',
            'data' => $success,
            'user'=>$user,

        ]);


    }


    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|View
     */
    public function finish()
    {
        // Keep Success Message for the page refreshing
        session()->keep(['message']);
        if (!session()->has('message')) {
            return redirect(config('app.locale') . '/');
        }

        // Meta Tags
        MetaTag::set('title', session('message'));
        MetaTag::set('description', session('message'));

        return view('auth.register.finish');
    }
    public function registerCompany(RegisterRequest $request)
    {

        // Conditions to Verify User's Email or Phone
        $emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email');
        $phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone');

        // New User
        $user = new User();
        $input = $request->only($user->getFillable());
        foreach ($input as $key => $value) {
            $user->{$key} = $value;
        }

        $user->country_code   = config('country.code');
        $user->language_code  = config('app.locale');
        $user->password       = Hash::make($request->input('password'));
        $user->phone_hidden   = $request->input('phone_hidden');
        $user->user_type_id   = $request->input('company');
        $user->ip_addr        = Ip::get();
        $user->ip_addr        = Ip::get();
        $user->verified_email = 1;
        $user->verified_phone = 1;

        // Email verification key generation
        if ($emailVerificationRequired) {
            $user->email_token = md5(microtime() . mt_rand());
            $user->verified_email = 0;
        }

        // Mobile activation key generation
        if ($phoneVerificationRequired) {
            $user->phone_token = mt_rand(100000, 999999);
            $user->verified_phone = 1;
        }

        // Save
        $user->save();

        // Message Notification & Redirection
        $request->session()->flash('message', t("Your account has been created."));
        $nextUrl = config('app.locale') . '/register/finish';

        // Send Admin Notification Email
        if (config('settings.mail.admin_notification') == 1) {
            try {
                // Get all admin users
                $admins = User::permission(Permission::getStaffPermissions())->get();
                if ($admins->count() > 0) {
                    Notification::send($admins, new UserNotification($user));
                    /*
                    foreach ($admins as $admin) {
                        Notification::route('mail', $admin->email)->notify(new UserNotification($user));
                    }
                    */
                }
            } catch (\Exception $e) {
                flash($e->getMessage())->error();
            }
        }

        // Send Verification Link or Code
        if ($emailVerificationRequired || $phoneVerificationRequired) {

            // Save the Next URL before verification
            session(['userNextUrl' => $nextUrl]);

            // Email
            if ($emailVerificationRequired) {
                // Send Verification Link by Email
                $this->sendVerificationEmail($user);

                // Show the Re-send link
                $this->showReSendVerificationEmailLink($user, 'user');
            }

            // Phone
            if ($phoneVerificationRequired) {
                // Send Verification Code by SMS
                $this->sendVerificationSms($user);

                // Show the Re-send link
                $this->showReSendVerificationSmsLink($user, 'user');

                // Go to Phone Number verification
                $nextUrl = config('app.locale') . '/verify/user/phone/';
            }

            // Send Confirmation Email or SMS,
            // When User clicks on the Verification Link or enters the Verification Code.
            // Done in the "app/Observers/UserObserver.php" file.

        } else {

            // Send Confirmation Email or SMS
            if (config('settings.mail.confirmation') == 1) {
                try {
                    $user->notify(new UserActivated($user));
                } catch (\Exception $e) {
                    flash($e->getMessage())->error();
                }
            }

            // Redirect to the user area If Email or Phone verification is not required
            if (Auth::loginUsingId($user->id)) {
                return redirect()->intended(config('app.locale') . '/account');
            }

        }

        // Redirection
        $success['token'] =  $user->createToken('theqqaPassport')->accessToken;
        $success['name'] =  $user->name;
        return response()->json([
            'status' => 'success',
            'data' => $success,
        ]);
    }
    public function registershop(RegisterRequest $request)
    {

        // Conditions to Verify User's Email or Phone
        $emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email');
        $phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone');

        // New User
        $user = new User();
        $input = $request->only($user->getFillable());
        foreach ($input as $key => $value) {
            $user->{$key} = $value;
        }

        $user->country_code   = config('country.code');
        $user->language_code  = config('app.locale');
        $user->password       = Hash::make($request->input('password'));
        $user->phone_hidden   = $request->input('phone_hidden');
        $user->user_type_id   = $request->input('company');
        $user->ip_addr        = Ip::get();
        $user->ip_addr        = Ip::get();
        $user->verified_email = 1;
        $user->verified_phone = 1;

        // Email verification key generation
        if ($emailVerificationRequired) {
            $user->email_token = md5(microtime() . mt_rand());
            $user->verified_email = 0;
        }

        // Mobile activation key generation
        if ($phoneVerificationRequired) {
            $user->phone_token = mt_rand(100000, 999999);
            $user->verified_phone = 0;
        }

        // Save
        $user->save();

        // Message Notification & Redirection
        $request->session()->flash('message', t("Your account has been created."));
        $nextUrl = config('app.locale') . '/register/finish';

        // Send Admin Notification Email
        if (config('settings.mail.admin_notification') == 1) {
            try {
                // Get all admin users
                $admins = User::permission(Permission::getStaffPermissions())->get();
                if ($admins->count() > 0) {
                    Notification::send($admins, new UserNotification($user));
                    /*
                    foreach ($admins as $admin) {
                        Notification::route('mail', $admin->email)->notify(new UserNotification($user));
                    }
                    */
                }
            } catch (\Exception $e) {
                flash($e->getMessage())->error();
            }
        }

        // Send Verification Link or Code
        if ($emailVerificationRequired || $phoneVerificationRequired) {

            // Save the Next URL before verification
            session(['userNextUrl' => $nextUrl]);

            // Email
            if ($emailVerificationRequired) {
                // Send Verification Link by Email
                $this->sendVerificationEmail($user);

                // Show the Re-send link
                $this->showReSendVerificationEmailLink($user, 'user');
            }

            // Phone
            if ($phoneVerificationRequired) {
                // Send Verification Code by SMS
                $this->sendVerificationSms($user);

                // Show the Re-send link
                $this->showReSendVerificationSmsLink($user, 'user');

                // Go to Phone Number verification
                $nextUrl = config('app.locale') . '/verify/user/phone/';
            }

            // Send Confirmation Email or SMS,
            // When User clicks on the Verification Link or enters the Verification Code.
            // Done in the "app/Observers/UserObserver.php" file.

        }
        else
        {

            // Send Confirmation Email or SMS
            if (config('settings.mail.confirmation') == 1) {
                try {
                    $user->notify(new UserActivated($user));
                } catch (\Exception $e) {
                    flash($e->getMessage())->error();
                }
            }

            // Redirect to the user area If Email or Phone verification is not required
            if (Auth::loginUsingId($user->id)) {
                return redirect()->intended(config('app.locale') . '/account');
            }

        }

        // Redirection
        $success['token'] =  $user->createToken('theqqaPassport')->accessToken;
        $success['name'] =  $user->name;
        return response()->json([
            'status' => 'success',
            'data' => $success,
        ]);
    }
    public function registerexhibition(RegisterRequest $request)
    {

        // Conditions to Verify User's Email or Phone
        $emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email');
        $phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone');

        // New User
        $user = new User();
        $input = $request->only($user->getFillable());
        foreach ($input as $key => $value) {
            $user->{$key} = $value;
        }

        $user->country_code   = config('country.code');
        $user->language_code  = config('app.locale');
        $user->password       = Hash::make($request->input('password'));
        $user->phone_hidden   = $request->input('phone_hidden');
        $user->user_type_id   = '6';
        $user->ip_addr        = Ip::get();
        $user->ip_addr        = Ip::get();
        $user->verified_email = 1;
        $user->verified_phone = 1;

        // Email verification key generation
        if ($emailVerificationRequired) {
            $user->email_token = md5(microtime() . mt_rand());
            $user->verified_email = 0;
        }

        // Mobile activation key generation
        if ($phoneVerificationRequired) {
            $user->phone_token = mt_rand(100000, 999999);
            $user->verified_phone = 0;
        }

        // Save
        $user->save();

        // Message Notification & Redirection
        $request->session()->flash('message', t("Your account will be active after admin approve on your Account."));
        $nextUrl = config('app.locale') . '/register/finish';

        // Send Admin Notification Email
        if (config('settings.mail.admin_notification') == 1) {
            try {
                // Get all admin users
                $admins = User::permission(Permission::getStaffPermissions())->get();
                if ($admins->count() > 0) {
                    Notification::send($admins, new UserNotification($user));
                    /*
                    foreach ($admins as $admin) {
                        Notification::route('mail', $admin->email)->notify(new UserNotification($user));
                    }
                    */
                }
            }
            catch (\Exception $e) {
//                flash($e->getMessage())->error();
            }
        }

//         Send Verification Link or Code
//        if ($emailVerificationRequired || $phoneVerificationRequired) {
//
//            // Save the Next URL before verification
//            session(['userNextUrl' => $nextUrl]);
//
//            // Email
//            if ($emailVerificationRequired) {
//                // Send Verification Link by Email
//                $this->sendVerificationEmail($user);
//
//                // Show the Re-send link
//                $this->showReSendVerificationEmailLink($user, 'user');
//            }
//
//            // Phone
//            if ($phoneVerificationRequired) {
//                // Send Verification Code by SMS
//                $this->sendVerificationSms($user);
//
//                // Show the Re-send link
//                $this->showReSendVerificationSmsLink($user, 'user');
//
//                // Go to Phone Number verification
//                $nextUrl = config('app.locale') . '/verify/user/phone/';
//            }
//
//            // Send Confirmation Email or SMS,
//            // When User clicks on the Verification Link or enters the Verification Code.
//            // Done in the "app/Observers/UserObserver.php" file.
//
//        } else
        {

            // Send Confirmation Email or SMS
            if (config('settings.mail.confirmation') == 1) {
                try {
                    $user->notify(new UserActivated($user));
                } catch (\Exception $e) {
                    flash($e->getMessage())->error();
                }
            }

            // Redirect to the user area If Email or Phone verification is not required
            if (Auth::loginUsingId($user->id)) {
                return redirect()->intended(config('app.locale') . '/account');
            }

        }

        // Redirection
        $success['token'] =  $user->createToken('theqqaPassport')->accessToken;
        $success['name'] =  $user->name;
        return response()->json([
            'status' => 'success',
            'data' => $success,
        ]);

    }

}
