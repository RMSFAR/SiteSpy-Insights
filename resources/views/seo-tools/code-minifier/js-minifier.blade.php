{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Js code minifier'))
@section('content')

<link rel="stylesheet" href="{{asset('assets/custom-css/fileUploadMutilayout.css')}}">

<section class="section">
    <div class="section-header">
    <h1><i class="fa fa-js"></i> {{ __('Js code minifier') }}</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{route('code_minifier')}}">{{ __("Code Minifier");}}</a></div>
        <div class="breadcrumb-item">{{ __('Js code minifier') }}</div>
    </div>
    </div>
</section>
    
  
<div class="row multi_layout">

    <div class="col-12 col-md-6 col-lg-6 collef">
    <div class="card main_card">
        <div class="card-header">
        <h4><i class="fas fa-info-circle"></i> {{ __('Info'); }}</h4>
        </div>
        <form enctype="multipart/form-data" method="POST"  id="new_search_form">
            @csrf


        <div class="card-body">

            <div class="form-group">
            <label class="form-label"> {{ __("Js Code Minifier"); }} <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="{{ __("Js Code Minifier") }}" data-content='{{ __("Write your js code here... ") }}'><i class='fa fa-info-circle'></i> </a></label>
            <textarea id="js_code" name="js_code" class="form-control" style="width:100%;min-height: 140px;" rows="10"></textarea>
            </div>

            <div class="form-group">
                <label> {{ __('Files');}} <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="{{ __("Files") }}" data-content='{{ __("To minify your single or multiple js files, please select your files by clicking the upload button bellow. ") }}'><i class='fa fa-info-circle'></i> </a></label>
                    <div id="file_upload_url" class="form-control">{{ __('Upload');}}</div>
            </div> 
        
        </div>

        <div class="card-footer bg-whitesmoke mt-66">

            <button type="button"  id="minify_js" class="btn btn-primary "> {{ __("Minify"); }}</button>
            <button class="btn btn-secondary btn-md float-right" onclick="goBack('code_minifier')" type="button"> {{ __('Cancel'); }}</button>
        </div>

        </form>
    </div>          
    </div>

    <div class="col-12 col-md-6 col-lg-6 colmid">
    <div id="custom_spinner"></div>
    <div id="middle_column_content" style="background: #ffffff!important;">

        <div class="card">
        <div class="card-header">
            <h4> <i class="fab fa-js"></i> {{ __('JS Minifier Results'); }}</h4>
            
        </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-12 bck_clr" id="nodata">

        <div class="empty-state">
            <img class="img-fluid" src="{{ asset('assets/img/drawkit/revenue-graph-colour.svg') }}" style="height: 300px" alt="image">
        

        </div>

        </div>
    </div>
    </div>
</div>


<script>
    "use strict";

    var read_after_delete_js = '{{ route('read_after_delete_js') }}';
    var read_text_file_js = '{{ route('read_text_file_js') }}';
    var js_minifier_textarea = '{{ route('js_minifier_textarea') }}';
    var Please_write_js_first = '{{ __('Please write js first') }}';

</script>
    
<script src="{{asset('assets/custom-js/codeminify/jsminify.js')}}"></script>   


@endsection




