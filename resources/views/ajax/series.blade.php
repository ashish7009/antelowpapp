@if(count($seriesmedias) > 0)
	<?php $pct=0;

?>
    @foreach($seriesmedias as $index => $seriesmedia)
        <section class="serieslist relative wow fadeIn" id="{{ seriesmedia_unique_string($seriesmedia) }}">
            <div class="comment-report-section">
         <?php
		
		 $tstatus=-1;
		 $rid=-1;
		// dd($seriesmedia->series->user); 
		//dd($seriesmedia->series->user['userid']);
		if(count($truefc)>0){
			 foreach($truefc as $t){				
				 if($t->friend1_id == $seriesmedia->series->user['userid'] || $t->friend2_id == $seriesmedia->series->user['userid']){
					
					$tstatus=$t->request_status;
					$rid=$t->truefriend_id;
				 }
			 }
		 }
		
		 ?>
                <div class="report-content-flag" data-seriesmediaid="{{ $seriesmedia->seriesmediaid }}">
                    <!-- Report -->
                    <img src="/images/flag.png" width="17px">
                    <ul class="report_now">
                        <li >It's offensive</li>
                        <li>It's a spam</li>
                        <li>Something else</li> 
                    </ul>
                </div>
            </div>
            <a class="profile" href="{{ url('/user/view/'.$seriesmedia->series['userid']) }}">
               <img src="{{ asset($seriesmedia->series->user['imagefilepath']) }}"
                     alt="{{ ucfirst($seriesmedia->series->user['fullname']) }}"
                     class="img-responsive userprofile inline-block img-thumbnail">
					
            </a>
            <a href="{{ url('/user/view/'.$seriesmedia->series->userid) }}" img="{{ $seriesmedia->hasfile }}"
               class="username inline-block">{{ ucfirst($seriesmedia->series->user['fullname']) }}</a>
            <div>
                <span class="total-episode pull-left">{{ $seriesmedia->created_at->diffForHumans() }}</span>
                <div class="col-xs-6 col-sm-6 col-md-6 text-right no-padding" style="float: right;">
                    <div class="availabel-display-container">
                        <a class="nounderline pr-10-tab">
                            @if(!$seriesmedia->ispublished)
                                {{-- <img src="/images/ic_unavailable@2X.png" style="margin-top: -3px;" class="" width="13px"
                                     height="13px"> --}}
                            @else
                                <img src="/images/ic_available@2X.png" style="margin-top: -3px;" class="" width="13px"
                                     height="13px">
                            @endif
                            {{-- <span class="availabel-unavailable-text ver-middle">@if($seriesmedia->ispublished)
                                    Available @else Unavailable @endif</span> --}}
                            <span class="availabel-unavailable-text ver-middle @if(!$seriesmedia->ispublished)  comming_soon @endif">@if($seriesmedia->ispublished)
                            Available @else @if($tstatus==1) Truefriend @else Coming Soon @endif <div class="coming_soon_tooltip"></div> @endif</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
			
			<?php if($globaldata['user']->userid != $seriesmedia->series->user['userid']){ ?>
				<div class="tf_banner" id="tf{{ $seriesmedia->seriesmediaid }}">
<div class="tf_left" data-target="{{ $seriesmedia->seriesmediaid }}">
<span class="fa-stack fa-lg icon-twitter">
          <i class="fa fa-circle fa-stack-2x"></i>
          <i class="fa fa-arrow-left fa-stack-1x"></i>
        </span>
</div>
<?php if($tstatus!=1){
	//echo $tstatus;
	?>
	
	
	<?php if($tstatus!=1){ ?>
<div class="tf_name">You are not {{ ucfirst($seriesmedia->series->user['firstname']) }} <strong>Truefriend</strong></div>
<?php }if($tstatus==1){ ?>
<div class="tf_name">You are  {{ ucfirst($seriesmedia->series->user['firstname']) }} <strong>Truefriend</strong></div>
<?php } ?>
<div class="tf_why">
<?php if($tstatus!=1){?>
<h3>Truefriends don't have to wait!</h3>
<p>Become {{ ucfirst($seriesmedia->series->user['firstname']) }}'s Truefriend and never wait to see a post again</p>
<?php }if($tstatus==1){ ?>
<h3>Truefriends don't have to wait!</h3>
<p> {{ ucfirst($seriesmedia->series->user['firstname']) }} is your Truefriend and never wait to see a post again</p>
<?php } ?>
<img class="tf_img" src="{{ asset($seriesmedia->series->user['imagefilepath']) }}" />
<br>
<?php if($tstatus==0){ ?>
<button type="button" class="btn btn-pink2 btn-lg tawait" id="tawait{{ $seriesmedia->seriesmediaid }}" data-f1="{{ $seriesmedia->series->user['userid'] }}" data-target="{{ $seriesmedia->seriesmediaid }}">Awaiting Approval </button><br>
<button type="button" class="btn btn-pink3 btn-lg tcancel" id="tcancel{{ $seriesmedia->seriesmediaid }}" data-f1="{{ $seriesmedia->series->user['userid'] }}" data-rid="<?php echo $rid; ?>" data-target="{{ $seriesmedia->seriesmediaid }}">Cancel Request</button><br>
<?php }if($tstatus==-1){ ?>
<button type="button" class="btn btn-pink1 btn-lg trequest" id="trequest{{ $seriesmedia->seriesmediaid }}" data-f1="{{ $seriesmedia->series->user['userid'] }}"  data-f2="{{$globaldata['user']->userid}}" data-target="{{ $seriesmedia->seriesmediaid }}">Request to become a Truefriend?</button><br>
<?php } ?>
</div>
	<?php }else{ ?>
	
	
			  @if($tstatus==0)
 <?php if($globaldata['user']->userid != $seriesmedia->series->user['userid']){ ?>
			<?php if(!$seriesmedia->imagefile){ ?>
						<div class="vid_preview"><i class="fa fa-video-camera xcam" aria-hidden="true"></i> Preview
</div>	 
						<?php } ?>		
		<div class="true_friend_right" data-target="{{ $seriesmedia->seriesmediaid }}">
		
		 <span class="fa-stack fa-lg icon-twitter">
          <i class="fa fa-circle fa-stack-2x"></i>
          <i class="fa fa-arrow-right fa-stack-1x"></i>
        </span>
		</div> 
			 <?php } ?>
                    <div class="rel-video {{ $seriesmedia->publishdateday }}"
                         @if($tstatus==0) data-publishdateair="{{ $seriesmedia->publishdateair }}" @endif>
                            <div class="rel-text">
                              <!--  <i class="fa fa-star" aria-hidden="true"></i>
                                @if($seriesmedia->request_counter == 0)
                                    <span class="">{{'0'}}</span> person is interested
                                @else
                                    @if($seriesmedia->request_counter == 1)
                                        <span class="">1</span> person is interested   
                                    @else
                                        <span class="">{{$seriesmedia->request_counter}} </span> people are 
                                        interested
                                    @endif
                                @endif -->
                            </div>
                        <span style="font-size: 26px; font-style: italic; margin-right: 5px;"></span> 
                        
                        <div class="{{ $seriesmedia->publishdateday }} watchlike "><span>
						<?php if(!$seriesmedia->imagefile){ ?>
						<i class="fa fa-video-camera" aria-hidden="true"></i>
						<?php }else{ ?>
						<div style="height:25px;width:25px;"></div>
						<?php } ?>
<?php 
//echo $seriesmedia->publishdateair;
            echo str_replace('inn ','<div class="on">in</div>',str_replace('on ','<div class="on">on</div>',str_replace('You can see this','<div class="avail">Available</div>',$seriesmedia->publishdateair))); ?></span></div>
            
                    </div>
                    @if($seriesmedia->imagefile)
                        @if(date('a', strtotime($seriesmedia->publish_time)) == 'pm')
                            <div class="bannertop moonlight">
                                <img src="/images/moon.png" height="14" width="14">
                                <span class="moonlighttext">{{ ucfirst($seriesmedia->series->user['firstname']) }}'s episodes airs every {{ $seriesmedia->publishdateday }}
                                    at {{ date('ha', strtotime($seriesmedia->publish_time)) }}</span>
                            </div>
                        @else
                            <div class="bannertop sunlight">
                                <img src="/images/sun.png" height="18" width="18">
                                <span class="sunlighttext">{{ ucfirst($seriesmedia->series->user['firstname']) }}'s episodes airs every {{ $seriesmedia->publishdateday }}
                                    at {{ date('ha', strtotime($seriesmedia->publish_time)) }}</span>
                            </div>
                        @endif
                    @endif
                @endif
                @if($seriesmedia->imagefile)
                    <a href="/uploads/{{ $seriesmedia->imagefile }}" class="pop" style="width: 100%;"
                       data-lightbox="image-{{$seriesmedia->seriesmediaid}}" data-title="{{ $seriesmedia->title }}">
                        <img src="/uploads/{{ $seriesmedia->imagefile }}" onload="javascript:checkImg(this)"
                             class="popup-image @if($tstatus==1) @else  @if($seriesmedia->imagefile) imageBlur  @endif @endif">
                    </a>
                    @if($tstatus==0)
						 <?php if($globaldata['user']->userid != $seriesmedia->series->user['userid']){ ?>
					<?php if(!$seriesmedia->imagefile){ ?>
						<div class="vid_preview"><i class="fa fa-video-camera xcam" aria-hidden="true"></i> Preview
</div>	
						<?php } ?>	
		<div class="true_friend_right" data-target="{{ $seriesmedia->seriesmediaid }}">
		 <span class="fa-stack fa-lg icon-twitter">
          <i class="fa fa-circle fa-stack-2x"></i>
          <i class="fa fa-arrow-right fa-stack-1x"></i>
        </span>
		</div> 
			 <?php } ?>
                        <img src="{{ asset('images/Photo logo.png') }}" alt="Photo" class="image_on_video">
                    @endif
                @else
                    @if($seriesmedia->isfile)
                        @if($seriesmedia->hasthumb)
                            <div class="thumbimg" style="background-image: url({{ asset($seriesmedia->thumbpath) }});"></div>
                        @endif
                        @if($seriesmedia->hasfile)
                            @if($seriesmedia->filethumbname != '')
                                <span style="background: url( {{ "'".asset('/videothumb/'.$seriesmedia->filethumbname)."'" }}) center center no-repeat;"
                                      class="video_thumb popup-image"></span>
                                <video id="series-{{$seriesmedia->seriesmediaid}}" class="seeris-video embed-responsive-item hidden bg"  preload="metadata" playsinline  loop>
                                    <source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/mp4">
                                    <source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/ogg">
                                    <source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/quicktime">
                                    Your browser does not support HTML5 video.
                                </video>
                                <img src="{{ asset('images/fullscreen.svg') }}" alt="" class="fullbtn">
                            @else
                                <video id="series-{{$seriesmedia->seriesmediaid}}" class="seeris-video embed-responsive-item bg"  preload="metadata" playsinline  loop>
                                    <source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/mp4">
                                    <source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/ogg">
                                    <source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/quicktime">
                                    Your browser does not support HTML5 video.
                                </video>
                               <!-- <img src="{{ asset('images/fullscreen.svg') }}" alt="" class="fullbtn">-->
                            @endif
                            @if($tstatus==0)
                               <img src="{{ asset('images/Video logo.png') }}" alt="Video" class="image_on_video"> 
								 <?php /*if($globaldata['user']->userid != $seriesmedia->series->user->userid){ ?>
						
		<div class="true_friend_right" data-target="{{ $seriesmedia->seriesmediaid }}"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i></div> 
			 <?php }*/ ?>
                                 <img src="{{ asset('images/videobtn.png') }}" alt="" class="playbtn newvideopreview" >
                                 <div class = "newlightboxOverlay requestcounter{{ $seriesmedia->seriesmediaid }}" style="height: 11278px; display: none;">
                                 	<div class = "newvideobanner">
                                 	<div style = "position: absolute;right: -37%;top: 4%;">
                                 		<img src = "https://www.schemk.com/images/add_new_close_btn.png" class="close_banner" style = "width: 8%;"> 
                                 	</div>
                                 		<div>
                                 		<span class ="newvideotext1">See the rest of it <?php echo str_replace('You can see this','',$seriesmedia->publishdateair); ?></span>
                                 			<a class="profile" href="{{ url('/user/view/'.$seriesmedia->series->userid) }}">
                <img src="{{ asset($seriesmedia->series->user['imagefilepath']) }}"
                     alt="{{ ucfirst($seriesmedia->series->user['fullname']) }}"
                     class="img-responsive userprofile inline-block img-thumbnail" style = "position: absolute;left: 40%;">          						<span style = "    position: absolute;left: 50%;top: 50%;
    transform: translateX(-50%);"> {{ ucfirst($seriesmedia->series->user['fullname']) }}</span>
                     					</a> 
                     
                     <span style = "padding-top: 25%;display: flex;padding-left: 12%;"> 
                     Let {{ ucfirst($seriesmedia->series->user['firstname']) }} know that you are interested in seeing this
                     </span>
                     
                     
                        <a href="javascript:void(0);" onclick="counterwatchvideo1(this,{{ $seriesmedia->seriesmediaid}} );"
                        	style = "
                        	background: #f31685;
				    display: flex;
				    height: 35px;
				    margin-left: 25%;
				    margin-right: 25%;
				    margin-top: 2%;
				    border-radius: 11px;
				                        	"
                        >
                        <i class="fa fa-star" id="interested601" aria-hidden="true" style = "display: flex;
    align-items: center;
    margin-left: 10%;"></i>
                        	<span style = "
                        		    display: flex;
					    margin-left: 17%;
					    font-size: 14px;
					    text-align: center;
					    align-items: center;
                        	"
                        	
                        	>I'm interested</span>
                        </a>
                     
            					</div>
                                 	</div>
                                 </div>
                            @else
                               <!-- <img src="{{ asset('images/videobtn.png') }}" alt="" class="playbtn">-->
                            @endif
                        @else
                            <div class="embed-responsive-item noimage-bg"></div>
                        @endif
                    @else
                        @if($seriesmedia->hasurlthumb)
                            <div class="thumbimg" style="background-image: url({{ asset($seriesmedia->urlthumbpath) }});"></div>
                        @endif
                        <iframe class="embed-responsive-item" src="{{ $seriesmedia->workingurl }}" allowfullscreen></iframe>
                    @endif
                @endif
				
                     
	
	<?php } ?>
</div>
		
            <div class="clearfix"></div>
			<?php } ?>
            <div data-seriesmediaid="{{ $seriesmedia->seriesmediaid }}" id="vid{{ $seriesmedia->seriesmediaid }}" class="embed-responsive embed-responsive-16by9 video seriesmediaid_{{$seriesmedia->seriesmediaid}} media-item @if($seriesmedia->ispublished) bindvideoplayanalytics @else  na-video {{ $seriesmedia->publishdateday }} @endif @if($seriesmedia->imagefile) imageheight @endif" @if(!$seriesmedia->ispublished) data-publishdateair="{{ $seriesmedia->publishdateair }}" @endif>
             
			

			  @if(!$seriesmedia->ispublished)
 <?php if($globaldata['user']->userid != $seriesmedia->series->user['userid']){ ?>
						<?php if(!$seriesmedia->imagefile){ ?>
						<div class="vid_preview"><i class="fa fa-video-camera xcam" aria-hidden="true"></i> Preview
</div>	
						<?php } ?>
		<div class="true_friend_right" data-target="{{ $seriesmedia->seriesmediaid }}">
		 <span class="fa-stack fa-lg icon-twitter">
          <i class="fa fa-circle fa-stack-2x"></i>
          <i class="fa fa-arrow-right fa-stack-1x"></i>
        </span>
		</div> 
			 <?php } ?>
                    <div class="rel-video {{ $seriesmedia->publishdateday }}"
                         @if(!$seriesmedia->ispublished) data-publishdateair="{{ $seriesmedia->publishdateair }}" @endif>
                            <div class="rel-text">
                              <!--  <i class="fa fa-star" aria-hidden="true"></i>
                                @if($seriesmedia->request_counter == 0)
                                    <span class="">{{'0'}}</span> person is interested
                                @else
                                    @if($seriesmedia->request_counter == 1)
                                        <span class="">1</span> person is interested   
                                    @else
                                        <span class="">{{$seriesmedia->request_counter}} </span> people are 
                                        interested
                                    @endif
                                @endif -->
                            </div>
                        <span style="font-size: 26px; font-style: italic; margin-right: 5px;"></span> 
                        
                        <div class="{{ $seriesmedia->publishdateday }} watchlike "><span>
						<?php if(!$seriesmedia->imagefile){ ?>
						<i class="fa fa-video-camera" aria-hidden="true"></i>
						<?php }else{ ?>
						<div style="height:25px;width:25px;"></div>
						<?php } ?>
<?php
echo str_replace('in ','<div class="on">in</div>',str_replace('on ','<div class="on">on</div>',str_replace('You can see this','<div class="avail">Available</div>',$seriesmedia->publishdateair))); ?></span></div>
                        
                    </div>
                    @if($seriesmedia->imagefile)
                        @if(date('a', strtotime($seriesmedia->publish_time)) == 'pm')
                            <div class="bannertop moonlight">
                                <img src="/images/moon.png" height="14" width="14">
                                <span class="moonlighttext">{{ ucfirst($seriesmedia->series->user['firstname']) }}'s episodes airs every {{ $seriesmedia->publishdateday }}
                                    at {{ date('ha', strtotime($seriesmedia->publish_time)) }}</span>
                            </div>
                        @else
                            <div class="bannertop sunlight">
                                <img src="/images/sun.png" height="18" width="18">
                                <span class="sunlighttext">{{ ucfirst($seriesmedia->series->user['firstname']) }}'s episodes airs every {{ $seriesmedia->publishdateday }}
                                    at {{ date('ha', strtotime($seriesmedia->publish_time)) }}</span>
                            </div>
                        @endif
                    @endif
                @endif
                @if($seriesmedia->imagefile)
                    <a href="/uploads/{{ $seriesmedia->imagefile }}" class="pop" style="width: 100%;"
                       data-lightbox="image-{{$seriesmedia->seriesmediaid}}" data-title="{{ $seriesmedia->title }}">
                        <img src="/uploads/{{ $seriesmedia->imagefile }}" onload="javascript:checkImg(this)"
                             class="popup-image @if($seriesmedia->ispublished) @else  @if($seriesmedia->imagefile) imageBlur  @endif @endif">
                    </a>
                    @if(!$seriesmedia->ispublished)
						 <?php if($globaldata['user']->userid != $seriesmedia->series->user['userid']){ ?>
						<?php if(!$seriesmedia->imagefile){ ?>
						<div class="vid_preview"><i class="fa fa-video-camera xcam" aria-hidden="true"></i> Preview
</div>	
						<?php } ?>
		<div class="true_friend_right" data-target="{{ $seriesmedia->seriesmediaid }}">
		 <span class="fa-stack fa-lg icon-twitter">
          <i class="fa fa-circle fa-stack-2x"></i>
          <i class="fa fa-arrow-right fa-stack-1x"></i>
        </span>
		</div> 
			 <?php } ?>
                        <img src="{{ asset('images/Photo logo.png') }}" alt="Photo" class="image_on_video">
                    @endif
                @else
                    @if($seriesmedia->isfile)
                        @if($seriesmedia->hasthumb)
                            <div class="thumbimg" style="background-image: url({{ asset($seriesmedia->thumbpath) }});"></div>
                        @endif
                        @if($seriesmedia->hasfile)
                            @if($seriesmedia->filethumbname != '')
                                <span style="background: url( {{ "'".asset('/videothumb/'.$seriesmedia->filethumbname)."'" }}) center center no-repeat;"
                                      class="video_thumb popup-image"></span>
                                <video id="series-{{$seriesmedia->seriesmediaid}}" class="seeris-video embed-responsive-item hidden bg"  preload="metadata" playsinline   loop>
                                    <source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/mp4">
                                    <source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/ogg">
                                    <source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/quicktime">
                                    Your browser does not support HTML5 video.
                                </video>
                                <img src="{{ asset('images/fullscreen.svg') }}" alt="" class="fullbtn">
                            @else
                                <video id="series-{{$seriesmedia->seriesmediaid}}" class="seeris-video embed-responsive-item bg"  preload="metadata" playsinline  loop>
                                    <source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/mp4">
                                    <source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/ogg">
                                    <source src="{{ $seriesmedia->filepath }}#t=0.5" type="video/quicktime">
                                    Your browser does not support HTML5 video.
                                </video>
								
                                <img src="{{ asset('images/fullscreen.svg') }}" alt="" class="fullbtn">
                            @endif
                            @if(!$seriesmedia->ispublished)
								<!--<div class="custom_overlay"></div>
                                <img src="{{ asset('images/Video logo.png') }}" alt="Video" class="image_on_video1"> -->
								 <?php if($globaldata['user']->userid != $seriesmedia->series->user['userid']){ ?>
						<?php if(!$seriesmedia->imagefile){ ?>
						<div class="vid_preview"><i class="fa fa-video-camera xcam" aria-hidden="true"></i> Preview
</div>	
						<?php } ?>
		<div class="true_friend_right" data-target="{{ $seriesmedia->seriesmediaid }}">
		 <span class="fa-stack fa-lg icon-twitter">
          <i class="fa fa-circle fa-stack-2x"></i>
          <i class="fa fa-arrow-right fa-stack-1x"></i>
        </span>
		</div> 
			 <?php } ?>
                                 <img src="{{ asset('images/videobtn.png') }}" alt="" class="playbtn newvideopreview" >
                                 <div class = "newlightboxOverlay requestcounter{{ $seriesmedia->seriesmediaid }}" style="height: 11278px; display: none;">
                                 	<div class = "newvideobanner">
                                 	<div style = "position: absolute;right: -37%;top: 4%;">
                                 		<img src = "https://www.schemk.com/images/add_new_close_btn.png" class="close_banner" style = "width: 8%;">
                                 	</div>
                                 		<div>
                                 		<span class ="newvideotext1">See the rest of it <?php echo str_replace('You can see this','',$seriesmedia->publishdateair); ?></span>
                                 			<a class="profile" href="{{ url('/user/view/'.$seriesmedia->series->userid) }}">
                <img src="{{ asset($seriesmedia->series->user['imagefilepath']) }}"
                     alt="{{ ucfirst($seriesmedia->series->user['fullname']) }}"
                     class="img-responsive userprofile inline-block img-thumbnail" style = "position: absolute;left: 40%;">          						<span style = "    position: absolute;left: 50%;top: 50%;
    transform: translateX(-50%);"> {{ ucfirst($seriesmedia->series->user['fullname']) }}</span>
                     					</a> 
                     
                     <span style = "padding-top: 25%;display: flex;padding-left: 12%;">
                     Let {{ ucfirst($seriesmedia->series->user['firstname']) }} know that you are interested in seeing this
                     </span>
                     
                     
                        <a href="javascript:void(0);" onclick="counterwatchvideo1(this,{{ $seriesmedia->seriesmediaid}} );"
                        	style = "
                        	background: #f31685;
				    display: flex;
				    height: 35px;
				    margin-left: 25%;
				    margin-right: 25%;
				    margin-top: 2%;
				    border-radius: 11px;
				                        	"
                        >
                        <i class="fa fa-star" id="interested601" aria-hidden="true" style = "display: flex;
    align-items: center;
    margin-left: 10%;"></i>
                        	<span style = "
                        		    display: flex;
					    margin-left: 17%;
					    font-size: 14px;
					    text-align: center;
					    align-items: center;
                        	"
                        	
                        	>I'm interested</span>
                        </a>
                     
            					</div>
                                 	</div>
                                 </div>
                            @else
                             <img src="{{ asset('images/videobtn.png') }}" alt="" class="playbtn">
                            @endif
                        @else
                            <div class="embed-responsive-item noimage-bg"></div>
                        @endif
                    @else
                        @if($seriesmedia->hasurlthumb)
                            <div class="thumbimg" style="background-image: url({{ asset($seriesmedia->urlthumbpath) }});"></div>
                        @endif
                        <iframe class="embed-responsive-item" src="{{ $seriesmedia->workingurl }}" allowfullscreen></iframe>
                    @endif
                @endif
				
            </div>
		 <div class="clearfix"></div>
            <div class="details" id="details_{{$seriesmedia->seriesmediaid}}">
                <div class="inline-block pull-right col-right p0">
                    <div class="col-xs-12 col-sm-12 col-md-12 no-padding pt-5-tab" style="color: #8d8d8d; font-size: 16px;">
                        <div style="float: left;">
                            @if(isset($globaldata['user']))
                                @if(isset($globaldata['myseriesmedialikes']) && in_array($seriesmedia->seriesmediaid, $globaldata['myseriesmedialikes']))
                                    <span class="total-likes ver-middle likescount color-hot-pink">
									   {{ intval($seriesmedia->series->user['likeinfluencer']) + intval($seriesmedia->seriesmedialikesdislikes()->likes()->count()) + intval($seriesmedia->alikes) }}
                                    </span>
                                    <img src="/images/heart-fill.png" width="17.5px" height="18px"
                                         class="heart-20 ver-bottom nounderline like-unlike color-hot-pink"
                                         title="Unlike" data-like="0" onclick="likeDislikeSeriesMedia(this,{{ $seriesmedia->seriesmediaid }},{{$globaldata['user']->userid}});">
                                        <!-- <a href="javascript:void(0);" class="fa fa-heart heart-20 ver-bottom nounderline like-unlike color-hot-pink" title="Unlike" data-like="0" onclick="likeDislikeSeriesMedia(this,$seriesmedia->seriesmediaid);"></a> -->
                                @else
                                    <span class="total-likes ver-middle likescount color-black">
									{{ intval($seriesmedia->series->user['likeinfluencer']) + intval($seriesmedia->seriesmedialikesdislikes()->likes()->count()) + intval($seriesmedia->alikes) }}
								    </span>
                                    <img src="/images/heart.png" width="17.5px" height="18px" class="ver-bottom nounderline like-unlike color-grey-border" title="Like" data-like="1" onclick="likeDislikeSeriesMedia(this,{{ $seriesmedia->seriesmediaid }},{{$globaldata['user']->userid}});">
                                    <!-- <a href="javascript:void(0);" class="fa fa-heart-o heart-20 ml-5-tab ver-bottom nounderline like-unlike color-grey-border margin-none" title="Like" data-like="1" onclick="likeDislikeSeriesMedia(this,$seriesmedia->seriesmediaid);"></a> -->
                                @endif
                            @else
                                <span class="total-likes ver-middle likescount color-black">
								{{ intval($seriesmedia->series->user['likeinfluencer']) + intval($seriesmedia->seriesmedialikesdislikes()->likes()->count()) + intval($seriesmedia->alikes)}}
							     </span>
                                <a class="fa fa-heart-o heart-20 ver-bottom nounderline like-unlike color-hot-pink" title="Like" href="javascript: void(0);" onclick="pleaseLogin();"></a>
                            @endif
                            &nbsp;&nbsp;
                        </div>

                        <div class="comment-report-section1">
                            <div class="comments">
                                @if(isset($globaldata['user']) || $seriesmedia->seriesmediacomments()->active()->count() > 0)
                                    <a data-toggle="collapse" href="#commentsbox_{{ $seriesmedia->seriesmediaid }}">comments <small>(<span id="commentscount_{{ $seriesmedia->seriesmediaid }}">{{ $seriesmedia->seriesmediacomments()->active()->count() }}</span>)</small>
                                    </a>
                                @else
                                    <span>no comments, please login to add yours</span>
                                @endif
                            </div>
                            @if($seriesmedia->ispublished)
                                 @if($seriesmedia->episodes_will_air != 0)
                                    <a href="javascript:void(0);" class="air-count-toggle">
                                        <span class="upcoming_episode_popover hidden"><i class="fa fa-info-circle" aria-hidden="true"></i> Upcoming episode</span>
                                        <div class="air-count">
                                            {{-- <img src="{{asset('/images/play-tv.png')}}" width="20" height="15"> --}}
                                            <span class="count">{{ $seriesmedia->episodes_will_air }}</span>
                                            <span class="count-title">
                                                <svg version="1.1" id="Layer_2" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 19.237 17.288" enable-background="new 0 0 19.237 17.288"   xml:space="preserve">
                                                    <g>
                                                        <path fill="#1787FB" d="M16.665,1.114H2.475c-0.83,0-1.5,0.681-1.5,1.5v9.94c0,0.83,0.67,1.5,1.5,1.5h6.59v1.56h-3.03v1h6.82v-1
                                                            h-2.79v-1.56h6.6c0.82,0,1.5-0.67,1.5-1.5v-9.94C18.165,1.795,17.484,1.114,16.665,1.114z M17.165,12.555c0,0.28-0.23,0.5-0.5,0.5
                                                            H2.475c-0.28,0-0.5-0.22-0.5-0.5v-0.31h15.19V12.555z"/>
                                                    </g>
                                                </svg>
                                               {{--  <svg version="1.1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/" x="0px" y="0px" width="91.411px" height="21.269px" viewBox="0 0 91.411 21.269" enable-background="new 0 0 91.411 21.269" xml:space="preserve">
                                                    <g id="Layer_2">
                                                        <g>
                                                            <path fill="#1687FB" d="M82.749,20.039H1.244V1.206h89.074L82.749,20.039z"/>
                                                        </g>
                                                        <g>
                                                            <path fill="#FFFFFF" d="M18.324,4.586H5.164c-1.25,0.22-1.56,1.21-1.47,1.75l0.01,7.8c-0.04,0.681,0.12,1.221,0.48,1.59
                                                                c0.44,0.471,1,0.48,1.06,0.48h6.04v0.5h-2v1h5v-1h-2v-0.5h6.04c0.07,0,0.63-0.01,1.07-0.48c0.35-0.369,0.51-0.909,0.48-1.56
                                                                l-0.01-7.76C19.964,5.796,19.654,4.806,18.324,4.586z M18.874,6.336v7.86c0.02,0.38-0.05,0.67-0.2,0.84
                                                                c-0.15,0.16-0.35,0.17-0.35,0.17H5.244c0,0-0.19-0.01-0.34-0.16c-0.15-0.17-0.22-0.46-0.2-0.88l-0.01-7.9
                                                                c-0.02-0.17,0-0.579,0.55-0.68h6.54l6.46-0.01C18.884,5.687,18.904,6.096,18.874,6.336z"/>
                                                        </g>
                                                    </g>
                                                    <g id="Layer_3">
                                                        <text transform="matrix(1 0 0 1 23.3271 12.5381)" fill="#FFFFFF" font-family="'MyriadPro-Regular'" font-size="7">Upcoming episodes</text>
                                                    </g>
                                                </svg> --}}
                                            </span>
                                        </div>
                                    </a>
                                @endif
                            @endif
                        </div>
                        <div style="float: right; display: flex;">
                            @if($seriesmedia->ispublished)
                                <div style="display: flex; justify-content: flex-end; align-items: flex-end;">
								    <span class="share ver-middle color-black" style="font-size: 20px;"> {{ $seriesmedia->seriesmediaclicks()->count() }} </span>
                                    <span  style="font-size: 14px;margin-left: 5px;background: #00eeff;color: #0f1626;padding: 6px;border-radius: 50%;font-size: 18px;"><i class="fa fa-eye"></i></span>
                                </div>
                            @else
								<?php  $pct++; ?>
                                <div class="report-content1" data-seriesmediaid="{{ $seriesmedia->seriesmediaid }}" style="margin-left: 20px;">
                                    <a href="javascript:void(0);" onclick="counterwatchvideo(this,{{ $seriesmedia->seriesmediaid}} );">
                                        <div class="watch-count" id="views">
                                            <span class="watchint">I'm Interested! &nbsp; </span> 
                                           {{--  <svg version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 20 20" enable-background="new 0 0 20 20" xml:space="preserve">
                                                <polygon fill="#1187fd" points="5.91,7.87 10,9.93 10,10.09 5.95,12.12 "/>
                                                <polygon fill="#1187fd" points="15.155,10.01 11,12.12 11,10.69 11,9.32 11,7.87 "/>
                                                <path fill="#1187fd" d="M10,0.4c-5.3,0-9.6,4.3-9.6,9.6s4.3,9.6,9.6,9.6s9.6-4.3,9.6-9.6S15.3,0.4,10,0.4zM9.79,13.75l0.01-2.54l-5.01,2.54l0.02-7.51L9.8,8.8l0.01-2.56l7.34,3.77L9.79,13.75z"/>
                                                <path fill="#FFFFFF" d="M17.15,10.01L9.81,6.24L9.8,8.8L4.81,6.24l-0.02,7.51l5.01-2.54l-0.01,2.54L17.15,10.01z M5.9,12.12l0.06-4.25L10,9.93v0.16L5.9,12.12z M11,9.32V7.87l4.155,2.14L11,12.12v-1.43V9.32z"/>
                                            </svg> --}}
                                            <span class="interested"   data-target="{{ $seriesmedia->seriesmediaid }}"> <i class="fa fa-star-o" id="interested{{ $seriesmedia->seriesmediaid }}" aria-hidden="true"></i><span class="interested_counter{{ $seriesmedia->seriesmediaid }}">{{$seriesmedia->request_counter}}</span></span>
                                        </div>
										
                                    </a>
                                </div> 
								
                                <!-- <a href="javascript:void(0);" title="I want to see this now" data-toggle="popover" data-placement="bottom" data-content="Can't wait for the air date?" onclick="counterwatchvideo(this,{{ $seriesmedia->seriesmediaid }});" >
    								<span class="watch-text">Wants to see this now</span>
    							</a> -->
                            @endif
                        </div>
                    </div>
                </div>
                <div class="clear-row"></div>
				<div class="inline-block">
				<?php 

				if($globaldata['user']->tour_count<=15){
               if(!$seriesmedia->ispublished){
	
				?>
				
										<div class="availabel-unavailable-text ver-middle   comming_soon1 ">  <div class="coming_soon_tooltip1"></div><i class="fa fa-info-circle" aria-hidden="true"></i>
 Let <?php $xnt=explode(' ',ucfirst($seriesmedia->series->user['fullname'])); echo $xnt[0]; ?> know you're interested in seeing this</div>
 
<?php             }} ?>
										</div>
										 <div class="clear-row"></div>
                <div class="inline-block pull-left col-left pt-15-tab">
                    @if(!$seriesmedia->ispublished)
                    <span class="caption-title {{ $seriesmedia->publishdateday }}">{{ ucfirst($seriesmedia->publishdateday) }}
                                    at  {{ date('ha', strtotime($seriesmedia->publish_time)) }}</span>
                    @endif
                    <span class="episode comment-more more new" onload="function getemoji()" title="{{ $seriesmedia->title }}" data-toggle="tooltip">{{ strip_tags($seriesmedia->title) }}</span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
                <div class="clearfix"></div>
            </div>
            <div id="commentsbox_{{ $seriesmedia->seriesmediaid }}" class="comments-collapse panel-collapse collapse">
                <div class="comments-collapse-in">
                    <div class="comments-container @if($seriesmedia->seriesmediacomments()->active()->count() == 0) hide @endif" id="commentscontainer_{{ $seriesmedia->seriesmediaid }}">
                        <div class="commentsarea margin-left-80">
                            @if($seriesmedia->seriesmediacomments()->active()->count() > 0)
                                @foreach($seriesmedia->seriesmediacomments()->active()->get() as $seriesmediacomment)
                                    @include('seriesmediacomment')
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @if(isset($globaldata['user']))
                        <div class="comment-box comments-container">
                            <div class="comment padding-left-80 margin-left-80">
                                <div class="relative comment_form">
                                    <div class="form-left">
                                        <a href="{{ url('/user/view/'.$globaldata['user']->userid) }}" class="username small-hide">{{ ucfirst($globaldata['user']->fullname) }}
                                        </a>
                                        <a href="{{ url('/user/view/'.$globaldata['user']->userid) }}" class="profile">
                                            <img src="{{ asset($globaldata['user']->imagefilepath) }}"
                                                 alt="{{ ucfirst($globaldata['user']->fullname) }}"
                                                 class="img-responsive userprofile inline-block img-thumbnail">
                                        </a>
                                        <a href="{{ url('/user/view/'.$globaldata['user']->userid) }}" class="username small-hide">{{ ucfirst($globaldata['user']->fullname) }}</a>
                                    </div>
                                    <div class="form-right">
                                        <!-- <div class="episode-title pt0"> -->
                                        <div class="input-group">
                                            <textarea class="form-control comment-textarea" placeholder="Add comment" id="commenttextarea_{{ $seriesmedia->seriesmediaid }}"></textarea>
                                            <span class="input-group-btn">
    											<button class="btn btn-lg comment-btn" type="button" data-seriesmediaid="{{ $seriesmedia->seriesmediaid }}" data-target="#commenttextarea_{{ $seriesmedia->seriesmediaid }}" onclick="addComment(this);"> <img src="/images/send.png" alt=""> </button>
										    </span>
                                        </div>
                                        <!-- </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endforeach
@else
    <h3 class="messagetext text-center wow fadeInDown">No airs found.</h3>
@endif
<div id="serieselikemodel" class="modal md fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="serieselikeform" method="post" class="serieselikeform">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title modeltitle">Media Like</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" name="seriesmediaid" class="seriesmediaid"/>
                        <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                        <div class="form-group form-label">
                            <label class="control-label">
                                Enter likes
                            </label>
                            <div>
                                <input type="text" name="likevalue" class="form-control likevalue" placeholder="e.g. 5">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-invite">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
/*jQuery(document).ready(function($) {
   $(document).on('click', '.na-video', function(event) {
   var me = $(this).find('video')[0];
		me.play();
		 me.on("timeupdate", function(event) {
        var currenttime = me.currentTime;
    var duration = me.duration;
    var checktime = me.duration * 0.51;
    if (currenttime >= checktime) {
        me.pause();
        me.currentTime = 0;
        me.play();
    }
    });
   });
});*/  
	$(document).on('click','.close_banner',function(e){
	$(this).parent().parent().parent().hide();
	$(this).parent().parent().parent().css('display','none');	
	});
	$(document).on('click','.newvideobanner',function(e){
		
	$(this).parent().hide();
	$(this).parent().css('display','none');	
	});
  
    $('body').delegate('.report-content1', 'click', function (event) {
        $(this).find('ul').toggle();
    });
	  $('body').delegate('.newlightboxOverlay', 'click', function (event) {
        $(this).toggle();
    });
	  $('body').delegate('.report-content-flag', 'click', function (event) {
        $(this).find('ul').toggle();
    });

    $('body').delegate('.report-content1 ul li', 'click', function (event) {
        var seriesmediaid = $(this).parent('.report-content1').data('seriesmediaid');
        var reason = $(this).text();
    });
	

    checkImg = function (img) {
        $("<img/>") // Make in memory copy of image to avoid css issues
            .attr("src", $(img).attr("src"))
            .load(function () {
                pic_real_width = this.width;   // Note: $(this).width() will not
                pic_real_height = this.height; // work for in memory images.
                var imgClass = (this.width / this.height > 1) ? 'wide' : 'tall';
                $(img).addClass(imgClass);
            });
    };

    // console.log(screen.width, screen.height);
    $('.video').css('height', screen.width * 0.9);

    // Close button css
    jQuery(document).ready(function () {
        jQuery('.cpation-close').click(function () {
            jQuery(this).parent().css('display', 'none');
        });
        $('#serieselikeform').on('submit', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var seriesmediaid = $(this).find('.seriesmediaid').val();
            var likes = $(this).find('.likevalue').val();
            if (likes != '') {
                //  var seriesmediaid = $(this).data('seriesmediaid');
                $.ajax({
                    type: "POST",
                    url: baseurl() + '/series/media/adminlike',
                    data: {'seriesmediaid': seriesmediaid, 'likevalue': likes},
                    dataType: 'json',
                    success: function (res) {
                        console.log(res);
                        $('#serieselikemodel').modal('hide');
                        $('#details_'+seriesmediaid).find('.likescount').html(res.count);
                    }
                });
            } else {
                showError('Please enter your like.');
            }
        });

        jQuery('.air-count-toggle').each(function(){
            jQuery(this).on('click',function(){
                jQuery('.upcoming_episode_popover').addClass('hidden');
                jQuery(this).find('.upcoming_episode_popover').removeClass('hidden');
            });
        });
    });
</script>
<style>
.newvideobanner{
	position: absolute;
    text-align: center;
    height: 2%;
    background: #030217;
    left: 5%;
    right: 5%;
    top: 40px;
    bottom: 5%;
    border-radius: 10px;
}
.newvideotext1{
	    font-size: 17px;
	    font-weight: 1000;
    display: flex;
    padding-left: 11%;
    padding-top: 9%;
}
.newlightboxOverlay{
position: absolute;
    top: 0;
    left: 0;
    z-index: 9999;
   width: 100%;
   color:white;
    background: #4c4a4b2b;
    display: none;
}
.air-count .count {
   position: absolute;
    width: 18px;
    height: 18px;
    background: #ee2d4f;
    color: white;
    text-align: center;
    border-radius: 50%;
    top: -1px;
    left: 165px;
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99;
}

.air-count .count-title {
   position: absolute;
    height: 20px;
    background: none !important;
    top: 2px;
    left: 145px;
    width: auto;
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-align: center;
    border-radius: 15px;
    padding: 5px;
}
.upcoming_episode_popover{
    position: absolute;
    top: -30px;
    left: 152px;
    font-size: 13px;
    background-color: #FFFFFF;
    border-radius: 10px;
    width: 130px;
    padding: 2px 8px;
    color: #000000;
}
</style>