{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Analytics settings'))
@section('content')
<section class="section section_custom">
    <div class="section-header">
      <h1><i class="fas fa-chart-pie"></i> <?php echo __('Analytics settings'); ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><?php echo __("System"); ?></div>
        <div class="breadcrumb-item active"><a href="{{route('settings')}}"><?php echo __("Settings"); ?></a></div>
        <div class="breadcrumb-item"><?php echo __('Analytics settings'); ?></div>
      </div>
    </div>
    @include('shared.message')
 
    <div class="section-body">
      <div class="row">
        <div class="col-12">
            <form action="{{route("analytics_settings_action")}}" method="POST">
            @csrf
            <div class="card">
              <div class="card-body">              
                  <div class="form-group">
                       <label class="col-xs-12" for=""><?php echo __("Paste Facebook Pixel Code");?> (<?php echo __("Inside Script Tag");?>)
                       </label>
                       <div class="col-xs-12">
                           <textarea name="pixel_code" class="codeeditor">@include('shared.fb-px')</textarea>        
                           @if ($errors->has('pixel_code'))
                           <span class="text-danger">{{ $errors->first('pixel_code') }}</span>
                           @endif
                       </div>
                  </div>
  
                  <div class="form-group">
                       <label class="col-xs-12" for=""><?php echo __("Paste Google Analytics Code");?> (<?php echo __("Inside Script Tag");?>)
                       </label>
                       <div class="col-xs-12">
                           <textarea name="google_code" class="codeeditor">@include('shared.google-code')</textarea>        
                           @if ($errors->has('google_code'))
                           <span class="text-danger">{{ $errors->first('google_code') }}</span>
                           @endif
                       </div>
                  </div>
              </div>
  
              <div class="card-footer bg-whitesmoke">
                <button class="btn btn-primary btn-lg" id="save-btn" type="submit"><i class="fas fa-save"></i> <?php echo __("Save");?></button>
                <button class="btn btn-secondary btn-lg float-right" onclick='goBack("admin/settings")' type="button"><i class="fa fa-remove"></i>  <?php echo __("Cancel");?></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  
@endsection
