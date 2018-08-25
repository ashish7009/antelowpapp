@extends('layouts.front')

@section('pagetitle', 'Sign Up')

@section('content')
<div class="content-container singin-mobil singup-mobil">
	<div class="container">
		<div class="wow fadeInDown">
			<h1 class="page-title color-pink">Sign Up</h1>
			<div class="sing__mobil">
				<div class="sing__subtitle">with your social network</div>
		        <div class="sing__socials">
		            <a href="#" class="sing__socials__item sing__socials_gp" style="visibility: hidden;"></a>
		            <a href="{{ url('/auth/facebook') }}" class="sing__socials__item sing__socials_fb"></a>
		            <a href="#" class="sing__socials__item sing__socials_tw" style="visibility: hidden;"></a>
		        </div>
		        <div class="sing__or"><span>or</span></div>
	        </div>
			<div class="formblock">
				{!! Form::open(['url' => '/user/signup', 'id' => 'formsignup']) !!}
					<input type="hidden" name="minimal" value="0" />
					<div class="row">
						<div class='form-group col-sm-6 col-xs-12'>
							<label for="firstname">Name <span class="text-danger">*</span></label>
							{!! Form::text('firstname', null, ['class' => 'form-control', 'id' => 'firstname', 'placeholder' => 'Name']) !!}
						</div>
						<div class='form-group col-sm-6 col-xs-12'>
							<label for="email">Email <span class="text-danger">*</span></label>
							{!! Form::text('email', null, ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Email']) !!}
						</div>
						<div class='form-group col-sm-6 col-xs-12'>
							<label for="age">Age <span class="text-danger">*</span></label>
							{!! Form::text('age', null, ['class' => 'form-control', 'id' => 'age', 'placeholder' => 'Age', 'readonly']) !!}
						</div>
						<div class='form-group col-sm-6 col-xs-12 desktop-block'>
							<label for="password_confirmation">Interests</label>
							<select id='interests' multiple='multiple' name="interests[]" class="form-control select2">
								@foreach ($interests as $interest)
									<option value="{{ $interest }}">{{ $interest }}</option>
								@endforeach
							</select> 
						</div>
						<div class='form-group col-sm-6 col-xs-12'>
							<label for="password">Password <span class="text-danger">*</span></label>
							{!! Form::password('password', ['class' => 'form-control', 'id'=>'password', 'placeholder' => 'Password']) !!}
						</div>
						<div class='form-group col-sm-6 col-xs-12'>
							<label for="password_confirmation">Confirm Password<span class="text-danger">*</span></label>
							{!! Form::password('password_confirmation', ['class' => 'form-control', 'id'=>'password_confirmation', 'placeholder' => 'Confirm Password']) !!} 
						</div>
						<div class="form-group col-sm-12 col-xs-12 desktop-block">
							<label for="imagefile">Profile Image</label>
							<div>
								{!! Form::file('imagefile', ['id' => 'imagefile']) !!}
								<p class="m0">Preferred image size is {{ config('app.constants.user_image_width') }} x {{ config('app.constants.user_image_height') }}.</p>
							</div>
						</div>
						<div class="form-group col-sm-12 col-xs-12">
							<label class="icheck-label">
								{!! Form::checkbox('agreement', '1', false) !!}
								I accept all the <a href="#">terms, conditions</a> and <a href="#">privacy policy</a> of Seeris.com
							</label>
						</div>
						<div class="col-xs-12 text-right ">
							<button type="submit" class="btn btn-warning first__singup first__btn">Sign Up</button>
						</div>
						<div class="sing__info sing__mobil">
							<p>Already registered? <a href="{{ url('/user/signin') }}">Sign In</a></p>
							<p>By signing up you agree to our <a target="_blank" style="text-decoration: underline;" href="/TERMS OF USE AGREEMENT Schemk.pdf">terms and conditions</a></p>
						</div>
					</div>
				{!! Form::close() !!}
			</div>
			<div class="successblock" style="display: none;">
				<h3 class="text-success messagetext">Thanks for signing up! A confirmation email has been sent to your mailbox. <br><b style="color:#000;">Don't forget to check your spambox too!</b></h3>
			</div>
		</div>
	</div>
</div>

@endsection

@section('page-scripts')
<script src="{{ asset('js/signup.js?cache=2.1') }}"></script>
@endsection
