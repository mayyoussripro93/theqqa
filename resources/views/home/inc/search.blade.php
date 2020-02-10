<?php
// Init.
$sForm = [
    'enableFormAreaCustomization' => '0',
    'hideTitles' => '0',
    'title' => t('Sell and buy near you'),
    'subTitle' => t('Simple, fast and efficient'),
    'bigTitleColor' => '', // 'color: #FFF;',
    'subTitleColor' => '', // 'color: #FFF;',
    'backgroundColor' => '', // 'background-color: #444;',
    'backgroundImage' => '', // null,
    'height' => '', // '450px',
    'parallax' => '0',
    'hideForm' => '0',
    'formBorderColor' => '', // 'background-color: #333;',
    'formBorderSize' => '', // '5px',
    'formBtnBackgroundColor' => '', // 'background-color: #4682B4; border-color: #4682B4;',
    'formBtnTextColor' => '', // 'color: #FFF;',
];

// Get Search Form Options
if (isset($searchFormOptions)) {
    if (isset($searchFormOptions['enable_form_area_customization']) and !empty($searchFormOptions['enable_form_area_customization'])) {
        $sForm['enableFormAreaCustomization'] = $searchFormOptions['enable_form_area_customization'];
    }
    if (isset($searchFormOptions['hide_titles']) and !empty($searchFormOptions['hide_titles'])) {
        $sForm['hideTitles'] = $searchFormOptions['hide_titles'];
    }
    if (isset($searchFormOptions['title_' . config('app.locale')]) and !empty($searchFormOptions['title_' . config('app.locale')])) {
        $sForm['title'] = $searchFormOptions['title_' . config('app.locale')];
        $sForm['title'] = str_replace(['{app_name}', '{country}'], [config('app.name'), config('country.name')], $sForm['title']);
        if (str_contains($sForm['title'], '{count_ads}')) {
            try {
                $countPosts = \App\Models\Post::currentCountry()->unarchived()->count();
            } catch (\Exception $e) {
                $countPosts = 0;
            }
            $sForm['title'] = str_replace('{count_ads}', $countPosts, $sForm['title']);
        }
        if (str_contains($sForm['title'], '{count_users}')) {
            try {
                $countUsers = \App\Models\User::count();
            } catch (\Exception $e) {
                $countUsers = 0;
            }
            $sForm['title'] = str_replace('{count_users}', $countUsers, $sForm['title']);
        }
    }
    if (isset($searchFormOptions['sub_title_' . config('app.locale')]) and !empty($searchFormOptions['sub_title_' . config('app.locale')])) {
        $sForm['subTitle'] = $searchFormOptions['sub_title_' . config('app.locale')];
        $sForm['subTitle'] = str_replace(['{app_name}', '{country}'], [config('app.name'), config('country.name')], $sForm['subTitle']);
        if (str_contains($sForm['subTitle'], '{count_ads}')) {
            try {
                $countPosts = \App\Models\Post::currentCountry()->unarchived()->count();
            } catch (\Exception $e) {
                $countPosts = 0;
            }
            $sForm['subTitle'] = str_replace('{count_ads}', $countPosts, $sForm['subTitle']);
        }
        if (str_contains($sForm['subTitle'], '{count_users}')) {
            try {
                $countUsers = \App\Models\User::count();
            } catch (\Exception $e) {
                $countUsers = 0;
            }
            $sForm['subTitle'] = str_replace('{count_users}', $countUsers, $sForm['subTitle']);
        }
    }
    if (isset($searchFormOptions['parallax']) and !empty($searchFormOptions['parallax'])) {
        $sForm['parallax'] = $searchFormOptions['parallax'];
    }
    if (isset($searchFormOptions['hide_form']) and !empty($searchFormOptions['hide_form'])) {
        $sForm['hideForm'] = $searchFormOptions['hide_form'];
    }
}

// Country Map status (shown/hidden)
$showMap = false;
if (file_exists(config('larapen.core.maps.path') . config('country.icode') . '.svg')) {
    if (isset($citiesOptions) and isset($citiesOptions['show_map']) and $citiesOptions['show_map'] == '1') {
        $showMap = true;
    }
}
?>
@if (isset($sForm['enableFormAreaCustomization']) and $sForm['enableFormAreaCustomization'] == '1')

    @if (isset($firstSection) and !$firstSection)
        <div class="h-spacer"></div>
    @endif

    <?php $parallax = (isset($sForm['parallax']) and $sForm['parallax'] == '1') ? 'parallax' : ''; ?>
    <div class="wide-intro {{ $parallax }}">
        <div class="overlay">
            <!-- Start - Move Search Section From Below Video to Inside Overlay - ProCrew -->
            <div class="dtable hw100">
                <div class="dtable-cell hw100">
                    <div class="container text-center">

                        @if ($sForm['hideTitles'] != '1')
                            <h1 class="intro-title animated fadeInDown" style="visibility: hidden;"> {{ $sForm['title'] }} </h1>
                            <p class="sub animateme fittext3 animated fadeIn" style="visibility: hidden;">
                                {!! $sForm['subTitle'] !!}
                            </p>
                        @endif

                    <!-- Start - Invisible Search Section -->
                        @if ($sForm['hideForm'] != '1')
                            <div class="search-row animated fadeInUp" style="visibility: hidden;">
                                <?php $attr = ['countryCode' => config('country.icode')]; ?>
                                <form id="search" name="search"
                                      action="{{ lurl(trans('routes.v-search', $attr), $attr) }}"
                                      method="GET">
                                    <div class="row m-0">
                                        <div class="col-sm-5 col-xs-12 search-col relative">
                                            <i class="icon-docs icon-append"></i>
                                            <input type="text" name="q" class="form-control keyword has-icon"
                                                   placeholder="{{ t('What?') }}" value=""
                                                   title=""
                                                   data-placement="bottom"
                                                   data-toggle="tooltip" type="button"
                                                   data-original-title="{{ t('car_searce') }}">
                                        </div>

                                        <div class="col-sm-5 col-xs-12 search-col relative locationicon">
                                            <i class="icon-location-2 icon-append"></i>
                                            <input type="hidden" id="lSearch" name="l" value="">
                                            @if ($showMap)
                                                <input type="text" id="locSearch" name="location"
                                                       class="form-control locinput input-rel searchtag-input has-icon tooltipHere"
                                                       placeholder="{{ t('Where?') }}" value="" title=""
                                                       data-placement="bottom"
                                                       data-toggle="tooltip" type="button"
                                                       data-original-title="{{ t('Enter a city name OR a state name with the prefix ":prefix" like: :prefix', ['prefix' => t('area:')]) . t('State Name') }}">
                                            @else
                                                <input type="text" id="locSearch" name="location"
                                                       class="form-control locinput input-rel searchtag-input has-icon"
                                                       placeholder="{{ t('Where?') }}" value="">
                                            @endif
                                        </div>

                                        <div class="col-sm-2 col-xs-12 search-col">
                                            <button class="btn btn-primary btn-search btn-block">
                                                <i class="icon-search"></i> <strong>{{ t('Find') }}</strong>
                                            </button>
                                        </div>
                                        {!! csrf_field() !!}
                                    </div>
                                </form>
                            </div>
                    @endif
                    <!-- End - Invisible Search Section -->

                    </div>
                </div>
            </div>
            <!-- End - Move Search Section From Below Video to Inside Overlay - ProCrew -->

            <!-- Start - Services Section - ProCrew -->
            <div class="container-fluid services-section">
                <div class="row justify-content-md-center">
                    <h1><strong>{{ t('services_title') }}</strong></h1>
                    <div class="heading-line"></div>
                </div>
                <div class="row justify-content-md-center justify-content-sm-center services">
                    {{--<div class="col-md-12" id="service-alerts"></div>
                    <p class="col-md-12 text-center service-alert" id="alert-1">{{ t('ownership_desc') }}</p>
                    <p class="col-md-12 text-center service-alert" id="alert-2">{{ t('mogaz_desc') }}</p>
                    <p class="col-md-12 text-center service-alert" id="alert-3">{{ t('checking_desc') }}</p>
                    <p class="col-md-12 text-center service-alert" id="alert-4">{{ t('shipping_desc') }}</p>
                    <p class="col-md-12 text-center service-alert" id="alert-5">{{ t('maintenance_desc') }}</p>--}}

                    <div class="col-md-2 col-sm-2 text-center service" id="service-1">
                        <a class="" href="{{ lurl('contactfor/checking') }}">
                            <div class="service-icon service-3"></div>
                            <h3 class="service-heading">{{ t('checking_title') }}</h3>
                            <p class="service-desc">{{ t('checking_desc') }}</p>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-2 text-center service" id="service-2">
                        <a class="" href="{{ lurl('contactfor/maintenance') }}">
                            <div class="service-icon service-5"></div>
                            <h3 class="service-heading">{{ t('maintenance_title') }}</h3>
                            <p class="service-desc">{{ t('maintenance_desc') }}</p>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-2 text-center service" id="service-3">

                        <a class="" href="{{ lurl('contactfor/ownership') }}">
                            <div class="service-icon service-1"></div>
                            <h3 class="service-heading">{{ t('ownership_title') }}</h3>
                            <p class="service-desc">{{ t('ownership_desc') }}</p>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-2 text-center service" id="service-4">
                        <a class="" href="{{ lurl('contactfor/shipping') }}">
                            <div class="service-icon service-4"></div>
                            <h3 class="service-heading">{{ t('shipping_title') }}</h3>
                            <p class="service-desc">{{ t('shipping_desc') }}</p>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-2 text-center service" id="service-5">
                        <a class="" href="{{ lurl('contactfor/mogaz') }}">
                            <div class="service-icon service-2"></div>
                            <h3 class="service-heading">{{ t('mogaz_title') }}</h3>
                            <p class="service-desc">{{ t('mogaz_desc') }}</p>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-2 text-center service" id="service-5">
                        <a class="" href="{{ lurl('contactfor/estimation') }}">
                            <div class="service-icon service-6"></div>
                            <h3 class="service-heading">{{ t('estimation title') }}</h3>
                            <p class="service-desc">{{ t('estimation_desc') }}</p>
                        </a>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
            <!-- End - Services Section - ProCrew -->

        </div>
        <!-- Start - Video Header - ProCrew -->
        <video autoplay muted loop id="headerVideo">
            <source src="videos/headerVideo-2.mp4" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>
        <!-- End - Video Header - ProCrew -->
    </div>

@else

    @include('home.inc.spacer')
    <div class="container">
        <div class="intro">
            <div class="dtable hw100">
                <div class="dtable-cell hw100">
                    <div class="container text-center">

                        <div class="search-row fadeInUp">
                            <?php $attr = ['countryCode' => config('country.icode')]; ?>
                            <form id="search" name="search" action="{{ lurl(trans('routes.v-search', $attr), $attr) }}"
                                  method="GET">
                                <div class="row m-0">
                                    <div class="col-sm-5 col-xs-12 search-col relative">
                                        <i class="icon-docs icon-append"></i>
                                        <input type="text" name="q" class="form-control keyword has-icon"
                                               placeholder="{{ t('What?') }}" value=""
                                               title=""
                                               data-placement="bottom"
                                               data-toggle="tooltip" type="button"
                                               data-original-title="{{ t('car_searce')  }}">
                                    </div>

                                    <div class="col-sm-5 col-xs-12 search-col relative locationicon">
                                        <i class="icon-location-2 icon-append"></i>
                                        <input type="hidden" id="lSearch" name="l" value="">
                                        @if ($showMap)
                                            <input type="text" id="locSearch" name="location"
                                                   class="form-control locinput input-rel searchtag-input has-icon tooltipHere"
                                                   placeholder="{{ t('Where?') }}" value="" title=""
                                                   data-placement="bottom"
                                                   data-toggle="tooltip" type="button"
                                                   data-original-title="{{ t('Enter a city name OR a state name with the prefix ":prefix" like: :prefix', ['prefix' => t('area:')]) . t('State Name') }}">
                                        @else
                                            <input type="text" id="locSearch" name="location"
                                                   class="form-control locinput input-rel searchtag-input has-icon"
                                                   placeholder="{{ t('Where?') }}" value="">
                                        @endif
                                    </div>

                                    <div class="col-sm-2 col-xs-12 search-col">
                                        <button class="btn btn-primary btn-search btn-block">
                                            <i class="icon-search"></i> <strong>{{ t('Find') }}</strong>
                                        </button>
                                    </div>
                                    {!! csrf_field() !!}
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endif

<!-- Start - Move Search Section From Inside Overlay to Below Video - ProCrew -->
@if ($sForm['hideForm'] != '1')
    <div class="container animated fadeInUp new" style="margin-top: 30px;">
        <div class="col-xl-12 content-box layout-section">
            <div class="search-row animated fadeInUp">
                <?php $attr = ['countryCode' => config('country.icode')]; ?>
                <form id="search" name="search"
                      action="{{ lurl(trans('routes.v-search', $attr), $attr) }}"
                      method="GET">
                    <div class="row m-0">
                        <div class="col-sm-5 col-xs-12 search-col relative">
                            <i class="icon-docs icon-append"></i>
                            <input type="text" name="q" class="form-control keyword has-icon"
                                   placeholder="{{ t('What?') }}" value=""
                                   title=""
                                   data-placement="bottom"
                                   data-toggle="tooltip" type="button"
                                   data-original-title="{{ t('car_searce') }}">
                        </div>

                        <div class="col-sm-5 col-xs-12 search-col relative locationicon">
                            <i class="icon-location-2 icon-append"></i>
                            <input type="hidden" id="lSearch" name="l" value="">
                            @if ($showMap)
                                <input type="text" id="locSearch" name="location"
                                       class="form-control locinput input-rel searchtag-input has-icon tooltipHere"
                                       placeholder="{{ t('Where?') }}" value="" title=""
                                       data-placement="bottom"
                                       data-toggle="tooltip" type="button"
                                       data-original-title="{{ t('Enter a city name OR a state name with the prefix ":prefix" like: :prefix', ['prefix' => t('area:')]) . t('State Name') }}">
                            @else
                                <input type="text" id="locSearch" name="location"
                                       class="form-control locinput input-rel searchtag-input has-icon"
                                       placeholder="{{ t('Where?') }}" value="">
                            @endif
                        </div>

                        <div class="col-sm-2 col-xs-12 search-col">
                            <button class="btn btn-primary btn-search btn-block">
                                <i class="icon-search"></i> <strong>{{ t('Find') }}</strong>
                            </button>
                        </div>
                        {!! csrf_field() !!}
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<!-- End - Move Search Section From Inside Overlay to Below Video - ProCrew -->
