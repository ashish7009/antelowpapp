@extends('layouts.front')

@section('pagetitle', 'Profile')

@section('content') 
<!---<div class="content-container desktop-block">
	<div class="container">
		<div class="wow fadeInDown">
			<div class="mb5 pb5 p0 col-sm-6 col-xs-12 pull-left">
				<h1 class="page-title color-pink">Your Profile</h1>
			</div>
			<div class="clearfix"></div>
			<div class="pt5 mt5">
				{!! Form::model($user, ['url' => '/user/edit', 'id' => 'formprofile']) !!}
					{!! Form::hidden('deleteimage', 0, ['id' => 'deleteimage']) !!}
					<div class="row">
						<div class='form-group col-sm-6 col-xs-12'>
							<label for="firstname">Name <span class="text-danger">*</span></label>
							{!! Form::text('firstname', null, ['class' => 'form-control', 'id' => 'firstname']) !!}
						</div>
						<div class='form-group col-sm-6 col-xs-12'>
							<label for="email">Email <span class="text-danger">*</span></label>
							{!! Form::text('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
						</div>
						<div class='form-group col-sm-6 col-xs-12'>
							<label for="age">Age <span class="text-danger">*</span></label>
							{!! Form::text('age', null, ['class' => 'form-control', 'id' => 'age']) !!}
						</div>
						<div class='form-group col-sm-6 col-xs-12'>
							<label for="password_confirmation">Interests</label>
							<select id='interests' multiple='multiple' name="interests[]" class="form-control select2">
								@foreach ($custome_array as $interest)
									<option value="{{ $interest }}" @if(in_array($interest, $interestsarray)) selected @endif>{{ $interest }}</option>
								@endforeach
							</select>
						</div>
						<div class='form-group col-sm-6 col-xs-12'>
							<label for="password">Password <span class="text-danger">*</span></label>
							{!! Form::password('password', ['class' => 'form-control', 'id'=>'password']) !!}
						</div>
						<div class='form-group col-sm-6 col-xs-12'>
							<label for="password_confirmation">Confirm Password<span class="text-danger">*</span></label>
							{!! Form::password('password_confirmation', ['class' => 'form-control', 'id'=>'password_confirmation']) !!}
						</div>
						<div class="form-group col-sm-6 col-xs-12">
							<label for="aboutme">About Me</label>
							{!! Form::textarea('aboutme', null, ['class' => 'form-control aboutme', 'id' => 'aboutme']) !!}
						</div>
						<div class="form-group col-sm-6 col-xs-12">
							<label for="imagefile">Profile Image</label>
							<div>
								<div class="imagethumb">
									<div class="imagethumb-in">
										<img src="{{ $user->imagefilepath }}" alt="{{ $user->fullname }}" />
										@if($user->hasimage)<a class="fa fa-remove" title="Remove" onclick="removeProfilePic();"></a>@endif
									</div>
								</div>
								<div class="inputfilecontainer">
									{!! Form::file('imagefile', ['class' => 'bootstrapfile', 'id' => 'imagefile']) !!}
									<p>Preferred image size is {{ config('app.constants.user_image_width') }} x {{ config('app.constants.user_image_height') }}.</p>
								</div>
							</div>
						</div>
						
						<div class="col-xs-12 text-right">
							<button type="submit" class="btn btn-warning">Submit</button>
						</div>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>  -->
<div class="mobil-block setting">
    <div class=""><div class="col-xs-12 col-sm-12 col-md-12">
			<a href="http://schemk.com/series" class="back-to-page fadeInDown">
				<img src="/images/ic_back@2X.png" >
			</a>
    </div></div>

	{!! Form::model($user, ['url' => '/user/edit', 'id' => 'formprofile']) !!}
	{!! Form::hidden('deleteimage', 0, ['id' => 'deleteimage']) !!}
	{!! Form::hidden('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
	<div class="setting__icon">
		<a href="#">
			<div class="setting__icon__img">
				<img src="{{ $user->imagefilepath }}" alt="{{ $user->fullname }}" style="width:100%;height:100%"/>
				@if($user->hasimage)<a class="fa fa-remove" title="Remove" onclick="removeProfilePic();"></a>@endif
			</div>
			

			<label class="fileContainer">Add profile image
				{!! Form::file('imagefile', ['class' => '', 'id' => 'imagefile']) !!}</label>
			</a>
		</div>

		<div class="setting__form">
			{{-- firstname --}}
			<div class="row">
				<div class="col-xs-12 col-sm-12">
					{!! Form::text('firstname', null, ['class' => 'form-control text-placeholder', 'id' => 'firstname', 'placeholder' => 'First name']) !!}
				</div>
			</div>
			{{-- lastname --}}
			<div class="row">
				<div class="col-xs-12 col-sm-12">
					{!! Form::text('lastname', null, ['class' => 'form-control text-placeholder', 'id' => 'lastname', 'placeholder' => 'Last name']) !!}
				</div>
			</div>

			{{-- age --}}
			<div class="row">

				<div class="col-xs-12 col-sm-12 text-center">
					{{-- @if($user->bod)
						{!! Form::text('age', null, ['class' => 'form-control', 'id' => 'age', 'placeholder' => 'Age', 'readonly']) !!}
						@else --}}
						<input type="text" id="date" data-format="DD-MM-YYYY" data-template="DD / MM / YYYY" />
						{{-- <input type="hidden" name="bod" id="bod"> --}}
						{!! Form::hidden('age', null, ['class' => 'form-control', 'id' => 'age']) !!}
						{!! Form::hidden('bod', null, ['class' => 'form-control', 'id' => 'bod', 'placeholder' => 'Bod', 'readonly']) !!}
						{{-- @endif --}}
					</div>
				</div>

				{{-- password --}}
				<div class="row">
					<div class="col-xs-6 col-sm-6">
						{!! Form::password('password', ['class' => 'form-control text-placeholder', 'id'=>'password' , 'placeholder' => 'Password']) !!}
					</div>
					<div class="col-xs-6 col-sm-6">
						{!! Form::password('password_confirmation', ['class' => 'form-control text-placeholder', 'id'=>'password_confirmation','placeholder' => 'Re-tipe Password']) !!}

					</div>
				</div>

			</div>
			<div class="row text-center profile-bio">
				<span class="">Bio</span>
				<div class="col-xs-12 col-sm-12">
					{!! Form::textarea('aboutme', null, ['class' => 'form-control aboutme', 'id' => 'aboutme', 'placeholder' => 'About me']) !!}
				</div>
			</div>
			<div class="row">
				<input type="submit" class="setting__btn" value="Done">
			</div>
			{!! Form::close() !!}
		</div>
		@endsection

		@section('page-scripts')
		<script src="{{ asset('js/profile.js?cache=1.7') }}"></script>
		<style>
		.fileContainer {
			overflow: hidden;
			position: relative;
		}

		.fileContainer [type=file] {
			cursor: inherit;
			display: block;
			font-size: 999px;
			filter: alpha(opacity=0);
			min-height: 100%;
			min-width: 100%;
			opacity: 0;
			position: absolute;
			right: 0;
			text-align: right;
			top: 0;
		}
		#age
		{
			float :none !important;
		}

.content-section .back-to-page img {
    width: 11px;
    height: 19px;
    margin: 11px 11px 11px -2px;
    position: absolute;
}
	</style>
	@endsection

@push('footer_script')
<script>
jQuery(document).ready(function(){
	var bod = $('#bod').val();
	var bodsplit = bod.split("/");
	var d = bodsplit[0];
	var m = bodsplit[1];
	var y = bodsplit[2];
	var dateFormat =d+"-"+m+"-"+y;
	if(dateFormat != null)
	{
		dateFormat = dateFormat;
	}
	else
	{
		dateFormat ="dd-mm-yyyy";
	}
		
	$('#date').combodate(
	{
		value : dateFormat,
		minYear: 1950,
		maxYear: moment().format('YYYY'),
		firstItem: 'name',
	});
	
	$('.year,.month,.day').on('change',function()
	{
		getdate = $('#date').combodate('getValue','YYYY-MM-DD');
		var today = new Date();
		dob = new Date(getdate);
		age = new Date(today - dob).getFullYear() - 1970;
		setdate =$('#date').combodate('getValue','DD/MM/YYYY');
		$('#bod').val(setdate);
		$('#age').val(age);
	});

	function readURL(input) {
  		if (input.files && input.files[0]) {
    		var reader = new FileReader();
			reader.onload = function(e) {
  				$('.setting__icon__img img').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
  		}
	}
	$("#imagefile").change(function() {
	  readURL(this);
	});

});
</script>
@endpush