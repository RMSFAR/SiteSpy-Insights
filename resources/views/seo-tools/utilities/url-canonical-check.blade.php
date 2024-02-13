{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('URL Canonical Check'))
@section('content')



<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-external-link-square-alt"></i> {{ __('URL Canonical Check')}}</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{route('utilities') }}">{{__("Utilities")}}</a></div>
      <div class="breadcrumb-item">{{ __('URL Canonical Check')}}</div>
    </div>
  </div>
</section>
  

<div class="row multi_layout">

  <div class="col-12 col-md-6 col-lg-6 collef">
    <div class="card main_card">
      <div class="card-header">
        <h4><i class="fas fa-info-circle"></i> {{__('Info')}}</h4>
      </div>
      <form enctype="multipart/form-data" method="POST"  id="new_search_form">
        @csrf


        <div class="card-body">

          <div class="form-group">
            <label class="form-label"> {{__("URL Canonical Check")}} <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="{{__("URL Canonical Check") }}" data-content='{{__("Put your URLs (comma separated)") }}'><i class='fa fa-info-circle'></i> </a></label>
            <textarea id="bulk_email" class="form-control" style="width:100%;min-height: 120px;" rows="10"></textarea>
          </div>
      
        </div>

        <div class="card-footer bg-whitesmoke mt-66">

            <button type="button"  id="new_search_button" class="btn btn-primary "><i class="fa fa-search"></i> {{__("Search")}}</button>
            <button class="btn btn-secondary btn-md float-right" onclick="goBack('menu_loader/utlities')" type="button"><i class="fa fa-remove"></i> {{__('Cancel')}}</button>
          
    

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
          <h4> <i class="fas fa-external-link-square-alt"></i> {{__('URL Canonical Check Results')}}</h4>
          
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
  var url_canonical_action = '{{ route('url_canonical_action') }}';
  var Please_enter_your_urls = '{{ __('Please enter your urls') }}';

</script>

<script src="{{asset('assets/custom-js/utilities/urlCanonical.js')}}"></script>




@endsection