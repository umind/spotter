@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')
    @if(get_option('enable_monetize') == 1)
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    {!! get_option('monetize_code_above_categories') !!}
                </div>
            </div>
        </div>
    @endif

    @if(get_option('enable_monetize') == 1)
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    {!! get_option('monetize_code_below_categories') !!}
                </div>
            </div>
        </div>
    @endif

    @if($ads->count())
        <div id="regular-ads-container">
            <div class="container">
                <div class="row">

                    <div class="col-md-12">
                        <div class="front-ads-head">
                            <h2>{{ $event->title }}</h2>
                        </div>
                    </div>

                    @foreach($ads as $ad)
                        <div class="col-md-3">

                            <div class="ad-box">
								<div class="ad-box-caption-title">
									<h3>Lorem Ipsum</h3>
									<a class="ad-box-title" href="{{ route('single_ad', [$ad->id, $ad->slug]) }}" title="{{ $ad->title }}">
										{{ str_limit($ad->title, 40) }}
									</a>
								</div>
                                <div class="ads-thumbnail">
                                    <a href="{{ route('single_ad', [$ad->id, $ad->slug]) }}">
                                        <img itemprop="image"  src="{{ media_url($ad->feature_img) }}" class="img-responsive" alt="{{ $ad->title }}">
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
									<div class="bid-number">Bid number: 00546</div>
									<div class="starting-price">Starting price: 40.000</div>
								</div>
<!--
                                <div class="caption">
                                    <div class="ad-box-category">
                                        @if($ad->sub_category)
                                            <a class="price text-muted" href="{{ route('search', [ $ad->country->country_code,  'category' => 'cat-'.$ad->sub_category->id.'-'.$ad->sub_category->category_slug]) }}"> <i class="fa fa-folder-o"></i> {{ $ad->sub_category->category_name }} </a>
                                        @endif
                                        @if($ad->city)
                                            <a class="location text-muted" href="{{ route('search', [$ad->country->country_code, 'state' => 'state-'.$ad->state->id, 'city' => 'city-'.$ad->city->id]) }}"> <i class="fa fa-map-marker"></i> {{ $ad->city->city_name }} </a>
                                        @endif
                                    </div>
                                </div>
-->
<!--								<div class="artical-number">ART-1234567890</div>-->
<!--
                                <div class="ad-box-footer">
                                    <span class="ad-box-price">@lang('app.starting_price') {{ themeqx_price($ad->price) }},</span>
                                    <span class="ad-box-price">@lang('app.current_bid') {{ themeqx_price($ad->current_bid()) }}</span>
                                    @if($ad->price_plan == 'premium')
                                        <div class="ad-box-premium" data-toggle="tooltip" title="@lang('app.premium_ad')">
                                            {!! $ad->premium_icon() !!}
                                        </div>
                                    @endif
                                </div>
-->
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
                        <h2><i class="fa fa-frown-o"></i> @lang('app.no_regular_ads_country') </h2>

                        @if (env('APP_DEMO') == true)
                            <h4>Seems you are checking the demo version, you can check ads preview by switching country to <a href="{{route('set_country', 'US')}}"><img src="{{asset('assets/flags/16/us.png')}}" /> United States </a> </h4>
                        @endif

                    </div>
                </div>
            </div>
        </div>

    @endif


    @if(get_option('enable_monetize') == 1)
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    {!! get_option('monetize_code_below_regular_ads') !!}
                </div>
            </div>
        </div>
    @endif

    <div class="container">
        <div class="section-stats-box">
            <div class="row">
                <div class="col-sm-4">
                    <div class="home-stats-box">
                        <div class="inner">
                            <i class="fa fa-gavel"></i>
                            <div class="inner-content">
                                <h3 class="title">@lang('app.ads')</h3>
                                <div class="sub_title">{{$total_ads_count}} @lang('app.ads_available')</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="home-stats-box">
                        <div class="inner">
                            <i class="fa fa-lock"></i>
                            <div class="inner-content">
                                <h3 class="title">@lang('app.secured_payments')</h3>
                                <div class="sub_title">@lang('app.secured_all_your_payments')s</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="no-border home-stats-box">
                        <div class="inner">
                            <i class="fa fa-users"></i>
                            <div class="inner-content">
                                <h3 class="title">@lang('app.trusted_sellers')</h3>
                                <div class="sub_title">{{$user_count}} @lang('app.registered_sellers')</div>
                            </div>
                        </div>
                    </div>
                </div>
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