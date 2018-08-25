@extends('layouts.front')

@section('pagetitle', 'Series')

@section('content')
<link rel="stylesheet" href="{{ asset('css/hopscotch-0.2.6.min.css') }}" />
<style>
.content-section {
	background-color: #f6f6f6;
}
div.hopscotch-bubble .hopscotch-bubble-container{
	width: auto !important;
	height: auto !important;
	
}
div.hopscotch-bubble {
    background-color: #ff017f !important;
    border: 5px solid rgb(255, 1, 127) !important;
	border-radius:20px;
}
div.hopscotch-bubble .hopscotch-bubble-arrow-container.down .hopscotch-bubble-arrow {
    border-top: 17px solid #ff017f !important;
   /* border-left: 36px solid transparent !important;
   // border-right: 36px solid transparent !important;*/
}
div.hopscotch-bubble .hopscotch-bubble-arrow-container.down .hopscotch-bubble-arrow-border {
   
    border-top: 17px solid rgb(255, 1, 127) !important;
  
}
.hopscotch-bubble-arrow-container.left .hopscotch-bubble-arrow {
   // border-bottom: 20px solid transparent !important;
    border-right: 17px solid #ff017f !important;
    border-top: 17px solid transparent;
    position: relative;
   // left: -18px !important;
  
}
div.hopscotch-bubble h3 {
    color: #fff !important;
}
div.hopscotch-bubble .hopscotch-bubble-content {
    margin: 0px !important; 
	    text-align: center;
}
div.hopscotch-bubble .hopscotch-bubble-number{
	display:none;
}
div.hopscotch-bubble h3 {
  
    font-size: 17px !important;
   // line-height: 30px !important;
}
.tuimg{
	height: 70px;
}

</style>


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
				<a id="nav-home" href="{{ url('/series') }}" class="topnav__item topnav__item_home {{ isset($menu_series)?'active':'' }}">
					<!-- <span>Home</span> -->
				</a>
				<a id="nav-schedule" href="{{ url('/user/schedule') }}" class="topnav__item topnav__item_schedule {{ Request::path() == 'user/schedule' ? 'active' : '' }}">
					<!-- <span>Schedule</span> -->
				</a> 
				<a class="topnav__item topnav__item_video_list" id="pktstory" href="{{ url('/story') }}">
		            <!-- <span>Video Play List</span> -->
		        </a>

				@if(isset($globaldata['user']))
				<div class="dropdown hideinhome ">

					<a id="nav-notofication" href="{{ url('/user/user_notification') }}" class="topnav__item topnav__item_notific  no-hover nounderline notification-link {{ Request::is('user_notification')?'active':'' }}" data-toggle="" href="#">
						
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

				<a class="topnav__item topnav__item_avaliable" href="javascript:void(0);" class="navbar-toggle-mobil" data-toggle="collapse" data-target=".mobil-dropdown">
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
<div id="tr_in_request" class="modal fade" role="dialog" style="display:none;">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
  
      <div class="modal-body custom_request" style="text-align:center" >
	  <h3 ><span class="custom_request_name"></span> sent a Truefriend request</h3>
        <p>By being a Truefriend, <span class="custom_request_name1"></span> can see your episodes immediately without waiting </p>
		<img class="tf_img1" src="" /><br>
		<div style="text-align:right">
		<a type="button" class="btn  btn-lg trequest_no" role="button">Nope!</a>
<a type="button" class="btn  btn-lg  trequest_accept" role="button">Accept request </a>
      </div>
      </div>
     
    </div>

  </div>
</div>

@section('page-scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-hammerjs@2.0.0/jquery.hammer.min.js"></script>
<script src="{{ asset('js/hopscotch-0.2.6.min.js') }}"></script>

<!-- <script src="{{ asset('js/series.js?cache=2.7') }}"></script> -->
@endsection


@push('footer_script')
<script>
var tour = {
  id: 'hello-hopscotch',
  steps: [
    {
      target: document.querySelectorAll("#views")[0],
      title: 'See the number of people interested in seeing a post',
      content: '<img src="<?php echo secure_asset('uploads/h1.PNG');?>" class="tuimg"/>',
      placement: 'top',
      //arrowOffset: 'center'
    },
    {
      target: 'nav-schedule',
      title: 'See when next your friends will post something new.',
      content: '<img src="<?php echo secure_asset('uploads/h2.PNG'); ?>" class="tuimg"/>',
      placement: 'top',
      yOffset: 0
    },
    {
      target: 'pktstory',
      placement: 'top',
      title: 'See posts that are now available.',
      content: '<img src="<?php echo secure_asset('uploads/h3.PNG'); ?>" class="tuimg"/>'
    },
    {
      target: 'add-video-mobil',
      placement: 'right',
      title: 'Upload your media',
      content: '<img src="<?php echo secure_asset('uploads/h1.PNG'); ?>" class="tuimg"/>',
      yOffset: 0
    }
  ],
  showPrevButton: true,
  scrollTopMargin: 300,
  onClose: function() {
    $.ajax({
		url:'<?php echo url('/user/tour'); ?>',
		data:{uid:<?php echo $globaldata['user']->userid; ?>},
		type:'post',
		success:function(){
			//window.location.reload();
		}
	});
  }
},

/* ========== */
/* TOUR SETUP */
/* ========== */
addClickListener = function(el, fn) {
  if (el.addEventListener) {
    el.addEventListener('click', fn, false);
  }
  else {
    el.attachEvent('onclick', fn);
  }
},

init = function() {
  var startBtnId = 'startTourBtn',
      calloutId = 'startTourCallout',
      mgr = hopscotch.getCalloutManager(),
      state = hopscotch.getState();
mgr.removeAllCallouts();
      hopscotch.startTour(tour);

};
 $( document ).ready(function() {
	 <?php if($globaldata['user']->tour_count<=15){ ?>
        init();		
 <?php } ?>
		$(document).on('click','.hopscotch-next',function(){
			if($(this).text()=='Done'){
				  $.ajax({
		url:'<?php echo url('/user/tour'); ?>',
		data:{uid:<?php echo $globaldata['user']->userid; ?>},
		type:'post',
		success:function(){
			//window.location.reload();
		}
	});
			}
		});
		/*$(document).on('click','.report-content-flag',function(){
			$(this).find( "ul" ).toggle();
		});
		$(document).on('click','ul.report_now li',function(){
			$(this).toggle();
			//$(this).css('display','none');
			
			
		});*/
		$(document).on('click','.comming_soon1',function(){
			$(this).hide();
		});
    });


</script>
@endpush