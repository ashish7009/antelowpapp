@extends('layouts.front')

@section('pagetitle', 'Follower Details')

@section('content')
<div class="content-container mobil-feed">
	<div class="container">
	<div class="row">
	<div class="topnav">
		        <a href="{{ url('/series') }}" class="topnav__item topnav__item_home  {{ Request::path() == '/series' ? 'active' : '' }}">
		            <span>Home</span>
		        </a> 
		        <a href="{{ url('/user/schedule') }}" class="topnav__item topnav__item_schedule {{ Request::path() == 'user/schedule' ? 'active' : '' }}">
		            <span>Schedule</span>
		        </a> 
		        <a href="{{ url('/series/available_now') }}" class="topnav__item topnav__item_avaliable  {{ Request::path() == 'series/available_now' ? 'active' : '' }}">
		            <span>Avaliable Now</span>
		        </a> 
				@if(isset($globaldata['user']))
						<div class="dropdown hideinhome ">
						
		        <a href="{{ url('/user/user_notification') }}" class="topnav__item topnav__item_notific  no-hover nounderline notification-link {{ Request::path() == 'user/user_notification' ? 'active' : '' }}" data-toggle="" href="#">
		            <span>Notifications</span>
		            <span class="count">{{$globaldata['notificationcounts']}}</span>
		        </a> 
				<ul class="dropdown-menu dropdown-menu-right notification-dropdown">
								@if(isset($globaldata['notificationcounts']) && $globaldata['notificationcounts'] > 0)
									@if(isset($globaldata['episodes_will_air_today_count']) && $globaldata['episodes_will_air_today_count'] > 0)
									<li>
										<a>
											<span>{{ $globaldata['episodes_will_air_today_count'] }} @if($globaldata['episodes_will_air_today_count'] == 1) post @else posts @endif will air today</span>
										</a>
									</li>
									@endif
									@if(isset($globaldata['justairedepisodes']) && !$globaldata['justairedepisodes']->isEmpty())
										@foreach($globaldata['justairedepisodes'] as $justairedepisode)
										<li>
											<a target="_blank" href="{{ url('series/'.$justairedepisode->series->slug.'/'.seriesmedia_unique_string($justairedepisode)) }}">
												<span>Your episode <b>{{ $justairedepisode->title }}</b> just aired</span>
											</a>
										</li>
										@endforeach
									@endif
									@if(isset($globaldata['notifications']))
										@foreach($globaldata['notifications'] as $notification)
										<li>
											<a target="_blank" href="{{ $notification->notificationurl }}">
												<span>{{ $notification->notificationtext }}</span>
											</a>
										</li>
										@endforeach
									@endif
								@else
								<li>
									<a>
										<span>No notifications</span>
									</a>
								</li>
								@endif
							</ul>
							</div>
						@endif
		    </div>
			</div>
		<div class="wow fadeInDown row notification-top-border">

			<!-- <br/><div class="col-lg-12 text-center"><h4>Notifications</h4></div>
			<div class="clearfix"></div><br/> -->
				@if(isset($globaldata['notificationcounts']) && $globaldata['notificationcounts'] > 0)
									@if(isset($globaldata['episodes_will_air_today_count']) && $globaldata['episodes_will_air_today_count'] > 0)
										<div class="col-sm-6 mt5 pt5 single-notify new new-notification-pans ">
											<div class="padding-none">
												<a>
													<span>{{ $globaldata['episodes_will_air_today_count'] }} @if($globaldata['episodes_will_air_today_count'] == 1) post @else posts @endif will air today</span>
												</a>
											</div>
										</div>
									<div class="clearfix"></div>
									
									@endif
									@if(isset($globaldata['justairedepisodes']) && !$globaldata['justairedepisodes']->isEmpty())
										@foreach($globaldata['justairedepisodes'] as $justairedepisode)
										<div class="col-xs-12 col-sm-12 col-md-12 new-notification-pans pt-pb-9-tab display-flex-mid">
											<div class="col-xs-2 col-sm-2 col-md-2">
											<img src="{{ asset($globaldata['user']->imagefilepath) }}" alt="{{ $globaldata['user']->imagefilepath }}" class="notifiction-image">
											</div>
											<div class="col-xs-7 col-sm-7 col-ms-7">
												<div class="">
											
													<div class="padding-none">
														<a target="_blank" href="{{ url('series/'.$justairedepisode->series->slug.'/'.seriesmedia_unique_string($justairedepisode)) }}">
															<span>Your episode <b>{{ $justairedepisode->title }}</b> just aired</span>
														</a>
													</div>
												</div>
											</div>
											<div class="col-xs-3 col-sm-3 col-md-3">
												<!-- <label class="btn btn-primary btn-xs new-notification-btn no-pointer-events">NEW</label> -->
											</div>
										</div>
										@endforeach
									@endif
									@if(isset($globaldata['notifications']))
										@foreach($globaldata['notifications'] as $notification)
										<div class="col-xs-12 col-sm-12 col-md-12 new-notification-pans pt-pb-9-tab display-flex-mid">
											<div class="col-xs-2 col-sm-2 col-md-2">
												<img src="{{ asset($globaldata['user']->imagefilepath) }}" alt="{{ $globaldata['user']->imagefilepath }}" class="notifiction-image">
											</div>
											<div class="col-xs-7 col-sm-7 col-ms-7">
												<div class="">
											
													<a target="_blank" href="{{ $notification->notificationurl }}">
														<span>{!! $notification->notificationtext !!}</span>
													</a>
												</div>
											</div>
											<div class="col-xs-3 col-sm-3 col-md-3">
												<label class="btn btn-primary btn-xs new-notification-btn no-pointer-events">NEW</label>
											</div>
										</div>
										@endforeach
									@endif
								@else
						<div class="col-xs-2 col-sm-2 col-md-2"></div>
									
								<div class="col-sm-12 col-xs-12 padding-none">
									<a>
										<span>No notifications</span>
									</a>
								</div>
								@endif
				
		
		<div class="col-lg-12 text-center" style="display:none"><h4>See more...</h4></div>
		<div class="clearfix"></div><br/>

		</div>
	</div>
</div>
@endsection
