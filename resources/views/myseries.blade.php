@extends('layouts.front')

@section('htmlclass', 'theme-black')

@section('pagetitle', 'My Series')

@section('content')
<div class="content-container pb0">
	<div class="container">
		<h1 class="page-title color-pink">My Series</h1>
		<div class="text-center pb20"><a class="top-box text-center wow fadeInDown" href="{{ url('/vlogs/edit') }}">Start a Vlog Series</a></div>
		<div class="serieslistcontainer">
			@if(count($serieslist) > 0)
				@foreach($serieslist as $series)
				<div class="series series_{{ $series->seriesid }} wow fadeIn">
					<div class="row">
						<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2 width50-500 seriesthumb">
							<div class="imgae-upload">
								<!-- <img src="{{ $series->filepath }}" alt="{{ $series->title }}" class="img-responsive" />  -->
								<img src="{{ $globaldata['user']->imagefilepath }}" alt="{{ $globaldata['user']->fullname }}" class="img-responsive img-thumbnail rounded width100" /> 
							</div>
						</div>
						<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2 width50-500 text-center pull-right">
							<div class="mb5 pb5">
								<div class="pb5 mb5"><span class="bg-white color-pink circle">{{ $series->seriesmedias()->get()->count() }}</span></div>
								<div class="pt5 mt5"><span class="label bg-pink color-white inline-block"><strong>@if($series->seriesmedias()->get()->count() == 1) Episode @else Episodes @endif</strong></span></div>
							</div>
							<div class="pt5 mt5">
								<div class="pb5 mb5"><a href="{{ url('/series/edit/'.$series->slug ) }}" class="bg-white color-black circle"><i class="fa fa-plus"></i></a></div>
								<div class="pt5 mt5"><a href="{{ url('/series/edit/'.$series->slug ) }}" class="color-white inline-block"><strong>Add Media</strong></a></div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-8 col-lg-8 width100-500">
							<div class="form-group">
								<h2 class="page-title color-white m0">{{ $series->title }}</h2>
							</div>
							<!-- <div class="form-group">
								<p class="color-white m0">{!! nl2br($series->description) !!}</p>
							</div> -->
							<div>
								<a class="btn btn-bordered btn-pink btn-rounded mr5" href="{{ url('/series/edit/'.$series->slug) }}">Edit</a>
								<a class="btn btn-bordered btn-pink btn-rounded ml5" href="javascript: void(0)" onclick="deleteSeries({{ $series->seriesid }});">Delete</a>
							</div>
						</div>
					</div>
					<div><hr class="grey margin30"></div>
				</div>
				@endforeach
			@else
			<h3 class="messagetext text-center color-white wow fadeInDown">No series found.</h3>
			@endif
		</div>
	</div>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('js/myseries.js?cache=1.3') }}"></script>
@endsection