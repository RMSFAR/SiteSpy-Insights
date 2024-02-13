{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',$page_title)
@section('content')

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<style type="text/css" media="screen">
  #payment_options .list-group-item-action{margin-bottom: 30px;}
  #payment_options .list-group-item-action{margin-bottom: 30px;}
  #payment_options img{margin-right: 20px;}
</style>

<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-cart-plus"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-button">
      <a href="{{route('transaction_log')}}" class="btn btn-primary"><i class="fas fa-history"></i> <?php echo __("Transaction Log"); ?></a>
    </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{url('/payment/buy_package')}}"><?php echo __("Payment"); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></a></div>
    </div>
  </div>

  <div class="section-body">

    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-cart-plus"></i> <?php echo __("Payment Options");?></h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
                @if($no_payment_found_error)
                    <div class="alert alert-danger p-4 mt-4">
                        <h4 class="alert-heading">{{__('No payment method found')}}</h4>
                        <p class="mt-2">{{__('The application administrator has not yet set up a payment option, or receiving payments has been temporarily disabled. Please notify the administrator about this situation.')}}</p>
                    </div>
                @else
                    <?php echo $buttons_html;?>
                @endif
            </div>
          </div>
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
 
    </div>

  </div>
</section>



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
                <input type="hidden" id="selected-package-id" value="<?php echo $package_id; ?>">
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
                    <span style="font-size: 20px;"><i class="fas fa-cloud-upload-alt" style="font-size: 35px;color: var(--blue);"></i> <?php echo __('Upload'); ?></span>
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

<script>
  "use strict";

  var manual_payment_upload_file = '{{ route("manual_payment_upload_file") }}';
  var manual_payment = '{{ route("manual_payment") }}';
  var manual_payment_delete_file = '{{ route("manual_payment_delete_file") }}';


</script>

<script type="text/javascript">
  $(document).ready(function(){
      $('.modal').on("hidden.bs.modal", function (e) { 
        if ($('.modal:visible').length) { 
          $('body').addClass('modal-open');
        }
      });
  });
</script>

<script>
  "use strict";


  function delete_uploaded_file(filename) {
    if('' !== filename) {     
      $.ajax({
        type: 'POST',
        dataType: 'JSON',
        data: { filename },
        beforeSend: function (xhr) {
              xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        },
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
  }

  $(document).ready(function() {
    $(document).ready(function(){
      $(document).on('click', '#manual-payment-button', function(event) {
        event.preventDefault();
        $('#manual-payment-modal').modal('show');
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
          beforeSend: function (xhr) {
              xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          },
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          success: function(response) {
            if (response.success) {
              // Hides spinner
              $(that).removeClass('disabled btn-progress');

              // Empties form values
              empty_form_values();
              $('#selected-package-id').val('');  

              // Shows success message
              Swal.fire({
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

              Swal.fire({
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
        $('#selected-package-id').val(''); 
      });

    });
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
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    success:function(file, response) {
      // Shows error message
      if (response.error) {
        swal({
          icon: 'error',
          text: response.error,
          title: global_lang_error
        });
        return;
      }

      if (response.filename) {
        $(uploaded_file).val(response.filename);
      }
    },
    removedfile: function(file) {
      var filename = $(uploaded_file).val();
      delete_uploaded_file(filename);
    },
  });
</script>
@endsection
