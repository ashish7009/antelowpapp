@extends('layouts.front')

@section('pagetitle', 'HashTags')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">
@endsection

@section('page-scripts')
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
@endsection

@section('content')
<style>
.owl-carousel .owl-item img {
   width: 25px !important;
   display: unset !important;
}
.owl-carousel .owl-item .pop img{
	width: 100% !important;
	height: 100% !important;
}
</style>
<div class="content-container" style="background-color: #2f2e2d;">
	<div class="container">
		<div class="wow fadeInDown row">
			<div class="row">
				<div class="col-sm-offset-8 col-xs-offset-8 col-sm-4 col-xs-4 pull-right treding_container">
					<span class="treding"><i class="fa fa-line-chart" aria-hidden="true"></i> Trending</span>
				</div>
			</div>
			<div class="searchdata" >
			@if(count($hashtags) > 0)
				<?php $counter = 0;?>
				@foreach($hashtags as $suser)
					<?php $counter++; ?>
				<div class="row pt10">
					<a href="{{ url('/user/search/'.ucfirst(str_replace('#', '',$suser->hashtag_content))) }}">
						<div class="col-sm-3 col-xs-3 pt30">
							<div class="hashsign_container text-center">
								<span class="hashsign">#</span>
							</div>
						</div>
						<div class="col-sm-8 col-xs-8 padding-none">
							<span href="#" class="mt5 pt5 user-episode user-episode1 user-title">{{ ucfirst(str_replace('#','', $suser->hashtag_content)) }}</span>
						</div>
					</a>
					<div class="clearfix"></div>
				</div>
				@if($counter == 2)
					<div class="slider_hashtag_video">
						<div class="row">
							<div class="col-sm-12 col-xs-12">
								<span class="popular pull-right">Popular</span>
							</div>
						</div>
						<div class="videos_container">
							<div class="owl-carousel owl-theme">
								@foreach($seriesmedias as $index => $seriesmedia)
								<div class="item" style="width:145px">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<div data-seriesmediaid="{{ $seriesmedia->seriesmediaid }}" class="background_none embed-responsive embed-responsive-16by9 video media-item @if($seriesmedia->ispublished) bindvideoplayanalytics @else  na-video {{ $seriesmedia->publishdateday }} @endif @if($seriesmedia->imagefile) imageheight @endif" @if(!$seriesmedia->ispublished) data-publishdateair="{{ $seriesmedia->publishdateair }}" @endif style="height: 130px !important;">
												@if(!$seriesmedia->ispublished)
													<div class="rel-video {{ $seriesmedia->publishdateday }}" @if(!$seriesmedia->ispublished) data-publishdateair="{{ $seriesmedia->publishdateair }}" @endif>
														<div class="rel-text">
															@if($seriesmedia->request_counter == 0)
																<span class="waiting-count">{{'0'}}</span> person is interested
															@else
																@if($seriesmedia->request_counter == 1)
																	<span class="waiting-count">1</span> person is interested
																@else
																	<span class="waiting-count">{{$seriesmedia->request_counter}} </span> people are interested
																@endif
															@endif
														</div>
														<span style="font-size: 26px; font-style: italic; margin-right: 5px;">|</span>
														<div class="{{ $seriesmedia->publishdateday }}">{{ $seriesmedia->publishdateair }} </div>
													</div>
													@if($seriesmedia->imagefile)
														@if(date('a', strtotime($seriesmedia->publish_time)) == 'pm')
															<div class="bannertop moonlight">
																<img src="/images/moon.png" class="lazy" height="14" width="14">
																<span class="moonlighttext">{{ ucfirst($seriesmedia->series->user->firstname) }}'s Stories airs every {{ $seriesmedia->publishdateday }} at {{ date('ha', strtotime($seriesmedia->publish_time)) }}</span>
															</div>
														@else
															<div class="bannertop sunlight">
																<img src="/images/sun.png" class="lazy" height="18" width="18">
																<span class="sunlighttext">{{ ucfirst($seriesmedia->series->user->firstname) }}'s Stories airs every {{ $seriesmedia->publishdateday }} at {{ date('ha', strtotime($seriesmedia->publish_time)) }}</span>
															</div> 
														@endif
													@endif
												@endif
												@if($seriesmedia->imagefile)
													<a href="/uploads/{{ $seriesmedia->imagefile }}" class="pop" style="width: 100%;" data-lightbox="image-{{$seriesmedia->seriesmediaid}}" data-title="{{ $seriesmedia->title }}">
														<span style="background: url({{ "'".asset('/uploads/'.$seriesmedia->imagefile ."'") }}) center center no-repeat" class="popup-image slider_image @if($seriesmedia->ispublished) @else  @if($seriesmedia->imagefile) imageBlur  @endif @endif"> </span>
													</a>
												@else
													@if($seriesmedia->isfile)
														@if($seriesmedia->hasthumb)
															<div class="thumbimg" style="background-image: url({{ asset($seriesmedia->thumbpath) }});"></div>
														@endif
														@if($seriesmedia->hasfile)
															<video id="series-{{$seriesmedia->seriesmediaid}}" class="background_none seeris-video embed-responsive-item bg" preload="metadata" playsinline  loop>
																<source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/mp4">
																<source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/ogg">
																<source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/quicktime">
																Your browser does not support HTML5 video.
															</video>
															<img src="{{ asset('images/videobtn.png') }}" alt="" class="playbtn lazy">
														@else
															<div class="embed-responsive-item noimage-bg"></div>
														@endif
													@else
														@if($seriesmedia->hasurlthumb)
															<div class="thumbimg" style="background-image: url({{ asset($seriesmedia->urlthumbpath) }});"></div>
														@endif
														<iframe class="embed-responsive-item" src="{{ $seriesmedia->workingurl }}" allowfullscreen></iframe>
													@endif
												@endif
											</div>
										</div>
									</div>
									<div class="row">
										<div class="hashtag_video_name">
											<div class="col-sm-8 col-xs-8">
												{{-- <img src="{{ asset($seriesmedia->series->user->imagefilepath) }}" alt="{{ ucfirst($seriesmedia->series->user->fullname) }}" class="lazy img-responsive userprofile1 inline-block img-thumbnail"> --}}
													
												<span class="hashtag_display_name"><b>{{ ucfirst($seriesmedia->series->user->firstname) }}</b></span>
											</div>
										
											<div class="col-sm-4 col-xs-4">
												<span class="following_span pull-right">Following</span>
											</div>
										</div>
									</div>
								</div>
								@endforeach
							</div>
						</div>
					</div>
				@endif
				@endforeach
				
			@else
				<h3 class="messagetext text-center wow fadeInDown">No Hashtag found.</h3>
			@endif
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 text-center search-div">
				<a href="{{ url('/user/list')}}"><img class="find_people_btn" src="{{ asset('images/find_people_icn.png')}}" height="50" width="50"></a>
				<label class="find-lbl" style="">Find People</label>
			</div>
		</div>
	</div>
</div>	
@endsection

@push('footer_script')
<script>
	$("document").ready(function() {
    setTimeout(function() {
       $('.navbar-search-inner').show();
       $('.navbar-search1').hide();
       $('.navbar-toggle-mobil').hide();
	       $('.search_back').click(function(event) {
			$('.navbar-search1').show();
			$('.navbar-search-inner').hide();
			$('.navbar-toggle-mobil').show();
		});
	    $('.wrapper').css('background-color','#2f2e2d');

	    checkImg = function(img) {
		$("<img/>") // Make in memory copy of image to avoid css issues
		.attr("src", $(img).attr("src"))
		.load(function() {
			pic_real_width = this.width;   // Note: $(this).width() will not
			pic_real_height = this.height; // work for in memory images.
			var imgClass = (this.width / this.height > 1) ? 'wide' : 'tall';
			$(img).addClass(imgClass);
		});
	};

    },1000);
});

var pathname = window.location.pathname;
if(pathname == pathname)
{
	$('.navbar-search1').on('click',function(e){
		e.preventDefault();
		 $('.navbar-search-inner').show();
       $('.navbar-search1').hide();
       $('.navbar-toggle-mobil').hide();
	});
}

$('.owl-carousel').owlCarousel({
	loop:true,
	margin:5,
	autoWidth:true,
	lazyLoad:true,
	nav:false,
	dots:false,
	responsive:{
		0:{
			items:3
		},
		600:{
			items:3
		},
		1000:{
			items:5
		}
	}
});


$('#search_tag').on('change',function(){
	var searchtag = $(this).val();
	if(searchtag == 'hashtag')
	{
		var url = '/user/hashtag/hashtag';
		// $('.search-div').addClass('hidden');
	}
	else if(searchtag == 'person')
	{
		var url = '/user/hashtag/person';
		// $('.search-div').removeClass('hidden');
	}
	$.ajax({
		url : url,
		data : {'searchtag':$(this).val()},
		type : 'get',
		dataType :'JSON',
		success : function(res)
		{
			$('.searchdata').html(res);
		}
	});
});

</script>
@endpush