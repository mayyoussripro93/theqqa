{{--
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
--}}
@extends('layouts.master')

@section('content')
    @if (!(isset($paddingTopExists) and $paddingTopExists))
        <div class="h-spacer"></div>
    @endif
    <div class="main-container">
        <div class="container">
            <div class="row">

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

                <div class="col-md-8 page-content">
                    <div class="inner-box">
                        <h2 class="title-2">
                            <strong><i class="icon-user-add"></i> {{ t('Create your account, Its free') }}</strong>
                        </h2>

                        @if (config('settings.social_auth.social_login_activation'))
                            <div class="row mb-3 d-flex justify-content-center pl-3 pr-3">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-fb">
                                        <a href="{{ lurl('auth/facebook') }}" class="btn-fb"><i
                                                    class="icon-facebook"></i> {!! t('Connect with Facebook') !!}</a>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-danger">
                                        <a href="{{ lurl('auth/google') }}" class="btn-danger"><i
                                                    class="icon-googleplus-rect"></i> {!! t('Connect with Google') !!}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="row d-flex justify-content-center loginOr">
                                <div class="col-xl-12 mb-1">
                                    <hr class="hrOr">
                                    <span class="spanOr rounded">{{ t('or') }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="row mt-5">
                            <div class="col-xl-12">
                                <form class="form-horizontal">

                                </form>
                                <form id="signupForm_user" class="form-horizontal client-form" method="POST"
                                      action="{{ url()->current() }}" enctype="multipart/form-data">
                                    {!! csrf_field() !!}
                                    <fieldset>

                                        <div class="form-group row required">
                                            <label class="col-md-4 col-form-label">{{ t('Register type') }}
                                                <sup>*</sup></label>
                                            <div class="col-md-6">
                                                <select name="client" id="clientId" class='form-control selecter'
                                                        value="{{old('client')}}">
                                                    <option value="user" @if(old('client') == "user") {{ 'selected' }} @endif> {{ t('User') }} </option>
                                                    <option value="company" @if(old('client') == "company") {{ 'selected' }} @endif> {{ t('Company') }} </option>
                                                    <option value="exhibition" @if(old('client') == "exhibition") {{ 'selected' }} @endif> {{ t('exhibition') }} </option>
                                                    <option value="shop" @if(old('client') == "shop") {{ 'selected' }} @endif> {{ t('shop') }} </option>
                                                </select>
                                            </div>
                                        </div>

                                        <?php $CompanyError = (isset($errors) and $errors->has('company')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group row required" id="companyId" style="display: none">
                                            <label class="col-md-4 col-form-label{{ $CompanyError }}">{{ t('Company type') }}
                                                <sup>*</sup></label>
                                            <div class="col-md-6">
                                                <select name="company"
                                                        class="form-control selecter{{ $CompanyError }}">
                                                    <option value="3"> {{ t('Car Maintenance Company') }} </option>
                                                    <option value="4"> {{ t('Car Inspection Company') }} </option>
                                                    <option value="5"> {{ t('Car Shipping Company') }} </option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- company -->
                                        <?php $CompanyError = (isset($errors) and $errors->has('company')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group row required" id="shopId" style="display: none">
                                            <label class="col-md-4 col-form-label{{ $CompanyError }}">{{ t('shop type') }}
                                                <sup>*</sup></label>
                                            <div class="col-md-6">
                                                <select name="shop"
                                                        class="form-control selecter{{ $CompanyError }}">
                                                    <option value="10"> {{ t('Car carrier') }} </option>
                                                    <option value="8"> {{ t('accessories shop') }} </option>
                                                    <option value="9"> {{ t('Spare parts shop') }} </option>
                                                    <option value="7"> {{ t('Batteries shop' ) }} </option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- name -->
                                        <?php $nameError = (isset($errors) and $errors->has('name')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group row required">
                                            <label class="col-md-4 col-form-label">{{ t('Name') }} <sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input name="name" placeholder="{{ t('Name') }}"
                                                       class="form-control input-md{{ $nameError }}" type="text"
                                                       value="{{ old('name') }}">
                                            </div>
                                        </div>

                                        <!-- country_code -->
                                        @if (empty(config('country.code')))
                                            <?php $countryCodeError = (isset($errors) and $errors->has('country_code')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group row required">
                                                <label class="col-md-4 col-form-label{{ $countryCodeError }}"
                                                       for="country_code">{{ t('Your Country') }} <sup>*</sup></label>
                                                <div class="col-md-6">
                                                    <select id="countryCode_user" name="country_code"
                                                            class="form-control sselecter{{ $countryCodeError }}">
                                                        <option value="0" {{ (!old('country_code') or old('country_code')==0) ? 'selected="selected"' : '' }}>{{ t('Select') }}</option>
                                                        @foreach ($countries as $code => $item)
                                                            <option value="{{ $code }}" {{ (old('country_code', (!empty(config('ipCountry.code'))) ? config('ipCountry.code') : 0)==$code) ? 'selected="selected"' : '' }}>
                                                                {{ $item->get('name') }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @else
                                            <input id="countryCode_user" name="country_code" type="hidden"
                                                   value="{{ config('country.code') }}">
                                        @endif

                                        @if (isEnabledField('phone'))
                                        <!-- phone -->
                                            <?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group row required">
                                                <label class="col-md-4 col-form-label">{{ t('Phone') }}
                                                    {{--@if (!isEnabledField('email'))--}}
                                                    <sup>*</sup>
                                                    {{--@endif--}}
                                                </label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span id="phoneCountry_user"
                                                                  class="input-group-text">{!! getPhoneIcon(old('country', config('country.code'))) !!}</span>
                                                        </div>

                                                        <input name="phone"
                                                               placeholder="{{ (!isEnabledField('email')) ? t('Mobile Phone Number') : t('Phone Number') }}"
                                                               class="form-control input-md{{ $phoneError }}"
                                                               type="text"
                                                               value="{{ phoneFormat(old('phone'), old('country', config('country.code'))) }}"
                                                        >

                                                        <div class="input-group-append tooltipHere" data-placement="top"
                                                             data-toggle="tooltip"
                                                             data-original-title="{{ t('Hide the phone number on the ads.') }}">
															<span class="input-group-text">
																<input name="phone_hidden" id="phoneHidden_user"
                                                                       type="checkbox"
                                                                       value="1" {{ (old('phone_hidden')=='1') ? 'checked="checked"' : '' }}>&nbsp;<small>{{ t('Hide') }}</small>
															</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if (isEnabledField('email'))
                                        <!-- email -->
                                            <?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group row required">
                                                <label class="col-md-4 col-form-label" for="email">{{ t('Email') }}
                                                    {{--@if (!isEnabledField('phone'))--}}
                                                    <sup>*</sup>
                                                    {{--@endif--}}
                                                </label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="icon-mail"></i></span>
                                                        </div>
                                                        <input id="email_user"
                                                               name="email"
                                                               type="email"
                                                               class="form-control{{ $emailError }}"
                                                               placeholder="{{ t('Email') }}"
                                                               value="{{ old('email') }}"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <?php $nameError = (isset($errors) and $errors->has('id_number')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group row required"  id="id_number">
                                            <label class="col-md-4 col-form-label">{{ t('id number') }}
                                                <sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input name="id_number" placeholder="{{ t('id number') }}"
                                                       class="form-control input-md{{ $nameError }}" type="text"
                                                       value="{{ old('id_number') }}">
                                            </div>
                                        </div>


                                        <?php $nameError = (isset($errors) and $errors->has('id_number_owner')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group row required" id="id_number_owner" style="display: none" >
                                            <label class="col-md-4 col-form-label">{{ t( 'Owner ID') }}
                                                <sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input name="id_number_owner" placeholder="{{ t( 'Owner ID') }} "
                                                       class="form-control input-md{{ $nameError }}" type="text"
                                                       value="{{ old('id_number_owner') }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-6">
                                                <div class="form-group ads-googlemaps" id="googleMap_from"
                                                     style="width:100%;height:250px;display: none">
                                                    <div class="card ">
                                                        <div class="card-header">{{ t('Location\'s Map') }}</div>
                                                        <div class="card-content">
                                                            <div class="card-body text-left p-0">
                                                                <div class="ads-googlemaps">
                                                                </div>
                                                                {{--<iframe id="googleMaps" width="100%" height="250" frameborder="0"--}}
                                                                {{--scrolling="no" marginheight="0" marginwidth="0" src=""></iframe>--}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $subladmin1 = (isset($errors) and $errors->has('subladmin1')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group row required" id="subadmin1" style="display: none">
                                            <label class="col-md-4 col-form-label">{{ t( 'subladmin1') }}
                                                <sup>*</sup></label>
                                            <div class="col-md-6">



                                                <select name="subladmin1[]" id="subladmin1"
                                                        class="form-control {{ $subladmin1 }}" multiple="multiple">
                                                    @foreach ($subladmin1s as $subladmin1)
                                                        <option lat="{{ $subladmin1->latitude }}"
                                                                long="{{ $subladmin1->longitude }}"
                                                                value="{{ $subladmin1->id }}"> {{ $subladmin1->name }} </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        @if (isEnabledField('username'))
                                        <!-- username -->
                                            <?php $usernameError = (isset($errors) and $errors->has('username')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group row required">
                                                <label class="col-md-4 col-form-label"
                                                       for="email">{{ t('Username') }}</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="icon-user"></i></span>
                                                        </div>
                                                        <input id="username_user"
                                                               name="username"
                                                               type="text"
                                                               class="form-control{{ $usernameError }}"
                                                               placeholder="{{ t('Username') }}"
                                                               value="{{ old('username') }}"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    <!-- password -->
                                        <?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group row required">
                                            <label class="col-md-4 col-form-label" for="password">{{ t('Password') }}
                                                <sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input id="password_user" name="password" type="password"
                                                       class="form-control{{ $passwordError }}"
                                                       placeholder="{{ t('Password') }}">
                                                <br>
                                                <input id="password_confirmation_user" name="password_confirmation"
                                                       type="password" class="form-control{{ $passwordError }}"
                                                       placeholder="{{ t('Password Confirmation') }}">
                                                <small id=""
                                                       class="form-text text-muted">{{ t('At least 5 characters') }}</small>
                                            </div>
                                        </div>

                                        <!-- file -->
                                        <?php $fileError = (isset($errors) and $errors->has('file')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group row required" id="file_comp" style="display: none">
                                            <label class="col-md-4 col-form-label" for="file">{{ t('file') }}
                                                <sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input  type="file" multiple="multiple" name="file[]"
                                                       class="form-control{{ $fileError }}"
                                                       placeholder="{{ t('file') }}">
                                                <small id=""
                                                       class="form-text text-muted">{{ t("Please provide a copy of the owner's or agent's identity and the copy of the Commercial Record .") }}</small>
                                            </div>

                                        </div>
                                        @if (config('settings.security.recaptcha_activation'))
                                        <!-- recaptcha -->
                                            <?php $recaptchaError = (isset($errors) and $errors->has('g-recaptcha-response')) ? 'is-invalid' : ''; ?>
                                            <div class="form-group row required">
                                                <label class="col-md-4 col-form-label{{ $recaptchaError }}"
                                                       for="g-recaptcha-response"></label>
                                                <div class="col-md-6">
                                                    {!! Recaptcha::render(['lang' => config('app.locale')]) !!}
                                                </div>
                                            </div>
                                        @endif

                                    <!-- term -->
                                        <?php $termError = (isset($errors) and $errors->has('term')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group row required">
                                            <label class="col-md-4 col-form-label"></label>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input name="term" id="term_user"
                                                           class="form-check-input{{ $termError }}"
                                                           value="1"
                                                           type="checkbox" {{ (old('term')=='1') ? 'checked="checked"' : '' }}
                                                    >

                                                    <label class="form-check-label" for="invalidCheck3">
                                                        {!! t('I have read and agree to the <a :attributes>Terms & Conditions</a>', ['attributes' => getUrlPageByType('terms')]) !!}
                                                    </label>
                                                </div>
                                                <div style="clear:both"></div>
                                            </div>
                                        </div>

                                        <!-- Button  -->
                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label"></label>
                                            <div class="col-md-6">
                                                <button id="signupBtn_user"
                                                        class="btn btn-success btn-lg"> {{ t('Register') }} </button>
                                            </div>
                                        </div>

                                        <div class="mb-5"></div>

                                    </fieldset>
                                </form>

                            </div>

                        </div>


                    </div>
                </div>

                <div class="col-md-4 reg-sidebar">
                    <div class="reg-sidebar-inner text-center">
                        <div class="promo-text-box"><i class="icon-picture fa fa-4x icon-color-1"></i>
                            <h3><strong>{{ t('Post a Free Classified') }}</strong></h3>
                            <p>
                                {{ t('Do you have something to sell, to rent, any service to offer or a job offer? Post it at :app_name, its free, local, easy, reliable and super fast!',
                                ['app_name' => config('app.name')]) }}
                            </p>
                        </div>
                        <div class="promo-text-box"><i class=" icon-pencil-circled fa fa-4x icon-color-2"></i>
                            <h3><strong>{{ t('Create and Manage Items') }}</strong></h3>
                            <p>{{ t('Become a best seller or buyer. Create and Manage your ads. Repost your old ads, etc.') }}</p>
                        </div>
                        <div class="promo-text-box"><i class="icon-heart-2 fa fa-4x icon-color-3"></i>
                            <h3><strong>{{ t('Create your Favorite ads list.') }}</strong></h3>
                            <p>{{ t('Create your Favorite ads list, and save your searches. Don\'t forget any deal!') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script>
        $(document).ready(function () {
            /* Submit Form */
            $("#signupBtn_user").click(function () {
                $("#signupForm_user").submit();
                return false;
            });


            if ($('#clientId').val() === 'user') {

                $('#companyId').hide();
                $('#shopId').hide();
                $('#id_number').show();
                $('#id_number_owner').hide();
                $('#googleMap_from').hide();
                $('#file_comp').hide();
                $('#subadmin1').hide();

            } else if ($('#clientId').val() === 'company') {

                $('#companyId').show();
                $('#shopId').hide();
                $('#id_number').hide();
                $('#id_number_owner').show();
                $('#googleMap_from').show();
                $('#file_comp').show();
                $('#subadmin1').show();

            } else if ($('#clientId').val() === 'shop') {

                $('#companyId').hide();
                $('#shopId').show();
                $('#id_number').hide();
                $('#id_number_owner').show();
                $('#googleMap_from').show();
                $('#file_comp').show();
                $('#subadmin1').show();
            } else if ($('#clientId').val() === 'exhibition') {

                $('#companyId').hide();
                $('#shopId').hide();
                $('#id_number').hide();
                $('#id_number_owner').show();
                $('#googleMap_from').show();
                $('#file_comp').show();
                $('#subadmin1').show();
            }
        });
    </script>

    <script>

        var App = jQuery('#clientId');
        App.change(function () {
            if ($(this).val() === 'user') {

                $('#companyId').hide();
                $('#shopId').hide();
                $('#id_number').show();
                $('#id_number_owner').hide();
                $('#googleMap_from').hide();
                $('#file_comp').hide();
                $('#subadmin1').hide();
            } else if ($(this).val() === 'company') {
                $('#companyId').show();
                $('#shopId').hide();
                $('#id_number').hide();
                $('#id_number_owner').show();
                $('#googleMap_from').show();
                $('#file_comp').show();
                $('#subadmin1').show();

            } else if ($(this).val() === 'shop') {
                $('#companyId').hide();
                $('#shopId').show();
                $('#id_number').hide();
                $('#id_number_owner').show();
                $('#googleMap_from').show();
                $('#file_comp').show();
                $('#subadmin1').show();
            } else if ($(this).val() === 'exhibition') {
                $('#companyId').hide();
                $('#shopId').hide();
                $('#id_number').hide();
                $('#id_number_owner').show();
                $('#googleMap_from').show();
                $('#file_comp').show();
                $('#subadmin1').show();
            }
        });
    </script>
    <script src="{{ url('assets/plugins/bxslider/jquery.bxslider.min.js') }}"></script>
    <link href="css/multi-select.css"/>
    <script src="js/jquery.multi-select.js" type="text/javascript"></script>
    <script>

        @if (config('settings.single.show_post_on_googlemap'))
        /* Google Maps */
        getGoogleMaps(
            '{{ config('services.googlemaps.key') }}',
            '{{ (isset($post->city) and !empty($post->city)) ? addslashes($post->city->name) . ',' . config('country.name') : config('country.name') }}',
            '{{ config('app.locale') }}'
        );
        function getCities(rlat, rlng, url) {
            $.ajax({
                type: "GET",
                data: {
                    lat: rlat,
                    lng: rlng,
                },
                url: url,
                cache: false,
                success: function (res) {
                    if($("#subladmin1").val() == null){
                        let all = [];
                        all.push(res.city.id);
                        $("#subladmin1").val(all)
                        $('#subladmin1').multiSelect();
                        changeLocation()
                    }else{
                        let all = $("#subladmin1").val();
                        all.push(res.city.id);
                        $("#subladmin1").val(all)
                        $('#subladmin1').multiSelect();
                        changeLocation()
                    }
                }
            });
        }
        var rlat, rlng;
        map = null;
        function myMap() {
            markers = [];
            function showPosition(position) {
                if (position == null) {
                    rlat =  {!! $city->latitude !!};
                    rlng = {!! $city->longitude!!};
                } else {
                    rlat = position.coords.latitude;
                    rlng = position.coords.longitude;
                }
                var url = window.location.protocol + "//" + window.location.host + "/contactfor/ownership";
                var mapProp = {
                    center: new google.maps.LatLng(rlat, rlng),
                    zoom: 4,
                };
                map = new google.maps.Map(document.getElementById("googleMap_from"), mapProp);
                map.addListener('click', function (event) {
                    rlat = event.latLng.lat()
                    rlng = event.latLng.lng()
                    marker = new google.maps.Marker({
                        position: event.latLng,
                        map: map,
                    });
                    markers.push(marker);
                    getCities(rlat, rlng, url);
                })
            }
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (success) {
                        showPosition(success)
                    },
                    function (failure) {
                        showPosition(null)
                    });
            } else {
                $('#location').html('Geolocation is not supported by this browser.');
            }
        }
        @endif
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIZUZRKJadLxndLkF4nocisyxkV6aC-nw&callback=myMap"></script>

    <script src="{{ url('assets/js/form-validation.js') }}"></script>
    <script>
        function changeLocation(){
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
            if($("#subladmin1").val() != null && $("#subladmin1").val().length > 0) {
                for (locationId of $("#subladmin1").val()) {
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng($("[value='" + locationId +"']").attr("lat"), $("[value='" + locationId +"']").attr("long")),
                        map: map,
                    });
                    markers.push(marker);
                }
            }
        }
        $(document).ready(function () {
            $("#subladmin1").change(function () {
                changeLocation()
                $('#subladmin1').multiSelect();
            });
        });
    </script>

@endsection