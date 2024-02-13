{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Google app settings'))
@section('content')

<section class="section">
	<div class="section-header">
		   <h1><i class="fab fa-google"></i> <?php echo __('Google app settings'); ?></h1>
		   
		   <div class="section-header-breadcrumb">
		     <div class="breadcrumb-item"><?php echo __("System"); ?></div>
		     <div class="breadcrumb-item"><a href="{{route('social_apps')}}"><?php echo __("Social Apps"); ?></a></div>
		     <div class="breadcrumb-item"><?php echo __('Google app settings'); ?></div>
		   </div>
	</div>

	@include('shared.message')



	<div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
              <div class="card-body">
                  <b><?php echo __("Google Auth Redirect URL")."</b> : <span class='text-info'>".url("home/google_login_back"); ?></span><br/>
              </div>
            </div>
        </div>
        
    </div>

	@include('shared.message')


	<div class="section-body">
	  <div class="row">
	    <div class="col-12">
	        <form action="{{route("google_settings_action")}}" method="POST">
				@csrf
	        <div class="card">
	          <div class="card-header"><h4 class="card-title"><i class="fas fa-info-circle"></i> <?php echo __("App Details"); ?></h4></div>
	          <div class="card-body">   
				
		            <div class="row">
			            <div class="col-12 col-md-6">
			            	<div class="form-group">
			            	    <label for=""><i class="fas fa-file-signature"></i> <?php echo __("App Name");?> </label>
			            	    <input name="app_name" value="{{$google_settings->app_name ?? ''}}"  class="form-control" type="text">              
			            	    @if ($errors->has('app_name'))
					  				<span class="text-danger"> {{ $errors->first('app_name') }} </span>
				  	  			@endif
			            	</div>
			            </div>
			            <div class="col-12 col-md-6">
			            	<div class="form-group">
			            	    <label for=""><i class="ion ion-key"></i> <?php echo __("API Key");?> </label>
			            	    <input name="api_key" value="{{$google_settings->api_key ?? ''}}"  class="form-control" type="text">              
								@if ($errors->has('api_key'))
									<span class="text-danger"> {{ $errors->first('api_key') }} </span>
							  	@endif
			            	</div>
			            </div>
			        </div>



	              <div class="row">
		                <div class="col-12 col-md-6">
		                  <div class="form-group">
		                    <label for=""><i class="far fa-id-card"></i>  <?php echo __("Client ID");?></label>
		                    <input name="google_client_id" value="{{$google_settings->google_client_id ?? ''}}" class="form-control" type="text">  
							@if ($errors->has('google_client_id'))
								<span class="text-danger"> {{ $errors->first('google_client_id') }} </span>
						  	@endif		                  </div>
		                </div>

		                <div class="col-12 col-md-6">
		                  <div class="form-group">
		                    <label for=""><i class="fas fa-key"></i>  <?php echo __("Client Secret");?></label>
		                    <input name="google_client_secret" value="{{$google_settings->google_client_secret ?? ''}}" class="form-control" type="text">  
							@if ($errors->has('google_client_secret'))
								<span class="text-danger"> {{ $errors->first('google_client_secret') }} </span>
						  	@endif		                  </div>
		                </div>
	              </div>

	              <div class="form-group">
					<?php	
					$status =isset($google_settings->status)?$google_settings->status:"";
					if ($status == '') $status = '1';
					?>
		        	  <label class="custom-switch mt-2">
		        	    <input type="checkbox" name="status" value="1" class="custom-switch-input"  <?php if($status=='1') echo 'checked'; ?>>
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