@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')

    <div class="container">

    @if( ! $ad->is_bid_accepted())      
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">@lang('app.winning_amount')</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['route'=> 'bid_action', 'class' => 'form-inline']) !!}
                    <div class="form">
                        <div class="input-group max-bid">
                            <input type="text" name="won_bid_amount" class="form-control input-number">
                      </div>
                  </div>
                <button type="button" class="btn btn-danger action" data-ad-id="" data-bid-id="" data-action="accept">@lang('app.winning_amount')</button>
              {!! Form::close() !!}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @endif

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

                        @if($ad->bids->count())
                            <table class="table table-striped">
                                <tr>
                                    <th>@lang('app.bidder')</th>
                                    <th>@lang('app.bid_amount')</th>
                                    <th>@lang('app.date_time')</th>
                                    <th>#</th>
                                </tr>
                                @foreach($ad->bids as $bid)
                                    <tr>
                                        <td><a href="{{route('bidder_info', $bid->id)}}">{{ $bid->user->user_name }}</a> </td>
                                        <td>{{ $bid->max_bid_amount > $bid->bid_amount ? themeqx_price($bid->max_bid_amount) : themeqx_price($bid->bid_amount) }}
                                            @if($bid->is_accepted)
                                                <span class="label label-success">@lang('app.sold_for'): {{ number_format($bid->won_bid_amount, 2) }}</span>
                                            @elseif(!$bid->is_accepted && $ad->current_bid() == $bid->bid_amount)
                                                <span class="label label-success">@lang('app.highest_bid')</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($bid->created_at)->format('F d Y, H:i') }}</td>
                                        <td>
                                            @if( ! $ad->is_bid_accepted())
                                                <a class="btn btn-success accept_bid" data-ad-id="{{$ad->id}}" data-bid-id="{{$bid->id}}" data-toggle="modal" data-target="#myModal"><i class="fa fa-check-circle-o"></i> </a>
                                            @endif

                                            <a href="javascript:;" class="btn btn-danger action" data-ad-id="{{$ad->id}}" data-bid-id="{{$bid->id}}"  data-action="delete"><i class="fa fa-trash-o"></i> </a>
                                        </td>
                                    </tr>
                                @endforeach

                            </table>
                        @else
                            <p>@lang('app.there_is_no_bids')</p>
                        @endif

                    </div>
                </div>

            </div>   <!-- /#page-wrapper -->

        </div>   <!-- /#wrapper -->


    </div> <!-- /#container -->
@endsection

@section('page-js')
    <script>
        $(document).ready(function() {
            $('.accept_bid').on('click', function () {
                var selector = $(this);
                var bid_id = selector.data('bid-id');
                var ad_id = selector.data('ad-id');

                $('button.action').attr('data-bid-id', bid_id);
                $('button.action').attr('data-ad-id', ad_id);
            });

            $('.action').on('click', function () {
                var selector = $(this);
                var action = selector.data('action');

                if (action === 'delete'){
                    if (!confirm('{{ trans('app.are_you_sure') }}')) {
                        return;
                    }
                }

                var ad_id = selector.data('ad-id');
                var bid_id = selector.data('bid-id');
                var won_bid_amount = $('input.input-number').val();

                $.ajax({
                    url: '{{ route('bid_action') }}',
                    type: "POST",
                    data: {ad_id: ad_id, bid_id:bid_id, action:action, _token: '{{ csrf_token() }}', won_bid_amount: won_bid_amount},
                    success: function (data) {
                        if (data.success == 1) {
                            if (action === 'delete') {
                                selector.closest('tr').remove();
                            }else if (action === 'accept'){
                                $('#myModal').modal('hide');
                                $('form')[0].reset();
                                $('.btn-success.action').remove();
                                // reload
                                location.reload();
                            }
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                        }
                    }
                });
            });
        });

    </script>


@endsection