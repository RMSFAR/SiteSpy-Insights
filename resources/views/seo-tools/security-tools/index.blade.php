{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Security tools'))
@section('content')

<section class="section">
    <div class="section-header">
      <h1><i class="fa fa-shield"></i> <?php echo __('Security tools'); ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><?php echo __('Security tools'); ?></div>
      </div>
    </div>
  
    <div class="section-body">
      <div class="row">
        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fab fa-typo3"></i>
            </div>
            <div class="card-body">
              <h4><?php echo __("Malware Scan"); ?></h4>
              <p><?php echo __("Scan any websites’ malware status"); ?></p>
              <a href="{{route('malware_index')}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>
  
        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fab fa-css3-alt"></i>
            </div>
            <div class="card-body">
              <h4><?php echo __("Virus Total Scan"); ?></h4>
              <p><?php echo __("scan in 67 different places."); ?></p>
              <a href="{{route('virus_index')}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>
  
  
      </div>
    </div>
</section>
@endsection