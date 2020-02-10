{{--
 * Theqqa - #1 Cars Services Platform in KSA
 * Copyright (c) ProCrew. All Rights Reserved
 *
 * Website: http://www.procrew.pro
 *
 * Theqqa
 */
--}}
<?php
$fullUrl = url(\Illuminate\Support\Facades\Request::getRequestUri());
$plugins = array_keys((array)config('plugins'));
?>
        <!DOCTYPE html>
<html lang="{{ config('app.locale') }}"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('common.meta-robots')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-title" content="{{ config('settings.app.app_name') }}">
    <link rel="apple-touch-icon-precomposed" sizes="144x144"
          href="{{ \Storage::url('app/default/ico/apple-touch-icon-144-precomposed.png') . getPictureVersion() }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114"
          href="{{ \Storage::url('app/default/ico/apple-touch-icon-114-precomposed.png') . getPictureVersion() }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72"
          href="{{ \Storage::url('app/default/ico/apple-touch-icon-72-precomposed.png') . getPictureVersion() }}">
    <link rel="apple-touch-icon-precomposed"
          href="{{ \Storage::url('app/default/ico/apple-touch-icon-57-precomposed.png') . getPictureVersion() }}">
    <link rel="shortcut icon" href="{{ \Storage::url(config('settings.app.favicon')) . getPictureVersion() }}">

    <title>{!! MetaTag::get('title') !!}</title>
    {!! MetaTag::tag('description') !!}{!! MetaTag::tag('keywords') !!}
    <link rel="canonical" href="{{ $fullUrl }}"/>
    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        @if (strtolower($localeCode) != strtolower(config('app.locale')))
            <link rel="alternate" href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}"
                  hreflang="{{ strtolower($localeCode) }}"/>
        @endif
    @endforeach
    @if (count($dnsPrefetch) > 0)
        @foreach($dnsPrefetch as $dns)
            <link rel="dns-prefetch" href="//{{ $dns }}">
        @endforeach
    @endif
    @if (isset($post))
        @if (isVerifiedPost($post))
            @if (config('services.facebook.client_id'))
                <meta property="fb:app_id" content="{{ config('services.facebook.client_id') }}"/>
            @endif
            {!! $og->renderTags() !!}
            {!! MetaTag::twitterCard() !!}
        @endif
    @else
        @if (config('services.facebook.client_id'))
            <meta property="fb:app_id" content="{{ config('services.facebook.client_id') }}"/>
        @endif
        {!! $og->renderTags() !!}
        {!! MetaTag::twitterCard() !!}
    @endif
    @include('feed::links')
    @if (config('settings.seo.google_site_verification'))
        <meta name="google-site-verification" content="{{ config('settings.seo.google_site_verification') }}"/>
    @endif
    @if (config('settings.seo.msvalidate'))
        <meta name="msvalidate.01" content="{{ config('settings.seo.msvalidate') }}"/>
    @endif
    @if (config('settings.seo.alexa_verify_id'))
        <meta name="alexaVerifyID" content="{{ config('settings.seo.alexa_verify_id') }}"/>
    @endif

    @yield('before_styles')
    <link href="{{ url('css/multi-select.css') }}" rel="stylesheet">
    @if (config('lang.direction') == 'rtl')
        <link href="https://fonts.googleapis.com/css?family=Cairo|Changa" rel="stylesheet">
        <link href="{{ url(mix('css/app.rtl.css')) }}" rel="stylesheet">
    @else
        <link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">
    @endif
    @if (config('plugins.detectadsblocker.installed'))
        <link href="{{ url('assets/detectadsblocker/css/style.css') . getPictureVersion() }}" rel="stylesheet">
    @endif

    @include('layouts.inc.tools.style')

    <link href="{{ url('css/custom.css') . getPictureVersion() }}" rel="stylesheet">

    @yield('after_styles')
	
	@if (isset($plugins) and !empty($plugins))
		@foreach($plugins as $plugin)
			@yield($plugin . '_styles')
		@endforeach
	@endif
    
    @if (config('settings.style.custom_css'))
		{!! printCss(config('settings.style.custom_css')) . "\n" !!}
    @endif
	
	@if (config('settings.other.js_code'))
		{!! printJs(config('settings.other.js_code')) . "\n" !!}
	@endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script>
        paceOptions = {
            elements: true
        };
    </script>
    <script src="{{ url('assets/js/pace.min.js') }}"></script>
    <script src="{{ url('assets/plugins/modernizr/modernizr-custom.js') }}"></script>

    <style>
        #loading {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999999999;
            overflow: hidden;
            width: 100%;
            height: 100%;
            background: #fff;
            text-align: center;
            padding-top: 270px;
        }

        .spinner {
            width: 200px;
            height: 100px;
            position: fixed;
            top: 42%;
            left: 50.5%;
            margin-left: -98px;
            margin-top: -50px;
        }
    </style>
</head>

@if( Request::segment(2) == 'سيارات-مستعملة' || Request::segment(3) == 'used-cars')
    <body class="{{ config('app.skin') }} cat-bg">
    @else
        <body class="{{ config('app.skin') }}">
        @endif

        {{--@if( Request::segment(3) == 'paymentservice' && Request::segment(1) == 'post')
            <div id="loading">
                <div class="spinner">
                    <img src="/images/paypal-loader.gif" width="110"
                         height="110">
                </div>
            </div>
        @endif--}}
        <div id="wrapper">

            @section('header')
                @include('layouts.inc.header')
            @show

            @section('search')
            @show
            @section('wizard')
            @show
            @if (isset($siteCountryInfo))
                <div class="h-spacer"></div>
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12 alert-container">
                            <div class="alert alert-warning">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                {!! $siteCountryInfo !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')

            @section('info')
            @show

            @section('footer')
                @include('layouts.inc.footer')
            @show

        </div>

        @section('modal_location')
        @show
        @section('modal_abuse')
        @show
        @section('modal_message')
        @show

        @includeWhen(!auth()->check(), 'layouts.inc.modal.login')
        @include('layouts.inc.modal.change-country')
        @include('cookieConsent::index')

        @if (config('plugins.detectadsblocker.installed'))
            @if (view()->exists('detectadsblocker::modal'))
                @include('detectadsblocker::modal')
            @endif
        @endif

        <script>
                    {{-- Init. Root Vars --}}
            var siteUrl = '<?php echo url((!currentLocaleShouldBeHiddenInUrl() ? config('app.locale') : '') . '/'); ?>';
            var languageCode = '<?php echo config('app.locale'); ?>';
            var countryCode = '<?php echo config('country.code', 0); ?>';
            var timerNewMessagesChecking = <?php echo (int)config('settings.other.timer_new_messages_checking', 0); ?>;

                    {{-- Init. Translation Vars --}}
            var langLayout = {
                    'hideMaxListItems': {
                        'moreText': "{{ t('View More') }}",
                        'lessText': "{{ t('View Less') }}"
                    },
                    'select2': {
                        errorLoading: function () {
                            return "{!! t('The results could not be loaded.') !!}"
                        },
                        inputTooLong: function (e) {
                            var t = e.input.length - e.maximum, n = {!! t('Please delete #t character') !!};
                            return t != 1 && (n += 's'), n
                        },
                        inputTooShort: function (e) {
                            var t = e.minimum - e.input.length, n = {!! t('Please enter #t or more characters') !!};
                            return n
                        },
                        loadingMore: function () {
                            return "{!! t('Loading more results…') !!}"
                        },
                        maximumSelected: function (e) {
                            var t = {!! t('You can only select #max item') !!};
                            return e.maximum != 1 && (t += 's'), t
                        },
                        noResults: function () {
                            return "{!! t('No results found') !!}"
                        },
                        searching: function () {
                            return "{!! t('Searching…') !!}"
                        }
                    }
                };
        </script>

        @yield('before_scripts')

        <script src="{{ url(mix('js/app.js')) }}"></script>
        @if (file_exists(public_path() . '/assets/plugins/select2/js/i18n/'.config('app.locale').'.js'))
            <script src="{{ url('assets/plugins/select2/js/i18n/'.config('app.locale').'.js') }}"></script>
        @endif
        @if (config('plugins.detectadsblocker.installed'))
            <script src="{{ url('assets/detectadsblocker/js/script.js') . getPictureVersion() }}"></script>
        @endif
        <script>
            $(document).ready(function () {
                {{-- Select Boxes --}}
                $('.selecter').select2({
                    language: langLayout.select2,
                    dropdownAutoWidth: 'true',
                    minimumResultsForSearch: Infinity,
                    width: '100%'
                });

                {{-- Searchable Select Boxes --}}
                $('.sselecter').select2({
                    language: langLayout.select2,
                    dropdownAutoWidth: 'true',
                    width: '100%'
                });

                {{-- Social Share --}}
                $('.share').ShareLink({
                    title: '{{ addslashes(MetaTag::get('title')) }}',
                    text: '{!! addslashes(MetaTag::get('title')) !!}',
                    url: '{!! $fullUrl !!}',
                    width: 640,
                    height: 480
                });

                {{-- Modal Login --}}
                @if (isset($errors) and $errors->any())
                @if ($errors->any() and old('quickLoginForm')=='1')
                $('#quickLogin').modal();
                @endif
                @endif
            });
        </script>

        <!-- Start - Loading Script - ProCrew -->
        <script type="text/javascript">
            $(window).load(function () {
                $("#loading").fadeOut("slow");
            });
        </script>
        <!-- End - Loading Script - ProCrew -->

        <!-- Start - Payment Buttons Hover - ProCrew -->
        <script type="text/javascript">
            $(document).ready(function () {
                $('.pay-btn').mouseover(function () {
                    $(this).find('img').attr('src', function (i, src) {
                        return src.replace('visa.png', 'visa-hvr.png')
                    })
                })
                $('.pay-btn').mouseout(function () {
                    $(this).find('img').attr('src', function (i, src) {
                        return src.replace('visa-hvr.png', 'visa.png')
                    })
                })
                $('.pay-btn').mouseover(function () {
                    $(this).find('img').attr('src', function (i, src) {
                        return src.replace('paypal.png', 'paypal-hvr.png')
                    })
                })
                $('.pay-btn').mouseout(function () {
                    $(this).find('img').attr('src', function (i, src) {
                        return src.replace('paypal-hvr.png', 'paypal.png')
                    })
                })
            });
        </script>
        <!-- End - Payment Buttons Hover - ProCrew -->

        <!-- Start - Enlarge Image On Click - ProCrew -->
        <script type="text/javascript">
            $(document).ready(function () {
                $('.car-img-ow').click(function () {
                    x = $(this).attr('src');
                    $('#cpModal img').attr("src",x);
                });
            });
        </script>
        <!-- End - Enlarge Image On Click - ProCrew -->



        {{--
        <!-- Start - Service Alert Script - ProCrew -->
        <script type="text/javascript">
            $(document).ready(function () {
                if ($(window).width() <= 1300) {
                    $("#service-1").hover(function () {
                        $("#service-alerts").css("display", "none");
                        $("#alert-1").css("display", "block");
                    }, function () {
                        $("#alert-1").css("display", "none");
                        $("#service-alerts").css("display", "block");
                    });
                    $("#service-2").hover(function () {
                        $("#service-alerts").css("display", "none");
                        $("#alert-2").css("display", "block");
                    }, function () {
                        $("#alert-2").css("display", "none");
                        $("#service-alerts").css("display", "block");
                    });
                    $("#service-3").hover(function () {
                        $("#service-alerts").css("display", "none");
                        $("#alert-3").css("display", "block");
                    }, function () {
                        $("#alert-3").css("display", "none");
                        $("#service-alerts").css("display", "block");
                    });
                    $("#service-4").hover(function () {
                        $("#service-alerts").css("display", "none");
                        $("#alert-4").css("display", "block");
                    }, function () {
                        $("#alert-4").css("display", "none");
                        $("#service-alerts").css("display", "block");
                    });
                    $("#service-5").hover(function () {
                        $("#service-alerts").css("display", "none");
                        $("#alert-5").css("display", "block");
                    }, function () {
                        $("#alert-5").css("display", "none");
                        $("#service-alerts").css("display", "block");
                    });
                }
            });
        </script>
        <!-- End - Service Alert Script - ProCrew -->
        --}}

        @yield('after_scripts')

        @if (isset($plugins) and !empty($plugins))
            @foreach($plugins as $plugin)
                @yield($plugin . '_scripts')
            @endforeach
        @endif

        @if (config('settings.footer.tracking_code'))
            {!! printJs(config('settings.footer.tracking_code')) . "\n" !!}
        @endif
        </body>
</html>