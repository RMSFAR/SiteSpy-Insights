{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('DNS Information'))
@section('content')

<link rel="stylesheet" href="{{ asset('assets/custom-css/fileUploadMutilayout.css') }}">


<section class="section">
  <div class="section-header">
    <h1><i class="fa fa-globe"></i> {{ __('DNS Information')}}</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{route('analysis_tools')}}">{{ __("Analysis Tools")}}</a></div>
      <div class="breadcrumb-item">{{ __('DNS Information')}}</div>
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
              <label class="form-label"> {{ __("Domain")}} <code>*</code> <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="" data-content="Put your domain names comma separated" data-original-title="Domain"><i class="fa fa-info-circle"></i> </a></label>
             
              <textarea id="domain_name" name="domain_name" class="form-control" style="width:100%;min-height: 140px;" rows="10"></textarea>
          </div>

        </div>

        <div class="card-footer bg-whitesmoke mt-136">

            <button type="button"  id="new_search_button" class="btn btn-primary ">{{ __("Analysis")}}</button>
            <button class="btn btn-secondary btn-md float-right" onclick="goBack('analysis_tools')" type="button"><i class="fa fa-remove"></i> {{ __('Cancel')}}</button>
          
    

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
          <h4> <i class="fas fa-globe"></i> {{ __('DNS Information')}}</h4>
          
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

  var Please_Enter_Domain_Name = '{{ __('Please Enter Domain Name') }}';
  var lang_TTL = '{{ __('TTL') }}';
  var Class = '{{ __('Class') }}';
  var Target = '{{ __('IP/Target') }}';
  var Host = '{{ __('Host') }}';
  var Type = '{{ __('Type') }}';
  var dns_info_action = '{{ route('dns_info_action') }}';

</script>

<script src="{{asset('assets/custom-js/analysis-tools/dns-info.js')}}"></script>




<div class="modal fade show" id="who_is_download_selected">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="background: #fefefe;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-server"></i> {{ __('DNS Information')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div id="custom_spinner"></div>
      <div class="modal-body text-center" id="total_download_selected"> 


      </div>
      
    </div>
  </div>
</div>


@endsection