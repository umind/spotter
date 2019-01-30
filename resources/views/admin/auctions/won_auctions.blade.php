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
				<h1 class="page-header"> {{ $title }}  </h1>
                @endif
                @include('admin.flash_msg')

                <div class="row">
                    <div class="col-xs-12">

                        @if($ads->total() > 0)
                            <table class="table table-bordered table-striped table-responsive">

                                @foreach($ads as $ad)
                                    <tr>
                                        <td width="100">
                                            <img src="{{ media_url($ad->feature_img) }}" class="thumb-listing-table" alt="">
                                        </td>
                                        <td>
                                            <h5><a href="{{  route('single_ad', [$ad->id, $ad->slug]) }}" target="_blank">{{ $ad->title }}</a></h5>
                                            <p class="text-muted">
                                                @php 
                                                    $event = $ad->events()->first(); 
                                                    $wonBid = $ad->bids()->where('is_accepted', 1)->first();
                                                @endphp

                                                <i class="fa fa-clock-o"></i> @lang('app.bought_for'): {{ themeqx_price($wonBid->won_bid_amount) }}
                                                <br>
                                                <i class="fa fa-calendar"></i> <span>@lang('app.event'):</span>
                                                @if($event)
                                                    <a href="{{ route('single_event', ['event' => $event->id]) }}" target="_blank">
                                                         <span>{{ $event->title }}</span>
                                                    </a>
                                                @else
                                                    <span>@lang('app.event_not_assigned')</span>
                                                @endif
                                            </p>
                                        </td>

                                        @can('view-invoice', [$ad, $wonBid])
                                            <td>    
                                                <a 
                                                    href="{{ route('invoice', $ad->id) }}" 
                                                    class="btn btn-primary" 
                                                    title="Invoice" 
                                                    target="_blank">
                                                    <i class="fa fa-file"></i>
                                                </a>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach

                            </table>
                        @endif

                        {!! $ads->links() !!}

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