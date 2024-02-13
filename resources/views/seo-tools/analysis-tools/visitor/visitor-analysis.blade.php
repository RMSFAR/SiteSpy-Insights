{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Visitor analysis'))

@section('content')

<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fa fa-chart-line"></i> {{ __('Visitor analysis')}}</h1>
    <div class="section-header-button">
     <a class="btn btn-primary add_domain_modal" href="#">
        <i class="fa fa-plus-circle"></i> {{ __("Add new domain")}}
     </a> 
    </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{route("analysis_tools")}}">{{ __("Analysis Tools")}}</a></div>
      <div class="breadcrumb-item">{{ __('Visitor analysis')}}</div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">

          <div class="card-body data-card">
            {{-- {{ $this->session->flashdata('dashboard_msg')}} <br> --}}
          	<div class="row">
          		<div class="col-md-6 col-12">
              	<div class="input-group mb-3 float-left" id="searchbox">
    	          	
                    <input type="text" class="form-control" id="domain_name" name="domain_name" autofocus placeholder="{{ __('Domain Name')}}" aria-label="" aria-describedby="basic-addon2">
  	          	  	<div class="input-group-append">
  	          	    	<button class="btn btn-primary" id="search_submit" title="{{ __('Search')}}" type="button"><i class="fas fa-search"></i> <span class="d-none d-sm-inline">{{ __('Search')}}</span></button>
  	      	 	 	    </div>
            		</div>
          		</div>
          	</div>
            <div class="table-responsive2">
            	<table class="table table-bordered" id="mytable">
                <thead>
                	<tr>
                		<th>#</th>      
                		<th style="vertical-align:middle;width:20px">
                			<input class="regular-checkbox" id="datatableSelectAllRows" type="checkbox"/><label for="datatableSelectAllRows"></label>        
                		</th>
        						<th>{{ __("Domin Name")}}</th>      
        						<th>{{ __("Domin Code")}}</th>
        						<th>{{ __("JavaScript Code")}}</th>
        						<th>{{ __("Actions")}}</th>
                	</tr>
                </thead>
                <tbody>
                </tbody>
            	</table>
            </div>             
          </div>
        </div>
      </div>
    </div>
    
  </div>
</section> 

<script>

  "use strict";

  var Are_you_sure_about_deleting_this_domain = '{{ __("Are you sure about deleting this domain from visitor analysis? All the collected data for this domain will also be deleted.") }}';
  var Are_you_sure_about_deleting_data_except_last_30 = '{{ __('Are you sure about deleting data except last 30 days for this domain?') }}';
  var Domain_and_corresponding_data_has_been_deleted_successfully = '{{ __('Domain and corresponding data has been deleted successfully.') }}';
  var No_Domain_is_found_for_this_user_with_this_ID = '{{ __('No Domain is found for this user with this ID.') }}';
  var Data_except_last_30_days_has_been_deleted_successfully = '{{ __('Data except last 30 days has been deleted successfully.') }}';
  var Do_you_want_to_remove_this_domain_showing_from_your_dashboard = '{{ __('Do you want to remove this domain showing from your dashboard?') }}';
  var Do_you_want_to_remove_this_domain_remove_from_your_dashboard = '{{ __('Do you want to remove this domain remove from your dashboard?') }}';
  var domain_list_visitor_data = '{{ route('domain_list_visitor_data') }}';
  var add_domain_action = '{{ route('add_domain_action') }}';
  var ajax_delete_domain = '{{ route('ajax_delete_domain') }}';
  var get_js_code = '{{ route('get_js_code') }}';
  var display_in_dashboard = '{{ route('display_in_dashboard') }}';
  var ajax_delete_last_30_days_data = '{{ route('ajax_delete_last_30_days_data') }}';

</script>

<script src="{{asset('assets/custom-js/analysis-tools/visitor-index.js')}}"></script>

<div class="modal fade" id="add_domain_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus-circle"></i> {{ __('Add a domain for visitor analysis.')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <div class="input-group mb-3">
                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-signature"></i></div></div>
                <input type="text" class="form-control" placeholder="{{ __('Write or Paste Domain Name here')}}" id="domain_name_add" name="domain_name_add">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="button" id="add_domain"><i class="fas fa-plus-circle"></i> {{ __('Add')}}</button>
                </div>
              </div>
            </div>
          </div>
        </div> 
        <div class="row">
          <div class="col-12" id="analytic_code">
            
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="get_js_code" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center"><i class="fas fa-code"></i> {{ __("JS Code")}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body" id="get_js_code_modal_body">                
              
            </div>
        </div>
    </div>
</div>

@endsection