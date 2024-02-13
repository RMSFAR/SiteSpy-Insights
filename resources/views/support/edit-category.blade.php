@extends('design.app')
@section('title',$page_title)
@section('content')


<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-plus-circle"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="<?php echo url('/simplesupport/tickets'); ?>"><?php echo __("Support Desk"); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  {{-- <?php $this->load->view('admin/theme/message'); ?> --}}
  @include('shared.message')

  <div class="row">
    <div class="col-12">

      <form class="form-horizontal" action="<?php echo url('/').'/simplesupport/edit_category_action';?>" method="POST">
        @csrf
        <div class="card">
          <div class="card-body">
            <input type="hidden" name="id" value="<?php echo $xdata->id;?>">
            <div class="form-group">
              <label for="category_name"> <?php echo __("Category Name")?> *</label>
              <input name="category_name" value="<?php echo $xdata->category_name;?>"  class="form-control" type="text">
              @if ($errors->has('category_name'))
              <span class="text-danger">{{ $errors->first('category_name') }}</span>
              @endif
            </div>            
            
          </div>

          <div class="card-footer bg-whitesmoke">
            <button name="submit" type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> <?php echo __("Save");?></button>
            <button  type="button" class="btn btn-secondary btn-lg float-right" onclick='goBack("/simplesupport/support_category_manager",0)'><i class="fa fa-remove"></i> <?php echo __("Cancel");?></button>
          </div>
        </div>
      </form>  
    </div>
  </div>
</section>

@endsection
