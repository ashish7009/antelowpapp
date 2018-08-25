@if(isset($seriesmediacomments) && count($seriesmediacomments) > 0)
	@foreach($seriesmediacomments as $index => $seriesmediacomment)
	<div class="seriesmediacomment seriesmediacomment_{{ $seriesmediacomment->seriesmediacommentid }} @if($index != count($seriesmediacomments)-1) pb5 mb5 @endif">
		<div class="row row5">
			<div class="col-xs-2 col5">
				<img class="img-thumbnail" src="{{ asset($seriesmediacomment->user->imagefilepath) }}" alt="{{ ucfirst($seriesmediacomment->user->fullname) }}" />
			</div>
			<div class="col-xs-8 col5">
				<div><strong class="color-pink">{{ ucfirst($seriesmediacomment->user->fullname) }}</strong></div>
				<div>{{ $seriesmediacomment->comment }}</div>
				<div class="color-grey"><small>On {{ $seriesmediacomment->created_at->format(config('app.constants.dateformat_listing_datetime')) }}</small></div>
			</div>
			<div class="col-xs-2 col5 text-right">
				<a href="javascript:void(0);" onclick="deleteComment({{ $seriesmediacomment->seriesmediacommentid }});" title="Delete" class="fa fa-trash-o fa-action nounderline"></a>
			</div>
		</div>
	</div>
	@endforeach
@else
<div class="text-center">
	<div class="callout callout-default m0">
		<h4 class="m0">No comments found!</h4>
	</div>
</div>
@endif