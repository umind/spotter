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

    @if($events->count())
        <div id="regular-ads-container">
            <div class="container">
                <div class="row">
    
                    @foreach($events as $event)
                        <div class="col-md-3">
                            <div class="ad-box">
                                <div class="ads-thumbnail">
                                    <a href="{{ route('single_event', ['event' => $event->id]) }}">
                                        <img itemprop="image"  src="{{ media_url($event->feature_img) }}" class="img-responsive" alt="{{ $event->title }}">
                                        <span class="modern-img-indicator">
                                    </span>
                                    </a>
                                </div>
                                <div class="caption">
                                    <div class="ad-box-caption-title">
                                        <a class="ad-box-title" href="{{ route('single_event', ['event' => $event->id]) }}" title="{{ $event->title }}">
                                            {{ str_limit($event->title, 40) }}
                                        </a>
                                    </div>
                                </div>

                                {{-- <div class="countdown" data-expire-date="{{$ad->expired_at}}" ></div>
                                <div class="place-bid-btn">
                                    <a href="{{ route('single_ad', [$ad->id, $ad->slug]) }}" class="btn btn-primary">@lang('app.place_bid')</a>
                                </div> --}}

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