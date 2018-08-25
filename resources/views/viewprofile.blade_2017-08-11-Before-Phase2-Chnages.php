@extends('layouts.front')

@section('pagetitle', 'User Details')

@section('content')
<div class="content-container">
	<div class="container">
		<div>
			<div class="row sm-center wow fadeInDown">
				<div class="col-md-2 col-sm-3 col-xs-3 width100-500 pt5 mt5">
					<img src="{{ asset($user->imagefilepath) }}" alt="{{ $user->imagefilepath }}" class="img-responsive img-thumbnail inline-block rounded width100 max-width200-500"> 
				</div>
				<div class="col-md-8 col-sm-6 col-xs-5 width100-500">
					<p class="user-name sub-title text-capitalize">{{ $user->firstname }}</p>
					@if($user->aboutme != '')
						<div class="user-details mb5 pb5">{{ $user->aboutme }}</div>
					@else
					 	<p><i>NA</i></p>
					@endif	
					@if(isset($globaldata['user']))
                        @if($globaldata['user']->userid != $user->userid)
							@if($followuser->isEmpty())
								<a href="javascript:void(0);" class="user-follow" title="Follow" data-follow="1"  onclick="followUnfollow(this, {{ $user->userid }});">Follow</a> 												
							@else
								<a href="javascript:void(0);" class="user-follow" title="Unfollow" data-follow="0"  onclick="followUnfollow(this, {{ $user->userid }});">Unfollow</a>
							@endif
                        @endif
					@else
						<a class="user-follow" title="follow" href="javascript: void(0);" onclick="pleaseLogin();">Follow</a>
					@endif

				</div>
				<div class="col-md-2 col-sm-3 col-xs-4 width100-500 text-center pt5 mt5">
					<span id="followcount" class="followcount">{{ $followusercount }}</span>
					<br>
					<span class="followlabel">
					@if($followusercount == 1)
						Follower
					@else
						Followers
					@endif
					</span>
				</div>
			</div>
			<!-- seeris tabs -->
			<div>
				<ul class="nav nav-tabs seeris-tabs text-center wow fadeInDown">
				@foreach($user->series as $index => $series)
					<li class="{!! ($index == 0) ? 'active' : '' !!}"><a data-toggle="tab" href="#{{ $series->slug }}">{{ $series->title }}</a></li> 
				@endforeach
				</ul>

				<div class="clearfix"></div>

				<!-- seeris tabs -->
				<div class="tab-content seeris-content">
				@if(!$user->series()->get()->isEmpty())
					@foreach($user->series as $index => $series)
					<div id="{{ $series->slug }}" class="tab-pane fade {!! ($index == 0) ? 'in active' : '' !!}">
					@if(!$series->seriesmedias()->get()->isEmpty())
						@foreach($series->seriesmedias as $seriesmedia)
						<div class="row form-group relative wow fadeIn">
							<div class="col-md-3 col-xs-5 width100-500">
								<div data-seriesmediaid="{{ $seriesmedia->seriesmediaid }}" class="embed-responsive embed-responsive-4by3 sm video media-item @if($seriesmedia->ispublished) bindvideoplayanalytics @else na-video {{ $seriesmedia->publishdateday }} @endif" @if(!$seriesmedia->ispublished) data-publishdateair="{{ $seriesmedia->publishdateair }}" @endif>
									@if($seriesmedia->isfile)
										@if($seriesmedia->hasthumb)
											<div class="thumbimg sm" style="background-image: url({{ asset($seriesmedia->thumbpath) }});"></div>
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
										@endif
										<iframe class="embed-responsive-item" data-src="{{ $seriesmedia->workingurl }}" allowfullscreen></iframe>
									@endif
								</div>
							</div>
							<div class="col-md-9 col-xs-7 width100-500 static">
								<span class="user-episode">{{ $seriesmedia->title }}</span>
								<br><br>
								<span class="episode-disc color-grey">{{ $seriesmedia->description }}</span>
								<div class="likecmt">
									<span class="fa fa-heart-o nounderline like-unlike" title="Like"></span>&nbsp;
									<span class="like-unlike">
										{{ $seriesmedia->seriesmedialikesdislikes()->likes()->count() }}
									</span>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<span class="fa fa-comment-o comments nounderline" title="comment" aria-hidden="true"></span>&nbsp;
									<span class="comments">{{ $seriesmedia->seriesmediacomments()->active()->count() }}</span>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<span class="like-unlike"><i class="fa fa-play fa-rotate-180"></i> {{ $seriesmedia->seriesmediaclicks()->count() }}</span>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						@endforeach
					@else
						<h3 class="messagetext text-center wow fadeInDown">No episodes uploaded yet.</h3>
					@endif
					</div>
					@endforeach
				@else
					<h3 class="messagetext text-center wow fadeInDown">No series added yet.</h3>
				@endif
				</div>	
			</div>
			<!-- seeris tabs -->

		</div>
	</div>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('js/viewprofile.js?cache=1.6') }}"></script>
@endsection