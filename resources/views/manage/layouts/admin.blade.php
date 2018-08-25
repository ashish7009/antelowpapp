<!DOCTYPE html>
<html>
<head>
	
	@include('manage.layouts.head')
	@yield('page-css')
	
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

	<header class="main-header">
		<a href="{{ url('/manage/dashboard') }}" class="logo">
			<span class="logo-mini"><img src="{{ asset('/manage/images/logo.png') }}" alt="{{ config('app.constants.website') }}" /></span>
		</a>
		<nav class="navbar navbar-static-top" role="navigation">
			<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
				<span class="sr-only">Toggle navigation</span>
			</a>
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">

					<!-- USER ACCOUNT DETAIL START -->
					<li class="dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<img src="{{ $globaldata['admin']->imagefilepath }}" class="user-image" alt="User Image" />
							<span class="hidden-xs">{{ $globaldata['admin']->firstname . ' ' . $globaldata['admin']->lastname }}</span>
							<input type="hidden" name="removeimage" id="removeimage" value="0" />
						</a>
						<ul class="dropdown-menu">
							<li class="user-header">
								<img src="{{ $globaldata['admin']->imagefilepath }}" class="img-circle" alt="User Image" />
								<p>{{ $globaldata['admin']->firstname . ' ' . $globaldata['admin']->lastname }} <small>{{ $globaldata['admin']->email }}</small></p>
							</li>
							<li class="user-footer">
								<div class="pull-left">
									<a href="{{ url('manage/profile') }}" class="btn btn-primary btn-flat">Profile</a>
								</div>
								<div class="pull-right">
									<a href="{{ url('manage/logout') }}" class="btn btn-primary btn-flat">Sign out</a>
								</div>
							</li>
						</ul>
					</li>
					<!-- USER ACCOUNT DETAIL END -->
					
				</ul>
			</div>
		</nav>
	</header>
	
	@include('manage.layouts.leftmenu')
	
	<div class="content-wrapper">
		
		@yield('content')
		
	</div>
	
	<footer class="main-footer">
		<div class="pull-right hidden-xs">
			<b>Version</b> 1.0
		</div>
		<strong>Copyright &copy; {{ date('Y') }} <a href="{{ url('/') }}">{{ config('app.constants.website') }}</a>.</strong> All rights reserved.
	</footer>
	
</div>

@include('manage.layouts.foot')
@yield('page-scripts')

</body>
</html>

