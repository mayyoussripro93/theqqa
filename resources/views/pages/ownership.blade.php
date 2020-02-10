{{--
 * Theqqa - Ads Web Application
 * Copyright (c) ProCrew. All Rights Reserved
 *
 * Website: http://www.procrew.pro
 *
 * Theqqa
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
                                <div class="col-md-3 text-center"><img src="{{ asset('images/owner-blue.png') }}"
                                                                       width="100"></div>
                                <div class="col-md-6 text-center">
                                    <h2><strong>{{ t('easy transfer service') }}</strong></h2>
                                    <h4>{{ t('ownership_desc') }}</h4>
                                    <h4><strong class="text-red">"{{ t('Both parties must attend the signing')}}
                                            "</strong></h4>
                                </div>
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
                              action="{{ lurl('contactfor/ownership') }}" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <fieldset>

                                <h5 class="list-title gray mt-0">
                                    <strong>{{ t('car register') }}</strong>
                                </h5>
                                <div class="form-group row required">
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

                                <h5 class="list-title gray mt-0 hidden_no" style="display: none">
                                    <strong>{{ t('theqqa url')  }}:</strong>
                                </h5>
                                <div class="row hidden_no" style="display: none">
                                    <div class="col-md-6 search-col relative locationicon">
                                        <input type="hidden" id="postlSearch" name="l" value="">
                                        <?php $carurlError = (isset($errors) and $errors->has('car_url')) ? ' is-invalid' : ''; ?>
                                        <input type="text" id="postSearch" name="location"
                                               class="form-control {{$carurlError}} "
                                               placeholder="{{ t('Find')  }}"
                                               value="{{!empty($_GET['p'])?$_GET['p']:''}}">
                                        <span class="text-gold">{{t('service')}} </span>

                                    </div>
                                </div>
                                <input type="hidden" name="id_code" value="{{$id_code}}">
                                <input type="hidden" id="country_name" name="country_name" value="{{config('country.name')}}">
                                <input type="hidden" id="country_code" name="country_code" value="{{config('country.code')}}">
                                <div id="item-list" class="ajData"></div>
                                <div class="row">
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
                                </div>
                                <input type="hidden" name="service_type" value="{{t( 'ownership_title')}}">
                                <h5 class="list-title gray mt-0">
                                    <strong>{{ t('user data') }}</strong>
                                </h5>
                                <div class="row">
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

                                <div class="row">
                                    <!-- Purchaser -->
                                    <div class="col-md-6">
                                        <h5 class="list-title gray mt-0">
                                            <strong>{{ t('purchaser_data') }}</strong>
                                        </h5>
                                        <div class="col-md-12 no-padding">
                                            <?php $purchaser_name = (isset($errors) and $errors->has('purchaser_name')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group required">
                                                <input id="purchaser_name" name="purchaser_name" type="text"
                                                       placeholder="{{ t('purchaser name') }}"
                                                       class="form-control{{ $purchaser_name }}"
                                                       value="{{ old('purchaser_name') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 no-padding">
                                            <?php $user_id = (isset($errors) and $errors->has('user_id')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group required">
                                                <input id="user_id" name="user_id" type="text"
                                                       placeholder="{{ t('purchaser ID')  }}"
                                                       class="form-control{{ $user_id }}"
                                                       value="{{ old('user_id') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 no-padding">
                                            <?php $purchaser_phone = (isset($errors) and $errors->has('purchaser_phone')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group required">
                                                <input id="purchaser_phone" name="purchaser_phone" type="text"
                                                       placeholder="{{ t('purchaser phone') }}"
                                                       class="form-control{{ $purchaser_phone }}"
                                                       value="{{ old('purchaser_phone') }}">
                                            </div>
                                        </div>
                                        <div class="row" style="margin-left: 0; margin-right: 0;">
                                            <div class="col-md-4 no-padding">
                                                <label style="font-size: 16px;">{{ t('purchaser id image') }}:</label>
                                            </div>
                                            <div class="col-md-8 no-padding">
                                                <?php $purchaser_id_image = (isset($errors) and $errors->has("purchaser_id_image")) ? ' is-invalid' : ''; ?>
                                                <div class="form-group required">
                                                    <input id="purchaser_id_image" name="purchaser_id_image" type="file"
                                                           placeholder="{{ t('purchaser id image') }}"
                                                           class="form-control{{ $purchaser_id_image }}"
                                                           value="{{ old('purchaser_id_image') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Seller -->
                                    <div class="col-md-6">
                                        <h5 class="list-title gray mt-0">
                                            <strong>{{ t('seller_data') }}</strong>
                                        </h5>
                                        <div class="col-md-12 hidden_yes no-padding">
                                            <?php $seller_name = (isset($errors) and $errors->has('seller_name')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group required">
                                                <input id="seller_name" name="seller_name" type="text"
                                                       placeholder="{{ t('seller name') }}"
                                                       class="form-control{{ $seller_name }}"
                                                       value="{{ old('seller_name') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 no-padding">
                                            <?php $owner_id = (isset($errors) and $errors->has('owner_id')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group required">
                                                <input id="owner_id" name="owner_id" type="text"
                                                       placeholder="{{ t('seller ID') }}"
                                                       class="form-control{{ $owner_id }}"
                                                       value="{{ old('owner_id') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 no-padding">
                                            <?php $seller_phone = (isset($errors) and $errors->has('seller_phone')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group required">
                                                <input id="seller_phone" name="seller_phone" type="text"
                                                       placeholder="{{ t('seller phone') }}"
                                                       class="form-control{{ $seller_phone }}"
                                                       value="{{ old('seller_phone') }}">
                                            </div>
                                        </div>
                                        <div class="row" style="margin-left: 0; margin-right: 0;">
                                            <div class="col-md-4 hidden_yes no-padding">
                                                <label style="font-size: 16px;">{{ t('seller id image') }}:</label>
                                            </div>
                                            <div class="col-md-8 hidden_yes no-padding">
                                                <?php $seller_id_image = (isset($errors) and $errors->has("seller_id_image")) ? ' is-invalid' : ''; ?>
                                                <div class="form-group required">
                                                    <input id="seller_id_image" name="seller_id_image" type="file"
                                                           placeholder="{{ t('seller id image') }}"
                                                           class="form-control{{ $seller_id_image }}"
                                                           value="{{ old('seller_id_image') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <h5 class="list-title gray mt-0">
                                    <strong>{{ t('car data') }}</strong>
                                </h5>
                                <div class="row">
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

                                    <?php $priceError = (isset($errors) and $errors->has('price')) ? ' is-invalid' : ''; ?>
                                    <div class="col-md-2">
                                        <label class="col-form-label" for="price"
                                               style="font-size: 16px;">{{ t('car price') }}</label>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input id="price" name="price" class="form-control{{ $priceError }}"
                                                   placeholder="{{ t('e.i. 15000') }}" type="text"
                                                   value="{{ old('price') }}">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!! config('currency')!!}</span>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label style="font-size: 16px;">{{ t('driving_license') }}:</label>
                                    </div>
                                    <div class="col-md-4">
                                        <?php $driving_license = (isset($errors) and $errors->has("driving_license")) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="driving_license" name="driving_license" type="file"
                                                   placeholder="{{ t('driving_license') }}"
                                                   class="form-control{{ $driving_license }}"
                                                   value="{{ old('driving_license') }}">
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
                                            <span class="text-gold">{{ t('car_Pictures_span')}}</span>
                                        </div>

                                    </div>


                                    <div class="col-md-12 hidden_yes">
                                        <!-- Map -->
                                        <div class="ads-googlemaps hidden_yes" id="googleMap_from"
                                             style="width:100%;height:250px;">
                                            <div class="card sidebar-card">
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

                                    <!-- Fields -->
                                    <?php $exhibitionschooseError = (isset($errors) and $errors->has('exhibitions_place')) ? ' is-invalid' : ''; ?>
                                    <div class="col-md-2 hidden_yes" style="margin-top: 1rem; margin-bottom: 1rem;">
                                        <label style="font-size: 16px;">{{ t('nearest_place') }}:</label>
                                    </div>
                                    <div class="col-md-4 hidden_yes" style="margin-top: 1rem; margin-bottom: 1rem;">
                                        <select name="exhibitions_place" id="exhibitions_place"
                                                class="form-control {{ $exhibitionschooseError }}">
                                            @foreach ($subladmin1s as $subladmin1)
                                                <option value="{{ $subladmin1->id }}"> {{ $subladmin1->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <?php $exhibitionsidError = (isset($errors) and $errors->has('exhibitions_id')) ? ' is-invalid' : ''; ?>
                                    <div class="col-md-2 hidden_yes" style="margin-top: 1rem; margin-bottom: 1rem;">
                                        <label style="font-size: 16px;">{{ t('available_exhibitions') }}:</label>
                                    </div>
                                    <div class="col-md-4 hidden_yes" style="margin-top: 1rem; margin-bottom: 1rem;">
                                        <select name="exhibitions_id" id="exhibitions_id"
                                                class="form-control {{ $exhibitionsidError }}">
                                            @foreach ($exhibitionsusers as $exhibitionsuser)
                                                <option value="{{ $exhibitionsuser->id }}"> {{ $exhibitionsuser->name }} </option>

                                            @endforeach
                                        </select>
                                    </div>


                                    <div id="log"></div>
                                    <div id="car_ch"></div>

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
    <!-- bxSlider Javascript file -->
    <script src="{{ url('assets/plugins/bxslider/jquery.bxslider.min.js') }}"></script>

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
                    var oldcity = $('#exhibitions_place option').filter(function (i, e) {
                        return $(e).val() == res.city.id
                    })

                    var city = $('#exhibitions_place option').filter(function (i, e) {
                        return $(e).val() == res.city.id
                    })
                    city.attr("selected", "selected");
                    city.siblings().removeAttr('selected');
                    $('#exhibitions_id').empty()


                    $.each(res.exhibitionsusers, function (key, value) {
                        $('#exhibitions_id').append("<option value=" + value.id + ">" + value.name + "</option>")
                    });

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
                getCities(rlat, rlng, url)
                var mapProp = {
                    center: new google.maps.LatLng(rlat, rlng),
                    zoom: 6,
                };
                map = new google.maps.Map(document.getElementById("googleMap_from"), mapProp);
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(rlat, rlng),
                    map: map,
                });
                markers.push(marker);
                geocoder = new google.maps.Geocoder();
                // This event listener will call addMarker() when the map is clicked.
                map.addListener('click', function (event) {

                    for (var i = 0; i < markers.length; i++) {
                        markers[i].setMap(null);
                    }
                    markers = [];

                    rlat = event.latLng.lat()
                    rlng = event.latLng.lng()
                    marker = new google.maps.Marker({
                        position: event.latLng,
                        map: map,
                    });
                    markers.push(marker);
                    getCities(rlat, rlng, url);
                });
                // This event listener will call addMarker() when the map is clicked.


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
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>--}}
    <script src="{{ url('assets/js/form-validation.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#exhibitions_place").change(function () {
                if ($(this).val().length > 0) {

                    var url = window.location.protocol + "//" + window.location.host + "/contactfor/ownership";
                    $.ajax({
                        type: "GET",
                        data: {
                            aId: $(this).val(),
                        },
                        url: url,
                        cache: false,
                        success: function (res) {
                            for (var i = 0; i < markers.length; i++) {
                                markers[i].setMap(null);
                            }
                            markers = [];
                            map.setCenter(new google.maps.LatLng(res.city.latitude, res.city.longitude));
                            marker = new google.maps.Marker({
                                position: new google.maps.LatLng(res.city.latitude, res.city.longitude),
                                map: map,
                            });
                            markers.push(marker);
                            $('#exhibitions_id').empty()
                            $.each(res.exhibitionsusers, function (key, value) {
                                $('#exhibitions_id').append("<option value=" + value.id + ">" + value.name + "</option>")
                            });
                        }
                    });
                }

            });

            $("#car_Pictures").on("change", function() {
                if ($("#car_Pictures")[0].files.length > 2 || $("#car_Pictures")[0].files.length < 2) {
                    Swal.fire({
                        title: '',
                        type: 'error',
                        html:'{!! t("car_Pictures_span") !!}',
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
        $(document).ready(function () {
            /* Submit Form */
            $("#signupBtn_no1").click(function () {
                $("#signupForm_no").submit();
                return false;
            });
        });
    </script>

    <script>
        var App = jQuery('#clientId');
        var select = this.value;
        App.change(function () {
                if ($(this).val() === 'no') {
                    $('#car_ch').append('<input type="hidden" name="for_ownership" id="for_check" value="no">');
                    $('.hidden_no').hide();
                    $('.hidden_yes').show();
                    $('#search').hide();
                } else if ($(this).val() === 'yes') {
                    $('#car_ch').append('<input type="hidden" name="for_ownership" id="for_check" value="yes">');
                    $('.hidden_no').show();
                    $('.hidden_yes').hide();
                    $('#search').show();
                }
            }
        );
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
                        url: '{{ url('contactfor/ownership') }}',
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
                $('.client-form').hide();
                $('#signupForm_no').show();
                $('#car_ch').append('<input type="hidden" name="for_ownership" id="for_check" value="no">');
                $('.hidden_no').hide();
                $('.hidden_yes').show();
                $('#search').hide();
            } else if ($('#clientId').val() === 'yes') {
                $('.client-form').hide();
                $('#signupForm_no').show();
                $('#car_ch').append('<input type="hidden" name="for_ownership" id="for_check" value="yes">');
                $('.hidden_no').show();
                $('.hidden_yes').hide();
                $('#search').show();
            }

        });

    </script>

@endsection
