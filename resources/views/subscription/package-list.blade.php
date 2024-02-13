{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Package manager'))
@section('content')


<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-shopping-bag"></i> {{ __('Package manager') }}</h1>
    <div class="section-header-button">
     <a class="btn btn-primary"  href="{{ route('add_package');}}">
        <i class="fas fa-plus-circle"></i> {{ __("New Package")}}
     </a> 
    </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">{{ __("Subscription")}}</div>
      <div class="breadcrumb-item">{{ __('Package manager') }}</div>
    </div>
  </div>

  <div class="section-body">

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body data-card">
            <div class="table-responsive2">
              <table class="table table-bordered" id="mytable">
                <thead>
                  <tr>
                    <th>#</th>      
                    <th>{{ __("Package ID")}}</th>      
                    <th>{{ __("Package Name")}}</th>
                    <th>{{ __("Price")}} - {{ isset($payment_config[0]->currency) ? $payment_config[0]->currency : 'USD'}}</th>
                    <th>{{ __("Validity")}} - {{ __("days")}}</th>
                    <th>{{ __("Default Package")}}</th>
                    <th style="min-width: 150px">{{ __("Actions")}}</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>             
          </div>

        </div>
      </div>
    </div>
    
  </div>
</section>

@php $csrf_token=csrf_token() @endphp

<script>
  "use strict";

  var package_manager_data = '{{ route("package_manager_data") }}';
  var csrf_token = '{{$csrf_token}}';
  var Default_package_can_not_be_deleted = '{{ __("Default package can not be deleted.") }}';

</script>

<script src="{{asset('/assets/custom-js/subscription/package-list.js')}}"></script>





@endsection


