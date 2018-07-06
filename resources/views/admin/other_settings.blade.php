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

                        {!! Form::open(['class'=>'form-horizontal', 'files'=>'true']) !!}
                        <div class="form-group  {{ $errors->has('logo')? 'has-error':'' }}">
                            <label class="col-sm-4 control-label">@lang('app.site_logo')</label>
                            <div class="col-sm-8">

                                @if(logo_url())
                                    <img src="{{ logo_url() }}" />
                                @endif


                                <input type="file" id="logo" name="logo" class="filestyle" >
                                {!! $errors->has('logo')? '<p class="help-block">'.$errors->first('logo').'</p>':'' !!}
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

            </div>
			
        </div>

    </div>
@endsection

@section('page-js')


@endsection