<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@section('title') {{ get_option('site_title') }} @show</title>


    <!-- bootstrap css -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-theme.min.css') }}">
    <!-- Font awesome 4.4.0 -->
    <link rel="stylesheet" href="{{ asset('assets/font-awesome-4.4.0/css/font-awesome.min.css') }}">
    <!-- load page specific css -->

    <!-- main select2.css -->
    <link href="{{ asset('assets/select2-4.0.3/css/select2.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">

    <!-- Conditional page load script -->
    @if(request()->segment(1) === 'dashboard')
        <link rel="stylesheet" href="{{ asset('assets/css/admin.css?ver=' . str_random(10)) }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/metisMenu/dist/metisMenu.min.css') }}">
    @endif

<!-- main style.css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css?ver=' . str_random(10)) }}">

    @if(is_rtl())
        <link rel="stylesheet" href="{{ asset('assets/css/rtl.css?ver=' . str_random(10)) }}">
    @endif

    @yield('page-css')

    @if(get_option('additional_css'))
        <style type="text/css">
            {{ get_option('additional_css') }}
        </style>
    @endif

    <script src="{{ asset('assets/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js') }}"></script>
    <script type="text/javascript">
        window.jsonData = {!! frontendLocalisedJson() !!};
    </script>

</head>
<body class="@if(is_rtl()) rtl @endif">
	<div id="app">

		@if(env('APP_DEMO') == true)
			@include('demobar')
		@endif
		<div id="sub-header">
			<div class="container">
				<div class="row">
					<div class="col-xs-6">
						<div class="social-icons">
							@php
								$facebook_url = get_option('facebook_url');
								$twitter_url = get_option('twitter_url');
								$linked_in_url = get_option('linked_in_url');
								$dribble_url = get_option('dribble_url');
								$google_plus_url = get_option('google_plus_url');
								$youtube_url = get_option('youtube_url');
							@endphp
							<ul>
								@if($facebook_url)
									<li><a href="{{$facebook_url}}"><i class="fa fa-facebook"></i> </a> </li>
								@endif
								@if($twitter_url)
									<li><a href="{{$twitter_url}}"><i class="fa fa-twitter"></i> </a> </li>
								@endif
								@if($google_plus_url)
									<li><a href="{{$google_plus_url}}"><i class="fa fa-google-plus"></i> </a> </li>
								@endif
								@if($youtube_url)
									<li><a href="{{$youtube_url}}"><i class="fa fa-youtube"></i> </a> </li>
								@endif
								@if($linked_in_url)
									<li><a href="{{$linked_in_url}}"><i class="fa fa-linkedin"></i> </a> </li>
								@endif
								@if($dribble_url)
									<li><a href="{{$dribble_url}}"><i class="fa fa-dribbble"></i> </a> </li>
								@endif
							</ul>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="right-info">
							<ul>
								@if(Auth::check())
									@php 
										$notifications = Auth::user()->notifications->sortByDesc('date');
										$notifNum = $notifications->where('is_read', 0)->count();
									@endphp
									<li class="notification-li" id="notifications">
										<div class="notification">
											<i class="fa fa-bell"></i>
											@if($notifNum > 0 && Auth::user()->notification_bell == 1)
												<span class="notification-number">{{ $notifNum }}</span>
											@endif
										</div>
										<div class="notification-div">
											<h3>{{ trans('app.notifications') }}</h3>
											<ul class="notification-list">
												@if($notifications->count() > 0)
													@foreach($notifications as $notification)
														<li style="{{ !$notification->is_read ? 'background-color: #edf2fa;' : '' }}">
															<a href="{{ route('show_notification', $notification->id) }}">
																<span>{!! $notification->text !!}</span>
																<span class="date-notification">{{ Carbon\Carbon::parse($notification->date)->formatLocalized(get_option('date_format')) }}</span>
															</a>
														</li>
													@endforeach
												@else
													<p>@lang('app.no_notifications_found')</p>
												@endif
											</ul>
										</div>
									</li>
								@endif

								@if (Auth::guest())
									<li><a href="{{ route('login') }}">@lang('app.login')</a></li>
									<li><a href="{{ route('register') }}">@lang('app.register')</a></li>
								@else
									<li class="dropdown">
										<a href="#" class="dropdown-toggle user" data-toggle="dropdown" role="button" aria-expanded="false">
											{{ auth()->user()->user_name }} <span class="headerAvatar"> <img src="{{auth()->user()->get_gravatar()}}" /> </span> <span class="caret"></span>
										</a>

										<ul class="dropdown-menu user" role="menu">
											@if(auth()->user()->is_admin())
											<li><a href="{{route('dashboard')}}"> @lang('app.dashboard') </a> </li>
											@else
											<li><a href="{{route('active_bidding_auctions')}}"> @lang('app.my_spotter') </a> </li>
											@endif
											<li>
												<a href="{{ route('logout') }}"
												   onclick="event.preventDefault();
														 document.getElementById('logout-form').submit();">
													@lang('app.logout')
												</a>

												<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
													{{ csrf_field() }}
												</form>
											</li>
										</ul>
									</li>
								@endif



							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>


		<nav class="navbar navbar-default navbar-static-top">
			<div class="container">
				<div class="navbar-header">

					<!-- Collapsed Hamburger -->
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
						<span class="sr-only">Toggle Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>

					<!-- Branding Image -->
					<a class="navbar-brand" href="{{ route('home') }}">
						<img src="{{ asset('uploads/logo/spotter_logo.png') }}" alt="spotter logo">
						{{-- <h2 class="text-uppercase">Spotter</h2> --}}
					</a>
				</div>

				<div class="collapse navbar-collapse" id="app-navbar-collapse">
					<!-- Left Side Of Navbar -->
					<ul class="nav navbar-nav">
						<li><a href="{{route('home')}}">@lang('app.events')</a></li>
						<li><a href="{{ route('products')}}">@lang('app.products')</a> </li>
						@if($header_menu_pages->count() > 0)
							@foreach($header_menu_pages as $page)
								<li><a href="{{ route('single_page', $page->slug) }}">{{ $page->title }} </a></li>
							@endforeach
						@endif
                        <li><a href="{{ route('contact_us_page') }}">@lang('app.contact_us')</a></li>
					</ul>

					<!-- Right Side Of Navbar -->
					<ul class="nav navbar-nav navbar-right nav-sarchbar">
						<!-- Authentication Links -->


						<li>
							<a href="#" id="example-show" class="showLink" onclick="showHide('example');return false;" style="display: inline;"><i class="fa fa-search"></i></a>
							<div id="example" class="more" style="display: none;">
								{!! Form::open(['route' => 'search_redirect','method' => 'get', 'class' => 'form-inline']) !!}
									<input type="text" class="form-control" id="searchKeyword" name="q" placeholder="@lang('app.what_are_u_looking')">
								{!! Form::close() !!}
								<a href="#" id="example-hide" class="hideLink" onclick="showHide('example');return false;"><i class="fa fa-close"></i></a>
							</div>
						</li>

					</ul>
				</div>
			</div>
		</nav>


		@if(Request::segment(1) == 'dashboard')
			<div class="container">
				<div class="filter-holder">
					    <div class="account-search">
					        {!! Form::open(['url' => route('get_search_results'), 'method' => 'GET']) !!}
					            <input type="text" name="q" value="{{ old('q') }}">
					            <button type="submit" class="btn btn-primary">@lang('app.search')</button>
					        {!! Form::close() !!}
					    </div>
					    <div class="account-sort-by">
					        <label class="sort-none">@lang('app.sort_by')</label>
					        <select name="order_by" onchange="location=this.value;">
					            @foreach(getOrderBy() as $key => $order)
					                <option value="{{ urldecode(route('get_search_results', array_merge(request()->query(), ['order_by' => $key]), false)) }}"                  
					                	{{ request('order_by') == $key ? 'selected' : '' }}>{{ $order }}</option>
					            @endforeach
					        </select>
					    </div>
					    <div class="account-status">
					        <label class="sort-none">@lang('app.status')</label>
					        <select name="status" onchange="location=this.value;">
					        	<option value="{{ urldecode(route('get_search_results')) }}">@lang('app.all')</option>
				            	@if(Auth::user()->is_admin())
									@foreach(getArticleStatuses() as $status)
						                <option value="{{ urldecode(route('get_search_results', array_merge(request()->query(), ['status' => $status]), false)) }}" 
						                	{{ request('status') == $status ? 'selected' : '' }}>@lang('app.' . $status . '_word')</option>
						            @endforeach
						        @else
						        	@foreach(getUserArticleStatuses() as $status)
						                <option value="{{ urldecode(route('get_search_results', array_merge(request()->query(), ['status' => $status]), false)) }}" 
						                	{{ request('status') == $status ? 'selected' : '' }}>@lang('app.' . $status . '_word')</option>
						            @endforeach
				            	@endif
					        </select>
					    </div>
				</div>
			</div>
		@endif

		@yield('content')
		<!-- Push-footer start -->
		<div class="push-footer"></div>
		<!-- Push-footer end -->
	</div>
	<div id="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <ul class="footer-menu">
                        <li> <a href="{{ route('home') }}"><i class="fa fa-home"></i> @lang('app.home')</a></li>

                        @if($show_in_footer_menu->count() > 0)
                            @foreach($show_in_footer_menu as $page)
                                <li><a href="{{ route('single_page', $page->slug) }}">{{ $page->title }} </a></li>
                            @endforeach
                        @endif
                        <li><a href="{{ route('contact_us_page') }}">@lang('app.contact_us')</a></li>
                    </ul>

                    <div class="footer-heading">
                        <h3 class="text-uppercase">{{get_option('site_name')}}</h3>
                    </div>

                    <div class="footer-copyright">
                        <p>{!! get_text_tpl(get_option('footer_copyright_text')) !!}</p>
                    </div>

                    <div class="footer-social-links">
                        @php
                            $facebook_url = get_option('facebook_url');
                            $twitter_url = get_option('twitter_url');
                            $linked_in_url = get_option('linked_in_url');
                            $dribble_url = get_option('dribble_url');
                            $google_plus_url = get_option('google_plus_url');
                            $youtube_url = get_option('youtube_url');
                        @endphp
                        <ul>
                            @if($facebook_url)
                                <li><a href="{{$facebook_url}}"><i class="fa fa-facebook"></i> </a> </li>
                            @endif
                            @if($twitter_url)
                                <li><a href="{{$twitter_url}}"><i class="fa fa-twitter"></i> </a> </li>
                            @endif
                            @if($google_plus_url)
                                <li><a href="{{$google_plus_url}}"><i class="fa fa-google-plus"></i> </a> </li>
                            @endif
                            @if($youtube_url)
                                <li><a href="{{$youtube_url}}"><i class="fa fa-youtube"></i> </a> </li>
                            @endif
                            @if($linked_in_url)
                                <li><a href="{{$linked_in_url}}"><i class="fa fa-linkedin"></i> </a> </li>
                            @endif
                            @if($dribble_url)
                                <li><a href="{{$dribble_url}}"><i class="fa fa-dribbble"></i> </a> </li>
                            @endif
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
	<!-- Back-to-top start -->
	<a href="javascript:void(0)" id="back-to-top" title="Back to top"></a>
	<!-- Back-to-top end -->

<script src="{{ asset('assets/js/vendor/jquery-1.11.2.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('assets/select2-4.0.3/js/select2.min.js') }}"></script>

<!-- Conditional page load script -->
@if(request()->segment(1) === 'dashboard')
    <script src="{{ asset('assets/plugins/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script>
        $(function() {
            $('#side-menu').metisMenu();
        });
    </script>
@endif
<script src="{{ asset('assets/js/main.js?ver=' . str_random(10)) }}"></script>
<script>
    var toastr_options = {closeButton : true};
</script>

@if(get_option('additional_js'))
    {!! get_option('additional_js') !!}
@endif

<script>
	$('#notifications').on('click', function () {
		$(this).find('.notification-number').remove();
		$.ajax({
			data: {_token: '{{csrf_token()}}'},
			url: '{{ route('remove_notification_number') }}',
			type: 'post',
			success: function (data) {
				return true;
			}
		});
	});
</script>

@yield('page-js')
</body>
</html>
