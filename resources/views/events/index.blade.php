@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')

    <div id="regular-ads-container">
        <div class="container">
			<div class="auction">
				<div class="front-ads-head">
					<h2 class="text-uppercase">@lang('app.ads')</h2>
				</div>
				@if($events->count())
					@foreach($events as $event)
						<div class="auction-header">
							<div class="row">
								<div class="col-xs-6 auction-name">
									<p>{{ $event->title }}</p>
								</div>
								<div class="col-xs-6 all-products text-right">
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
									<p class="text-red countdown" data-expire-date="{{ $event->auction_begins }}"></p>
								</div>
								<div class="col-md-4 text-right">
									<p>@lang('app.date'): {{ Carbon\Carbon::parse($event->auction_ends)->format('d-m-Y') }}</p>
									<p>@lang('app.begins'): {{ Carbon\Carbon::parse($event->auction_ends)->format('H:i') }}</p>
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