@extends('layouts.front')

@section('pagetitle', 'Notifications')

@section('content')
<div class="content-container mobil-feed">
	<div class="container">
	<div class="row">
	<div class="topnav">
		 <ul class="mobil-dropdown">
                    <li class="@if(isset($menu_profile)) active @endif">
                        <a href="{{ url('/user/edit') }}">
                            <span>Profile</span>
                        </a>
                    </li>
                    <li class="@if(isset($menu_myseries)) active @endif">
                        <a href="{{ url('/vlogs/edit') }}">
                            <span>Airs</span>
                        </a>
                    </li>
                    <li class="@if(isset($menu_myfollower)) active @endif">
                        <a href="{{ url('/user/myfollower') }}">
                            <span>Followers</span>
                        </a>
                    </li>
                    @if($globaldata['user']->userid == '6')
                    <li class="@if(isset($menu_myfollower)) active @endif">
                        <a href="{{ url('/influencer') }}">
                            <span>Influencer</span>
                        </a>
                    </li>
                   @endif
                    <li>
                        <a href="{{ url('/user/logout') }}" class="text-center color-red mobil-logout">
                            <strong>SIGN OUT</strong>
                        </a>
                    </li>
                    <a href="javascript: void(0);" onclick="openInvitePopup(0, 'Invite Friends');"
                       class="btn invite-mobil"></a>
                </ul>
		        <a href="{{ url('/series') }}" class="topnav__item topnav__item_home  {{ Request::path() == '/series' ? 'active' : '' }}">
		            <!-- <span>Home</span> -->
		        </a> 
		        <a href="{{ url('/user/schedule') }}" class="topnav__item topnav__item_schedule {{ Request::path() == 'user/schedule' ? 'active' : '' }}">
		            <!-- <span>Schedule</span> -->
		        </a>
		        <a class="topnav__item topnav__item_video_list" href="{{url('/story')}}">
		            <!-- <span>Video Play List</span> -->
		        </a>

				@if(isset($globaldata['user']))
						<div class="dropdown hideinhome ">
						
		        <a href="{{ url('/user/user_notification') }}" class="topnav__item topnav__item_notific  no-hover nounderline notification-link {{ Request::path() == 'user/user_notification' ? 'active' : '' }}" data-toggle="" href="#">
		            <!-- <span>Notifications</span> -->
		            <span class="count">{{$globaldata['notificationtopcounts']}}</span>
		        </a> 
				<ul class="dropdown-menu dropdown-menu-right notification-dropdown">
								@if(isset($globaldata['notificationcounts']) && $globaldata['notificationcounts'] > 0)
									@if(isset($globaldata['episodes_will_air_today_count']) && $globaldata['episodes_will_air_today_count'] > 0)
										<li>
											<a>
												<span>{{ $globaldata['episodes_will_air_today_count'] }} 
													@if($globaldata['episodes_will_air_today_count'] == 1) 		post 
													@else 
														posts 
													@endif will air today
												</span>
											</a>
										</li>
									@endif
									@if(isset($globaldata['justairedepisodes']) && !$globaldata['justairedepisodes']->isEmpty())
										@foreach($globaldata['justairedepisodes'] as $justairedepisode)
										<li>
											<a target="_blank" href="{{ url('series/'.$justairedepisode->series->slug.'/'.seriesmedia_unique_string($justairedepisode)) }}">
												<span>Your post <b>{{ $justairedepisode->title }}</b> just aired</span>
											</a>
										</li>
										@endforeach
									@endif
									@if(isset($globaldata['notifications']))
										@foreach($globaldata['notifications'] as $notification)
										<li>
											<a target="_blank" href="{{ $notification->notificationurl }}" onclick="">
												<span>{{ $notification->notificationtext }}</span>
											</a>
										</li>
										@endforeach
									@endif
								@else
									<li>
										{{-- <a>
											<span>No notifications</span>
										</a> --}}
										<div class="wow fadeInDown">
											<h1 class="page-title color-red">No search result</h1>
											<p>No Notification</p>
										</div>
									</li>
								@endif
				</ul>
							</div>
						@endif

				<a class="topnav__item topnav__item_avaliable" href="javascript:void(0);" class="navbar-toggle-mobil" data-toggle="collapse" data-target=".mobil-dropdown">
		            <!-- <span>Avaliable Now</span> -->
		        </a>
		    </div>
		 
			</div>
		<div class="wow fadeInDown row notification-top-border">

			<!-- <br/><div class="col-lg-12 text-center"><h4>Notifications</h4></div>
			<div class="clearfix"></div><br/> -->
				@if(isset($globaldata['notificationcounts']) && $globaldata['notificationcounts'] > 0)
									@if(isset($globaldata['episodes_will_air_today_count']) && $globaldata['episodes_will_air_today_count'] > 0)
										<div class="col-xs-12 col-sm-12 col-md-12 new-notification-pans pt-pb-9-tab display-flex-mid">
										<div class="col-xs-2 col-sm-2 col-md-2">
												<span class="badge badge-light watch-badge-1 requestcount" style="border-radius: 29px !important;padding: 15px !important;min-width:40px !important;">
													{{ $globaldata['episodes_will_air_today_count'] }}
												</span>
											</div>
										<div class="col-xs-7 col-sm-7 col-ms-7">
											<div class="padding-none">
												<a>
													<span>{{ $globaldata['episodes_will_air_today_count'] }} @if($globaldata['episodes_will_air_today_count'] == 1) post @else posts @endif will air today</span>
												</a>
											</div>
										</div>
										<div class="col-xs-3 col-sm-3 col-md-3">
												<!-- <label class="btn btn-primary btn-xs new-notification-btn no-pointer-events">NEW</label> -->
											</div>
										</div>
								
									
									@endif
									@if(isset($globaldata['justairedepisodes']) && !$globaldata['justairedepisodes']->isEmpty())
										@foreach($globaldata['justairedepisodes'] as $justairedepisode)
										<div class="col-xs-12 col-sm-12 col-md-12 new-notification-pans pt-pb-9-tab display-flex-mid">
											<div class="col-xs-2 col-sm-2 col-md-2">
												<span class="badge badge-light watch-badge-1 requestcount" style="border-radius: 29px !important;padding: 15px !important;min-width:40px !important;background-color:yellow !import">
													Air
												</span>
											</div>
											<div class="col-xs-7 col-sm-7 col-ms-7">
												<div class="">
											
													<div class="padding-none">
														<a target="_blank" href="{{ url('series/'.$justairedepisode->series->slug.'/'.seriesmedia_unique_string($justairedepisode)) }}" onclick="">
															<span>Your post <b>{{ $justairedepisode->title }}</b> just aired</span>
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
									<?php //echo "<pre>";print_r($globaldata['notifications']);echo "</pre>"; ?>
										@foreach($globaldata['notifications'] as $notification)
									<?php if($notification->notificationtext!=''){ ?>
										<div class="col-xs-12 col-sm-12 col-md-12 new-notification-pans pt-pb-9-tab display-flex-mid">
										@if($notification->notificationimage == "")
											<div class="col-xs-2 col-sm-2 col-md-2">
												<span class="badge badge-light watch-badge-1 requestcount" style="border-radius: 29px !important;padding: 15px !important;min-width:40px !important;">
													{!! $notification->content_text !!}
												</span>
											</div>
										@else
											<div class="col-xs-2 col-sm-2 col-md-2 hgh">
												<img src="{{ secure_asset($notification->notificationimage) }}" alt="" class="notifiction-image">
											</div>
										@endif
											<div class="col-xs-7 col-sm-7 col-ms-7">
												<div class="">
													<a href="javascript:void(0);" id="{{ $notification->notificationid }}" onclick="isviewed(this.id,'{{ $notification->notificationurl }}')" >
														<span>{!! $notification->notificationtext !!}</span>
													</a>
												</div>
											</div>
											<div class="col-xs-3 col-sm-3 col-md-3">
												@if($notification->is_viewed == 0)
													<label class="btn btn-primary btn-xs new-notification-btn no-pointer-events">NEW</label>
												@endif
											</div>
										</div>
									<?php } ?>
										@endforeach
									@endif
								@else
						<div class="col-xs-2 col-sm-2 col-md-2"></div>
									
								<div class="col-sm-12 col-xs-12 padding-none">
									<a>
										{{-- <span>No notifications</span> --}}
										<div class="container">
											<div class="wow fadeInDown">
												<h1 class="page-title color-red">No search result</h1>
												<p>No Notifications Found.</p>
											</div>
										</div>
									</a>
								</div>
								@endif
				
		
		<div class="col-lg-12 text-center" style="display:none"><h4>See more...</h4></div>
		<div class="clearfix"></div><br/>

		</div>
	</div>
</div>
@endsection
@section('page-scripts')
<script src="{{ asset('js/myseries.js') }}"></script>
@endsection