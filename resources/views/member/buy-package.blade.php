{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',$page_title)
@section('content')

<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-cart-plus"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-button">
      <a href="{{route('transaction_log')}}" class="btn btn-primary"><i class="fas fa-history"></i> <?php echo __("Transaction Log"); ?></a>
    </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("Payment"); ?></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></a></div>
    </div>
  </div>

  <div class="section-body">
    
    <div class="row">
      <?php 
      foreach($payment_package as $pack)
      {?>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="pricing <?php if($pack->highlight=='1') echo 'pricing-highlight';?>">
            <div class="pricing-title">
              <?php echo $pack->package_name; ?>
            </div>
            <div class="pricing-padding">
              <div class="pricing-price">
                <div><?php echo $curency_icon; ?></sup><?php echo $pack->price?></div>
                <div><?php echo $pack->validity?> <?php echo __("days"); ?></div>
              </div>
              <div class="pricing-details nicescroll" style="height: 180px;">
                <?php 
                $module_ids=$pack->module_ids;
                $monthly_limit=json_decode($pack->monthly_limit,true);
                // $module_names_array=$this->basic->execute_query('SELECT module_name,id FROM modules WHERE FIND_IN_SET(id,"'.$module_ids.'") > 0  ORDER BY module_name ASC');
                $module_names_array=DB::select('SELECT module_name, id FROM modules WHERE FIND_IN_SET(id, ?) > 0 ORDER BY module_name ASC', [$module_ids]);

                foreach ($module_names_array as $row)
                {                              
                    $limit=0;
                    $limit=$monthly_limit[$row->id];
                    if($limit=="0") $limit2=__("unlimited");
                    else $limit2=$limit;
                    $limit2=" : ".$limit2;
                    echo '
                    <div class="pricing-item">
                      <div class="pricing-item-icon_x bg-light_x"><i class="fas fa-check"></i></div>
                      <div class="pricing-item-label">&nbsp;'.__($row->module_name).$limit2.'</div>
                    </div>';
                } ?>
                                
              </div>
            </div>
            <div class="pricing-cta">
              <a href="" class="choose_package" data-id="<?php echo $pack->id;?>"><?php echo __("Select Package"); ?> <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
      <?php 
      } ?>
    </div>
  </div>
</section>

<div class="modal fade" tabindex="-1" role="dialog" id="payment_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-cart-plus"></i> <?php echo __("Payment Options");?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center" id="waiting" style="width: 100%;margin: 20px 0;"><i class="fas fa-spinner fa-spin blue" style="font-size:40px;"></i></div>
        <div id="button_place"></div>
        <br>
        <?php 
        if ($last_payment_method != '')
        { 
          
          $payment_type = ($has_reccuring == 'true') ? __('Recurring') : __('Manual');

          echo '<br><div class="alert alert-light alert-has-icon">
                  <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                  <div class="alert-body">
                    <div class="alert-title">'.__("Last Payment").'</div>
                    '.__("Last Payment").' : '.$last_payment_method.' ('.$payment_type.')
                  </div>
                </div>';
        }?>
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <?php if ('yes' == $manual_payment): ?>
          <button type="button" id="manual-payment-button" class="btn btn-info"><?php echo __('Manual Payment'); ?></button>      
        <?php endif; ?>
        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fa fa-remove"></i> <?php echo __("Close"); ?></button>
      </div>
    </div>
  </div>
</div>

<?php if ('yes' == $manual_payment): ?>
<div class="modal fade" role="dialog" id="manual-payment-modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-file-invoice-dollar"></i> <?php echo __("Manual payment");?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">

          <?php if (isset($manual_payment_instruction) && ! empty($manual_payment_instruction)): ?>
          <div class="row">
            <div class="col-lg-12 mb-4">
              <!-- Manual payment instruction -->
              <h6  class="display-6"><i class="far fa-lightbulb"></i> <?php echo __('Manual payment instructions'); ?></h6>
                  <?php echo $manual_payment_instruction; ?>
            </div>
          </div>
          <?php endif; ?>

          <!-- Paid amount and currency -->
          <div class="row">
            <div class="col-lg-6 mb-4">
              <div class="form-group">
                <label for="paid-amount"><i class="fa fa-money-bill-alt"></i> <?php echo __('Paid Amount'); ?>:</label>
                <input type="number" name="paid-amount" id="paid-amount" class="form-control" min="1">
                <input type="hidden" id="selected-package-id">
              </div>
            </div>
            <div class="col-lg-6 mb-4">
              <div class="form-group">
                <label for="paid-currency"><i class="fa fa-coins"></i> <?php echo __('Currency'); ?></label>              
                <?php echo Form::select('paid-currency', $currency_list, $currency, ['id' => 'paid-currency', 'class' => 'form-control select2','style'=>'width:100%']); ?>
              </div>
            </div>
          </div>          
          
          <div class="row">
            <!-- Image upload - Dropzone -->
            <div class="col-lg-6">
              <div class="form-group">
                <label><i class="fa fa-paperclip"></i> <?php echo __('Attachment'); ?> <?php echo __('(Max 5MB)');?> </label>
                <div id="manual-payment-dropzone" class="dropzone mb-1">
                  <div class="dz-default dz-message">
                    <input class="form-control" name="uploaded-file" id="uploaded-file" type="hidden">
                    <span style="font-size: 20px;"><i class="fas fa-cloud-upload-alt" style="font-size: 35px;color: #6777ef;"></i> <?php echo __('Upload'); ?></span>
                  </div>
                </div>
                <span class="red">Allowed types: pdf, doc, txt, png, jpg and zip</span>
              </div>
            </div>

            <!-- Additional Info -->
            <div class="col-lg-6">
              <div class="form-group">
                <label for="paid-amount"><i class="fa fa-info-circle"></i> <?php echo __('Additional Info'); ?>:</label>
                &nbsp;
                <textarea name="additional-info" id="additional-info" class="form-control"></textarea>
              </div>
            </div>  
          </div>

        </div><!-- ends container -->
      </div><!-- ends modal-body -->

      <!-- Modal footer -->
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" id="manual-payment-submit" class="btn btn-primary"><?php echo __('Submit'); ?></button>      
        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fa fa-remove"></i> <?php echo __("Close"); ?></button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
  "use strict";

  var manual_payment_upload_file = '{{ route("manual_payment_upload_file") }}';
  var manual_payment = '{{ route("manual_payment") }}';
  var manual_payment_delete_file = '{{ route("manual_payment_delete_file") }}';

  var Subscription_Message = '{{ __("Subscription Message") }}';
  var You_have_already_a_subscription_enabled_in_paypal = '{{ __("You have already a subscription enabled in paypal. If you want to use different paypal or different package, make sure to cancel your previous subscription from your paypal.") }}';




</script>


<script>
  $(document).ready(function() {

    // Fixes multiple modal issues
    $('.modal').on("hidden.bs.modal", function (e) { 
      if ($('.modal:visible').length) { 
        $('body').addClass('modal-open');
      }
    });

   
      payment_modal = $('#payment_modal');

    function get_payment_button(package) 
    {
      $("#waiting").show();
      $("#button_place").html('');
      $("#payment_modal").modal();
      $.ajax
      ({
          type:'POST',
          data:{package:package},
          url:base_url+'payment/payment_button/',
          success:function(response)
           {
               $("#waiting").hide();
               $("#button_place").html(response);
           }
              
       }); 
    }    

    $(document).on('click', ".choose_package", function(e) {
       e.preventDefault();           
       var package=$(this).attr('data-id');
       // Sets package id for manual payment
       $('#selected-package-id').val(package);
       var redirect_url = base_url+'/payment/payment_button/'+package;
       var has_reccuring = <?php echo $has_reccuring; ?>;
       if(has_reccuring)  
       {
        swal(Subscription_Message, You_have_already_a_subscription_enabled_in_paypal)
        .then((value) => {
          window.location.assign(redirect_url)            
        });
      }
      else window.location.assign(redirect_url)
    });
  });
</script>

<?php if ('yes' == $manual_payment): ?>
<script>
  
  $(document).ready(function() {

    $(document).on('click', '#manual-payment-button', function() {
      $('#payment_modal').modal('toggle');
      $('#manual-payment-modal').modal();
    });

    // Uploads files
    var uploaded_file = $('#uploaded-file');
    Dropzone.autoDiscover = false;
    $("#manual-payment-dropzone").dropzone({ 
      url: manual_payment_upload_file,
      maxFilesize:5,
      uploadMultiple:false,
      paramName:"file",
      createImageThumbnails:true,
      acceptedFiles: ".pdf,.doc,.txt,.png,.jpg,.jpeg,.zip",
      maxFiles:1,
      addRemoveLinks:true,
      success:function(file, response) {
        var data = JSON.parse(response);

        // Shows error message
        if (data.error) {
          swal({
            icon: 'error',
            text: data.error,
            title: global_lang_error
          });
          return;
        }

        if (data.filename) {
          $(uploaded_file).val(data.filename);
        }
      },
      removedfile: function(file) {
        var filename = $(uploaded_file).val();
        delete_uploaded_file(filename);
      },
    });

    // Handles form submit
    $(document).on('click', '#manual-payment-submit', function() {
      
      // Reference to the current el
      var that = this;

      // Shows spinner
      $(that).addClass('disabled btn-progress');

      var data = {
        paid_amount: $('#paid-amount').val(),
        paid_currency: $('#paid-currency').val(),
        package_id: $('#selected-package-id').val(),
        additional_info: $('#additional-info').val(),
      };

      $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: manual_payment,
        data: data,
        success: function(response) {
          if (response.success) {
            // Hides spinner
            $(that).removeClass('disabled btn-progress');

            // Empties form values
            empty_form_values();
            $('#selected-package-id').val('');  

            // Shows success message
            swal({
              icon: 'success',
              title: global_lang_success,
              text: response.success,
            });

            // Hides modal
            $('#manual-payment-modal').modal('hide');
          }

          // Shows error message
          if (response.error) {
            // Hides spinner
            $(that).removeClass('disabled btn-progress');

            swal({
              icon: 'error',
              title: global_lang_error,
              text: response.error,
            });
          }
        },
        error: function(xhr, status, error) {
          $(that).removeClass('disabled btn-progress');
        },
      });
    });

    $('#manual-payment-modal').on('hidden.bs.modal', function (e) {
      var filename = $(uploaded_file).val();
      delete_uploaded_file(filename);
      $('#selected-package-id').val(''); 
    });

    function delete_uploaded_file(filename) {
      if('' !== filename) {     
        $.ajax({
          type: 'POST',
          dataType: 'JSON',
          data: { filename },
          url: manual_payment_delete_file,
          success: function(data) {
            $('#uploaded-file').val('');
          }
        });
      }

      // Empties form values
      empty_form_values();     
    }

    // Empties form values
    function empty_form_values() {
      $('#paid-amount').val(''),
      $('.dz-preview').remove();
      $('#additional-info').val(''),
      $('#paid-currency').prop("selectedIndex", 0);
      $('#manual-payment-dropzone').removeClass('dz-started dz-max-files-reached');

      // Clears added file
      Dropzone.forElement('#manual-payment-dropzone').removeAllFiles(true);
    }

  });
</script>
<?php endif; ?>

@endsection