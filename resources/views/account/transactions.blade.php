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
	@include('common.spacer')
	<div class="main-container">
		<div class="container">
			<div class="row">
				
				<div class="col-md-3 page-sidebar">
					@include('account.inc.sidebar')
				</div>
				<!--/.page-sidebar-->
				
				<div class="col-md-9 page-content">
					<div class="inner-box">
						<h2 class="title-2"><i class="icon-money"></i> {{ t('Transactions') }} </h2>
						
						<div style="clear:both"></div>
						
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
								<tr>
									<th><span>ID</span></th>
									<th>{{ t('Description') }}</th>
									<th>{{ t('Payment Method') }}</th>
									<th>{{ t('Value') }}</th>
									<th>{{ t('Date') }}</th>
									<th>{{ t('Status') }}</th>
								</tr>
								</thead>
								<tbody>
								<?php
								if (isset($transactions) && $transactions->count() > 0):
									foreach($transactions as $key => $transaction):
										
										// Fixed 2
										if (empty($transaction->post)) continue;
										if (!$countries->has($transaction->post->country_code)) continue;
										
										if (empty($transaction->package)) continue;
								?>
								<tr>
									<td>#{{ $transaction->id }}</td>
								<?php if ( $transaction->post->id != 0){?>
											<?php if ( $transaction->package->id != 19){?>
											<td>
												<?php $attr = ['slug' => slugify($transaction->post->title), 'id' => $transaction->post->id]; ?>
												<a href="{{ lurl($transaction->post->uri, $attr) }}">{{ $transaction->post->title }}</a><br>
												<strong>{{ t('Type') }}</strong> {{ $transaction->package->name }} <br>
												<strong>{{ t('Duration') }}</strong> {{ $transaction->package->duration }} {{ t('days') }}
											</td>
											<?php } else{ ?>
											<td>
												<?php $attr = ['slug' => slugify($transaction->post->title), 'id' => $transaction->post->id]; ?>
												<a href="{{ lurl($transaction->post->uri, $attr) }}">{{ $transaction->post->title }}</a><br>
												<strong>{{ t('Type') }}</strong> {{ $transaction->package->name }} <br>
											</td>
												<?php }?>
									<?php } else{ ?>
									<td>
									<a href="#">{{ $transaction->post->title }}</a><br>
									<strong>{{ t('Type') }}</strong> {{ $transaction->package->name }} <br>
									<?php }?>
									</td>
									<td>
										@if ($transaction->active == 1)
											@if (!empty($transaction->paymentMethod))
												{{ t('Paid by') }} {{ $transaction->paymentMethod->display_name }}
											@else
												{{ t('Paid by') }} --
											@endif
										@else
											{{ t('Pending payment') }}
										@endif
									</td>
									<?php if ( $transaction->package->id == 19){
										$post_price=\App\Models\Post::where('id',$transaction->post->id)->first();?>

									<td>{!! ((!empty($transaction->package->currency)) ? $transaction->package->currency->symbol : '') . '' . $post_price->price*0.025 !!}</td>
									<?php } else{ ?>
									<td>{!! ((!empty($transaction->package->currency)) ? $transaction->package->currency->symbol : '') . '' . $transaction->package->price !!}</td>
									<?php }?>
									<td>{{ $transaction->created_at->formatLocalized(config('settings.app.default_datetime_format')) }}</td>
									<td>
										@if ($transaction->active == 1)
											<span class="badge badge-success">{{ t('Done') }}</span>
										@else
											<span class="badge badge-info">{{ t('Pending') }}</span>
										@endif
									</td>
								</tr>
								<?php endforeach; ?>
								<?php endif; ?>
								</tbody>
							</table>
						</div>
						
						<nav aria-label="">
							{{ (isset($transactions)) ? $transactions->links() : '' }}
						</nav>
						
						<div style="clear:both"></div>
					
					</div>
				</div>
				<!--/.page-content-->
				
			</div>
			<!--/.row-->
		</div>
		<!--/.container-->
	</div>
	<!-- /.main-container -->
@endsection

@section('after_scripts')
@endsection