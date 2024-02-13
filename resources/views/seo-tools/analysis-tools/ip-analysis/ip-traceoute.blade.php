{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Ip traceroute'))
@section('content')

<link rel="stylesheet" href="{{ asset('assets/custom-css/fileUploadMutilayout.css') }}">


<section class="section">
  <div class="section-header">
    <h1><i class="fa fa-map-marker-alt"></i> {{ __('Ip traceroute')}}</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{route('analysis_tools')}}">{{ __("Analysis Tools")}}</a></div>
      <div class="breadcrumb-item">{{ __('Ip traceroute')}}</div>
    </div>
  </div>
</section>
  

<div class="row multi_layout">

  <div class="col-12 col-md-3 col-lg-3 collef">
    <div class="card main_card">
      <div class="card-header">
        <h4><i class="fas fa-info-circle"></i> {{ __('Info')}}</h4>
      </div>
      <form  method="POST" enctype="multipart/form-data"  id="new_search_form">
        @csrf


        <div class="card-body">

          <div class="form-group">
              <label class="form-label"> {{ __("IP Address")}} <code>*</code></label>
              <input id="domain_name" name="domain_name" class="form-control" />
              <small id="passwordHelpBlock" class="form-text text-muted"><a href="{{route('domain_info_index') }}">{{ __('Get Your Domain IP')}}</a></small>
          </div>

        </div>

        <div class="card-footer bg-whitesmoke mt-233">

            <button type="button"  id="new_search_button" class="btn btn-primary ">{{ __("Analysis")}}</button>
            <button class="btn btn-secondary btn-md float-right" onclick="goBack('ip/index')" type="button"><i class="fa fa-remove"></i> {{ __('Cancel')}}</button>
          
    

        </div>

      </form>
    </div>          
  </div>

  <div class="col-12 col-md-9 col-lg-9 colmid">
    <div id="custom_spinner"></div>
    <div id="unique_per">
      
    </div>
    <div id="middle_column_content" style="background: #ffffff!important;">

      <div class="card">
        <div class="card-header">
          <h4> <i class="fas fa-map-marker-alt"></i> {{ __('IP Traceroute')}}</h4>
          
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-6 col-lg-12 bck_clr" id="nodata">

        <div class="empty-state">
          <img class="img-fluid" src="{{asset("assets/img/drawkit/revenue-graph-colour.svg")}}" style="height: 300px" alt="image">
        

        </div>

      </div>
    </div>
  </div>
</div>


<script>
  "use strict";
  var Please_Enter_IP_Address = '{{ __('Please Enter IP Address') }}';
  var traceout_check_data = '{{ route('traceout_check_data') }}';
</script>

<script src="{{asset('assets/custom-js/analysis-tools/ip-traceout.js')}}"></script>


  
@php 
  if(isset($google_api[0]['google_api_key'])) $google_api_key=$google_api[0]['google_api_key'];
  else $google_api_key="AIzaSyBG0sIVBWReW1Q0WGkWO28uGaKWhQp7Q4c";
@endphp
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={{ $google_api_key}}"></script> 

@endsection