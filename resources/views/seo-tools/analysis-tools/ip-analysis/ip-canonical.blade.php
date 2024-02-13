{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Ip canonical check'))
@section('content')

<link rel="stylesheet" href="{{ asset('assets/custom-css/fileUploadMutilayout.css') }}">


<section class="section">
  <div class="section-header">
    <h1><i class="fa fa-map-marker-alt"></i> {{ __('Ip canonical check')}}</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{route('analysis_tools')}}">{{ __("Analysis Tools")}}</a></div>
      <div class="breadcrumb-item">{{ __('Ip canonical check')}}</div>
    </div>
  </div>
</section>
  

<div class="row multi_layout">

  <div class="col-12 col-md-5 col-lg-5 collef">
    <div class="card main_card">
      <div class="card-header">
        <h4><i class="fas fa-info-circle"></i> {{ __('Info')}}</h4>
      </div>
      <form  method="POST" enctype="multipart/form-data"  id="new_search_form">
        @csrf


        <div class="card-body">

          <div class="form-group">
              <label class="form-label"> {{ __("Domain")}} <code>*</code> <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="" data-content="Put your domain names comma / new line separated" data-original-title="Domain"><i class="fa fa-info-circle"></i> </a></label>
             
              <textarea id="domain_name" name="domain_name" class="form-control" style="width:100%;min-height: 140px;" rows="10"></textarea>
          </div>

        </div>

        <div class="card-footer bg-whitesmoke mt-136">

            <button type="button"  id="new_search_button" class="btn btn-primary ">{{ __("Analysis")}}</button>
            <button class="btn btn-secondary btn-md float-right" onclick="goBack('ip/index')" type="button"><i class="fa fa-remove"></i> {{ __('Cancel')}}</button>
          
    

        </div>

      </form>
    </div>          
  </div>

  <div class="col-12 col-md-7 col-lg-7 colmid">
    <div id="custom_spinner"></div>
    <div id="unique_per">
      
    </div>
    <div id="middle_column_content" style="background: #ffffff!important;">

      <div class="card">
        <div class="card-header">
          <h4> <i class="fas fa-map-marker-alt"></i> {{ __('IP Canonical Check')}}</h4>
          
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
  var Please_Enter_Domain_Name = '{{ __('Please Enter Domain Name') }}';
  var ip_canonical_action = '{{ route('ip_canonical_action') }}';
</script>

<script src="{{asset('assets/custom-js/analysis-tools/ip-canonical.js')}}"></script>



@endsection