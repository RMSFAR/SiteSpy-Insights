{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Transaction log'))
@section('content')


<section class="section section_custom">
    <div class="section-header">
      <h1><i class="fas fa-history"></i> <?php echo $page_title; ?></h1>
      <div class="section-header-button">
        <a href="{{route('transaction_log_manual')}}" class="btn btn-primary"><i class="fas fa-hand-holding-usd"></i> <?php echo __('Manual Transaction Log'); ?></a> 
      </div>
      <div class="section-header-breadcrumb">
        <?php 
        if(session("user_type")=="Admin") 
        echo '<div class="breadcrumb-item">'.__("Subscription").'</div>';
        else echo '<div class="breadcrumb-item">'.__("Payment").'</div>';
        ?>
        <div class="breadcrumb-item"><?php echo $page_title; ?></div>
      </div>
    </div>
  
    {{-- <?php $this->load->view('admin/theme/message'); ?> --}}
    @include('shared.message')
    
    <div class="section-body">
  
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body data-card">
              <div class="table-responsive2">
                <table class="table table-bordered" id="mytable">
                  <thead>
                    <tr>
                      <th>#</th>      
                      <th style="vertical-align:middle;width:20px">
                          <input class="regular-checkbox" id="datatableSelectAllRows" type="checkbox"/><label for="datatableSelectAllRows"></label>        
                      </th>
                      <th><?php echo __("ID"); ?></th>      
                      <th><?php echo __("Email"); ?></th>      
                      <th><?php echo __("First Name"); ?></th>      
                      <th><?php echo __("Last Name"); ?></th>      
                      <th><?php echo __("Method"); ?></th>
                      <th><?php echo __("Cycle Start"); ?></th>
                      <th><?php echo __("cycle End"); ?></th>
                      <th><?php echo __("Paid at"); ?></th>
                      <th><?php echo __("Amount")." ".$curency_icon; ?></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th><?php echo __("Total"); ?></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                    </tr>
                  </tfoot>
                </table>
              </div>             
            </div>
  
          </div>
        </div>
      </div>
      
    </div>
  </section>


  


<script>
    "use strict";
    var global_lang_choose_data = '{{ __('Choose Date') }}';
    var curency_icon ='{{ $curency_icon }}';
    var transaction_log_data = '{{ route("transaction_log_data") }}';
</script>




<script src="{{asset('/assets/custom-js/subscription/transaction-log.js')}}"></script>

 

@endsection