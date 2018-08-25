@extends('layouts.front')

@section('pagetitle', 'Get Paid')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/home.css?cache=2.2') }}">
@endsection

@section('content')
<div class="content-container p0">

	<!-- YELLOW START -->
	<section class="bg-yellow color-black pt0 overflow-hidden">
		<div class="container">
			<div class="text-center">
				<a class="logo relative wow fadeIn" href="{{ url('/series') }}">
					<span class="top-logo">Seeris</span>
				</a>
			</div>
			<div class="bubble-section relative">
				<div class="row">
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-6 width100-500 wow fadeInLeft">
						<h2 class="page-title text-uppercase"><strong>So, you've got an idea?</strong></h2>
						<p class="text-justify">An idea of what to talk about on your series page.</p>
					</div>
				</div>
				<div class="floating-bubble-container lifestyle wow fadeInDown" data-animation2="infinite animated tree" data-animation1="fadeInDown">
					<div class="bubble-icon glyphicon glyphicon-comment color-maroon"></div>
					<div class="bubble-text color-white rotate--15">Lifestyle<br>Videos</div>
				</div>
				<div class="floating-bubble-container vlogging wow fadeInUp" data-animation2="infinite animated tree animation-delay-0-5" data-animation1="fadeInUp">
					<div class="bubble-icon fa fa-cloud fa-cloud-inverse color-red"></div>
					<div class="bubble-text color-white rotate-15">Vlogging</div>
					<div class="bubble-icon-sm">
						<div class="bubble-icon fa fa-cloud fa-cloud-inverse color-red"></div>
					</div>
					<div class="bubble-icon-xs">
						<div class="bubble-icon fa fa-cloud fa-cloud-inverse color-red"></div>
					</div>
				</div>
				<div class="floating-bubble-container gaming wow fadeInRight" data-animation2="infinite animated tree animation-delay-1" data-animation1="fadeInRight">
					<div class="bubble-icon fa fa-cloud fa-cloud-inverse color-white"></div>
					<div class="bubble-text color-black rotate-15">Gaming<br>Videos</div>
					<div class="bubble-icon-sm">
						<div class="bubble-icon fa fa-cloud fa-cloud-inverse color-black"></div>
					</div>
					<div class="bubble-icon-xs">
						<div class="bubble-icon fa fa-cloud fa-cloud-inverse color-black"></div>
					</div>
				</div>
				<div class="floating-bubble-container travel wow fadeInLeft" data-animation2="infinite animated tree animation-delay-1-5" data-animation1="fadeInLeft">
					<div class="bubble-icon glyphicon glyphicon-comment color-light-blue"></div>
					<div class="bubble-text color-black rotate-15">Travel<br>Videos</div>
				</div>
				<div class="floating-bubble-container comedy wow fadeInDown" data-animation2="infinite animated tree animation-delay-2" data-animation1="fadeInDown">
					<div class="bubble-icon glyphicon glyphicon-cloud color-white"></div>
					<div class="bubble-text color-black rotate--15">Comedy</div>
				</div>
				<div class="floating-bubble-container cuteanimals wow fadeInLeft" data-animation2="infinite animated tree animation-delay-2-5" data-animation1="fadeInLeft">
					<div class="bubble-icon fa fa-comment color-green"></div>
					<div class="bubble-text color-white rotate-15">Cute Animal<br>Videos</div>
				</div>
			</div>
		</div>
	</section>
	<!-- YELLOW END -->

	<!-- LIGHT BLUE START -->
	<section class="section-padding bg-light-blue color-black">
		<div class="container">
			<div class="row">
				<div class="col-lg-5 col-md-6 col-sm-7 col-xs-9 width100-500 wow fadeInLeft">
					<h2 class="page-title text-uppercase"><strong>Create your First series</strong></h2>
					<p class="text-justify">Create your first series and upload episodic videos; then schedule them to air whenever you want. Create a sequential release date for each episode.</p>
				</div>
			</div>
			<div class="row">
				<div class="wow fadeInRight col-lg-offset-5 col-lg-5 col-md-offset-5 col-md-6 col-sm-offset-5 col-sm-7 col-xs-6 col-xs-offset-6 width100-500">
					<div class="bg-dark-blue info-bubble color-white">
						<h3 class="sub-title">My travel vlog</h3>
						<div>
							<span class="inline-block vertical-middle mr5 pr5">
								<img src="{{ asset('images/icon-play.png') }}" class="img-responsive" alt="Episode 1" />
							</span>
							<span class="inline-block vertical-middle">
								Episode 1<br>
								My Trip to Amsterdam
							</span>
						</div>
						<div class="text-right">
							<span class="inline-block labelbottom bg-green">
								<small class="smalllabel bg-yellow rounded">Will air on Monday</small>
								<small class="smalllabel inline-block"> @ 4am</small>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- LIGHT BLUE END -->

	<!-- BLACK START -->
	<section class="section-padding bg-black color-white">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-4 col-sm-5 col-xs-8 width100-500 wow fadeInLeft">
					<h2 class="page-title text-uppercase"><strong>Become a Show Runner</strong></h2>
					<p class="color-light-grey text-justify">Become a show runner, and get paid per episode. Get a threshold view of 1000+ to become eligible for showrunner status.</p>
				</div>
				<div class="col-lg-3 col-lg-offset-1 col-sm-offset-1 col-md-3 col-sm-4 col-xs-4 pull-right width100-500 wow fadeInRight">
					<img src="{{ asset('images/showrunner.png') }}" alt="Become a Show Runner" class="img-responsive width100 hcenter-500 max-width200-500" />
				</div>
			</div>
		</div>
	</section>
	<!-- BLACK END -->

	<!-- RED START -->
	<section class="bg-red color-white">
		<div class="section-padding pb0">
			<div class="container">
				<div class="row">
					<div class="col-lg-5 col-md-6 col-sm-6 col-xs-8 width100-500 wow fadeInLeft">
						<h2 class="page-title text-uppercase"><strong>Get paid for your series</strong></h2>
						<p class="color-white text-justify">Once you become a showrunner, a monetization request will be send to you. Once you accept the monetization request, you will be asked to produce a new series consisting of 6 episodes. Our Showrunners, typically get paid between $8-$275 per episode for ads and sponsorship. No Commission, no cuts, and no pay per click. You get all the funds after each episode produced.</p>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-5 col-xs-4 pull-right width100-500 wow fadeInRight">
						<img src="{{ asset('images/dollar.png') }}" alt="Get paid for your account" class="img-responsive width100 hcenter-500 max-width200-500" />
					</div>
				</div>
			</div>
		</div>
		<div class="section-padding">
			<div class="container">
				<p class="color-white wow fadeInLeft">Please note, you can choose to decline monetizing your account.</p>
			</div>
		</div>
	</section>
	<!-- RED END -->

	<section class="bg-black color-white text-center">
		<div class="container">
			<div class="pt5 pb5 mt5 mb5">
				<p></p>
				<p><a class="btn btn-bordered btn-white btn-hover-yellow btn-sm ml5 mr5" href="{{ url('/contact-us') }}">CONTACT US</a></p>
			</div>
		</div>
	</section>

</div>
@endsection

@section('page-scripts')
<script src="{{ asset('js/getpaid.js?cache=1') }}"></script>
@endsection