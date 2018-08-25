<!DOCTYPE html>
<html>
<head>
	@include('manage.layouts.head', ['pagetitle' => ' - Login'])
</head>
<body class="hold-transition login-page">
	<div class="login-box">
		<div class="login-box-body">
			<div class="login-logo mb0 pb5">
				<a class="inline-block img-responsive" href="{{ url('/') }}">
					<span style="color: red;text-decoration: line-through;">
						<span style="color: #EDD23B">Seeris</span>
					</span>
				</a>
			</div>
			<p class="login-box-msg">Sign in to start your session</p>     
			{!! Form::open(['url' => 'manage/login', 'id' => 'formlogin']) !!}
				<div class="form-group has-feedback">
					{!! Form::text('email', '', ['class' => 'form-control', 'placeholder' => 'Email']) !!}
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">        
					{!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="row">
					<div class="col-xs-8">
						<div class="checkbox icheck">
							<label>
								<input type="checkbox" name="remember" value="1"> Remember Me
							</label>
						</div>
					</div>
					<div class="col-xs-4">
						<button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
					</div>        
				</div>
			{!! Form::close() !!}
		</div>
	</div>
@include('manage.layouts.foot')
<script src="{{ asset('/manage/js/login.js?cache=1') }}"></script>
</body>
</html>