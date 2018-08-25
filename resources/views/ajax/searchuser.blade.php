@if(count($hashtags) > 0)
				<?php $counter = 0;?>
				@foreach($hashtags as $myfollowuser)
					<?php $counter++; ?>
				<div class="row pt10">
					<a href="{{url('user/view/'.$myfollowuser['followerid'])}}">
						<div class="col-sm-3 col-xs-3 pt30">
							<div class="hashsign_container text-center">
								<span class="hashsign1" style="background-image: url({{ asset($myfollowuser->follower['imagefilepath']) }})"></span>
							</div>
						</div>
						<div class="col-sm-8 col-xs-8 padding-none">
							<span class="mt5 pt5 user-episode user-episode1 user-title">{{ $myfollowuser->follower['firstname'] }} {{ $myfollowuser->follower['lastname'] }}</span>
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
															<video id="series-{{$seriesmedia->seriesmediaid}}" class="background_none seeris-video embed-responsive-item bg" preload="metadata" playsinline loop>
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
			<script>
	$("document").ready(function() {
    setTimeout(function() {
       $('.navbar-search-inner').show();
       $('.navbar-search1').hide();

	       $('.search_back').click(function(event) {
			$('.navbar-search1').show();
			$('.navbar-search-inner').hide();
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

</script>