@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')

    <div class="container">
		
		<div id="admin-panel" class="row">
			
			<div class="col-sm-5 col-md-4 col-lg-3">
				@include('admin.sidebar_menu')
			</div>

			<div class="col-sm-7 col-md-8 col-lg-9">

                @if( ! empty($title))
				    <h1 class="page-header">{{ $title }}</h1>
                @endif
                @include('admin.flash_msg')

                <div class="row">
                    <div class="col-xs-12">

                        @if($ads->total() > 0)
                            <table class="table table-bordered table-striped table-responsive">

                                @foreach($ads as $ad)
                                    @if($status == 'lost' && $ad->bids()->whereNotNull('won_bid_amount')->value('user_id') == Auth::id())
                                        @continue
                                    @endif

                                    <tr>
                                        <td width="100">
                                            <img src="{{ media_url($ad->feature_img) }}" class="thumb-listing-table" alt="">
                                        </td>
                                        <td>
                                            <h5>
                                                <a href="{{  route('single_ad', [$ad->id, $ad->slug]) }}" target="_blank">{{ $ad->bid_no }} / {{ $ad->title }}</a> 
                                                @if($user->is_admin())
                                                    ({!! $ad->status_context() !!})
                                                @endif
                                            </h5>
                                            <p class="text-muted">
                                                @php 
                                                    $event = $ad->events()->first(); 
                                                    $wonBidAmount = $ad->bids()->where('is_accepted', 1)->value('won_bid_amount');
                                                    $wonBidUserId = $ad->bids()->where('is_accepted', 1)->value('user_id');
                                                @endphp

                                                <i class="fa fa-calendar"></i> <span>@lang('app.event'):</span>
                                                @if($event)
                                                    <a href="{{ route('single_event', ['event' => $event->id]) }}" target="_blank">
                                                        <span>{{ $event->title }}</span>
                                                    </a>
                                                @else
                                                    <span>@lang('app.event_not_assigned')</span>
                                                @endif

                                                <br>

                                                @if(in_array($ad->status, ['0', '1']))
                                                    <i class="fa fa-clock-o"></i> <span>@lang('app.bidding_deadline'):</span> <span>{{ \Carbon\Carbon::parse($ad->expired_at)->formatLocalized(get_option('date_format')) }}</span>
                                                @endif

                                                @if($wonBidUserId)
                                                    @if($wonBidUserId == $user->id)
                                                        <i class="fa fa-clock-o"></i> @lang('app.bought_for'): {{ themeqx_price($wonBidAmount) }}
                                                    @endif
                                                    @if($user->is_admin())
                                                    <i class="fa fa-clock-o"></i> @lang('app.bought_for'): {{ themeqx_price($wonBidAmount) }}
                                                        @if($ad->paid)
                                                            <span class="label label-success">@lang('app.paid')</span>
                                                        @else
                                                            <span class="label label-danger">@lang('app.not_paid')</span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </p>
                                        </td>

                                        @if($user->is_admin())
                                            <td>
                                                @if(in_array($ad->status, ['1', '3']))
                                                    <a href="{{route('auction_bids', $ad->id)}}" class="btn btn-info" data-toggle="tooltip" title="@lang('app.bids')"><i class="fa fa-gavel"></i> {{$ad->bids->count()}} </a>
                                                @endif
                                                @if(in_array($ad->status, ['0', '1', '4']))
                                                    <a href="{{ route('edit_ad', $ad->id) }}" class="btn btn-primary"><i class="fa fa-edit"></i> </a>
                                                @endif
                                                @if($wonBidAmount && !$ad->paid)
                                                    <a href="javascript:;" class="btn btn-success changePaymentStatus" data-slug="{{ $ad->slug }}" data-value="1"><i class="fa fa-check-circle-o"></i> </a>
                                                @endif
                                                <a href="javascript:;" class="btn btn-danger deleteAds" data-slug="{{ $ad->slug }}"><i class="fa fa-trash"></i> </a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach

                            </table>
                        @endif

                        {!! $ads->appends(request()->input())->links() !!}

                    </div>
                </div>

            </div>
			
        </div>

    </div>
@endsection

@section('page-js')

    <script>
        $(document).ready(function() {
            $('.deleteAds').on('click', function () {
                if (!confirm('{{ trans('app.are_you_sure') }}')) {
                    return '';
                }
                var selector = $(this);
                var slug = selector.data('slug');
                $.ajax({
                    url: '{{ route('delete_ads') }}',
                    type: "POST",
                    data: {slug: slug, _token: '{{ csrf_token() }}'},
                    success: function (data) {
                        if (data.success == 1) {
                            selector.closest('tr').hide('slow');
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                        }
                    }
                });
            });
        });

        $('.changePaymentStatus').on('click', function () {
            if (!confirm('{{ trans('app.are_you_sure') }}')) {
                return '';
            }
            var selector = $(this);
            var slug = selector.data('slug');
            var value = selector.data('value');
            $.ajax({
                url: '{{ route('change_payment_status') }}',
                type: "POST",
                data: {slug: slug, value: value, _token: '{{ csrf_token() }}'},
                success: function (data) {
                    if (data.success == 1) {
                        selector.closest('tr').find('.label-danger').toggleClass('label-danger label-success').text('{{ __('app.paid') }}');
                        selector.remove();
                        toastr.success(data.msg, '@lang('app.success')', toastr_options);
                    }
                }
            });
        });
    </script>

    <script>
        @if(session('success'))
            toastr.success('{{ session('success') }}', '{{ trans('app.success') }}', toastr_options);
        @endif
        @if(session('error'))
            toastr.error('{{ session('error') }}', '{{ trans('app.success') }}', toastr_options);
        @endif
    </script>

@endsection