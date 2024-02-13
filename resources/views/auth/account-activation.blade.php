@extends('design.auth_app')
@section('title')
    {{__("Account Activation")}}
@endsection
@section('content')


<div class="container mt-5">
  <div class="row">
    <div class="col-12 ">
      {{-- <div class="login-brand">
         <a href="<?php echo url('');?>"><img src="{{config('my_config.logo')}}" alt="<?php echo config('mt_config.product_name');?>" width="200"></a>
      </div> --}}

      <div class="card card-primary">
        <div class="card-header"><h4><i class="fas fa-user-check"></i> <?php echo __("Account Activation");?></h4></div>

        <div class="card-body" id="recovery_form">
          <p class="text-muted"><?php echo __("Put your email and activation code that we sent to your email"); ?></p>
          <form method="POST" action="{{route('account-activation-action')}}">
            <div class="form-group">
              <label for="email"><?php echo __("Email");?> *</label>
              <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus>
              <div class="invalid-feedback"><?php echo __("Please enter your email"); ?></div>
            </div>
            <div class="form-group">
              <label for="email"><?php echo __("Account Activation Code");?> *</label>
              <input type="text" class="form-control" id="code" name="code" tabindex="1" required>
              <div class="invalid-feedback"><?php echo __("Please enter activation code"); ?></div>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4" name="submit" id="submit">
                <i class="fas fa-user-check"></i> <?php echo __("Activate My Account");?>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


<script src="{{asset('/assets/modules/jquery.min.js')}}"></script>
<script>
      var activation_action = '{{ route("account-activation-action") }}';
      var login = '{{ route("login") }}';
      var csrf_token = '{{ csrf_token() }}';
      var lang_error = '{{ __("Error") }}';
      var Account_activation_code_does_not_match = '{{ __("Account activation code does not match") }}';
      var You_can_login_here = '{{ __("You can login here") }}';
      var your_account_has_been_activated = '{{ __("Congratulations, your account has been activated successfully.") }}';
</script>
<script src="{{asset('assets/custom-js/auth/auth.account-activation.js')}}"></script>

@endsection