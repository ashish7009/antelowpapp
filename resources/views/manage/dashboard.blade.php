@extends('manage.layouts.admin')

@section('pagetitle', ' - Dashboard')

@section('content')
<section class="content-header">
	<h1>Dashboard <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li class="active"><i class="fa fa-dashboard"></i> Home</li>
	</ol>
</section>
<section class="content">      
	<div class="box box-custom">
    	<div class="box-body text-center">
    		<div class="pv100">
	        	<h3>Welcome to the <strong>Admin Area</strong></h3>
				<h4>Website Admin Panel.</h4>
				<h4>You can use the left navigation panel to update the website contents.</h4>
			</div>
		</div>
	</div>
</section>
@endsection