{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Metatag generator'))
@section('content')

<link rel="stylesheet" href="{{asset('assets/custom-css/fileUploadMutilayout.css')}}">


<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-tags"></i> {{ __('Metatag generator')}}</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{route('utilities') }}">{{ __("Utilities")}}</a></div>
      <div class="breadcrumb-item">{{ __('Metatag generator')}}</div>
    </div>
  </div>
</section>
  

<div class="row multi_layout">

  <div class="col-12 col-md-4 col-lg-4 collef">
    <div class="card main_card">
        

        <div class="card-header">
          <h4><i class="fas fa-tags"></i> {{ __('Metatag Generator List')}}</h4>
        </div>
        <div class="card-body">

          <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="google_check_box" value="1" name="google_check_box">
                <label class="custom-control-label" for="google_check_box">{{ __('Google')}}</label>
              </div>
          </div>
         
          <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="facebook_check_box" value="1" name="facebook_check_box">
                <label class="custom-control-label" for="facebook_check_box">{{ __('Facebook')}}</label>
              </div>
          </div>
        
        <div class="form-group">
          <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="twiter_check_box" value="1" name="twiter_check_box">
              <label class="custom-control-label" for="twiter_check_box">{{ __('Twitter')}}</label>
            </div>
        </div>

        </div>

        <div class="card-footer bg-whitesmoke mt-66">

            <button type="button"  id="new_search_button" class="btn btn-primary "><i class="fa fa-code"></i> {{ __("Generate")}}</button>
            
            <button class="btn btn-secondary btn-md float-right" onclick="goBack('menu_loader/utlities')" type="button"><i class="fa fa-remove"></i> {{ __('Cancel')}}</button>

    

        </div>
    </div>          
  </div>

  <div class="col-12 col-md-8 col-lg-8 colmid">
    <div id="custom_spinner"></div>
    <div id="middle_column_content" style="background: #ffffff!important;">

      <div class="card">
        <div class="card-header">
          <h4> <i class="fas fa-tags"></i> {{ __('Metatag Forms')}}</h4>

        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-6 col-lg-12 bck_clr" id="nodata">

       <div class="empty-state" id="show_hide">
          <img class="img-fluid" src="{{asset("assets/img/drawkit/revenue-graph-colour.svg")}}" style="height: 300px"  alt="image">
        </div> 

        <div class="card" id="google_block" style="display: none;">
          <div class="card-header">
            <h4>{{ __('Google Metatag Form')}}</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label>{{ __('Description')}}</label>
              <textarea class="form-control" id="google_description" name="google_description">{{  set_value('google_description')}}</textarea>
              @if ($errors->has('google_description'))
									<code class="text-danger">{{ $errors->first('google_description') }}</code>
									@endif
            </div>
            <div class="form-group">
              <label>{{ __('Keywords')}}</label>
                <input type="text" name="google_keywords" class="form-control" id="google_keywords" value="{{  set_value('google_keywords')}}">
                @if ($errors->has('google_keywords'))
									<code class="text-danger">{{ $errors->first('google_keywords') }}</code>
									@endif
            </div>            

            <div class="form-group">
              <label>{{ __('Author')}}</label>
                <input type="text" name="google_author" class="form-control" id="google_author" value="{{  set_value('google_author')}}">
                @if ($errors->has('google_author'))
									<code class="text-danger">{{ $errors->first('google_author') }}</code>
									@endif
            </div>            
            <div class="form-group">
              <label>{{ __('Copyright')}}</label>
                <input type="text" name="google_copyright" class="form-control" id="google_copyright" value="{{  set_value('google_copyright')}}">
                @if ($errors->has('google_copyright'))
									<code class="text-danger">{{ $errors->first('google_copyright') }}</code>
									@endif
            </div>           
             <div class="form-group">
              <label>{{ __('Application Name')}}</label>
                <input type="text" name="google_application_name" class="form-control" id="google_application_name" value="{{  set_value('google_application_name')}}">
                @if ($errors->has('google_application_name'))
									<code class="text-danger">{{ $errors->first('google_application_name') }}</code>
									@endif
            </div>


          </div>
        </div>


        <div class="card" id="facebook_block" style="display: none;">
          <div class="card-header">
            <h4>{{ __('Facebook Metatag Form')}}</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label>{{ __('Title')}}</label>
                <input type="text" name="facebook_title" class="form-control" id="facebook_title" value="{{  set_value('facebook_title')}}">
                @if ($errors->has('facebook_title'))
									<code class="text-danger">{{ $errors->first('facebook_title') }}</code>
									@endif
            </div> 
            <div class="form-group">
              <label>{{ __('Description')}}</label>
              <textarea class="form-control" id="facebook_description" name="facebook_description">{{  set_value('facebook_description')}}</textarea>
              @if ($errors->has('facebook_description'))
									<code class="text-danger">{{ $errors->first('facebook_description') }}</code>
									@endif
            </div>
           

            <div class="form-group">
              <label>{{ __('Type')}}</label>
                <input type="text" name="facebook_type" class="form-control" id="facebook_type" value="{{  set_value('facebook_type')}}">
                @if ($errors->has('facebook_type'))
									<code class="text-danger">{{ $errors->first('facebook_type') }}</code>
									@endif
            </div>            
            <div class="form-group">
              <label>{{ __('Image URL')}}</label>
                <input type="text" name="facebook_image" class="form-control" id="facebook_image" value="{{  set_value('facebook_image')}}">
                @if ($errors->has('facebook_image'))
									<code class="text-danger">{{ $errors->first('facebook_image') }}</code>
									@endif
            </div>           
             <div class="form-group">
              <label>{{ __('Page URL')}}</label>
                <input type="text" name="facebook_url" class="form-control" id="facebook_url" value="{{  set_value('facebook_url')}}">
                @if ($errors->has('facebook_url'))
									<code class="text-danger">{{ $errors->first('facebook_url') }}</code>
									@endif
            </div>             
            <div class="form-group">
              <label>{{ __('Facebook App ID')}}</label>
                <input type="text" name="facebook_app_id" class="form-control" id="facebook_app_id" value="{{  set_value('facebook_app_id')}}">
                @if ($errors->has('facebook_app_id'))
									<code class="text-danger">{{ $errors->first('facebook_app_id') }}</code>
									@endif
            </div>

            <div class="form-group">
              <label>{{ __('Localization')}}</label>
                <input type="text" name="facebook_localization" class="form-control" id="facebook_localization" value="{{  set_value('facebook_localization')}}">
                @if ($errors->has('facebook_localization'))
									<code class="text-danger">{{ $errors->first('facebook_localization') }}</code>
									@endif
            </div>


          </div>
        </div>
        
        <div class="card" id="twiter_block" style="display: none;">
          <div class="card-header">
            <h4>{{ __('Twitter Metatag Form')}}</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label>{{ __('Card')}}</label>
                <input type="text" name="twiter_card" class="form-control" id="twiter_card" value="{{  set_value('twiter_card')}}">
                @if ($errors->has('twiter_card'))
									<code class="text-danger">{{ $errors->first('twiter_card') }}</code>
									@endif
            </div>            
            <div class="form-group">
              <label>{{ __('Title')}}</label>
                <input type="text" name="twiter_title" class="form-control" id="twiter_title" value="{{  set_value('twiter_title')}}">
                @if ($errors->has('twiter_title'))
									<code class="text-danger">{{ $errors->first('twiter_title') }}</code>
									@endif
            </div>            
            <div class="form-group">
              <label>{{ __('Description')}}</label>
              <textarea class="form-control" id="twiter_description" name="twiter_description">{{  set_value('twiter_description')}}</textarea>
              @if ($errors->has('twiter_description'))
									<code class="text-danger">{{ $errors->first('twiter_description') }}</code>
									@endif
            </div>

       
             <div class="form-group">
              <label>{{ __('Image URL')}}</label>
                <input type="text" name="twiter_image" class="form-control" id="twiter_image" value="{{  set_value('twiter_image')}}">
                @if ($errors->has('twiter_image '))
									<code class="text-danger">{{ $errors->first('twiter_image ') }}</code>
									@endif
            </div>


          </div>
        </div>


      </div>
    </div>
  </div>
</div>

@php
    $id = Auth::user()->id;    
    $time = date("Ymd"); 
    $link = asset('download/metatag/metatag_'.$id.'_'.$time.'.txt');

@endphp

<script>
  "use strict" 
  var meta_tag_action = '{{ route('meta_tag_action') }}';
  var link = '{{$link}}';
  var download= '{{ __('download') }}';
  var Your_file_is_ready_download = '{{ __('Your file is ready download') }}';
  var One_or_more_required_fields_are_missing = '{{ __('One or more required fields are missing') }}';
</script>

<script src="{{asset('assets/custom-js/utilities/metatag.js')}}"></script>



<div class="modal fade show" id="set_auto_comment_templete_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background: #fefefe;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-tags"></i> {{ __('Metatag Generated')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <code aria-hidden="true">×</code>
        </button>
      </div>
      
      <div class="modal-body text-center" id="unique_email_download_div"> 
       
      </div>
      
    </div>
  </div>
</div>


@endsection