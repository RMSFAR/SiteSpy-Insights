{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Keyword position analysis'))
@section('content')

<link rel="stylesheet" href="{{ asset('assets/custom-css/fileUploadMutilayout.css') }}">
  
  
  <section class="section">
    <div class="section-header">
      <h1><i class="fa fa-tags"></i> {{ __('Keyword position analysis')}}</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{route("analysis_tools")}}">{{ __("Analysis Tools")}}</a></div>
        <div class="breadcrumb-item"><a href="{{route('keyword_index')}}">{{ __("Keyword Position Analysis")}}</a></div>
        <div class="breadcrumb-item">{{ __('Keyword position analysis')}}</div>
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
  
              </div>
            </div>
            <div class="form-group">
              <label class="form-label"> {{ __("Website URL")}} <code>*</code></label>
              <input id="domain_name" name="domain_name" class="form-control"  />
            </div>
            <div class="form-group">
              <label class="form-label"> {{ __("Keyword")}} <code>*</code></label>
              <input id="keyword" name="keyword" class="form-control"  />
            </div>
            <div class="form-group">
              <label class="form-label"> {{ __("Location")}}</label>
              @php 
              $default_country = config("my_config.country_name") ?? 'bd';
              $select_note_con['']=__("Please select any country");
              $country_name =$select_note_con +get_country_names();
              echo Form::select('country_name',$country_name,$default_country,array('class'=>'form-control select2','id'=>'country_name'));
             @endphp	  
            </div>
            <div class="form-group">
              <label class="form-label"> {{ __("Language")}}</label>
              @php 
              $default_language = config("my_config.language") ?? 'en';
              $select_note['']=__("Please select any language");
              $language_name =$select_note + get_language_list();
              echo Form::select('language_name',$language_name,$default_language,array('class'=>'form-control select2','id'=>'language_name'));
             @endphp	
            </div>
          </div>
  
          <div class="card-footer bg-whitesmoke">
  
              <button type="button"  id="new_search_button" class="btn btn-primary ">{{ __("Analysis")}}</button>
              <button class="btn btn-secondary btn-md float-right" onclick="goBack('keyword/position_keyword')" type="button"><i class="fa fa-remove"></i> {{ __('Cancel')}}</button>
            
      
  
          </div>
  
        </form>
      </div>          
    </div>
  
    <div class="col-12 col-md-8 col-lg-8 colmid">
      <div id="custom_spinner"></div>
      <div id="unique_per">
        
      </div>
      <div id="middle_column_content" style="background: #ffffff!important;padding: 20px;">
  
        <div class="card">
          <div class="card-header">
            <h4> <i class="fas fa-tags"></i> {{ __('Keyword Position Analysis')}}</h4>
            
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
      var Please_enter_keyword = '{{ __('Please enter keyword') }}';
      var Please_enter_website_url = '{{ __('Please enter website url') }}';
      var Please_Select_Search_Engine = '{{ __('Please Select Search Engine') }}';
      var keyword_position_action = '{{ route('keyword_position_action') }}';
</script>
  
<script src="{{asset('assets/custom-js/analysis-tools/keyword-position-new.js')}}"></script> 

  
  


@endsection