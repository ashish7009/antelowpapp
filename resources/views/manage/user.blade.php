@extends('manage.layouts.admin')

@section('pagetitle', ' - User '.((empty($user->userid)) ? 'Add' : 'Edit'))

@section('content')	
<section class="content-header">
	<h1>Users <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/manage') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/manage/users') }}">Users</a></li>
		<li class="active">{{ (empty($user->userid)) ? 'Add' : 'Edit' }}</li>
	</ol>
</section>
{!! Form::model($user, ['url' => 'manage/users/store', 'id' => 'formuser', 'files' => true]) !!}
	{!! Form::hidden('userid', null, ['id' => 'userid']) !!}
	{!! Form::hidden('deleteimage', 0, ['id' => 'deleteimage']) !!}
	<section class="content">
		<div class="box box-custom">
			<div class="box-header with-border">
				<h3 class="box-title">{{ (empty($user->userid)) ? 'Please fill in the below fields to add a new user' : 'Please modify the details below' }}</h3>
			</div>
			<div class="box-body">			
				<div class='form-group col-lg-6 col-xs-12'>
					<label for="firstname">First Name <span class="text-danger">*</span></label>
					{!! Form::text('firstname', null, ['class' => 'form-control', 'id' => 'firstname']) !!}
				</div>
				<div class='form-group col-lg-6 col-xs-12'>
					<label for="lastname">Last Name <span class="text-danger">*</span></label>
					{!! Form::text('lastname', null, ['class' => 'form-control', 'id' => 'lastname']) !!}
				</div>
				<div class='form-group col-lg-6 col-xs-12'>
					<label for="email">Email <span class="text-danger">*</span></label>
					{!! Form::text('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
				</div>
				<div class='form-group col-lg-6 col-xs-12'>
					<label for="phoneno">Phone No <span class="text-danger">*</span></label>
					{!! Form::text('phoneno', null, ['class' => 'form-control', 'id' => 'phoneno']) !!}
				</div>
				<div class='form-group col-lg-6 col-xs-12'>
					<label for="password">Password @if(empty($user->userid))<span class="text-danger">*</span>@endif</label>
					{!! Form::password('password', ['class' => 'form-control', 'id'=>'password']) !!}
				</div>
				<div class='form-group col-lg-6 col-xs-12'>
					<label for="password_confirmation">Confirm Password @if(empty($user->userid))<span class="text-danger">*</span>@endif</label>
					{!! Form::password('password_confirmation', ['class' => 'form-control', 'id'=>'password_confirmation']) !!}
				</div>
				<div class='form-group col-lg-6 col-xs-12'>
					<label for="address1">Address Line 1 <span class="text-danger">*</span></label>
					{!! Form::text('address1', null, ['class' => 'form-control', 'id' => 'address1']) !!}
				</div>
				<div class='form-group col-lg-6 col-xs-12'>
					<label for="address2">Address Line 2</label>
					{!! Form::text('address2', null, ['class' => 'form-control', 'id' => 'address2']) !!}
				</div>
				<div class='form-group col-lg-6 col-xs-12'>
					<label for="country">Country <span class="text-danger">*</span></label>
					{!! Form::text('country', null, ['class' => 'form-control', 'id' => 'country']) !!}
				</div>
				<div class='form-group col-lg-6 col-xs-12'>
					<label for="state">State <span class="text-danger">*</span></label>
					{!! Form::text('state', null, ['class' => 'form-control', 'id' => 'state']) !!}
				</div>
				<div class='form-group col-lg-6 col-xs-12'>
					<label for="city">City <span class="text-danger">*</span></label>
					{!! Form::text('city', null, ['class' => 'form-control', 'id' => 'city']) !!}
				</div>
				<div class="pull-right col-lg-6 col-xs-12">
					<div class='form-group'>
						<label for="pincode">Pin Code <span class="text-danger">*</span></label>
						{!! Form::text('pincode', null, ['class' => 'form-control', 'id' => 'pincode']) !!}
					</div>
					<div class="form-group">
						<label>Status</label>
						<div>
							<label class="mr10 icheck-label">{!! Form::radio('status', '1', true) !!} Active</label>
							<label class="mr10 icheck-label">{!! Form::radio('status', '0') !!} Inactive</label>
						</div>
					</div>
				</div>
				<div class="form-group col-lg-6 col-xs-12">
					<label for="imagefile">Image</label>
					<div>
						<div class="pull-left imagethumb">
							<img src="{{ $user->imagefilepath }}" alt="User Image" class="profile-user-img img-lg loggedinuserimage" />
							@if($user->hasimage)<a class="fa fa-remove" title="Remove" onclick="removeUserPic();"></a>@endif
						</div>
						<div class="inputfilecontainer">
							{!! Form::file('imagefile', ['id' => 'imagefile']) !!}
							<p class="help-block margin0">Preferred image size is {{ config('app.constants.user_image_width') }} x {{ config('app.constants.user_image_height') }}.</p>
						</div>
					</div>
				</div>		
			</div>
			<div class="box-footer text-right">
				<a class="btn btn-default" href="{{ url('/manage/users') }}">Cancel</a>
				{!! Form::button('Submit', ['id'=>'btnsubmit', 'type' => 'submit', 'class' => 'btn btn-primary ml10']) !!}
			</div>		
		</div>
	</section>
{!! Form::close() !!}

@endsection
@section('page-scripts')
<script src="{{ asset('/manage/js/user.js?cache=1') }}"></script>
@endsection