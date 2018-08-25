@extends('layouts.front')

@section('pagetitle', 'Series: The #1 Broadcasting Channel For Online Vloggers. Upload Your Vlogs On Series and Get Paid For Each Episode.')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/home.css?cache=1.16') }}">
@endsection

@section('content')
<div class="content-container p0">
	<div class="bg-yellow color-white home-top-section">
		<div class="container">
			<div class="text-center">
				<span class="wow fadeIn">Don't be boring</span><br>
				<a class="logo relative wow fadeIn" href="{{ url('/') }}">
					<span class="top-logo">Seeris</span>
					<span class="beta">beta</span>
				</a>
				<div class="heading wow fadeIn">The first Broadcasting channel for Vloggers</div>
				<div><a class="heading2 wow fadeIn" href="{{ url('/vlogs/edit') }}">Create your vlog series</a></div>
				<!-- <div>
					<b class="color-black wow fadeIn">You don't have to be in Hollywood to create a web Seeris. All you need is a mobile phone and something interesting to talk about!</b>
				</div> -->
				<span class="wow fadeIn blue-label">Get ready to go Viral!</span>
			</div>
			<div class="row series-feature text-center overflow-hidden">
				<div class="col-sm-3 wow fadeInLeft">
					<div class="feature-box">
						<i class="fa fa-play-circle" aria-hidden="true"></i>
						<i class="fa fa-lock" aria-hidden="true"></i>
						<span class="expire">This video will air 12/27</span>
					</div>
					<p class="p-content">
						Use our adept technology to schedule your content and have them air when you want.
					</p>
				</div>
				<div class="col-sm-3 wow fadeInLeft">
					<div class="feature-box">
						<span class="follow" href="#">Follow</span>
					</div>
					<p class="p-content">
						Follow your favorite<br>vlog series
					</p>
				</div>
				<div class="col-sm-3 wow fadeInRight">
					<div class="feature-box">
						<i class="fa fa-usd" aria-hidden="true"></i>
						<i class="fa fa-usd" aria-hidden="true"></i>
						<i class="fa fa-usd" aria-hidden="true"></i>
						<i class="fa fa-usd" aria-hidden="true"></i>
					</div>
					<p class="p-content">
						We pay you to vlog. Upload your vlog series and get paid per episode
					</p>
				</div>
				<div class="col-sm-3 wow fadeInRight">
					<div class="feature-box">
						<i class="fa fa-television" aria-hidden="true"></i>
					</div>
					<p class="p-content">
						Explore some of the most interesting vlog series on the web
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="content-container">
	<div class="container">
		<div class="row videos-container">
			@foreach($seriesmedias as $index => $seriesmedia)
			<div class="col-md-3 col-xs-6 width100-500 wow fadeIn">
				<div data-seriesmediaid="{{ $seriesmedia->seriesmediaid }}" class="embed-responsive embed-responsive-4by3 video sm media-item @if($seriesmedia->ispublished) bindvideoplayanalytics @else na-video {{ $seriesmedia->publishdateday }} @endif" @if(!$seriesmedia->ispublished) data-publishdateair="{{ $seriesmedia->publishdateair }}" @endif>
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
				<div class="details">
					<span class="episode" title="{{ $seriesmedia->title }}" data-toggle="tooltip">{{ str_limit(strip_tags($seriesmedia->title), 255) }}</span>
					<!-- <span class="episode-heading">{{ $seriesmedia->description }}</span> -->
				</div>
			</div>
			@endforeach
			@if(!isset($globaldata['user']))
				<div class="col-md-6 col-xs-12 wow fadeIn">
					<div class="yellow-box text-center">
						<p class="head">Create a Series on what you care about the most</p>
						<a href="{{ url('/user/signup') }}" class="signup">SIGN UP</a>
						<br>
						<p class="seeris">Series is free to USE</p>
					</div>
				</div>
			@endif
		</div>
	</div>
</div>
@endsection
