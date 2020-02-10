{{--
 * Theqqa - #1 Cars Services Platform in KSA
 * Copyright (c) ProCrew. All Rights Reserved
 *
 * Website: http://www.procrew.pro
 *
 * Theqqa
--}}
@extends('layouts.master')

@section('header')
	@include('layouts.inc.header')
@endsection

@section('search')
	@parent
@endsection

@section('content')
	@include('common.spacer')
	<div class="main-container inner-page" style="min-height: 527px;">
		<div class="container">
			<div class="section-content">
				<div class="row">

					<h1 class="text-center title-1" style="text-transform: none;">
						<strong>{{ t('Our websites abroad') }}</strong>
					</h1>
					<hr class="center-block small mt-0">

					@if (isset($countryCols))
						<div class="col-md-12 page-content">
							<div class="inner-box relative">
								
								<h3 class="title-2"><i class="icon-location-2"></i> {{ t('Select a country') }}</h3>
								
								<div class="row m-0">
									@foreach ($countryCols as $key => $col)
										<ul class="cat-list col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6 {{ (count($countryCols) == $key+1) ? 'cat-list-border' : '' }}">
											@foreach ($col as $k => $country)
												<?php
												$countryLang = App\Helpers\Localization\Country::getLangFromCountry($country->get('languages'));
												?>
												<li>
													<img src="{{ url('images/blank.gif') . getPictureVersion() }}" class="flag flag-{{ ($country->get('icode')=='uk') ? 'gb' : $country->get('icode') }}" style="margin: 0 5px 4px;">
													<a href="{{ localUrl($country, '', true) }}"
													   title="{!! $country->get('name') !!}"
													   class="tooltipHere"
													   data-toggle="tooltip"
													   data-original-title="{!! $country->get('name') !!}"
													>{{ str_limit($country->get('name'), 30) }}</a>
												</li>
											@endforeach
										</ul>
									@endforeach
								</div>
								
							</div>
						</div>
					@endif

				</div>

				@include('layouts.inc.social.horizontal')

			</div>
		</div>
	</div>
@endsection

@section('info')
@endsection

@section('after_scripts')
@endsection
