<header class="main-header">
	<!-- Logo -->
	<a href="{{ route('admin.dashboard') }}" class="logo">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<span class="logo-mini"> <img style="width: 50px;" src="{{ asset('backend/images/unimass-mini.png') }}" alt=""></span>
		<!-- logo for regular state and mobile devices -->
		<span class="logo-lg"><b><img style="width:70px;" src="{{ asset('backend/images/unimass.png') }}" alt=""></span>
		</a>
		<!-- Header Navbar: style can be found in header.less -->
		<nav class="navbar navbar-static-top">
			<!-- Sidebar toggle button-->
			<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
				<span class="sr-only">Toggle navigation</span>
			</a>
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
					<!-- Messages: style can be found in dropdown.less-->
					<li class="dropdown messages-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							@php
							$ses_role_name    = Session::get('user.ses_role_name');
							$status="";

							@endphp
							Logged in as :: {{ $ses_role_name }}
						</a>
					</li>
					<!-- User Account: style can be found in dropdown.less -->
					<li class="dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<img src="@if(Auth::User()->profile)@if(Auth::User()->profile->image) {{asset(Auth::User()->profile->image)}}@endif @else{{ asset('backend/images/avatar.jpg') }} @endif" class="user-image" alt="User Image">
							<span class="hidden-xs">{{ Auth::user()->name }}</span>
						</a>
						<ul class="dropdown-menu">
							<!-- User image -->
							<li class="user-header">
								<img src="@if(Auth::User()->profile)@if(Auth::User()->profile->image) {{asset(Auth::User()->profile->image)}} @endif @else{{ asset('backend/images/avatar.jpg') }} @endif" class="img-circle" alt="User Image">

								<p>
									<small>User</small> {{ Auth::user()->name }}
								</p>
							</li>
							<!-- Menu Footer-->
							<li class="user-footer">
								<div class="pull-left">
									<a href="{{route('profile')}}" class="btn btn-default btn-flat">Profile</a>
								</div>
								<div class="pull-right">
									<a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">Sign out</a>
									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
										@csrf
									</form>
								</div>
							</li>
						</ul>
					</li>
					<li>
						<a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
								@csrf
							</form>
							<i class="fa fa-power-off"></i>
						</a>
					</li>
				</ul>
			</div>
		</nav>
	</header>
