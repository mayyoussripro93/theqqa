{{--
 * Theqqa - #1 Cars Services Platform in KSA
 * Copyright (c) ProCrew. All Rights Reserved
 *
 * Website: http://www.procrew.pro
 *
 * Theqqa
--}}
@extends('layouts.master')

{{--@section('search')
	@parent
	@include('pages.inc.contact-intro')
@endsection--}}

@section('content')
	@include('common.spacer')
	<div class="main-container" style="min-height: 527px;">
		<div class="container">
			<div class="row clearfix">
				
				@if (isset($errors) and $errors->any())
					<div class="col-xl-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h5><strong>{{ t('Oops ! An error has occurred. Please correct the red fields in the form') }}</strong></h5>
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

						<h5 class="list-title gray mt-0">
							<strong>{{ t('Contact Us') }}</strong>
						</h5>
						<div class="col-md-6" style="float: right;">
							<p style="font-size:16px; margin-top: 20px;"><i class="fa fa-phone" style="color: #ad891d; margin-left: 10px;"></i> <strong>الهاتف:</strong> 0551166575</p>
							<p style="font-size: 16px;"><i class="fa fa-map-pin" style="font-size: 24px; color: #ad891d; margin-left: 10px;"></i> <strong>العنوان:</strong> 4410 طريق الدمام - حي المؤنسية - 13253 - الرياض <br>- المملكة العربية السعودية</p>
							<p style="font-size:16px;"><i class="fa fa-envelope" style="color: #ad891d; margin-left: 10px;"></i> <strong>البريد الإلكتروني:</strong> <a href="mailto:Support@theqqa.com.sa">Support@theqqa.com.sa</a></p>
						</div>
						<div class="col-md-6" style="float: right;">
							<form class="form-horizontal" method="post" action="{{ lurl(trans('routes.contact')) }}">
							{!! csrf_field() !!}
							<fieldset>
								<div class="row">
									<input type="hidden" id="service_type" name="service_type" value="contact_page">
									<div class="col-md-6">
										<?php $firstNameError = (isset($errors) and $errors->has('first_name')) ? ' is-invalid' : ''; ?>
										<div class="form-group required">
											<input id="first_name" name="first_name" type="text" placeholder="{{ t('First Name') }}"
												   class="form-control{{ $firstNameError }}" value="{{ old('first_name') }}">
										</div>
									</div>

									<div class="col-md-6">
										<?php $lastNameError = (isset($errors) and $errors->has('last_name')) ? ' is-invalid' : ''; ?>
										<div class="form-group required">
											<input id="last_name" name="last_name" type="text" placeholder="{{ t('Last Name') }}"
												   class="form-control{{ $lastNameError }}" value="{{ old('last_name') }}">
										</div>
									</div>

									<div class="col-md-6">
										<?php $companyNameError = (isset($errors) and $errors->has('company_name')) ? ' is-invalid' : ''; ?>
										<div class="form-group required">
											<input id="company_name" name="company_name" type="text" placeholder="{{ t('Company Name') }}"
												   class="form-control{{ $companyNameError }}" value="{{ old('company_name') }}">
										</div>
									</div>

									<div class="col-md-6">
										<?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
										<div class="form-group required">
											<input id="email" name="email" type="text" placeholder="{{ t('Email Address') }}" class="form-control{{ $emailError }}"
												   value="{{ old('email') }}">
										</div>
									</div>

									<div class="col-md-12">
										<?php $messageError = (isset($errors) and $errors->has('message')) ? ' is-invalid' : ''; ?>
										<div class="form-group required">
											<textarea class="form-control{{ $messageError }}" id="message" name="message" placeholder="{{ t('Message') }}"
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

										<div class="form-group">
											<button type="submit" class="btn btn-primary btn-lg">{{ t('Submit') }}</button>
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
@endsection

@section('after_scripts')
	<script src="{{ url('assets/js/form-validation.js') }}"></script>
@endsection
