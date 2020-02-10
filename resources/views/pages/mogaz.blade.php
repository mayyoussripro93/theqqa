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

        <div class="alert">
            {{ session('info') }}
        </div>


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
                                <div class="col-md-3 text-center"><img src="{{ asset('images/mogaz-blue.png') }}"
                                                                       width="100"></div>
                                <div class="col-md-6 text-center"><h2>
                                        <strong>{{ t('mogaz_title') }}</strong></h2>
                                    <h4>{{ t('mogaz_desc') }}</h4></div>
                                <div class="col-md-3 text-center"><span class="service-cost"><?php echo $package->price ?></span>
                                    <?php if( config('app.locale') == 'en' ){?>
                                    <span class="currency">SR</span>
                                    <?php } else{ ?>
                                    <span class="currency">ريال</span>
                                   <?php } ?>

                                </div>
                            </div>
                        </div>


                        <form id="signupForm_no" class="form-horizontal  client-form" method="post"
                              action="{{ lurl('contactfor/mogaz') }}">
                            {!! csrf_field() !!}


                            <fieldset>
                                <h5 class="list-title gray mt-0">
                                    <strong>{{ t('car register') }}</strong>
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <select name="client" id="clientId" class='form-control selecter' required>
                                            @if(!empty($_GET['p']))
                                                <option value="no" @if (old('client') == "no") {{ 'selected' }} @endif> {{ t('No') }} </option>
                                                <option value="yes"
                                                        selected @if (old('client') == "yes") {{ 'selected' }} @endif> {{ t('Yes') }} </option>

                                            @else
                                                <option value="no" @if (old('client') == "no") {{ 'selected' }} @endif> {{ t('No') }} </option>
                                                <option value="yes" @if (old('client') == "yes") {{ 'selected' }} @endif> {{ t('Yes') }} </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="id_code" value="{{$id_code}}">
                                <h5 class="list-title gray mt-0 hidden_no" style="display: none">
                                    <strong>{{ t('theqqa url')  }}:</strong>
                                </h5>
                                <div class="row hidden_no" style="display: none">
                                    <div class="col-md-6 search-col relative locationicon">

                                        <input type="hidden" id="postlSearch" name="l" value="">
                                        <input type="hidden" id="country_name" name="country_name" value="{{config('country.name')}}">
                                        <input type="hidden" id="country_code" name="country_code" value="{{config('country.code')}}">
                                        <?php $carurlError = (isset($errors) and $errors->has('car_url')) ? ' is-invalid' : ''; ?>
                                        <input type="text" id="postSearch" name="location"
                                               class="form-control {{$carurlError}} "
                                               placeholder="{{ t('Find')  }}"
                                               value="{{!empty($_GET['p'])?$_GET['p']:''}}">
                                        <span class="text-gold">{{t('service')}}</span>
                                    </div>
                                </div>
                                <div id="item-list" class="ajData"></div>
                                <h5 class="list-title gray mt-0">
                                    <strong>{{ t('user data') }}</strong>
                                </h5>
                                <div class="row">
                                    <input type="hidden" name="service_type" value="{{t( 'mogaz service') }}">
                                    <input type="hidden" name="car_url_type" value="1">
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
                                    <strong>{{ t('car data') }}</strong>
                                </h5>
                                <div class="row">

                                    <div class="col-md-6 hidden_yes">
                                        <?php $owner_id = (isset($errors) and $errors->has('owner_id')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="owner_id" name="owner_id" type="text"
                                                   placeholder="{{ t('Owner ID') }}"
                                                   class="form-control{{ $owner_id }}" value="{{ old('owner_id') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 hidden_yes">
                                        <?php $plate_number = (isset($errors) and $errors->has('plate_number')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="plate_number" name="plate_number" type="text"
                                                   placeholder="{{ t('plate number') }}"
                                                   class="form-control{{ $plate_number }}"
                                                   value="{{ old('plate_number') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 hidden_yes">
                                        <?php $serial_number = (isset($errors) and $errors->has('serial_number')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="serial_number" name="serial_number" type="text"
                                                   placeholder="{{ t('serial number') }}"
                                                   class="form-control{{ $serial_number }}"
                                                   value="{{ old('serial_number') }}">
                                        </div>
                                    </div>


                                    @if(!empty($_GET['u']))
                                        <div class="col-md-6">
                                            <?php $carurlError = (isset($errors) and $errors->has('car_url')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group required">
                                                <input id="car_url" name="car_url" type="hidden"
                                                       placeholder="{{ t('car url') }}"
                                                       class="form-control{{ $carurlError }}"
                                                       value="{{urldecode($_GET['u'])}}">
                                            </div>

                                        </div>
                                    @else
                                        <div class="col-md-6">
                                            <?php $carurlError = (isset($errors) and $errors->has('car_url')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group required">
                                                <input id="car_url" name="car_url" type="hidden"
                                                       placeholder="{{ t('car url') }}"
                                                       class="form-control{{ $carurlError }}"
                                                       value="{{!empty($post_url)?$post_url:''}}">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-12">
                                        <?php $messageError = (isset($errors) and $errors->has('message')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
											<textarea class="form-control{{ $messageError }}" id="message"
                                                      name="message" placeholder="{{ t('Message') }}"
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
        var App = jQuery('#clientId');
        var select = this.value;
        App.change(function () {
            if ($(this).val() === 'no') {
                $('#car_ch').append('<input type="hidden" name="for_mogaz" id="for_mogaz" value="no">');
                $('.hidden_no').hide();
                $('.hidden_yes').show();
                $('#search').hide();
            } else if ($(this).val() === 'yes') {
                $('#car_ch').append('<input type="hidden" name="for_mogaz" id="for_mogaz" value="yes">');
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
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> {!! t("let's start") !!}',
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
                $('#car_ch').append('<input type="hidden" name="for_mogaz" id="for_mogaz" value="no">');
                $('.hidden_no').hide();
                $('.hidden_yes').show();
                $('#search').hide();
            } else if ($('#clientId').val() === 'yes') {
                $('#car_ch').append('<input type="hidden" name="for_mogaz" id="for_mogaz" value="yes">');
                $('.hidden_no').show();
                $('.hidden_yes').hide();
                $('#search').show();
            }

        });

    </script>
@endsection
