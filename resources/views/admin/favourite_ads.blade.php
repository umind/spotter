@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')
    <div class="container">

        <div id="admin-panel" class="row">
            
            <div class="col-sm-5 col-md-4 col-lg-3">
                @include('admin.sidebar_menu')
            </div>

            <div id="page-wrapper" class="col-sm-7 col-md-8 col-lg-9">
                @if( ! empty($title))
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header"> {{ $title }}  </h1>
                        </div> <!-- /.col-lg-12 -->
                    </div> <!-- /.row -->
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
                                            <h5><a href="{{  route('single_ad', [$ad->id, $ad->slug]) }}" target="_blank">{{ $ad->title }}</a> </h5>
                                            <p class="text-muted">
                                                @php $event = $ad->events()->first(); @endphp

                                                <i class="fa fa-clock-o"></i>
                                                <span>{{ Carbon\Carbon::parse($ad->expired_at)->isPast() ? trans('app.expired_on') : trans('app.expires_on') }}: 
                                                {{ Carbon\Carbon::parse($ad->expired_at)->format('F d Y, H:i') }}</span>
                                                <br>
                                                <i class="fa fa-calendar"></i> <span>@lang('app.event'):</span>
                                                <a href="{{ route('single_event', ['event' => $event->id]) }}" target="_blank">
                                                     <span>{{ $event->title }}</span>
                                                </a>
                                            </p>
                                        </td>

                                    </tr>
                                @endforeach
                            </table>
                        @endif
                        {!! $ads->links() !!}
                    </div>
                </div>
            </div>   <!-- /#page-wrapper -->

        </div>   <!-- /#wrapper -->

    </div> <!-- /#container -->
@endsection

@section('page-js')

    <script>
        @if(session('success'))
            toastr.success('{{ session('success') }}', '{{ trans('app.success') }}', toastr_options);
        @endif
        @if(session('error'))
            toastr.error('{{ session('error') }}', '{{ trans('app.success') }}', toastr_options);
        @endif
    </script>

@endsection