{{--
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
--}}
@extends('layouts.master')

@section('wizard')
    @include('post.inc.wizard')
@endsection

@section('content')
    @include('common.spacer')
    <div class="main-container">
        <div class="container">
            <div class="row">

                @include('post.inc.notification')

                <div class="col-md-12 page-content">
                    <div class="inner-box">

                        <h2 class="title-2"><strong><i class="icon-tag"></i> {{ t('Pricing') }}</strong></h2>

                        <div class="row">
                            <div class="col-sm-12">
                                <form class="form" id="postForm" method="POST" action="{{ url()->current() }}"
                                      enctype="multipart/form-data">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="post_id" id="post_id" value="{{ $post->id }}">
                                             <?php if(auth()->check()){
                                 
                                        $login_user=Auth::user();
                                    ?>
                                                      <input type="hidden" name="first_name" id="auth_first_name" value="{{ $login_user->first_name }}">
                                    <input type="hidden" name="email"  id="auth_email"  value="{{ $login_user->email }}">
                                    <input type="hidden" name="phone" id="auth_phone"  value="{{ $login_user->phone }}">


                  
                                <?php }?>
                                    <fieldset>

                                        @if (isset($packages) and isset($paymentMethods) and $packages->count() > 0 and $paymentMethods->count() > 0)
                                            <div class="well pb-0">
                                                <h3><i class="icon-certificate icon-color-1"></i> {{ t('Premium Ad') }}
                                                </h3>
                                                <p>
                                                    {{ t('The premium package help sellers to promote their products or services by giving more visibility to their ads to attract more buyers and sell faster.') }}
                                                </p>
                                                <?php $packageIdError = (isset($errors) and $errors->has('package_id')) ? ' is-invalid' : ''; ?>
                                                <div class="form-group mb-0">
                                                    <table id="packagesTable"
                                                           class="table table-hover checkboxtable mb-0">
                                                        <?php
                                                        // Get Current Payment data
                                                        $currentPaymentMethodId = 0;
                                                        $currentPaymentActive = 1;
                                                        if (isset($post->latestPayment) and !empty($post->latestPayment)) {
                                                            $currentPaymentMethodId = $post->latestPayment->payment_method_id;
                                                            if ($post->latestPayment->active == 0) {
                                                                $currentPaymentActive = 0;
                                                            }
                                                        }
                                                        ?>
                                                        @foreach ($packages as $package)
                                                            <?php
                                                            $currentPackageId = 0;
                                                            $currentPackagePrice = 0;
                                                            $packageStatus = '';
                                                            $badge = '';
                                                            if (isset($post->latestPayment) and !empty($post->latestPayment)) {
                                                                if (isset($post->latestPayment->package) and !empty($post->latestPayment->package)) {
                                                                    $currentPackageId = $post->latestPayment->package->tid;
                                                                    $currentPackagePrice = $post->latestPayment->package->price;
                                                                }
                                                            }
                                                            // Prevent Package's Downgrading
                                                            if ($currentPackagePrice > $package->price) {
                                                                $packageStatus = ' disabled';
                                                                $badge = ' <span class="badge badge-danger">' . t('Not available') . '</span>';
                                                            } elseif ($currentPackagePrice == $package->price) {
                                                                $badge = '';
                                                            } else {
                                                                $badge = ' <span class="badge badge-success">' . t('Upgrade') . '</span>';
                                                            }
                                                            if ($currentPackageId == $package->tid) {
                                                                $badge = ' <span class="badge badge-secondary">' . t('Current') . '</span>';
                                                                if ($currentPaymentActive == 0) {
                                                                    $badge .= ' <span class="badge badge-warning">' . t('Payment pending') . '</span>';
                                                                }
                                                            }
                                                            $package_id = [9, 17, 11, 15, 13, 19, 21];
                                                            ?>
                                                            @if(!in_array($package->tid,$package_id))
                                                                <tr>
                                                                    <td class="text-left align-middle p-3">
                                                                        <div class="form-check">
                                                                            <input type="hidden" name="service_type"  id="auth_service_type"  value="{{ $package->name }}">
                                                                           <input type="hidden" value="{{ $package->price }}" class="package_price">
                                                                            <input class="form-check-input package-selection{{ $packageIdError }}"
                                                                                   type="radio"
                                                                                   name="package_id"
                                                                                   id="packageId-{{ $package->tid }}"
                                                                                   value="{{ $package->tid }}"
                                                                                   data-name="{{ $package->name }}"
                                                                                   data-currencysymbol="{{ $package->currency->symbol }}"
                                                                                   data-currencyinleft="{{ $package->currency->in_left }}"
                                                                                    {{ (old('package_id', $currentPackageId)==$package->tid) ? ' checked' : (($package->price==0) ? ' checked' : '') }} {{ $packageStatus }}
                                                                            >
                                                                            <label class="form-check-label mb-0{{ $packageIdError }}">
                                                                                <strong class="tooltipHere"
                                                                                        title=""
                                                                                        data-placement="right"
                                                                                        data-toggle="tooltip"
                                                                                        data-original-title="{!! $package->description !!}"
                                                                                >{!! $package->name . $badge !!} </strong>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-right align-middle p-3">
                                                                        <p id="price-{{ $package->tid }}" class="mb-0">
                                                                            @if ($package->currency->in_left == 1)
                                                                                <span class="price-currency">{!! $package->currency->symbol !!}</span>
                                                                            @endif
                                                                            <span class="price-int">{{ $package->price }}</span>
                                                                            @if ($package->currency->in_left == 0)
                                                                                <span class="price-currency">{!! $package->currency->symbol !!}</span>
                                                                            @endif
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach

                                                        <tr>
                                                            <td class="text-left align-middle p-3">
                                                                <?php $paymentMethodIdError = (isset($errors) and $errors->has('payment_method_id')) ? ' is-invalid' : ''; ?>
                                                                <div class="form-group mb-0">
                                                                    <div class="row" style="margin-top: 1rem;">
                                                                        <div class="col-md-4 col-sm-4">
                                                                            <label for="payment_method_id"
                                                                                   class="control-label">{{ t('choose_payment_method') }}</label>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-8">
                                                               
                                                                            <select class="form-control selecter{{ $paymentMethodIdError }}"
                                                                                    name="payment_method_id"
                                                                                    id="paymentMethodId">
                                                                                @foreach ($paymentMethods as $paymentMethod)
{{--                                                                                      @if ($currentPaymentMethodId == 0))--}}
                                                                                    {{--@if (view()->exists('payment::' . $paymentMethod->name))--}}
                                                                                      @if( $paymentMethod->id !=1 )
                                                                                    <option value="{{ $paymentMethod->id }}"
                                                                                            data-name="{{ $paymentMethod->name }}" {{ (old('payment_method_id', $currentPaymentMethodId)==$paymentMethod->id) ? 'selected="selected"' : '' }}>
                                                                                        @if ($paymentMethod->name == 'offlinepayment')
                                                                                            {{ trans('offlinepayment::messages.Offline Payment') }}
                                                                                        @else
                                                                                            {{ $paymentMethod->display_name }}
                                                                                        @endif
                                                                                    </option>
                                                                                    @endif
{{--                                                                                    @endif--}}
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group mb-0" id="bank_transfer">
                                                                        <div class="row" style="margin-top: 1rem;">
                                                                            <div class="col-md-4 col-sm-4">
                                                                                <label for="bank_transfer_in"
                                                                                       class="control-label">{{ t('upload_bank_transfer') }}</label>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-8">
                                                                                <input type="file"
                                                                                       name="bank_transfer_in"
                                                                                       id="bank_transfer_in" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                            </td>

                                                            <td class="text-right align-middle p-3">
                                                                <p class="mb-0" style="color: #ff9113;">
                                                                    <strong>
                                                                        {{ t('Payable Amount') }}:
                                                                        <span class="price-currency amount-currency currency-in-left"
                                                                              style="display: none;"></span>
                                                                        <span class="payable-amount">0</span>
                                                                        <span class="price-currency amount-currency currency-in-right"
                                                                              style="display: none;"></span>
                                                                    </strong>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>

                                        @if (isset($paymentMethods) and $paymentMethods->count() > 0)
                                            <!-- Payment Plugins -->
                                            <?php $hasCcBox = 0; ?>
                                            @foreach($paymentMethods as $paymentMethod)
                                               @if( $paymentMethod->id !=1 )
                                                @if (view()->exists('payment::' . $paymentMethod->name))
                                                    @include('payment::' . $paymentMethod->name, [$paymentMethod->name . 'PaymentMethod' => $paymentMethod])
                                                @endif 
                                                @endif
                                                <?php if ($paymentMethod->has_ccbox == 1 && $hasCcBox == 0) $hasCcBox = 1; ?>
                                            @endforeach
                                        @endif
                                    @endif

                                    <!-- Button  -->
                                        <div class="form-group">
                                            <div class="col-md-12 text-center mt-4">
                                                @if (getSegment(2) == 'create')
                                                    <a id="skipBtn"
                                                       href="{{ lurl('posts/create/' . $post->tmp_token . '/finish') }}"
                                                       class="btn btn-default btn-lg">{{ t('Skip') }}</a>
                                                @else
                                                    <?php $attr = ['slug' => slugify($post->title), 'id' => $post->id]; ?>
                                                    <a id="skipBtn" href="{{ lurl($post->uri, $attr) }}"
                                                       class="btn btn-default btn-lg">{{ t('Skip') }}</a>
                                                @endif
                                         
                                                          <button id="submitPostForm"
                                                        class="submitPostForm btn btn-success btn-lg"> <a class ="PT_open_popup" id="en_button">{{ t('Pay') }}</a> </button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.page-content -->
            </div>
        </div>
    </div>
@endsection

@section('after_styles')
@endsection

@section('after_scripts')
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
    <script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.2.3/jquery.payment.min.js"></script>
    @if (file_exists(public_path() . '/assets/plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js'))
        <script src="{{ url('/assets/plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js') }}"
                type="text/javascript"></script>
    @endif
      <script src="https://paytabs.com/express/v4/paytabs-express-checkout.js"></script>
    <script>
        /* Submit Form */
        if ($('#paymentMethodId').val() == 2) {

            $('#bank_transfer').css('display', 'block');
        } else {
            $('#bank_transfer').css('display', 'none');
        }
                @if (isset($packages) and isset($paymentMethods) and $packages->count() > 0 and $paymentMethods->count() > 0)
        var currentPackagePrice = {{ $currentPackagePrice }};
        var currentPaymentActive = {{ $currentPaymentActive }};
        $(document).ready(function () {


            /* Show price & Payment Methods */
            var selectedPackage = $('input[name=package_id]:checked').val();
            var packagePrice = getPackagePrice(selectedPackage);
            var packageCurrencySymbol = $('input[name=package_id]:checked').data('currencysymbol');
            var packageCurrencyInLeft = $('input[name=package_id]:checked').data('currencyinleft');
            var paymentMethod = $('#paymentMethodId').find('option:selected').data('name');
            showAmount(packagePrice, packageCurrencySymbol, packageCurrencyInLeft);
            showPaymentSubmitButton(currentPackagePrice, packagePrice, currentPaymentActive, paymentMethod);

            /* Select a Package */
            $('.package-selection').click(function () {
                selectedPackage = $(this).val();
                packagePrice = getPackagePrice(selectedPackage);
                packageCurrencySymbol = $(this).data('currencysymbol');
                packageCurrencyInLeft = $(this).data('currencyinleft');
                showAmount(packagePrice, packageCurrencySymbol, packageCurrencyInLeft);
                showPaymentSubmitButton(currentPackagePrice, packagePrice, currentPaymentActive, paymentMethod);
            });

            /* Select a Payment Method */
            $('#paymentMethodId').on('change', function () {
                paymentMethod = $(this).find('option:selected').data('name');
                /* Submit Form */

                if ($('#paymentMethodId').val() == 2) {

                    $('#bank_transfer').css('display', 'block');
                } else {
                    $('#bank_transfer').css('display', 'none');
                }
                showPaymentSubmitButton(currentPackagePrice, packagePrice, currentPaymentActive, paymentMethod);
            });

            /* Form Default Submission */
            $('#submitPostForm').on('click', function (e) {
                e.preventDefault();
    
        if($('#paymentMethodId').val() == 3){
              Paytabs.initWithIframe(document.body,{
          settings: {
            secret_key: "<?php echo env('PAYTABS_SECRET_KEY'); ?> ",
            merchant_id:"<?php echo env('PAYTABS_MERCHANT_ID'); ?>",
            url_redirect:'https://www.theqqa.com/paytab/savedata',
            amount: $('.package_price').val(),
            title: $('#auth_service_type').val(),
            currency: "SAR",
            product_names: $('#auth_service_type').val(),
            order_id:  $('#post_id').val() ,
            ui_type: "iframe",
            is_popup: "true",
            ui_show_header: "true",
            ui_show_billing_address:"false",
        
       
   
            redirect_on_reject: 0,
              
          },
          customer_info: {
            first_name:  $('#auth_first_name').val(),
            phone_number:  $('#auth_phone').val(),
            email_address: $('#auth_email').val(),
            country_code: "966"
          },
        billing_address: {
            full_address: " 4410 طريق الدمام - حي المؤنسية",
            city: "Riyadh",
            state: "Riyadh",
            country: "SAU",
            postal_code: "13253"
          }
          
        });
            //   $('#postForm').submit();
        }

              else  if ($('#paymentMethodId').val() == 2) {
                    $('#postForm').submit();

                } else {
                    if (packagePrice <= 0) {
                        $('#postForm').submit();
                    }

                }


                // return false;
            });


        });

        @endif

        /* Show or Hide the Payment Submit Button */
        /* NOTE: Prevent Package's Downgrading */
        /* Hide the 'Skip' button if Package price > 0 */
        function showPaymentSubmitButton(currentPackagePrice, packagePrice, currentPaymentActive, paymentMethod) {
            if (packagePrice > 0) {
                $('#submitPostForm').show();
                $('#skipBtn').hide();

                if (currentPackagePrice > packagePrice) {
                    $('#submitPostForm').hide();
                }
                if (currentPackagePrice == packagePrice) {
                    if (paymentMethod == 'offlinepayment' && currentPaymentActive != 1) {
                        $('#submitPostForm').hide();
                        $('#skipBtn').show();
                    }
                }
            } else {
                $('#skipBtn').show();
            }
        }
    </script>
@endsection
