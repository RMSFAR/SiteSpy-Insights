@extends('design.subscription-theme')
@section('title',__('Credential Check'))
@section('content')
<div class="container mt-5">
    <div class="row">
      <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-6 offset-xl-3">
        <div class="login-brand">
          <a href="<?php echo url('/');?>"><img src="{{config('my_config.logo')}}" alt="<?php echo config('my_config.product_name');?>" width="200"></a>
        </div>
  
        <div class="card card-primary">
          <div class="card-header"><h4><i class="far fa-copyright"></i> <?php echo __("Register your software"); ?></h4></div>
  
          <div class="card-body" id="recovery_form">
            <p class="text-muted"><?php echo __("Put purchase code to activate software"); ?></p>
            <form method="POST">
              @csrf
              @if (session('purchase_code_error_message')=='1')
                <div class="alert alert-danger p-0">
                    <ul>
                        <li>{{ __('Invalid purchase code') }}</li>
                    </ul>
                </div>
              @endif
              <div class="form-group">
                <label for="email"><?php echo __("Purchase Code"); ?> *</label>
                <input id="purchase_code" type="text" class="form-control" id="purchase_code" name="purchase_code" tabindex="1" autofocus value="{{ old('purchase_code') }}" placeholder="{{__('Purchase Code')}} *">
                <div class="invalid-feedback"><?php echo __("Please enter purchase code"); ?></div>
              </div>
  
              <div class="form-group">
                <button type="submit" id="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                 <i class="far fa-paper-plane"></i> <?php echo __("Submit Purchase Code"); ?>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    var dashboard_url = "{{ route('dashboard') }}";
  </script>
  <script src="{{asset('assets/custom-js/auth/auth.credential-check.js')}}"></script>
  
@endsection

