{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',$page_title)
@section('content')


<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-bell"></i> <?php echo $page_title; ?></h1>
    <?php if(Auth::user()->user_type =="Admin") 
    { ?>
      <div class="section-header-button">
       <a class="btn btn-primary"  href="{{route('announcement_add')}}">
          <i class="fas fa-plus-circle"></i> <?php echo __("New Announcement"); ?>
       </a> 
      </div>
    <?php 
    } ?>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("Subscription"); ?></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  @include('shared.message')

  <?php 
  if(session()->flash('mark_seen_success')!='')
  echo "<div class='alert alert-success text-center'><i class='fas fa-check-circle'></i> ".session()->flash('mark_seen_success')."</div>"; 
  ?>

  <div class="section-body">

    <div class="row">      
      <div class="col-12 col-md-7">
        <div class="input-group mb-3" id="searchbox">
          <div class="input-group-prepend">
              <select class="select2 form-control" id="seen_type">
                <option value="0"><?php echo __("Unseen"); ?></option>
                <option value="1"><?php echo __("Seen"); ?></option>
                <option value=""><?php echo __("Everything"); ?></option>
              </select>
            </div>
          <input type="text" class="form-control" id="search" autofocus placeholder="<?php echo __('Search...'); ?>" aria-label="" aria-describedby="basic-addon2">
          <div class="input-group-append">
            <button class="btn btn-primary" id="search_submit" type="button"><i class="fas fa-search"></i> <?php echo __('Search'); ?></button>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-5">
        <button class="btn btn-outline-primary btn-lg float-right" id="mark_seen_all"><i class="fas fa-eye-slash"></i> <?php echo __("Mark all unseen as seen"); ?></button>
      </div>
    </div>

    <div class="activities">
        <div id="load_data" style="width: 100%;"></div>      
    </div> 


    <div class="text-center" id="waiting" style="width: 100%;margin: 30px 0;">
      <i class="fas fa-spinner fa-spin blue" style="font-size:60px;"></i>
    </div>  

    <div class="card" id="nodata" style="display: none">
      <div class="card-body">
        <div class="empty-state">
          <img class="img-fluid" style="height: 200px" src="{{asset('assets/img/drawkit/drawkit-nature-man-colour.svg')}}" alt="image">
          <h2 class="mt-0"><?php echo __("We could not find any data.") ?></h2>
        </div>
      </div>
    </div>
 

    <button class="btn btn-outline-primary float-right" style="display: none;" id="load_more" data-limit="10" data-start="0"><i class="fas fa-book-reader"></i> <?php echo __("Load More"); ?></button>
      
  </div>
</section>

<script>
  "use strict";
  var No_data_found = '{{ __("No data found") }}';
  var Do_you_really_want_to_delete_it = '{{ __("Do you really want to delete it?") }}';
  var Do_you_really_want_to_mark_all_unseen_notifications_as_seen = '{{ __("Do you really want to mark all unseen notifications as seen?") }}';
  var announcement_list_data = '{{ route("announcement_list_data") }}';
  var announcement_mark_seen_all = '{{ route("announcement_mark_seen_all") }}';
</script>

<script src="{{asset('assets/custom-js/announcement/announcement-list.js')}}"></script>


@endsection