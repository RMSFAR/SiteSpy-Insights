{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Email template settings'))
@section('content')

<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-id-card"></i> <?php echo __('Email template settings'); ?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><?php echo __("System"); ?></div>
			<div class="breadcrumb-item active"><a href="{{route('settings')}}"><?php echo __("Settings"); ?></a></div>
			<div class="breadcrumb-item"><?php echo __('Email template settings'); ?></div>
		</div>
	</div>
	@include('shared.message')


	<?php $save_button = '<div class="card-footer bg-whitesmoke">
	                      <button class="btn btn-primary btn-lg" id="save-btn" type="submit"><i class="fas fa-save"></i> '.__("Save").'</button>
	                      <button class="btn btn-secondary btn-lg float-right" onclick=\'goBack("settings")\' type="button"><i class="fa fa-remove"></i> '. __("Cancel").'</button>
	                    </div>'; ?>

	<?php $double_email = ["membership_expiration_10_days_before","membership_expiration_1_day_before","membership_expiration_1_day_after","paypal_payment","paypal_new_payment_made","stripe_payment","stripe_new_payment_made"]; ?>


	<form class="form-horizontal text-c" action="{{route('email_template_settings_action')}}" method="POST">
		@csrf
		<div class="section-body">
			<div id="output-status"></div>
			<div class="row">

				<div class="col-md-8">
		    		<?php 
						$i=0; 
		    			foreach ($emailTemplatetabledata as $value)
		    			{
							
		    				// if($this->session->userdata('license_type') != 'double' && in_array($value['template_type'], $double_email))  continue;
		    				
		    				$temp_fildset =  strtolower(str_replace([' ','_'],'-', $value->title)); ?>

							<div class="card" id="<?php echo $temp_fildset; ?>">

								<div class="card-header">
									<h4>
										<i class="<?php echo $value->icon ; ?>"></i> <?php echo __($value->title ); ?>
										<a data-html='true' data-toggle="popover" data-placement="bottom" title="<?php echo __($value->info );?>" data-content="<b><u><?php echo __('Variable List').' : </b></u><br>'.str_replace(',', '<br>', $value->tooltip ); ?>"><i class="fa fa-info-circle"></i></a>
									</h4>
								</div>
								<div class="card-body">


		       			           	<div class="form-group">
		       			             	<label for="<?php echo $value->template_type .'-subject'; ?>" ><i class="fa fa-bars"></i> <?php echo __('Subject');?> 
		       			             	</label>

		       	               			<input name="<?php echo $value->template_type.'-subject'; ?>" value='<?php if($value->subject !='') echo $value->subject ; else echo $default_values[$i]['subject'] ; ?>' class="form-control" type="text" id="<?php echo $value->template_type .'-subject'; ?>">			          
											  @if ($errors->has($value->template_type.'-subject'))
											  <span class="text-danger">{{ $errors->first($value->template_type.'-subject') }}</span>
											  @endif
		       			            </div>

		       			            <div class="form-group">
		       			             	<label for="<?php echo $value->template_type.'-message' ?>"><i class="fa fa-envelope"></i> <?php echo __("Message");?> 
		       			             	</label>

		       	               			<textarea name="<?php echo $value->template_type .'-message' ?>" id="<?php echo $value->template_type .'-message' ?>" class="codeeditor"><?php if($value->message !='') echo $value->message ; else echo $default_values[$i]['message'] ; ?></textarea>          
											  @if ($errors->has($value->template_type.'-message'))
											  <span class="text-danger">{{ $errors->first($value->template_type.'-message') }}</span>
											  @endif
		       			            </div>	
		       			            <a href="<?php echo url('/')."admin/delete_email_template/".$value->template_type ; ?>" class="float-right"><i class="fa fa-refresh"></i>  <?php echo __("Restore To Default");?></a>

			                		
								</div>
								<?php echo $save_button; ?>
							</div>
		    			<?php
		    			$i++;
						}; 
					?>
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
							    		<?php 
		    							foreach ($emailTemplatetabledata as $value)
						    			{
						    				// if($this->session->userdata('license_type') != 'double' && in_array($value->template_type , $double_email))  continue;
						    				$temp_fildset =  strtolower(str_replace([' ','_'],'-', $value->title));
											$fieldset = ucwords(str_replace('_',' ',$value->template_type));	
											?>

											<li class="nav-item">
												<a href="#<?php echo $temp_fildset; ?>" class="nav-link">
													<i class="<?php echo $value->icon; ?>"></i> <?php echo __($value->title); ?>
												</a>
											</li>										
						    			<?php 
						    			} ?>							
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


<script>
	$('[data-toggle="popover"]').popover();
	$('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});
</script>


<script type="text/javascript">
  $('document').ready(function(){
    $(".settings_menu a").click(function(){
    	$(".settings_menu a").removeClass("active");
    	$(this).addClass("active");
    });
  });
</script>

@endsection