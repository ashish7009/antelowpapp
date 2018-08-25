@extends('manage.layouts.admin')

@section('pagetitle', ' - Users')

@section('content')
<section class="content-header">
	<h1>Users <small>Control panel</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/manage') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Users</li>
	</ol>
</section>
<section class="content">
	<div class="box box-custom">
		<div class="box-header with-border">
			<h3 class="box-title">List of all the users registered</h3>
			<!-- <div class="pull-right ml10">
	        	<a href="{{ url('/manage/users/add') }}" class="btn btn-primary btn-sm btn-block">Add New</a>
			</div> -->
        	<div class="col-lg-2 col-md-3 col-sm-4 col-xs-5 pull-right pl0 pr0 ml10">
        		{!! Form::open(['url' => 'manage/users/load', 'id' => 'searchform', 'class' => 'input-group input-group-sm']) !!}
					<input title="Search by name, email, age, interests, about" type="text" placeholder="Search" class="form-control pull-right" name="search" id="searchtextbox" data-toggle="tooltip" />
			    	<div class="input-group-btn">
			        	<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
			        </div>
		        {!! Form::close() !!}
			</div>
        	<div class="clearfix"></div>
		</div>
		<div class="box-body table-responsive no-padding">
			<table class="table table-hover listing">
				<thead>
					<tr>
						<th class="field_no text-center">No</th>
						<th colspan="2">User Details</th>
						<th>About</th>
						<th class="field_createdat text-center">Registered On</th>
						<th class="field_createdat text-center">Actions</th>
					</tr>                                                                                 
				</thead>
				<tbody id="tabledata"></tbody>
			</table>
		</div>
		<div class="box-footer clearfix text-right" id="pagingdata" style="display: none;"></div>
	</div>
</section>
@endsection

@section('page-scripts')
<script src="{{ asset('/manage/js/users.js?cache=1') }}"></script>

<div id="influencermodel" class="modal md fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			{!! Form::open(['url' => '/manage/users/influencersubmit', 'id' => 'influencerform']) !!}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title modeltitle" id="modeltitle"></h4>
				</div>
				<div class="modal-body">
					<div>
						<input type="hidden" name="userid" id="userid" />
						<div class="form-group">
							<label class="icheck-label pointer">
								<span class="inline-block mr5">Is Influencer?</span> <input type="checkbox" name="isinfluencer" id="isinfluencer" value="1">
							</label>
						</div>
						<div class="form-group">
							<label for="likeinfluencer" class="control-label">Like Influencer</label>
							<div>
								{!! Form::text('likeinfluencer', null, ['class' => 'form-control', 'id' => 'likeinfluencer']) !!}
							</div>
						</div>
						<div class="form-group m0">
							<label for="followerinfluencer" class="control-label">Follower Influencer</label>
							<div>
								{!! Form::text('followerinfluencer', null, ['class' => 'form-control', 'id' => 'followerinfluencer']) !!}
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection