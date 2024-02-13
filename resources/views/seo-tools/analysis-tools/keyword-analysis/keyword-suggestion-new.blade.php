{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Keyword auto suggestion'))
@section('content')

<link rel="stylesheet" href="{{ asset('assets/custom-css/fileUploadMutilayout.css') }}">

  
  <section class="section">
    <div class="section-header">
      <h1><i class="fa fa-tags"></i> {{ __('Keyword auto suggestion')}}</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{route("analysis_tools")}}">{{ __("Analysis Tools")}}</a></div>
        <div class="breadcrumb-item"><a href="{{route('keyword_suggestion') }}">{{ __("Keyword Auto Suggestion")}}</a></div>
        <div class="breadcrumb-item">{{ __('Keyword auto suggestion')}}</div>
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
                <div class="selectgroup selectgroup-pills">
                  <label class="selectgroup-item" for="keyword_google">
                    <input type="checkbox" name="keyword_google" value="1" id="keyword_google" class="selectgroup-input" checked>
                    <span class="selectgroup-button">{{ __('Google')}}</span>
                  </label>
                  <label class="selectgroup-item" for="keyword_bing">
                    <input type="checkbox" name="keyword_bing" value="1" id="keyword_bing" class="selectgroup-input" checked>
                    <span class="selectgroup-button">{{ __('Bing')}}</span>
                  </label>
                  <label class="selectgroup-item" for="keyword_yahoo">
                    <input type="checkbox" name="keyword_yahoo" value="1" id="keyword_yahoo" class="selectgroup-input" checked>
                    <span class="selectgroup-button">{{ __('Yahoo')}}</span>
                  </label>
                  <label class="selectgroup-item" for="keyword_wiki">
                    <input type="checkbox" name="keyword_wiki" value="1" id="keyword_wiki" class="selectgroup-input" checked>
                    <span class="selectgroup-button">{{ __('Wiki')}}</span>
                  </label>                
                  <label class="selectgroup-item" for="keyword_amazon">
                    <input type="checkbox" name="keyword_amazon" value="1" id="keyword_amazon" class="selectgroup-input" checked>
                    <span class="selectgroup-button">{{ __('Amazon')}}</span>
                  </label>
  
              </div>
            </div>
            <div class="form-group">
              <label class="form-label"> {{ __("Keyword")}} <code>*</code></label>
              <input id="keyword" name="keyword" class="form-control"  />
            </div>
  
          </div>
  
          <div class="card-footer bg-whitesmoke" style="margin-top: 122px!important;">
  
              <button type="button"  id="new_search_button" class="btn btn-primary ">{{ __("Analysis")}}</button>
              <button class="btn btn-secondary btn-md float-right" onclick="goBack('keyword/auto_keyword')" type="button"><i class="fa fa-remove"></i> {{ __('Cancel')}}</button>
            
      
  
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
            <h4> <i class="fas fa-tags"></i> {{ __('Keyword Auto Suggestion')}}</h4>
            
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
    
    var keyword_suggestion_action = '{{ route('keyword_suggestion_action') }}';
    var Please_Select_Search_Engine = '{{ __('Please Select Search Engine') }}';
    var Please_enter_keyword = '{{ __('Please enter keyword') }}';
</script>

<script src="{{asset('assets/custom-js/analysis-tools/keyword-suggestion-new.js')}}"></script>
   


@endsection