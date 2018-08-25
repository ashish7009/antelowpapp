@extends('layouts.front')

@section('pagetitle', 'Verify Email')

@section('content')
<div class="content-container">
	<div class="container">
		<div class="wow fadeInDown">
			<h1 class="page-title color-pink">Email Verification</h1>
			@if($type == 'success')
			<h3 class="messagetext text-success">{{ $message }}</h3>
			@else
			<h3 class="messagetext text-danger">{{ $message }}</h3>
			@endif
		</div>
	</div>
</div>
@endsection