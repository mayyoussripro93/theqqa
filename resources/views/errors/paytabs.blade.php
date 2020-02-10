{{--
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
--}}
@extends('errors.layouts.master')

@section('search')
	@parent
	@include('errors/layouts/inc/search')
@endsection

@section('content')
	@if (!(isset($paddingTopExists) and $paddingTopExists))
		<div class="h-spacer"></div>
	@endif
	 <?php echo json_encode($_REQUEST); ?>
	<div class="main-container inner-page">
		<div class="container">
			<div class="section-content">
				<div class="row">

					<div class="col-md-12 page-content">

						<div class="error-page" style="margin: 100px 0;">
							<!--<h2 class="headline text-center" style="font-size: 180px; float: none;"> 405</h2>-->
							<!--<div class="text-center m-l-0" style="margin-top: 60px;">-->
							<!--	<h3 class="m-t-0"><i class="fa fa-warning"></i> Method not allowed.</h3>-->
							<!--			<h3 class="m-t-0"><i class="fa fa-warning"></i> Method not allowed.</h3>-->
								<p>
									<?php
									$defaultErrorMessage = "Meanwhile, you may <a href='" . url('/') . "'>return to homepage</a>";
									?>
	
								</p>
							<!--</div>-->
						</div>

					</div>

				</div>
			</div>
		</div>
	</div>
	<!-- /.main-container -->
@endsection

@section('after_scripts')
// <script>

//     $(document).ready(function () {
//     window.location.replace('https://www.theqqa.com');
// })</script>
@endsection
