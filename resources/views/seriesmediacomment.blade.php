@if(isset($seriesmediacomment))
<div class="comment padding-left-80">
	<div class="relative">
    	<a href="{{ url('/user/view/'.$seriesmediacomment->userid) }}" class="username small-hide">{{ ucfirst($seriesmediacomment->user->fullname) }}</a>
        
        <div class="col-xs-12 col-sm-12 col-md-12" style="padding: 0;">
            <div class="col-xs-3 col-sm-3 col-md-3" style="padding: 0 10px; width: 54px;">
                <a href="{{ url('/user/view/'.$seriesmediacomment->userid) }}" class="profile">
                    <img src="{{ asset($seriesmediacomment->user->imagefilepath) }}" alt="{{ ucfirst($seriesmediacomment->user->fullname) }}" class="img-responsive userprofile inline-block no-margin no-max-width">
                </a>
            </div>
            <div style="width: calc(100% - 54px;); padding: 0; padding-right: 10px;">
                <a href="{{ url('/user/view/'.$seriesmediacomment->userid) }}" class="username small-visible no-padding">{{ ucfirst($seriesmediacomment->user->fullname) }}</a>
                <div class="episode-title no-padding">
                    {!! nl2br($seriesmediacomment->comment) !!}
                </div>
            </div>
        </div>

	</div>
</div>
@endif