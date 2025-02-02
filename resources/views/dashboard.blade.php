{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Dashboard'))
@section('content')

<div class="d-none">
{{__("Dashboard")}}
{{__("System")}}
{{__("Subscription")}}
{{__("Analysis Tools")}}
{{__("Utlities")}}
{{__("URL Shortner")}}
{{__("Keyword Tracking")}}
{{__("Security Tools")}}
{{__("Code Minifier")}}
{{__("Widgets")}}
{{__("Social Apps & APIs")}}
{{__("Settings")}}
{{__("Cron Job")}}
{{__("Add-on Manager")}}
{{__("Check Update")}}
{{__("Package Manager")}}
{{__("User Manager")}}
{{__("Announcement")}}
{{__("Payment Accounts")}}
{{__("Earning Summary")}}
{{__("Transaction Log")}}
{{__("Theme Manager")}}
{{__("Native API")}}
{{__("Language Editor")}}
</div>

<section class="section">
	<div class="section-body">
		<div class="row">
		  <div class="col-lg-4 col-md-4 col-sm-12">
		    <div class="card card-statistic-2">
		      <div class="card-stats">
		        <div class="card-stats-title"><?php echo __('Statistics'); ?>
		        </div>
		        <div class="card-stats-items">
		          <div class="card-stats-item">
		            <div class="card-stats-item-count"><?php echo $total_page_view; ?></div>
		            <div class="card-stats-item-label"><?php echo __('Page View'); ?></div>
		          </div>
		          <div class="card-stats-item">
		            <div class="card-stats-item-count"><?php echo $total_unique_visitor; ?></div>
		            <div class="card-stats-item-label" title="<?php echo __('Unique Visitor'); ?>"><?php echo __('Unique Visitor'); ?></div>
		          </div>
		          <div class="card-stats-item">
		            <div class="card-stats-item-count"><?php echo $bounce_rate; ?></div>
		            <div class="card-stats-item-label" title="<?php echo __('Bounce Rate'); ?>"><?php echo __('Bounce Rate'); ?></div>
		          </div>
		        </div>
		      </div>
		      <div class="card-icon shadow-primary bg-primary">
		        <i class="fas fa-clock"></i>
		      </div>
		      <div class="card-wrap">
		        <div class="card-header">
		          <h4><?php echo __('Total Stay Time'); ?></h4>
		        </div>
		        <div class="card-body">
		          <?php echo $total_stay_time; ?>
		        </div>
		      </div>
		    </div>
		  </div>
		    <div class="col-lg-4 col-md-4 col-sm-12">
		      <div class="card card-statistic-2">
		        <div class="card-chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
		        <canvas id="balance-chart" height="79" width="336" class="chartjs-render-monitor" style="display: block; width: 336px; height: 79px;"></canvas>
		      </div>
		      <div class="card-icon shadow-primary bg-primary">
		        <i class="fas fa-search"></i>
		      </div>
		      <div class="card-wrap">
		        <div class="card-header">
		          <h4><?php echo __('Last 7 Days Visitor From Search Engine'); ?></h4>
		        </div>
		        <div class="card-body">
		          <?php echo $seven_days_search_engine; ?>
		        </div>
		      </div>
		    </div>
		  </div>
		  <div class="col-lg-4 col-md-4 col-sm-12">
		    <div class="card card-statistic-2">
		      <div class="card-chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
		      <canvas id="sales-chart" height="79" width="336" class="chartjs-render-monitor" style="display: block; width: 336px; height: 79px;"></canvas>
		    </div>
		    <div class="card-icon shadow-primary bg-primary">
		      <i class="fas fa-directions"></i>
		    </div>
		    <div class="card-wrap">
		      <div class="card-header">
		        <h4><?php echo __('Last 7 Days Visitor From Direct'); ?></h4>
		      </div>
		      <div class="card-body">
		        <?php echo $seven_days_direct; ?>
		      </div>
		    </div>
		  </div>
		  </div>
		</div>
		<div class="row">
			<div class="col-lg-8">
			    <div class="card">
			      <div class="card-header">
			        <h4><?php echo __('Last 30 Days New Vs Returning User'); ?></h4>
			      </div>
			      <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
			      <canvas id="thirtydays_new_vs_returning" height="333" width="633" class="chartjs-render-monitor" style="display: block; width: 633px; height: 333px;"></canvas>
			    </div>
			  </div>
			</div>
			<?php
				$browser_list = [
					'chrome' => asset('assets/img/browser/chrome.png'),
					'firefox' => asset('assets/img/browser/firefox.png'),
					'safari' => asset('assets/img/browser/safari.png'),
					'opera' => asset('assets/img/browser/opera.png'),
					'ie' => asset('assets/img/browser/ie.png'),
					'edge' => asset('assets/img/browser/edge.png'),
				]; 
			?>
			<div class="col-lg-4">
			  <div class="card gradient-bottom">
			    <div class="card-header">
			      <h4><?php echo __('Top 5 Browsers'); ?></h4>
			    </div>
			    <div class="card-body" id="top-5-scroll" tabindex="2" style="height: 315px; overflow: hidden; outline: none;">
			      <ul class="list-unstyled list-unstyled-border">
			      	<?php foreach($top5_browser as $value): ?>
			        <li class="media">
			          <?php  
			          	$browser_name = strtolower($value['browser_name']);
			          	// $browser_name = 'abir';
						if(!empty($browser_name))
			          	$browser_img_path = isset($browser_list[$browser_name]) ? $browser_list[$browser_name] : asset("assets/img/browser/other.png");
						else $browser_img_path=asset("assets/img/browser/other.png");
			          ?>
			          <img class="mr-3 rounded" width="55" src="<?php echo url($browser_img_path); ?>" alt="<?php echo $value['browser_name']; ?>">
			          <div class="media-body">
			            <div class="float-right"><div class="font-weight-600 text-muted text-small"><?php echo number_format($value['sessions_count']); ?> &nbsp; <?php echo __('Sessions'); ?></div></div>
			            <div class="media-title"><?php echo $value['browser_name']; ?></div>
			            <div class="mt-1">
			              <div class="budget-price">
			                <div class="budget-price-square bg-primary" data-width="<?php echo $value['desktop_percentage']; ?>%"></div>
			                <div class="budget-price-label"><?php echo number_format($value['desktop_percentage'])."%"; ?></div>
			              </div>
			              <div class="budget-price">
			                <div class="budget-price-square bg-danger" data-width="<?php echo $value['mobile_percentage']; ?>%"></div>
			                <div class="budget-price-label"><?php echo number_format($value['mobile_percentage'])."%"; ?></div>
			              </div>
			            </div>
			          </div>
			        </li>
				    <?php endforeach; ?>
			      </ul>
			    </div>
			    <div class="card-footer pt-3 d-flex justify-content-center">
			      <div class="budget-price justify-content-center">
			        <div class="budget-price-square bg-primary" data-width="20" style="width: 20px;"></div>
			        <div class="budget-price-label"><?php echo __('Desktop'); ?></div>
			      </div>
			      <div class="budget-price justify-content-center">
			        <div class="budget-price-square bg-danger" data-width="20" style="width: 20px;"></div>
			        <div class="budget-price-label"><?php echo __('Mobile'); ?></div>
			      </div>
			    </div>
			  </div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-7">
				<div class="card">
					<div class="card-header"><h4><?php echo __('Top 5 Visitor Countries'); ?></h4></div>
					<div class="card-body">
						<div class="owl-carousel owl-theme" id="products-carousel">
						  <?php foreach($top5_country as $value) : ?>
						  <div>
						    <div class="product-item pb-3">
						      <div class="product-image">
						        <img alt="image" src="<?php echo $value['country_flag']; ?>" class="img-fluid">
						      </div>
						      <div class="product-details">
						        <div class="product-name"><?php if($value['country_name'] != '') echo $value['country_name']; else echo $value['country_name']; ?></div>
						        <div class="text-muted text-small"><?php echo $value['session_count']; ?> &nbsp; <?php echo __('Visitor'); ?></div>

						      </div>
						    </div>
						  </div>
						  <?php endforeach; ?>
						</div>
					</div>
				</div>	
			</div>
			<?php
				$os_list = [
					'android' => asset('assets/img/os/android.png'),
					'ipad' => asset('assets/img/os/ipad.png'),
					'iphone' => asset('assets/img/os/iphone.png'),
					'linux' => asset('assets/img/os/linux.png'),
					'mac os x' => asset('assets/img/os/mac.png'),
					'search bot' => asset('assets/img/os/search-bot.png'),
					'windows' => asset('assets/img/os/windows.png'),
				]; 
			?>
			<div class="col-lg-5">
				<div class="card">
					<div class="card-header"><h4><?php echo __('Top Operating Systems'); ?></h4></div>
					<div class="card-body">
						<div class="row">
						  <div class="col-sm-6">
						    <ul class="list-unstyled list-unstyled-border list-unstyled-noborder mb-0">
						      <?php 
						      	for($i=0;$i<3;$i++) : 
						      		if(!isset($top5_os[$i]['os_name'])) 
						      			continue;
						      		$os_name = strtolower($top5_os[$i]['os_name']);
						          	$os_img_path = isset($os_list[$os_name]) ? $os_list[$os_name] : asset("assets/img/browser/other.png");
						      ?>
							      <li class="media"> 
							        <img class="img-fluid mt-1" src="<?php echo url($os_img_path); ?>" alt="image" width="40">
							        <div class="media-body ml-3">
							          <div class="media-title"><?php echo isset($top5_os[$i]['os_name']) ? $top5_os[$i]['os_name'] : ''; ?></div>
							          <div class="text-small text-muted"><?php echo isset($top5_os[$i]['session_count']) ? number_format($top5_os[$i]['session_count']) : 0; echo " "; echo __('Sessions');?></div>
							        </div>
							      </li>
						      <?php endfor; ?>
						    </ul>
						  </div>
						  <div class="col-sm-6 mt-sm-0 mt-4">
						    <ul class="list-unstyled list-unstyled-border list-unstyled-noborder mb-0">
						      <?php 
						      	for($i=3;$i<6;$i++) : 
						      		if(!isset($top5_os[$i]['os_name'])) 
						      			continue;
						      		$os_name = strtolower($top5_os[$i]['os_name']);
						          	$os_img_path = isset($os_list[$os_name]) ? $os_list[$os_name] : asset("assets/img/browser/other.png");
						      ?>
							      <li class="media"> 
							        <img class="img-fluid mt-1" src="<?php echo url($os_img_path); ?>" alt="image" width="40">
							        <div class="media-body ml-3">
							          <div class="media-title"><?php echo isset($top5_os[$i]['os_name']) ? $top5_os[$i]['os_name'] : ''; ?></div>
							          <div class="text-small text-muted"><?php echo isset($top5_os[$i]['session_count']) ? number_format($top5_os[$i]['session_count']) : 0; echo " "; echo __('Sessions');?></div>
							        </div>
							      </li>
						      <?php endfor; ?>
						    </ul>
						  </div>
						</div>

					</div>
				</div>
			</div>

		</div>

		<div class="row">
			<div class="col-12 col-lg-12 p-2">
				<div class="card">
					<div class="card-header">
						<h4><?php echo __('Visitor Comparison- Last 7 Days'); ?></h4>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-12 col-md-12 p-0">
								<canvas id="seven_days_camparison_line_chart" height="100"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<?php if(isset($website_id_1)) : ?>
				<div class="col-12 col-lg-4 p-2">
				  <div class="card">
				    <div class="card-header">
				      <h4> 
				      	<?php 

				      		$website_id_1 = isset($website_id_1) ? $website_id_1: "";

				      	 ?>
				      	 <a target="_blank" href="<?php echo base_url('visitor_analysis/domain_details/').$website_id_1; ?>"><?php echo isset($website_name_1) ? $website_name_1 : ""; ?></a>
				      </h4>
				    </div>
				    <div class="card-body">
				      <div class="row">
				      	<div class="col-12 col-md-6 p-0">
				      		<canvas id="country_chart_data_1" height="300"></canvas>
				      	</div>
				      	<div class="col-12 col-md-6 p-0">
				      		<canvas id="pie_chart_data_1" height="300"></canvas>
				      	</div>
				      </div>
				      <br>
				      <div class="row">
				      	<div class="col-12 col-md-12 p-0">
				      		<canvas id="day_wise_click_report_chart_1" height="150"></canvas>
				      	</div>
				      </div>	 	       
				    </div>
				  </div>
				</div>
			<?php endif; ?>

			<?php if(isset($website_id_2)) : ?>
			    <div class="col-12 col-lg-4 p-2">
				    <div class="card">
				      <div class="card-header">
				      	<h4>
				      		<?php 
				      		$website_id_2 = isset($website_id_2) ? $website_id_2: "";
				      	 	?>
				      		 <a target="_blank" href="<?php echo base_url('visitor_analysis/domain_details/').$website_id_2; ?>"><?php echo isset($website_name_2) ? $website_name_2 : ""; ?></a>
				      	</h4>
				      </div>
				      <div class="card-body">
				        <div class="row">
				        	<div class="col-12 col-md-6 p-0">
				        		<canvas id="country_chart_data_2" height="300"></canvas>
				        	</div>
				        	<div class="col-12 col-md-6 p-0">
				        		<canvas id="pie_chart_data_2" height="300"></canvas>
				        	</div>
				        </div>
				        <br>
				        <div class="row">
				        	<div class="col-12 col-md-12 p-0">
				        		<canvas id="day_wise_click_report_chart_2" height="150"></canvas>
				        	</div>
				        </div>       
				      </div>
				    </div>
			    </div>
		    <?php endif; ?>

		    <?php if(isset($website_id_3)) : ?>
				<div class="col-12 col-lg-4 p-2">
					  <div class="card">
					    <div class="card-header">
					      <h4>
					      		<?php 
					      		$website_id_3 = isset($website_id_3) ? $website_id_3: "";
					      	 	?>
					      	 <a target="_blank" href="<?php echo base_url('visitor_analysis/domain_details/').$website_id_3; ?>"><?php echo isset($website_name_3) ? $website_name_3 : ""; ?></a>
					      </h4>
					    </div>
					    <div class="card-body">
					      <div class="row">
					      	<div class="col-12 col-md-6 p-0">
					      		<canvas id="country_chart_data_3" height="300"></canvas>
					      	</div>
					      	<div class="col-12 col-md-6 p-0">
					      		<canvas id="pie_chart_data_3" height="300"></canvas>
					      	</div>
					      </div>
					      <br>
					      <div class="row">
					      	<div class="col-12 col-md-12 p-0">
					      		<canvas id="day_wise_click_report_chart_3" height="150"></canvas>
					      	</div>
					      </div> 		 	       
					    </div>
					  </div>
				</div>
			<?php endif; ?>
		</div>
		

	</div>
</section>

@php 

	$steps = 10;

	$first_line_chart = isset($day_wise_click_report[0]) ? array_column($day_wise_click_report[0], 'user') : array();
	$final_first = round(array_sum($first_line_chart)/$steps);
	if($final_first == 0)
		$final_first_steps = 1;
	else
		$final_first_steps = $final_first;


	$second_line_chart = isset($day_wise_click_report[1]) ? array_column($day_wise_click_report[1], 'user') : array();
	$final_second = round(array_sum($second_line_chart)/$steps);
	if($final_second == 0)
		$final_second_steps = 1;
	else
		$final_second_steps = $final_second;


	$third_line_chart = isset($day_wise_click_report[2]) ? array_column($day_wise_click_report[2], 'user') : array();
	$final_third = round(array_sum($third_line_chart)/$steps);
	if($final_third == 0)
		$final_third_steps = 1;
	else
		$final_third_steps = $final_third;


	$final_array = array();
	$day_wise_click_report_compare_first=isset($line_char_data_compare[0])?$line_char_data_compare[0]:array();

	$domain_list=array_keys($day_wise_click_report_compare_first);
	unset($domain_list[0]);

	foreach ($domain_list as $key => $value) {
		$final_array[$value] = array();
	}

	foreach ($line_char_data_compare as $key => $value) {
		
		foreach ($value as $key2 => $value2) {
			if ($key2 != 'date') {
				array_push($final_array[$key2], $value2);
			}
		}

	}

   $final_array_keys = array_keys($final_array);

   //isset($final_array[$final_array_keys[0]]) ? json_encode($final_array[$final_array_keys[0]]) : json_encode(array());

   $first = isset($final_array_keys[0]) ? $final_array_keys[0] : "";
   $first_index = isset($final_array[$first]) ? json_encode($final_array[$first]) : json_encode(array());

   $sec = isset($final_array_keys[1]) ? $final_array_keys[1] : "";
   $sec_index = isset($final_array[$sec]) ? json_encode($final_array[$sec]) : json_encode(array());
   

   $third = isset($final_array_keys[2]) ? $final_array_keys[2] : "";
   $third_index = isset($final_array[$third]) ? json_encode($final_array[$third]) : json_encode(array());


@endphp

<script>
	
    $(".visitor_doamin_list_item").click(function(e) {
        e.preventDefault();

        var id = $(this).attr('data-id');
        var name = $(this).text();;
        $.ajax({
            type:'POST' ,
            url:visior_domain_session,
            data:{id,name},
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            success:function(response){
                location.reload();
            },
            error: function (xhr, statusText) {
                const msg = handleAjaxError(xhr, statusText);
                Swal.fire({icon: 'error',title: global_lang_error,html: msg});
                return false;
            }
        });
    });
</script>

<script type="text/javascript">

	/**
	 * =======================Start Doughnut Chart ==============================================
	 */
	<?php if(isset($website_id_1)) : ?>
	var country_chart_data_1_config = {
	  	type: 'doughnut',
	  	data: {
	  		datasets: [{
	  			data: <?php echo json_encode(array_values(isset($country_chart_data_1) ? $country_chart_data_1 : array())); ?>,
	  			backgroundColor: [
	  				'#ff5e57',
	  				'#ff6384',
	  				'#6777ef',
	  				'#ffa426',
	  				'#c32849'
	  			],
	  			
	  		}],
	  		labels: <?php echo json_encode(array_keys(isset($country_chart_data_1) ? $country_chart_data_1 : array())); ?>
	  	},
	  	options: {
	  		responsive: true,
	  		legend: {
	  			display: false,
	  		},
	  		
	  		animation: {
	  			animateScale: true,
	  			animateRotate: true
	  		},
	  		title: {
	  			display: true,
	  			text: '<?php echo __("Todays New Visitor"); ?>',
	  			position: 'bottom',
	  		}

	  	}
	  };

	 
	var country_chart_data_1_config_ctx = document.getElementById('country_chart_data_1').getContext('2d');
	var country_chart_data_1_chart = new Chart(country_chart_data_1_config_ctx, country_chart_data_1_config);
	<?php endif; ?>
	//End Country
	<?php if(isset($website_id_2)) : ?>
	var country_chart_data_2_config = {
	  	type: 'doughnut',
	  	data: {
	  		datasets: [{
	  			data: <?php echo json_encode(array_values(isset($country_chart_data_2) ? $country_chart_data_2 : array())); ?>,
	  			backgroundColor: [
	  				'#ff5e57',
	  				'#ff6384',
	  				'#6777ef',
	  				'#ffa426',
	  				'#c32849'
	  			],
	  			
	  		}],
	  		labels: <?php echo json_encode(array_keys(isset($country_chart_data_2) ? $country_chart_data_2 : array())); ?>
	  	},
	  	options: {
	  		responsive: true,
	  		legend: {
	  			display: false,
	  		},
	  		
	  		animation: {
	  			animateScale: true,
	  			animateRotate: true
	  		},
	  		title: {
	  			display: true,
	  			text: '<?php echo __("Todays New Visitor"); ?>',
	  			position: 'bottom',
	  		}

	  	}
	  };

	 
	var country_chart_data_2_config_ctx = document.getElementById('country_chart_data_2').getContext('2d');
	var country_chart_data_2_config_chart = new Chart(country_chart_data_2_config_ctx, country_chart_data_2_config);
	<?php endif; ?>
	// End Country 2
	<?php if(isset($website_id_3)) : ?>
	var country_chart_data_3_config = {
	  	type: 'doughnut',
	  	data: {
	  		datasets: [{
	  			data: <?php echo json_encode(array_values(isset($country_chart_data_3) ? $country_chart_data_3 : array())); ?>,
	  			backgroundColor: [
	  				'#ff5e57',
	  				'#ff6384',
	  				'#6777ef',
	  				'#ffa426',
	  				'#c32849'
	  			],
	  			
	  		}],
	  		labels: <?php echo json_encode(array_keys(isset($country_chart_data_3) ? $country_chart_data_3 : array())); ?>
	  	},
	  	options: {
	  		responsive: true,
	  		legend: {
	  			display: false,
	  		},
	  		
	  		animation: {
	  			animateScale: true,
	  			animateRotate: true
	  		},
	  		title: {
	  			display: true,
	  			text: '<?php echo __("Todays New Visitor"); ?>',
	  			position: 'bottom',
	  		}

	  	}
	  };

	 
	var country_chart_data_3_config_ctx = document.getElementById('country_chart_data_3').getContext('2d');
	var country_chart_data_3_config_chart = new Chart(country_chart_data_3_config_ctx, country_chart_data_3_config);
	<?php endif; ?>

	// End Country 3
	
	
		/**
	 * ======================= End Doughnut Chart ==============================================
	 */
	/**
	 * =================================Start Pie Chart ===================================
	 * 
	 */
	<?php if(isset($website_id_1)) : ?>
	var pie_chart_data_1_config = {
	 	type: 'pie',
	 	data: {
	 		datasets: [{
	 			data: <?php echo json_encode(array_values(isset($pie_chart_data_1['value']) ? $pie_chart_data_1['value']: array())); ?>,
	 			backgroundColor: [	
	 			'#badc58',
	 			'#6ab04c',
	 			],
	 			
	 			
	 		}],
	 		labels: ["New User","Returning User"]
	 	},
	 	options: {
	 		responsive: true,
	 		legend: {
	 			display: false,
	 			//position: 'top',

	 		},
	 		animation: {
	 			animateScale: true,
	 			animateRotate: true
	 		},
	 		title: {
	 			display: true,
	 			text: '<?php echo __("Todays New Vs Returning"); ?>',
	 			position: 'bottom',
	 		}

	 	}
	 };

	var pie_chart_data_1_config_ctx = document.getElementById('pie_chart_data_1').getContext('2d');
	var pie_chart_data_1_config_ctx_chart = new Chart(pie_chart_data_1_config_ctx, pie_chart_data_1_config);
	<?php endif; ?>
	//End First One
	<?php if(isset($website_id_2)) : ?>
	var pie_chart_data_2_config = {
	 	type: 'pie',
	 	data: {
	 		datasets: [{
	 			data: <?php echo json_encode(array_values(isset($pie_chart_data_2['value']) ? $pie_chart_data_2['value']: array())); ?>,
	 			backgroundColor: [	
	 			'#badc58',
	 			'#6ab04c',
	 			],
	 			
	 			
	 		}],
	 		labels: ["New User","Returning User"]
	 	},
	 	options: {
	 		responsive: true,
	 		legend: {
	 			display: false,
	 			//position:'top'
	 		},
	 		title: {
	 			display: true,
	 			text: '<?php echo __("Todays New Vs Returning"); ?>',
	 			position:'bottom'
	 		},
	 		animation: {
	 			animateScale: true,
	 			animateRotate: true
	 		},


	 	}
	 };

	var pie_chart_data_2_config_ctx = document.getElementById('pie_chart_data_2').getContext('2d');
	var pie_chart_data_2_config_ctx_chart = new Chart(pie_chart_data_2_config_ctx, pie_chart_data_2_config);
	<?php endif; ?>
	//End Second One
	<?php if(isset($website_id_3)) : ?>
	var pie_chart_data_3_config = {
	 	type: 'pie',
	 	data: {
	 		datasets: [{
	 			data: <?php echo json_encode(array_values(isset($pie_chart_data_3['value']) ? $pie_chart_data_3['value']: array())); ?>,
	 			backgroundColor: [	
	 			'#badc58',
	 			'#6ab04c',
	 			],
	 			
	 			
	 		}],
	 		labels: ["New User","Returning User"]
	 	},
	 	options: {
	 		responsive: true,
	 		legend: {
	 			display: false,
	 			//position:'top'
	 		},
	 		title: {
	 			display: true,
	 			text: '<?php echo __("Todays New Vs Returning"); ?>',
	 			position:'bottom'
	 		},
	 		animation: {
	 			animateScale: true,
	 			animateRotate: true
	 		},


	 	}
	 };

	var pie_chart_data_3_config_ctx = document.getElementById('pie_chart_data_3').getContext('2d');
	var pie_chart_data_3_config_ctx_chart = new Chart(pie_chart_data_3_config_ctx, pie_chart_data_3_config);
	<?php endif; ?>
	//End Third One

	
	/**
	 *  ======================End Pie Chart =====================================================
	 */
	
	/**
	 * ======================Start Line Chart ===================================================
	 */
	<?php if(isset($website_id_1)) : ?>	
	var day_wise_click_report_chart_1_data = document.getElementById("day_wise_click_report_chart_1").getContext('2d');

	var day_wise_click_report_chart_1_data_label = <?php echo isset($day_wise_click_report[0]) ? json_encode(array_column($day_wise_click_report[0], 'date')) : json_encode(array()); ?>;
	var day_wise_click_report_chart_1_data_values = <?php echo isset($day_wise_click_report[0]) ? json_encode(array_column($day_wise_click_report[0], 'user')) : json_encode(array()); ?>;

	var day_wise_click_report_chart_1_chart = new Chart(day_wise_click_report_chart_1_data, {
	  type: 'line',
	  data: {
	    labels: day_wise_click_report_chart_1_data_label,
	    datasets: [{
	      label: '<?php echo __("Click"); ?>',
	      data: day_wise_click_report_chart_1_data_values,
	      borderWidth: 3,
	      borderColor: '#36a2eb',
	      backgroundColor: 'transparent',
	      pointBackgroundColor: '#fff',
	      pointBorderColor: '#36a2eb',
	      pointRadius: 2
	    }]
	  },
	  options: {
	    legend: {
	      display: false
	    },
	    title: {
	    	display: true,
	    	text: '<?php echo __("Visitor Last - 7 Days"); ?>',
	    	position:'bottom'
	    },
	    scales: {
	      yAxes: [{
	        gridLines: {
	          display: false,
	          drawBorder: false,
	        },
	        ticks: {
	          stepSize: <?php echo $final_first_steps; ?>
	        }

	      }],
	      xAxes: [{
	        gridLines: {
	          color: '#fbfbfb',
	          lineWidth: 2
	        },
	        type: 'time',
	        distribution: 'series'
	      }],

	    },
	  }
	});
	<?php endif; ?>
	// Chart One End
	<?php if(isset($website_id_2)) : ?>
	var day_wise_click_report_chart_2_data = document.getElementById("day_wise_click_report_chart_2").getContext('2d');

	var day_wise_click_report_chart_2_data_label = <?php echo isset($day_wise_click_report[1]) ? json_encode(array_column($day_wise_click_report[1], 'date')) : json_encode(array()); ?>;
	var day_wise_click_report_chart_2_data_values = <?php echo isset($day_wise_click_report[1]) ? json_encode(array_column($day_wise_click_report[1], 'user')) : json_encode(array()); ?>;

	var day_wise_click_report_chart_2_chart = new Chart(day_wise_click_report_chart_2_data, {
	  type: 'line',
	  data: {
	    labels: day_wise_click_report_chart_2_data_label,
	    datasets: [{
	      label: '<?php echo __("Click"); ?>',
	      data: day_wise_click_report_chart_2_data_values,
	      borderWidth: 3,
	      borderColor: '#36a2eb',
	      backgroundColor: 'transparent',
	      pointBackgroundColor: '#fff',
	      pointBorderColor: '#36a2eb',
	      pointRadius: 2
	    }]
	  },
	  options: {
	    legend: {
	      display: false
	    },
	    title: {
	    	display: true,
	    	text: '<?php echo __("Visitor Last - 7 Days"); ?>',
	    	position:'bottom'
	    },
	    scales: {
	      yAxes: [{
	        gridLines: {
	          display: false,
	          drawBorder: false,
	        },
	        ticks: {
	          stepSize: <?php echo $final_second_steps; ?>
	        }
	      }],
	      xAxes: [{
	        gridLines: {
	          color: '#fbfbfb',
	          lineWidth: 2
	        },
	        type: 'time',
	        distribution: 'series'
	      }],

	    },
	  }
	});
	<?php endif; ?>
	// Chart Two End

	<?php if(isset($website_id_3)) : ?>
	var day_wise_click_report_chart_3_data = document.getElementById("day_wise_click_report_chart_3").getContext('2d');

	var day_wise_click_report_chart_3_data_label = <?php echo isset($day_wise_click_report[2]) ? json_encode(array_column($day_wise_click_report[2], 'date')) : json_encode(array()); ?>;
	var day_wise_click_report_chart_3_data_values = <?php echo isset($day_wise_click_report[2]) ? json_encode(array_column($day_wise_click_report[2], 'user')) : json_encode(array()); ?>;

	var day_wise_click_report_chart_3_chart = new Chart(day_wise_click_report_chart_3_data, {
	  type: 'line',
	  data: {
	    labels: day_wise_click_report_chart_3_data_label,
	    datasets: [{
	      label: '<?php echo __("Click"); ?>',
	      data: day_wise_click_report_chart_3_data_values,
	      borderWidth: 3,
	      borderColor: '#36a2eb',
	      backgroundColor: 'transparent',
	      pointBackgroundColor: '#fff',
	      pointBorderColor: '#36a2eb',
	      pointRadius: 2
	    }]
	  },
	  options: {
	    legend: {
	      display: false
	    },
	    title: {
	    	display: true,
	    	text: '<?php echo __("Visitor Last - 7 Days"); ?>',
	    	position:'bottom'
	    },
	    scales: {
	      yAxes: [{
	        gridLines: {
	          display: false,
	          drawBorder: false,
	        },
	        ticks: {
	          stepSize: <?php echo $final_third_steps; ?>
	        }

	      }],
	      xAxes: [{
	        gridLines: {
	          color: '#fbfbfb',
	          lineWidth: 2
	        },
	        type: 'time',
	        distribution: 'series'
	      }],

	    },
	  }
	});
	<?php endif; ?>
	// Chart Three End


	//Chart Fourth End
	/**
	 * =====================End Line Chart ======================================================
	 */




	 /**
	  * ======================Start Visitor Comparison Last 7 Days =====================================
	  */
	 	
	 	var fb_vs_ig_vs_web_earning_chart = document.getElementById('seven_days_camparison_line_chart').getContext('2d');

	 	var gradient_info = fb_vs_ig_vs_web_earning_chart.createLinearGradient(0, 0, 0, 600);
	 	gradient_info.addColorStop(0, 'rgba(21, 233, 255, .8)');
	 	gradient_info.addColorStop(1, 'rgba(19, 29, 75, .8)'); 

	 	var gradient_success = fb_vs_ig_vs_web_earning_chart.createLinearGradient(0, 0, 0, 600);
	 	gradient_success.addColorStop(0, 'rgba(83, 161, 100,.8)');
	 	gradient_success.addColorStop(1, 'rgba(19, 29, 75, .8)'); 

	 	var gradient_primary = fb_vs_ig_vs_web_earning_chart.createLinearGradient(0, 0, 0, 600);
	 	gradient_primary.addColorStop(0, 'rgba(13, 139, 241, .6)');
	 	gradient_primary.addColorStop(1, 'rgba(7, 65, 204, .6)'); 

	 	var gradient_secondary = fb_vs_ig_vs_web_earning_chart.createLinearGradient(0, 0, 0, 600);
	 	gradient_secondary.addColorStop(0, 'rgba(241, 71, 147, .7)');
	 	gradient_secondary.addColorStop(1, 'rgba(58, 9, 137, .7)'); 

	 	var gradient_warning = fb_vs_ig_vs_web_earning_chart.createLinearGradient(0, 0, 0, 600);
	 	gradient_warning.addColorStop(0, 'rgba(252, 74, 26, .8)');
	 	gradient_warning.addColorStop(1, 'rgba(247, 183, 51, .8)'); 

	 	var gradient_danger = fb_vs_ig_vs_web_earning_chart.createLinearGradient(0, 0, 0, 600);
	 	gradient_danger.addColorStop(0, 'rgba(255, 106, 0, .8)');
	 	gradient_danger.addColorStop(1, 'rgba(238, 9, 121, .8)'); 

	 	var seven_days_camparison_line_chart_data = document.getElementById("seven_days_camparison_line_chart").getContext('2d');
	 	<?php
	 		if($line_char_data_compare)
	 		{
	 			$comparison_label = array_column($line_char_data_compare, 'date');
	 			$comparison_label = json_encode($comparison_label);
	 		}
	 		else
	 			$comparison_label = json_encode(array());
	 	?>
	 	var seven_days_camparison_line_chart_data_label = <?php echo $comparison_label ?>;
	 	var first_label = '<?php echo isset($final_array_keys[0]) ? $final_array_keys[0] : ""; ?>';
	 	var second_label = '<?php echo isset($final_array_keys[1]) ? $final_array_keys[1] : ""; ?>';
	 	var third_label = '<?php echo isset($final_array_keys[2]) ? $final_array_keys[2] : ""; ?>';

	 	var seven_days_camparison_line_charta = new Chart(seven_days_camparison_line_chart_data, {
	 	  type: 'line',
	 	  data: {
	 	    labels: seven_days_camparison_line_chart_data_label,
	 	    datasets: [{
	 	      label: [first_label],
	 	      data: <?php echo $first_index; ?>,
	 	      borderWidth: 0,
	 	      backgroundColor: gradient_info,
	 	      borderWidth: 0,
	 	      borderColor: 'transparent',
	 	      pointBorderWidth: 0 ,
	 	      pointRadius: 0,
	 	      pointBackgroundColor: 'transparent',
	 	      pointHoverBackgroundColor: 'transparent',
	 	    },
	 	    {
	 	      label: [second_label],
	 	      data: <?php echo $sec_index; ?>,
	 	      borderWidth: 0,
	 	      backgroundColor: gradient_secondary,
	 	      borderWidth: 0,
	 	      borderColor: 'transparent',
	 	      pointBorderWidth: 0,
	 	      pointRadius: 0,
	 	      pointBackgroundColor: 'transparent',
	 	      pointHoverBackgroundColor: 'rgba(13, 139, 241, .8)',
	 	    },
	 	    {
	 	      label: [third_label],
	 	      data: <?php echo $third_index; ?>,
	 	      borderWidth: 0,
	 	      backgroundColor: gradient_danger,
	 	      borderWidth: 0,
	 	      borderColor: 'transparent',
	 	      pointBorderWidth: 0 ,
	 	      pointRadius: 0,
	 	      pointBackgroundColor: 'transparent',
	 	      pointHoverBackgroundColor: 'transparent',
	 	    }]
	 	  },
	 	  options: {
	 	  	responsive: true,
	 	  	legend: {
	 	  		display: true,
	 	  	},
	 	  	title: {
	 	  		display: true,
	 	  		text: '<?php echo __('Visitor Comparison- Last 7 Days'); ?>',
	 	  		position:'bottom'
	 	  	},
	 	  	tooltips: {
	 	  		mode: 'index',
	 	  		intersect: false,
	 	  	},
	 	  	hover: {
	 	  		mode: 'nearest',
	 	  		intersect: true
	 	  	},
	 	  	scales: {

	 	  		yAxes: [{
	 	  		  gridLines: {
	 	  		    display: false,
	 	  		    drawBorder: false,
	 	  		  },
	 	  		  scaleLabel: {
	 	  		  	display: true,
	 	  		  }

	 	  		}],
	 	  		xAxes: [{
	 	  		  gridLines: {
	 	  		    color: '#fbfbfb',
	 	  		    lineWidth: 2
	 	  		  },
	 	  		  scaleLabel: {
	 	  		  	display: true,
	 	  		  },
	 	  		  type: 'time',
	 	  		     time: {
	 	  		         displayFormats: {
	 	  		             quarter: 'MMM YYYY'
	 	  		         }
	 	  		     }
	 	  		}]
	 	  	}
	 	  }
	 	});

	 /**
	  * ======================End Visitor Comparison Last 7 Days =====================================
	  */


	  /**
	   * ==================== Last 7 Days Visitor From Search Engine  =======================================
	   *
	   */
	  var balance_chart = document.getElementById("balance-chart").getContext('2d');

	  var balance_chart_bg_color = balance_chart.createLinearGradient(0, 0, 0, 70);
	  balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
	  balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

	  var myChart = new Chart(balance_chart, {
		    type: 'line',
		    data: {
		      labels: <?php echo isset($traffic_line_chart_dates) ? json_encode($traffic_line_chart_dates) : array(); ?>,
		      datasets: [{
		        label: '<?php echo __('Visitor From Search Engine'); ?>',
		        data: <?php echo isset($traffic_search_link) ? json_encode($traffic_search_link) : array(); ?>,
		        backgroundColor: balance_chart_bg_color,
		        borderWidth: 3,
		        borderColor: 'rgba(63,82,227,1)',
		        pointBorderWidth: 0,
		        pointBorderColor: 'transparent',
		        pointRadius: 3,
		        pointBackgroundColor: 'transparent',
		        pointHoverBackgroundColor: 'rgba(63,82,227,1)',
		      }]
		    },
		    options: {
		      layout: {
		        padding: {
		          bottom: -1,
		          left: -1
		        }
		      },
		      legend: {
		        display: false
		      },
		      scales: {
		        yAxes: [{
		          gridLines: {
		            display: false,
		            drawBorder: false,
		          },
		          ticks: {
		            beginAtZero: true,
		            display: false
		          }
		        }],
		        xAxes: [{
		          gridLines: {
		            drawBorder: false,
		            display: false,
		          },
		          ticks: {
		            display: false
		          }
		        }]
		      },
		    }
		  });

	  /**
	   * Last 7 days Visitor From Direct
	   */
	  var sales_chart = document.getElementById("sales-chart").getContext('2d');

	  var sales_chart_bg_color = sales_chart.createLinearGradient(0, 0, 0, 80);
	  sales_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
	  sales_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

	  var myChart2 = new Chart(sales_chart, {
		    type: 'line',
		    data: {
		      labels: <?php echo isset($traffic_line_chart_dates) ? json_encode($traffic_line_chart_dates) : array(); ?>,
		      datasets: [{
		        label: '<?php echo __('Visitor From Direct'); ?>',
		        data: <?php echo isset($traffic_direct_link) ? json_encode($traffic_direct_link) : array(); ?>,
		        borderWidth: 2,
		        backgroundColor: sales_chart_bg_color,
		        borderWidth: 3,
		        borderColor: 'rgba(63,82,227,1)',
		        pointBorderWidth: 0,
		        pointBorderColor: 'transparent',
		        pointRadius: 3,
		        pointBackgroundColor: 'transparent',
		        pointHoverBackgroundColor: 'rgba(63,82,227,1)',
		      }]
		    },
		    options: {
		      layout: {
		        padding: {
		          bottom: -1,
		          left: -1
		        }
		      },
		      legend: {
		        display: false
		      },
		      scales: {
		        yAxes: [{
		          gridLines: {
		            display: false,
		            drawBorder: false,
		          },
		          ticks: {
		            beginAtZero: true,
		            display: false
		          }
		        }],
		        xAxes: [{
		          gridLines: {
		            drawBorder: false,
		            display: false,
		          },
		          ticks: {
		            display: false
		          }
		        }]
		      },
		    }
		  });

	  /**
	   * Last 30 Days Returning Vs New User
	   */
	  
	  var thirty_days_camparison_line_chart_data = document.getElementById("thirtydays_new_vs_returning").getContext('2d');

	  var thirty_days_camparison_line_chart_data_label = <?php echo isset($new_vs_returning_dates) ? json_encode($new_vs_returning_dates) : json_encode(array()); ?>;
	  var thirty_days_camparison_line_charta = new Chart(thirty_days_camparison_line_chart_data, {
		    type: 'line',
		    data: {
		      labels: thirty_days_camparison_line_chart_data_label,
		      datasets: [
		      {
		        label: '<?php echo __('Returning User'); ?>',
		        data: <?php echo isset($thirty_days_returning_user) ? json_encode($thirty_days_returning_user) : json_encode(array()); ?>,
		        borderWidth: 2,
		        backgroundColor: 'rgba(254,86,83,.7)',
		        borderWidth: 0,
		        borderColor: 'transparent',
		        pointBorderWidth: 0,
		        pointRadius: 3.5,
		        pointBackgroundColor: 'transparent',
		        pointHoverBackgroundColor: 'rgba(254,86,83,.8)'
		      },
		      {
		        label: '<?php echo __('New User'); ?>',
		        data: <?php echo isset($thirty_days_new_user) ? json_encode($thirty_days_new_user) : json_encode(array())?>,
		        borderWidth: 2,
		        backgroundColor: 'rgba(63,82,227,.8)',
		        borderWidth: 0,
		        borderColor: 'transparent',
		        pointBorderWidth: 0 ,
		        pointRadius: 3.5,
		        pointBackgroundColor: 'transparent',
		        pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
		      }]
		    },
		  options: {
		    legend: {
		      display: true
		    },
		    title: {
		    	display: true,
		    	text: '<?php echo __('Last 30 Days New Vs Returning User'); ?>',
		    	position:'bottom'
		    },
		    scales: {
		      yAxes: [{
		        gridLines: {
		          drawBorder: false,
		          color: '#f2f2f2',
		        },
		        ticks: {
		          beginAtZero: true,
		          //stepSize: stepsize,
		        }
		      }],
		      xAxes: [{
		        gridLines: {
		          display: false,
		          tickMarkLength: 15,
		        },
		        type: 'time',
		           time: {
		               displayFormats: {
		                   quarter: 'MMM YYYY'
		               }
		           }
		      }]
		    },
		  }
	  });


	  //TOP Five Contry
	  
	 $("#products-carousel").owlCarousel({
		   items: 3,
		   margin: 10,
		   autoplay: true,
		   autoplayTimeout: 5000,
		   loop: true,
		   responsive: {
		     0: {
		       items: 2
		     },
		     768: {
		       items: 2
		     },
		     1200: {
		       items: 3
		     }
		   }
	 });


</script>



@endsection










