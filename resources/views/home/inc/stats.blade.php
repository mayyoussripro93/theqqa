@if (isset($countPosts) and isset($countUsers) and isset($countCities))
    @include('home.inc.spacer')
    @include('home.inc.spacer')
    {{--<!-- Start - Ads Section - ProCrew -->--}}
    {{--<div class="container-fluid ads-section">--}}
        {{--<div class="container">--}}
            {{--<div class="row">--}}
                {{--<div class="col-md-2 text-center ad" style="padding: 18px 0;">--}}
                    {{--<a href="#"><img src="images/ad-3.jpg" alt="Advertisement" width="180" height="150"></a>--}}
                {{--</div>--}}
                {{--<div class="col-md-8 text-center ad" style="padding: 48px 0;">--}}
                    {{--<a href="#"><img src="https://s0.2mdn.net/6232239/Cart-Abandoners-728x90.jpg" alt="Advertisement" width="728" height="90"></a>--}}
                {{--</div>--}}
                {{--<div class="col-md-2 text-center ad" style="padding: 18px 0;">--}}
                    {{--<a href="#"><img src="images/ad-3.jpg" alt="Advertisement" width="180" height="150"></a>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<!-- End - Ads Section - ProCrew -->--}}

    <div class="container-fluid stats-section">
        <div class="container">
            <div class="page-info page-info-lite rounded">
                <div class="text-center section-promo">
                    <div class="row">

                        @if (isset($countPosts))
                            <div class="col-sm-4 col-xs-6 col-xxs-12">
                                <div class="iconbox-wrap">
                                    <div class="iconbox">
                                        <div class="iconbox-wrap-icon">
                                            <i class="icon icon-docs"></i>
                                        </div>
                                        <div class="iconbox-wrap-content">
                                            <h5><span>{{ $countPosts }}</span></h5>
                                            <div class="iconbox-wrap-text">{{ t('Free ads') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (isset($countUsers))
                            <div class="col-sm-4 col-xs-6 col-xxs-12">
                                <div class="iconbox-wrap">
                                    <div class="iconbox">
                                        <div class="iconbox-wrap-icon">
                                            <i class="icon icon-group"></i>
                                        </div>
                                        <div class="iconbox-wrap-content">
                                            <h5><span>{{ $countUsers }}</span></h5>
                                            <div class="iconbox-wrap-text">{{ t('Trusted Sellers') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (isset($countCities))
                            <div class="col-sm-4 col-xs-6 col-xxs-12">
                                <div class="iconbox-wrap">
                                    <div class="iconbox">
                                        <div class="iconbox-wrap-icon">
                                            <i class="icon icon-map"></i>
                                        </div>
                                        <div class="iconbox-wrap-content">
                                            <h5><span>{{ $countCities . '+' }}</span></h5>
                                            <div class="iconbox-wrap-text">{{ t('Locations') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
