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
						<div class="table-scrollable">

							@if($ads->total() > 0)
								<table class="table table-bordered table-striped table-responsive">

									@foreach($ads as $ad)
										<tr>
											<td width="100">
												<img src="{{ media_url($ad->feature_img) }}" class="thumb-listing-table" alt="">
											</td>
											<td class="info-text">
												<h5><a href="{{  route('single_ad', [$ad->id, $ad->slug]) }}" target="_blank">{{ $ad->title }}</a></h5>
												<p class="text-muted">
													@php $event = $ad->events()->first(); @endphp

													<i class="fa fa-clock-o"></i> @lang('app.expires_on'): {{ Carbon\Carbon::parse($ad->expired_at)->formatLocalized(get_option('date_format')) }}
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

											@php
												$maxBidObj = $ad->bids()->whereNotNull('max_bid_amount')->first();
												$highestBidObj = $ad->bids()->whereNotNull('bid_amount')->orderBy('bid_amount', 'desc')->first();
												$userHighestBid = Auth::user()->bids()->where('ad_id', $ad->id)->max('bid_amount');
											@endphp

											<td class="prices">
												<p>Ihr aktuelles Gebot</p>
												@if($maxBidObj && $maxBidObj->user_id == Auth::id())
													<p>Ihr maximum Gebot</p>
												@endif
												@if($highestBidObj->user_id != Auth::id())
													<p>überboten</p>
												@endif
											</td>
											<td class="prices number">
												<p>{{ number_format($userHighestBid, 2) }}</p>
												@if($maxBidObj && $maxBidObj->user_id == Auth::id())
													<p>{{ number_format($maxBidObj->max_bid_amount, 2) }}</p>
												@endif
												@if($highestBidObj->user_id != Auth::id())
													<p>{{ number_format($highestBidObj->bid_amount, 2) }}</p>
												@endif
											</td>
											<td class="status">
<!--
												<div class="white-status">
													<img src="{{ asset('assets/img/white.png') }}" alt="assets/img/white.png" />
												</div>
-->
												@if($highestBidObj->user_id == Auth::id())
												<div class="green-status">
													<img src="{{ asset('assets/img/green.png') }}" alt="assets/img/green.png" />
												</div>
												@else
												<div class="red-status">
													<img src="{{ asset('assets/img/red.png') }}" alt="assets/img/red.png" />
												</div>
												@endif
												@if($maxBidObj && $maxBidObj->user_id == Auth::id())
												<div class="green-status">
													<img src="{{ asset('assets/img/green.png') }}" alt="assets/img/green.png" />
												</div>
												@endif
												@if($highestBidObj->user_id != Auth::id())
												<div class="red-status">
													<img src="{{ asset('assets/img/red.png') }}" alt="assets/img/red.png" />
												</div>
												@endif
											</td>

											{{-- <td>
												<a href="{{ route('edit_ad', $ad->id) }}" class="btn btn-primary"><i class="fa fa-edit"></i> </a>
												<a href="javascript:;" class="btn btn-danger deleteAds" data-slug="{{ $ad->slug }}"><i class="fa fa-trash"></i> </a>
											</td> --}}
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