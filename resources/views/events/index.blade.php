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
						{{-- <div class="auction-content">
							<ul>
								@foreach($event->auctions as $auction)
									<li class="item">
										<a href="{{ route('single_ad', [$auction->id, $auction->slug]) }}">
											<img src="{{ media_url($auction->feature_img, 'crop') }}" class="img-responsive" />
										</a>
										<div class="information">
											<h3 style="font-weight: 600">{{ $auction->bid_no }}</h3>
											<p>@lang('app.starting_price') {{ themeqx_price($auction->price, 2) }}</p>
										</div>
									</li>
								@endforeach
							</ul>
						</div> --}}
						<div class="auction-footer">
							<div class="row no-gutters">
								<div class="col-md-2">
									<div class="auction-top">
										<p>{{ $event->auctioner }}</p>
										<p>{{ $event->address }}</p>
										@if($event->address2)
                                            <p>{{ $event->address2 }}</p>
                                        @endif
										<p>{{ $event->zip_code }} {{ $event->city }}</p>
									</div>
								</div>
								<div class="col-md-8 text-center">
									<div class="auction-middle">
										@if($event->image)
	                                		<a href="{{ route('single_event', $event->id) }}">
												<img src="{{ event_img_url($event->image) }}" height="150px">
	                                		</a>
										@endif
									</div>
								</div>
								<div class="col-md-2 text-right">
									<div class="auction-bottom">
										@php 
											$latestProductToExpire = $event->auctions()->latest('expired_at')->first();
										@endphp
										<p class="text-red {{ Carbon\Carbon::parse($event->auction_ends)->isPast() ? '' : 'countdown' }}" data-expire-date="{{ $event->auction_ends }}">
											@if(Carbon\Carbon::parse($event->auction_ends)->isPast())
												@if($latestProductToExpire)
													@if(Carbon\Carbon::parse($latestProductToExpire->expired_at)->isPast())
														@lang('app.auction_has_ended')
													@else
														@lang('app.auction_soon_ends')
													@endif
												@endif
											@endif
										</p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12" style="margin-top: 10px;">
									<p>{{ Carbon\Carbon::parse($event->auction_ends)->format('d-m-Y') }} ab {{ Carbon\Carbon::parse($event->auction_ends)->format('H:i') }} Uhr</p>
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