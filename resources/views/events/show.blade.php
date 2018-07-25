@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')
    @if($ads->count())
        <div id="regular-ads-container">
			<div id="auction">
				<div class="container">
					<div id="auction-info">
						<div class="row">
							<div class="col-md-8">
								<h2 class="text-uppercase">Open auction 05.08.2018.</h2>
								<div class="category">
									<img src="{{ asset('assets/img/category.png') }}" title="Category" alt="assets/img/category.png" />
									<p>All Categories</p>
								</div>
								<div class="location">
									<img src="{{ asset('assets/img/location.png') }}" title="Location" alt="assets/img/location.png" />
									<p>Vienna, Austria</p>
								</div>
								<div class="time">
									<img src="{{ asset('assets/img/time.png') }}" title="Time" alt="assets/img/time.png" />
									<p>Time remaining: 52 days, 11:45 hours</p>
								</div>
							</div>
							<div class="col-md-4 text-right">
								<img class="auction" src="{{ asset('assets/img/auction.png') }}" title="Auction" alt="assets/img/auction.png" />
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="container">
				<div class="row">

                    @foreach($ads as $ad)
                        <div class="col-md-3">
                            <div class="ad-box">
								<div class="ad-box-caption-title">
									<h3>
                                        <a class="ad-box-title" href="{{ route('single_ad', [$ad->id, $ad->slug]) }}" title="{{ $ad->title }}">
                                            {{ str_limit($ad->title, 40) }}
                                        </a>
                                    </h3>
								</div>
                                <div class="ads-thumbnail">
                                    <a href="{{ route('single_ad', [$ad->id, $ad->slug]) }}">
                                        <img itemprop="image" src="{{ media_url($ad->feature_img) }}" class="img-responsive" alt="{{ $ad->title }}">
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
									<div class="bid-number">@lang('app.bid_no'): {{ $ad->auction_no }}</div>
									<div class="starting-price">@lang('app.starting_price') {{ themeqx_price($ad->price) }}</div>
								</div>

                                <div class="countdown" data-expire-date="{{$ad->expired_at}}" ></div>
                                <div class="place-bid-btn">
                                    <a href="{{ route('single_ad', [$ad->id, $ad->slug]) }}" class="btn btn-primary">@lang('app.place_bid')</a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    @else
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="no-ads-wrap">
                        <h2><i class="fa fa-frown-o"></i> @lang('app.no_auctions_found') </h2>
                    </div>
                </div>
            </div>
        </div>

    @endif

@endsection