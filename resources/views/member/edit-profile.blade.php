{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Profile'))
@section('content')



<?php 
	$name= isset($profile_info[0]->name) ? $profile_info[0]->name : ""; 
	$email= isset($profile_info[0]->email) ? $profile_info[0]->email : ""; 
	$time_zone= isset($profile_info[0]->time_zone) ? $profile_info[0]->time_zone : ""; 
	$user_type= isset($profile_info[0]->user_type) ? $profile_info[0]->user_type : ""; 
	$package_name= isset($profile_info[0]->package_name) ? $profile_info[0]->package_name : ""; 
	$address= isset($profile_info[0]->address) ? $profile_info[0]->address : ""; 
	$logo= isset($profile_info[0]->brand_logo) ? $profile_info[0]->brand_logo : ""; 
	if($logo=="") $logo=file_exists(asset("assets/img/avatar/avatar-3.png")) ? asset("assets/img/avatar/avatar-3.png") : asset("assets/img/avatar/avatar-3.png");
	else $logo=$logo;
?>

<section class="section">
	<div class="section-header">
		<h1><i class="far fa-user"></i> <?php echo __('Profile'); ?></h1>
		<div class="section-header-breadcrumb">
		  <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}"><?php echo __('Dashboard'); ?></a></div>
		  <div class="breadcrumb-item"><?php echo __('Profile'); ?></div>
		</div>
	</div>


	@include('shared.message')
	<div class="section-body">
	  <h2 class="section-title"><?php echo __('Hi'); ?>, <?php echo $name; ?> !</h2>
	  <p class="section-lead">
	    <?php echo __('Change information about yourself on this page.'); ?>
	  </p>

	  <div class="row mt-sm-4">
	    <div class="col-12 col-md-12 col-lg-5">
	      <div class="card profile-widget">
	        <div class="profile-widget-header">
	          <img alt="image" src="<?php echo $logo; ?>" class="rounded-circle profile-widget-picture">
	          <div class="profile-widget-items">
	            <div class="profile-widget-item">
	              <div class="profile-widget-item-label"><?php echo __('Email') ?></div>
	              <div class="profile-widget-item-value" style="font-size: 12px;"><?php echo $email; ?></div>
	            </div>
	            <?php if(Auth::user()->user_type != 'Admin') : ?>
		            <div class="profile-widget-item">
		              <div class="profile-widget-item-label"><?php echo __('Package') ?></div>
		              <div class="profile-widget-item-value" style="font-size: 12px;"><?php echo $package_name; ?></div>
		            </div>
	            <?php endif; ?>
	          </div>
	        </div>
	        <div class="profile-widget-description">
	          <div class="profile-widget-name"><?php echo $name; ?> <div class="text-muted d-inline font-weight-normal"><div class="slash"></div> <?php echo $user_type; ?></div></div>
	          	<?php echo $address; ?></b>.
	        </div>
	        <div class="card-footer text-center">
	          <!-- <div class="font-weight-bold mb-2">Follow Ujang On</div> -->
	          <!-- <a href="#" class="btn btn-social-icon btn-facebook mr-1">
	            <i class="fab fa-facebook-f"></i> 
	          </a> -->
	          <?php if(Auth::user()->user_type == 'Member')
			  { ?>
	      		  <a class="delete_full_access btn btn-outline-danger red pointer btn-sm" title="<?php echo __('Delete Account'); ?>"><i class="fas fa-trash"></i> <?php echo __('Delete Account'); ?></a>
      		  	  <?php 
      		  } ?>
	        </div>
	      </div>
	    </div>
	    <div class="col-12 col-md-12 col-lg-7">
	      <div class="card" style="margin-top: 35px;">
	        <form class="form-horizontal" enctype="multipart/form-data" action="{{route('edit_profile_action')}}" method="POST">
				@csrf
				<div class="card-header">
	            <h4><i class="far fa-edit"></i> <?php echo __('Edit Profile'); ?> </h4>
	          </div>
	          <div class="card-body">
	              <div class="row">
	                <div class="form-group col-md-6 col-12">
	                  	<label class="control-label" for=""><i class="fas fa-monument"></i> <?php echo __("Name");?> *</label>
                      	<div>
                     		<input name="name" value="<?php echo $name;?>"  class="form-control" type="text">		               
							 @if ($errors->has('name'))
							 <span class="text-danger">{{ $errors->first('name') }}</span>
							 @endif                   		</div>
	                </div>
	                <div class="form-group col-md-6 col-12">
	                  	<label class="control-label" for=""><i class="far fa-envelope-open"></i> <?php echo __("Email");?> *</label>
                   		<div>
                     		<input name="email" value="<?php echo $email;?>"  class="form-control" type="email">		          
							 @if ($errors->has('email'))
							 <span class="text-danger">{{ $errors->first('email') }}</span>
							 @endif                   		</div>
	                </div>
	              </div>
	              <div class="row">
	                <div class="form-group col-md-6 col-12">
	                  	<label class="control-label" for=""><i class="fas fa-key"></i> <?php echo __("Password");?></label>
                      	<div>
                     		<input name="password" id="password" placeholder="******"  class="form-control" type="password">		               
							 @if ($errors->has('password'))
							 <span class="text-danger">{{ $errors->first('password') }}</span>
							 @endif                   		
						</div>
	                </div>
	                <div class="form-group col-md-6 col-12">
	                  	<label class="control-label" for=""><i class="fas fa-key"></i> <?php echo __("Confirm Password");?> </label>
                   		<div>
                     		<input name="password_confirmation" id="password_confirmation" placeholder="******"  class="form-control" type="password">		          
							 @if ($errors->has('password_confirmation'))
							 <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
							 @endif                   		
						</div>
	                </div>
	              </div>
	              <div class="row">
	                <div class="form-group col-12">
	                  	<label class="control-label" for=""><i class="fas fa-map-marked-alt"></i> <?php echo __("Address");?></label>
                   		<div>
                     		<textarea name="address" class="form-control"><?php echo $address;?></textarea>	          
							 @if ($errors->has('address'))
							 <span class="text-danger">{{ $errors->first('address') }}</span>
							 @endif
                   		</div>
	                </div>
	              </div>
	              <div class="row">
	                <div class="form-group col-12 col-md-6">
	                	<label for=""><i class="fas fa-image"></i> <?php echo __("image");?> (png)</label>
						<div class="custom-file">
                            <input name="logo" class="custom-file-input" type="file">
                            <label class="custom-file-label">Choose File</label>
                            <small>
                            	<?php echo __("Max Dimension");?> : 300 x 300, <?php echo __("Max Size");?> : 200KB</small>	          
								@if ($errors->has('logo'))
								<span class="text-danger">{{ $errors->first('logo') }}</span>
								@endif
                        </div>
	                </div>
	                <div class="form-group col-md-6 col-12">
	                  	<label class="control-label" for=""><i class="fas fa-user-clock"></i> <?php echo __("Time Zone");?></label>
                   		<div>
                   			<select name="time_zone" id="time_zone" class="form-control select2">
                   				<?php 
                   					$time_zone_list[''] = __('Please select Time Zone');
                   					foreach($time_zone_list as $key=>$value) : 
                   				?>
                   					<option value="<?php echo $key; ?>" <?php if($key==$time_zone) echo "selected"; ?> ><?php echo $value; ?></option>
                   				<?php endforeach; ?>
                   			</select>
                   		</div>
	                </div>
	              </div>
	          </div>
	          <div class="card-footer">
	          		<button class="btn btn-primary btn-lg" id="save-btn" type="submit"><i class="fas fa-save"></i> <?php echo __("Save");?></button>
	          </div>
	        </form>
	      </div>
	    </div>
	  </div>
	</div>
</section>

@php $user_id= Auth::user()->id; @endphp



<script>
	"use strict"
	var user_id = {{$user_id}};
	var asset_url = '{{ asset("assets/pre-loader/color/Preloader_9.gif")}}';

</script>

<script type="text/javascript">


  $(document).ready(function(){
	  $(document).on('click','.delete_full_access',function(){
	    $("#delete_dialog").modal(); 
	  }); 

	  $(document).on('click','.cancel_button',function(){
	  	$("#delete_dialog").modal('hide');
	  });

	  $(document).on('click','.delete_confirm',function(){
	  	$("#message_div").attr("class","text-center").html('<img class="center-block" src="'+asset_url+'" height="30" width="30" alt="Processing..."><br/>');
	    $("#delete_dialog").modal(); 
	    var csrf_token = $(this).attr('csrf_token');
	    $.ajax({
	      type:'POST',
	      dataType: 'JSON',
	      url:"members/user_delete_action"+"/"+user_id,
	      data:{csrf_token:csrf_token},
	      success:function(response){ 
	      	if(response.status == 1)
	      	{
	      		$("#delete_dialog").modal('hide');
	      		swal(global_lang_success, response.message, 'success').then((value) => {
         			  location.reload();
					});
	      	}
	      	else
		    {
		    	$('#message_div').attr("class","alert alert-danger text-center").css("margin-top","20px").html(response.message);
		    	$(".modal-footer").hide();
		    }
	      }
	    });
	  });

	  $('#delete_dialog').on('hidden.bs.modal', function () { 
		 location.reload(); 
	 });
  });



</script>

<div class="modal fade" id="delete_dialog" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-warning"></i> <?php echo __("Delete Account?");?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
            	<div id="message_div">
            		<div class="text-center"><i style="font-size: 70px" class="fa fa-warning center-block orange"></i></div><br>
                	<h6><?php echo __("Deleting your account will delete all your data from our system and this account can not be restored again. Do you really want to delete your account?"); ?></h6>
             	</div>
             </div>

            <div class="modal-footer" style="display: block">
            	<button   class="btn btn-danger delete_confirm btn-lg float-left"><i class="fas fa-trash"></i> <?php echo __('Delete My Account'); ?></button>
            	<button class="btn btn-light cancel_button btn-lg float-right"><i class="fa fa-remove"></i> <?php echo __('Cancel'); ?></button>
            </div>

        </div>
    </div>
</div>

@endsection



