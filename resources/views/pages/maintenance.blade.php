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

@endsection
<style>.intro-inner {
        display: none !important;
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
                                <div class="col-md-3 text-center"><img src="{{ asset('images/maintenance-blue.png') }}"
                                                                       width="100">
                                </div>
                                <div class="col-md-6 text-center"><h2>
                                        <strong>{{ t('maintenance_title') }}</strong></h2>
                                    <h4>{{ t('maintenance_desc') }}</h4></div>
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
                              action="{{ lurl('contactfor/maintenance') }}">
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

                                    @if (config('settings.single.show_post_on_googlemap'))
                                        <div class="col-md-12 hidden_yes">
                                            <h5 class="list-title gray mt-0">
                                                <strong>{{ t('maintenance_location')  }}:</strong>
                                            </h5>
                                        </div>

                                        <div class="col-md-12 hidden_no" style="display: none">
                                            <h5 class="list-title gray mt-0">
                                                <strong>{{ t('maintenance_choose')  }}:</strong>
                                            </h5>
                                        </div>

                                        <div class="col-md-12 hidden_yes" style="margin-bottom: 2rem;">
                                            <div class="ads-googlemaps" id="googleMap" style="width:100%;height:250px;">
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
                                    @endif
                                </div>
                                <input type="hidden" name="id_code" value="{{$id_code}}">
                                <div class="row" style="margin-bottom: 1rem;">
                                    <?php $car_place = (isset($errors) and $errors->has('car_place')) ? ' is-invalid' : ''; ?>
                                    <div class="col-md-2 hidden_yes">
                                        <label style="font-size: 16px;">{{ t('car_place') }}:</label>
                                    </div>
                                    <div class="col-md-4 hidden_yes">
                                        <select name="car_place" id="car_place" class="form-control {{ $car_place }}">
                                            @foreach ($subladmin1s as $subladmin1)
                                                <option value="{{ $subladmin1->id }}"> {{ $subladmin1->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <?php $maintenanceIdError = (isset($errors) and $errors->has('maintenance_id')) ? ' is-invalid' : ''; ?>
                                    <div class="col-md-2 hidden_yes">
                                        <label style="font-size: 16px;">{{ t('maintenance_center') }}:</label>
                                    </div>
                                    <div class="col-md-4 hidden_yes">
                                        <select name="maintenance_id" id="maintenance_id"
                                                class="form-control {{ $maintenanceIdError }}">
                                            @foreach ($maintenance_users as $maintenance_user)
                                                <option value="{{ $maintenance_user->id }}"> {{ $maintenance_user->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <?php $maintenanceIdyesError = (isset($errors) and $errors->has('maintenance_id_yes')) ? ' is-invalid' : '';?>

                                    <div class="col-md-4 hidden_no maintenance_yes_places " style="display: none">
                                        <select name="maintenance_id_yes" id="maintenance_id_yes"
                                                class="form-control {{ $maintenanceIdyesError }}">
                                            @foreach ($maintenance_users_yes as $maintenance_user)
                                                <option value="{{ $maintenance_user->id }}"> {{ $maintenance_user->name }} </option>
                                            @endforeach
                                        </select>
                                        <span class="text-gold hidden_no">{{ t('maintenance_place') }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php $addressError = (isset($errors) and $errors->has('address')) ? ' is-invalid' : ''; ?>
                                    <div class="col-md-2 hidden_yes">
                                        <label style="font-size: 16px;">{{ t('address') }}:</label>
                                    </div>
                                    <div class="col-md-4 hidden_yes">
                                        <input id="address" name="address" type="text"
                                               placeholder="{{ t('address') }}"
                                               class="form-control{{ $addressError}}" value="">
                                    </div>
                                </div>

                                <h5 class="list-title gray mt-0 hidden_yes" id="no_car_title">
                                    <strong>{{ t('car data') }}</strong>
                                </h5>
                                <div class="row hidden_yes" id="no_car">

                                    <div class="col-md-6">
                                        <?php $owner_id = (isset($errors) and $errors->has('owner_id')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="owner_id" name="owner_id" type="text"
                                                   placeholder="{{ t('Owner ID') }}"
                                                   class="form-control{{ $owner_id }}" value="{{ old('owner_id') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <?php $plate_number = (isset($errors) and $errors->has('plate_number')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="plate_number" name="plate_number" type="text"
                                                   placeholder="{{ t('plate number') }}"
                                                   class="form-control{{ $plate_number }}"
                                                   value="{{ old('plate_number') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <?php $serial_number = (isset($errors) and $errors->has('serial_number')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="serial_number" name="serial_number" type="text"
                                                   placeholder="{{ t('serial number') }}"
                                                   class="form-control{{ $serial_number }}"
                                                   value="{{ old('serial_number') }}">
                                        </div>
                                    </div>
                                </div>
                                <h5 class="list-title gray mt-0">
                                    <strong>{{ t('user data') }}</strong>
                                </h5>
                                <div class="row">
                                    <input type="hidden" name="service_type" value="{{t( 'maintenance_title')}}">
                                    <div class="col-md-6">
                                        <?php $firstNameError = (isset($errors) and $errors->has('first_name')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <input id="first_name" name="first_name" type="text"
                                                   placeholder="{{ t('First Name') }}"
                                                   class="form-control{{ $firstNameError }}" value="{{ !empty(auth()->user()->name)?auth()->user()->name:
                                                   old('first_name') }}">
                                        </div>
                                    </div>
                                    <div id="car_ch"></div>

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
                                    <p id="demo"></p>
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
    @if (config('services.googlemaps.key'))
        {{--<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.googlemaps.key') }}"--}}
        {{--type="text/javascript"></script>--}}
    @endif

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

        function geocodeAddress(geocoder, resultsMap, latlng) {

            // var address = document.getElementById('address').value;
            geocoder.geocode({'latLng': latlng}, function (results, status) {
                if (status === 'OK') {
                    // resultsMap.setCenter(results[0].geometry.location);
                    document.getElementById("address").value = results[0].formatted_address;


                    //  marker = new google.maps.Marker({
                    //     map: resultsMap,
                    //     position: results[0].geometry.location
                    // });
                    // markers.push(marker);
                    var length = results[0].address_components.length - 1;
                    var city = results[0].address_components[length - 1].short_name;
                    // var country = results[0].address_components[length].long_name;

//                    geocoder.geocode({'address': city}, function(results, status) {
//                        // console.log(results)
//                        if (status === 'OK') {
//                            // console.log(results[0].formatted_address)
// //                            console.log(results[0])
// //                            var center = resultsMap.getCenter()
//                            // console.log(center.lat())
//                            // console.log(center.lng())
//                            // resultsMap.setCenter(results[0].geometry.location);
//                        } else {
//                            // alert('Geocode was not successful for the following reason: ' + status);
//                        }
//                    });
                } else {
                    // alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }

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
                    var oldcity = $('#car_place option').filter(function (i, e) {
                        return $(e).val() == res.city.id
                    })

                    var city = $('#car_place option').filter(function (i, e) {
                        return $(e).val() == res.city.id
                    })
                    city.attr("selected", "selected");
                    city.siblings().removeAttr('selected');

                    $('#maintenance_id').empty()


                    $.each(res.maintenance_users, function (key, value) {

                        $('#maintenance_id').append("<option value=" + value.id + ">" + value.name + "</option>")
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
                var url = window.location.protocol + "//" + window.location.host + "/contactfor/maintenance";
                getCities(rlat, rlng, url)
                var mapProp = {
                    center: new google.maps.LatLng(rlat, rlng),
                    zoom: 6,
                };
                map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

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
                    geocodeAddress(geocoder, map, event.latLng);
                    marker = new google.maps.Marker({
                        position: event.latLng,
                        map: map,
                    });

                    markers.push(marker);
                    getCities(rlat, rlng, url);
//                $.ajax({
//                    type: "GET",
//                    data: {
//                        lat: rlat,
//                        lng: rlng,
//                    },
//                    url: url,
//                    cache: false,
//                    success: function(res) {
//                        var oldcity = $('#car_place option').filter(function(i, e) { return $(e).val() == res.city.id})
//
//                        var city = $('#car_place option').filter(function(i, e) { return $(e).val() == res.city.id})
//                            city.attr("selected","selected");
//                            city.siblings().removeAttr('selected');
//
//                        $('#maintenance_id').empty()
//
//
//                        $.each(res.maintenance_users, function(key, value){
//                            $('#maintenance_id').append("<option value="+value.id+">"+value.name+"</option>")
//                        });
//                    }
//                });

                });


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
        $(document).ready(function () {
            $("#car_place").change(function () {
                if ($(this).val().length > 0) {
                    var url = window.location.protocol + "//" + window.location.host + "/contactfor/maintenance";
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
                            geocodeAddress(geocoder, map, new google.maps.LatLng(res.city.latitude, res.city.longitude));
                            $('#maintenance_id').empty()


                            $.each(res.maintenance_users, function (key, value) {
                                $('#maintenance_id').append("<option value=" + value.id + ">" + value.name + "</option>")
                            });
                        }
                    });
                }

            });
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
                $('#car_ch').append('<input type="hidden" name="for_mainten" id="for_check" value="no">');
                $('.hidden_no').hide();
                $('.hidden_yes').show();
                $('#search').hide();
            } else if ($(this).val() === 'yes') {
                $('#car_ch').append('<input type="hidden" name="for_mainten" id="for_check" value="yes">');
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
                        url: '{{ url('contactfor/maintenance') }}',
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
                val3 = $('#postlSearch').val();
                $.ajax({
                    url: '{{ url('contactfor/maintenance/yes') }}',
                    method: "GET",
                    data: {id: val3},
                    success: function (data) {
                        data = JSON.parse(data);
                        url = "{{ Request::root()}}/" + data.uri;
                        console.log(data.data)
                        $('#car_url').val(url);
                        $('.maintenance_yes_places').empty();
                        $('.maintenance_yes_places').append(data.data);


                    }
                });
            })


            if ($('#clientId').val() === 'no') {
                $('#car_ch').append('<input type="hidden" name="for_mainten" id="for_check" value="no">');
                $('.hidden_no').hide();
                $('.hidden_yes').show();
                $('#search').hide();
            } else if ($('#clientId').val() === 'yes') {
                $('#car_ch').append('<input type="hidden" name="for_mainten" id="for_check" value="yes">');
                $('.hidden_no').show();
                $('.hidden_yes').hide();
                $('#search').show();
            }
        });


    </script>

@endsection








