@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')
    <div id="regular-ads-container">
		<div id="auction">
			<div class="container">
				<div id="auction-info">
					<div class="row">
						<div class="col-md-10">
							<h2 class="text-uppercase">{{ $event->title }}</h2>
							{{-- <div class="auctioneer">
								<p>@lang('app.auctioner'): {{ $event->auctioner }}</p>
							</div> --}}
							<div class="location">
								<div class="clearfix">
                                    <span class="is-text-black pull-left">@lang('app.city_event_show_title'):</span> 
                                    <div class="pull-left location-info">
                                        <p>{{ $event->auctioner }}</p>
                                        <p>{{ $event->address }}</p>
                                        @if($event->address2)
                                            <p>{{ $event->address2 }}</p>
                                        @endif
                                        <p>{{ $event->zip_code }}, {{ $event->city }}</p>
                                    </div>
                                </div>
							</div>
							<div class="time">
								<p>
                                    <span class="time-label is-text-black">@lang('app.auction_begins_event_show_title'):</span> {{ \Carbon\Carbon::parse($event->auction_ends)->formatLocalized('%d %B %Y') }} {{ __('app.at') }} {{ \Carbon\Carbon::parse($event->auction_ends)->formatLocalized('%H:%M') }}
                                </p>
							</div>
                            <div class="event_view_dates">
								<p>
                                    <span class="is-text-black">@lang('app.view_dates'):</span><span>{!! nl2br($event->view_dates) !!}</span>
                                </p>
                            </div>
                            <div class="event_description">
								<p>
                                    <span>{!! nl2br($event->description) !!}</span>
                                </p>
                            </div>
						</div>
						<div class="col-md-2 text-right">
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
									<div class="starting-price pull-left">{{ $ad->price ? __('app.starting_price') : __('app.buy_now_price') }} </div>
									<div class="pull-right">{{ $ad->price ? themeqx_price($ad->price) : themeqx_price($ad->buy_now_price) }}</div>
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