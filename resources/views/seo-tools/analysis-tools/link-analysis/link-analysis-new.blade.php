{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('New link analyzer'))
@section('content')

<link rel="stylesheet" href="{{ asset('assets/custom-css/fileUploadMutilayout.css') }}">

  
  <section class="section">
    <div class="section-header">
      <h1><i class="fas fa-anchor"></i> {{ __('New link analyzer')}}</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{route("analysis_tools")}}">{{ __("Analysis Tools")}}</a></div>
        <div class="breadcrumb-item"><a href="{{route('link_analysis_index')}}">{{ __("Link Analyzer")}}</a></div>
        <div class="breadcrumb-item">{{ __('New link analyzer')}}</div>
      </div>
    </div>
  </section>
    
  
  <div class="row multi_layout">
  
    <div class="col-12 col-md-4 col-lg-4 collef">
      <div class="card main_card">
        <div class="card-header">
          <h4><i class="fa fa-info-circle"></i> {{ __('Info')}}</h4>
        </div>
        <form  method="POST" enctype="multipart/form-data"  id="new_search_form">
          @csrf
  
  
          <div class="card-body">
  
            <div class="form-group">
              <label class="form-label"> {{ __("URL")}} <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="{{ __("URL") }}" data-content='{{ __("Put your url") }}'><i class='fa fa-info-circle'></i> </a></label>
              <input id="keyword" name="keyword" class="form-control"  />
            </div>
  
          </div>
  
          <div class="card-footer bg-whitesmoke" style="margin-top: 266px!important;">
  
              <button type="button"  id="new_search_button" class="btn btn-primary ">{{ __("Analysis")}}</button>
              <button class="btn btn-secondary btn-md float-right" onclick="goBack('link_analysis/index')" type="button"><i class="fa fa-remove"></i> {{ __('Cancel')}}</button>
            
      
  
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
            <h4> <i class="fas fa-anchor"></i> {{ __('New Link Analyzer')}}</h4>
            
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

    var link_analysis_action = '{{ route('link_analysis_action') }}';  
    var Please_enter_url = '{{ __('Please enter url') }}';
</script>
   
<script src="{{asset('assets/custom-js/analysis-tools/link-analysis.js')}}"></script>   
   

   


@endsection