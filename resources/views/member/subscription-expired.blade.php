@extends('design.app')
@section('title',$page_title)
@section('content')


<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-stopwatch"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="{{ url('/dashboard') }}"><?php echo __('Dashboard'); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        
        <div class="card">
          <div class="card-body">
            <div class="empty-state" style="padding-top: 0">
              <img class="img-fluid" src="<?php echo asset('assets/img/drawkit/expired.jpg'); ?>" height="300" width="300">
              <h2 class="mt-0"><?php echo __("Your subscription package is expired"); ?></h2>
              <p class="lead">
              <?php echo __("To get access back please contact system admin."); ?>
              </p>
              <a href="{{ route('usage_history') }}" class="btn btn-primary mt-4"><i class="fas fa-book-reader"></i> <?php echo __('Read More'); ?></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


@endsection
