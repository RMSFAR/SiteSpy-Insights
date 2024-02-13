{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Rebrandly URL analytics'))
@section('content')

<section class="section">

	<div class="section-header">
	    <h1>
	    	<i class="far fa-chart-bar"></i>
			<?php echo __("Rebrandly URL Analytics");?> : 
			<a href="<?php echo "https://{$rebrandly_shortener}"; ?>" target="_BLANK"><?php echo $rebrandly_shortener; ?></a>
	
	    </h1>
		<div class="section-header-breadcrumb">

	  		<div class="breadcrumb-item"><a href="{{route('rebrandly_shortener_index') }}"><?php echo __("Rebrandly URL Shortener");?></a></div>
	  		<div class="breadcrumb-item"><?php echo __('Rebrandly URL analytics');?></div>

	    </div>
  	</div>


  	<div class="section-body">	
			<?php 

				if(isset($total_click_data['error_message']))
					$error_message = $total_click_data['error_message'];


			 ?>
			 <?php if (isset($error_message)): ?>
 				<div class="card">
 	              <div class="card-header">
 	                <h4><?php echo __('Something Went Wrong'); ?></h4>
 	              </div>
 	              <div class="card-body">
 	                <div class="empty-state" data-height="400" style="height: 400px;">
 	                  <div class="empty-state-icon bg-danger">
 	                    <i class="fas fa-times"></i>
 	                  </div>
 	                  <h2><?php echo __('Something Went Wrong'); ?></h2>
 	                  <p class="lead">
 	                    <?php echo $error_message; ?>
 	                  </p>
 	                </div>
 	              </div>
 	            </div>
			 	<?php else: ?>
				<div class="row">
				  <div class="col-12 col-lg-6">
				    <div class="card">
				      <div class="card-header">
				        <h4>
				        	<?php echo __("Total Click Report");?>
				        	<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo __("Total Click Report") ?>" data-content="<?php echo __("The number of times click this rebrandly url shortener") ?>"><i class='fas fa-info-circle'></i> </a>
				        </h4>
				      </div>
				      <div class="card-body">
				        <canvas id="clicks_top_country" height="173"></canvas>	       
				      </div>
				    </div>
				  </div>

				  <div class="col-12 col-lg-6">
				    <div class="card">
				      <div class="card-header">
				        <h4>
				        	<?php echo __("Last Click & Sessions Report");?>
				        
				        </h4>
				      </div>
				      <div class="card-body">
				       <div class="card card-statistic-1">
	                       <div class="card-icon bg-primary">
	                         <i class="fas fa-clock"></i>
	                       </div>
	                       <div class="card-wrap">
	                         <div class="card-header">
	                           <h4><?php echo __('Last Click Time'); ?></h4>
	                         </div>
	                         <div class="card-body">
	                          <?php 

	                          	$last_click_time = isset($total_click_data['lastClickAt']) ? $total_click_data['lastClickAt'] : 0;
								  
	                          	$last_date = date('Y-m-d:H:i:s',strtotime($last_click_time));

	                            if ($last_date == '1970-01-01:01:00:00')
	                            	echo "N/A";
	                            else
	                            	echo $last_date;
	            


	                           ?>
	                         </div>
	                       </div>
	                     </div>	
	                     <div class="card card-statistic-1">
                             <div class="card-icon bg-success">
                               <i class="far fa-user"></i>
                             </div>
                             <div class="card-wrap">
                               <div class="card-header">
                                 <h4><?php echo __('Total Sessions'); ?></h4>
                               </div>
                               <div class="card-body">
                                 <?php 

                                 	$sessions = isset($total_click_data['sessions']) ? $total_click_data['sessions'] : 0;
                                 	echo $sessions;

                                  ?>
                               </div>
                             </div>
                           </div>       
				      </div>
				    </div>
				  </div>
				</div>
			 <?php endif; ?>




    </div>

</section>


@php

 //Rebrandly URL Shortener Total Clicks
    if (isset($data['total_click_data']['clicks'])) {
        $final_data= [];
        $final_data['Total clicks'] = isset($data['total_click_data']['clicks']) ? $data['total_click_data']['clicks'] : 0;
        $data = [];
        $data['data_array'] = $final_data;
    }

@endphp

<script>
    "use strict" 
    var final_data_array = '<?php echo json_encode(array_values(isset($data['data_array']) ? $data['data_array'] : [])) ?>';
    var label_final_data_array = '<? php echo json_encode(array_keys(isset($data['data_array']) ? $data['data_array'] : [])) ?>';

</script>

<script src="{{asset('assets/custom-js/url-shortner/rebrandly-analytics.js')}}"></script>

@endsection