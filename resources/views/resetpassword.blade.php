@extends('layouts.front')

@section('pagetitle', 'Reset Password')

@section('content')
<div class="content-container">
	<div class="container">
		<div class="wow fadeInDown">
			<h1 class="page-title color-pink">Reset Password</h1>
			@if($type == 'success')
			<div class="formblock">
				<div class="row">
					<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
						{!! Form::open(['url' => '/user/reset-password', 'id' => 'formresetpassword']) !!}
							{!! Form::hidden('token', $token) !!}
							<div class="form-group">
								<label class="control-label">Password: </label>
								{!! Form::password('password', ['class' => 'form-control', 'id' => 'password']) !!}
							</div>
							<div class="form-group">
								<label class="control-label">Confirm Password: </label>
								{!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
							</div>
							<div class="text-right">
								<button type="submit" class="btn btn-warning">Reset</button>
							</div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
			<div class="successblock" style="display: none;">
				<h3 class="text-success messagetext">Your password has been reset. You can now login with your new password.</h3>
			</div>
			@else
				<h4 class="text-danger">{{ $message }}</h4>
			@endif
		</div>
	</div>
</div>
@endsection

@section('page-scripts')
@if($type == 'success')
<script src="{{ asset('js/resetpassword.js?cache=1.2') }}"></script>
@endif
@endsection