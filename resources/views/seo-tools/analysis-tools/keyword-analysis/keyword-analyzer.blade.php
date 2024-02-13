{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Keyword analyzer'))
@section('content')

<link rel="stylesheet" href="{{ asset('assets/custom-css/fileUploadMutilayout.css') }}">



<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-tags"></i> {{ __('Keyword analyzer')}}</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{route('analysis_tools')}}">{{ __("Analysis Tools")}}</a></div>
      <div class="breadcrumb-item">{{ __('Keyword analyzer')}}</div>
    </div>
  </div>
</section>
  

<div class="row multi_layout">

  <div class="col-12 col-md-4 col-lg-4 collef">
    <div class="card main_card">
      <div class="card-header">
        <h4><i class="fas fa-info-circle"></i> {{ __('Info')}}</h4>
      </div>
      <form  method="POST" enctype="multipart/form-data"  id="new_search_form">
        @csrf


        <div class="card-body">
          <div class="form-group">
            <label class="form-label"> {{ __("Keyword Analyzer")}} <code>*</code></label>
            <input id="keyword_domain_name" name="keyword_domain_name" class="form-control" placeholder="{{ __('Domain Name')}}"  />
          </div>
        </div>

        <div class="card-footer bg-whitesmoke mt-232">

            <button type="button"  id="new_search_button" class="btn btn-primary "> {{ __("Analyze")}}</button>
            <button class="btn btn-secondary btn-md float-right" onclick="goBack('keyword/keyword_analyzer')" type="button"><i class="fa fa-remove"></i> {{ __('Cancel')}}</button>
          
    

        </div>

      </form>
    </div>          
  </div>

  <div class="col-12 col-md-8 col-lg-8 colmid">
    <div id="custom_spinner"></div>
    <div id="middle_column_content" style="background: #ffffff!important;padding: 20px;">

      <div class="card">
        <div class="card-header">
          <h4> <i class="fas fa-tags"></i> {{ __('Keyword Analyzer Results')}}</h4>
          
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
  var keyword_analyzer_data = '{{ route('keyword_analyzer_data') }}';
</script>



<script src="{{asset('assets/custom-js/analysis-tools/keyword-analyzer.js')}}"></script>



@endsection