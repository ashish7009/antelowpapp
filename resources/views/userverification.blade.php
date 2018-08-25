@extends('layouts.front')

@section('pagetitle', 'Verify Account')

@section('content')
<style>
.wrapper{
	    background: #0f1626 !important;
}
</style>
<div class="content-container" style="background:#0f1626;">
	<div class="container">
		<div class="wow fadeInDown">
		<div style="text-align:right;">
		<img src="http://www.schemk.com/images/logo1.png" style="height: 150px;" />
		</div>
		<div style="text-align: center;color:#fff;">
			
			@if($type == 'success')
			<i class="fa fa-check-circle-o" aria-hidden="true" style="    font-size: 130px;
    color: #00c2ca;"></i>	
			<h3 class="messagetext " style="font-size: 30px;
    line-height: 35px;">{{ $message }}</h3>
			@else
				<i class="fa fa-times-circle-o" aria-hidden="true" style="    font-size: 130px;
    color: #f6007a;"></i>
			<h3 class="messagetext" style="font-size: 30px;
    line-height: 35px;">{{ $message }}</h3>
			@endif
		</div>
		</div>
	</div>
</div>
@endsection