@extends('layouts.front')

@section('htmlclass', 'theme-black')

@section('pagetitle', (empty($series->seriesid))?'Start a Series':'Edit Series')

@section('content')
<div class="content-container">
	<div class="container">
		<div class="wow fadeIn">
			
			<h1 class="page-title color-pink">@if(empty($series->seriesid))Start a @else Edit @endif Series</h1>
			<!-- <div class="color-white"><h1>Progress <span id="progresspercent">0</span> %</h1></div> -->
			
			{!! Form::model($series, ['url' => '/series/store', 'id' => 'formseries']) !!}
				{!! Form::hidden('seriesid', null, ['id' => 'seriesid']) !!}
				
				<div class="row">
					<?php /* ?>
					<div class="col-xs-5 col-sm-5 col-md-4 col-lg-3 width50-500 seriesthumb">
						<div class="frame yellow">
							<div class="imgae-upload">
								<img src="{{ $series->filepath }}" alt="{{ $series->title }}" class="img-responsive" /> 
								<div class="uploader">
									<div class="fa fa-plus uploader-btn">
										{!! Form::file('file', ['class' => 'uploadfile']) !!}
									</div>
								</div>
							</div>
						</div>
					</div>
					@if(!empty($series->seriesid))
					<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2 width50-500 text-center pull-right">
						<div class="pb5 mb5"><span class="bg-white color-pink circle">{{ $seriesmediacounts }}</span></div>
						<div class="pt5 mt5"><span class="label bg-pink color-white inline-block"><strong>@if($seriesmediacounts == 1) Episode @else Episodes @endif</strong></span></div>
					</div>
					@endif
					<div class="@if(!empty($series->seriesid)) col-xs-4 col-sm-5 col-md-6 col-lg-7 @else col-xs-6 col-sm-7 col-md-8 col-lg-9 @endif width100-500">
						<div class="form-group">
							{!! Form::text('title', null, ['class' => 'form-control input-lg invisible-form-control', 'id' => 'title', 'placeholder' => 'Enter your seeris title here', 'title' => 'Click to edit']) !!}
						</div>
						<div class="form-group">
							{!! Form::textarea('description', null, ['class' => 'form-control invisible-form-control textarea-height', 'id' => 'description', 'placeholder' => 'Enter your seeris description here', 'title' => 'Click to edit']) !!}
						</div>
						@if(!empty($series->seriesid))
						<div>
							<a class="btn btn-bordered btn-pink btn-rounded btn-size18" href="javascript: void(0);" onclick="deleteSeries({{ $series->seriesid }});">Delete</a>
						</div>
						@endif
					</div>
					<?php */ ?>
					<div class="@if(!empty($series->seriesid)) col-xs-7 col-sm-10 col-md-10 col-lg-10 @else col-xs-12 col-sm-12 col-md-12 col-lg-12 @endif width100-5001">
						<div>
							{!! Form::text('title', null, ['class' => 'form-control input-lg invisible-form-control', 'id' => 'title', 'placeholder' => 'Enter your series title here', 'title' => 'Click to edit']) !!}
						</div>
					</div>
					@if(!empty($series->seriesid))
					<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 width50-5001 text-center pull-right">
						<div class="pb5 mb5"><span class="bg-white color-pink circle">{{ $seriesmediacounts }}</span></div>
						<div class="pt5 mt5 pb5 mb5"><span class="label bg-pink color-white inline-block"><strong>@if($seriesmediacounts == 1) Episode @else Episodes @endif</strong></span></div>
						<div class="pt5 mt5">
							<a href="javascript: void(0);" onclick="deleteSeries({{ $series->seriesid }});" class="bg-white color-black circle"><i class="fa fa-trash"></i></a>
						</div>
					</div>
					@endif
				</div>

				<div><hr class="white margin30"></div>

				<div class="series-media-container">

					@if($seriesmediacounts > 0)
					@foreach($series->seriesmedias as $index => $seriesmedia)
					<div class="series-media" data-counter="{{ $index+1 }}">
						<a class="deletemedia fa fa-trash color-white" onclick="deleteMedia(this);"></a>
						<div class="row">
							<div class="col-lg-3 col-md-4 col-sm-5 col-xs-5 left-media">
								<input type="hidden" name="seriesmediaid[]" value="{{ $seriesmedia->seriesmediaid }}" class="seriesmediaid" />
								<input type="hidden" name="seriesmediadeleted[]" value="0" class="seriesmediadeleted" />
								<input type="hidden" name="seriesmediaimmidiatepublish[]" value="{{ $seriesmedia->immidiatepublish }}" class="seriesmediaimmidiatepublish" />
								<input type="hidden" name="seriesmediavideotype[]" data-oldvideotype="{{ $seriesmedia->isfile }}" value="{{ $seriesmedia->isfile }}" class="seriesmediavideotype" />
								<input type="hidden" name="seriesmediahasvideo[]" value="0" class="seriesmediahasvideo" />
								<input type="hidden" name="seriesmediavideoindex[]" value="-1" class="seriesmediavideoindex" />
								<input type="hidden" name="seriesmediahasvideothumb[]" value="0" class="seriesmediahasvideothumb" />
								<input type="hidden" name="seriesmediavideothumbindex[]" value="-1" class="seriesmediavideothumbindex" />
								<div>
									<div class="video-upload">
										<div class="video-upload-in">
											<div class="video-files embed-responsive embed-responsive-4by3 media-item sm @if(!$seriesmedia->ispublished) na-video {{ $seriesmedia->publishdateday }} @endif" @if(!$seriesmedia->ispublished) data-publishdateair="{{ $seriesmedia->publishdateair }}" @endif>
												@if($seriesmedia->isfile)
													@if($seriesmedia->hasthumb)
													<div class="thumbimg" style="background-image: url({{ asset($seriesmedia->thumbpath) }});"></div>
													@endif
													@if($seriesmedia->hasfile)
													<video class="embed-responsive-item" controls>
														<source src="{{ $seriesmedia->filepath }}" type="video/mp4">
														<source src="{{ $seriesmedia->filepath }}" type="video/ogg">
														Your browser does not support HTML5 video.
													</video>
													@else
													<div class="embed-responsive-item noimage-bg"></div>
													@endif
												@else
													@if($seriesmedia->hasurlthumb)
													<div class="thumbimg sm" style="background-image: url({{ asset($seriesmedia->urlthumbpath) }});"></div>
													@else
													<iframe class="embed-responsive-item" data-src="{{ $seriesmedia->workingurl }}" allowfullscreen></iframe>
													@endif
												@endif
											</div>
											<div class="video-controls">
												<div class="form-group m0">
													<label class="icheck-label color-white">
														<input type="radio" @if($seriesmedia->isfile) checked="checked" @endif class="seriesmediavideotype_upload radios"> Upload
													</label>
													<div class="row row5">
														<div class="col-xs-10 col5">
															<input type="file" name="seriesmediavideofile[]" class="bootstrapfile seriesmideiavideouploader ignore-common video">	
														</div>
														<div class="col-xs-2 col5 icononly fixtitle tooltipred videothumbuploadcol" title="Use this to upload video thumbnail" data-toggle="tooltip" data-placement="bottom">
															<input type="file" name="seriesmediavideothumbfile[]" class="bootstrapfile seriesmideiavideouploaderthumb ignore-common">
														</div>
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
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-9 col-md-8 col-sm-7 col-xs-7 right-media">
								<div class="form-group">
									<input type="text" value="{{ $seriesmedia->title }}" name="seriesmediatitle[]" class="form-control input-md invisible-form-control seriesmediatitle" placeholder="Enter your episode title here" readonly="readonly" >
								</div>
								<div class="form-group">
									<textarea name="seriesmediadescription[]" class="form-control invisible-form-control textarea-height seriesmediadescription" placeholder="Enter your episode description here" title="Click to edit">{{ $seriesmedia->description }}</textarea>
								</div>
								<div class="ph12">
									<div class="color-white">
										<div class="mt5">When do you want this to air? </div>
										<div>
											<div class="form-group mb0 pull-left pt5 mr5 pr5 mt5">
												<label class="icheck-label pointer color-white">
													<input type="radio" @if($seriesmedia->immidiatepublish) checked="checked" @endif class="seriesmedia_immidiatepublish_yes radios"> Immediately
												</label>
											</div>
											<div class="form-group mb0 pull-left mr5 pr5">
												<label class="icheck-label pointer color-white pull-left pt5 mt5">
													<input type="radio" @if(!$seriesmedia->immidiatepublish) checked="checked" @endif class="seriesmedia_immidiatepublish_no radios"> <span class="inline-block pr5 mr5">Select Date & Time</span> <span class="fa fa-calendar"></span>
												</label>
												<input type="text" class="form-control invisible-form-control pull-left widthauto seriespublishdate mt3" name="seriespublishdate[]" @if(!$seriesmedia->immidiatepublish) value="{{ $seriesmedia->publishdatetime }}" @endif title="Click to edit" @if($seriesmedia->immidiatepublish) readonly="readonly" @endif />
												<div class="clearfix"></div>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
								<div class="color-white text-right ph12">
									<a class="inline-block nounderline mr5 pr5">
										<span class="fa fa-heart-o fa-2x mr5"></span>
										<span class="fa-2x"><small>{{ $seriesmedia->seriesmedialikesdislikes()->likes()->count() }}</small></span>
									</a>
									<a class="inline-block nounderline mr5 pr5 ml5 pl5" href="javascript:void(0);" onclick="openCommentsPopup({{ $seriesmedia->seriesmediaid }});">
										<span class="fa fa-comments-o fa-2x mr5"></span>
										<span class="fa-2x"><small>{{ $seriesmedia->seriesmediacomments()->count() }}</small></span>
									</a>
									<a class="inline-block nounderline ml5 pl5">
										<span class="fa fa-play fa-2x fa-rotate-180 mr5"></span>
										<span class="fa-2x"><small>{{ $seriesmedia->seriesmediaclicks()->count() }}</small></span>
									</a>
								</div>
							</div>
						</div>
					</div>
					@endforeach
					@endif

				</div>
				<div>
					<a class="btn btn-pink btn-sm" onclick="addNewMedia();">
						<i class="fa fa-plus"></i> Add New
					</a>
				</div>

				<div class="text-right pt5 mt5">
					<button type="submit" class="btn btn-warning">Submit</button>
				</div>
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
<script src="{{ asset('js/addseries.js?cache=1.9') }}"></script>
@endsection