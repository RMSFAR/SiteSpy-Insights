{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',$page_title)
@section('content')

<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-eye"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("Subscription"); ?></div>
      <div class="breadcrumb-item active"><a href="{{route('announcement_full_list')}}"><?php echo __("Announcement"); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  @include('shared.message')

  <div class="section-body">

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="section-title mt-0"><?php echo __("Title"); ?> :<small> <?php echo $xdata->title;?></small> </div>
            <div class="section-title"><?php echo __("Description"); ?></div>
            <div class="p-3 mb-2 bg-light text-dark" style="margin-left: 45px;"><?php echo nl2br($xdata->description);?></div>
            <div class="section-title"><?php echo __("Published"); ?> <small><?php echo date_time_calculator($xdata->created_at,true); ?></small></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


@endsection