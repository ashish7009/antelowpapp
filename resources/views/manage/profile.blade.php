@extends('manage.layouts.admin')

@section('pagetitle', ' - Profile')

@section('content')
<section class="content-header">
	<h1>Profile <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/manage') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Profile</li>
	</ol>
</section>
<section class="content">      
	<div class="box box-custom">
		<div class="box-header with-border">
			<h3 class="box-title">Please modify the details below to edit your profile</h3>
		</div>           
		{!! Form::open(['url' => 'manage/profile', 'id' => 'formprofile']) !!}
			{!! Form::hidden('userid', $admin->userid) !!}
			<input name="deleteimage" id="deleteimage" type="hidden" value="0" class="form-control" />
			<div class="box-body">
				<div class="row">
					<div class="form-group col-lg-6 col-xs-12">
						<label for="firstname">First Name <span class="text-danger">*</span></label>
						{!! Form::text('firstname', $admin->firstname, ['class' => 'form-control', 'id' => 'firstname']) !!}
					</div>
					<div class="form-group col-lg-6 col-xs-12">
						<label for="lastname">Last Name <span class="text-danger">*</span></label>
						{!! Form::text('lastname', $admin->lastname, ['class' => 'form-control', 'id' => 'lastname']) !!}
					</div>
					<div class="form-group col-lg-6 col-xs-12">
						<label for="email">Email <span class="text-danger">*</span></label>
						{!! Form::text('email', $admin->email, ['class' => 'form-control', 'id' => 'email']) !!}
					</div>
					<div class="form-group col-lg-6 col-xs-12">
						<label for="oldpassword">Old Password</label>
						{!! Form::password('oldpassword', ['class' => 'form-control', 'id' => 'oldpassword']) !!}
					</div>
					<div class="form-group col-lg-6 col-xs-12">
						<label for="password">New Password</label>
						{!! Form::password('password', ['class' => 'form-control', 'id' => 'password']) !!}
					</div>
					<div class="form-group col-lg-6 col-xs-12">
						<label for="password_confirmation">Confirm Password</label>
						{!! Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation']) !!}
					</div>
					<div class='form-group col-lg-6 col-xs-12 pull-right'>
						<label for="phoneno">Phone No <span class="text-danger">*</span></label>
						{!! Form::text('phoneno', $admin->phoneno, ['class' => 'form-control', 'id' => 'phoneno']) !!}
					</div>
					<div class="form-group col-lg-6 col-xs-12">
						<label for="imagefile">Image</label>
						<div>
							<div class="pull-left imagethumb">
								<img src="{{ $admin->imagefilepath }}" alt="User Image" class="profile-user-img img-lg loggedinuserimage" />
								@if($admin->hasimage)<a class="fa fa-remove" title="Remove" onclick="removeProfilePic();"></a>@endif
							</div>
							<div class="inputfilecontainer">
								{!! Form::file('imagefile', ['id' => 'imagefile']) !!}
								<p class="help-block margin0">Preferred image size is {{ config('app.constants.user_image_width') }} x {{ config('app.constants.user_image_height') }}.</p>
							</div>
						</div>
					</div>
				</div>
			</div>             
			<div class="box-footer text-right">
				<a href="{{ url('/manage/profile') }}" class="btn btn-default">Cancel</a>
				{!! Form::button('Submit', ['type' => 'submit', 'class' => 'btn btn-primary ml10']) !!}
			</div>
		{!! Form::close() !!}
	</div>
</section>
@endsection

@section('page-scripts')
<script src="{{ asset('/manage/js/profile.js?cache=1') }}"></script>
@endsection
