{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Keyword tracking'))
@section('content')

<section class="section">
    <div class="section-header">
      <h1><i class="fa fa-map-marker-alt"></i> <?php echo __("Keyword tracking"); ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><?php echo __("Keyword tracking"); ?></div>
      </div>
    </div>
  
    <div class="section-body">
      <div class="row">
        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fas fa-cogs"></i>
            </div>
            <div class="card-body">
              <h4><?php echo __("Tracking Settings"); ?></h4>
              <p><?php echo __("Shorten, share and track your shortened URLs"); ?></p>
              <a href="{{route('keyword_tracking_index')}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>
  
        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fas fa-bars"></i>
            </div>
            <div class="card-body">
              <h4><?php echo __("Position Report"); ?></h4>
              <p><?php echo __("Analytics of shortened URL."); ?></p>
              <a href="{{route('keyword_position_report')}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>
  
      </div>
    </div>
</section>

@endsection