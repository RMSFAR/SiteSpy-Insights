{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Visitor analysis report'))
@section('content')

<link rel="stylesheet" href="{{ asset('assets/custom-css/analysis-tools/domain-details.css') }}">


<input type="hidden" id="domain_id" value="{{ $id}}"/>

<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-chart-line"></i> {{ __('Visitor analysis report')}}</h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><a href="{{ route("analysis_tools")}}">{{ __("Analysis Tools")}}</a></div>
			<div class="breadcrumb-item active"><a href="{{ route('visitor_analysis')}}">{{ __("Visitor Analytics")}}</a></div>
			<div class="breadcrumb-item">{{ __('Report')}}</div>
		</div>
	</div>

	<div class="section-body">
		<div class="card">

			<div class="card-header">
				<h4><i class="fas fa-globe"></i> {{ "Domain Name"}} -   <span class='red domain_name'></span></h4>
			</div>
			
			<div class="card-body">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="traffic_source-tab" data-toggle="tab" href="#traffic_source" role="tab" aria-controls="traffic_source" aria-selected="true">{{ __('Traffic Source')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="false">{{ __('Overview')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="visitor_type-tab" data-toggle="tab" href="#visitor_type" role="tab" aria-controls="visitor_type" aria-selected="false">{{ __('Visitor Type')}}</a>
					</li>
					<!-- <li class="nav-item">
						<a class="nav-link" id="content_overview-tab" data-toggle="tab" href="#content_overview" role="tab" aria-controls="content_overview" aria-selected="false">{{ __('Content Overview')}}</a>
					</li> -->
					<li class="nav-item">
						<a class="nav-link" id="country_wise_report-tab" data-toggle="tab" href="#country_wise_report" role="tab" aria-controls="country_wise_report" aria-selected="false">{{ __('Country Wise Report')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="browser_report-tab" data-toggle="tab" href="#browser_report" role="tab" aria-controls="browser_report" aria-selected="false">{{ __('Browser Report')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="os_report-tab" data-toggle="tab" href="#os_report" role="tab" aria-controls="os_report" aria-selected="false">{{ __('OS Report')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="device_report-tab" data-toggle="tab" href="#device_report" role="tab" aria-controls="device_report" aria-selected="false">{{ __('Device Report')}}</a>
					</li>
				</ul>
				<div class="tab-content tab-bordered" id="myTab3Content">
					<div class="tab-pane fade show active" id="traffic_source" role="tabpanel" aria-labelledby="traffic_source-tab">
						@include('seo-tools.analysis-tools.visitor.traffic-source')
					</div>
					<div class="tab-pane fade" id="overview" role="tabpanel" aria-labelledby="overview-tab">
						@include('seo-tools.analysis-tools.visitor.overview')
					</div>
					<div class="tab-pane fade" id="visitor_type" role="tabpanel" aria-labelledby="visitor_type-tab">
						@include('seo-tools.analysis-tools.visitor.visitor-type')
					</div>
					<div class="tab-pane fade" id="content_overview" role="tabpanel" aria-labelledby="content_overview-tab">
						@include('seo-tools.analysis-tools.visitor.content-overview')
					</div>
					<div class="tab-pane fade" id="country_wise_report" role="tabpanel" aria-labelledby="country_wise_report-tab">
						@include('seo-tools.analysis-tools.visitor.country-wise-report')
					</div>
					<div class="tab-pane fade" id="browser_report" role="tabpanel" aria-labelledby="browser_report-tab">
						@include('seo-tools.analysis-tools.visitor.browser-report')
					</div>
					<div class="tab-pane fade" id="os_report" role="tabpanel" aria-labelledby="os_report-tab">
						@include('seo-tools.analysis-tools.visitor.os-report')
					</div>
					<div class="tab-pane fade" id="device_report" role="tabpanel" aria-labelledby="device_report-tab">
						@include('seo-tools.analysis-tools.visitor.device-report')
					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<script>
	"use strict";

	var global_lang_procced = '{{ __('Proceed') }}';
	var New_User = '{{ __('New User') }}';
	var Referal = '{{ __('Referal') }}';
	var Sessions = '{{ __('Sessions') }}';
	var Social_Network = '{{ __('Social Network') }}';
	var Search_Engine = '{{ __('Search Engine') }}';
	var Direct_Link = '{{ __('Direct Link') }}';
	var Returning_User = '{{ __('Returning User') }}';
	var Top_five_referrers_in_percentage = '{{ __('Top five referrers in percentage') }}';
	var Visitors_from_different_search_engine = '{{ __('Visitors from different search engine') }}';
	var Visitors_from_different_social_networks = '{{ __('Visitors from different social networks') }}';
	var ajax_get_traffic_source_data = '{{ route('ajax_get_traffic_source_data') }}';
	var ajax_get_individual_browser_data = '{{ route('ajax_get_individual_browser_data') }}';
	var ajax_get_individual_os_data = '{{ route('ajax_get_individual_os_data') }}';
	var ajax_get_individual_device_data = '{{ route('ajax_get_individual_device_data') }}';
	var ajax_get_individual_country_data = '{{ route('ajax_get_individual_country_data') }}';
	var pre_loader = '{{ asset('assets/img/pre-loader/Fancy pants.gif') }}';


</script>

<script src="{{asset('assets/custom-js/analysis-tools/domain-details.js')}}"></script>


<!-- country wise individual data -->
<div id="modal_for_country_report" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<h5 id="new_search_details_title" class="modal-title"><i class="fa fa-binoculars"></i> {{ __('Details Information About')}} <span id="id_for_country_name"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="row"><div class="text-center" id="modal_waiting_country_name"></div></div>

				<div class="row">
					<div class="col-12">
					  <div class="card">
					    <div class="card-header">
					      <h4><i class="far fa-chart-bar"></i> {{ __('Day Wise Sessions Report From')}} <span id="country_name_from_date"></span> to <span id="country_name_to_date"></span></h4>
					    </div>
					    <div class="card-body">
					      <canvas id="country_name_line_chart" height="134"></canvas>
					    </div>
					  </div>
					</div>
				</div>

				<div class="row">
					<div class="col-12">						
						<div class="table-responsive" id="individual_country_data_table">
							
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
			</div>
		</div>
	</div>
</div>
<!-- end of country wise individual data -->

<!-- individual browser report -->
<div id="modal_for_browser_report" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<h5 id="new_search_details_title" class="modal-title"><i class="fa fa-binoculars"></i> {{ __('Details Information About')}} <span id="id_for_browser_name"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
			</div>

			<div class="modal-body" id="browser_details_body">
				<div class="row"><div class="text-center" id="modal_waiting_browser_name"></div></div>
				
				<div class="row">
					<div class="col-12">
					  <div class="card">
					    <div class="card-header">
					      <h4><i class="far fa-chart-bar"></i> {{ __('Day Wise Sessions Report From')}} <span id="browser_name_from_date"></span> to <span id="browser_name_to_date"></span></h4>
					    </div>
					    <div class="card-body">
					      <canvas id="browser_name_line_chart" height="134"></canvas>
					    </div>
					  </div>
					</div>
				</div>

				<div class="row">
					<div class="col-12">						
						<div class="table-responsive" id="individual_browser_data_table">
							
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
			</div>
		</div>
	</div>
</div>
<!-- end of individual browser report -->

<!-- individual os data -->
<div id="modal_for_os_report" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<h5 id="new_search_details_title" class="modal-title"><i class="fa fa-binoculars"></i> {{ __('Details Information About')}} <span id="id_for_os_name"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="row"><div class="text-center" id="modal_waiting_os_name"></div></div>

				<div class="row">
					<div class="col-12">
					  <div class="card">
					    <div class="card-header">
					      <h4><i class="far fa-chart-bar"></i> {{ __('Day Wise Sessions Report From')}} <span id="os_name_from_date"></span> to <span id="os_name_to_date"></span></h4>
					    </div>
					    <div class="card-body">
					      <canvas id="os_name_line_chart" height="134"></canvas>
					    </div>
					  </div>
					</div>
				</div>

			</div>


			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
			</div>
		</div>
	</div>
</div>
<!-- end of individual os data -->

<!-- individual device report -->
<div id="modal_for_device_report" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<h5 id="new_search_details_title" class="modal-title"><i class="fa fa-binoculars"></i> {{ __('Details Information About')}} <span id="id_for_device_name"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="row"><div class="text-center" id="modal_waiting_device_name"></div></div>
				<div class="row">
					<div class="col-12">
					  <div class="card">
					    <div class="card-header">
					      <h4><i class="far fa-chart-bar"></i> {{ __('Day Wise Sessions Report From')}} <span id="device_name_from_date"></span> to <span id="device_name_to_date"></span></h4>
					    </div>
					    <div class="card-body">
					      <canvas id="device_name_line_chart" height="134"></canvas>
					    </div>
					  </div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
			</div>
		</div>
	</div>
</div>
<!-- end of individual device report -->

@endsection

