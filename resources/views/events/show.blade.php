@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')
    <div id="regular-ads-container">
		<div id="auction">
			<div class="container">
				<div id="auction-info">
					<div class="row">
						<div class="col-md-8">
							<h2 class="text-uppercase">{{ $event->title }}</h2>
							{{-- <div class="auctioneer">
								<img src="{{ asset('assets/img/auctioneer.png') }}" alt="assets/img/auctioneer.png" />
								<p>@lang('app.auctioner'): {{ $event->auctioner }}</p>
							</div> --}}
							<div class="location">
								<img src="{{ asset('assets/img/auctioneer.png') }}" alt="assets/img/auctioneer.png" />
								<p>@lang('app.city'): {{ $event->auctioner }}, {{ $event->address }}, {{ $event->zip_code }} {{ $event->city }}</p>
							</div>
							<div class="time">
								<img src="{{ asset('assets/img/time.png') }}" alt="assets/img/time.png" />
								<p>@lang('app.last_bidding'): {{ \Carbon\Carbon::parse($event->auction_ends)->formatLocalized('%d %B %Y') }} {{ __('app.at') }} {{ \Carbon\Carbon::parse($event->auction_ends)->formatLocalized('%H:%M') }}</p>
							</div>
                            <div class="event_view_dates">
                                <img src="{{ asset('assets/img/calendar.png') }}" alt="assets/img/calendar.png" />
								<p><span>@lang('app.view_dates'):</span><span>{!! nl2br($event->view_dates) !!}</span></p>
                            </div>
                            <div class="event_description">
                                <img src="{{ asset('assets/img/description.png') }}" alt="assets/img/description.png" />
								<p><span></span><span>{!! nl2br($event->description) !!}</span></p>
                            </div>
						</div>
						<div class="col-md-4 text-right">
                            @php 
                                $latestProductToExpire = $event->auctions()->latest('expired_at')->first();
                            @endphp

                            @if($event->image)
                                <img src="{{ event_img_url($event->image) }}" class="event-full-img">
                            @endif

                            <div class="{{ Carbon\Carbon::parse($event->auction_ends)->isPast() ? '' : 'countdown event-show-countdown' }}" data-expire-date="{{$event->auction_ends}}"></div>
                                <div class="event-show-countdown">
                                    @if(Carbon\Carbon::parse($event->auction_ends)->isPast())
                                        @if($latestProductToExpire && Carbon\Carbon::parse($latestProductToExpire->expired_at)->isPast())
                                            @lang('app.auction_has_ended')
                                        @else
                                            @lang('app.auction_soon_ends')
                                        @endif
                                    @endif
                                </div>
                            {{-- <h1>@lang('app.bid_now')</h1> --}}
							{{-- <img class="auction" src="{{ asset('assets/img/auction.png') }}" title="Auction" alt="assets/img/auction.png" /> --}}
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="container">
			<div class="row events">
                @if(
                    ($event->status == '1' && $ads->count()) || 
                    ($event->status == '2' && $ads->count())
                )
                    @foreach($ads as $ad)
                        <div class="col-sm-6 col-md-3">
                            <div class="ad-box">
								<div class="ad-box-caption-title">
									<h4>
                                        <a class="ad-box-title" href="{{ route('single_ad', [$ad->id, $ad->slug]) }}" title="{{ $ad->title }}">{{ $ad->title }}</a>
                                    </h4>
								</div>
                                <div class="ads-thumbnail">
                                    <a href="{{ route('single_ad', [$ad->id, $ad->slug]) }}">
                                        <img itemprop="image" src="{{ media_url($ad->feature_img, 'crop') }}" class="img-responsive" alt="{{ $ad->title }}">
                                        <span class="modern-img-indicator">
                                        @if(! empty($ad->video_url))
                                                <i class="fa fa-file-video-o"></i>
                                            @else
                                                <i class="fa fa-file-image-o"> {{ $ad->media_img->count() }}</i>
                                            @endif
                                    </span>
                                    </a>
                                </div>
								<div class="bid-price">
									<div class="bid-number text-center">{{ $ad->bid_no }}</div>
									<div class="starting-price pull-left">@lang('app.starting_price') </div>
									<div class="pull-right">{{ themeqx_price($ad->price) }}</div>
								</div>

                                <div class="countdown" data-expire-date="{{$ad->expired_at}}" ></div>
                                <div class="place-bid-btn">
                                    <a href="{{ route('single_ad', [$ad->id, $ad->slug]) }}" class="btn btn-primary">@lang('app.place_bid')</a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <div class="no-ads-wrap">
                            <h2><i class="fa fa-frown-o"></i> @lang('app.no_auctions_found') </h2>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection