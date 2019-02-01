@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection


@section('page-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')

    <div class="container">

		<div id="admin-panel" class="row">
			
			<div class="col-sm-5 col-md-4 col-lg-3">
				@include('admin.sidebar_menu')
			</div>

			<div class="col-sm-7 col-md-8 col-lg-9">
                @if( ! empty($title))
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header"> {{ $title }}  </h1>
                        </div> <!-- /.col-lg-12 -->
                    </div> <!-- /.row -->
                @endif

                @include('admin.flash_msg')

                <div class="row">
                    <div class="col-md-10 col-xs-12">

                        {{ Form::open(['id'=>'adsPostForm', 'class' => 'form-horizontal', 'files' => true]) }}

                        <legend> <span class="ad_text"> @lang('app.ad') </span> @lang('app.info')</legend>

                        <div class="form-group  {{ $errors->has('event')? 'has-error':'' }}">
                            <label for="event" class="col-sm-4 control-label">@lang('app.event')</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="event" name="event">
                                    <option value="">@lang('app.choose_event')</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}" {{ in_array($event->id, $ad->events()->pluck('event_id')->toArray()) ? 'selected' : '' }}>{{ $event->title }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->has('event')? '<p class="help-block">'.$errors->first('event').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('ad_title')? 'has-error':'' }}">
                            <label for="ad_title" class="col-sm-4 control-label">@lang('app.ad_title')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="ad_title" value="{{ old('ad_title') ? old('ad_title') : $ad->title }}" name="ad_title" placeholder="@lang('app.ad_title')">
                                {!! $errors->has('ad_title')? '<p class="help-block">'.$errors->first('ad_title').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('bid_no')? 'has-error':'' }}">
                            <label for="bid_no" class="col-sm-4 control-label">@lang('app.bid_no')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="bid_no" value="{{ old('bid_no') ? old('bid_no') : $ad->bid_no }}" name="bid_no" placeholder="@lang('app.bid_no')">
                                {!! $errors->has('bid_no')? '<p class="help-block">'.$errors->first('bid_no').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('auction_no')? 'has-error':'' }}">
                            <label for="auction_no" class="col-sm-4 control-label">@lang('app.auction_no')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="auction_no" value="{{ old('auction_no') ? old('auction_no') : $ad->auction_no }}" name="auction_no" placeholder="@lang('app.auction_no')">
                                {!! $errors->has('auction_no')? '<p class="help-block">'.$errors->first('auction_no').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('ad_description')? 'has-error':'' }}">
                            <label class="col-sm-4 control-label"><span class="ad_text"> @lang('app.ad') </span> @lang('app.description')</label>
                            <div class="col-sm-8">
                                <textarea name="ad_description" class="form-control" id="content_editor" rows="8">{{ old('ad_description')?  old('ad_description') : $ad->description }}</textarea>
                                {!! $errors->has('ad_description')? '<p class="help-block">'.$errors->first('ad_description').'</p>':'' !!}
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group  {{ $errors->has('price')? 'has-error':'' }}">
                                    <label for="price" class="col-md-6 col-md-offset-1 control-label"> <span class="price_text">@lang('app.starting_price')</span> </label>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">{{ get_option('currency_sign') }}</span>
                                            <input type="text" placeholder="@lang('app.starting_price')" class="form-control" name="price" id="price" value="{{ old('price') ? old('price') : $ad->price }}">
                                        </div>
                                    </div>

                                    <div class="col-md-5 col-md-offset-7">
                                        {!! $errors->has('price')? '<p class="help-block">'.$errors->first('price').'</p>':'' !!}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-5">
                                <div class="form-group {{ $errors->has('buy_now_price')? 'has-error':'' }}">
                                    <label for="buy_now_price" class="text-left col-md-4 control-label"> <span class="price_text">@lang('app.buy_now_price')</span> </label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">{{ get_option('currency_sign') }}</span>
                                            <input type="text" placeholder="@lang('app.buy_now_price')" class="form-control" name="buy_now_price" id="buy_now_price" value="{{ old('buy_now_price') ? old('buy_now_price') : $ad->buy_now_price }}">
                                        </div>
                                    </div>

                                    <div class="col-md-8 col-md-offset-4">
                                        {!! $errors->has('buy_now_price')? '<p class="help-block">'.$errors->first('buy_now_price').'</p>':'' !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group  {{ $errors->has('price_increaser')? 'has-error':'' }}">
                            <label for="price_increaser" class="col-md-4 control-label"> <span class="price_increaser_text">@lang('app.price_increaser')</span> </label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon">{{ get_option('currency_sign') }}</span>
                                    <input type="text" placeholder="@lang('app.price_increaser')" class="form-control" name="price_increaser" id="price_increaser" value="{{ old('price_increaser')? old('price_increaser') : $ad->price_increaser }}">
                                </div>
                            </div>

                            <div class="col-sm-8 col-md-offset-4">
                                {!! $errors->has('price_increaser')? '<p class="help-block">'.$errors->first('price_increaser').'</p>':'' !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->has('bid_deadline')? 'has-error':'' }}">
                            <label for="bid_deadline" class="col-sm-4 control-label"> @lang('app.bid_deadline')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="bid_deadline" value="{{ old('bid_deadline')? old('bid_deadline') : Carbon\Carbon::parse($ad->expired_at)->format('H:i') }}" name="bid_deadline" placeholder="@lang('app.bid_deadline')">
                                {!! $errors->has('bid_deadline')? '<p class="help-block">'.$errors->first('bid_deadline').'</p>':'' !!}
                            </div>
                        </div>

                        <legend>@lang('app.image')</legend>

                        <div class="form-group {{ $errors->has('images')? 'has-error':'' }}">
                            <div class="col-sm-12">

                                <div id="uploaded-ads-image-wrap">
                                    @if($ad->media_img->count() > 0)
                                        @foreach($ad->media_img as $img)
                                            <div class="creating-ads-img-wrap">
                                                <img src="{{ media_url($img, 'crop') }}" class="img-responsive" />
                                                <div class="img-action-wrap" id="{{ $img->id }}">
                                                    <a href="javascript:;" class="imgDeleteBtn"><i class="fa fa-trash-o"></i> </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>


                                <div class="col-sm-8 col-sm-offset-4">
                                    <div class="upload-images-input-wrap">
                                        <input type="file" name="images[]" class="form-control" />
                                        <input type="file" name="images[]" class="form-control" />
                                    </div>

                                    <div class="image-ad-more-wrap">
                                        <a href="javascript:;" class="image-add-more"><i class="fa fa-plus-circle"></i> @lang('app.add_more')</a>
                                    </div>
                                </div>
                                {!! $errors->has('images')? '<p class="help-block">'.$errors->first('images').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('video_url')? 'has-error':'' }}">
                            <label for="ad_title" class="col-sm-4 control-label">@lang('app.video_url')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="video_url" value="{{ old('video_url')? old('video_url') : $ad->video_url }}" name="video_url" placeholder="@lang('app.video_url')">
                                {!! $errors->has('video_url')? '<p class="help-block">'.$errors->first('video_url').'</p>':'' !!}
                                <p class="help-block">@lang('app.video_url_help')</p>
                                <p class="text-info">@lang('app.video_url_help_for_modern_theme')</p>
                            </div>
                        </div>


                        {{-- <legend>@lang('app.location_info')</legend>

                        <div class="form-group  {{ $errors->has('country')? 'has-error':'' }}">
                            <label for="category_name" class="col-sm-4 control-label">@lang('app.country')</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" name="country">
                                    <option value="">@lang('app.select_a_country')</option>

                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ $ad->country_id == $country->id ? 'selected' :'' }}>{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->has('country')? '<p class="help-block">'.$errors->first('country').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group  {{ $errors->has('state')? 'has-error':'' }}">
                            <label for="category_name" class="col-sm-4 control-label">@lang('app.state')</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" id="state_select" name="state">
                                    @if($previous_states->count() > 0)
                                        @foreach($previous_states as $state)
                                        <option value="{{ $state->id }}" {{ $ad->state_id == $state->id ? 'selected' :'' }}>{{ $state->state_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p class="text-info">
                                    <span id="state_loader" style="display: none;"><i class="fa fa-spin fa-spinner"></i> </span>
                                </p>
                            </div>
                        </div>

                        <div class="form-group  {{ $errors->has('city')? 'has-error':'' }}">
                            <label for="category_name" class="col-sm-4 control-label">@lang('app.city')</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" id="city_select" name="city">
                                    @if($previous_cities->count() > 0)
                                        @foreach($previous_cities as $city)
                                        <option value="{{ $city->id }}" {{ $ad->city_id == $city->id ? 'selected':'' }}>{{ $city->city_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p class="text-info">
                                    <span id="city_loader" style="display: none;"><i class="fa fa-spin fa-spinner"></i> </span>
                                </p>
                            </div>
                        </div> --}}

{{--                         <legend><span class="seller_text"> @lang('app.seller') </span> @lang('app.info')</legend>

                        <div class="form-group {{ $errors->has('seller_name')? 'has-error':'' }}">
                            <label for="seller_name" class="col-sm-4 control-label"> <span class="seller_text"> @lang('app.seller') </span> @lang('app.name')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="seller_name" value="{{ old('seller_name')? old('seller_name') : $ad->seller_name }}" name="seller_name" placeholder="@lang('app.seller_name')">
                                {!! $errors->has('seller_name')? '<p class="help-block">'.$errors->first('seller_name').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('seller_email')? 'has-error':'' }}">
                            <label for="seller_email" class="col-sm-4 control-label">  <span class="seller_text"> @lang('app.seller') </span> @lang('app.email')</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="seller_email" value="{{ old('seller_email')? old('seller_email') : $ad->seller_email }}" name="seller_email" placeholder="@lang('app.seller_email')">
                                {!! $errors->has('seller_email')? '<p class="help-block">'.$errors->first('seller_email').'</p>':'' !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->has('seller_phone')? 'has-error':'' }}">
                            <label for="seller_phone" class="col-sm-4 control-label">  <span class="seller_text"> @lang('app.seller') </span> @lang('app.phone')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="seller_phone" value="{{ old('seller_phone') ? old('seller_phone') : $ad->seller_phone }}" name="seller_phone" placeholder="@lang('app.seller_phone')">
                                {!! $errors->has('seller_phone')? '<p class="help-block">'.$errors->first('seller_phone').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('address')? 'has-error':'' }}">
                            <label for="address" class="col-sm-4 control-label">@lang('app.address')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="address" value="{{ old('address')? old('address') : $ad->address }}" name="address" placeholder="@lang('app.address')">
                                {!! $errors->has('address')? '<p class="help-block">'.$errors->first('address').'</p>':'' !!}
                            </div>
                        </div> --}}

                        <hr />

                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary">@lang('app.edit_ad')</button>
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

    <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'content_editor' );
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('#bid_deadline').datetimepicker({
                format: 'HH:mm'
            });
        })
    </script>

    <script>

        function generate_option_from_json(jsonData, fromLoad){

            //Load Category Json Data To Brand Select
            if (fromLoad === 'category_to_brand'){
                var option = '';
                if (jsonData.length > 0) {
                    option += '<option value="0" selected> <?php echo trans('app.select_a_brand') ?> </option>';
                    for ( i in jsonData){
                        option += '<option value="'+jsonData[i].id+'"> '+jsonData[i].brand_name +' </option>';
                    }
                    $('#brand_select').html(option);
                    $('#brand_select').select2();
                }else {
                    $('#brand_select').html('');
                    $('#brand_select').select2();
                }
                $('#brand_loader').hide('slow');
            }else if(fromLoad === 'country_to_state'){
                var option = '';
                if (jsonData.length > 0) {
                    option += '<option value="0" selected> @lang('app.select_state') </option>';
                    for ( i in jsonData){
                        option += '<option value="'+jsonData[i].id+'"> '+jsonData[i].state_name +' </option>';
                    }
                    $('#state_select').html(option);
                    $('#state_select').select2();
                }else {
                    $('#state_select').html('');
                    $('#state_select').select2();
                }
                $('#state_loader').hide('slow');

            }else if(fromLoad === 'state_to_city'){
                var option = '';
                if (jsonData.length > 0) {
                    option += '<option value="0" selected> @lang('app.select_city') </option>';
                    for ( i in jsonData){
                        option += '<option value="'+jsonData[i].id+'"> '+jsonData[i].city_name +' </option>';
                    }
                    $('#city_select').html(option);
                    $('#city_select').select2();
                }else {
                    $('#city_select').html('');
                    $('#city_select').select2();
                }
                $('#city_loader').hide('slow');
            }
        }


        $(document).ready(function(){

            $('#event').change(function () {
                $.ajax({
                    type : 'get',
                    url : '{{ route('get_event_time') }}',
                    data : { event_id : $(this).val() },
                    success : function (data) {
                        if (data.success) {
                            $('#bid_deadline').val(data.eventTime);
                        }
                    }
                });
            });

            $('[name="country"]').change(function(){
                var country_id = $(this).val();
                $('#state_loader').show();
                $.ajax({
                    type : 'POST',
                    url : '{{ route('get_state_by_country') }}',
                    data : { country_id : country_id,  _token : '{{ csrf_token() }}' },
                    success : function (data) {
                        generate_option_from_json(data, 'country_to_state');
                    }
                });
            });

            $('[name="state"]').change(function(){
                var state_id = $(this).val();
                $('#city_loader').show();
                $.ajax({
                    type : 'POST',
                    url : '{{ route('get_city_by_state') }}',
                    data : { state_id : state_id,  _token : '{{ csrf_token() }}' },
                    success : function (data) {
                        generate_option_from_json(data, 'state_to_city');
                    }
                });
            });

            $('body').on('click', '.imgDeleteBtn', function(){
                //Get confirm from user
                if ( ! confirm('{{ trans('app.are_you_sure') }}')){
                    return '';
                }

                var current_selector = $(this);
                var img_id = $(this).closest('.img-action-wrap').attr('id');
                $.ajax({
                    url : '{{ route('delete_media') }}',
                    type: "POST",
                    data: { media_id : img_id, _token : '{{ csrf_token() }}' },
                    success : function (data) {
                        if (data.success == 1){
                            current_selector.closest('.creating-ads-img-wrap').hide('slow');
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                        }
                    }
                });
            });

            $(document).on('click', '.image-add-more', function (e) {
                e.preventDefault();
                $('.upload-images-input-wrap').append('<input type="file" name="images[]" class="form-control" />');
            });

        });
    </script>


    <script>
        @if(session('success'))
            toastr.success('{{ session('success') }}', '<?php echo trans('app.success') ?>', toastr_options);
        @endif
    </script>
@endsection