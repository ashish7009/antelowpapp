@extends('layouts.front')

@section('pagetitle', 'Follower Details')

@section('content')
<div class="content-container">
	<div class="container">
		<div class="wow fadeInDown row">
			<br/><div class="col-lg-12 text-center"><h4>Search Result</h4></div>
			<div class="clearfix"></div><br/>
		{{-- @if($search_tag == 'hashtag')
			@if(count($users) > 0)
				@foreach($users as $suser)
				<div class="col-sm-6 mt5 pt5 single-user">
					<div class="col-sm-4 col-xs-4 text-center">
						<img src="{{ asset($suser->imagefilepath) }}" alt="{{ $suser->title }}" class="img-responsive img-thumbnail inline-block rounded width100 max-width200-500"> 
					</div>
					<div class="col-sm-8 col-xs-8 padding-none">
						<a href="{{ url('/user/view/'.$suser->userid) }}" class="mt5 pt5 user-episode user-title">{{ $suser->firstname }}</a>
						<br>
					</div>
					<div class="clearfix"></div>
				</div>
				@endforeach
			@else
				<h3 class="messagetext text-center wow fadeInDown">No followers found.</h3>
			@endif
		@else --}}
			@if(count($users) > 0)
				@foreach($users as $suser)
				<div class="col-sm-6 mt5 pt5 single-user">
					<div class="col-sm-4 col-xs-4 text-center">
						<img src="{{ asset($suser->imagefilepath) }}" alt="{{ $suser->title }}" class="img-responsive img-thumbnail inline-block rounded width100 max-width200-500"> 
					</div>
					<div class="col-sm-8 col-xs-8 padding-none">
						<a href="{{ url('/user/view/'.$suser->userid) }}" class="mt5 pt5 user-episode user-title">{{ $suser->firstname }}</a>
						<br>
						@if($suser->publish_day == '')

						@else
							<span class="user-details mt5 pt5 
							@if($suser->publish_day == 'Mondays') monday 
							@elseif($suser->publish_day == 'Tuesdays') tuesday 
							@elseif($suser->publish_day == 'Wednesdays') wednesday
							@elseif($suser->publish_day == 'Thursdays') thursday
							@elseif($suser->publish_day == 'Fridays') friday
							@elseif($suser->publish_day == 'Saturdays') saturday
							@elseif($suser->publish_day == 'Sundays') sunday
							@endif">
							content airs every {{ $suser->publish_day }}</span>
						@endif
					</div>
					<div class="clearfix"></div>
				</div>
				@endforeach
			@else
				<h3 class="messagetext text-center wow fadeInDown">No User found.</h3>
			@endif
		{{-- @endif --}}
		</div>
	</div>
</div>
@endsection
