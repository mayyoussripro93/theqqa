{{--
 * Theqqa - #1 Cars Services Platform in KSA
 * Copyright (c) ProCrew. All Rights Reserved
 *
 * Website: http://www.procrew.pro
 *
 * Theqqa
 */
--}}
@extends('layouts.master')

@section('search')
    @parent

@endsection
<style>.intro-inner {
        display: none;
    !important;
    }</style>
@section('content')

    @include('common.spacer')
    <div class="main-container booking-page">
        <div class="container">
            <div class="row clearfix">

                @if (isset($errors) and $errors->any())
                    <div class="col-xl-12">
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5>
                                <strong>{{ t('Oops ! An error has occurred. Please correct the red fields in the form') }}</strong>
                            </h5>
                            <ul class="list list-check">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if (Session::has('flash_notification'))
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-xl-12">
                                @include('flash::message')
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-md-12">
                    <div class="contact-form">
                        <div class="service-details alert alert-info">
                            <div class="row">
                                <div class="col-md-3 text-center"><img src="{{ asset('images/budget-gold.png') }}"
                                                                       width="100"></div>
                                <div class="col-md-6 text-center"><h2><strong>{{ t('Booking the car') }}</strong></h2>
                                    <h4>{{ t('booking_desc') }}</h4></div>
                                <div class="col-md-3 text-center"><span
                                            class="service-cost">{{0.025 *  $post->price }} <span
                                                class="currency">{!! config('currency')!!}</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="page-content">
                            <div class="inner-box">

                                <h2 class="title-2" style="color: #8f7630;"><strong><i
                                                class="icon-money"></i> {{ t('Car Booking Deposit') }}
                                    </strong></h2>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <form class="form" id="postForm" method="POST"
                                              action="{{  lurl('contactfor/'.$post->id.'/booking_car') }}"
                                              enctype="multipart/form-data">
                                            {!! csrf_field() !!}
                                            
                                     <?php if(auth()->check()){
                                        $login_user=Auth::user();
                                    ?>
                                    <input type="hidden" name="first_name" id="auth_first_name" value="{{ $login_user->first_name }}">
                                    <input type="hidden" name="email"  id="auth_email"  value="{{ $login_user->email }}">
                                    <input type="hidden" name="phone" id="auth_phone"  value="{{ $login_user->phone }}">
                                <?php }?>
                                            <input type="hidden" name="post_id"  id="post_id" value="{{ $post->id }}">
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                            <input type="hidden" name="post_booking_price"
                                                   value=" {{ 0.025 *  $post->price }} {!! config('currency')!!}">
                                            <fieldset>

                                                <div class="well pb-0">
                                                    <div class="row justify-content-md-center"
                                                         style="margin-bottom: 30px;">
                                                        <div class="col-lg-8 col-md-10 col-sm-12">
                                                            <h4 class="book-row">
                                                                <div class="book-text">{{ t('Price of the car in Theqqa website') }}</div>
                                                                <div class="book-value">{{ $post->price }} {!! config('currency')!!}</div>
                                                            </h4>
                                                            <h4 class="book-row">
                                                                <div class="book-text">{{ t('The car will be booked with a payment of 2.5% of the value of the car.') }}</div>
                                                                <div class="book-value">{{ $post->price }} * 2.5%
                                                                    = {{ 0.025 *  $post->price }} {!! config('currency')!!}</div>
                                                            </h4>
{{--                                                            <h4 class="book-row">--}}
{{--                                                                <div class="book-text">{{ t('guarantee_value') }}</div>--}}
{{--                                                                <div class="book-value">--}}
{{--                                                                    100 {!! config('currency')!!}</div>--}}
{{--                                                            </h4>--}}
                                                            <h4 class="book-row">
                                                                <div class="book-text">{{ t('total_value') }}</div>
                                                                <div class="book-value">  {{ 0.025 *  $post->price }} {!! config('currency')!!}</div>
                                                            </h4>
                                                        </div>
                                                    </div>

                                                    <?php $packageIdError = (isset($errors) and $errors->has('package_id')) ? ' is-invalid' : ''; ?>
                                                    <div class="row justify-content-md-center">
                                                        <div class="col-lg-8 col-md-10 col-sm-12">
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

                                                                        ?>
                                                                        <tr>
                                                                            @if($package->id =='19'or $package->id =='20')
                                                                                <td class="text-left align-middle p-3">

                                                                                    <div class="form-check">
                                                                                        <input type="hidden" name="service_type"  id="auth_service_type"  value="{{$package->name }}">
                                                                                        <input type="hidden" value="{{ 0.025 *  $post->price  }}" class="package_price">
                                                                                          <input type="hidden" value="{{ $package->tid }}" class="paytabs_package_id">
                                                                                        <input class="form-check-input package-selection{{ $packageIdError }}"
                                                                                               type="radio" checked
                                                                                               name="package_id"
                                                                                               id="packageId-{{ $package->tid }}"
                                                                                               value="{{ $package->tid }}"
                                                                                               data-name="{{ $package->name }}"
                                                                                               data-currencysymbol="{{ $package->currency->symbol }}"
                                                                                               data-currencyinleft="{{ $package->currency->in_left }}"
                                                                                                {{ (old('package_id', $currentPackageId)==$package->tid) ? ' checked' : (($package->price==0) ? ' checked' : '') }} {{ $packageStatus }}>


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
                                                                                    <p id="price-{{ $package->tid }}"
                                                                                       class="mb-0">
                                                                                        @if ($package->currency->in_left == 1)
                                                                                            <span class="price-currency">
                                                                                            {{--{!! $package->currency->symbol !!}--}}
                                                                                                {!! config('currency')!!}
                                                                                        </span>
                                                                                        @endif
                                                                                        <span class="price-int">{{ 0.025 *  $post->price }} </span>
                                                                                        @if ($package->currency->in_left == 0)
                                                                                            <span class="price-currency">
                                                                                            {{--{!! $package->currency->symbol !!}--}}
                                                                                                {!! config('currency')!!}
                                                                                        </span>
                                                                                        @endif
                                                                                    </p>
                                                                                </td>
                                                                            @endif
                                                                        </tr>
                                                                    @endforeach

                                                                    <tr class="book-pay">
                                                                        <td colspan="2" class="align-middle p-3">
                                                                            <div class="row" style="margin-top: 1rem;">
                                                                                <div class="col-md-5 col-sm-5">
                                                                                    <label class="mb-0">{{ t('choose_payment_method') }}</label>
                                                                                </div>
                                                                                <div class="col-md-7 col-sm-7">
                                                                                    <?php $paymentMethodIdError = (isset($errors) and $errors->has('payment_method_id')) ? ' is-invalid' : ''; ?>
                                                                                    <div class="form-group mb-0">
                                                                                        <div class="col-md-12 col-sm-12 p-0">
                                                                                            <select class="form-control selecter{{ $paymentMethodIdError }}"
                                                                                                    name="payment_method_id"
                                                                                                    id="paymentMethodId">
                                                                                                @foreach ($paymentMethods as $paymentMethod)
                                                                                                 @if( $paymentMethod->id !=1 )
                                                                                                    {{--@if (view()->exists('payment::' . $paymentMethod->name))--}}
                                                                                                    <option value="{{ $paymentMethod->id }}"
                                                                                                            data-name="{{ $paymentMethod->name }}">
                                                                                                        @if ($paymentMethod->name == 'offlinepayment')
                                                                                                            {{ trans('offlinepayment::messages.Offline Payment') }}
                                                                                                        @else
                                                                                                            {{ $paymentMethod->display_name }}
                                                                                                        @endif
                                                                                                    </option>
                                                                                                      @endif
                                                                                                    {{--@endif--}}
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group mb-0" id="bank_transfer">
                                                                                <div class="row" style="margin-top: 1rem;">
                                                                                    <div class="col-md-5 col-sm-5">
                                                                                        <label for="bank_transfer_in"
                                                                                               class="control-label">{{ t('upload_bank_transfer') }}</label>
                                                                                    </div>
                                                                                    <div class="col-md-7 col-sm-7">
                                                                                        <input type="file"
                                                                                               name="bank_transfer_in"
                                                                                               id="bank_transfer_in" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            {{--<input type="file"--}}
                                                                                   {{--name="bank_transfer"--}}
                                                                                   {{--id="bank_transfer"--}}
                                                                                   {{--style="display: none"--}}
                                                                                   {{--required>--}}
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                @if (isset($paymentMethods) and $paymentMethods->count() > 0)
                                                                                    <!-- Payment Plugins -->
                                                                                    <?php $hasCcBox = 0; ?>
                                                                                    @foreach($paymentMethods as $paymentMethod)
                                                                                        @if (view()->exists('payment::' . $paymentMethod->name))
                                                                                            @include('payment::' . $paymentMethod->name, [$paymentMethod->name . 'PaymentMethod' => $paymentMethod])
                                                                                        @endif

                                                                                        <?php if ($paymentMethod->has_ccbox == 1 && $hasCcBox == 0) $hasCcBox = 1; ?>
                                                                                    @endforeach
                                                                                @endif


                                                                                <!-- Button  -->
                                                                                    <div class="form-group">
                                                                                        <div class="col-md-12 text-center mt-3">
                                                                                                 <button id="submitPostForm"
                                                        class="submitPostForm btn btn-success btn-lg"> <a class ="PT_open_popup" id="en_button">{{ t('Pay') }}</a> </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_scripts')

    <!-- bxSlider Javascript file -->
    <script src="{{ url('assets/plugins/bxslider/jquery.bxslider.min.js') }}"></script>

 <script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.2.3/jquery.payment.min.js"></script>


    <script src="{{ url('assets/js/form-validation.js') }}"></script>
    <script>
        $(document).ready(function () {
            var alert_service = "{!! t( 'For_service_alert') !!}";
            Swal.fire({
                title: '{!! t("To Request This Service") !!}',
                type: 'info',
                html:alert_service,
                showCloseButton: true,
                focusConfirm: false,
                confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> {!! t("let's start") !!}',
                confirmButtonAriaLabel: 'Thumbs up, great!',
            })
            /* Submit Form */
            if ($('#paymentMethodId').val() == 2) {

                $('#bank_transfer').css('display', 'block');
            } else {
                $('#bank_transfer').css('display', 'none');
            }
        });
    </script>

    <script>
        var App = jQuery('#clientId');
        var select = this.value;
        App.change(function () {
            if ($(this).val() === 'no') {
                $('.client-form').hide();
                $('#signupForm_no').show();
            }
        });
    </script>
      <script src="https://paytabs.com/express/v4/paytabs-express-checkout.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
       <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.2.3/jquery.payment.min.js"></script>
    @if (file_exists(public_path() . '/assets/plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js'))
        <script src="{{ url('/assets/plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js') }}"
                type="text/javascript"></script>
    @endif

    <script>


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

                if ($(this).val() == 2) {

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
            url_redirect:'https://www.theqqa.com/paytab/booking/savedata',
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
          },
         
         
        });
            //   $('#postForm').submit();
        }

              else  if ($('#paymentMethodId').val() == 2) {
                    $('#postForm').submit();

              }
                
                
                
                

        //         return false;
            });
        });


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
