@extends('layouts.front')

@section('pagetitle', 'Series')

@section('content')
<div class="content-container pb0 mobil-feed">
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
				<a href="{{ url('/series') }}" class="topnav__item topnav__item_home {{ Request::path() == '/series' ? 'active' : '' }}">
					<!-- <span>Home</span> -->
				</a> 
				<a href="{{ url('/user/schedule') }}" class="topnav__item topnav__item_schedule {{ Request::path() == 'user/schedule' ? 'active' : '' }}">
					<!-- <span>Schedule</span> -->
				</a>
				<a  class="topnav__item topnav__item_video_list" href="{{url('/story')}}">
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
				<a class="topnav__item topnav__item_avaliable"
				href="javascript:void(0);" class="navbar-toggle-mobil" data-toggle="collapse"
                           data-target=".mobil-dropdown">
					<!-- <span>Avaliable Now</span> -->
				</a>
			</div>
			
			<div class="col-md-offset-3 col-md-6 col-sm-offset-2 col-sm-8 col-xs-offset-1 col-xs-10 feature-box relative">
				<input type="hidden" id="page" value="0" />
				<input type="hidden" id="lastpage" value="-1" />
				<input type="hidden" id="slug" value="{{ $slug }}" />
				<input type="hidden" id="mediastring" value="{{ $mediastring }}" />
				<input type="hidden" id="keyword" value="{{ $keyword }}" />
				<div id="loaddata"></div>
				<div id="pagingdata"></div>
			</div>	
		</div>
	</div>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('js/series_available.js?cache=2.7') }}"></script>
@endsection