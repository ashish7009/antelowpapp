@extends('layouts.front')

@section('pagetitle', 'Sign In')

@section('content')
<div class="content-container singin-mobil">
	<div class="container">
		<div class="wow fadeInDown">
			<h1 class="page-title color-pink">Sign In</h1>
			<div class="sing__mobil">
				<div class="sing__subtitle">with your social network</div>
		        <div class="sing__socials">
		            <a href="{{ url('/auth/facebook') }}" class="sing__socials__item sing__socials_fb"></a>
		        </div> 
		        <div class="sing__or"><span>or</span></div>
	        </div>
			<div class="row">
				<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
					{!! Form::open(['url' => '/user/signin', 'id' => 'formsignin']) !!}
						<div class="form-group">
							<label class="control-label">Email: </label>
							{!! Form::text('email', '', ['class' => 'form-control email-address', 'placeholder' => 'Email']) !!}
						</div>
						<div class="form-group">
							<label class="control-label">Password: </label>
							{!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
						</div>
						<div class="form-group form-forgot">
							<a href="{{ url('/user/forgot-password') }}">Forgot password?</a>
						</div>
						<div class="row">
							<div class="col-xs-8 form-remember">
								<label>
									<input type="checkbox" name="remember" value="1"> Remember Me
								</label>
							</div>
							<div class="col-xs-4 text-right form-btn">
								<button type="submit" class="btn btn-warning first__singup first__btn">Sign In</button>
							</div>
							<div class="sing__info sing__mobil">
								<p>Not a member? <a href="{{ url('/user/signup') }}">Sign Up</a></p>
            					<p><a href="{{ url('/user/forgot-password') }}">Recover my password</a></p>
							</div>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('js/signin.js?cache=1.2') }}"></script>
@endsection

@push('footer_script')
<script> 
	$('.first__singup').on('click',function(){
		var email = $('.email-address').val();
		console.log(email);
		Android.handleinput(String(email));
	});	
</script>
@endsection