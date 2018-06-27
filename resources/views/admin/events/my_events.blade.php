@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')

    <div class="container">
        <div id="wrapper">

            @include('admin.sidebar_menu')

            <div id="page-wrapper">
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

                        @if($events->total() > 0)
                            <table class="table table-bordered table-striped table-responsive">

                                @foreach($events as $event)
                                    <tr>
                                        <td width="100">
                                            <img src="{{ media_url($event->feature_img) }}" class="thumb-listing-table" alt="">
                                        </td>
                                        <td>
                                            <h5><a href="{{  route('single_event', ['event' => $event->id]) }}" target="_blank">{{ $event->title }}</a> ({!! $event->status_context() !!})</h5>
                                        </td>

                                        <td>
                                            <a href="{{ route('edit_event', $event->id) }}" class="btn btn-primary"><i class="fa fa-edit"></i> </a>
                                            <a href="javascript:;" class="btn btn-danger deleteAds" data-event="{{ $event->id }}"><i class="fa fa-trash"></i> </a>
                                        </td>
                                    </tr>
                                @endforeach

                            </table>
                        @endif

                        {!! $events->links() !!}

                    </div>
                </div>

            </div>   <!-- /#page-wrapper -->

        </div>   <!-- /#wrapper -->


    </div> <!-- /#container -->
@endsection

@section('page-js')

    <script>
        $(document).ready(function() {
            $('.deleteAds').on('click', function () {
                if (!confirm('{{ trans('app.are_you_sure') }}')) {
                    return '';
                }
                var selector = $(this);
                var event = selector.data('event');
                $.ajax({
                    url: '{{ route('delete_event') }}',
                    type: "POST",
                    data: {event: event, _token: '{{ csrf_token() }}'},
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