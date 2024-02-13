{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',$page_title)
@section('content')


<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-edit"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("Subscription"); ?></div>
      <div class="breadcrumb-item active"><a href="{{route('user_manager')}}"><?php echo __("User Manager"); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  @include('shared.message')

  <div class="row">
    <div class="col-12">

      <form class="form-horizontal" action="{{route('edit_user_action')}}" method="POST">
        @csrf
        <div class="card">
          <div class="card-body">

            <input name="id" value="<?php echo $xdata->id;?>"  class="form-control" type="hidden">

            <div class="form-group">
              <label for="name"> <?php echo __("Full Name")?> </label>
              <input name="name" value="<?php echo $xdata->name;?>"  class="form-control" type="text">
              @if ($errors->has('name'))
              <span class="text-danger">{{ $errors->first('name') }}</span>
              @endif
            </div>
             
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="email"> <?php echo __("Email")?> *</label>
                  <input name="email" value="<?php echo $xdata->email;?>"  class="form-control" type="email">
                  @if ($errors->has('email'))
									<span class="text-danger">{{ $errors->first('email') }}</span>
									@endif
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="mobile"><?php echo __("Mobile")?></label>              
                  <input name="mobile" value="<?php echo $xdata->mobile ;?>"  class="form-control" type="text">
                  @if ($errors->has('mobile'))
									<span class="text-danger">{{ $errors->first('mobile') }}</span>
									@endif               
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="address"> <?php echo __("Address")?></label>
              <textarea name="address" class="form-control"><?php echo $xdata->address; ?></textarea>
              @if ($errors->has('address'))
              <span class="text-danger">{{ $errors->first('address') }}</span>
              @endif
            </div> 

            <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="user_type" > <?php echo __('User Type');?></label>
                    <div class="custom-switches-stacked mt-2">
                      <div class="row">   
                        <div class="col-6 col-md-4">
                          <label class="custom-switch">
                            <input type="radio" name="user_type" value="Member"  <?php if($xdata->user_type=='Member') echo 'checked'; ?> class="user_type custom-switch-input">
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description"><?php echo __('Member'); ?></span>
                          </label>
                        </div>                        
                        <div class="col-6 col-md-4">
                          <label class="custom-switch">
                            <input type="radio" name="user_type" value="Admin" <?php if($xdata->user_type=='Admin') echo 'checked'; ?> class="user_type custom-switch-input">
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description"><?php echo __('Admin'); ?></span>
                          </label>
                        </div>
                      </div>                                  
                    </div>
                    @if ($errors->has('user_type'))
                    <span class="text-danger">{{ $errors->first('user_type') }}</span>
                    @endif
                </div> 
              </div>

              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="status" > <?php echo __('Status');?></label><br>
                  <label class="custom-switch mt-2">
                    <input type="checkbox" name="status" value="1" class="custom-switch-input" <?php if($xdata->status=='1') echo 'checked'; ?>>
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description"><?php echo __('Active');?></span>
                    @if ($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                    @endif
                  </label>
                </div>
              </div>             
            </div>

            <div class="row" id="hidden">
              <div class="col-6">
                <div class="form-group">
                  <label for="package_id"> <?php echo __("Package")?> *</label>
                  <?php 
                    $default_package = $xdata->package_id;
                    if($default_package=='0') $default_package = '1';
                  ?>
                  <?php echo Form::select('package_id', $packages, $default_package,array('class'=>'form-control select','style'=>'width:100%')); ?>                 
                  @if ($errors->has('package_id'))
									<span class="text-danger">{{ $errors->first('package_id') }}</span>
									@endif
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="expired_date"> <?php echo __("Expiry Date")?> *</label>
                  <?php 
                    $default_date = $xdata->expired_date;
                    if($default_date=='0000-00-00 00:00:00') $default_date = '';
                  ?>
                  <input name="expired_date" value="<?php echo $default_date;?>"  required class="form-control datepicker" type="text">
                  @if ($errors->has('expired_date'))
									<span class="text-danger">{{ $errors->first('expired_date') }}</span>
									@endif
                </div>
              </div>
            </div>


          </div>

          <div class="card-footer bg-whitesmoke">
            <button name="submit" type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> <?php echo __("Save");?></button>
            <button  type="button" class="btn btn-secondary btn-lg float-right" onclick='goBack("admin/user_manager",0)'><i class="fa fa-remove"></i> <?php echo __("Cancel");?></button>
          </div>
        </div>
      </form>  
    </div>
  </div>
</section>

<script>
  "use strict";

  var user_type = {{$xdata->user_type}};
</script>         

<script type="text/javascript">
  $(document).ready(function() {
    if(user_type=="Admin") $("#hidden").hide();
    else $("#validity").show();
    $(".user_type").click(function(){
      if($(this).val()=="Admin") $("#hidden").hide();
      else $("#hidden").show();
    });
  });
</script>


@endsection