{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Virustotal scan'))
@section('content')

<link rel="stylesheet" href="{{ asset('assets/custom-css/fileUploadMutilayout.css') }}">

<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-shield-alt"></i> {{__('Virustotal scan')}}</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{route('security_tools')}}">{{__("Security Tools")}}</a></div>
      <div class="breadcrumb-item"><a href="{{route('virus_index')}}">{{__("VirusTotal Scan")}}</a></div>
      <div class="breadcrumb-item">{{__('Virustotal scan')}}</div>
    </div>
  </div>
</section>


<div class="row multi_layout">

  <div class="col-12 col-md-4 col-lg-4 collef">
    <div class="card main_card">
      <div class="card-header">
        <h4><i class="fas fa-info-circle"></i> {{__('Info')}}</h4>
      </div>
      <form  method="POST" enctype="multipart/form-data"  id="new_search_form">
        @csrf


        <div class="card-body">

          <div class="form-group">
            <label class="form-label"> {{__("Domain")}} <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="{{__("Domain")}}" data-content='{{__("Put your domain name")}}'><i class='fa fa-info-circle'></i> </a></label>
            <input name="domain_name" id="domain_name" value="" class="form-control" type="text">
          </div>

      
        </div>

        <div class="card-footer bg-whitesmoke mt-240">

            <button type="button"  id="new_search_button" class="btn btn-primary "><i class="fa fa-search"></i> {{__("Search")}}</button>
            <button class="btn btn-secondary btn-md float-right" onclick="goBack('security_tools/virus_index')" type="button"><i class="fa fa-remove"></i> {{__('Cancel')}}</button>
          
    

        </div>

      </form>
    </div>          
  </div>

  <div class="col-12 col-md-8 col-lg-8 colmid">
    <div id="custom_spinner"></div>
    <div id="unique_per">
      
    </div>
    <div id="middle_column_content" style="background: #ffffff!important;">

      <div class="card">
        <div class="card-header">
          <h4> <i class="fas fa-shield-alt"></i> {{__('VirusTotal Scan Results')}}</h4>
          
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-6 col-lg-12 bck_clr" id="nodata">

        <div class="empty-state">
          <img class="img-fluid" src="{{asset("assets/img/drawkit/revenue-graph-colour.svg")}}" style="height: 300px" src=" " alt="image">
        

        </div>

      </div>
    </div>
  </div>
</div>

<script>
  "use strict";
  var base_url='{{url('/')}}';
  var Please_enter_your_domain_name_first='{{__("Please enter your domain name first")}}';
  var virus_total_scan_action='{{route("virus_total_scan_action")}}';
</script>

<script src="{{asset('assets/custom-js/security-tools/virus-scan.js')}}"></script>


@endsection