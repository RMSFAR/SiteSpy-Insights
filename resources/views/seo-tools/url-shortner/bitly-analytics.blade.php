{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Bitly analytics'))
@section('content')


<section class="section">

	<div class="section-header">
	    <h1>
	    	<i class="far fa-chart-bar"></i>
			{{ __("Bitly Analytics")}} : 
			<a href="{{ $bitly_shortener}}" target="_BLANK">{{ $bitly_shortener}}</a>
	    </h1>
		<div class="section-header-breadcrumb">
	      <div class="breadcrumb-item">
	  
	      </div>
	    </div>
  	</div>

  	<div class="section-body">	

			<div class="row">
			  <div class="col-12 col-lg-12">
			    <div class="card">
			      <div class="card-header">
			        <h4>
			        	{{__("Monthly Click Report")}}
			        	<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="{{__("Monthly Click Report") }}" data-content="{{__("The number of times click this bitly url shortener") }}"><i class='fas fa-info-circle'></i> </a>
			        </h4>
			      </div>
			      <div class="card-body">
			        <canvas id="bitly_click_report" height="200"></canvas>	       
			      </div>
			    </div>
			  </div>
			</div>



			<div class="row">
				<div class="col-12 col-lg-6">
				  <div class="card">
				    <div class="card-header">
				      <h4>
				      	{{__("Clicks: Top 10 Countries")}}
				      	<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="{{__("Clicks: Top 10 Countries") }}" data-content="{{__("The number of times click this bitly url shortener from which country") }}"><i class='fas fa-info-circle'></i> </a>
				      </h4>
				    </div>
				    <div class="card-body">
				      <canvas id="clicks_top_country" height="180"></canvas>	       
				    </div>
				  </div>
				</div>
			  <div class="col-12 col-lg-6">
			    <div class="card">
			      <div class="card-header">
			        <h4>
			        	{{__("Clicks: Top 10 Referring Domains")}}
			        	<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="{{__("Clicks: Top 10 Referring Domains") }}" data-content="{{__("The number of people who have clicked by referring domains.") }}"><i class='fas fa-info-circle'></i> </a>
			        </h4>
			      </div>
			      <div class="card-body">
			        <canvas id="click_referring_domains" height="180"></canvas>	       
			      </div>
			    </div>
			  </div>



			</div>

    </div>

</section>

@php

	//Bitly URL Shortener Clicks Report	
	if (isset($monthly_click_data['link_clicks'])) {			
			$link_clicks = $monthly_click_data['link_clicks'];			
			$link_clicks_date_array = array();
			$link_clicks_array = array();
			$i = 0;
			foreach ($link_clicks as $key => $value) {				
				$link_clicks_date_array[$i]['date'] = date('Y-m-d',strtotime($value['date']));
				$link_clicks_array[$i]['clicks'] = $value['clicks'];
				$i++;
			}
	}

	$link_clicks_date_label = array_column($link_clicks_date_array, 'date');
	$link_clicks_click = array_column($link_clicks_array, 'clicks');

	//Blitly URL Shortener Clicks By Country

	if (isset($monthly_bitly_monthly_countries_data['metrics'])) {
		
		$bitly_ten_countries = $monthly_bitly_monthly_countries_data['metrics'];
		$bitly_ten_countries_array = array();

		$i = 0;
		foreach ($bitly_ten_countries as $key => $value) {

			$bitly_ten_countries_array[$i][$value['value']] = $value['clicks'];
			$i++;
		}		
		$top_ten_country = array_slice($bitly_ten_countries_array, 0, 10, true);

		$final_data_country = array();

		foreach ($top_ten_country as $key1 => $value1) {
			
			foreach ($value1 as $key2 => $value2) {
				$final_data_country['country'][$key2]=$value2;
			}
		}
	}


	// Bitly URL Shortener By Referring Domains


	if (isset($monthly_bitly_montly_referring_domains_data['metrics'])) {

			$bitly_montly_referring_domains = $monthly_bitly_montly_referring_domains_data['metrics'];
			$bitly_montly_referring_domains_data = array();
			$i = 0;
			foreach ($bitly_montly_referring_domains as $key => $value) {
				$bitly_montly_referring_domains_data[$i][$value['value']] = $value['clicks'];
				$i++;
			}

			$top_ten_domain = array_slice($bitly_montly_referring_domains_data, 0, 10, true);
			$final_data_domain = array();
		

			foreach ($top_ten_domain as $key1 => $value1) {
				
				foreach ($value1 as $key2 => $value2) {
					$final_data_domain['domains'][$key2]=$value2;
				}
			}

	}

@endphp

<script>
	"use strict";

	var link_clicks_date_label = '<?php echo json_encode($link_clicks_date_label)?>' ;
	var link_clicks_click = '<?php echo json_encode($link_clicks_click)?>' ;
	var Click = '{{ __("Clicks") }}';
	var final_data_country = '<?php echo json_encode(array_values(isset($final_data_country['country']) ? $final_data_country['country'] : []))?>' ;
	var label_final_data_country = '<?php echo json_encode(array_keys(isset($final_data_country['country']) ? $final_data_country['country'] : []))?>' ;
	var final_data_domain = '<?php echo json_encode(array_values(isset($final_data_domain['domains']) ? $final_data_domain['domains'] : []))?>' ;
	var label_final_data_domain = '<?php echo json_encode(array_keys(isset($final_data_domain['domains']) ? $final_data_domain['domains'] : []))?>' ;
</script>


<script src="{{asset('assets/custom-js/url-shortner/bitly-analytics.js')}}"></script>


@endsection