@extends('layouts.front')

@section('pagetitle', 'Forgot Password')

@section('content')
<div class="content-container">
	<div class="container">
		<div class="wow fadeInDown">
			<h1 class="page-title color-pink">Forgot Password</h1>
			<div class="formblock">
				<div class="row">
					<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
						{!! Form::open(['url' => '/user/forgot-password', 'id' => 'formforgotpassword']) !!}
							<div class="form-group">
								<label class="control-label">Email: </label>
								{!! Form::text('email', '', ['class' => 'form-control']) !!}
							</div>
							<div class="text-right">
								<button type="submit" class="btn btn-warning">Submit</button>
							</div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
			<div class="successblock" style="display: none;">
				<h3 class="text-success messagetext">A password reset link has been sent to your email address. Please click on "Reset Password" button to reset your password.</h3>
			</div>
		</div>
	</div>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('js/forgotpassword.js?cache=1.2') }}"></script>
@endsection