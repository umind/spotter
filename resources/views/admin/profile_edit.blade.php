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

                        {!! Form::open(['url' => route('update_user', $user->id), 'class'=>'form-horizontal', 'files'=>'true']) !!}

                        <div class="form-group {{ $errors->has('title')? 'has-error':'' }}">
                            <label for="title" class="col-sm-4 control-label">@lang('app.anrede')</label>
                            <div class="col-sm-8">
                                @foreach(getArray('title_types') as $key => $titleType)
                                    <label class="radio-button">
                                        <input id="title" type="radio" class="form-control" name="title" value="{{ $key }}" {{ old('title') == $key ? 'checked' : '' }} {{ $user->title == $key && old('title') != $key ? 'checked' : '' }}> {{ $titleType }}
                                    </label>
                                @endforeach
                                {!! $errors->has('title')? '<p class="help-block">'.$errors->first('title').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="company-info {{ old('title') != 2 && $user->title != '2' ? 'hidden' : '' }}">
                            <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                                <label for="company_name" class="col-md-4 control-label">@lang('app.company_name')</label>

                                <div class="col-md-8">
                                    <input id="company_name" type="text" class="form-control" name="company_name" value="{{ old('company_name') ? old('company_name') : $user->company_name }}"  autofocus>

                                    @if ($errors->has('company_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('company_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('company_vat') ? ' has-error' : '' }}">
                                <label for="company_vat" class="col-md-4 control-label">@lang('app.company_vat')</label>

                                <div class="col-md-8">
                                    <input id="company_vat" type="text" class="form-control" name="company_vat" value="{{ old('company_vat') ? old('company_vat') : $user->company_vat }}"  autofocus>

                                    @if ($errors->has('company_vat'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('company_vat') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('company_no') ? ' has-error' : '' }}">
                                <label for="company_no" class="col-md-4 control-label">@lang('app.company_no')</label>

                                <div class="col-md-8">
                                    <input id="company_no" type="text" class="form-control" name="company_no" value="{{ old('company_no') ? old('company_no') : $user->company_no }}"  autofocus>

                                    @if ($errors->has('company_no'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('company_no') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('first_name')? 'has-error':'' }}">
                            <label for="first_name" class="col-sm-4 control-label">@lang('app.first_name')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="first_name" value="{{ old('first_name')? old('first_name') : $user->first_name }}" name="first_name" placeholder="@lang('app.first_name')">
                                {!! $errors->has('first_name')? '<p class="help-block">'.$errors->first('first_name').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('last_name')? 'has-error':'' }}">
                            <label for="last_name" class="col-sm-4 control-label">@lang('app.last_name')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="last_name" value="{{ old('last_name')? old('last_name') : $user->last_name }}" name="last_name" placeholder="@lang('app.last_name')">
                                {!! $errors->has('last_name')? '<p class="help-block">'.$errors->first('last_name').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('address')? 'has-error':'' }}">
                            <label for="address" class="col-sm-4 control-label">@lang('app.address')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="address" value="{{ old('address')? old('address') : $user->address }}" name="address" placeholder="@lang('app.address')">
                                {!! $errors->has('address')? '<p class="help-block">'.$errors->first('address').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('zip_code')? 'has-error':'' }}">
                            <label for="zip_code" class="col-sm-4 control-label">@lang('app.zip_code')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="zip_code" value="{{ old('zip_code')? old('zip_code') : $user->zip_code }}" name="zip_code" placeholder="@lang('app.zip_code')">
                                {!! $errors->has('zip_code')? '<p class="help-block">'.$errors->first('zip_code').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('city')? 'has-error':'' }}">
                            <label for="city" class="col-sm-4 control-label">@lang('app.city')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="city" value="{{ old('city')? old('city') : $user->city }}" name="city" placeholder="@lang('app.city')">
                                {!! $errors->has('city')? '<p class="help-block">'.$errors->first('city').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('country_id')? 'has-error':'' }}">
                            <label for="phone" class="col-sm-4 control-label">@lang('app.country')</label>
                            <div class="col-sm-8">
                                <select id="country_id" name="country_id" class="form-control select2">
                                    <option value="">@lang('app.select_a_country')</option>
                                    <option value="756" {{ old('country') == 756 || $user->country_id == 756 ? 'selected' : '' }}>Schweiz</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ $user->country_id == $country->id ? 'selected' :'' }}>{{ $country->name_de }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->has('country_id')? '<p class="help-block">'.$errors->first('country_id').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('phone')? 'has-error':'' }}">
                            <label for="phone" class="col-sm-4 control-label">@lang('app.phone')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="phone" value="{{ old('phone')? old('phone') : $user->phone }}" name="phone" placeholder="@lang('app.phone')">
                                {!! $errors->has('phone')? '<p class="help-block">'.$errors->first('phone').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('user_name')? 'has-error':'' }}">
                            <label for="user_name" class="col-sm-4 control-label">@lang('app.user_name')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="user_name" value="{{ old('user_name')? old('user_name') : $user->user_name }}" name="user_name" placeholder="@lang('app.user_name')">
                                {!! $errors->has('user_name')? '<p class="help-block">'.$errors->first('user_name').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('email')? 'has-error':'' }}">
                            <label for="email" class="col-sm-4 control-label">@lang('app.email')</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="email" value="{{ old('email')? old('email') : $user->email }}" name="email" placeholder="@lang('app.email')">
                                {!! $errors->has('email')? '<p class="help-block">'.$errors->first('email').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group  {{ $errors->has('photo')? 'has-error':'' }}">
                            <label class="col-sm-4 control-label">@lang('app.change_avatar')</label>
                            <div class="col-sm-8">
                                <input type="file" id="photo" name="photo" class="filestyle" >
                                {!! $errors->has('photo')? '<p class="help-block">'.$errors->first('photo').'</p>':'' !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="email_notifications" {{ $user->email_notifications ? 'checked' : '' }}> @lang('app.receive_notification_emails')
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-4">
                                <button type="submit" class="btn btn-primary">@lang('app.save')</button>
                            </div>
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>

            </div>

        </div>


    </div>
@endsection

@section('page-js')
    <script src="{{ asset('assets/js/bootstrap-filestyle.min.js') }}"></script>
    <script>
        $(":file").filestyle({buttonName: "btn-primary", buttonBefore: true});

        $('.radio-button input').change(function () {
            if ($(this).val() == 2) {
                $('.company-info').removeClass('hidden');
            } else {
                $('.company-info').addClass('hidden');
            }
        });
    </script>
@endsection