{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Smtp settings'))
@section('content')


<style>.note-btn{padding: 0 10px !important}.note-editable{min-height:200px !important}</style>


<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fa fa-envelope"></i> <?php echo __('Smtp settings'); ?></h1>
    <?php if($test_btn == 1) { ?>
      <div class="section-header-button">
          <a class="btn btn-primary send_test_mail" href="">
              <i class="fas fa-paper-plane"></i> <?php echo __("Send Test Email"); ?>
          </a> 
      </div>
    <?php } ?>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("System"); ?></div>
      <div class="breadcrumb-item active"><a href="{{route('settings')}}"><?php echo __("Settings"); ?></a></div>
      <div class="breadcrumb-item"><?php echo __('Smtp settings'); ?></div>
    </div>
  </div>
  
	@include('shared.message')


  <div class="section-body">
    <div class="row">
      <div class="col-12">
          <form action="{{route("smtp_settings_action")}}" method="POST">
            @csrf
          <div class="card">
            <div class="card-body">              
                <div class="form-group">
                    <label for=""><i class="fa fa-at"></i> <?php echo __("Sender Email Address");?> </label>  
                    <input name="email_address" value="{{isset($xvalue->email_address) ? $xvalue->email_address :""}}"  class="form-control" type="email">              
                    @if ($errors->has('email_address'))
                    <span class="text-danger">{{ $errors->first('email_address') }}</span>
                    @endif
                </div>



                <div class="row">
                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for=""><i class="fa fa-server"></i>  <?php echo __("SMTP Host");?></label>
                      <input name="smtp_host" value="{{isset($xvalue->smtp_host) ? $xvalue->smtp_host :""}}" class="form-control" type="text">  
                      @if ($errors->has('smtp_host'))
                        <span class="text-danger">{{ $errors->first('smtp_host') }}</span>
                      @endif
                    </div>


                  </div>

                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for=""><i class="fas fa-plug"></i>  <?php echo __("SMTP Port");?></label>
                      <input name="smtp_port" value="{{isset($xvalue->smtp_port) ? $xvalue->smtp_port :""}}" class="form-control" type="text">  
                      @if ($errors->has('smtp_port'))
                        <span class="text-danger">{{ $errors->first('smtp_port') }}</span>
                      @endif
                    </div>
 

                  </div>
                </div>

                <div class="row">
                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for=""><i class="fas fa-user-circle"></i>  <?php echo __("SMTP User");?></label>
                      <input name="smtp_user" value="{{isset($xvalue->smtp_user) ? $xvalue->smtp_user :""}}" class="form-control" type="text">  
                      @if ($errors->has('smtp_user'))
                        <div class="text-danger">{{ $errors->first('smtp_user') }}</div>
                      @endif
                    </div>


                  </div>

                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for=""><i class="fas fa-key"></i>  <?php echo __("SMTP Password");?></label>

                      <input name="smtp_password" value="{{isset($xvalue->smtp_password) ? $xvalue->smtp_password :""}}" class="form-control" type="text">  
                      @if ($errors->has('smtp_password'))
                        <span class="text-danger">{{ $errors->first('smtp_password') }}</span>
                      @endif
                    </div>


                  </div>
                </div>

                <div class="form-group">
                  <label for="smtp_type" ><i class="fa fa-shield-alt"></i> <?php echo __('Connection Type');?>?</label>
                    <?php 
                    $smtp_type =isset($xvalue->smtp_type)?$xvalue->smtp_type:"";
                    if($smtp_type == '') $smtp_type='Default';
                    ?>
                    <div class="custom-switches-stacked mt-2">
                      <div class="row">   
                        <div class="col-4 col-md-2">
                          <label class="custom-switch">
                            <input type="radio" name="smtp_type" value="Default" class="custom-switch-input" <?php if($smtp_type=='Default') echo 'checked'; ?>>
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description"><?php echo __('Default'); ?></span>
                          </label>
                        </div>
                        <div class="col-4 col-md-2">
                          <label class="custom-switch">
                            <input type="radio" name="smtp_type" value="tls" class="custom-switch-input" <?php if($smtp_type=='tls') echo 'checked'; ?>>
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description"><?php echo __('TLS'); ?></span>
                          </label>
                        </div>
                        <div class="col-4 col-md-2">
                          <label class="custom-switch">
                            <input type="radio" name="smtp_type" value="ssl" class="custom-switch-input" <?php if($smtp_type=='ssl') echo 'checked'; ?>>
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description"><?php echo __('SSL'); ?></span>
                          </label>
                        </div>
                      </div>                                  
                    </div>
                    @if ($errors->has('smtp_type'))
                      <span class="text-danger">{{ $errors->first('smtp_type') }}</span>
                    @endif
                </div> 


            </div>

            <div class="card-footer bg-whitesmoke">
              <button class="btn btn-primary btn-lg" id="save-btn" type="submit"><i class="fas fa-save"></i> <?php echo __("Save");?></button>
              <button class="btn btn-secondary btn-lg float-right" onclick='goBack("admin/settings")' type="button"><i class="fa fa-remove"></i>  <?php echo __("Cancel");?></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<script>
  var send_test_email= '{{route('send_test_email')}}';
</script>

<script>
  $(document).ready(function($) {
    var base_url = 'url("/")'; 
    $('div.note-group-select-from-files').remove();

    $(document).on('click', '.send_test_mail', function(event) {
      event.preventDefault();
      $("#modal_send_test_email").modal();
    });

    $(document).on('click', '#send_test_email', function(event) {
      event.preventDefault();

      var email=$("#recipient_email").val();
      var subject=$("#subject").val();
      var message=$("#message").val(); 
      

      if(email=='') {
        $("#recipient_email").addClass('is-invalid');
        return false;
      }
      else {
        $("#recipient_email").removeClass('is-invalid');
      }

      if(subject=='') {
        $("#subject").addClass('is-invalid');
        return false;
      }
      else {
        $("#subject").removeClass('is-invalid');
      }

      if(message=='') {
        $("#message").addClass('is-invalid');
        return false;
      }
      else {
        $("#message").removeClass('is-invalid');
      }

      $(this).addClass('btn-progress');
      $("#show_message").html('');
      $.ajax({
        context: this,
        type:'POST' ,
        url: send_test_email,
        data:{email:email,message:message,subject:subject},
        success:function(response){

          $(this).removeClass('btn-progress');      
          
          if(response.error == false) {
            $("#show_message").addClass("alert alert-primary");
            $("#show_message").html(response.message);

          } else {
            $("#show_message").addClass("alert alert-danger");
            $("#show_message").html(response.message);
          }
        }
      });

    });
  });
  
</script>

<div class="modal fade" id="modal_send_test_email" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title text-primary"><i class="fa fa-paper-plane"></i> <?php echo __("Send Test Email");?></h5>
        <button type="button" onclick="javascript:window.location.reload()" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>

      <div id="modalBody" class="modal-body">        
        <div id="show_message" class="text-center"></div>

        <div class="row">
          <div class="col-12 col-md-6">
            <div class="form-group">
              <label for="recipient_email"><i class="fas fa-at"></i> <?php echo __("Recipient Email"); ?></label>
              <input type="text" id="recipient_email" class="form-control"/>
              <div class="invalid-feedback"><?php echo __("Email is required"); ?></div>
            </div>

            
          </div>
          <div class="col-12 col-md-6">
            <div class="form-group">
              <label for="subject"><i class="far fa-lightbulb"></i> <?php echo __("Subject"); ?></label>
              <input type="text" id="subject" class="form-control"/>
              <div class="invalid-feedback"><?php echo __("Subject is required"); ?></div>
            </div>
          </div>

          <div class="col-12">
            <div class="form-group">
              <label for="message"><i class="fas fa-envelope"></i> <?php echo __("Message"); ?></label>
              <textarea name="message" style="height:300px !important;" class="summernote form-control" id="message"></textarea>
              <div class="invalid-feedback"><?php echo __("Message is required"); ?></div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer bg-whitesmoke">
        <button id="send_test_email" class="btn-lg btn btn-primary" > <i class="fas fa-paper-plane"></i>  <?php echo __("Send"); ?></button>
        <button type="button" onclick="javascript:window.location.reload()" class="btn-lg btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo __("Close"); ?></button>
      </div>
    </div>
  </div>
</div>

@endsection