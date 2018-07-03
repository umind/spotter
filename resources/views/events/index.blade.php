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
							<div class="row-eq-height">
								<div class="col-md-6 auction-name">
									<p>{{ $event->title }}</p>
								</div>
								<div class="col-md-6 all-products text-right">
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
							<div class="row-eq-height">
								<div class="col-md-4">
									<p>@lang('app.auctioner'): {{ $event->user->first_name }} {{ $event->user->last_name }}</p>
									<p>@lang('app.venue'): {{ $event->address }}, {{ $event->zip_code }} {{ $event->city }}</p>
								</div>
								<div class="col-md-4 text-center">
									<p class="text-red countdown" data-expire-date="{{ $event->auction_ends }}"></p>
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

    <div class="footer-features">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2>@lang('app.sell_your_items_through')</h2>
                    <p>@lang('app.thousands_of_people_selling')</p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <div class="icon-text-feature">
                        <i class="fa fa-check-circle-o"></i>
                        @lang('app.excellent_value')
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="icon-text-feature">
                        <i class="fa fa-check-circle-o"></i>
                        @lang('app.ease_of_use')
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="icon-text-feature">
                        <i class="fa fa-check-circle-o"></i>
                        @lang('app.huge_variety')
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="icon-text-feature">
                        <i class="fa fa-check-circle-o"></i>
                        @lang('app.human_support')
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <a href="{{route('category')}}" class="btn btn-warning btn-lg"><i class="fa fa-search"></i> @lang('app.browse_ads')</a>
                    <a href="{{route('create_ad')}}" class="btn btn-warning btn-lg"><i class="fa fa-save"></i> @lang('app.post_an_ad')</a>

                </div>
            </div>

        </div>
    </div>


@endsection