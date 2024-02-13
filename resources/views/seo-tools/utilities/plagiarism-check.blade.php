{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Plagiarism check'))
@section('content')


<link rel="stylesheet" href="{{asset('assets/custom-css/fileUploadMutilayout.css')}}">

<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-language"></i> {{ __('Plagiarism check') }}</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{route('utilities') }}">{{ __("Utilities")}}</a></div>
      <div class="breadcrumb-item">{{ __('Plagiarism check') }}</div>
    </div>
  </div>
</section>
  

<div class="row multi_layout">

  <div class="col-12 col-md-6 col-lg-6 collef">
    <div class="card main_card">
      <div class="card-header">
        <h4><i class="fas fa-info-circle"></i> {{ __('Info')}}</h4>
      </div>
      <form enctype="multipart/form-data" method="POST"  id="new_search_form">
        @csrf


        <div class="card-body">

          <div class="form-group">
            <label class="form-label"> {{ __("Plagiarism Check")}} <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="{{ __("Plagiarism Check") }}" data-content='{{ __("put your text or upload text file with contain 500 words(max)") }}'><i class='fa fa-info-circle'></i> </a></label>
            <textarea id="bulk_email" class="form-control" style="width:100%;min-height: 120px;" rows="10"  autofocus></textarea>
          </div>

          <div class="form-group">
                <label> {{ __('Files')}}</label>
                  <div id="file_upload_url" class="form-control">{{ __('Upload')}}</div>
          </div> 
      
        </div>

        <div class="card-footer bg-whitesmoke mt-66">

            <button type="button"  id="new_search_button" class="btn btn-primary "><i class="fa fa-search"></i> {{ __("Search")}}</button>
            <button class="btn btn-secondary btn-md float-right" onclick="goBack('menu_loader/utlities')" type="button"><i class="fa fa-remove"></i> {{ __('Cancel')}}</button>
          
    

        </div>

      </form>
    </div>          
  </div>

  <div class="col-12 col-md-6 col-lg-6 colmid">
    <div id="custom_spinner"></div>
    <div id="unique_per">
      
    </div>
    <div id="middle_column_content" style="background: #ffffff!important;">

      <div class="card">
        <div class="card-header">
          <h4> <i class="fas fa-language"></i> {{ __('Plagiarism Check Results')}}</h4>
          
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
  "use strict" 
  var Please_enter_your_contents = '{{ __('Please enter your contents') }}';
  var you_have_provided_a_blank_string = '{{ __('you have provided a blank string') }}';
  var You_have_entered_too_large_string = '{{ __('You have entered too large string') }}';
  var plagarism_check_action = '{{route("plagarism_check_action")}}'
</script>


<script src="{{asset('assets/custom-js/utilities/plagiarism.js')}}"></script>


@endsection