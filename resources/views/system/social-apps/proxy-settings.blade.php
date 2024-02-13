{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Proxy settings'))
@section('content')

<style>
    ::placeholder{color:#bbb !important;}
    .dropdown-toggle::after{content:none !important;}
    #proxy_keyword{max-width: 50% !important;}
    .bbw{border-bottom-width: thin !important;border-bottom:solid .5px #f9f9f9 !important;padding-bottom:20px;}
    @media (max-width: 575.98px) { #proxy_keyword{max-width: 90% !important;} }
</style>
  
<section class="section section_custom">
      <div class="section-header">
          <h1><i class="fas fa-user-secret"></i> <?php echo __('Proxy settings'); ?></h1>
          <div class="section-header-button">
              <a class="btn btn-primary insert_new_proxy" href="#">
                  <i class="fas fa-plus-circle"></i> <?php echo __("New Proxy"); ?>
              </a> 
          </div>
          <div class="section-header-breadcrumb">
              <div class="breadcrumb-item"><?php echo __('System'); ?></div>
              <div class="breadcrumb-item"><a href="{{route("social_apps")}}"><?php echo __("Social Apps & APIs"); ?></a></div>
              <div class="breadcrumb-item"><?php echo __('Proxy settings'); ?></div>
          </div>
      </div>

  	@include('shared.message')

      <div class="section-body">
          <div class="row">
              <div class="col-12">
                  <div class="card">
                      <div class="card-body data-card">
                          <div class="row">
                              <div class="col-12 col-md-6">
                                  <div class="input-group float-left" id="searchbox">
                                      <input type="text" class="form-control" id="proxy_keyword" name="proxy_keyword" placeholder="<?php echo __('Search'); ?>" aria-label="" aria-describedby="basic-addon2">
                                  </div>
                              </div>
                          </div>
                          <div class="table-responsive2">
                              <table class="table table-bordered" id="mytable_proxy">
                                  <thead>
                                      <tr>
                                          <th class="centering no-sort"><?php echo __("#"); ?></th> 
                                          <th class="centering no-sort"><?php echo __("ID"); ?></th> 
                                          <th class="centering no-sort"><?php echo __("Proxy"); ?></th>      
                                          <th class="centering no-sort"><?php echo __("Proxy Port"); ?></th>  
                                          @if(Auth::user()->user_type == "Admin")
                                          <th class="centering no-sort"><?php echo __("Permisson"); ?></th>
                                          @endif   
                                          <th class="centering no-sort"><?php echo __("Proxy Username"); ?></th>
                                          <th class="centering no-sort"><?php echo __("Proxy Password"); ?></th>
                                          <th class="centering no-sort"><?php echo __("Actions"); ?></th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                              </table>
                          </div>             
                      </div>
                  </div>
              </div>
          </div>
      </div>
</section> 
  
  
<div class="modal fade" id="new_proxy_modal" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" style="max-width:35% !important;">
          <div class="modal-content">
              <div class="modal-header bbw">
                  <h5 class="modal-title blue"><i class="fas fa-user-secret"></i> <?php echo __('New Proxy Settings'); ?></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
  
              <div class="modal-body">
                  <div class="row">
                      <div class="col-12">
                          <form action="#" method="POST" id="new_proxy_form">
                            @csrf
                              <div class="row">
                                  <div class="col-12">
                                      <div class="form-group">
                                          <label><?php echo __('Proxy'); ?></label>
                                          <input type="text" class="form-control" id="proxy" name="proxy">
                                      </div>
                                  </div>
                                  <div class="col-12">
                                      <div class="form-group">
                                          <label><?php echo __('Proxy Port'); ?></label>
                                          <input type="text" class="form-control" id="proxy_port" name="proxy_port">
                                      </div>
                                  </div>
                                  <div class="col-12">
                                      <div class="form-group">
                                          <label><?php echo __('Proxy Username'); ?></label>
                                          <input type="text" class="form-control" id="proxy_username" name="proxy_username">
                                      </div>
                                  </div>
                                  <div class="col-12">
                                      <div class="form-group">
                                          <label><?php echo __('Proxy Password'); ?></label>
                                          <input type="text" class="form-control" id="proxy_password" name="proxy_password">
                                      </div>
                                  </div>
  
                                  @if(Auth::user()->user_type == "Admin")
                                      <div class="col-12">
                                          <div class="form-group">
                                              <label for="user_type" > <?php echo __('Proxy Permission');?></label>
                                              <div class="custom-switches-stacked mt-2">
                                                  <div class="row">   
                                                      <div class="col-6">
                                                          <label class="custom-switch">
                                                              <input type="radio" name="permission" value="everyone" checked class="user_type custom-switch-input">
                                                              <span class="custom-switch-indicator"></span>
                                                              <span class="custom-switch-description"><?php echo __('Everyone'); ?></span>
                                                          </label>
                                                      </div>                        
                                                      <div class="col-6">
                                                          <label class="custom-switch">
                                                              <input type="radio" name="permission" value="only me" class="user_type custom-switch-input">
                                                              <span class="custom-switch-indicator"></span>
                                                              <span class="custom-switch-description"><?php echo __('Only me'); ?></span>
                                                          </label>
                                                      </div>
                                                  </div>                                  
                                              </div>
                                          </div> 
                                      </div>
                                  @endif
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
  
              <div class="modal-footer bg-whitesmoke">
                  <button type="button" class="btn btn-primary" id="proxy_save"><i class="fa fa-save"></i> <?php echo __('Save'); ?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo __('Close'); ?></button>
              </div>
          </div>
      </div>
</div>
  
<div class="modal fade" id="update_proxy_modal" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" style="max-width:35% !important;">
          <div class="modal-content">
              <div class="modal-header bbw">
                  <h5 class="modal-title blue"><i class="fas fa-user-secret"></i> <?php echo __('Update Proxy Settings'); ?></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
  
              <div class="modal-body">
                  <div class="proxyModalBody">
                      
                  </div>
              </div>
  
              <div class="modal-footer bg-whitesmoke update-proxy-modal-footer">
                  <button type="button" class="btn btn-primary" id="proxy_update"><i class="fa fa-edit"></i> <?php echo __('Update'); ?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo __('Close'); ?></button>
              </div>
          </div>
      </div>
</div>

<script>
    "use strict";
    var base_url = '{{url('/')}}';
    var report_link = '{{route('proxy_settings')}}';
    var proxy_settings_data = '{{route('proxy_settings_data')}}';
    var delete_proxy = '{{route('delete_proxy')}}';
    var insert_proxy = '{{route('insert_proxy')}}';
    var ajax_update_proxy_info = '{{route('ajax_update_proxy_info')}}';
    var update_proxy_settings = '{{route('update_proxy_settings')}}';
    var csrf_token = '{{ csrf_token() }}';
    var global_lang_confirmation = "{{ __('Are you sure?') }}";
    var Proxy_Added = "{{ __('Proxy Added') }}";
    var Proxy_is_required = "{{ __('Proxy is required') }}";
    var Proxy_Port_is_required = "{{ __('Proxy Port is required') }}";

    var Proxy_Updated = "{{__('Proxy Updated') }}";
    var global_lang_error = '{{ __('Error') }}';
    var global_lang_warning = '{{ __('Warning') }}';
    var Doyouwanttodeletethisrecordfromdatabase = "{{ __('Do you want to detete this record?') }}";
    var Doyouwanttodeletealltheserecordsfromdatabase = "{{ __('Do you want to detete all the records from the database?') }}";
    var Proxy_Settings_has_been_Deleted_Successfully = "{{ __('Proxy Settings has been Deleted Successfully.') }}";
    var Something_went_wrong_please_try_once_again = "{{ __('Something went wrong, please try once again.') }}";

    <?php if(check_is_mobile_view()) echo 'var areWeUsingScroll = false;';
    else echo 'var areWeUsingScroll = true;';?>

</script>

<script src="{{asset('assets/custom-js/social-apps/proxy-settings.js')}}"></script>


  

@endsection

