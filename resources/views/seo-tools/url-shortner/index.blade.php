{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Url shortner'))

@section('content')

<section class="section">
    <div class="section-header">
      <h1><i class="fa fa-cut"></i> <?php echo __("Url shortner"); ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><?php echo __("Url shortner"); ?></div>
      </div>
    </div>
  
    <div class="section-body">
      <div class="row">
  
        {{-- <?php if(Auth::user()->user_type == 'Admin' || in_array(18,$module_access)) : ?> --}}
        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fab fa-bity"></i>
            </div>
            <div class="card-body">
              <h4><?php echo __("Bitly Url shortener"); ?></h4>
              <p><?php echo __("Shorten, share and track your shortened URLs"); ?></p>
              <a href="{{route('bitly_shortener_index')}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>
  
        <div class="col-lg-6">
          <div class="card card-large-icons">
            <div class="card-icon text-primary">
              <i class="fab fa-bandcamp"></i>
            </div>
            <div class="card-body">
              <h4><?php echo __("Rebrandly"); ?></h4>
              <p><?php echo __("Shorten, share and track your shortened URLs"); ?></p>
              <a href="{{route('rebrandly_shortener_index')}}" class="card-cta"><?php echo __("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        </div>
        {{-- <?php endif; ?> --}}
  
      </div>
    </div>
</section>

@endsection