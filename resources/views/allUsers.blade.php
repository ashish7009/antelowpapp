@extends('layouts.front')

@section('pagetitle', 'Find People')

@section('content')

<style>
.content-section {
	background-color: #f6f6f6;
}

</style>
<div class="content-container pb0 mobil-feed">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-3 col-md-6 col-sm-offset-2 col-sm-8 col-xs-offset-1 col-xs-10 feature-box relative">
				<div class="wow fadeInDown row">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 p-15-tab edit-info-section">
							<div class="col-xs-4 col-sm-4 col-md-4 text-center">
								<a href="{{ secure_url('user/hashtag') }}" class="back-to-page fadeInDown">
									<img src="{{secure_asset('/images/ic_back@2X.png')}}">
								</a>
							</div>
							<div class="col-xs-4 col-sm-4 col-md-4">
								<h4 class="wow fadeInDown no-padding no-margin" style="visibility: visible; animation-name: fadeInDown;">Find People</h4>
							</div>
							<div class="col-xs-4 col-sm-4 col-md-4 text-right">
								<a href="javascript:void(0)" class="edit-info fadeInDown">
									<!-- <img src="/images/ic_edit@2X.png"> -->
								</a>
							</div>
						</div>
					
					</div>
					
					<div class="clearfix"></div>
					<input type="hidden" id="page" value="0" />
					<input type="hidden" id="lastpage" value="-1" />
					<div id="loadUsers"></div>
					<div id="pagingdata"></div>
				</div>				
			</div>	
		</div>
	</div>
</div>
@endsection
@section('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-hammerjs@2.0.0/jquery.hammer.min.js"></script>
<script src="{{ asset('js/hashtag.js?cache=1.1') }}"></script>
@endsection