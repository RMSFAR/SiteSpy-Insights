{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',$page_title)
@section('content')




<input type="hidden" name="csrf_token" id="csrf_token" value="{{csrf_token()}}">
<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-users"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-button">
     <a class="btn btn-primary"  href="{{route('add_user')}}">
        <i class="fas fa-plus-circle"></i> <?php echo __("New User"); ?>
     </a> 
    </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("Subscription"); ?></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

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
                    <th><?php echo __("Avatar"); ?></th>      
                    <th><?php echo __("Name"); ?></th>      
                    <th><?php echo __("Email"); ?></th>
                    <th><?php echo __("Package"); ?></th>
                    <th><?php echo __("Status"); ?></th>
                    <th><?php echo __("Type"); ?></th>
                    <th><?php echo __("Expiry"); ?></th>
                    <th style="min-width: 150px"><?php echo __("Actions"); ?></th>
                    <th><?php echo __("Registered"); ?></th>
                    <th><?php echo __("Last Login"); ?></th>
                    <th><?php echo __("Last IP"); ?></th>
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

{{-- <?php
$drop_menu = '<div class="btn-group dropleft float-right"><button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  '.__("Options").'  </button>  <div class="dropdown-menu dropleft"> <a class="dropdown-item has-icon send_email_ui pointer"><i class="fas fa-paper-plane"></i> '.__("Send Email").'</a> <a class="dropdown-item has-icon" href="'.base_url('admin/login_log').'"><i class="fas fa-history"></i> '.__("Login Log").'</a>';
// if($this->session->userdata('license_type') == 'double')
//   $drop_menu .= '<a target="_BLANK" class="dropdown-item has-icon" href="'.base_url('dashboard/index/system').'"><i class="fas fa-tachometer-alt"></i> '.__("System Dashboard").'</a><a target="_BLANK" class="dropdown-item has-icon" href="'.base_url('admin/activity_log').'"><i class="fas fa-history"></i> '.__("User Activity Log").'</a>';
$drop_menu .= '</div> </div>';
?>  --}}

<script>
  "use strict";
  var login_log= '{{ route("login_log") }}';
  var user_manager_data = '{{ route("user_manager_data") }}';
  var change_user_password_action = '{{ route("change_user_password_action") }}';
  var send_email_member = '{{ route("send_email_member") }}';

</script>


<script>       

 
  $(document).ready(function() {

    $('div.note-group-select-from-files').remove();

    var drop_menu ='<div class="btn-group dropleft float-right"><button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  '+("Options")+'  </button>  <div class="dropdown-menu dropleft"> <a class="dropdown-item has-icon send_email_ui pointer"><i class="fas fa-paper-plane"></i> '+("Send Email")+'</a> <a class="dropdown-item has-icon" href="'+login_log+'"><i class="fas fa-history"></i> '+("Login Log")+'</a>'+'</div> </div>';

    setTimeout(function(){ 
      $("#mytable_filter").append(drop_menu); 
    }, 2000);
    
    var perscroll;
    var table = $("#mytable").DataTable({
        serverSide: true,
        processing:true,
        bFilter: true,
        order: [[ 2, "desc" ]],
        pageLength: 10,
        ajax: {
            "url": user_manager_data,
            "type": 'POST',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
        },
        language: 
        {
          url: datatable_lang_file
        },
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        columnDefs: [
          {
              targets: [2,8],
              visible: false
          },
          {
              targets: [0,1,3,7,9,10,11,13],
              className: 'text-center'
          },
          {
              targets: [0,1,3,10],
              sortable: false
          }
        ],
        fnInitComplete:function(){  // when initialization is completed then apply scroll plugin
            if(areWeUsingScroll)
            {
              if (perscroll) perscroll.destroy();
              perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
            }
        },
        scrollX: 'auto',
        fnDrawCallback: function( oSettings ) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again 
            if(areWeUsingScroll)
            {
              if (perscroll) perscroll.destroy();
              perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
            }
        }
    });

    $(document).on('click', '.change_password', function(e) {
      e.preventDefault();

      var user_id = $(this).attr('data-id');
      var user_name = $(this).attr('data-user');

      $("#putname").html(user_name);
      $("#putid").val(user_id);

      $("#change_password").modal();
    });

    var confirm_match=0;
    $(".password").keyup(function(){
      
        var new_pass=$("#password").val();
        var conf_pass=$("#confirm_password").val();

        if(new_pass=='' || conf_pass=='') 
        {
          return false;
        }

        if(new_pass==conf_pass)
        {
            confirm_match=1;
            $("#password").removeClass('is-invalid');
            $("#confirm_password").removeClass('is-invalid');
        }
        else
        {
            confirm_match=0;
            $("#confirm_password").addClass('is-invalid');
        }

    });

    $(document).on('click', '#save_change_password_button', function(e) {
      e.preventDefault();

      var user_id =  $("#putid").val();
      var password =  $("#password").val();
      var confirm_password =  $("#confirm_password").val();
      var csrf_token = $("#csrf_token").val();

      password = password.trim();
      confirm_password = confirm_password.trim();

      if(password=='' || confirm_password=='')
      {
          $("#password").addClass('is-invalid');
          return false;
      }
      else
      {
          $("#password").removeClass('is-invalid');
      }

      if(confirm_match=='1')
      {
          $("#confirm_password").removeClass('is-invalid');
      }
      else
      {
          $("#confirm_password").addClass('is-invalid');
          return false;
      }

      $("#save_change_password_button").addClass("btn-progress");

      $.ajax({
      url: change_user_password_action,
      type: 'POST',
      dataType: 'JSON',
      beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
      },
      data: {user_id:user_id,password:password,confirm_password:confirm_password,csrf_token:csrf_token},
        success:function(response)
        {
          $("#save_change_password_button").removeClass("btn-progress");

          if(response.status == "1")  
            swal(global_lang_success,response.message, 'success')
           .then((value) => {
               $("#change_password").modal('hide');
            });

          else  swal(global_lang_error,response.message, 'error');
        }
    });

    });


    $(document).on('click', '.send_email_ui', function(e) {
      var user_ids = [];
      $(".datatableCheckboxRow:checked").each(function ()
      {
          user_ids.push(parseInt($(this).val()));
      });
      
      if(user_ids.length==0) 
      {
        swal(global_lang_warning, global_You_have_to_select_users_to_send_email, 'warning');
        return false;
      }
      else  $("#modal_send_sms_email").modal();
    });

    $(document).on('click', '#send_sms_email', function(e) { 
              
      var subject=$("#subject").val();
      var message=$("#message").val(); 
      var csrf_token = $("#csrf_token").val();

      var user_ids = [];
      $(".datatableCheckboxRow:checked").each(function ()
      {
          user_ids.push(parseInt($(this).val()));
      });
      
      if(user_ids.length==0) 
      {
        swal(global_lang_warning, global_You_have_to_select_users_to_send_email, 'warning');
        return false;
      }

      if(subject=='')
      {
          $("#subject").addClass('is-invalid');
          return false;
      }
      else
      {
          $("#subject").removeClass('is-invalid');
      }

      if(message=='')
      {
          $("#message").addClass('is-invalid');
          return false;
      }
      else
      {
          $("#message").removeClass('is-invalid');
      }

      $(this).addClass('btn-progress');
      $("#show_message").html('');
      $.ajax({
      context: this,
      type:'POST' ,
      url: send_email_member,
      beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
      },
      data:{message:message,user_ids:user_ids,subject:subject,csrf_token:csrf_token},
      success:function(response){
        $(this).removeClass('btn-progress');                  
        $("#show_message").addClass("alert alert-primary");
        $("#show_message").html(response);
      }
    }); 

  });
});

 

</script>



<div class="modal fade" tabindex="-1" role="dialog" id="change_password" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-key"></i> <?php echo __("Change Password");?> (<span id="putname"></span>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">  
              <form class="form-horizontal" action="<?php echo url('/').'admin/change_user_password_action';?>" method="POST">
                <div id="wait"></div>
                <input id="putid" value="" class="form-control" type="hidden">           
                <div class="form-group">
                  <label for="password"><?php echo __("New Password"); ?> *  </label>                  
                  <input id="password" class="form-control password" type="password">             
                  <div class="invalid-feedback"><?php echo __("You have to type new password twice"); ?></div>
                </div>

                <div class="form-group">
                    <label for="confirm_password"><?php echo __("Confirm New Password"); ?> * </label>                  
                    <input id="confirm_password"  class="form-control password" type="password">             
                   <div class="invalid-feedback"><?php echo __("Passwords does not match"); ?></div>
                </div>
              </form>            
            </div>


            <div class="modal-footer bg-whitesmoke br">
              <button type="button" id="save_change_password_button" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> <?php echo __("Save"); ?></button>
              <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo __("Close"); ?></button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="modal_send_sms_email" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-paper-plane"></i> <?php echo __("Send Email");?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div id="modalBody" class="modal-body">        
        <div id="show_message" class="text-center"></div>

        <div class="form-group">
          <label for="subject"><?php echo __("Subject"); ?> *</label><br/>
          <input type="text" id="subject" class="form-control"/>
          <div class="invalid-feedback"><?php echo __("Subject is required"); ?></div>
        </div>

        <div class="form-group">
          <label for="message"><?php echo __("Message"); ?> *</label><br/>
          <textarea name="message" style="height:300px !important;" class="summernote form-control" id="message"></textarea>
          <div class="invalid-feedback"><?php echo __("Message is required"); ?></div>
        </div>
     
      </div>

      <div class="modal-footer">
           <button id="send_sms_email" class="btn-lg btn btn-primary" > <i class="fas fa-paper-plane"></i>  <?php echo __("Send"); ?></button>
            <button type="button" class="btn-lg btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo __("Close"); ?></button>
      </div>
    </div>
  </div>
</div>


@endsection