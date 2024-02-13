{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Keyword tracking lists'))
@section('content')


<link rel="stylesheet" href="{{ asset('assets/custom-css/seo-tools/keyword-tracking/keyword-tracking.css') }}">

  <section class="section section_custom">
    <div class="section-header">
      <h1><i class="fa fa-trophy"></i> <?php echo __('Keyword tracking lists'); ?></h1>
        <div class="section-header-button">
          <a class="btn btn-primary" id="add_new_keyword" href="#">
            <i class="fas fa-plus-circle"></i> <?php echo __("Add Keyword"); ?>
          </a> 
        </div>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{route('keyword_tracking')}}"><?php echo __('Keyword Tracking'); ?></a></div>
        <div class="breadcrumb-item"><?php echo __('Keyword tracking lists'); ?></div>
      </div>
    </div>
  
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body data-card">
              <div class="row">
  
                  <div class="col-md-6 col-12">
                      <div class="input-group float-left" id="searchbox">
  
                          <input type="text" class="form-control" id="keyword_searching" name="keyword_searching" placeholder="<?php echo __('Keyword'); ?>" aria-label="" aria-describedby="basic-addon2">
                      </div>
                  </div>
                  <div class="col-md-6 col-12">
                      <a href="javascript:;" id="post_date_range" class="btn btn-primary btn-lg icon-left float-right btn-icon"><i class="fas fa-calendar"></i> <?php echo __("Choose Date");?></a><input type="hidden" id="keyword_post_date_range_val">
                      <a class="btn btn-lg btn-outline-danger float-right delet_all_keywords mr-1" href=""><i class="fas fa-trash-alt"></i> <?php echo __('Delete'); ?>
                      </a>
                  </div>
              </div>
              
              <div class="table-responsive2">
                  <table class="table table-bordered" id="mytable">
                  <thead>
                      <tr>
                                  <th>#</th> 
                                  <th style="vertical-align:middle;width:20px">
                                      <input class="regular-checkbox" id="datatableSelectAllRows" type="checkbox"/><label for="datatableSelectAllRows"></label>        
                                  </th> 
                                  <th><?php echo __("ID"); ?></th>            
                      <th><?php echo __("Keyword"); ?></th>         
                      <th><?php echo __("Website"); ?></th>         
                      <th><?php echo __("Country"); ?></th>         
                      <th><?php echo __("Language"); ?></th>         
                      <th><?php echo __("Created At"); ?></th>         
                      <th><?php echo __("Actions"); ?></th>
  
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
  
  
  <div class="modal fade" id="new_keyword_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bbw">
          <h5 class="modal-title blue"><i class="fas fa-plus-circle"></i> <?php echo __('Keyword Position Tracking'); ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <form action="#" id="keyword_tracking_form">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label><?php echo __('Keyword'); ?></label>
                      <input type="text" class="form-control" id="keyword" name="keyword">
                    </div>
                  </div>
  
                  <div class="col-12">
                    <div class="form-group">
                      <label><?php echo __('Website'); ?></label>
                      <input type="text" class="form-control" id="website" name="website">
                    </div>
                  </div>
  
                  <div class="col-12">
                    <div class="form-group">
                      <label><?php echo __('Country'); ?></label>
                      @php 
											$default_country = config("my_config.country_name") ?? 'bd';
                      $select_note_con['']=__("Select country");
											$country =$select_note_con +get_country_names();
											echo Form::select('country',$country,$default_country,array('class'=>'form-control select','id'=>'country'));
									 	@endphp	
                    </div>
                  </div>
  
                  <div class="col-12">
                    <div class="form-group">
                      <label><?php echo __('Language'); ?></label>
                      @php 
											$default_language = config("my_config.language") ?? 'en';
                      $select_note['']=__("Select language");
											$language =$select_note + get_language_list();
											echo Form::select('language',$language,$default_language,array('class'=>'form-control select','id'=>'language'));
									 	@endphp	
                    </div>
                  </div>
                </div>  
              </form>
            </div>
          </div>
        </div>
          <div class="modal-footer bg-whitesmoke">
            <button type="button" class="btn btn-primary" id="new_search_button"><i class="fas fa-save"></i> <?php echo __('Save'); ?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo __('Close'); ?></button>
          </div>
      </div>
    </div>
  </div>


  <script>
    "use strict";
   var keyword_list_data = '{{route('keyword_list_data')}}';
   var delete_keyword_action = '{{route('delete_keyword_action')}}';
   var keyword_tracking_settings_action = '{{route('keyword_tracking_settings_action')}}';
   var delete_selected_keyword_action = '{{route('delete_selected_keyword_action')}}';
   var Keyword_Created = '{{ __("Keyword Created") }}';
   var Keyword_Rejected = '{{ __("Keyword Rejected") }}';
   var Usage_Warning = '{{ __("Usage Warning") }}';
   var redirect_url = '{{route('keyword_tracking_index')}}';
   var report_url = '{{url('/payment/usage_history')}}';
   var Domain_has_been_Deleted_Successfully = "{{ __('Domain has been Deleted Successfully.') }}";
   var Please_select_keyword_to_delete = "{{ __('Please select keyword to delete.') }}";
   var global_lang_choose_data = '{{ __('Date') }}';
   var global_lang_last_30_days = '{{ __("Last 30 Days") }}';
   var global_lang_this_month = '{{ __("This Month") }}';
   var global_lang_See_Report = '{{ __("See Report") }}';
   var global_lang_last_month = '{{ __("Last Month") }}';
   var Selected_Keyword_has_been_deleted_Successfully = '{{ __('Selected Keyword has been deleted Successfully') }}';
  </script>
    
  <script src="{{asset('assets/custom-js/keyword-tracking/tracking-list.js')}}"></script>


@endsection



