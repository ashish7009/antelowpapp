@if(isset($users) && count($users) > 0)
	@foreach($users as $index => $row)
	<tr>
		<td class="text-center">{{ ( ($users->currentPage() - 1 ) * $users->perPage() ) + $index + 1 }}</td>	
		<td class="field_userimage">
			<img class="profile-user-img" alt="{{ $row->fullname }}" src="{{ $row->imagefilepath }}" />
		</td>
		<td>
			<div>
				<span class="mr5 pr5"><b>Name:</b> {{ $row->fullname }}</span>
			</div>
			<div>
				<span class="mr5 pr5"><b>Email:</b>&nbsp;<a href="mailto:{{ $row->email }}">{{ $row->email }}</a></span>
			</div>
			<div>
				<span class="mr5 pr5"><b>Age:</b> {{ $row->age }}</span>
				<span class="mr5 pr5"><b>Followers:</b> {{ $row->followers()->count() }}</span>
				<span class="mr5 pr5"><b>Airs:</b> {{ $row->series()->active()->first()->seriesmedias()->active()->count() }}</span>
			</div>
			<div>
				<span class="mr5 pr5"><b>Interests:</b> {!! trim($row->interests) != '' ? $row->interests : '<i>NA</i>' !!}</span>
			</div>
		</td>
		<td>
			<div class="descscrollable"><small>{!! trim($row->aboutme) != '' ? $row->aboutme : '<i>NA</i>' !!}</small></div>
		</td>
		<td class="text-center">{{ $row->created_at->format(config('app.constants.dateformat_listing_datetime')) }}</td>
		<td class="text-center">
			<a href="{{ url('/user/view/'.$row->userid) }}" title="View" class="fa-action" target="_blank">View</a>
			|
			<a href="javascript:void(0);" onclick="openInfluencerPopup('{{ $row->fullname }}', {{ $row->userid }}, {{ $row->isinfluencer }}, {{ intval($row->likeinfluencer) }}, {{ intval($row->followerinfluencer) }});" title="Influencer" class="fa-action">Influencer</a>
		</td>
	</tr>
	@endforeach
@else
<tr>
	<td colspan="6" class="text-center">
		<div class="callout callout-default m0">
			<h4 class="m0">No users found!</h4>
		</div>
	</td>
</tr>
@endif
<tr id="ajaxpagingdata">
	<td>
		{!! $users->render() !!}
	</td>
</tr>