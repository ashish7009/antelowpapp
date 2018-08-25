@extends('layouts.front')

@section('pagetitle', 'Follower Details')

@section('content')
<div class="content-container" id="followers-page">
	<div class="col-xs-12 col-sm-12 col-md-12">
			<a href="http://schemk.com/series" class="back-to-page fadeInDown">
				<img src="/images/ic_back@2X.png">
			</a>
    </div>
	<div class="container">
	<?php //print_r($truefc); ?>
	<?php //print_r($tfud);
$used=array();
	?>
		<div class="wow fadeInDown row">
		@if(count($myfollowusers) > 0)
			<ul class="myfollowers-list">
			@foreach($myfollowusers as $myfollowuser)
			<?php if($myfollowuser->follower['firstname']!=null && $myfollowuser->follower['firstname']!=''){ ?>
				<li>
					<a href="{{url('user/view/'.$myfollowuser['followerid'])}}"><span class="avatar" style="background-image: url({{ asset($myfollowuser->follower['imagefilepath']) }})"></span></a>
					<a href="{{url('user/view/'.$myfollowuser['followerid'])}}"><span class="name">{{ $myfollowuser->follower['firstname'] }} {{ $myfollowuser->follower['lastname'] }}</span></a>
					<?php 
					$count=0;
					$used[]=$myfollowuser['followerid'];
					foreach($tfud as $id=>$t){
						
					if($id==$myfollowuser['followerid']){
						$count++;
					}
					}

					?>
					
					<?php ?>
					<?php if($count==0){ ?>
					<span class="follow">Follows you</span>
					<span class="follow-plus" data-img="{{ asset($myfollowuser->follower['imagefilepath']) }}" data-name="{{ $myfollowuser->follower['firstname'] }}" data-f2="<?php echo $myfollowuser['userid']; ?>" data-f1="{{ $myfollowuser['followerid'] }}" data-target="{{ $myfollowuser['followerid'] }}"><i class="fa fa-plus-circle" aria-hidden="true"></i></span>
					<?php }else{ ?>
					<span class="follow-true">Truefriend</span>
					<?php } ?>
				</li>
			<?php } ?>
			@endforeach
			<?php foreach($tfud as $id=>$t){
						if($t['firstname']!=null && $t['firstname']!=''){ 
					if(array_search($id,$used)==FALSE){
						?>
						
						<li>
					<a href="{{url('user/view/'.$id)}}"><span class="avatar" style="background-image: url({{ asset($t['imagefilepath']) }})"></span></a>
					<a href="{{url('user/view/'.$id)}}"><span class="name">{{ $t['firstname'] }} {{ $t['lastname'] }}</span></a>
					
					
					<span class="follow-true">Truefriend</span>
					
				</li>
						<?php
					}
						}
			}
					
					?>
			
			</ul>
		@else
			<h3 class="messagetext text-center wow fadeInDown">No followers found.</h3>
		@endif
		</div>
	</div>
</div>
<div id="tr_request" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
  <div class="modal-header custom_header">
        <h4 class="modal-title"> <h3 >Add <span class="custom_request_name_add">Eval</span> as a Truefriend</h3></h4>
      </div>
      <div class="modal-body custom_requestadd" style="text-align:center" >
	 <img class="tf_imgadd1" src="" />
        <p>By being a Truefriend, <span class="custom_request_name_add1">Eval</span> can see your episodes immediately without waiting </p>
		
		<div style="text-align: center;margin-top: 30px;">
<a type="button" class="btn  btn-lg  trequest_add" role="button" >Add as Truefriend</a>
      </div>
      </div>
     
    </div>

  </div>
</div>
@endsection
<style type="text/css">
	
</style>