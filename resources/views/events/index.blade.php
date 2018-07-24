@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')

    <div id="regular-ads-container">
        <div class="container">
			<div class="auction">
				@if($events->count())
					@foreach($events as $event)
						<div class="auction-header">
							<div class="row">
								<div class="col-sm-6 auction-name">
									<p>{{ $event->title }}</p>
								</div>
								<div class="col-sm-6 all-products text-right">
									<a href="{{ route('single_event', $event->id) }}" id="btn-allProducts">@lang('app.all_products')</a>
								</div>
							</div>
						</div>
							<div class="auction-content">
							<ul>
								@foreach($event->auctions as $auction)
									<li class="item">
										<a href="{{ route('single_ad', [$auction->id, $auction->slug]) }}">
											<img src="{{ media_url($auction->feature_img) }}" class="img-responsive" />
										</a>
										<div class="information">
											<h4>{{ $auction->title }}</h4>
											<p>@lang('app.starting_price'): {{ number_format($auction->price, 2) }}</p>
										</div>
									</li>
								@endforeach
							</ul>
						</div>
							<div class="auction-footer">
							<div class="row">
								<div class="col-md-4">
									<p>@lang('app.auctioner'): {{ $event->auctioner }}</p>
									<p>@lang('app.venue'): {{ $event->address }}, {{ $event->zip_code }} {{ $event->city }}</p>
								</div>
								<div class="col-md-4 text-center">
									@php 
										$latestProductToExpire = $event->auctions()->latest('expired_at')->first();
										// $dateUsedForCountdown = 
										$dataExpireDate = "data-expire-date='{$event->auction_ends}'"
									@endphp
									<p class="text-red {{ Carbon\Carbon::parse($event->auction_ends)->isPast() ? '' : 'countdown' }}">
										@if(Carbon\Carbon::parse($event->auction_ends)->isPast())
											@if(Carbon\Carbon::parse($latestProductToExpire->expired_at)->isPast())
												@lang('app.auction_has_ended')
											@else
												@lang('app.auction_soon_ends')
											@endif
										@endif
									</p>
								</div>
								<div class="col-md-4 text-right">
									<p>@lang('app.date'): {{ Carbon\Carbon::parse($event->auction_ends)->format('d-m-Y') }}</p>
									<p>@lang('app.auction_starts_to_end'): {{ Carbon\Carbon::parse($event->auction_ends)->format('H:i') }}</p>
								</div>
							</div>
						</div>
					@endforeach
				@else
					<h1>@lang('app.no_upcoming_events')</h1>
				@endif
			</div>
		</div>
	</div>

@endsection