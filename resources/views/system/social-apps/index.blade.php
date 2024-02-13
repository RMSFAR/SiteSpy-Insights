{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Social apps & apis'))
@section('content')

<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-hands-helping"></i> <?php echo __('Social apps & apis'); ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("System"); ?></div>
      <div class="breadcrumb-item"><?php echo __('Social apps & apis'); ?></div>
    </div>
  </div>


    <div class="section-body">
      <div class="row">
        @if (Auth::user()->user_type != 'Member')
        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fab fa-facebook"></i>
            </div>

            <div class="card-body">
              <h4><?php echo __("Facebook"); ?></h4>
              <p><?php echo __("Set your Facebook app key, secret etc..."); ?></p>
              <a href="{{route("add_facebook_settings")}}" class="card-cta"><?php echo __("Change Setting"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fab fa-google"></i>
            </div>
            <div class="card-body">
              <h4><?php echo __("Google"); ?></h4>
              <p><?php echo __("Set your Google app key, secret etc..."); ?></p>
              <a href="{{route("google_settings")}}" class="card-cta"><?php echo __("Change Setting"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>
      @endif
        
        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fab fa-connectdevelop"></i>
            </div>
            <div class="card-body">
              <h4><?php echo __("Connectivity Settings"); ?></h4>
              <p><?php echo __("Set All kind of Connectivity APIs..."); ?></p>
              <a href="{{route("connectivity_settings")}}" class="card-cta">
                <?php echo __("Change Setting"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>    

        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fas fa-user-secret"></i>
            </div>
            <div class="card-body">
              <h4><?php echo __("Proxy Settings"); ?></h4>
              <p><?php echo __("Set Proxies to use features."); ?></p>
              <a href="{{route("proxy_settings")}}" class="card-cta">
                <?php echo __("Change Setting"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>

      </div>
    </div>
</section>

@endsection