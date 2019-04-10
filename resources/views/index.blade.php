@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')

    @if($top_categories->count())
        <div class="home-category">

            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="front-ads-head">
                            <h2>@lang('app.categories')</h2>
                        </div>
                    </div>
                </div>
            </div>


            <div class="container">
                <div class="row">
                    @foreach($top_categories as $top_cat)
                        <div class="col-xs-6 col-sm-4 col-md-3">
                            <div class="home-cat-box">
                                <div class="home-cat-box-title">
                                    <h3> <a href="{{ route('search', [ 'category' => 'cat-'.$top_cat->id.'-'.$top_cat->category_slug]) }}"> <i class="fa fa-folder-open-o"></i>  {{$top_cat->category_name}}
                                        </a>
                                    </h3>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if($ads->count())
        <div id="premium-ads-container">
            <div class="container">
                <div class="row">

{{--                     <div class="col-md-12">
                        <div class="front-ads-head">
                            <h2>@lang('app.all_products')</h2>
                        </div>
                    </div> --}}

                    @foreach($ads as $ad)
                        <div class="col-sm-6 col-md-3">

                            <div class="ad-box">
                                <div class="ad-box-caption-title">
                                    <h4>
                                        <span class="ad-box-title" href="{{ route('single_ad', [$ad->id, $ad->slug]) }}" title="{{ $ad->title }}">
                                            {{ str_limit($ad->title, 40) }}
                                        </span>
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

                                <div class="countdown product-countdown" data-expire-date="{{ $ad->expired_at }}" data-sold="{{ $ad->bids->where('is_accepted', '1')->first() ? 'sold' : '' }}"></div>
                                <div class="place-bid-btn">
                                    <a href="{{ route('single_ad', [$ad->id, $ad->slug]) }}" class="btn btn-primary">@lang('app.place_bid')</a>
                                </div>

                            </div>
                        </div>
                    @endforeach

                    <div class="col-md-12 text-center">
                        {!! $ads->links() !!}
                    </div>
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