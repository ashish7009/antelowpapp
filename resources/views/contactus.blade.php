@extends('layouts.front')

@section('pagetitle', 'Contact Us')

@section('content')
<div class="content-container">
	<div class="container">
		<div class="wow fadeInDown">
			<div class="mb5 pb5 p0">
				<h1 class="page-title color-pink">Contact Us</h1>
			</div>
			<div class="pt5 mt5">
				{!! Form::open(['url' => '/contact-us', 'id' => 'formcontactus']) !!}
					{!! Form::hidden('deleteimage', 0, ['id' => 'deleteimage']) !!}
					<div class="row">
						<div class='form-group col-sm-6 col-xs-12'>
							<label for="name">Name <span class="text-danger">*</span></label>
							{!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
						</div>
						<div class='form-group col-sm-6 col-xs-12'>
							<label for="email">Email <span class="text-danger">*</span></label>
							{!! Form::text('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
						</div>
						<div class='form-group col-xs-12'>
							<label for="message">Message <span class="text-danger">*</span></label>
							{!! Form::textarea('message', null, ['class' => 'form-control', 'id' => 'message']) !!}
						</div>
						<div class="col-xs-12 text-right">
							<button type="submit" class="btn btn-warning">Submit</button>
						</div>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('js/contactus.js?cache=1') }}"></script>
@endsection