@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ strip_tags($title) }} | @endif @parent @endsection

@section('social-meta')
    <meta property="og:title" content="{{ safe_output($ad->title) }}">
    <meta property="og:description" content="{{ substr(trim(preg_replace('/\s\s+/', ' ',strip_tags($ad->description) )),0,160) }}">
    @if($ad->media_img->first())
        <meta property="og:image" content="{{ media_url($ad->media_img->first(), true) }}">
    @else
        <meta property="og:image" content="{{ asset('uploads/placeholder.png') }}">
    @endif
    <meta property="og:url" content="{{  route('single_ad', [$ad->id, $ad->slug]) }}">
    <meta name="twitter:card" content="summary_large_image">
    <!--  Non-Essential, But Recommended -->
    <meta name="og:site_name" content="{{ get_option('site_name') }}">
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/fancybox/jquery.fancybox.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.26.11/sweetalert2.min.css">
@endsection

@section('content')

  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">@lang('app.place_bid')</h4>
        </div>
        <div class="modal-body">
            {!! Form::open(['route'=> ['post_max_bid', $ad->id], 'class' => 'form-inline']) !!}
            <div class="form">
                <div class="input-group max-bid">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-danger btn-number" data-type="minus" data-field="max_bid_amount">
                            <span class="glyphicon glyphicon-minus"></span>
                        </button>
                    </span>
                @php
                    $inputMaxBid = $userMaxBid && $userMaxBid > $ad->current_bid_plus_increaser() ? number_format($userMaxBid + $ad->price_increaser, 2) : number_format($ad->current_bid_plus_increaser(), 2);
                    
                    if (!$bids->count()) {
                        $inputMaxBid = $ad->price;
                    }
                @endphp
                    <input type="text" name="max_bid_amount" class="form-control input-number bid-value" id="is-max-bid" value="{{ $inputMaxBid }}" min="{{ $inputMaxBid }}">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="max_bid_amount">
                          <span class="glyphicon glyphicon-plus"></span>
                      </button>
                  </span>
                  {{-- <div class="input-group-addon">.00</div> --}}
              </div>
          </div>
          <button type="submit" class="btn btn-danger bid">@lang('app.place_max_bid')</button>
          {!! Form::close() !!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('app.close')</button>
    </div>
    </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div class="modal fade" id="confirmBidModal" tabindex="-1" role="dialog" aria-labelledby="confirmBidModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p>@lang('app.confirm_bid_modal_text')</p>

                    {{-- @php
                        $adPrice = $ad->price ? $ad->price : $ad->buy_now_price;
                    @endphp --}}

                    <hr>
                    
                    <div class="row">
                        <div class="col-md-4">
                        <span>CHF</span>
                        </div>
                        <div class="col-md-4">
                            <span class="bidded-value"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <span>CHF</span>
                        </div>
                        <div class="col-md-4">
                            <span>7.7 MwSt</span>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <span>CHF</span>
                        </div>
                        <div class="col-md-4">
                            <span class="bidded-value-total"></span> Total
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="confirmBidButton">@lang('app.confirm_bid_button_text')</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('app.close')</button>
                </div>
            </div>
        </div>
    </div>

    <div class="page-header search-page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-group btn-breadcrumb">
                        <a href="{{ route('home') }}" class="btn btn-warning"><i class="glyphicon glyphicon-home"></i></a>

                        {{-- <a href="{{ route('search', [$ad->country->country_code] ) }}" class="btn btn-warning">{{$ad->country->country_code}}</a> --}}

                        @if($ad->category)
                            <a href="{{ route('search', ['category' => 'cat-'.$ad->category->id.'-'.$ad->category->category_slug] ) }}" class="btn btn-warning">  {{ $ad->category->category_name }} </a>
                        @endif
                        @if($ad->sub_category)
                            <a href="{{ route('search', ['category' => 'cat-'.$ad->sub_category->id.'-'.$ad->sub_category->category_slug] ) }}" class="btn btn-warning">  {{ $ad->sub_category->category_name }} </a>
                        @endif

                        <a href="{{  route('single_ad', [$ad->id, $ad->slug]) }}" class="btn btn-warning">{{ safe_output($ad->title) }}</a>

                    </div>
					<div class="bid-number-div">
						<h2 class="bid-number-header">{{ $ad->bid_no }}</h2>
                        <h2>{{safe_output($ad->title)}}</h2>
					</div>
					{{-- <p class="bid-number-header">{{ trans('app.bid_no') }}: {{ $ad->bid_no }}</p> --}}
					<p class="auction-number-header">{{ trans('app.auction_no') }}: {{ $ad->auction_no }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="single-auction-wrap">

        <div class="container">
            <div class="row">

                <div class="col-sm-12 paginated-articles">
                    <div class="previous-article pull-left">
                        @if($previous && $previous['id'] != $ad->id)
                            <a href="{{ url('auction/' . $previous['id'] . '/' . $previous['slug']) }}">
                                @lang('app.previous_article')
                            </a>
                        @endif
                    </div>

                    <div class="next-article pull-right">
                        @if($next && $next['id'] != $ad->id)
                            <a href="{{ url('auction/' .  $next['id'] . '/' .  $next['slug']) }}">
                                @lang('app.next_article')
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-sm-8">

                    @include('admin.flash_msg')

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

{{--                     @if($ad->category_type == 'auction' && ! auth()->check())
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-circle"></i> @lang('app.before_bidding_sign_in_info')
                        </div>
                    @endif
 --}}

                    <div class="auction-img-video-wrap">
                        @if ($ad->status == '2')
                            <div class="alert alert-warning"> <i class="fa fa-warning"></i> @lang('app.ad_closed')</div>
                        @elseif ( ! $ad->is_past_but_active())
                            <div class="alert alert-warning"> <i class="fa fa-warning"></i> @lang('app.ad_not_published_warning')</div>
                        @endif
                        @if( ! empty($ad->video_url))
                            <?php
                            $video_url = safe_output($ad->video_url);
                            if (strpos($video_url, 'youtube') > 0) {
                                preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $video_url, $matches);
                                if ( ! empty($matches[1])){
                                    echo '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://www.youtube.com/embed/'.$matches[1].'" frameborder="0" allowfullscreen></iframe></div>';
                                }

                            } elseif (strpos($video_url, 'vimeo') > 0) {
                                if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $video_url, $regs)) {
                                    if (!empty($regs[3])){
                                        echo '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://player.vimeo.com/video/'.$regs[3].'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
                                    }
                                }
                            }
                            ?>
                        @else
                            <div class="ads-gallery">
                                <a href="#" class="photos-holder">
                                    <img id="zoom_01" src="{{ media_url($ad->media_img()->first(), 'medium') }}" data-zoom-image="{{ media_url($ad->media_img()->first(), 'big') }}" alt="b-1">
                                </a>
                                <div id="gallery_01">
                                    @foreach($ad->media_img as $img)
                                        <a href="#" data-image="{{ media_url($img, 'medium') }}" data-zoom-image="{{ media_url($img, 'big') }}">
                                            <img id="zoom_01" src="{{ media_url($img, 'thumb') }}" alt="{{ $ad->title }}">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>

                    <div class="ads-detail">
                        @lang('app.description')</h4>
                        {!! nl2br(safe_output($ad->description)) !!}
                    </div>

                    @if($ad->category_type == 'auction')
                        <hr />
                        <div id="bid_history">
                            <h2>@lang('app.bid_history')</h2>
                            @if($bids->count())
                                <table class="table table-striped">
                                    <tr>
                                        <th>@lang('app.bidder')</th>
                                        {{-- <th>@lang('app.bid_amount')</th> --}}
                                        <th>@lang('app.date_time')</th>
                                    </tr>
                                    @foreach($bids as $bid)
                                        <tr>
                                            <td>
                                                {{ $bid->user->user_name }}
                                                @if($bid->won_bid_amount)
                                                    <i class="fa fa-check-circle text-primary"></i>
                                                @endif
                                            </td>
                                            {{-- <td>{{ themeqx_price($bid->bid_amount) }}</td> --}}
                                            <td>{{ \Carbon\Carbon::parse($bid->updated_at)->formatLocalized(get_option('date_format')) }}</td>
                                        </tr>
                                    @endforeach

                                </table>
                            @else
                                <p>@lang('app.there_is_no_bids')</p>
                            @endif
                        </div>
                    @endif

                    {{-- @if(get_option('enable_fb_comments') == 1)
                        <hr />
                        <div class="fb-comments" data-href="{{route('single_ad', [$ad->id, $ad->slug])}}" data-width="100%"></div>
                    @endif --}}

                    {{-- @if(get_option('enable_comments') == 1)
                        <hr />
                        @php $comments = \App\Comment::approved()->parent()->whereAdId($ad->id)->with('childs_approved')->orderBy('id', 'desc')->get();
                        $comments_count = \App\Comment::approved()->whereAdId($ad->id)->count();
                        @endphp
                        <div class="comments-container">
                            @if($comments_count < 1)
                                <h2>@lang('app.no_comment_found')</h2>
                            @elseif($comments_count == 1)
                                <h2>{{$comments_count}} @lang('app.comment_found')</h2>
                            @elseif($comments_count > 1)
                                <h2>{{$comments_count}} @lang('app.comments_found')</h2>
                            @endif

                            <div class="post-comments-form">
                                {{ Form::open(['route'=> ['post_comments', $ad->id], 'class' => 'form-horizontal']) }}

                                @if( ! auth()->check())
                                    <div class="form-group {{ $errors->has('author_name')? 'has-error':'' }}">
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="author_name" value="@if(auth()->check() ) {{auth()->user()->name}}@else{{old('author_name')}}@endif" name="author_name" placeholder="@lang('app.author_name')">
                                            {!! $errors->has('author_name')? '<p class="help-block">'.$errors->first('author_name').'</p>':'' !!}
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('author_email')? 'has-error':'' }}">
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="author_email" value="@if(auth()->check() ) {{auth()->user()->email}}@else{{old('author_email')}}@endif" name="author_email" placeholder="@lang('app.author_email')">
                                            {!! $errors->has('author_email')? '<p class="help-block">'.$errors->first('author_email').'</p>':'' !!}
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group {{ $errors->has('comment')? 'has-error':'' }}">
                                    <div class="col-sm-8">
                                        <textarea class="form-control" name="comment" rows="8" placeholder="@lang('app.write_your_comment')"></textarea>
                                        {!! $errors->has('comment')? '<p class="help-block">'.$errors->first('comment').'</p>':'' !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-8">
                                        <input type="hidden" value="" class="comment_id" name="comment_id">
                                        <button type="submit" class="btn btn-success" id="post-comment-button" name="post_comment"><i class="fa fa-pencil-square"></i> @lang('app.post_comment') </button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>

                            @if($comments->count())
                                <ul id="comments-list" class="comments-list">

                                    @foreach($comments as $comment)
                                        <li id="comment-{{$comment->id}}">
                                            <div class="comment-main-level">
                                                <div class="comment-avatar">
                                                    @if($comment->user_id)
                                                        <img src="{{$comment->author->get_gravatar()}}" alt="{{$comment->author_name}}">
                                                    @else
                                                        <img src="{{avatar_by_email($comment->author_email)}}" alt="{{$comment->author_name}}">
                                                    @endif
                                                </div>
                                                <div class="comment-box" data-comment-id="{{$comment->id}}">
                                                    <div class="comment-head">
                                                        <h6 class="comment-name by-author">{{$comment->author_name}}</h6>
                                                        <span>{{ $comment->created_at->diffForHumans() }}</span>
                                                        <i class="fa fa-reply"></i>
                                                    </div>
                                                    <div class="comment-content">
                                                        {!! safe_output(nl2br($comment->comment)) !!}
                                                    </div>

                                                    <div class="reply_form_box" style="display: none;"></div>
                                                </div>
                                            </div>

                                        @if($comment->childs_approved)
                                            @foreach($comment->childs_approved as $childComment)
                                                    <ul class="comments-list reply-list">
                                                        <li id="comment-{{$childComment->id}}">
                                                            <div class="comment-avatar">
                                                                @if($childComment->user_id)
                                                                    <img src="{{$childComment->author->get_gravatar()}}" alt="{{$childComment->author_name}}">
                                                                @else
                                                                    <img src="{{avatar_by_email($childComment->author_email)}}" alt="{{$childComment->author_name}}">
                                                                @endif
                                                            </div>
                                                            <div class="comment-box" data-comment-id="{{$comment->id}}">
                                                                <div class="comment-head">
                                                                    <h6 class="comment-name by-author">{{$childComment->author_name}}</h6>
                                                                    <span>{{$childComment->created_at->diffForHumans()}}</span>
                                                                    <i class="fa fa-reply"></i>
                                                                </div>
                                                                <div class="comment-content">
                                                                    {!! safe_output(nl2br($childComment->comment)) !!}
                                                                </div>
                                                                <div class="reply_form_box" style="display: none;"></div>
                                                            </div>
                                                        </li>

                                                    </ul>

                                                @endforeach
                                            @endif
                                        </li>

                                    @endforeach


                                </ul>

                            @else

                            @endif


                        </div>
                    @endif --}}

                </div>

                <div class="col-sm-4">
                    <div class="sidebar-widget">

                        @if($ad->category_type == 'auction')
                            <div class="widget">
                                {{-- <h3>
                                    {{ $ad->price ? __('app.starting_price') : __('app.buy_now_price') }} {{ $ad->price ? themeqx_price($ad->price) : themeqx_price($ad->buy_now_price) }}
                                </h3>
                                <p class="pdv">@lang('app.plus_pdv') 7.7% MwSt</p>

                                @if($ad->buy_now_price && $ad->price)
                                    <h5>
                                        {{ __('app.buy_now_price') }} {{ themeqx_price($ad->buy_now_price) }}
                                    </h5>
                                    <h6 class="pdv">@lang('app.plus_pdv') 7.7% MwSt</h6>
                                @endif --}}

                                @if($ad->expired_at)
                                    @if($ad->is_bid_active())

                                        {{-- <p>{{ sprintf(trans('app.bid_deadline_info'), $ad->bid_deadline(), $ad->bid_deadline_left()) }}</p> --}}
                                        
                                        {{-- <p>@lang('app.total_bids'): {{ $bids->count() }}, <a id="bid-history" href="javascript:void(0)">@lang('app.bid_history')</a> </p> --}}

                                        @if($ad->price)
                                            <div class="bid-max-div">
                                                <p>@lang('app.max_bid_title')</p>
                                                <p>@lang('app.max_bid_desc')</p>
                                                <button type="button" class="btn btn-danger bid pull-left" data-toggle="modal" data-target="#myModal">@lang('app.place_max_bid')</button>

                                                @if($userMaxBid && $maxBid->user_id == Auth::id())
                                                    <p style="margin-top: 20px;">@lang('app.your_max_bid'): {{ themeqx_price($userMaxBid) }}</p>
                                                @endif
                                            </div>
                                        @endif

                                        <hr>

                                        @if($ad->price)
                                            
                                            <div class="bid-title-desc">
                                                <p>@lang('app.bid_title')</p>
                                                <p>@lang('app.bid_desc')</p>
                                            </div>

                                            {!! Form::open(['route'=> ['post_bid', $ad->id], 'class' => 'form-inline']) !!}
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-danger btn-number" data-type="minus" data-field="bid_amount">
                                                                <span class="glyphicon glyphicon-minus"></span>
                                                            </button>
                                                        </span>

                                                        @if(Auth::check())
                                                            <input type="text" name="bid_amount" class="form-control input-number bid-value" id="is-standard-bid" value="{{ $bids->count() ? number_format($ad->current_bid_plus_increaser(), 2) : number_format($ad->price, 2) }}" min="{{ $bids->count() ? number_format($ad->current_bid_plus_increaser(), 2) : number_format($ad->price, 2) }}">
                                                        @else
                                                            <input type="text" name="bid_amount" class="form-control input-number bid-value" id="is-standard-bid" value="{{ number_format($ad->price, 2) }}" min="{{ number_format($ad->price, 2) }}">
                                                        @endif
                                                        
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="bid_amount">
                                                              <span class="glyphicon glyphicon-plus"></span>
                                                            </button>
                                                        </span>
                                                        {{-- <div class="input-group-addon">.00</div> --}}
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary bid">@lang('app.place_bid')</button>
                                            {!! Form::close() !!}

                                            <hr>
                                        @endif

                                        @if($ad->buy_now_price)
                                            {!! Form::open(['route'=> ['buy_now', $ad->id], 'class' => 'form-inline']) !!}
                                                <input type="hidden" value="{{ number_format($ad->buy_now_price, 2) }}" class="bid-value" id="is-buy-now-bid">

                                                @if($ad->price)
                                                    <p style="margin-top: 2px; margin-bottom: 0px;">@lang('app.buy_now_text_below_button1')</p>
                                                    <p style="margin-top: 0px;">@lang('app.buy_now_text_below_button2')</p>
                                                @endif

                                                <button type="submit" class="btn btn-success buy_now">@lang('app.buy_now') für {{ themeqx_price($ad->buy_now_price) }}</button>
                                            {!! Form::close() !!}

                                            <hr>
                                        @endif

                                        <p>
                                            <span class="ad-info-name">
                                                <i class="fa fa-calendar-check-o"></i>
                                                Angebots-Ende
                                            </span> 
                                            <span class="ad-info-value">{{ \Carbon\Carbon::parse($ad->expired_at)->formatLocalized(get_option('date_format')) }}</span>
                                        </p>
                                    @else
                                        @if($ad->is_bid_accepted())
                                            <p>@lang('app.bid_accepted')</p>
                                        @else
                                            <p>{{ sprintf(trans('app.bid_deadline_closed_info'), $ad->bid_deadline(), $ad->bid_deadline_left()) }}</p>
                                        @endif

                                        {{-- <p>@lang('app.total_bids'): {{ $bids->count() }} </p> --}}
                                        @if(Auth::check() && Auth::user()->is_admin())
                                            @if($wonBid && $wonUser)
                                                @php 
                                                    $wonBidAmountWithTax = $wonBid->won_bid_amount + ($wonBid->won_bid_amount*7.7/100)
                                                @endphp
                                                <p>@lang('app.sold_to'): {{ $wonUser->user_name }}</p>
                                                <p>@lang('app.sold_for'): {{ themeqx_price($wonBid->won_bid_amount) }} inkl. MwSt <strong>{{ themeqx_price($wonBidAmountWithTax) }}</strong></p>
                                            @elseif ($ad->status == '4')
                                                <p>@lang('app.no_bids_after_deadline')</p>
                                            @endif
                                        @endif

                                        <div class="alert alert-warning">
                                            <h4>@lang('app.bid_closed')</h4>
                                            <p>@lang('app.cant_bid_anymore')</p>
                                        </div>

                                    @endif
                                @else 
                                    <div class="alert alert-warning">
                                        <h4>@lang('app.ad_not_published')</h4>
                                        <p>@lang('app.ad_not_published_warning')</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="widget">

                            @if($ad->category_type== 'jobs')
                                <h3>@lang('app.job_summery')</h3>
                                <p><span class="ad-info-name"><i class="fa fa-houzz"></i> @lang('app.employer')</span> <span class="ad-info-value company-name">{{ $ad->seller_name }}</span></p>
                                <p><span class="ad-info-name"><i class="fa fa-money"></i> @lang('app.salary')</span> <span class="ad-info-value">{{ themeqx_price_ng($ad->price) }}</span></p>
                                <p><span class="ad-info-name"><i class="fa fa-refresh"></i> @lang('app.mentioned_salary_will_be')</span> <span class="ad-info-value"> @lang('app.'.$ad->job->salary_will_be) </span></p>

                                <p><span class="ad-info-name"><i class="fa fa-newspaper-o"></i> @lang('app.published_on')</span> <span class="ad-info-value">{{ $ad->created_at->format('M d, Y') }}</span></p>
                                <p><span class="ad-info-name"><i class="fa fa-building-o"></i> @lang('app.job_nature')</span> <span class="ad-info-value">@lang('app.'.$ad->job->job_nature)</span></p>
                                <p>
                                    <span class="ad-info-name"><i class="fa fa-map-marker"></i> @lang('app.job_location')</span> <span class="ad-info-value">
                                        @if($ad->job->is_any_where)
                                            @lang('app.any_where_in')
                                        @else
                                            @if($ad->city)
                                                {!! $ad->city->city_name !!},
                                            @endif
                                            @if($ad->state)
                                                {!! $ad->state->state_name !!},
                                            @endif
                                        @endif
                                        {!! $ad->country->country_name !!}
                                    </span>
                                </p>

                                <p><span class="ad-info-name"><i class="fa fa-briefcase"></i> @lang('app.job_validity')</span> <span class="ad-info-value">@lang('app.'.$ad->job->job_validity)</span></p>


                                <p><span class="ad-info-name"><i class="fa fa-clock-o"></i> @lang('app.application_deadline')</span> <span class="ad-info-value">{{ date('M d, Y', strtotime($ad->job->application_deadline)) }}</span></p>

                            @else
                                {{-- <h3>@lang('app.general_info')</h3> --}}
                            @endif

                            {{-- <p><span class="ad-info-name"><i class="fa fa-calendar-check-o"></i> @lang('app.posted_at')</span> <span class="ad-info-value">{{$ad->posted_date()}}</span></p> --}}
                            {{-- <p><span class="ad-info-name"><i class="fa fa-calendar-check-o"></i> @lang('app.expires_on')</span> <span class="ad-info-value">{{ \Carbon\Carbon::parse($ad->expired_at)->formatLocalized(get_option('date_format')) }}</span></p> --}}

                            <div class="modern-social-share-btn-group">
                                <h4>@lang('app.share_this_ad')</h4>
                                <a href="#" class="btn btn-default share s_facebook"><i class="fa fa-facebook"></i> </a>
                                <a href="#" class="btn btn-default share s_plus"><i class="fa fa-google-plus"></i> </a>
                                <a href="#" class="btn btn-default share s_twitter"><i class="fa fa-twitter"></i> </a>
                                <a href="#" class="btn btn-default share s_linkedin"><i class="fa fa-linkedin"></i> </a>
                            </div>

                            <ul class="ad-action-list">
                                    <li>
                                        <a href="javascript:;" id="save_as_favorite" data-slug="{{ $ad->slug }}">
                                            @if( ! $ad->is_my_favorite())
                                                <i class="fa fa-star-o"></i> @lang('app.save_ad_as_favorite')
                                            @else
                                                <i class="fa fa-star"></i> @lang('app.remove_from_favorite')
                                            @endif
                                        </a>
                                    </li>

                                    @if( ! $ad->category_type== 'jobs')
                                        @if(! empty($ad->user) && $ad->user->email)
                                            <li><a href="#" data-toggle="modal" data-target="#replyByEmail"><i class="fa fa-envelope-o"></i> @lang('app.reply_by_email')</a></li>
                                        @endif
                                    @endif
                                </ul>

                        </div>

                        {{-- <div class="widget"> --}}

                            {{--<h3>@lang('app.seller_info')</h3>--}}
                            {{-- @if( ! empty($ad->user))
                                <div class="sidebar-user-info">
                                    <div class="ad-single-user-avatar">
                                        <img src="{{ $ad->user->get_gravatar() }}" class="img-circle img-responsive" />
                                    </div>

                                    <div class="ad-single-user-info">
                                        <h5>{{ $ad->user->user_name }}</h5>
                                        @php $user_address = $ad->user->get_address(); @endphp
                                        @if($user_address)
                                            <p class="text-muted"><i class="fa fa-map-marker"></i> {!! $user_address !!}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif --}}

                            {{-- <div class="sidebar-user-link">
                                @if( ! $ad->category_type== 'jobs')
                                    <button class="btn btn-block" id="onClickShowPhone">
                                        <strong> <span id="ShowPhoneWrap"></span> </strong> <br />
                                        <span class="text-muted">@lang('app.click_to_show_phone_number')</span>
                                    </button>
                                @endif

                            </div> --}}

                        {{-- </div> --}}

                        @if($related_ads->count() > 0 && get_option('enable_related_ads') == 1)
                            <div class="widget similar-ads">
                                <h3>@lang('app.next_article')</h3>

                                @foreach($related_ads as $rad)
                                    <div class="item-loop">

                                        <div class="ad-box">
                                            <div class="ad-box-caption-title">
												<h4>
													<span class="ad-box-title" href="{{ route('single_ad', [$rad->id, $rad->slug]) }}" title="{{ $rad->title }}">{{ $rad->title }}</span>
												</h4>
                                            </div>
                                            <div class="ads-thumbnail">
                                                <a href="{{ route('single_ad', [$rad->id, $rad->slug]) }}">
                                                    <img itemprop="image" src="{{ media_url($rad->feature_img, 'crop') }}" class="img-responsive" alt="{{ $rad->title }}">
                                                    <span class="modern-img-indicator">
                                                    @if(! empty($rad->video_url))
                                                            <i class="fa fa-file-video-o"></i>
                                                        @else
                                                            <i class="fa fa-file-image-o"> {{ $rad->media_img->count() }}</i>
                                                        @endif
                                                </span>
                                                </a>
                                            </div>
                                            <div class="bid-price">
												<div class="bid-number text-center">{{ $rad->bid_no }}</div>
												<div class="starting-price pull-left">{{ $rad->price ? __('app.starting_price') : __('app.buy_now_price') }} </div>
                                                <div class="pull-right">{{ $rad->price ? themeqx_price($rad->price) : themeqx_price($rad->buy_now_price) }}</div>
                                            </div>

                                            <div class="countdown" data-expire-date="{{$rad->expired_at}}" ></div>
                                            <div class="place-bid-btn">
                                                <a href="{{ route('single_ad', [$rad->id, $rad->slug]) }}" class="btn btn-primary">@lang('app.place_bid')</a>
                                            </div>

                                        </div>
                                        
                                    </div>
                                @endforeach
                            </div>

                        @endif


                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection

@section('page-js')
    <script src="{{ asset('assets/js/jquery.elevateZoom-3.0.8.min.js') }}"></script>
    <script src="{{ asset('assets/fancybox/jquery.fancybox.js') }}"></script>
    <script src="{{ asset('assets/plugins/SocialShare/SocialShare.js') }}"></script>
    <script src="{{ asset('assets/plugins/form-validator/form-validator.min.js') }}"></script>

    @if(!Auth::check())
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.26.11/sweetalert2.min.js"></script> 
        <script>
            swal(
                'Vorsicht!',
                '{!! __('app.before_bidding_sign_in_info') !!}',
                'warning'
            );
        </script>
    @endif

    <script>
        $('.share').ShareLink({
            title: '{{ $ad->title }}', // title for share message
            text: '{{ substr(trim(preg_replace('/\s\s+/', ' ',strip_tags($ad->description) )),0,160) }}', // text for share message

            @if($ad->media_img->first())
            image: '{{ media_url($ad->media_img->first(), true) }}', // optional image for share message (not for all networks)
            @else
            image: '{{ asset('uploads/placeholder.png') }}', // optional image for share message (not for all networks)
            @endif
            url: '{{  route('single_ad', [$ad->id, $ad->slug]) }}', // link on shared page
            class_prefix: 's_', // optional class prefix for share elements (buttons or links or everything), default: 's_'
            width: 640, // optional popup initial width
            height: 480 // optional popup initial height
        })
    </script>
    <script>
        $.validate();
    </script>

    <script>
        $(function(){
            $('#onClickShowPhone').click(function(){
                $('#ShowPhoneWrap').html('<i class="fa fa-phone"></i> {{ $ad->seller_phone }}');
            });

            $('#save_as_favorite').click(function(){
                var selector = $(this);
                var slug = selector.data('slug');

                $.ajax({
                    type : 'POST',
                    url : '{{ route('save_ad_as_favorite') }}',
                    data : { slug : slug, action: 'add',  _token : '{{ csrf_token() }}' },
                    success : function (data) {
                        if (data.status == 1){
                            selector.html(data.msg);
                        }else {
                            if (data.redirect_url){
                                location.href= data.redirect_url;
                            }
                        }
                    }
                });
            });

            $(document).on('click', '.comments-list .fa-reply', function(e){
                e.preventDefault();

                var comment_id = $(this).closest('.comment-box').attr('data-comment-id');
                var reply_form = $('.post-comments-form').html();
                reply_form += '<a href="javascript:;" class="text-danger reply_form_remove"><i class="fa fa-times"> </a>';

                //reply_form_box
                $(this).closest('.comment-box').find('.reply_form_box').html(reply_form).show().find('.comment_id').val(comment_id);

            });

            $(document).on('click', '.reply_form_remove', function(e) {
                e.preventDefault();
                $(this).closest('form').remove();
                $(this).closest('.reply_form_box').hide();
            });

            // increase or decrease price
            $('.btn-number').click(function(e){
                e.preventDefault();
                
                fieldName = $(this).attr('data-field');
                type      = $(this).attr('data-type');
                var input = $("input[name='"+fieldName+"']");
                var currentVal = toFloat(input.val());
                var increaser = parseFloat('{{ $ad->price_increaser }}');
                
                if (!isNaN(currentVal)) {
                    if(type == 'minus') {
                        if(currentVal > input.attr('min')) {
                            input.val(number_format(currentVal - increaser, 2)).change();
                        } 
                        if(toFloat(input.val()) == input.attr('min')) {
                            $(this).attr('disabled', true);
                        }
                    } else if(type == 'plus') {
                        input.val(number_format(currentVal + increaser, 2)).change();
                    }
                } else {
                    input.val(0);
                }
            });

            $('.input-number').change(function() {

                minValue =  parseInt($(this).attr('min'));
                valueCurrent = toFloat($(this).val());
                
                name = $(this).attr('name');
                if(valueCurrent >= minValue) {
                    $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled');
                    $(this).val(number_format(valueCurrent, 2));
                } else {
                    $(this).val(number_format(minValue, 2));
                }
                
            });

            $(".input-number").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                     // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) || 
                     // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                         // let it happen, don't do anything
                         return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });


            // init zoom
            $("#zoom_01").elevateZoom({
				gallery:'gallery_01',
				scrollZoom:'True'
			}); 

            //pass the images to Fancybox
            $("#zoom_01").bind("click", function(e) {  
              var ez =   $('#zoom_01').data('elevateZoom');
                $.fancybox(ez.getGalleryList());
              return false;
            });

            $('button[type="submit"]').not('#post-comment-button').click(function (e) {
                e.preventDefault();
                var buttonsToDisabled = $('button[type="submit"]').not('#post-comment-button');
                var that = $(this);
                var form = that.parent('form');
                var bidInput = form.find('input.bid-value');
                var biddedValue = parseFloat(bidInput.val());

                bidInput

                if (bidInput.is('#is-standard-bid')) {
                    // if ($('#is-buy-now-bid').length > 0) {
                        var buyNowBid = parseFloat($('#is-buy-now-bid').val());

                        if (!isNaN(buyNowBid) && biddedValue > buyNowBid) {
                            biddedValue = parseFloat(buyNowBid);
                        }
                    // }
                }

                var totalBiddedValue = biddedValue + (biddedValue*7.7/100);

                $('span.bidded-value').text(number_format(biddedValue, 2));
                $('span.bidded-value-total').text(number_format(totalBiddedValue, 2));

                $('#confirmBidModal').modal({ 
                    backdrop: 'static', 
                    keyboard: false 
                }).on('click', '#confirmBidButton', function() {
                    that.text('{{ __('app.please_wait') }}');
                    buttonsToDisabled.attr('disabled', true);
                    form.submit(); 

                    $('#confirmBidModal').modal('hide');
                });                    
            });
        });
    </script>
@endsection