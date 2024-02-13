{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Advertisement settings'))
@section('content')

<section class="section section_custom">
    <div class="section-header">
      <h1><i class="fab fa-adversal"></i> <?php echo __('Advertisement settings'); ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><?php echo __("System"); ?></div>
        <div class="breadcrumb-item active"><a href="{{route('settings')}}"><?php echo __("Settings"); ?></a></div>
        <div class="breadcrumb-item"><?php echo __('Advertisement settings'); ?></div>
      </div>
    </div>
  
    @include('shared.message')

  
    <?php 
  if(array_key_exists(0,$config_data))
  $section1_html=$config_data[0]->section1_html; 
  else $section1_html="";

  if(array_key_exists(0,$config_data))
  $section1_html_mobile=$config_data[0]->section1_html_mobile; 
  else $section1_html_mobile="";;

  if(array_key_exists(0,$config_data))
  $section2_html=$config_data[0]->section2_html; 
  else $section2_html="";;

  if(array_key_exists(0,$config_data))
  $section3_html=$config_data[0]->section3_html; 
  else $section3_html="";;

  if(array_key_exists(0,$config_data))
  $section4_html=$config_data[0]->section4_html; 
  else $section4_html="";

  if(array_key_exists(0,$config_data))
  $status=$config_data[0]->status; 
  else $status="1";

  if($status==0) $class="disabled";
  else $class="";


    $placeholder=htmlspecialchars('<img src="http://yoursite.com/images/sample.png">');
    ?>
  
    <div class="section-body">
      <div class="row">
        <div class="col-12">
            <form action="{{route("advertisement_settings_action")}}" method="POST">
              @csrf
              <div class="card">
                <div class="card-body">
                    <div class="form-group">
                      <label for="force_https" ><i class="fas fa-eye"></i> <?php echo __('Display/Hide Advertisement');?>?</label>                   
                      <div class="custom-switches-stacked mt-2">
                        <div class="row">   
                          <div class="col-12 col-md-6 col-lg-4">
                            <label class="custom-switch">
                              <input type="radio" name="status" value="1" class="custom-switch-input" @if($status=='1') {{ 'checked' }} @else {{ '' }} @endif>
                              <span class="custom-switch-indicator"></span>
                              <span class="custom-switch-description"><?php echo __('I want to display advertisement'); ?></span>
                            </label>
                          </div>
                          <div class="col-12 col-md-6 col-lg-4">
                            <label class="custom-switch">
                              <input type="radio" name="status" value="0" class="custom-switch-input" @if($status=='0') {{ 'checked' }} @else {{ '' }} @endif>
                              <span class="custom-switch-indicator"></span>
                              <span class="custom-switch-description"><?php echo __('I do not want to display advertisement'); ?></span>
                            </label>
                          </div>
                        </div>                                  
                        </div>
                        @if ($errors->has('status'))
                        <span class="text-danger">{{ $errors->first('status') }}</span>
                        @endif
                    </div>
  
                    <div class="row">
                      <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label><?php echo __("Section - 1 (970x90 px)");?> 
                            </label>
                            <textarea name="section1_html"  id="section1_html" placeholder="<?php echo $placeholder;?>"  class="change_status form-control">{{$section1_html }}</textarea>                  
                            @if ($errors->has('section1_html'))
                            <span class="text-danger">{{ $errors->first('section1_html') }}</span>
                            @endif
                        </div>
                      </div>
                      <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label><?php echo __("Section - 1 : Mobile  (320x100 px)");?> 
                            </label>
                            <textarea name="section1_html_mobile"  id="section1_html_mobile" placeholder="<?php echo $placeholder;?>"  class="change_status form-control">{{$section1_html_mobile }}</textarea>                   
                            @if ($errors->has('section1_html_mobile'))
                            <span class="text-danger">{{ $errors->first('section1_html_mobile') }}</span>
                            @endif                 
                          <div class="space"></div>
                        </div>
                      </div>
                    </div>
  
                    <div class="row">
                      <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label><?php echo __("Section: 2 (300x250 px)");?> 
                            </label>
                            <textarea name="section2_html"   id="section2_html" placeholder="<?php echo $placeholder;?>"  class="change_status form-control">{{$section2_html  }}</textarea>                  
                            @if ($errors->has('section2_html'))
                            <span class="text-danger">{{ $errors->first('section2_html') }}</span>
                            @endif
                        </div>
                      </div>
                      <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label><?php echo __("Section: 3 (300x250 px)");?>
                            </label>
                            <textarea name="section3_html" id="section3_html" placeholder="<?php echo $placeholder;?>"  class="change_status form-control">{{$section3_html  }}</textarea>                   
                            @if ($errors->has('section3_html'))
                            <span class="text-danger">{{ $errors->first('section3_html') }}</span>
                            @endif
                        </div>
                      </div>
                      <div class="col-12 col-md-4">
                         <div class="form-group">
                            <label><?php echo __("Section: 4 (300x600 px)");?> 
                            </label>
                            <textarea name="section4_html"   id="section4_html" placeholder="<?php echo $placeholder;?>"  class="change_status form-control">{{$section4_html }}</textarea>                  
                            @if ($errors->has('section4_html'))
                    <span class="text-danger">{{ $errors->first('section4_html') }}</span>
                    @endif
                        </div>    
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
  
    <script>
        $(document).ready(function() {
            var selected_pre = $(".custom-switch-input:checked").val();
            if(selected_pre=="0")
            $(".change_status").attr('disabled','disabled');
            else $(".change_status").removeAttr('disabled');

            $(".custom-switch-input").change(function(){
            var selected = $(".custom-switch-input:checked").val();
            if(selected=="0")
            $(".change_status").attr('disabled','disabled');
            else $(".change_status").removeAttr('disabled');
            });
        });
    </script>
  
    <style type="text/css">
        textarea.form-control{height: 80px !important;}
        textarea.form-control::placeholder {color: #ccc;}
    </style>

    <script>
        $(document).ready(function() {
        var selected_pre = $(".custom-switch-input:checked").val();
            if(selected_pre=="0")
            $(".change_status").attr('disabled','disabled');
            else $(".change_status").removeAttr('disabled');

        $(".custom-switch-input").change(function(){
            var selected = $(".custom-switch-input:checked").val();
            if(selected=="0")
            $(".change_status").attr('disabled','disabled');
            else $(".change_status").removeAttr('disabled');
        });
        });
    </script>
        
    
    
    
    <style type="text/css">
        textarea.form-control{height: 80px !important;}
        textarea.form-control::placeholder {color: #ccc;}
    </style>
</section>

@endsection