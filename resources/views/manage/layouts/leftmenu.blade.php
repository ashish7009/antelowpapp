<aside class="main-sidebar">
	<section class="sidebar">
		<div class="sidemenulogo text-center">
			<a href="{{ url('/manage/dashboard') }}" class="img-responsive">
				<img src="{{ asset('/manage/images/logo.png') }}" alt="{{ config('app.constants.website') }}" class="img-responsive" />
			</a>
		</div>
		<ul class="sidebar-menu">
			<li class="header">MAIN NAVIGATION</li>
			<li {!! (isset($menu_dashboard) && $menu_dashboard) ? 'class="active"' : '' !!}><a href="{{ url('/manage/dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
			<li {!! (isset($menu_users) && $menu_users) ? 'class="active"' : '' !!}><a href="{{ url('/manage/users') }}"><i class="fa fa-user"></i> <span>Users</span></a></li>
			<li {!! (isset($menu_profile) && $menu_profile) ? 'class="active"' : '' !!}><a href="{{ url('/manage/profile') }}"><i class="fa fa-user-secret"></i> <span>Profile</span></a></li>
		</ul>
	</section>
</aside>