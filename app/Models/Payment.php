<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace App\Models;

use App\Models\Scopes\LocalizedScope;
use App\Models\Scopes\StrictActiveScope;
use App\Observer\PaymentObserver;
use Larapen\Admin\app\Models\Crud;

class Payment extends BaseModel
{
	use Crud;
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'payments';
	
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	// protected $primaryKey = 'id';
	
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var boolean
	 */
	// public $timestamps = false;
	
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['post_id', 'package_id', 'payment_method_id', 'transaction_id', 'active','user_id','image','price','date_service','pt_invoice_id','pt_transaction_id'];
	
	/**
	 * The attributes that should be hidden for arrays
	 *
	 * @var array
	 */
	// protected $hidden = [];
	
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	// protected $dates = [];
	
	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	protected static function boot()
	{
		parent::boot();
		
		Payment::observe(PaymentObserver::class);
		
		static::addGlobalScope(new StrictActiveScope());
		static::addGlobalScope(new LocalizedScope());
	}
	
	public function getPostTitleHtml()
	{
		$out = '#' . $this->post_id;
		if ($this->post) {
			$postUrl = url(config('app.locale') . '/' . $this->post->uri);
			$out .= ' | ';
			$out .= '<a href="' . $postUrl . '" target="_blank">' . $this->post->title . '</a>';
			
			if (config('settings.single.posts_review_activation')) {
				$outLeft = '<div class="pull-left">' . $out . '</div>';
				$outRight = '<div class="pull-right"></div>';
				
				if ($this->active != 1) {
					// Check if this ad has at least successful payment
					$countSuccessfulPayments = Payment::where('post_id', $this->post_id)->where('active', 1)->count();
					if ($countSuccessfulPayments <= 0) {
						$msg = trans('admin::messages.payment_post_delete_btn_tooltip');
						$tooltip = ' data-toggle="tooltip" title="' . $msg . '"';
						
						$outRight = '';
						$outRight .= '<div class="pull-right">';
						$outRight .= '<a href="' . admin_url('posts/' . $this->post_id) . '" class="btn btn-xs btn-danger" data-button-type="delete"' . $tooltip . '>';
						$outRight .= '<i class="fa fa-trash"></i> ';
						$outRight .= trans('admin::messages.Delete');
						$outRight .= '</a>';
						$outRight .= '</div>';
					}
				}
				
				$out = $outLeft . $outRight;
			}
		}
		
		return $out;
	}

    public function getFilenameHtml()
    {
        // /app/public/app/booking/'
        // Get picture

        $out =!empty($this->image) ?'
       
        <img src="' . resize('app/booking/'.$this->image, 'big') . '"  style="width:145px; height:90px;"  class="pay_class" data-toggle=\'modal\' data-target=\'#paymentModal\'
                                                 title="'. t("click_enlarge").'">   
                                                 <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog"
                                         aria-labelledby="dlModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"
                                                        id="dlModalLabel">'.t("bank_transfer").'</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="" style="max-width: 100%; height: auto;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    ':'';
        echo " <script type=\"text/javascript\">
            $(document).ready(function () {
                $('.pay_class').click(function () {
                    x = $(this).attr('src');
                    $('#paymentModal img').attr(\"src\",x);
                });
            });
        </script> ";

        return $out;
    }
    public function getPriceNameHtml()
    {
        // /app/public/app/booking/'
        // Get picture
        $out = !empty($this->price)?' (' . $this->price . ' ' . $this->package->currency_code. ')'  : ' (' . $this->package->price . ' ' . $this->package->currency_code . ')';

        return $out;
    }

    public function getUserNameHtml()
    {
        if (isset($this->user_id) and !empty($this->user_id)) {
            $url = admin_url('users/' . $this->user_id. '/edit');
            $tooltip = ' data-toggle="tooltip" title="' . $this->user_id. '"';

            return '<a href="' . $url . '"' . $tooltip . '>' . $this->user_id . '</a>';
        } else {
            return $this->user_id;
        }
    }
	public function getPackageNameHtml()
	{
		$out = $this->package_id;
		
		if (!empty($this->package)) {
			$packageUrl = admin_url('packages/' . $this->package_id . '/edit');
			
			$out = '';
			$out .= '<a href="' . $packageUrl . '">';
			$out .= $this->package->name;
			$out .= '</a>';

//            $out .= ' (' . $this->package->price . ' ' . $this->package->currency_code . ')';
		}
		
		return $out;
	}
//    public function getCurrencyNameHtml()
//    {
//
//
//        if (!empty($this->package)) {
//
//
//            $out = '';
//
//            $out .= ' ( ' . $this->package->currency_code . ')';
//        }
//
//        return $out;
//    }
	
	public function getPaymentMethodNameHtml()
	{
		$out = '--';
		
		if (!empty($this->paymentMethod)) {
			$paymentMethodUrl = admin_url('payment_methods/' . $this->payment_method_id . '/edit');
			
			$out = '';
			$out .= '<a href="' . $paymentMethodUrl . '">';
			if ($this->paymentMethod->name == 'offlinepayment') {
				$out .= trans('offlinepayment::messages.Offline Payment');
			} else {
				$out .= $this->paymentMethod->display_name;
			}
			$out .= '</a>';
		}
		
		return $out;
	}
	
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function post()
	{
		return $this->belongsTo(Post::class, 'post_id');
	}
	
	public function package()
	{
		return $this->belongsTo(Package::class, 'package_id', 'translation_of')->where('translation_lang', config('app.locale'));
	}
	
	public function paymentMethod()
	{
		return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
	}
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
	
	/*
	|--------------------------------------------------------------------------
	| ACCESORS
	|--------------------------------------------------------------------------
	*/
	
	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/
}
