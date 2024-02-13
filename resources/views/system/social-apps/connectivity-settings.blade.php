{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Connectivity settings'))
@section('content')

<style>
	.bbw{border-bottom-width: thin !important;border-bottom:solid .5px #f9f9f9 !important;padding-bottom:20px;}
	.full_section{margin:0;background: #fff}
	.full_section .card{margin-bottom:0;border-radius: 0;}
	.full_section{border:.5px solid #dee2e6;}
	.full_section .left-sec,.full_section .right-sec{padding-left: 0px; padding-right: 0px;border-right: .5px solid #dee2e6;}
	.full_section .card-b-none{box-shadow: none !important;}
	.right-sec .list-group-item{border:none;padding-left:0;}
</style>

<?php 

	if(array_key_exists(0,$config_data)) {
		$virus_total_api=$config_data[0]->virus_total_api; 
	} else {
		$virus_total_api="";
	}

	if(array_key_exists(0,$config_data)) {
		$google_api_key =$config_data[0]->google_safety_api; 
	} else { 
		$google_api_key ="";
	}

	if(array_key_exists(0,$config_data)) {
		$moz_access_id=$config_data[0]->moz_access_id; 
	} else { 
		$moz_access_id="";
	}

	if(array_key_exists(0,$config_data)) {
		$moz_secret_key=$config_data[0]->moz_secret_key; 
	} else { 
		$moz_secret_key="";
	}

	if(array_key_exists(0,$config_data)) {
		$mobile_ready=$config_data[0]->mobile_ready_api_key; 
	} else { 
		$mobile_ready="";
	}


	if(array_key_exists(0,$config_data)) {
		$bitly_access_token=$config_data[0]->bitly_access_token; 
	} else { 
		$bitly_access_token="";
	}	
	if(array_key_exists(0,$config_data)) {
		$rebrandly_api_key=$config_data[0]->rebrandly_api_key; 
	} else { 
		$rebrandly_api_key="";
	}

	if(array_key_exists(0,$config_data)) {
		$facebook_app_id=$config_data[0]->facebook_app_id; 
	} else { 
		$facebook_app_id="";
	}

	if(array_key_exists(0,$config_data)) {
		$facebook_app_secret=$config_data[0]->facebook_app_secret; 
	} else { 
		$facebook_app_secret="";
	}

	if($is_demo == '1' && Auth::user()->user_type == 'Admin')
	{
		$virus_total_api="XXXXXXXXXXXX";
		$google_api_key ="XXXXXXXXXXXX";
		$moz_access_id="XXXXXXXXXXXX";
		$moz_secret_key="XXXXXXXXXXXX";
		$mobile_ready="XXXXXXXXXXXX";
		$bitly_access_token="XXXXXXXXXXXX";
		$rebrandly_api_key="XXXXXXXXXXXX";
		$facebook_app_id="XXXXXXXXXXXX";
		$facebook_app_secret="XXXXXXXXXXXX";
	}

?>

<section class="section section_custom">
	<div class="section-header">
		<h1><i class="fab fa-connectdevelop"></i> <?php echo __('Connectivity settings'); ?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><?php echo __("System"); ?></div>
			<div class="breadcrumb-item"><a href="{{route('social_apps')}}"><?php echo __("Social Apps"); ?></a></div>
			<div class="breadcrumb-item"><?php echo __('Connectivity settings'); ?></div>
		</div>
	</div>
	
	@include('shared.message')

	<div class="section-body">
		<div class="row">
			<div class="col-12">
				<form class="form-horizontal" enctype="multipart/form-data" action="{{route('connectivity_settings_action')}}" method="POST">
					@csrf
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-12 col-md-6">
						            <div class="form-group">
						             	<label><?php echo __('Google Api key'); ?></label>
				               			<input name="google_safety_api" value="{{$google_api_key ?? ''}}"  class="form-control" type="text">  
										   @if ($errors->has('google_safety_api'))
										   <span class="text-danger"> {{ $errors->first('google_safety_api') }} </span>
										 	@endif
						           	</div> 
								</div>
								<div class="col-12 col-md-6">
						            <div class="form-group">
						             	<label><?php echo __('MOZ Access ID'); ?></label>
				               			<input name="moz_access_id" value="{{$moz_access_id ?? ''}}"  class="form-control" type="text">  
										   @if ($errors->has('moz_access_id'))
										   <span class="text-danger"> {{ $errors->first('moz_access_id') }} </span>
										 	@endif						           	</div> 
								</div>
								<div class="col-12 col-md-6">
						           	<div class="form-group">
						             	<label><?php echo __('MOZ Secret Key'); ?></label>
					               		<input name="moz_secret_key" value="{{$moz_secret_key ?? ''}}"  class="form-control" type="text">
										   @if ($errors->has('moz_secret_key'))
										   <span class="text-danger"> {{ $errors->first('moz_secret_key') }} </span>
										 @endif						           	</div>
								</div>
								<div class="col-12 col-md-6">
						           	<div class="form-group">
						             	<label><?php echo __('VirusTotal Key'); ?></label>
				               			<input name="virus_total_api" value="{{$virus_total_api ?? ''}}"  class="form-control" type="text"> 
										   @if ($errors->has('virus_total_api'))
										   <span class="text-danger"> {{ $errors->first('virus_total_api') }} </span>
										 @endif						           	</div>
								</div>
								<div class="col-12 col-md-6">
						            <div class="form-group">
						             	<label><?php echo __('Bitly Generic Access Token'); ?></label>
					           			<input name="bitly_access_token" value="{{$bitly_access_token ?? ''}}"  class="form-control" type="text">		          
										@if ($errors->has('bitly_access_token'))
											<span class="text-danger"> {{ $errors->first('bitly_access_token') }} </span>
										@endif						           	</div> 
								</div>								
								<div class="col-12 col-md-6">
						            <div class="form-group">
						             	<label><?php echo __('Rebrandly API Key'); ?></label>
					           			<input name="rebrandly_api_key" value="{{$rebrandly_api_key ?? ''}}"  class="form-control" type="text">		          
										   @if ($errors->has('rebrandly_api_key'))
										   <span class="text-danger"> {{ $errors->first('rebrandly_api_key') }} </span>
										 	@endif						           	</div> 
								</div>
							</div>
						</div>

						<div class="card-footer bg-whitesmoke">
	               			<button name="submit" type="submit" class="btn btn-primary btn-lg float-left"><i class="fa fa-save"></i> <?php echo __("Save");?></button>
           					<button type="button" onclick='goBack("social_apps/index",1)' class="btn btn-default btn-lg float-right"><i class="fa fa-close"></i> <?php echo __("Cancel");?></button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>


@endsection