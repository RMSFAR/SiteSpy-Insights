{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Settings'))
@section('content')

<section class="section">
  <div class="section-header">
    <h1><i class="fa fa-cogs"></i> <?php echo __('Settings'); ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("System"); ?></div>
      <div class="breadcrumb-item"><?php echo __('Settings'); ?></div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-toolbox"></i>
          </div>
          <div class="card-body">
            <h4><?php echo __("General"); ?></h4>
            <p><?php echo __("brand, logo, language, phpmail, https, upload..."); ?></p>
            <a href="{{route("general_settings")}}" class="card-cta"><?php echo __("Change Setting"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-store"></i>
          </div>
          <div class="card-body">
            <h4><?php echo __("Front-end"); ?></h4>
            <p><?php echo __("Hide, theme, social, review, video..."); ?></p>
            <a href="{{route("front_end_settings")}}" class="card-cta"><?php echo __("Change Setting"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-envelope"></i>
          </div>
          <div class="card-body">
            <h4><?php echo __("SMTP Settings"); ?></h4>
            <p><?php echo __("SMTP email settings"); ?></p>
            <a href="{{route("smtp_settings")}}" class="card-cta"><?php echo __("Change Setting"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-id-card"></i>
          </div>
          <div class="card-body">
            <h4><?php echo __("Email Template"); ?></h4>
            <p><?php echo __("Signup, change password, expiry, payment..."); ?></p>
            <a href="{{route("email_templete_settings")}}" class="card-cta"><?php echo __("Change Setting"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
           <i class="fas fa-chart-pie"></i>
          </div>
          <div class="card-body">
            <h4><?php echo __("Analytics"); ?></h4>
            <p><?php echo __("Google analytics, Facebook pixel code..."); ?></p>
            <a href="{{route("analytics_settings")}}" class="card-cta"><?php echo __("Change Setting"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
           <i class="fab fa-adversal"></i>
          </div>
          <div class="card-body">
            <h4><?php echo __("Advertisement"); ?></h4>
            <p><?php echo __("Banner, potrait, landscape image ads..."); ?></p>
            <a href="{{route("advertisement_settings")}}" class="card-cta"><?php echo __("Change Setting"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>

    

    </div>
  </div>
</section>
@endsection