@extends('layouts.front')

@section('pagetitle', (empty($series->seriesid))?'Start an Air':'Edit Airs')

@section('content')
<div class="content-container" id="AirPage">
	<div class="col-xs-12 col-sm-12 col-md-12 p-15-tab edit-info-section">
		<div class="col-xs-4 col-sm-4 col-md-4">
			<a href="{{ url('http://schemk.com/series') }}" class="back-to-page fadeInDown">
				<img src="/images/ic_back@2X.png">
			</a>
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4">
			<h1 class="page-title color-pink wow fadeInDown edit-airs-title no-padding no-margin">@if(empty($series->seriesid))Start a @else @endif Airs</h1>
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4 text-right">
			<a href="javascript:void(0)" class="edit-info fadeInDown">
				<!-- <img src="/images/ic_edit@2X.png"> -->
			</a>
		</div>
	</div>
	<div class="container">
		<div>
			{!! Form::model($series, ['url' => '/vlogs/store', 'id' => 'formseries']) !!}
				{!! Form::hidden('seriesid', null, ['id' => 'seriesid']) !!}
				<div class="row sm-center">
					<div class="col-md-12 col-xs-12 col-sm-12 pt5 mt5 wow fadeInDown profile-wrap">
						<img src="{{ asset($globaldata['user']->imagefilepath) }}" alt="{{ $globaldata['user']->imagefilepath }}" class="border-hot-pink img-responsive img-thumbnail inline-block rounded width100 max-width200-500"> 
					</div>
					<div class="col-md-12 col-xs-12 col-sm-12 wow fadeInDown user-detail-wrap">
						<p class="user-name sub-title text-capitalize">{{ $globaldata['user']->firstname }}</p>
						@if($globaldata['user']->aboutme != '')
							<div class="user-details mb5 pb5 pt-10-tab">{{ $globaldata['user']->aboutme }}</div>
						@else
						 	<p><i>NA</i></p>
						@endif

						<div class="col-md-12 col-sm-12 col-xs-12 text-center pt5 wow fadeInDown">
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
						</div>
						<div class="clearfix"></div>
						<div class="col-md-12 col-sm-12 col-xs-12 text-center air-counts wow fadeInDown">
							<strong>{{ $seriesmediacounts }} @if($seriesmediacounts == 1) Air @else Airs @endif</strong>
						</div>
					</div>
				</div>

				<div class="wow fadeInDown"><hr class="margin30 hr-border-grey"></div>

				<div class="series-media-container">

					@if($seriesmediacounts > 0)
					@foreach($seriesmedias as $index => $seriesmedia)
					<div class="series-media wow fadeIn airs-blog-container" data-counter="{{ $index+1 }}">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group form-group-flex">
								<input type="text" value="{{ $seriesmedia->title }}" name="seriesmediatitle[]" class="form-control input-md invisible-form-control seriesmediatitle seriesmediatitlefont" placeholder="Enter Caption" readonly>
								<a class="" onclick="deleteMedia(this);">
									<img src="/images/ic_delete@2X.png" width="18px" height="18px">
								</a>
							</div>
						</div>
						<!-- <div class="col-xs-2 col-sm-2 col-md-2"> -->
							<!-- <a class="deletemedia" onclick="deleteMedia(this);"> -->
							<!-- <a class="" onclick="deleteMedia(this);">
								<img src="/images/ic_delete@2X.png" width="18px" height="18px">
							</a> -->
						<!-- </div> -->
						<div class="">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-media no-l-r-pad">
								<input type="hidden" name="seriesmediaid[]" value="{{ $seriesmedia->seriesmediaid }}" class="seriesmediaid" />
								<input type="hidden" name="seriesmediadeleted[]" value="0" class="seriesmediadeleted" />
								<input type="hidden" name="seriesmediaimmidiatepublish[]" value="{{ $seriesmedia->immidiatepublish }}" class="seriesmediaimmidiatepublish" />
								<input type="hidden" name="seriesmediavideotype[]" data-oldvideotype="{{ $seriesmedia->isfile }}" value="{{ $seriesmedia->isfile }}" class="seriesmediavideotype" />
								<input type="hidden" name="seriesmediahasvideo[]" value="0" class="seriesmediahasvideo" />
								<input type="hidden" name="seriesmediavideoindex[]" value="-1" class="seriesmediavideoindex" />
								<input type="hidden" name="seriesmediahasvideothumb[]" value="0" class="seriesmediahasvideothumb" />
								<input type="hidden" name="seriesmediavideothumbindex[]" value="-1" class="seriesmediavideothumbindex" />
								<input type="hidden" name="seriespublishdate[]" value="{{ (!$seriesmedia->immidiatepublish) ? $seriesmedia->publishdatetime : '' }}" class="seriespublishdate" />
								<div>
									<div class="video-upload">
										<div class="video-upload-in">
											<div class="video video-files embed-responsive embed-responsive-16by9 media-item sm @if(!$seriesmedia->ispublished) na-video with-uploader {{ $seriesmedia->publishdateday }} @endif" @if(!$seriesmedia->ispublished) data-publishdateair="{{ $seriesmedia->publishdateair }}" @endif>
												<div class="rel-video {{ $seriesmedia->publishdateday }}" @if(!$seriesmedia->ispublished) data-publishdateair="{{ $seriesmedia->publishdateair }}" @endif>
													{{ $seriesmedia->publishdateair }}
												</div>
												@if(!$seriesmedia->ispublished)
													@if($seriesmedia->imagefile)
														@if(date('a', strtotime($seriesmedia->publish_time)) == 'pm')
															<div class="bannertop moonlight">
																<img src="/images/moon.png" height="14" width="14">
																<span class="moonlighttext">{{ ucfirst($seriesmedia->series->user->firstname) }}'s Stories airs every {{ $seriesmedia->publishdateday }} at {{ date('ha', strtotime($seriesmedia->publish_time)) }}</span>
															</div>
														@else
															<div class="bannertop sunlight">
																<img src="/images/sun.png" height="18" width="18">
																<span class="sunlighttext">{{ ucfirst($seriesmedia->series->user->firstname) }}'s Stories airs every {{ $seriesmedia->publishdateday }} at {{ date('ha', strtotime($seriesmedia->publish_time)) }}</span>
															</div> 
														@endif
													@endif
												@endif
												@if($seriesmedia->isfile)
													@if($seriesmedia->hasthumb)
													<div class="thumbimg noplaybtn" style="background-image: url({{ asset($seriesmedia->thumbpath) }});"></div>
													@endif
													@if($seriesmedia->hasfile)
													
													<video onload="javascript:setHeight(this)" class=" embed-responsive-item seeris-video" preload="metadata" {{($index == 0)?'':'muted="muted"'}} playsinline  loop >
														<source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/mp4">
														<source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/ogg">
														<source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/ogg">
														<source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/quicktime">
														Your browser does not support HTML5 video.
													</video>
													<img src="{{ asset('images/videobtn.png') }}" alt="" class="playbtn">
													@else
													
													<div class="embed-responsive-item @if(!$seriesmedia->imagefile) noimage-bg @endif">
														@if($seriesmedia->imagefile)
															<a href="/uploads/{{ $seriesmedia->imagefile }}" class="pop" data-lightbox="image-{{$seriesmedia->seriesmediaid}}" data-title="{{ $seriesmedia->title }}"> <img onload="javascript:setHeight(this)" src="/uploads/{{ $seriesmedia->imagefile }}" class="popup-image tall @if($seriesmedia->ispublished) @else  @if($seriesmedia->imagefile) imageBlur  @endif @endif"></a>
														@endif
													</div>
													@endif
												@else
													@if($seriesmedia->hasurlthumb)
													<div class="thumbimg noplaybtn sm" style="background-image: url({{ asset($seriesmedia->urlthumbpath) }});"></div>
													@else
													<iframe class="embed-responsive-item" data-src="{{ $seriesmedia->workingurl }}" allowfullscreen></iframe>
													@endif
												@endif
											</div>
<!-- 											<div class="upload-icon form-group m0"> -->
<!-- 												<div class="circle"> -->
<!-- 													<i class="fa fa-cloud-upload"></i> -->
<!-- 													<input type="file" name="seriesmediavideofile[]" class="seriesmideiavideouploader video">		 -->
<!-- 												</div> -->
<!-- 											</div> -->
											@if(!$seriesmedia->imagefile)
											<div class="video-controls">
												<div class="form-group m0">
													<label class="icheck-label color-white">
														<input type="radio" @if($seriesmedia->isfile) checked="checked" @endif class="seriesmediavideotype_upload radios"> Upload
													</label>
													<div class="row row5">
														<div class="col-xs-10 col5">
															<!-- <input type="file" name="seriesmediavideofile[]" class="bootstrapfile seriesmideiavideouploader ignore-common video">	 -->
														</div>
														<!--<div class="col-xs-2 col5 icononly fixtitle tooltipred videothumbuploadcol" title="Use this to upload video thumbnail" data-toggle="tooltip" data-placement="bottom">
															<input type="file" name="seriesmediavideothumbfile[]" class="bootstrapfile seriesmideiavideouploaderthumb ignore-common">
														</div>-->
														<!--<div class="col-xs-2 col5 icononly   videothumbuploadcol" title="Use this to upload video thumbnail" data-toggle="tooltip" data-placement="bottom">
															<input type="file" name="seriesmediavideothumbfile[]" class="bootstrapfile seriesmideiavideouploaderthumb ignore-common">
														</div>-->
													</div>
												</div>
												<div class="text-center mt5 mb5 pt5 color-yellow"><strong>Or</strong></div>
												<div class="form-group m0">
													<label class="icheck-label color-white">
														<input type="radio" @if(!$seriesmedia->isfile) checked="checked" @endif class="seriesmediavideotype_url radios"> Add a link
													</label>
													<input type="text" name="seriesmediavideourl[]" class="form-control videourl" @if(!$seriesmedia->isfile) value="{{ $seriesmedia->fileurl }}" @endif placeholder="http://" @if($seriesmedia->isfile) readonly="readonly" @endif>
												</div>
											</div>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<!-- <div class="form-group">
									<input type="text" value="{{ $seriesmedia->title }}" name="seriesmediatitle[]" class="form-control input-md invisible-form-control seriesmediatitle" placeholder="Enter Caption" readonly>
								</div> -->
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
										@if($seriesmedia->imagefile)
											<span class="fa-2x mr5 air-page-play-font ver-middle"><i class="fa fa-file-image-o"></i></span>

										@else
											<span class="fa-2x mr5 air-page-play-font ver-middle"><small>{{ $seriesmedia->seriesmediaclicks()->count() }}</small></span>
											<span class="play-video-icon inline-block ver-middle"></span>
										@endif
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
												<img src="/images/ic_unavailable@2X.png" class="ver-text-top" width="13px">
											@else
												<img src="/images/ic_available@2X.png" class="ver-text-top" width="13px">
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
						</div>
					</div>
					@endforeach
					@endif
				</div>
				<!--<div>
					<a class="btn btn-pink btn-sm" onclick="addNewMedia();">
						<i class="fa fa-plus"></i> Add New
					</a>
				</div> -->
<!-- 				<div class="text-right pt5 mt5"> -->
<!-- 					<button type="submit" class="btn btn-warning">Submit</button> -->
<!-- 				</div> -->
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection

@section('modal')
<div id="seriesmediacommentsmodal" class="modal md fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title modeltitle"></h4>
			</div>
			<div class="modal-body minheight200">
				<div class="comment-model">
					<div class="loaddata"></div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('js/addseries.js?cache=2.4') }}"></script>
<script>
	console.log(screen.width, screen.height, "vlogs/edit", $('.video'));

	setHeight = function(obj) {
		$('.video').css('height', (screen.width - 30) * 0.9);
		
		if(obj.tagName == 'IMG') {
			$("<img/>") // Make in memory copy of image to avoid css issues
			.attr("src", $(obj).attr("src"))
			.load(function() {
				console.log(this.width, this.height);
				pic_real_width = this.width;   // Note: $(this).width() will not
				pic_real_height = this.height; // work for in memory images.
				var imgClass = (this.width / this.height > 1) ? 'wide' : 'tall';
				$(obj).addClass(imgClass);
			});
		}
	}

	$()

</script>
@endsection
