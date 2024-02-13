{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Search engine'))

@section('content')

<link rel="stylesheet" href="{{ asset('assets/custom-css/fileUploadMutilayout.css') }}">
  
  
  
<section class="section">
    <div class="section-header">
        <h1><i class="fa fa-trophy"></i> {{ __('Search engine')}}</h1>
        <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{route("analysis_tools")}}">{{ __("Analysis Tools")}}</a></div>
        <div class="breadcrumb-item"><a href="{{route('search_engine_index')}}">{{ __("Search Engine Analysis")}}</a></div>
        <div class="breadcrumb-item">{{ __('Search engine')}}</div>
        </div>
    </div>
</section>
    
  
<div class="row multi_layout">
  
    <div class="col-12 col-md-5 col-lg-5 collef">
      <div class="card main_card">
        <div class="card-header">
          <h4><i class="fa fa-info-circle"></i> {{ __('Info')}}</h4>
        </div>
        <form  method="POST" enctype="multipart/form-data"  id="new_search_form">
          @csrf
         <div class="card-body">
              <div class="form-group">
                <div class="selectgroup selectgroup-pills">
                  <label class="selectgroup-item" for="google_index">
                    <input type="checkbox" name="google_index" value="1" id="google_index" class="selectgroup-input" checked>
                    <span class="selectgroup-button">{{ __('Google')}}</span>
                  </label>
                  <label class="selectgroup-item" for="bing_index">
                    <input type="checkbox" name="bing_index" value="1" id="bing_index" class="selectgroup-input" checked>
                    <span class="selectgroup-button">{{ __('Bing')}}</span>
                  </label>
                  <label class="selectgroup-item" for="yahoo_index">
                    <input type="checkbox" name="yahoo_index" value="1" id="yahoo_index" class="selectgroup-input" checked>
                    <span class="selectgroup-button">{{ __('Yahoo')}}</span>
                  </label>
  
              </div>
            </div>
            <div class="form-group">
              <label class="form-label"> {{ __("Domain")}} <code>*</code> <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="{{ __("Domain") }}" data-content='{{ __("Put your domain names or upload text/csv file - comma / new line separated") }}'><i class='fa fa-info-circle'></i> </a></label>
             
              <textarea id="domain_name" name="domain_name" class="form-control" style="width:100%;min-height: 140px;" rows="10"></textarea>
            </div>
  
            <div class="form-group">
                  <label> {{ __('Files')}} <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="{{ __("Files") }}" data-content='{{ __("Put your domain names or upload text/csv file - comma / new line separated") }}'><i class='fa fa-info-circle'></i> </a></label>
                    <div id="file_upload_url" class="form-control">{{ __('Upload')}}</div>
            </div> 
  
          </div>
  
          <div class="card-footer bg-whitesmoke mt-42">
  
              <button type="button"  id="new_search_button" class="btn btn-primary ">{{ __("Analysis")}}</button>
              <button class="btn btn-secondary btn-md float-right" onclick="goBack('search_engine_index')" type="button"><i class="fa fa-remove"></i> {{ __('Cancel')}}</button>
            
      
  
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
            <h4> <i class="fas fa-trophy"></i> {{ __('Search Engine Index')}}</h4>
            
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
  var Please_Check_Any_Search_Engine = '{{ __('Please Check Any Search Engine') }}';
  var read_after_delete_csv_txt = '{{ route("read_sengine_after_delete_csv_txt") }}';
  var read_text_csv_file_backlink = '{{ route("read_sengine_text_csv_file_backlink") }}';
  var search_engine_index_action = '{{ route("search_engine_index_action") }}';
</script>

    
<script src="{{asset('assets/custom-js/analysis-tools/search-engine.js')}}"></script>   

    

@endsection


