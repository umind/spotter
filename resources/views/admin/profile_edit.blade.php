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

                        {!! Form::open(['class'=>'form-horizontal', 'files'=>'true']) !!}

                        <div class="form-group {{ $errors->has('title')? 'has-error':'' }}">
                            <label for="title" class="col-sm-4 control-label">@lang('app.anrede')</label>
                            <div class="col-sm-8">
                                @foreach(getArray('title_types') as $key => $titleType)
                                    <label>
                                        <input id="title" type="radio" class="form-control" name="title" value="{{ $key }}"> {{ $titleType }}
                                    </label>
                                @endforeach
                                {!! $errors->has('title')? '<p class="help-block">'.$errors->first('title').'</p>':'' !!}
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
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ $user->country_id == $country->id ? 'selected' :'' }}>{{ $country->country_name }}</option>
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

                        <hr />

                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-4">
                                <button type="submit" class="btn btn-primary">@lang('app.edit')</button>
                            </div>
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>

            </div>   <!-- /#page-wrapper -->

        </div>   <!-- /#wrapper -->


    </div> <!-- /#container -->
@endsection

@section('page-js')
    <script src="{{ asset('assets/js/bootstrap-filestyle.min.js') }}"></script>
    <script>
        $(":file").filestyle({buttonName: "btn-primary", buttonBefore: true});
    </script>
@endsection