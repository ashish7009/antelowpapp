@extends('layouts.front')

@section('pagetitle', 'Series: The #1 Broadcasting Channel For Online Vloggers. Upload Your Vlogs On Series and Get Paid For Each Episode.')

@section('page-css')
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC:400,700" />
<link rel="stylesheet" href="{{ secure_asset('css/home.css?cache=2.2') }}" />
<link rel="stylesheet" href="{{ secure_asset('css/new-home.css?cache=2.2') }}" />
@endsection

@section('content')
<div class="content-container p0 desktop-block">
	<div class="bg-yellow home-top-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-5 col-md-6 col-sm-6 col-sm-offset-0 col-xs-8 col-xs-offset-2 width100-500 color-white">
					<div class="home-img">
						<div class="heading wow fadeInDown text-uppercase"><small><small>Turn your life into a TV series</small></small></div>
						<p class="wow fadeInDown small-text">Upload clips that grab your friends' attention! Air your experiences when you want to and Keep your friends in the loop for all your new postings. Upload 60 seconds video  that  document your experiences.</p>
						<div class="row">
							<div class="col-lg-8 col-md-8 col-sm-10 col-xs-12 text-center">
								<div>
									<a class="color-yellow text-center play-btn wow fadeInUp">
										<span class="fa fa-play"></span>
									</a>
								</div>
								<div class="wow fadeInUp">
									<a class="btn btn-lg border-white width100 btn-rounded btn-blue text-uppercase" href="{{ secure_url('vlogs/edit') }}">Air your experiences</a>
								</div>
								<div class="wow fadeInUp pt5">
									<p><u>It's all free</u></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-7 col-md-6 col-sm-6 col-sm-offset-0 col-xs-8 col-xs-offset-2 width100-500 overflow-hidden">
					<div class="row">
						<div class="col-lg-offset-1 col-lg-10 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 wow fadeInLeft">
									<div class="box-shadow img-container home-img">
										<div class="userdetails">
											<img class="inline-block userimg mr5" alt="Bryan" src="{{ secure_asset('images/user1.jpg') }}" />
											<strong>Bryan</strong>
										</div>
										<div class="middle-part">
											<img src="{{ secure_asset('images/a-day-at-the-park.png') }}" alt="A day at the park" class="img-responsive width100" />
											<div class="airlabel"><div class="bg-light-orange wow fadeInLeft" data-wow-delay="1s">This video will air on Monday @ 7pm</div></div>
										</div>
										<div class="desc light">A day at the park<br><br></div>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 wow fadeInRight">
									<div class="box-shadow img-container home-img">
										<div class="userdetails">
											<img class="inline-block userimg mr5" alt="Jessica" src="{{ secure_asset('images/user2.jpg') }}" />
											<strong>Jessica</strong>
										</div>
										<div class="middle-part">
											<img src="{{ secure_asset('images/my-coffee.jpg') }}" alt="A day at the park" class="img-responsive width100 home-img" />
											<div class="airlabel"><div class="bg-dark-blue wow fadeInRight" data-wow-delay="1s">This will air on Friday @ 7pm</div></div>
										</div>
										<div class="desc light">How I like my coffee. I only drink the very best</div>
									</div>
								</div>
								<div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 wow fadeInDown">
									<div class="box-shadow img-container home-img m0">
										<div class="userdetails">
											<img class="inline-block userimg mr5" alt="Beccy" src="{{ secure_asset('images/user3.jpg') }}" />
											<strong>Beccy</strong>
										</div>
										<div class="middle-part">
											<img src="{{ secure_asset('images/birthday.jpg') }}" alt="A day at the park" class="img-responsive width100 home-img" />
											<div class="airlabel"><div class="bg-red wow fadeInLeft" data-wow-delay="1s">This will air on Monday @ 7pm</div></div>
										</div>
										<div class="desc light">
											Lindsey's Birthday Bash.. Hurray!<br>
											<span class="badge bg-black mt5 mb5">Beccy's contents airs every Mon and Fri</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="bg-red section-padding-sm">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 width100-500 mb30-500">
					<div class="row">
						<div class="col-lg-offset-3 col-lg-8 col-md-offset-2 col-md-10 col-sm-offset-2 col-sm-10 col-xs-offset-2 col-xs-10 width100-500 wow fadeInRight">
							<div class="box-shadow img-container home-img m0">
								<div class="middle-part">
									<div class="airlabel"><div class="bg-pink wow fadeInLeft" data-wow-delay="1s">This video will air in 3 minutes</div></div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-9 col-xs-9 width100-500 wow fadeInLeft">
							<img src="{{ secure_asset('images/series-comments.jpg') }}" alt="Series Comments" class="box-shadow img-responsive width100 home-img" />
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-5 col-sm-offset-1 col-xs-offset-1 col-xs-5 width100-500 overflow-hidden">
					<div class="box-shadow img-container home-img wow fadeInDown">
						<div class="middle-part">
							<img src="{{ secure_asset('images/first-day-of-college.jpg') }}" alt="A day at the park" class="img-responsive width100 home-img" />
							<div class="airlabel"><div class="bg-dark-blue wow fadeInLeft" data-wow-delay="1s">This video will air in 3 minutes</div></div>
						</div>
						<div class="desc">
							<div class="pt5 pb5">First day of College...</div>
						</div>
					</div>
					<div class="box-shadow img-container home-img m0 wow fadeInDown">
						<div class="middle-part">
							<img src="{{ secure_asset('images/holiday.jpg') }}" alt="Holiday! first stop, LA." class="img-responsive width100 home-img" />
							<div class="airlabel"><div class="bg-yellow wow fadeInLeft" data-wow-delay="1s">This video will air in 12 minutes</div></div>
						</div>
						<div class="desc">
							<div class="pt5 pb5">Holiday! first stop, LA.</div>
						</div>
					</div>
					<div class="box-shadow img-container home-img wow fadeInDown">
						<div class="middle-part">
							<img src="{{ secure_asset('images/happy-new-year.jpg') }}" alt="A day at the park" class="img-responsive width100 home-img" />
							<div class="airlabel"><div class="bg-orange wow fadeInLeft" data-wow-delay="1s">This video will air on January 1st @ 00AM</div></div>
						</div>
						<div class="desc">
							<div class="pt5 pb5">Happy New Year! A new year message for my friends</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-md-offset-0 col-sm-offset-3 col-sm-6 col-xs-8 col-xs-offset-2 width100-500">
					<div class="row">
						<div class="col-lg-offset-1 col-lg-11 col-md-12 col-xs-12">
							<div class="box-shadow bg-white signup-box home-img wow fadeInRight">
								<div class="bg-yellow-light p5">
									<div class="p5">
										@if(!isset($globaldata['user']))
										<div class="formblock">
											{!! Form::open(['url' => '/user/signup', 'id' => 'formsignup']) !!}
												<input type="hidden" name="minimal" value="1" />
												<div class="heading color-black m0 text-center text-uppercase"><small class="form-group inline-block"><small>Create an Air</small></small></div>
												<div class='form-group'>
													{!! Form::text('firstname', null, ['class' => 'form-control border-black', 'id' => 'firstname', 'placeholder' => 'Name']) !!}
												</div>
												<div class='form-group'>
													{!! Form::text('email', null, ['class' => 'form-control border-black', 'id' => 'email', 'placeholder' => 'Email Address']) !!}
												</div>
												<div class='form-group'>
													{!! Form::password('password', ['class' => 'form-control border-black', 'id' => 'password', 'placeholder' => 'Password']) !!}
												</div>
												<div class="color-white text-center">
													<input type="submit" class="btn border-white btn-rounded btn-blue text-uppercase" value="Sign Up">
												</div>
												<div class="text-center pt5">
													<p><u>It's all free</u></p>
												</div>
											{!! Form::close() !!}
										</div>
										<div class="successblock" style="display: none;">
											<h3 class="text-success messagetext">Thanks for signing up! A confirmation email has been sent to your mailbox.</h3>
										</div>
										@else
										<div>
											<br><br><br><br>
											<div class="color-white text-center">
												<a class="btn border-white btn-rounded btn-blue text-uppercase" href="{{ secure_url('vlogs/edit') }}">Create an Air</a>
											</div>
											<div class="text-center pt5">
												<p><u>It's all free</u></p>
											</div>
											<br><br><br>
										</div>
										@endif
									</div>
								</div>
								<div class="p5">
									<div class="cursive tag-line color-black text-center">Join the community of everyday people stealing the limelight</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="bg-light-green section-padding-xs color-white border-bottom-white">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 pt5 width100-500 wow fadeInUp">
					<div class="tag-line-bg bg-dark-blue box-shadow text-center home-img">
						<strong>Your video will air every weekend @ 1pm</strong>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 width100-500 wow fadeInUp">
					<div class="tag-line-bg bg-yellow box-shadow text-center home-img">
						<strong>Jay's post just aired</strong>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-3 col-xs-6 col-xs-offset-3 pt5 width100-500 wow fadeInUp">
					<div class="tag-line-bg bg-black box-shadow text-center home-img">
						<strong>This video will air on Friday @12AM</strong>
					</div>
				</div>
			</div>
			<div class="heading text-center m0 text-uppercase wow fadeIn"><small>Unfold your life like a series</small></div>
			<div class="row">
				<div class="col-lg-4 col-lg-offset-1 col-md-5 col-md-offset-1 col-sm-6 col-xs-6 width100-500 wow fadeInDown">
					<div class="tag-line-bg bg-red box-shadow text-center home-img">
						<strong>Your friend's post will air today @ 12:30am</strong>
					</div>
				</div>
				<div class="col-lg-4 col-lg-offset-2 col-md-5 col-sm-6 col-xs-6 width100-500 wow fadeInDown">
					<div class="tag-line-bg bg-orange box-shadow text-center home-img">
						<strong>Sarah's post usually airs Thursdays @ 1pm</strong>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-6 col-xs-offset-3 width100-500 wow fadeInDown">
					<div class="tag-line-bg bg-pink box-shadow text-center home-img">
						<strong>When would you like your videos to air</strong>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="bg-light-green section-padding-xs color-white">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-4 col-xs-4 text-center width100-500 wow fadeInLeft">
					<div>
						<a class="nounderline fa fa-android fa-5x m5"></a>
						<a class="nounderline fa fa-apple fa-5x m5"></a>
					</div>
					<div class="fa-2x">Coming Soon...</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center width100-500 wow fadeInRight">
					<div><span>Don't be boring</span></div>
					<a class="logo relative" href="{{ secure_url('/series') }}">
						<span class="top-logo">Seeris</span>
					</a>
					<div><span>Seeris @ {{ date('Y') }}</span></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="owl-carousel owl-theme homeslider">
<div class="item">
<div class="first mobil-block">
	<div class="first__wrap">
    	<div class="first__info">
        	<h1 class="first__title">Potatoes</h1>
        	<div class="first__subtitle">Anticipate your friend's next move...</div>
        </div>
    </div>
    <p class="first__singin"><a href="{{ secure_url('/user/signin') }}" >Sign in</a></p>
    	
    <div class="first__buttons">
    	<a href="{{ secure_url('/user/signup') }}" class="first__btn first__singup">Sign up</a>
    	<a href="{{ secure_url('/auth/facebook') }}" class="first__btn first__facebook">Sign up with Facebook</a>
    	<div style="text-align: center; font-size: 18px; color: #e3d837; margin-top: 10px;">Potatoes is free</div>
    </div>
</div>
</div>
<div class="item">
<div class="second_screen">
<p class="first__singin"><a href="{{ secure_url('/user/signin') }}" >Sign in</a></p>
<br>
<div class="container white_bg">
<div class="row">
<div class="col-md-12 color_c1">
<p>8 friends airing new episodes on Monday</p>
<div class="schedule__item__frendlist text-center second_sch">
<a href="#"><img src="{{ secure_asset('uploads/s13.jpg') }}" class="cutimg"></a>
<a href="#"><img src="{{ secure_asset('uploads/s7.jpg') }}" class="cutimg"></a>
<a href="#"><img src="{{ secure_asset('uploads/s1.jpg') }}" class="cutimg"></a>
<a href="#"><img src="{{ secure_asset('uploads/s2.jpg') }}" class="cutimg"></a>
</div>
<div class="schedule__item__frendlist text-center second_sch">
<a href="#"><img src="{{ secure_asset('uploads/s3.jpg') }}" class="cutimg"></a>
<a href="#"><img src="{{ secure_asset('uploads/s4.jpg') }}" class="cutimg"></a>
<a href="#"><img src="{{ secure_asset('uploads/s5.jpg') }}" class="cutimg"></a>
<a href="#"><img src="{{ secure_asset('uploads/s6.jpg') }}" class="cutimg"></a>
</div>
<a href="{{ secure_url('/series') }}" class="btn btn-primary btn-rounded btn-custom">See more</a>
</div>
<div class="col-md-12 color_c2">
<p class="white_p">4 friends airing new episodes on Tuesday</p>
<div class="schedule__item__frendlist text-center ">
<a href="#"><img src="{{ secure_asset('uploads/s8.jpg') }}" class="cutimg"></a>
<a href="#"><img src="{{ secure_asset('uploads/s9.jpg') }}" class="cutimg"></a>
<a href="#"><img src="{{ secure_asset('uploads/s10.jpg') }}" class="cutimg"></a>
<a href="#"><img src="{{ secure_asset('uploads/s11.jpg') }}" class="cutimg"></a>
</div>

</div>
<div class="col-md-12 color_c3">
<p class="white_p">3 friends airing new episodes on Wednesday</p>
<div class="schedule__item__frendlist text-center ">
<a href="#"><img src="{{ secure_asset('uploads/s12.jpg') }}" class="cutimg"></a>
<a href="#"><img src="{{ secure_asset('uploads/s14.jpg') }}" class="cutimg"></a>
<a href="#"><img src="{{ secure_asset('uploads/s15.jpg') }}" class="cutimg"></a>
</div>

</div>


</div>
</div>

<div class="color_c4">
<h4>Know when next your friends will be airing new contents</h4>
<a href="{{ secure_url('/user/signup') }}" class="btn btn-primary btn-rounded btnlgx">Sign Up</a>
</div>


</div>
</div>
<div class="item">
<div class="third_screen">
<p class="first__singin black_s"><a href="{{ secure_url('/user/signin') }}" >Sign in</a></p>
<div class="container ts">
<div class="row">
<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6 ts1">
<img src="{{secure_asset('uploads/ts1.PNG')}}" class="cutimg">
<p>The rest of world has to wait to see your posts</p>
</div>
<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6 ts2">
<p>Your truefriends don't. They can see your post anytime</p>
<img src="{{secure_asset('uploads/ts2.PNG')}}" class="cutimg">
</div>
</div>
</div>
<div class="color_c4">
<a href="{{ secure_url('/user/signup') }}" class="btn btn-primary btn-rounded btnlgx" style="    margin-top: 25px;">Sign Up</a>
</div>
</div>
</div>
</div>

@endsection

@section('page-scripts')
@if(!isset($globaldata['user']))
<script src="{{ secure_asset('js/signup.js?cache=2.1') }}"></script>

@endif
@endsection

@push('footer_script')
<script>
$('.homeslider').owlCarousel({
			loop:false,
			margin:10,
			nav:false,
			autoplay:false,
			dots:true,
			responsive:{
				0:{
					items:1
				},
				600:{
					items:1
				},
				1000:{
					items:1
				}
			}
		});
		$('.homeslider .item').css('height',$(window).height());
</script>
@endpush

