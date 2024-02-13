{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Base64 Encoder/Decoder'))
@section('content')


<section class="section">
  <div class="section-header">
    <h1><i class="fab fa-centercode"></i> {{ __('Base64 Encoder/Decoder')}}</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{route('utilities') }}">{{ __("Utilities") }}</a></div>
      <div class="breadcrumb-item">{{ __('Base64 Encoder/Decoder')}}</div>
    </div>
  </div>
</section>
  

<div class="row multi_layout">

  <div class="col-12 col-md-5 col-lg-5 collef">
    <div class="card main_card">
      <div class="card-header">
        <h4><i class="fas fa-info-circle"></i> {{ __('Info')}}</h4>
      </div>
      <form enctype="multipart/form-data" method="POST"  id="new_search_form">
        @csrf


        <div class="card-body">

          <div class="form-group">
            <label class="form-label"> {{ __("Base64 Encoder/Decoder")}} <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="{{ __("Base64 Encoder/Decoder") }}" data-content='{{ __("Put your contents here") }}'><i class='fa fa-info-circle'></i> </a></label>
            <textarea id="base64" class="form-control" style="width:100%;min-height: 120px;" rows="10"></textarea>
          </div>
      
        </div>

        <div class="card-footer bg-whitesmoke mt-66">

            <button type="button"  id="new_search_button" class="btn btn-primary "><i class="fa fa-code"></i> {{ __("Encode")}}</button>

            <button class="btn btn-warning btn-md float-right" id="new_search_button_decode" type="button"><i class="fa fa-exchange"></i> {{ __("Decode")}}</button>
    

        </div>

      </form>
    </div>          
  </div>

  <div class="col-12 col-md-7 col-lg-7 colmid">
    <div id="custom_spinner"></div>
    <div id="middle_column_content" style="background: #ffffff!important;">

      <div class="card">
        <div class="card-header">
          <h4> <i class="fab fa-centercode"></i> {{ __('Base64 Encoder/Decoder Results')}}</h4>

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
  var base64_encode_action = '{{ route('base64_encode_action') }}';
  var base64_decode_action = '{{ route('base64_decode_action') }}';
  var You_have_not_enter_any_content = '{{ __('You have not enter any content') }}';

</script>

<script src="{{asset('assets/custom-js/utilities/base64.js')}}"></script>


@endsection