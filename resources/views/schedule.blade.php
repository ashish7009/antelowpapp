@extends('layouts.front')

@section('pagetitle', 'Schedule list')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">
@endsection

@section('page-scripts')
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
@endsection

@section('content')
<div class="content-container-">
	<div class="container">
		<div class="wow fadeInDown row">
			<div class="schedule__list">
				{{-- @if($key == 'Mondays' ) active @else hidden @endif --}}
				<div class="owl-carousel owl-theme">
					@foreach($user_schedule as $key=> $value)
					<div class="item">
						<div class="schedule__item schedule__item_{{ strtolower(substr($key, 0, -1)) }} step step-{{$key}} " data-step="{{$key}}">
							<div class="schedule_box">
								<div class="homepage_link"><a href="/series"><i class="fa fa-angle-left" aria-hidden="true"></i></a></div>
								<h1 class="width100 cnt{{count($value)}}">
									<div class="schedule__item__weekday text-center">
										<a href="{{ url('/user/schedule_day/'.$key) }}">{{$key}}</a>
									</div>
								</h1>
								<div class="schedule__item__right- setmin">
									<h5 class="schedule__item__info text-center"> 
										@if(count($value) > 0)
										<span class="number">{{count($value)}}</span> 
										@if(count($value) == 1) 
										friend is 
										@else 
										friends are 
										@endif 
										airing content on {{ $key }}
										@else
										No friend is airing content on {{ $key }}
										@endif
									</h5>
								</div>
								<br><br>
								<?php $number = 0; ?>
								<div class="schedule__item__frendlist text-center">
									@if(count($value) > 0)
									@foreach($value as $myfollowuser)
									@if($number <= 10)
									@if($myfollowuser->publish_day == $key)
									<a href="{{ url('/user/view/'.$myfollowuser->userid) }}"><img src="{{ asset($myfollowuser->imagefilepath) }}"></a>
									<?php $number++ ?>   
									@endif
									@endif
									@endforeach
									@if($number%4 == 0)
									<div class="clearfix"></div>
									@endif	
									@endif
								</div>
								@if($number >=8)
								<div class="text-center">
									<button class="schedule-see-more">See More</button>
								</div>
								@endif
								
								{{-- <div>
									<a href="javascript:void(0);" class="hidden"><i class="fa fa-angle-left left-arrow2" aria-hidden="true"></i></a>
									<a href="javascript:void(0);"><i class="fa fa-angle-right right-arrow2" aria-hidden="true"></i></a>
								</div> --}}
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>

@endsection


@push('footer_script')

<script>
	setTimeout(function(){
		jQuery('.schedule__item').each(function(){
			var number = jQuery(this).find('.schedule__item__frendlist').find('a').length;
			if(number <=4)
			{
				jQuery(this).find('.schedule__item__frendlist').css('display','flex');
			}
			else
			{
				jQuery(this).find('.schedule__item__frendlist').css('display','block');
				jQuery(this).find('img').css('display','inline-block');
				jQuery(this).find('.schedule__item__frendlist').css('margin-top','5px');
			}
		});

		$('.owl-carousel').owlCarousel({
			loop:true,
			margin:10,
			nav:true,
			dots:false,
			responsive:{
				0:{
					items:1
				},
				600:{
					items:3
				},
				1000:{
					items:5
				}
			}
		});
	},2000);
	var page_title = '<?php echo $page_title;  ?>';
	if(page_title == 'schedule')
	{
		$('.header').hide();
	}

	// $('.right-arrow2').on('click',function()
	// {
	// 	var current_step = $('.step.active').attr('data-step');
	// 	jQuery('.step').removeClass('active').addClass('hidden');
	// 	if(current_step == 'Mondays')
	// 	{
	// 		$('.step-Tuesdays').addClass('active').removeClass('hidden');
	// 	}
	// 	else if(current_step == 'Tuesdays')
	// 	{
	// 		$('.step-Wednesdays').addClass('active').removeClass('hidden');
	// 	}
	// 	else if(current_step == 'Wednesdays')
	// 	{
	// 		$('.step-Thursdays').addClass('active').removeClass('hidden');
	// 	}
	// 	else if(current_step == 'Thursdays')
	// 	{
	// 		$('.step-Fridays').addClass('active').removeClass('hidden');
	// 	}
	// 	else if(current_step == 'Fridays')
	// 	{
	// 		$('.step-Saturdays').addClass('active').removeClass('hidden');
	// 	}
	// 	else if(current_step == 'Saturdays')
	// 	{
	// 		$('.step-Sundays').addClass('active').removeClass('hidden');
	// 	}
	// 	else if(current_step == 'Sundays')
	// 	{
	// 		$('.step-Mondays').addClass('active').removeClass('hidden');
	// 	}
	// });
	// $('.left-arrow2').on('click',function()
	// {
	// 	var current_step = $('.step.active').attr('data-step');
	// 	console.log(current_step);
	// 	jQuery('.step').removeClass('active').addClass('hidden');
	// 	if(current_step == 'Mondays')
	// 	{
	// 		$('.step-Sundays').addClass('active').removeClass('hidden');
	// 	}
	// 	else if(current_step == 'Tuesdays')
	// 	{
	// 		$('.step-Mondays').addClass('active').removeClass('hidden');
	// 	}
	// 	else if(current_step == 'Wednesdays')
	// 	{
	// 		$('.step-Tuesdays').addClass('active').removeClass('hidden');
	// 	}
	// 	else if(current_step == 'Thursdays')
	// 	{
	// 		$('.step-Wednesdays').addClass('active').removeClass('hidden');
	// 	}
	// 	else if(current_step == 'Fridays')
	// 	{
	// 		$('.step-Thursdays').addClass('active').removeClass('hidden');
	// 	}
	// 	else if(current_step == 'Saturdays')
	// 	{
	// 		$('.step-Fridays').addClass('active').removeClass('hidden');
	// 	}
	// 	else if(current_step == 'Sundays')
	// 	{
	// 		$('.step-Saturdays').addClass('active').removeClass('hidden');
	// 	}
	// });
</script>

@endpush