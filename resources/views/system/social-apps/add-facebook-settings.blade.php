{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Facebook app settings'))
@section('content')

<section class="section">
	<div class="section-header">
		   <h1><i class="fab fa-facebook"></i> <?php echo __('Facebook app settings'); ?></h1>
		   <div class="section-header-breadcrumb">
		     <div class="breadcrumb-item"><?php echo __("System"); ?></div>
		     <div class="breadcrumb-item"><a href="{{route('social_apps')}}"><?php echo __("Social Apps"); ?></a></div>
		     <div class="breadcrumb-item"><?php echo __('Facebook app settings'); ?></div>
		   </div>
	</div>
	@include('shared.message')

	<div class="row">
        <div class="col-12 col-md-6 col-lg-6">
            <div class="card">
              <div class="card-body">
                  <b><?php echo __("App Domain")."</b> : <span class='text-info'>".get_domain_only(url('/')); ?></span><br/>
                  <b><?php echo __("Site URL")." :</b> <span class='text-info'>".url('/'); ?></span><br/><br>
                  <b><?php echo __("Privacy Policy URL")." :</b> <span class='text-info'>".url('/home/privacy_policy');?></span><br/>
                  <b><?php echo __("Terms of Service URL")." :</b> <span class='text-info'>".url('/home/terms_use');?></span><br/>
              </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-6">
            <div class="card">

              <div class="card-body" style="min-height: 145px;">
                  
                  <b><?php echo __("Valid OAuth Redirect URI")." </b>: <br><br><span class='text-info'>".url("home/fb_login_back"); ?></span><br>
                  
              </div>
            </div>
        </div>
    </div>
	
	@include('shared.message')

	<div class="section-body">
	  <div class="row">
	    <div class="col-12">
	        <form action="{{route("facebook_settings_update_action")}}" method="POST">
        	@csrf
	        <div class="card">
	          <div class="card-header"><h4 class="card-title"><i class="fas fa-info-circle"></i> <?php echo __("App Details"); ?></h4></div>
	          <div class="card-body">              
	              <div class="form-group">
	                  <label for=""><i class="fas fa-file-signature"></i> <?php echo __("App Name");?> </label>
	                  <input name="app_name" value="{{$app_data->app_name ?? ''}}"  class="form-control" type="text">              
					  @if ($errors->has('app_name'))
					  	<span class="text-danger"> {{ $errors->first('app_name') }} </span>
				  	  @endif
	              </div>

	              <div class="row">
		                <div class="col-12 col-md-6">
		                  <div class="form-group">
		                    <label for=""><i class="far fa-id-card"></i>  <?php echo __("App ID");?></label>
		                    <input name="api_id" value="{{$app_data->app_id ?? ''}}" class="form-control" type="text">  
							@if ($errors->has('api_id'))
                            	<span class="text-danger"> {{ $errors->first('api_id') }} </span>
                        	@endif
		                  </div>
		                </div>

		                <div class="col-12 col-md-6">
		                  <div class="form-group">
		                    <label for=""><i class="fas fa-key"></i>  <?php echo __("App Secret");?></label>
		                    <input name="api_secret" value="{{$app_data->app_secret ?? ''}}" class="form-control" type="text">  
							@if ($errors->has('api_secret'))
                            	<span class="text-danger"> {{ $errors->first('api_secret') }} </span>
                        	@endif
		                  </div>
		                </div>
	              </div>

	              <div class="form-group">
					<?php	
					$status =isset($app_data->status) ? $app_data->status:"";
					if ($status == '') $status = '1';
					?>
		        	  <label class="custom-switch mt-2">
		        	    <input type="checkbox" name="status" value="1" class="custom-switch-input"  @if($status=='1') {{ 'checked' }} @else {{ '' }} @endif>
		        	    <span class="custom-switch-indicator"></span>
		        	    <span class="custom-switch-description"><?php echo __('Active');?></span>
		        	    @if ($errors->has('status'))
                            <span class="text-danger"> {{ $errors->first('status') }} </span>
                        @endif
		        	  </label>
		          </div>
	          </div>

	          <div class="card-footer bg-whitesmoke">
	            <button class="btn btn-primary btn-lg" id="save-btn" type="submit"><i class="fas fa-save"></i> <?php echo __("Save");?></button>
	            <button class="btn btn-secondary btn-lg float-right" onclick='goBack("social_apps/index")' type="button"><i class="fa fa-remove"></i>  <?php echo __("Cancel");?></button>
	          </div>
	        </div>
	      </form>
	    </div>
	  </div>
	</div>
	   				

</section>


@endsection