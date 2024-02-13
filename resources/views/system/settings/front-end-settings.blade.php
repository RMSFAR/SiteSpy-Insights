{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Front-end settings'))
@section('content')

<style>
	.bg-white{background-color: #4D4D4D !important;}
	.bg-dark{background-color: #000000 !important;}
	.bg-blue{background-color: #1193D4 !important;}
	.bg-green{background-color: #00A65A !important;}
	.bg-purple{background-color: #545096 !important;}
	.bg-red{background-color: #E55053 !important;}
	.bg-yellow{background-color: #F39C12 !important;}

</style>


<section class="section">
	<div class="section-header">
		<h1><i class="fa fa-toolbox"></i> <?php echo __('Front-end settings'); ?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><?php echo __("System"); ?></div>
			<div class="breadcrumb-item active"><a href="{{route('settings')}}"><?php echo __("Settings"); ?></a></div>
			<div class="breadcrumb-item"><?php echo __('Front-end settings'); ?></div>
		</div>
	</div>

	@include('shared.message')


	<?php $save_button = '<div class="card-footer bg-whitesmoke">
	                      <button class="btn btn-primary btn-lg" id="save-btn" type="submit"><i class="fas fa-save"></i> '.__("Save").'</button>
	                      <button class="btn btn-secondary btn-lg float-right" onclick=\'goBack("admin/settings")\' type="button"><i class="fa fa-remove"></i> '. __("Cancel").'</button>
	                    </div>'; ?>


	<form class="form-horizontal text-c" enctype="multipart/form-data" action="{{route('frontend_settings_action')}}" method="POST">	
		@csrf
		<div class="section-body">
			<div id="output-status"></div>
			<div class="row">

				<div class="col-md-8">
					<div class="card" id="general-settings">

						<div class="card-header">
							<h4><i class="fas fa-wrench"></i> <?php echo __("General Settings"); ?></h4>
						</div>
						<div class="card-body">
							<div class="row">
					            <div class="col-12 col-md-6">
						            <div class="form-group">
					           		  <?php	
				               			$display_landing_page = config('frontend.display_landing_page');
				               			if($display_landing_page == '') $display_landing_page='0';
				               		  ?>
				               		  <br>
					           		  <label class="custom-switch mt-2">
					           		    <input type="checkbox" name="display_landing_page" value="1" class="custom-switch-input"  @if(config("frontend.display_landing_page")=='1') {{ 'checked' }} @else {{ '' }} @endif>
					           		    <span class="custom-switch-indicator"></span>
					           		    <span class="custom-switch-description"><?php echo __('Display Landing Page');?></span>
                                        @if ($errors->has('display_landing_page'))
                                           <span class="text-danger"> {{ $errors->first('display_landing_page') }} </span>
                                        @endif
					           		  </label>
					           		</div>
				           		</div>
				           		<div class="col-12 col-md-6">
						            <div class="form-group">
						             	<label for=""><i class="fas fa-palette"></i> <?php echo __("Theme");?> </label>            			
				               			<?php 
				               			$select_front_theme=config('frontend.theme_front');
										if(config('frontend.theme_front')!="") $select_front_theme=config('frontend.theme_front');
										// echo form_dropdown('theme_front',$themes_front,$select_front_theme,'class="form-control" id="theme_front"');  ?>

										  <div class="row gutters-xs">
										    <div class="col-auto">
										      <label class="colorinput">
										        <input name="theme_front" type="radio" value="white" class="colorinput-input" <?php if ($select_front_theme == 'white') echo "checked"; ?>/>
										        <span class="colorinput-color bg-white"></span>
										      </label>
										    </div>
										    <div class="col-auto">
										      <label class="colorinput">
										        <input name="theme_front" type="radio" value="black" class="colorinput-input" <?php if ($select_front_theme == 'black') echo "checked"; ?>/>
										        <span class="colorinput-color bg-dark"></span>
										      </label>
										    </div>
										    <div class="col-auto">
										      <label class="colorinput">
										        <input name="theme_front" type="radio" value="blue" class="colorinput-input" <?php if ($select_front_theme == 'blue') echo "checked"; ?>/>
										        <span class="colorinput-color bg-blue"></span>
										      </label>
										    </div>
										    <div class="col-auto">
										      <label class="colorinput">
										        <input name="theme_front" type="radio" value="green" class="colorinput-input" <?php if ($select_front_theme == 'green') echo "checked"; ?>/>
										        <span class="colorinput-color bg-green"></span>
										      </label>
										    </div>
										    <div class="col-auto">
										      <label class="colorinput">
										        <input name="theme_front" type="radio" value="purple" class="colorinput-input" <?php if ($select_front_theme == 'purple') echo "checked"; ?>/>
										        <span class="colorinput-color bg-purple"></span>
										      </label>
										    </div>
										    <div class="col-auto">
										      <label class="colorinput">
										        <input name="theme_front" type="radio" value="red" class="colorinput-input" <?php if ($select_front_theme == 'red') echo "checked"; ?>/>
										        <span class="colorinput-color bg-red"></span>
										      </label>
										    </div>
										    <div class="col-auto">
										      <label class="colorinput">
										        <input name="theme_front" type="radio" value="yellow" class="colorinput-input" <?php if ($select_front_theme == 'yellow') echo "checked"; ?>/>
										        <span class="colorinput-color bg-yellow"></span>
										      </label>
										    </div>
										  
										</div>
                                        @if ($errors->has('theme_front'))
                                            <span class="text-danger"> {{ $errors->first('theme_front') }} </span>
                                        @endif
						            </div>
					        	</div>

	        		            <div class="col-12 col-md-6">
	        			            <div class="form-group">
	        		           		  <?php	
	        	               			$front_end_search_display = config('frontend.front_end_search_display');
	        	               			if($front_end_search_display == '') $front_end_search_display='0';
	        	               		  ?>
	        	               		  <br>
	        		           		  <label class="custom-switch mt-2">
	        		           		    <input type="checkbox" name="front_end_search_display" value="1" class="custom-switch-input"  @if(config("frontend.front_end_search_display")=='1') {{ 'checked' }} @else {{ '' }} @endif>
	        		           		    <span class="custom-switch-indicator"></span>
	        		           		    <span class="custom-switch-description"><?php echo __('Display Front-end website analysis search');?></span>
                                        @if ($errors->has('front_end_search_display'))
                                            <span class="text-danger"> {{ $errors->first('front_end_search_display') }} </span>
                                        @endif
	        		           		  </label>
	        		           		</div>
	        	           		</div>
				       		</div>
						</div>
						<?php echo $save_button; ?>
					</div>



					<div class="card" id="social-settings">

						<div class="card-header">
							<h4><i class="fas fa-share-square"></i> <?php echo __("Social Settings"); ?></h4>
						</div>
						<div class="card-body">
       	        			<div class="row">
       	        				<div class="col-12 col-md-6"> 			
       					            <div class="form-group">
       					             	<label for=""><i class="fab fa-facebook"></i> <?php echo __("Facebook");?></label>
       					             	<input name="facebook_link" value="{{config('frontend.facebook') ?? old('facebook')}}" class="form-control" type="text">		          
                                        @if ($errors->has('facebook_link'))
                                            <span class="text-danger"> {{ $errors->first('facebook_link') }} </span>
                                        @endif          			
       					            </div>
       					        </div>
       					        <div class="col-12 col-md-6">
       					            <div class="form-group">
       					             	<label for=""><i class="fab fa-twitter"></i> <?php echo __("Twitter");?></label>
       					             	<input name="twitter_link" value="{{config('frontend.twitter') ?? old('twitter')}}" class="form-control" type="text">		          
                                        @if ($errors->has('twitter_link'))
                                            <span class="text-danger"> {{ $errors->first('twitter_link') }} </span>
                                        @endif        			
       					            </div>
       				            </div>
       			            </div>

       						<div class="row">
       	        				<div class="col-12 col-md-6">
       					            <div class="form-group">
       					             	<label for=""><i class="fab fa-linkedin"></i> <?php echo __("Linkedin");?></label>
       					             	<input name="linkedin_link" value="{{config('frontend.linkedin') ?? old('linkedin')}}" class="form-control" type="text">		          
                                        @if ($errors->has('linkedin_link'))
                                            <span class="text-danger"> {{ $errors->first('linkedin_link') }} </span>
                                        @endif          			
       					            </div>
       					        </div>
       							<div class="col-12 col-md-6">
       					            <div class="form-group">
       					             	<label for=""><i class="fab fa-youtube"></i> <?php echo __("Youtube");?></label>
       					             	<input name="youtube_link" value="{{config('frontend.youtube') ?? old('youtube')}}" class="form-control" type="text">		          
                                        @if ($errors->has('youtube_link'))
                                            <span class="text-danger"> {{ $errors->first('youtube_link') }} </span>
                                        @endif          			
       					            </div>
       					        </div>
       					    </div>	
						</div>
						<?php echo $save_button; ?>
					</div>
					


					<div class="card" id="review-settings">

						<div class="card-header">
							<h4><i class="fas fa-smile"></i> <?php echo __("Review Settings"); ?></h4>
						</div>
						<div class="card-body">

							<div class="form-group">
			           		  <?php	
		               			$display_review_block = config('frontend.display_review_block');
		               			if($display_review_block == '')	$display_review_block='0';
		               		  ?>
			           		  <label class="custom-switch mt-2">
			           		    <input type="checkbox" name="display_review_block" value="1" class="custom-switch-input"  @if(config("frontend.display_review_block")=='1') {{ 'checked' }} @else {{ '' }} @endif>
			           		    <span class="custom-switch-indicator"></span>
			           		    <span class="custom-switch-description"><?php echo __('Display Review Block');?></span>
                                @if ($errors->has('display_review_block'))
                                   <span class="text-danger"> {{ $errors->first('display_review_block') }} </span>
                                @endif
			           		  </label>
			           		</div>		

							<!-- review block display section -->
							<?php $customer_review = config('frontend.customer_review') ; ?>

							<div class="allReview">
								<!-- demo video section started -->
					            <div class="form-group">
					             	<label for=""><i class="fa fa-play-circle"></i> <?php echo __("Customer Review Video");?></label>
					             	<input name="customer_review_video" value="{{config('frontend.customer_review_video') ?? old('customer_review_video')}}" class="form-control" type="text">
                                    @if ($errors->has('customer_review_video'))
                                        <span class="text-danger"> {{ $errors->first('customer_review_video') }} </span>
                                    @endif          			
					            </div>
					            <!-- end of the demo video section -->

								
								<!-- showing reviews section -->
								<div id="accordion">
								<?php $i = 1;
								// $string = str_replace("\n", '', $customer_review);
								// $customer_review_array = eval("return $string;");
									foreach($customer_review as $singleReview) :
										$original = $singleReview[2];
		                                $base     = url('/');

		                                if (substr($original, 0, 4) != 'http') {
		                                    $img = $base.$original;
		                                } else {
		                                   $img = $original;
		                                }

								?>
									  <div class="accordion">
									    <div class="accordion-header collapsed" role="button" data-toggle="collapse" data-target="#panel-body-<?php echo $i; ?>" aria-expanded="false">
									      <h4><i class="fa fa-thumbs-up"></i> <?php echo __('Review #').' '.$i.' '; ?></h4>
									    </div>
									    <div class="accordion-body collapse" id="panel-body-<?php echo $i; ?>" data-parent="#accordion" style="padding: 25px;">
									      	<div class="row">
												<div class="col-xs-12 col-md-6">
										           	<div class="form-group">
										             	<label ><i class="fa fa-user"></i> <?php echo __('Name');?></label>
								               			<input name="reviewer<?php echo $i; ?>" value="<?php echo $singleReview[0];?>" class="form-control" type="text">		          
                                                        @if ($errors->has('reviewer'.$i))
                                                           <span class="text-danger"> {{ $errors->first('reviewer'.$i) }} </span>
                                                        @endif
										           </div>
									           	</div>
											
									           	<div class="col-xs-12 col-md-6">
										           	<div class="form-group">
										             	<label ><i class="fa fa-briefcase"></i> <?php echo __('Designation');?></label>
								               			<input name="designation<?php echo $i; ?>" value="<?php echo $singleReview[1];?>"  class="form-control" type="text">		          
                                                        @if ($errors->has('designation'.$i))
                                                           <span class="text-danger"> {{ $errors->first('designation'.$i) }} </span>
                                                        @endif
										           </div>
									           	</div>
										  	</div>

											<div class="row">
									           	<div class="col-xs-12 col-md-12">
										           	<div class="form-group">
										             	<label ><i class="fa fa-picture-o"></i> <?php echo __('Avatar');?></label>
								               			<input name="pic<?php echo $i; ?>" value="<?php echo $img;?>"  class="form-control" type="text">		          
                                                        @if ($errors->has('pic'.$i))
                                                           <span class="text-danger"> {{ $errors->first('pic'.$i) }} </span>
                                                        @endif
										           </div>
									           	</div>
								          	</div>

								          	<div class="row">
									           	<div class="col-xs-12 col-md-12">
										           	<div class="form-group">
										             	<label ><i class="fa fa-comment"></i> <?php echo __('Review');?><small style="font-size: 12px;">&nbsp;</small></label>
								               			<textarea name="description<?php echo $i; ?>" rows="3" class="form-control" type="text"><?php echo $singleReview[3];?></textarea>	
										           </div>
									           	</div>
								           	</div>
									    </div>
									  </div>
									 
			        			<?php $i++; endforeach; ?>	
			        			</div>
								<!-- end of showing reviews section -->
							</div>
		        			<!-- end display review block section -->
						</div>
						<?php echo $save_button; ?>
					</div>


					
					<div class="card" id="video-settings">

						<div class="card-header">
							<h4><i class="fas fa-video"></i> <?php echo __("Video Settings"); ?></h4>
						</div>
						<div class="card-body">
							<div class="form-group">
			           		  <?php	
		               			$display_video_block = config('frontend.display_video_block');
		               			if($display_video_block == '')	$display_video_block='0';
		               		  ?>
			           		  <label class="custom-switch mt-2">
			           		    <input type="checkbox" name="display_video_block" value="1" class="custom-switch-input"  @if(config("frontend.display_video_block")=='1') {{ 'checked' }} @else {{ '' }} @endif>
			           		    <span class="custom-switch-indicator"></span>
			           		    <span class="custom-switch-description"><?php echo __('Display Tutorial Block');?></span>
                                @if ($errors->has('display_video_block'))
                                   <span class="text-danger"> {{ $errors->first('display_video_block') }} </span>
                                @endif
			           		  </label>
			           		</div>	
			           		
			            	
		           			<div class="extensions">
								<!-- promo video section started -->
					            <div class="form-group">
					             	<label for=""><i class="fa fa-play-circle"></i> <?php echo __("Promo Video");?></label>
					             	<input name="promo_video" value="{{config('frontend.promo_video') ?? old('promo_video')}}" class="form-control" type="text">
                                    @if ($errors->has('promo_video'))
                                        <span class="text-danger"> {{ $errors->first('promo_video') }} </span>
                                    @endif         			
					            </div>
				            	<!-- end of the promo video section -->

				            	<?php $custom_video = config('frontend.custom_video'); ?>
				            	<!-- video tutorial section started -->
				            	<div id="accordion-1">
					            <?php $i = 1; 
					            	foreach ($custom_video as $singleVideo) : 
					            	$original_video = $singleVideo[0];
					                $baseurl    = url('/');

					                if (substr($original_video,0,4) != 'http') {
					                    $thumb = $baseurl.$original_video;
					                } else {
					                    $thumb = $original_video;
					                }
					            ?>
									<div class="accordion">
									  <div class="accordion-header collapsed" role="button" data-toggle="collapse" data-target="#video-settings-body-<?php echo $i; ?>" aria-expanded="false">
									    <h4><i class="fa fa-youtube"></i> <?php echo __('Tutorial # ').' '.$i.' '; ?></h4>
									  </div>
									  <div class="accordion-body collapse" id="video-settings-body-<?php echo $i; ?>" data-parent="#accordion-1" style="padding: 25px;">
						    			<div class="row">
						    				<div class="col-xs-12 col-md-6">
						    		           	<div class="form-group">
						    		             	<label ><i class="fa fa-image"></i> <?php echo __('Thumbnail');?></label>
						                   			<input name="thumbnail<?php echo $i; ?>" value="<?php echo $thumb; ?>"  class="form-control" type="text">		          
                                                    @if ($errors->has('thumbnail'))
                                                       <span class="text-danger"> {{ $errors->first('thumbnail') }} </span>
                                                    @endif
						    		           </div>
						    	           	</div>
						    				
						    	           	<div class="col-xs-12 col-md-6">
						    		           	<div class="form-group">
						    		             	<label ><i class="fa fa-hashtag"></i> <?php echo __('Title');?></label>
						                   			<input name="title<?php echo $i; ?>" value="<?php echo $singleVideo[1]; ?>"  class="form-control" type="text">		          
                                                    @if ($errors->has("title ".$i))
                                                       <span class="text-danger"> {{ $errors->first("title ".$i) }} </span>
                                                    @endif
						    		           </div>
						    	           	</div>
						    			</div>

						    			<div class="row">
						    	           	<div class="col-xs-12 col-md-12">
						    		           	<div class="form-group">
						    		             	<label ><i class="fa fa-link"></i> <?php echo __('URL');?></label>
						                   			<input name="video_url<?php echo $i; ?>" value="<?php echo $singleVideo[2]; ?>"  class="form-control" type="text">		          
                                                    @if ($errors->has("video_url ".$i))
                                                       <span class="text-danger"> {{ $errors->first("video_url ".$i) }} </span>
                                                    @endif
						    		           </div>
						    	           	</div>
						              	</div>
									  </div>
									</div>
									
				        		<?php $i++; endforeach; ?>
				        		</div>
			        			<!-- end of the video tutorial section -->
				           	</div>
						</div>
						<?php echo $save_button; ?>
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
										<li class="nav-item"><a href="#general-settings" class="nav-link"><i class="fas fa-wrench"></i> <?php echo __("General Settings"); ?></a></li>
										<li class="nav-item"><a href="#social-settings" class="nav-link"><i class="fas fa-share-square"></i> <?php echo __("Social Settings"); ?></a></li>
										<li class="nav-item"><a href="#review-settings" class="nav-link"><i class="fas fa-smile"></i> <?php echo __("Review Settings"); ?></a></li>
										<li class="nav-item"><a href="#video-settings" class="nav-link"><i class="fas fa-video"></i> <?php echo __("Video Settings"); ?></a></li>								
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
	$("document").ready(function() {
     	var val1 = "0";
     	var val2 = "0";

     	if ($("input[name='display_review_block']").is(':checked')) val1='1';
     	if ($("input[name='display_video_block']").is(':checked')) val2='1';

      	// initail situation
     	// review block
     	if(val1 =='0') 
     	{
     		$('.allReview').hide();
     		$('.review_block').css("min-height","150px");
     	} 
     	else 
     	{
     		$('.review_block').css("min-height","1266px");
     	}

     	// video block
     	if(val2 =='0') 
     	{
     		$('.extensions').hide();
     		$('.video_block').css("min-height","150px");
     	} 
     	else 
     	{
     		$('.video_block').css("min-height","1266px");
     	}


     	$('input[name=display_review_block]').change(function() {
      		if ($("input[name='display_review_block']").is(':checked'))
      		{
        		$('.allReview').show();
        		$('.review_block').css("min-height","1266px");
        		
        	} else {
        		$('.allReview').hide();
        		$('.review_block').css("min-height","150px");
        	}
   		}); 

     	$('input[name=display_video_block]').change(function() {
        	if ($("input[name='display_video_block']").is(':checked'))
        	{
        		$('.extensions').show();
        		$('.video_block').css("min-height","1266px");
        	} else {
        		$('.extensions').hide();
        		$('.video_block').css("min-height","150px");
        	}
      	});
    });
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