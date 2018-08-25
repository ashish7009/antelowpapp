@extends('layouts.front')

@section('pagetitle', 'User Details')

@section('content')
<style>
.newvideobanner{
	position: absolute;
    text-align: center;
    height: 2%;
    background: #030217;
    left: 5%;
    right: 5%;
    top: 40px;
    bottom: 5%;
    border-radius: 10px;
}
.newvideotext1{
	    font-size: 17px;
	    font-weight: 1000;
    display: flex;
    padding-left: 11%;
    padding-top: 9%;
}
.newlightboxOverlay{
position: absolute;
    top: 0;
    left: 0;
    z-index: 9999;
   width: 100%;
   color:white;
    background: #4c4a4b2b;
    display: none;
}
.air-count .count {
   position: absolute;
    width: 18px;
    height: 18px;
    background: #ee2d4f;
    color: white;
    text-align: center;
    border-radius: 50%;
    top: -1px;
    left: 165px;
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99;
}

.air-count .count-title {
   position: absolute;
    height: 20px;
    background: none !important;
    top: 2px;
    left: 145px;
    width: auto;
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-align: center;
    border-radius: 15px;
    padding: 5px;
}
.upcoming_episode_popover{
    position: absolute;
    top: -30px;
    left: 152px;
    font-size: 13px;
    background-color: #FFFFFF;
    border-radius: 10px;
    width: 130px;
    padding: 2px 8px;
    color: #000000;
}
</style>
<div class="content-container" id="UserViewPage">
	<div class="container">
		<div>
			<div class="row sm-center wow fadeInDown ">
				<div class="col-xs-12 col-sm-12 col-ms-12 pt5 mt5">
					<!-- <img src="{{ asset($user->imagefilepath) }}" alt="{{ $user->imagefilepath }}" class="img-responsive img-thumbnail inline-block rounded width100 max-width200-500">  -->
					<a href="{{ secure_url('user/hashtag') }}" class="back-to-page fadeInDown">
						<img src="{{secure_asset('/images/ic_back@2X.png')}}">
					</a>
					<div class="user-circle-avatar border-hot-pink" style="background-image: url({{secure_asset($user->imagefilepath)}}); background-size: cover;"></div>
					<span class="report-user" data-userid="{{ $globaldata['user']->userid }}">Report User</span>
				</div>
				<div class="col-xs-12 col-sm-12 col-ms-12">
					<p class="user-name sub-title text-capitalize">{{ $user->firstname }}</p>
					@if($user->aboutme != '')
					<div class="comment-more userprofile-more user-details mb5 pb5 pt-10-tab" >{{ $user->aboutme }}</div>
					@else
					<p><i>NA</i></p>
					@endif
				</div>
				<div class="col-xs-12 col-sm-12 col-ms-12">
					<div class="row">
							<div class="s1">
								<span id="followcount">{{ $followusercount }}</span><br><span class="s1_gary">
								@if($followusercount == 1) Follower @else Followers @endif</span>
							</div>
							<div class="s2">
								<span id="followcount">{{ $truefc }}/25</span><br><span class="s1_gary">
								@if($truefc == 1) Truefriend @else Truefriends @endif</span>
								
							</div>
						</div>
						<span class="follow-un-follow-container" style="position: relative; z-index: 999;    top: 8px;">
						<!--<i class="fa fa-sort-desc follow-un-follow-icon">-->
							@if(isset($globaldata['user']))
							@if($globaldata['user']->userid != $user->userid)
							@if($followuser->isEmpty())
							<a href="javascript:void(0);" class="un-follow-btn-single " title="Follow" data-follow="1"  onclick="followUnfollow(this, {{ $user->userid }});">Follow</a> 												
							@else
							<a href="javascript:void(0);" class="un-follow-btn-single " title="Unfollow" data-follow="0"  onclick="followUnfollow(this, {{ $user->userid }});"><i class="fa fa-check"></i> &nbspUnfollow</a>
							@endif
							@endif
							@else
							<a class="un-follow-btn-single" title="follow" href="javascript: void(0);" onclick="pleaseLogin();">Follow</a>
							@endif
						<!--</i>-->
					</span>
				<!--	<span class="follow-btn-single">
						{{ $followusercount }}
						@if($followusercount == 1)
						Follower
						@else
						Followers
						@endif
					</span>
					<span class="follow-un-follow-container" style="position: relative; z-index: 999;">
						<i class="fa fa-sort-desc follow-un-follow-icon">
							@if(isset($globaldata['user']))
							@if($globaldata['user']->userid != $user->userid)
							@if($followuser->isEmpty())
							<a href="javascript:void(0);" class="un-follow-btn-single hide" title="Follow" data-follow="1"  onclick="followUnfollow(this, {{ $user->userid }});"><i class="fa fa-check"></i> &nbspFollow</a> 												
							@else
							<a href="javascript:void(0);" class="un-follow-btn-single hide" title="Unfollow" data-follow="0"  onclick="followUnfollow(this, {{ $user->userid }});"><i class="fa fa-times"></i> &nbspUnfollow</a>
							@endif
							@endif
							@else
							<a class="un-follow-btn-single" title="follow" href="javascript: void(0);" onclick="pleaseLogin();">Follow</a>
							@endif
						</i>
					</span>
					<span class="follow-btn-single fill-style">
						{{ $truefc }}
						@if($truefc == 1)
						Truefriend
						@else
						Truefriends
						@endif
					</span>-->
				</div>
				<div class="hr-line-bottom"></div>
			</div>
			<div class="clearfix"></div>
			<div>
				<div class="tab-content seeris-content m0">
					@if(isset($seriesmedias) && count($seriesmedias) > 0)
					@foreach($seriesmedias as $seriesmedia)
					<div class="row form-group relative wow fadeIn single-user-container">
						<div class="comment-report-section">
							<div class="report-content-flag" data-seriesmediaid="{{ $seriesmedia->seriesmediaid }}">
								<!-- Report -->
								<img src="{{secure_asset('/images/flag.png')}}" width="17px">
								<ul>
									<li>It's offensive</li>
									<li>It's a spam</li>
									<li>Something else</li>
								</ul>
							</div>
						</div>

						<div class="col-xs-10 col-sm-10 col-md-10">
							<a href="{{ secure_url('series/'.$seriesmedia->series->slug.'/'.seriesmedia_unique_string($seriesmedia)) }}" target="_blank">
								<span class="seriesmediatitlefont details user-episode comment-more xmore">
									{{ $seriesmedia->title }}
								</span></a>
							</div>
							<!-- <div class="col-xs-2 col-sm-2 col-md-2">
								<div class="report-content report-content-text get-report-middel" data-seriesmediaid="{{ $seriesmedia->seriesmediaid }}" style="right: 16px">
									<img src="/images/Report icon.svg" width="17px">
									<ul>
										<li>It's offensive</li>
										<li>It's a spam</li>
										<li>Something else</li>
									</ul>
								</div>
							</div> -->
							<div class="clearfix"></div>
							<div class="col-md-3 col-xs-5 width100-500 no-padding @if($seriesmedia->imagefile) imageheight @endif">
								<div data-seriesmediaid="{{ $seriesmedia->seriesmediaid }}" class="video embed-responsive embed-responsive-4by3 sm video media-item @if($seriesmedia->ispublished) bindvideoplayanalytics @else na-video {{ $seriesmedia->publishdateday }} @endif" @if(!$seriesmedia->ispublished) data-publishdateair="{{ $seriesmedia->publishdateair }}" @endif>
									
									<?php if(!$seriesmedia->imagefile && !$seriesmedia->ispublished){ ?>
						<div class="vid_preview"><i class="fa fa-video-camera xcam" aria-hidden="true"></i> Preview
</div>	 
						<?php } ?>	
									<div class="rel-video {{ $seriesmedia->publishdateday }}" @if(!$seriesmedia->ispublished) data-publishdateair="{{ $seriesmedia->publishdateair }}" @endif>
										
										        <div class="{{ $seriesmedia->publishdateday }} watchlike "><span>
						<?php if(!$seriesmedia->imagefile){ ?>
						<i class="fa fa-video-camera" aria-hidden="true"></i>
						<?php }else{ ?>
						<div style="height:25px;width:25px;"></div>
						<?php } ?>
<?php
echo str_replace('in ','<div class="on">in</div>',str_replace('on ','<div class="on">on</div>',str_replace('You can see this','<div class="avail">Available</div>',$seriesmedia->publishdateair))); ?></span>

</div>
									</div>
									@if(!$seriesmedia->ispublished)
										@if($seriesmedia->imagefile)
											@if(date('a', strtotime($seriesmedia->publish_time)) == 'pm')
											<div class="bannertop moonlight">
												<img src="{{ secure_asset('/images/moon.png')}}" height="14" width="14">
												<span class="moonlighttext">{{ ucfirst($seriesmedia->series->user->firstname) }}'s Stories airs every {{ $seriesmedia->publishdateday }} at {{ date('ha', strtotime($seriesmedia->publish_time)) }}</span>
											</div>
											@else
											<div class="bannertop sunlight">
												<img src="{{ secure_asset('/images/sun.png')}}" height="18" width="18">
												<span class="sunlighttext">{{ ucfirst($seriesmedia->series->user->firstname) }}'s Stories airs every {{ $seriesmedia->publishdateday }} at {{ date('ha', strtotime($seriesmedia->publish_time)) }}</span>
											</div> 
											@endif
										@endif
									@endif
									@if($seriesmedia->imagefile)
									<a href="/uploads/{{ $seriesmedia->imagefile }}" class="pop" data-lightbox="image-{{$seriesmedia->seriesmediaid}}" data-title="{{ $seriesmedia->title }}">
										<img src="{{ secure_asset('/uploads/'.$seriesmedia->imagefile)}}" class="popup-image tall @if($seriesmedia->ispublished) @else  @if($seriesmedia->imagefile) imageBlur  @endif @endif">
									</a>
									@else

									@if($seriesmedia->isfile)
									@if($seriesmedia->hasthumb)
									<div class="thumbimg sm" style="background-image: url({{ asset($seriesmedia->thumbpath) }});"></div>
									@endif
									@if($seriesmedia->hasfile)
									<video id="series-{{$seriesmedia->seriesmediaid}}" class="seeris-video embed-responsive-item" preload="metadata" playsinline muted loop>
										<source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/mp4">
											<source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/ogg">
												<source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/quicktime">
													Your browser does not support HTML5 video.
												</video>
												<img src="{{ asset('images/videobtn.png') }}" alt="" class="playbtn">
												<div class = "newlightboxOverlay requestcounter{{ $seriesmedia->seriesmediaid }}" style="height: 11278px; display: none;">
                                 	<div class = "newvideobanner">
                                 	<div style = "position: absolute;right: -37%;top: 4%;">
                                 		<img src = "https://www.schemk.com/images/add_new_close_btn.png" class="close_banner" style = "width: 8%;"> 
                                 	</div>
                                 		<div>
                                 		<span class ="newvideotext1">See the rest of it <?php echo str_replace('You can see this','',$seriesmedia->publishdateair); ?></span>
                                 			<a class="profile" href="{{ url('/user/view/'.$seriesmedia->series->userid) }}">
                <img src="{{ asset($seriesmedia->series->user['imagefilepath']) }}"
                     alt="{{ ucfirst($seriesmedia->series->user['fullname']) }}"
                     class="img-responsive userprofile inline-block img-thumbnail" style = "position: absolute;left: 40%;">          						<span style = "    position: absolute;left: 50%;top: 50%;
    transform: translateX(-50%);"> {{ ucfirst($seriesmedia->series->user['fullname']) }}</span>
                     					</a> 
                     
                     <span style = "padding-top: 25%;display: flex;padding-left: 12%;"> 
                     Let {{ ucfirst($seriesmedia->series->user['firstname']) }} know that you are interested in seeing this
                     </span>
                     
                     
                        <a href="javascript:void(0);" onclick="counterwatchvideo1(this,{{ $seriesmedia->seriesmediaid}} );"
                        	style = "
                        	background: #f31685;
				    display: flex;
				    height: 35px;
				    margin-left: 25%;
				    margin-right: 25%;
				    margin-top: 2%;
				    border-radius: 11px;
				                        	"
                        >
                        <i class="fa fa-star" id="interested601" aria-hidden="true" style = "display: flex;
    align-items: center;
    margin-left: 10%;"></i>
                        	<span style = "
                        		    display: flex;
					    margin-left: 17%;
					    font-size: 14px;
					    text-align: center;
					    align-items: center;
                        	"
                        	
                        	>I'm interested</span>
                        </a>
                     
            					</div>
                                 	</div>
                                 </div>
												@else
												<div class="embed-responsive-item noimage-bg">{{$seriesmedia->hasfile}}</div>
												@endif
												@else
												@if($seriesmedia->hasurlthumb)
												<div class="thumbimg sm" style="background-image: url({{ asset($seriesmedia->urlthumbpath) }});"></div>
												@endif
												<iframe class="embed-responsive-item" data-src="{{ $seriesmedia->workingurl }}" allowfullscreen></iframe>
												@endif
												@endif
											</div>
										</div>
										<div class="clearfix"></div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px; padding-bottom: 15px;">
											<div class="ph12">
												<div>
													<div class="mt5" style="display:none">When do you want this to air?</div>
													<div class="row row2">
														<div style="display:none" class="form-group mb0 pt5 mt5 col2 col-lg-3 col-md-4 col-sm-6 col-xs-12">
															<label class="icheck-label pointer">
																<input type="radio" @if($seriesmedia->immidiatepublish) checked="checked" @endif class="seriesmedia_immidiatepublish_yes radios"> Air Immediately
															</label>
															<span class="inline-block"><span class="visible-xs">or</span></span>
														</div>
														<div style="display:none" class="pt5 mt5 col2 col-lg-1 col-md-6 col-sm-6 hidden-xs">or</div>
														<div class="form-group mb0 col2 col-lg-8 col-md-12 col-sm-12 col-xs-12">
															<div class="row row2">
																<div style="display:none" class="col2 col-lg-4 col-md-4 col-sm-12 col-xs-12">
																	<label class="icheck-label pointer pull-left pt5 mt5">
																		<input type="radio" @if(!$seriesmedia->immidiatepublish) checked="checked" @endif class="seriesmedia_immidiatepublish_no radios"> 
																		<span class="inline-block">Select Date & Time</span>
																	</label>		
																</div>
																<div class="col2 col-lg-8 col-md-8 col-sm-12 col-xs-12">
																	<div class="row row2">
																		<!-- 																<div class="col2 col-lg-5 col-md-5 col-sm-5 col-xs-6"> -->
																			<!-- 																	<div class="row row2 pt5"> -->
																				<!-- 																		<div class="pt5 form-group col2 col-lg-12 col-md-12 col-sm-12 col-xs-12"> -->
																					<!-- 																			<select class="form-control form-control-small seriesmediaimmidiatepublishno-controls seriesmediaday" name="publish_day[]" disabled> -->
																						<!-- 																				<option>DAY</option> -->
																						<!-- 																				<option value="Mondays" @if($seriesmedia->publish_day != "") @if(  $seriesmedia->publish_day == 'Mondays') selected @endif @endif > Every Monday </option> -->
																						<!-- 																				<option value="Tuesdays"  @if($seriesmedia->publish_day != "") @if(  $seriesmedia->publish_day == 'Tuesdays') selected @endif @endif > Every Tuesday </option> -->
																						<!-- 																				<option value="wednesdays"  @if($seriesmedia->publish_day != "") @if(  $seriesmedia->publish_day == 'Wednesdays') selected @endif @endif > Every Wednesday </option> -->
																						<!-- 																				<option value="Thursdays"  @if($seriesmedia->publish_day != "") @if(  $seriesmedia->publish_day == 'hursdays') selected @endif @endif > Every Thursday </option> -->
																						<!-- 																				<option value="Fridays"  @if($seriesmedia->publish_day != "") @if(  $seriesmedia->publish_day == 'Fridays') selected @endif @endif > Every Friday </option> -->
																						<!-- 																				<option value="Saturdays"  @if($seriesmedia->publish_day != "") @if(  $seriesmedia->publish_day == 'Saturdays') selected @endif @endif > Every Saturday </option> -->
																						<!-- 																				<option value="Sundays"  @if($seriesmedia->publish_day != "") @if(  $seriesmedia->publish_day == 'Sundays') selected @endif @endif > Every Sunday </option> -->
																						<!-- 																			</select> -->
																						<!-- 																		</div> -->
																						<!-- 																	</div> -->
																						<!-- 																</div> -->
																						<!-- 																<div class="col2 col-lg-6 col-md-6 col-sm-6 col-xs-6"> -->
																							<!-- 																	<div class="row row2 pt5"> -->
																								<!-- 																		<div class="pt5 form-group col2 col-lg-5 col-md-5 col-sm-5 col-xs-6"> -->
																									<!-- 																			<select class="form-control form-control-small seriesmediaimmidiatepublishno-controls seriesmediahour" name="seriesmediahour[]" disabled> -->
																										<!-- 																				<option value="">HH</option> -->
																										<!-- 																				@for($i=0;$i <= 23;$i++) -->
																										<!-- 																					<option @if($seriesmedia->publish_time != "") @if(  explode(':',$seriesmedia->publish_time)[0]  == $i) selected @endif @endif value="{{ addPrecedingZero($i) }}">{{ addPrecedingZero($i) }}</option> -->
																										<!-- 																				@endfor -->
																										<!-- 																			</select> -->
																										<!-- 																		</div> -->
																										<!-- 																		<div class="pt5 text-center hidden-xs col2 col-xs-1">:</div> -->
																										<!-- 																		<div class="pt5 form-group col2 col-lg-5 col-md-5 col-sm-5 col-xs-6"> -->
																											<!-- 																			<select class="form-control form-control-small seriesmediaimmidiatepublishno-controls seriesmediaminute" name="seriesmediaminute[]" disabled> -->
																												<!-- 																				<option value="">MM</option> -->
																												<!-- 																				@for($i=0;$i <= 59;$i++) -->
																												<!-- 																					<option @if($seriesmedia->publish_time != "") @if(  explode(':',$seriesmedia->publish_time)[1]  == $i) selected @endif @endif value="{{ addPrecedingZero($i) }}">{{ addPrecedingZero($i) }}</option> -->
																												<!-- 																				@endfor -->
																												<!-- 																			</select> -->
																												<!-- 																		</div> -->
																												<!-- 																	</div> -->
																												<!-- 																</div> -->
																											</div>
																										</div>
																									</div>
																									<!-- <input type="text" class="form-control invisible-form-control pull-left widthauto seriespublishdate mt3" name="seriespublishdate[]" @if(!$seriesmedia->immidiatepublish) value="{{ $seriesmedia->publishdatetime }}" @endif title="Click to edit" @if($seriesmedia->immidiatepublish) readonly="readonly" @endif /> -->
																								</div>
																							</div>

																						</div>
																					</div>
																					<div class="">
																						<div class="col-xs-7 col-sm-7 col-md-7 no-padding">
																							<a class="inline-block nounderline">
																								@if( $seriesmedia->seriesmedialikesdislikes()->likes()->count() == 0 )
																								<span class="fa-2x mr5 air-page-like-font ver-middle"><small>{{ intval($globaldata['user']->likeinfluencer) + intval($seriesmedia->seriesmedialikesdislikes()->likes()->count()) }}</small></span>
																								<span class="fs-17-tab fa fa-heart-o fa-2x ver-middle" style="color: #979797;"></span>
																								@else
																								<span class="fa-2x mr5 color-hot-pink air-page-like-font ver-middle"><small>{{ intval($globaldata['user']->likeinfluencer) + intval($seriesmedia->seriesmedialikesdislikes()->likes()->count()) }}</small></span>
																								<span class="fs-17-tab fa fa-heart fa-2x color-hot-pink ver-middle" style="color: #979797;"></span>
																								@endif
																							</a>
																							&nbsp;&nbsp;&nbsp;
																							<a class="inline-block nounderline">
																								<span class="fa-2x mr5 air-page-play-font ver-middle"><small>{{ $seriesmedia->seriesmediaclicks()->count() }}</small></span>
																								<span class="play-video-icon inline-block ver-middle"></span>
																							</a>
																							&nbsp;&nbsp;&nbsp;
																							<a class="inline-block nounderline" href="javascript:void(0);" onclick="openCommentsPopup({{ $seriesmedia->seriesmediaid }});">
																								<span class="fa-2x mr5 air-page-comment-font ver-middle"><small>{{ $seriesmedia->seriesmediacomments()->count() }}</small></span>
																								<span class="fs-17-tab fa fa-comments-o fa-2x ver-middle" style="color: #979797;"></span>
																							</a>
																						</div>
																						<div class="col-xs-5 col-sm-5 col-md-5 no-padding text-right">
																							<a class="" @if(!$seriesmedia->ispublished) onclick="publishMedia(this);" @endif >
																								@if(!$seriesmedia->ispublished)
										<img src="{{secure_asset('/images/ic_unavailable@2X.png')}}" class="ver-text-top" width="13px">
																								@else
										<img src="{{secure_asset('/images/ic_available@2X.png')}}" class="ver-text-top" width="13px">
																								@endif
																								<span class="fs-14-tab">@if($seriesmedia->ispublished) Is Available @else Make available @endif</span>
																							</a>
																						</div>
																					</div>
																					@if($seriesmedia->isprocessing == 1)
																					<p class="text-danger ph12">
																						This video is still being processed. This usually takes few minutes.
																					</p>
																					@endif
																					@if($seriesmedia->status == 2)
																					<p class="text-danger ph12">
																						This video has exceeded the max allowed duration of 60 sec.
																					</p>
																					@endif
																				</div>
																				<div class="clearfix"></div>
																			</div>
																			@endforeach
																			@else
																			<h3 class="messagetext text-center wow fadeInDown">No airs found.</h3>
																			@endif
																		</div>	
																	</div>
																	<!-- seeris tabs -->
																</div>
															</div>
														</div>
														@endsection

														@section('page-scripts')
														<script src="{{ secure_asset('js/viewprofile.js?cache=2.1') }}"></script>
														<script type="text/javascript">
															jQuery(document).ready(function()
															{
																jQuery('.follow-un-follow-icon').click(function()
																{
																	jQuery(this).find('.un-follow-btn-single').toggleClass('hide');
																	jQuery(this).find('.un-follow-btn-single').toggleClass('dispay-follow-un-follow');
																});
															});
														</script>
														@endsection

														@push('footer_script')
														<script>
															var showChar = 150;
															var ellipsestext = "...";
															var moretext = "more";
															var lesstext = "less";
															$('.userprofile-more').each(function() {
																var content = $(this).html();

																if(content.length > showChar) {

																	var c = content.substr(0, showChar);
																	var h = content.substr(showChar, content.length - showChar);

																	var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="javascript:void(0);" class="userprofile_morelink">' + moretext + '</a></span>';

																	$(this).html(html);
																}
															});
															$(".userprofile_morelink").click(function(){
																if($(this).hasClass("less")) {
																	$(this).removeClass("less");
																	$(this).html(moretext);
																} else {
																	$(this).addClass("less");
																	$(this).html(lesstext);
																}
																$(this).parent().prev().toggle();
																$(this).prev().toggle();
																return false;
															});

															$('.width100-500').css('height', screen.width * 0.9);
														</script>
														@endpush