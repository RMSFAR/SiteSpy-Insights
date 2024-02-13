{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('New shortener'))
@section('content')

<link rel="stylesheet" href="{{asset('assets/custom-css/url-shortner/rebrandly-shortner.css')}}">

<section class="section">
  <div class="section-header">
    <h1><i class="fa fa-cut"></i> {{ __('New shortener')}}</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{route('url_shortner')}}">{{ __('URL Shortner')}}</a></div>
      <div class="breadcrumb-item"><a href="{{route('rebrandly_shortener_index') }}">{{ __("Rebrandly URL Shortener")}}</a></div>
      <div class="breadcrumb-item">{{ __('New shortener')}}</div>
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
              <label class="form-label"> {{ __("Long URL")}} <code>*</code> <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="{{ __("Long URL") }}" data-content='{{ __("The destination URL you want your branded short link to point to") }}'><i class='fa fa-info-circle'></i> </a></label>
              <input id="long_url" name="long_url" class="form-control" />
            </div>
        

           <div class="form-group">
            <label class="form-label"> {{ __("title")}} <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="{{ __("title") }}" data-content='{{ __("A title you assign to the branded short link in order to remember what's behind it") }}'><i class='fa fa-info-circle'></i> </a></label>
            <input id="title" name="title" class="form-control" />
           </div>

        </div>

        <div class="card-footer bg-whitesmoke mt-140">

            <button type="button"  id="new_search_button" class="btn btn-primary ">{{ __("Shortener")}}</button>
            <button class="btn btn-secondary btn-md float-right" onclick="goBack('url_shortner/rebrandly')" type="button"><i class="fa fa-remove"></i> {{ __('Cancel')}}</button>
          
    

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
          <h4> <i class="fas fa-cut"></i> {{ __('Rabrandly URL Shortener')}}</h4>
          
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
  "use strict" 
  var Please_enter_long_url = '{{ __('Please enter long url') }}';
  var rebrandly_shortener_action = '{{ route("rebrandly_shortener_action") }}';

</script>


<script src="{{asset('assets/custom-js/url-shortner/rebrandly-shortner.js')}}"></script>


@endsection

