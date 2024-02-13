{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('General settings'))
@section('content')

<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-toolbox"></i> <?php echo __('General settings'); ?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><?php echo __("System"); ?></div>
			<div class="breadcrumb-item active"><a href="{{route('settings')}}"><?php echo __("Settings"); ?></a></div>
			<div class="breadcrumb-item"><?php echo __('General settings'); ?></div>
		</div>
	</div>
	@include('shared.message')

	<?php $save_button = '<div class="card-footer bg-whitesmoke">
	                      <button class="btn btn-primary btn-lg" id="save-btn" type="submit"><i class="fas fa-save"></i> '.__("Save").'</button>
	                      <button class="btn btn-secondary btn-lg float-right" onclick=\'goBack("admin/settings")\' type="button"><i class="fa fa-remove"></i> '. __("Cancel").'</button>
	                    </div>'; ?>
	
	<form class="form-horizontal text-c" enctype="multipart/form-data" action="{{route('general_settings_action')}}" method="POST">	
		@csrf
		<div class="section-body">
			<div id="output-status"></div>
			<div class="row">
				<div class="col-md-8">					
					<div class="card" id="brand">

						<div class="card-header">
							<h4><i class="fas fa-flag"></i> <?php echo __("Brand"); ?></h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for=""><i class="fa fa-globe"></i> <?php echo __("Application Name");?> </label>
										<input name="product_name" value="{{config('my_config.product_name') ?? old('product_name')}}"  class="form-control" type="text">		          
										@if ($errors->has('product_name'))
										<span class="text-danger">{{ $errors->first('product_name') }}</span>
										@endif
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for=""><i class="fa fa-compress"></i> <?php echo __("Application Short Name");?> </label>
										<input name="product_short_name" value="{{config('my_config.product_short_name') ?? old('product_short_name')}}"  class="form-control" type="text">
										@if ($errors->has('product_short_name'))
										<span class="text-danger">{{ $errors->first('product_short_name') }}</span>
										@endif
									</div>
								</div>
							</div>

							<div class="form-group">
								<label for=""><i class="fas fa-tag"></i> <?php echo __("Slogan");?> </label>
								<input name="slogan" value="{{config('my_config.slogan') ?? old('slogan')}}"  class="form-control" type="text">
								@if ($errors->has('slogan'))
								<span class="text-danger">{{ $errors->first('slogan') }}</span>
								@endif
							</div>

							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for=""><i class="fa fa-briefcase"></i> <?php echo __("Company Name");?></label>
										<input name="institute_name" value="{{config('my_config.institute_name') ?? old('institute_name')}}"  class="form-control" type="text">	
										@if ($errors->has('institute_name'))
										<span class="text-danger">{{ $errors->first('institute_name') }}</span>
										@endif
									</div>
								</div>

								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for=""><i class="fa fa-map-marker"></i> <?php echo __("Company Address");?></label>
										<input name="institute_address" value="{{config('my_config.institute_address') ?? old('institute_address')}}"  class="form-control" type="text">
										@if ($errors->has('institute_address'))
										<span class="text-danger">{{ $errors->first('institute_address') }}</span>
										@endif
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for=""><i class="fa fa-envelope"></i> <?php echo __("Company Email");?> *</label>
										<input name="institute_email" value="{{config('my_config.institute_email') ?? old('institute_email')}}"  class="form-control" type="email">
										@if ($errors->has('institute_email'))
										<span class="text-danger">{{ $errors->first('institute_email') }}</span>
										@endif
									</div>  
								</div>

								<div class="col-12 col-md-6">	
									<div class="form-group">
										<label for=""><i class="fa fa-mobile"></i> <?php echo __("Company Phone");?></label>
										<input name="institute_mobile" value="{{config('my_config.institute_mobile') ?? old('institute_mobile')}}"  class="form-control" type="text">
										@if ($errors->has('institute_mobile'))
										<span class="text-danger">{{ $errors->first('institute_mobile') }}</span>
										@endif
									</div>
								</div>
							</div>
						</div>
						<?php echo $save_button; ?>
					</div>

					<div class="card" id="preference">
						<div class="card-header">
							<h4><i class="fas fa-tasks"></i> <?php echo __("Preference"); ?></h4>
						</div>
						<div class="card-body">

				            <div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
						             	<label for=""><i class="fa fa-language"></i> <?php echo __("Language");?></label>            			
										@php 
											$default_language = config("my_config.language") ?? 'en';
											if(empty($default_language)) $default_language = config('app.language');
											echo Form::select('language',$language_list_new,$default_language,array('class'=>'form-control select2','id'=>'language'));
									 	@endphp	          
				             			
						            </div>
									@if ($errors->has('language'))
									<span class="text-danger">{{ $errors->first('language') }}</span>
									@endif
						        </div>

						        <div class="col-12 col-md-6">
						            <div class="form-group">
						             	<label for=""><i class="fa fa-clock-o"></i> <?php echo __("Time Zone");?></label>          			
										@php
											$selected = config("my_config.time_zone") ?? "";
											if(empty($selected)) $selected = config('app.timezone');
											$timezone_list = get_timezone_list();
											echo Form::select('time_zone',$timezone_list,$selected,array('class'=>'form-control select2','id'=>'time_zone'));
									 	@endphp		          
				             			@if ($errors->has('time_zone'))
										  <span class="text-danger">{{ $errors->first('time_zone') }}</span>
										@endif
						            </div>
						        </div>
					        </div>						
						   

				            <div class="form-group">
								@php	
								$email = config('my_config.email_sending_option');
								$email_sending_option =isset($email) ? $email:'';
								if ($email_sending_option == '') $email_sending_option = 'smtp';
								@endphp
				             	<label for="email_sending_option"><i class="fa fa-at"></i> <?php echo __('Email Sending Option');?></label> 
								<div class="row">
									<div class="col-12 col-md-6">
										<label class="custom-switch">
										  <input type="radio" name="email_sending_option" value="php_mail" class="custom-switch-input" @if($email_sending_option=='php_mail') {{ 'checked' }} @else {{ '' }} @endif>
										  <span class="custom-switch-indicator"></span>
										  <span class="custom-switch-description"><?php echo __('Use PHP Email Function'); ?></span>
										</label>
									</div>
									<div class="col-12 col-md-6">
										<label class="custom-switch">
										  <input type="radio" name="email_sending_option" value="smtp" class="custom-switch-input" @if($email_sending_option=='smtp') {{ 'checked' }} @else {{ '' }} @endif>
										  <span class="custom-switch-indicator"></span>
										  <span class="custom-switch-description"><?php echo __('Use SMTP Email'); ?>
										  	&nbsp;:&nbsp;<a href="{{route('smtp_settings')}}" class="float-right"> <?php echo __("SMTP Setting"); ?> </a></span>
										</label>
									</div>
								</div>
								@if ($errors->has('email_sending_option'))
								<span class="text-danger">{{ $errors->first('email_sending_option') }}</span>
								@endif
				            </div>

   						    <div class="row">
   						        <div class="col-12 col-md-6">
   						        	<div class="form-group">
										@php	
										$force = config('my_config.force_https');
										$force_https =isset($force) ? $force:'';
										if ($force_https == '') $force_https = '1';
										@endphp
   						        	  <label class="custom-switch mt-2">
   						        	    <input type="checkbox" name="force_https" value="1" class="custom-switch-input"  @if($force_https=='1') {{ 'checked' }} @else {{ '' }} @endif>
   						        	    <span class="custom-switch-indicator"></span>
   						        	    <span class="custom-switch-description"><?php echo __('Force HTTPS');?>?</span>
										   @if ($errors->has('force_https'))
										   <span class="text-danger">{{ $errors->first('force_https') }}</span>
										   @endif
   						        	  </label>
   						        	</div>
   						        </div>

   					           	<div class="col-12 col-md-6">
   					           		<div class="form-group">
										@php	
										$display = config('my_config.enable_signup_form');
										$enable_signup_form =isset($display) ? $display:'';
										if ($enable_signup_form == '') $enable_signup_form = '1';
										@endphp
   					           		  <label class="custom-switch mt-2">
   					           		    <input type="checkbox" name="enable_signup_form" value="1" class="custom-switch-input"  @if($enable_signup_form=='1') {{ 'checked' }} @else {{ '' }} @endif>
   					           		    <span class="custom-switch-indicator"></span>
   					           		    <span class="custom-switch-description"><?php echo __('Display Signup Page');?></span>
											  @if ($errors->has('enable_signup_form'))
											  <span class="text-danger">{{ $errors->first('enable_signup_form') }}</span>
											  @endif
   					           		  </label>
   					           		</div>        				           	
   					            </div>
   					        </div>

   					        <div class="row">
   						        <div class="col-12 col-md-6">
   						        	<div class="form-group">
										@php	
										$d = config('my_config.use_admin_app');
										$use_admin_app =isset($d) ? $d:'';
										if ($use_admin_app == '') $use_admin_app = 'yes';
										@endphp
   						        	  <label class="custom-switch mt-2">
   						        	    <input type="checkbox" name="use_admin_app" value="yes" class="custom-switch-input"  @if($use_admin_app=='yes') {{ 'checked' }} @else {{ '' }} @endif>
   						        	    <span class="custom-switch-indicator"></span>
   						        	    <span class="custom-switch-description"><?php echo __("Give admin's API access to users.");?></span>
										   @if ($errors->has('use_admin_app'))
										   <span class="text-danger">{{ $errors->first('use_admin_app') }}</span>
										   @endif
   						        	  </label>
   						        	</div>
   						        </div>
   					        </div>

						</div>
						<?php echo $save_button; ?>
					</div>

					<div class="card" id="logo-favicon">
						<div class="card-header">
							<h4><i class="fas fa-images"></i> <?php echo __("Logo & Favicon"); ?></h4>
						</div>
						<div class="card-body">			             	

			             	<div class="row">
			             		<div class="col-6">
 					             	<label for=""><i class="fas fa-image"></i> <?php echo __("logo");?> (png)</label>
 					             	<div class="custom-file">
 			                            <input type="file" name="logo" class="custom-file-input">
 			                            <label class="custom-file-label"><?php echo __("Choose File"); ?></label>
										 <?php $logo  =  config('my_config.logo') ?? asset('assets/img/logo.png');?>
 			                            <small><?php echo __("Max Dimension");?> : 700 x 200, <?php echo __("Max Size");?> : 500KB </small>	          
										 @if ($errors->has('logo'))
										 <span class="text-danger">{{ $errors->first('logo') }}</span>
										 @endif
 			                         </div>
			             		</div>
			             		<div class="col-6 my-auto text-center">
			             			<img class="img-fluid" src="{{ config('my_config.logo') }}" alt="Logo"/>
			             		</div>
			             	</div>

			             	<div class="row">
			             		<div class="col-6">
 					             	<label for=""><i class="fas fa-portrait"></i> <?php echo __("Favicon");?> (png)</label>
									  <?php $favicon  = config('my_config.favicon') ?? asset('assets/img/favicon.png'); ?>
 					             	<div class="custom-file">
 			                            <input type="file" name="favicon" class="custom-file-input">
 			                            <label class="custom-file-label"><?php echo __("Choose File"); ?></label>
 			                            <small><?php echo __("Dimension");?> : 100 x 100, <?php echo __("Max Size");?> : 50KB </small>	          
										 @if ($errors->has('favicon'))
										 <span class="text-danger">{{ $errors->first('favicon') }}</span>
										 @endif
 			                         </div>
			             		</div>
			             		<div class="col-6 my-auto text-center">
			             			<img class="img-fluid" src="{{ config('my_config.favicon') }}" alt="Favicon" style="max-width:50px;"/>
			             		</div>
			             	</div>
						</div>
						<?php echo $save_button; ?>
					</div>

					<div class="card" id="master-password">
						<div class="card-header">
							<h4><i class="fab fa-keycdn"></i> <?php echo __("Master Password"); ?></h4>
						</div>
						<div class="card-body">
				           <div class="form-group">
				             	<label for=""><i class="fa fa-key"></i> <?php echo __("Master Password (will be used for login as user)");?></label>
		               			<input name="master_password" value="{{config('my_config.master_password') ?? '******'}}"  class="form-control" type="text">
								   @if ($errors->has('master_password'))
								   <span class="text-danger">{{ $errors->first('master_password') }}</span>
								   @endif
				           </div>
						   {{-- <div class="row d-none"> --}}
						        {{-- <div class="col-12 col-md-6">
						        	<div class="form-group">
						        	  <label class="custom-switch mt-2">
						        	    <input type="checkbox" name="backup_mode" value="1" class="custom-switch-input"  @if(config('my_config.backup_mode')=='1') {{ 'checked' }} @else {{ '' }} @endif>
						        	    <span class="custom-switch-indicator"></span>
						        	    <span class="custom-switch-description"><?php echo __('Give access to user to set their own Facebook APP');?>?</span>
										@if ($errors->has('backup_mode'))
										<span class="text-danger">{{ $errors->first('backup_mode') }}</span>
										@endif
						        	  </label>
						        	</div>
						        </div>

					           	 <div class="col-12 col-md-6">
					           		<div class="form-group">
					           		  <label class="custom-switch mt-2">
					           		    <input type="checkbox" name="developer_access" value="1" class="custom-switch-input"  @if(config('my_config.developer_access')=='1') {{ 'checked' }} @else {{ '' }} @endif>
					           		    <span class="custom-switch-indicator"></span>
					           		    <span class="custom-switch-description"><?php echo __('Use Approved Facebook App of Author?');?> </span>
					           		    <a href="#" data-placement="top"  data-html="true" data-toggle="popover" data-trigger="focus" title="<?php echo __("Use Approved Facebook App of Author?") ?>" data-content="<?php echo __("If you select Yes, you may skip to add your own app. You can use Author's app. But this option only for the admin only. This can't be used for other system users. User management feature will be disapeared."); ?><br><br><?php echo __("If select No , you will need to add your own app & get approval and system users can use it.");?>"><i class='fa fa-info-circle'></i> </a>

					           		    <span class="red"><?php echo form_error('developer_access'); ?></span>
					           		    
					           		  </label>
					           		</div>        				           	
					            </div>  --}}
					        {{-- </div> --}}
						</div>
						<?php echo $save_button; ?>
					</div>

				

					{{-- <?php if(session('license_type' == 'double')) { ?> --}}
					<div class="card" id="support-desk">
						<div class="card-header">
							<h4><i class="fas fa-headset"></i> <?php echo __("Support Desk"); ?></h4>
						</div>
						<div class="card-body">
			           		<div class="form-group">
								@php	
								$enable = config('my_config.enable_support');
								$enable_support =isset($enable) ? $enable:'';
								if ($enable_support == '') $enable_support = '1';
								@endphp
			           		  <label class="custom-switch mt-2">
			           		    <input type="checkbox" name="enable_support" value="1" class="custom-switch-input"  @if($enable_support=='1') {{ 'checked' }} @else {{ '' }} @endif>
			           		    <span class="custom-switch-indicator"></span>
			           		    <span class="custom-switch-description"><?php echo __('Enable Support Desk for Users');?></span>
								   @if ($errors->has('enable_support'))
								   <span class="text-danger">{{ $errors->first('enable_support') }}</span>
								   @endif
			           		  </label>
			           		</div>
						</div>
						<?php echo $save_button; ?>
					</div>
					{{-- <?php } ?> --}}

					<div class="card" id="file-upload">
						<div class="card-header">
							<h4><i class="fas fa-cloud-upload-alt"></i> <?php echo __("File Upload"); ?></h4>
						</div>
						<div class="card-body">
			           		<div class="form-group">
			           			<label for=""><i class="fas fa-file"></i> <?php echo __("File Upload Limit (MB)");?></label>
		               			<input name="xeroseo_file_upload_limit" value="{{config('my_config.xeroseo_file_upload_limit') ?? old('xeroseo_file_upload_limit')}}"  class="form-control" type="number" min="1">
								   @if ($errors->has('xeroseo_file_upload_limit'))
								   <span class="text-danger">{{ $errors->first('xeroseo_file_upload_limit') }}</span>
								   @endif	
			           		</div>
						</div>
						<?php echo $save_button; ?>
					</div>	

					<div class="card" id="junk_data">
						<div class="card-header">
							<h4><i class="fas fa-trash-alt"></i> <?php echo __("Junk Data Deletion"); ?></h4>
						</div>
						<div class="card-body">				       
			              <div class="row">
			              		<div class="col-12">
	 				              	<div class="form-group">
	 					             	<label for=""><i class="fa fa-calendar"></i> <?php echo __("Visitor analytics older data, log/cache data after how many days?");?></label>
	 			               			<input name="delete_junk_data_after_how_many_days" value="{{config('my_config.delete_junk_data_after_how_many_days') ?? old('delete_junk_data_after_how_many_days')}}"  class="form-control" type="number" min="1">          
											@if ($errors->has('delete_junk_data_after_how_many_days'))
											<span class="text-danger">{{ $errors->first('delete_junk_data_after_how_many_days') }}</span>
											@endif
	 					            </div>
			              		</div>
			              	</div>
						</div>
						<?php echo $save_button; ?>
					</div>	

					<div class="card" id="server-status">
						<div class="card-header">
							<h4><i class="fas fa-server"></i> <?php echo __("Server Status"); ?></h4>
						</div>
						<div class="card-body">
							<?php

							// $sql="SHOW VARIABLES;";
				            $mysql_variables=DB::select("SHOW VARIABLES");
				            $variables_array_format=array();
				            foreach($mysql_variables as $my_var){
				                $variables_array_format[$my_var->Variable_name]=$my_var->Value;
				            }
				            $disply_index = array("version","innodb_version","innodb_log_file_size","wait_timeout","max_connections","connect_timeout","max_allowed_packet");

							$list1=$list2="";						  
						    $make_dir = (!function_exists('mkdir')) ? __("Disabled"):__("Enabled");
						    $zip_archive = (!class_exists('ZipArchive')) ? __("Disabled"):__("Enabled");
						    $list1 .= "<li class='list-group-item'><b>mkdir</b> : ".$make_dir."</li>"; 
						    $list2 .= "<li class='list-group-item'><b>ZipArchive</b> : ".$zip_archive."</li>"; 

						    if(function_exists('curl_version'))	$curl="Enabled";								    
							else $curl="Disabled";

							if(function_exists('mb_detect_encoding')) $mbstring="Enabled";								    
							else $mbstring="Disabled";

							if(function_exists('set_time_limit')) $set_time_limit="Enabled";								    
							else $set_time_limit="Disabled";

							if(function_exists('exec')) $exec="Enabled";								    
							else $exec="Disabled";

							$list2 .= "<li class='list-group-item'><b>curl</b> : ".$curl."</li>";
						    $list1 .= "<li class='list-group-item'><b>exec</b> : ".$exec."</li>"; 
							$list2 .= "<li class='list-group-item'><b>mb_detect_encoding</b> : ".$mbstring."</li>"; 
							$list2 .= "<li class='list-group-item'><b>set_time_limit</b> : ".$set_time_limit."</li>"; 


						    if(function_exists('ini_get'))
							{								 
								if( ini_get('safe_mode') )
							    $safe_mode="ON, please set safe_mode=off";								    
							    else $safe_mode="OFF";

							    if( ini_get('open_basedir')=="")
							    $open_basedir="No Value";								    
							    else $open_basedir="Has value";

							    if( ini_get('allow_url_fopen'))
							    $allow_url_fopen="TRUE";								    
							    else $allow_url_fopen="FALSE";

							    $list1 .= "<li class='list-group-item'><b>safe_mode</b> : ".$safe_mode."</li>"; 
							    $list2 .= "<li class='list-group-item'><b>open_basedir</b> : ".$open_basedir."</li>"; 
							    $list1 .= "<li class='list-group-item'><b>allow_url_fopen</b> : ".$allow_url_fopen."</li>";	
								$list1 .= "<li class='list-group-item'><b>upload_max_filesize</b> : ".ini_get('upload_max_filesize')."</li>";   
						    	$list1 .= "<li class='list-group-item'><b>max_input_time</b> : ".ini_get('max_input_time')."</li>";
					       		$list2 .= "<li class='list-group-item'><b>post_max_size</b> : ".ini_get('post_max_size')."</li>"; 
						    	$list2 .= "<li class='list-group-item'><b>max_execution_time</b> : ".ini_get('max_execution_time')."</li>";
													    
							}						       

					        $php_version = (function_exists('ini_get') && phpversion()!=FALSE) ? phpversion() : ""; ?>							

						    <div class="row">
							  	<div class="col-12 col-lg-6">								  		
									<ul class="list-group">
										<li class='list-group-item active'>PHP</li>  
							  			<li class='list-group-item'><b>PHP version : </b> <?php echo $php_version; ?></li>   
										<?php echo $list1; ?>
									</ul>
							  	</div>
							  	<div class="col-12 col-lg-6">
							  		<ul class="list-group">
							  			<li class='list-group-item active'>PHP</li>
							  			<?php echo $list2; ?>
									</ul>
							  	</div>
							  	<div class="col-12">
							  		<br>
							  		<ul class="list-group">
							  			<li class='list-group-item active'>MySQL</li>  
							  			
							  			<?php 
							  			foreach ($disply_index as $value) 
							  			{
							  				if(isset($variables_array_format[$value]))
							  				echo "<li class='list-group-item'><b>".$value."</b> : ".$variables_array_format[$value]."</li>";  
							  			} 
							  			?>
									</ul>
							  	</div>

						    </div>
							  	
						</div>
					</div>	
				</div>

				<div class="col-md-4 d-none d-sm-block">
					<div class="sidebar-item">
						<div class="make-me-sticky">
							<div class="card">
								<div class="card-header">
									<h4><i class="fas fa-columns"></i> <?php echo __("Sections"); ?></h4>
								</div>
								<div class="card-body">
									<ul class="nav nav-pills flex-column settings_menu">
										<li class="nav-item"><a href="#brand" class="nav-link"><i class="fas fa-flag"></i> <?php echo __("Brand"); ?></a></li>
										<li class="nav-item"><a href="#preference" class="nav-link"><i class="fas fa-tasks"></i> <?php echo __("Preference"); ?></a></li>
										<li class="nav-item"><a href="#logo-favicon" class="nav-link"><i class="fas fa-images"></i> <?php echo __("Logo & Favicon"); ?></a></li>
										<li class="nav-item"><a href="#master-password" class="nav-link"><i class="fab fa-keycdn"></i> <?php echo __("Master Password"); ?></a></li>
									

										{{-- <?php if($this->session->userdata('license_type') == 'double') { ?> --}}
										<li class="nav-item"><a href="#support-desk" class="nav-link"><i class="fas fa-headset"></i> <?php echo __("Support Desk"); ?></a></li>
										{{-- <?php } ?> --}}
										<li class="nav-item"><a href="#file-upload" class="nav-link"><i class="fas fa-cloud-upload-alt"></i> <?php echo __("File Upload"); ?></a></li>
										<li class="nav-item"><a href="#junk_data" class="nav-link"><i class="fas fa-trash-alt"></i> <?php echo __("Delete Junk Data"); ?></a></li>
										<li class="nav-item"><a href="#server-status" class="nav-link"><i class="fas fa-server"></i> <?php echo __("Server Status"); ?></a></li>								
									</ul>
								</div>						
							</div>
							
						</div>
					</div>
				</div>				
			</div>
		</div>
	</form>
</section>


<script type="text/javascript">
  $('document').ready(function(){
    $(".settings_menu a").click(function(){
    	$(".settings_menu a").removeClass("active");
    	$(this).addClass("active");
    });
  });
</script>
<script>
	$('[data-toggle="popover"]').popover();
	$('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});
</script>

@endsection