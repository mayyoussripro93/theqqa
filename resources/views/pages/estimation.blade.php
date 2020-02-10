{{--
 * Theqqa - #1 Cars Services Platform in KSA
 * Copyright (c) ProCrew. All Rights Reserved
 *
 * Website: http://www.procrew.pro
 *
 * Theqqa
--}}
@extends('layouts.master')

@section('search')
    @parent
    @include('pages.inc.contact-intro')
@endsection
<style>.intro-inner {
        display: none;
    !important;
    }</style>
@section('content')

    @include('common.spacer')
    <div class="main-container">
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
                                <div class="col-md-3 text-center"><img src="{{ asset('images/estimate-blue.png') }}"
                                                                       width="100"></div>
                                <div class="col-md-6 text-center"><h2>
                                        <strong>{{ t('estimation title') }}</strong></h2>
                                    <h4>{{ t('estimation_desc') }}</h4></div>
                                <div class="col-md-3 text-center"><span class="service-cost"><?php echo $package->price ?></span>
                                    <?php if( config('app.locale') == 'en' ){?>
                                    <span class="currency">SR</span>
                                    <?php } else{ ?>
                                    <span class="currency">ريال</span>
                                    <?php } ?></div>
                            </div>
                        </div>



                        <form id="signupForm_no" class="form-horizontal  client-form" method="post"
                              action="{{ lurl('contactfor/estimation') }}"  enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <fieldset>

                                <div id="item-list" class="ajData"></div>
                                <h5 class="list-title gray mt-0">
                                    <strong>{{ t('user data') }}</strong>
                                </h5>
                                <div class="row">
                                    <input type="hidden" name="service_type" value="{{t( 'estimation title') }}">
                                    <input type="hidden" name="car_url_type" value="1">
                                    <input type="hidden" name="id_code" value="{{$id_code}}">
                                    <input type="hidden" id="country_name" name="country_name" value="{{config('country.name')}}">
                                    <input type="hidden" id="country_code" name="country_code" value="{{config('country.code')}}">
                                    <div class="col-md-6">
                                        <?php $firstNameError = (isset($errors) and $errors->has('first_name')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="first_name" name="first_name" type="text"
                                                   placeholder="{{ t('First Name') }}"
                                                   class="form-control{{ $firstNameError }}"
                                                   value="{{ !empty(auth()->user()->name)?auth()->user()->name:
                                                   old('first_name') }}">
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="email" name="email" type="text"
                                                   placeholder="{{ t('Email Address') }}"
                                                   class="form-control{{ $emailError }}"
                                                   value="{{ !empty(auth()->user()->email)?auth()->user()->email:
                                                  old('email') }}">
                                        </div>
                                    </div>
                                </div>

                                <div id="car_ch"></div>
                                <h5 class="list-title gray mt-0 hidden_yes">
                                    <strong>{{ t('owner data') }}</strong>
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 hidden_yes">
                                        <?php $first_owner_name = (isset($errors) and $errors->has('first_owner_name')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="first_owner_name" name="first_owner_name" type="text"
                                                   placeholder="{{ t("First Owner's Name") }}"
                                                   class="form-control{{ $first_owner_name }}" value="{{ old('first_owner_name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 hidden_yes">
                                        <?php $middle_owner_name = (isset($errors) and $errors->has('middle_owner_name')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="middle_owner_name" name="middle_owner_name" type="text"
                                                   placeholder="{{ t("Middle Owner's Name") }}"
                                                   class="form-control{{ $middle_owner_name }}" value="{{ old('middle_owner_name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 hidden_yes">
                                        <?php $Last_owner_name = (isset($errors) and $errors->has('last_owner_name')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="last_owner_name" name="last_owner_name" type="text"
                                                   placeholder="{{ t("Last owner's name") }}"
                                                   class="form-control{{ $Last_owner_name }}" value="{{ old('last_owner_name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 hidden_yes">
                                        <?php $Mobile_number = (isset($errors) and $errors->has('Mobile_number')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="Mobile_number" name="Mobile_number" type="text"
                                                   placeholder="{{ t('Mobile_number') }}"
                                                   class="form-control{{ $Mobile_number }}" value="{{ old('Mobile_number') }}">
                                        </div>
                                    </div>
                                </div>
                                <div id="car_ch"></div>
                                <h5 class="list-title gray mt-0 hidden_yes">
                                    <strong>{{ t('car data') }}</strong>
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 hidden_yes">
                                        <?php $car_type = (isset($errors) and $errors->has('car_type')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="car_type" name="car_type" type="text"
                                                   placeholder="{{ t("Car Type") }}"
                                                   class="form-control{{ $car_type }}" value="{{ old('car_type') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 hidden_yes">
                                        <?php $car_category = (isset($errors) and $errors->has('car_category')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="car_category" name="car_category" type="text"
                                                   placeholder="{{ t("Car category") }}"
                                                   class="form-control{{ $car_category }}" value="{{ old('car_category') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 hidden_yes">
                                        <?php $car_brand = (isset($errors) and $errors->has('car_brand')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="car_brand" name="car_brand" type="text"
                                                   placeholder="{{ t("Car brand") }}"
                                                   class="form-control{{ $car_brand }}" value="{{ old('car_brand') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 hidden_yes">
                                        <?php $Year_manufacture = (isset($errors) and $errors->has('Year_manufacture')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="Year_manufacture" name="Year_manufacture" type="text"
                                                   placeholder="{{ t('Year of manufacture') }}"
                                                   class="form-control{{ $Year_manufacture }}" value="{{ old('Year_manufacture') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label style="font-size: 16px;">{{ t('Kilometers_car') }}:</label>
                                    </div>
                                    <div class="col-md-4">
                                        <?php $Kilometers = (isset($errors) and $errors->has("Kilometers")) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="Kilometers" name="Kilometers" type="text"
                                                   placeholder="{{ t('Kilometers_car') }}"
                                                   class="form-control{{ $Kilometers }}"
                                                   value="{{ old('Kilometers') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 hidden_yes">
                                        <label style="font-size: 16px;">{{ t('car Pictures') }}:</label>
                                    </div>
                                    <div class="col-md-4 hidden_yes">
                                        <?php $car_Pictures = (isset($errors) and $errors->has("car_Pictures")) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="car_Pictures" name="car_Pictures[]" type="file"
                                                   multiple="multiple"
                                                   placeholder="{{ t('car Pictures') }}"
                                                   class="form-control{{ $car_Pictures }}"
                                                   value="">
                                            <span class="text-gold">{{ t('car_Pictures_span_5')}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                            <?php $messageError = (isset($errors) and $errors->has('Notes')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group required">
											<textarea class="form-control{{ $messageError }}" id="message"
                                                      name="message" placeholder="{{ t('Notes') }}"
                                                      rows="7">{{ old('message') }}</textarea>
                                            </div>

                                            <!-- Captcha -->
                                            @if (config('settings.security.recaptcha_activation'))
                                                <?php $recaptchaError = (isset($errors) and $errors->has('g-recaptcha-response')) ? ' is-invalid' : ''; ?>
                                                <div class="form-group required">
                                                    <div>
                                                        {!! Recaptcha::render(['lang' => config('app.locale')]) !!}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Start - Payment Buttons -->
                                        <div class="col-md-12 text-center mt-4">
                                            <div class="form-group">
                                                <button id="signupBtn_no1" type="submit"
                                                        class="btn btn-success btn-lg submitPostForm">
                                                   <span class="hidden-md">{{ t('Pay') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- End - Payment Buttons -->

                                </div>
                            </fieldset>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script src="{{ url('assets/js/form-validation.js') }}"></script>
    <script>
        $(document).ready(function () {
            /* Submit Form */
            $("#signupBtn_no1").click(function () {
                $("#signupForm_no").submit();
                return false;
            });
            $("#car_Pictures").on("change", function() {
                if ($("#car_Pictures")[0].files.length > 4 || $("#car_Pictures")[0].files.length < 4) {
                    Swal.fire({
                        title: '',
                        type: 'error',
                        html:'{!! t('car_Pictures_span_5') !!}',
                        showCloseButton: true,
                        focusConfirm: false,
                        confirmButtonText:
                            '<i class="fa fa-times-circle"></i>  &nbsp;{!! t("Close") !!}',
                        confirmButtonAriaLabel: 'Thumbs up, great!',

                    })
                    // alert("You can select only 2 images");
                    $(this).val('');
                    document.getElementById("signupBtn_no1").disabled = true;
                }else{
                    document.getElementById("signupBtn_no1").disabled = false;
                }
            });
        });
    </script>


    <script>
        var App = jQuery('#clientId');
        var select = this.value;
        App.change(function () {
            if ($(this).val() === 'no') {
                $('#car_ch').append('<input type="hidden" name="for_estimat" id="for_estimat" value="no">');
                $('.hidden_no').hide();
                $('.hidden_yes').show();
                $('#search').hide();
            } else if ($(this).val() === 'yes') {
                $('#car_ch').append('<input type="hidden" name="for_estimat" id="for_estimat" value="yes">');
                $('.hidden_no').show();
                $('.hidden_yes').hide();
                $('#search').show();
            }
        });
    </script>
    <script>
        window.addEventListener("load", function () {

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
            var val2;

            $('#postSearch').change(function () {
                var val = $(this).val();
                if ($('#postlSearch').val() != val2 && $('#postlSearch').val() != "") {
                    val2 = $('#postlSearch').val();
                    $.ajax({
                        url: '{{ url('contactfor/mogaz') }}',
                        method: "GET",
                        data: {id: val2},
                        success: function (data) {
                            data = JSON.parse(data);
                            url = "{{ Request::root()}}/" + data.uri;
                            $('#car_url').val(url);
                            $('.ajData').empty();
                            $('.ajData').append(data.data);


                        }
                    });
                }
            })
            if ($('#clientId').val() === 'no') {
                $('#car_ch').append('<input type="hidden" name="for_estimat" id="for_estimat" value="no">');
                $('.hidden_no').hide();
                $('.hidden_yes').show();
                $('#search').hide();
            } else if ($('#clientId').val() === 'yes') {
                $('#car_ch').append('<input type="hidden" name="for_estimat" id="for_estimat" value="yes">');
                $('.hidden_no').show();
                $('.hidden_yes').hide();
                $('#search').show();
            }

        });

    </script>
@endsection
