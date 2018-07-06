@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('page-css')
	<link rel="stylesheet" href="{{ asset('/assets/css/image_select/Flat.css') }}">
	<link rel="stylesheet" href="{{ asset('/assets/css/image_select/ImageSelect.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.6/chosen.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@stop

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

                        {{ Form::open(['url' => route('store_event'), 'class' => 'form-horizontal', 'files'=>'true'] ) }}

                        <div class="form-group  {{ $errors->has('products')? 'has-error':'' }}">
	                        <label for="products" class="col-sm-4 control-label">@lang('app.products')</label>
	                        <div class="col-sm-8">
	                            <select class="form-control" id="products" name="products[]" multiple="multiple">
	                                @foreach($products as $product)
	                                    <option value="{{ $product->id }}" 
	                                    	data-img-src="{{ media_url($product->feature_img) }}">{{ $product->title }}</option>
	                                @endforeach
	                            </select>
	                            {!! $errors->has('products')? '<p class="help-block">'.$errors->first('products').'</p>':'' !!}
	                        </div>
	                    </div>

						<div class="form-group {{ $errors->has('title')? 'has-error':'' }}">
	                        <label for="title" class="col-sm-4 control-label">@lang('app.title')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="title" value="{{ old('title') }}" name="title" placeholder="@lang('app.title')">
                                {!! $errors->has('title')? '<p class="help-block">'.$errors->first('title').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('auctioner')? 'has-error':'' }}">
                            <label for="auctioner" class="col-sm-4 control-label">@lang('app.auctioner')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="auctioner" value="{{ old('auctioner') }}" name="auctioner" placeholder="@lang('app.auctioner')">
                                {!! $errors->has('auctioner')? '<p class="help-block">'.$errors->first('auctioner').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('address')? 'has-error':'' }}">
	                        <label for="address" class="col-sm-4 control-label">@lang('app.address')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="address" value="{{ old('address') }}" name="address" placeholder="@lang('app.address')">
                                {!! $errors->has('address')? '<p class="help-block">'.$errors->first('address').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('zip_code')? 'has-error':'' }}">
	                        <label for="address" class="col-sm-4 control-label">@lang('app.zip_code')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="zip_code" value="{{ old('zip_code') }}" name="zip_code" placeholder="@lang('app.zip_code')">
                                {!! $errors->has('zip_code')? '<p class="help-block">'.$errors->first('zip_code').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('city')? 'has-error':'' }}">
	                        <label for="address" class="col-sm-4 control-label">@lang('app.city')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="city" value="{{ old('city') }}" name="city" placeholder="@lang('app.city')">
                                {!! $errors->has('city')? '<p class="help-block">'.$errors->first('city').'</p>':'' !!}
                            </div>
                        </div>

{{--                         <div class="form-group {{ $errors->has('auction_begins')? 'has-error':'' }}">
                            <label for="products" class="col-sm-4 control-label">@lang('app.auction_begins')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" 
                                    id="auction_begins" 
                                    value="{{ old('auction_begins') }}" 
                                    name="auction_begins" 
                                    placeholder="@lang('app.auction_begins')">
                                {!! $errors->has('auction_begins')? '<p class="help-block">'.$errors->first('auction_begins').'</p>':'' !!}
                            </div>
                        </div> --}}

                        <div class="form-group {{ $errors->has('auction_deadline')? 'has-error':'' }}">
	                        <label for="auction_deadline" class="col-sm-4 control-label">@lang('app.bidding_deadline')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="auction_deadline" value="{{ old('auction_deadline') }}" name="auction_deadline" placeholder="@lang('app.bidding_deadline')">
                                {!! $errors->has('auction_deadline')? '<p class="help-block">'.$errors->first('auction_deadline').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('view_dates')? 'has-error':'' }}">
	                        <label for="view_dates" class="col-sm-4 control-label">@lang('app.view_dates')</label>
                            <div class="col-sm-8">
                                <textarea name="view_dates" id="view_dates" class="form-control" rows="6">{{ old('view_dates') }}</textarea>
                                {!! $errors->has('view_dates')? '<p class="help-block">'.$errors->first('view_dates').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('description')? 'has-error':'' }}">
	                        <label for="description" class="col-sm-4 control-label">@lang('app.description')</label>
                            <div class="col-sm-8">
                                <textarea name="description" id="description" class="form-control" rows="6">{{ old('description') }}</textarea>
                                {!! $errors->has('description')? '<p class="help-block">'.$errors->first('description').'</p>':'' !!}
                            </div>
                        </div>

	                    <div class="form-group">
	                        <div class="col-sm-offset-4 col-sm-8">
	                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> @lang('app.save')</button>
	                        </div>
	                    </div>
                        {{ Form::close() }}

                    </div>

                </div>


            </div>

        </div>

    </div>
@endsection

@section('page-js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.6/chosen.jquery.min.js"></script>
	<script src="{{ asset('assets/js/ImageSelect.jquery.js') }}"></script>

	<script>
        $(function () {
        	$('#auction_deadline, #auction_begins').datetimepicker({
	            format: 'DD-MM-YYYY HH:mm'
	        });
        })

	    $("#products").chosen();
	</script>
@endsection
