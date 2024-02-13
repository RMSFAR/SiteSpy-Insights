{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Moz rank'))
@section('content')


<link rel="stylesheet" href="{{ asset('assets/custom-css/fileUploadMutilayout.css') }}">
  

<section class="section">
    <div class="section-header">
      <h1><i class="fa fa-trophy"></i> {{ __('Moz rank')}}</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{route('analysis_tools')}}">{{ __("Analysis Tools")}}</a></div>
        <div class="breadcrumb-item"><a href="{{route('moz_rank_index')}}">{{ __("Moz Rank Analysis")}}</a></div>
        <div class="breadcrumb-item">{{ __('Moz rank')}}</div>
      </div>
    </div>
</section>
    
  
<div class="row multi_layout">
  
    <div class="col-12 col-md-5 col-lg-5 collef">
      <div class="card main_card">
        <div class="card-header">
          <h4><i class="fas fa-info-circle"></i> {{ __('Info')}}</h4>
        </div>
        <form  method="POST" enctype="multipart/form-data"  id="new_search_form">
          @csrf
  
  
          <div class="card-body">
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
              <button class="btn btn-secondary btn-md float-right" onclick="goBack('rank/moz_rank')" type="button"><i class="fa fa-remove"></i> {{ __('Cancel')}}</button>
            
      
  
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
            <h4> <i class="fas fa-trophy"></i> {{ __('Moz Rank')}}</h4>
            
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
  var moz_rank_action = '{{ route("moz_rank_action") }}';
  var read_text_csv_file_backlink = '{{ route("rank_read_text_csv_file_backlink") }}';
  var read_after_delete_csv_txt = '{{ route("rank_read_after_delete_csv_txt") }}';
</script>
  
<script src="{{asset('assets/custom-js/analysis-tools/mozrankanalysis.js')}}"></script>  
  

@endsection



  