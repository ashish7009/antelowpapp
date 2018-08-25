@extends('layouts.front')

@section('pagetitle', 'Influencer')

@section('content')
<div class="container user-list" style="display: none;">
	<h2 class="text-center">Influencer</h2> 
	<form id="selectedUserForm" method="post" accept-charset="utf-8">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th>Fullname</th>
					<th>ID</th>
				</tr>
			</thead>
			<tbody id="userlist">
				
			</tbody>
			<tfoot>
				<tr id="successMsg" style="display: none;">
					<td colspan="3"><div id="feedback"></div></td>
				</tr>
				<tr>
					<td colspan="3"><input type="submit" name="submit" value="Submit" class="btn btn-success pull-right"></td>
				</tr>
			</tfoot>
		</table>
	</form>  
</div>

<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" class="text-center">Login</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
					</div>
					<div class="form-group">
						<div class="pull-right error-msg" style="color:red; display: none;">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success" id="submit">Submit</button>
				</div>
			</div>
	</div>
</div>

@endsection

@push('footer_script')
<script>
	getUserlist();
	function getUserlist()
	{
		jQuery.ajax({
			url : '/influencer/get_user_list',
			type:'get',
			data : {},
			dataType :'json',
			success:function(res)
			{
				arr= [];
				userarry = [];
				jQuery.each(res,function(key,value){
					if(value['influencer'] == 1)
					{
						id= value['userid'];
						userarry.push(id);
					}

					tr = '<tr>'
					+'<td><input type="checkbox" class="ids" name="i_id" id="user-'+value['userid']+'" data-id="'+value['userid']+'" ></td>'
					+'<td>'+value['firstname']+'</td>'
					+'<td>'+value['userid']+'</td>'
					+'</tr>';
					arr.push(tr);
				});
				jQuery('#userlist').html(arr);
				if(userarry != '')
				{
					jQuery.each(userarry,function(key,value){
						jQuery('#user-'+value).prop('checked',true);
					});
				}

			}
		});
	}
	jQuery(window).load(function()
	{
		jQuery('#myModal').modal({backdrop: 'static', keyboard: false});
		jQuery('#myModal').modal('show');
	});

	jQuery('#submit').on('click',function(e)
	{
		$(this).attr('disabled',true);
		password = $('#password').val();
		if('barca4life' === password)
		{
			jQuery('.user-list').removeAttr('style');
			jQuery('#myModal').modal('hide');
			$('#submit').attr('disabled',false);
		}
		else{
			jQuery('.error-msg').show().html("Password is invalid!!");
			$('#submit').attr('disabled',false);
		}
	});

	jQuery('#selectedUserForm').on('submit',function(e)
	{
		e.preventDefault();
		myarray = [];
		jQuery.each(jQuery('.ids:checked'),function(key,value)
		{
			tagid = $(value).attr('data-id');
			myarray.push(tagid);
		});

		jQuery.ajax({
			url : '/influencer/selectedUser/'+myarray,
			type : 'post',
			data : {},
			processData : false,
			contentType: false,
			success: function(response)
			{
				console.log(response);
				if(response.indexOf('OK')>=0)
				{
					jQuery('#successMsg').show();
					jQuery('#feedback').removeClass().addClass('alert alert-success').html('Influencer added Successfully');
					setTimeout(function(){
							getUserlist();
							jQuery('#successMsg').hide('blind');
					},3000);
				}
				else
				{
					jQuery('#successMsg').show();
					jQuery('#feedback').removeClass().addClass('alert alert-danger').html('Something went wrong!!');
					setTimeout(function(){
						jQuery('#successMsg').hide('blind');
					},3000);
				}
			}
		});
	});

</script>
@endpush