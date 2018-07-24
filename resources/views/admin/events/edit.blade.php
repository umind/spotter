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

                        {{ Form::open(['url' => route('update_event', $event->id), 'class' => 'form-horizontal', 'files'=>'true'] ) }}

                        <div class="form-group  {{ $errors->has('products')? 'has-error':'' }}">
                            <label for="products" class="col-sm-4 control-label">@lang('app.products')</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="products" name="products[]" multiple="multiple">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                            {{ in_array($product->id, $selectedProducts) ? 'selected' : '' }}
                                            data-img-src="{{ media_url($product->feature_img) }}">{{ $product->title }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->has('products')? '<p class="help-block">'.$errors->first('products').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('title')? 'has-error':'' }}">
                            <label for="products" class="col-sm-4 control-label">@lang('app.title')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="title"  value="{{ old('title')? old('title') : $event->title }}" name="title" placeholder="@lang('app.title')">
                                {!! $errors->has('title')? '<p class="help-block">'.$errors->first('title').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('auctioner')? 'has-error':'' }}">
                            <label for="auctioner" class="col-sm-4 control-label">@lang('app.auctioner')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="auctioner" value="{{ old('auctioner')? old('auctioner') : $event->auctioner }}" name="auctioner" placeholder="@lang('app.auctioner')">
                                {!! $errors->has('auctioner')? '<p class="help-block">'.$errors->first('auctioner').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('address')? 'has-error':'' }}">
                            <label for="products" class="col-sm-4 control-label">@lang('app.address')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="address" value="{{ old('address')? old('address') : $event->address }}" name="address" placeholder="@lang('app.address')">
                                {!! $errors->has('address')? '<p class="help-block">'.$errors->first('address').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('zip_code')? 'has-error':'' }}">
                            <label for="products" class="col-sm-4 control-label">@lang('app.zip_code')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="zip_code" value="{{ old('zip_code')? old('zip_code') : $event->zip_code }}" name="zip_code" placeholder="@lang('app.zip_code')">
                                {!! $errors->has('zip_code')? '<p class="help-block">'.$errors->first('zip_code').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('city')? 'has-error':'' }}">
                            <label for="products" class="col-sm-4 control-label">@lang('app.city')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="city" value="{{ old('city')? old('city') : $event->city }}" name="city" placeholder="@lang('app.city')">
                                {!! $errors->has('city')? '<p class="help-block">'.$errors->first('city').'</p>':'' !!}
                            </div>
                        </div>

{{--                         <div class="form-group {{ $errors->has('auction_begins')? 'has-error':'' }}">
                            <label for="products" class="col-sm-4 control-label">@lang('app.auction_begins')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" 
                                    id="auction_begins" 
                                    value="{{ old('auction_begins')? old('auction_begins') : Carbon\Carbon::parse($event->auction_begins)->format('d-m-y HH:i') }}" 
                                    name="auction_begins" 
                                    placeholder="@lang('app.auction_begins')">
                                {!! $errors->has('auction_begins')? '<p class="help-block">'.$errors->first('auction_begins').'</p>':'' !!}
                            </div>
                        </div> --}}

                        <div class="form-group {{ $errors->has('auction_deadline')? 'has-error':'' }}">
                            <label for="products" class="col-sm-4 control-label">@lang('app.auction_starts_to_end')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" 
                                    id="auction_deadline" 
                                    value="{{ old('auction_deadline')? old('auction_deadline') : Carbon\Carbon::parse($event->auction_ends)->format('d-m-y H:i') }}" 
                                    name="auction_deadline" 
                                    placeholder="@lang('app.auction_starts_to_end')">
                                {!! $errors->has('auction_deadline')? '<p class="help-block">'.$errors->first('auction_deadline').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('view_dates')? 'has-error':'' }}">
                            <label for="products" class="col-sm-4 control-label">@lang('app.view_dates')</label>
                            <div class="col-sm-8">
                                <textarea name="view_dates" id="view_dates" class="form-control" rows="6">{{ old('view_dates')? old('view_dates') : $event->view_dates }}</textarea>
                                {!! $errors->has('view_dates')? '<p class="help-block">'.$errors->first('view_dates').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('description')? 'has-error':'' }}">
                            <label for="products" class="col-sm-4 control-label">@lang('app.description')</label>
                            <div class="col-sm-8">
                                <textarea name="description" id="description" class="form-control" rows="6">{{ old('description')? old('description') : $event->description }}</textarea>
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

            $("#products").chosen();
        })
    </script>
@endsection
