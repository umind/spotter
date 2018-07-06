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
                        @if($events->total() > 0)
                            <table class="table table-bordered table-striped table-responsive">

                                @foreach($events as $event)
                                    <tr>
                                        <td width="100">
                                            <div class="images-rotation" data-images='[{{ rotationImages($event->auctions) }}]'>
                                                <img class="primary-img thumb-listing-table" src="{{ media_url($event->auctions()->first()->feature_img) }}" alt="primary image" />
                                            </div>
                                        </td>
                                        <td>
                                            <h5><a href="{{  route('single_event', [$event->id]) }}" target="_blank">{{ $event->title }}</a></h5>
                                            
                                            <p class="text-muted">
                                                <i class="fa fa-user"></i> <span>@lang('app.auctioner'):</span> <span>{{ $event->auctioner }}</span>
                                                <br>
                                                {{-- <i class="fa fa-clock-o"></i> <span>@lang('app.begins'):</span> <span>{{ \Carbon\Carbon::parse($event->auction_begins)->formatLocalized(get_option('date_format')) }}</span>
                                                <br> --}}
                                                <i class="fa fa-clock-o"></i> <span>@lang('app.expired_on'):</span> <span>{{ \Carbon\Carbon::parse($event->auction_ends)->formatLocalized(get_option('date_format')) }}</span>
                                                <br>
                                                <i class="fa fa-map-marker"></i> <span>@lang('app.venue'):</span> <span>{{ $event->address . ', ' . $event->zip_code . ' ' . $event->city }}</span>
                                            </p>
                                        </td>

                                        <td>
{{--                                             <a href="{{ route('edit_event', ['event' => $event->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i> </a> --}}
                                            <a href="{{ route('close_event', $event->id) }}" onclick="return confirm('{{ trans('app.are_you_sure') }}');" class="btn btn-danger"><i class="fa fa-close"></i> </a>
                                            <a href="javascript:;" class="btn btn-danger deleteAds" data-event="{{ $event->id }}"><i class="fa fa-trash"></i> </a>
                                        </td>
                                    </tr>
                                @endforeach

                            </table>
                        @endif

                        {!! $events->links() !!}

                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection

@section('page-js')
<script src="{{ asset('assets/js/jquery.images-rotation.js') }}"></script>
    <script>
        $(document).ready(function() {

            // init image rotation
            $('.images-rotation').imagesRotation();

            $('.deleteAds').on('click', function () {
                if (!confirm('{{ trans('app.are_you_sure') }}')) {
                    return '';
                }
                var selector = $(this);
                var event = selector.data('event');
                $.ajax({
                    url: '{{ route('delete_event') }}',
                    type: "POST",
                    data: {slug: event, _token: '{{ csrf_token() }}'},
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