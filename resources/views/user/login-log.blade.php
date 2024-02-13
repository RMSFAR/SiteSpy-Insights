{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',$page_title)
@section('content')



<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-history"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-button">
    </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("Subscription"); ?></div>
      <div class="breadcrumb-item active"><a href="{{route('user_manager')}}"><?php echo __("User Manager"); ?></a></div>
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
                    <th><?php echo __("User"); ?></th>      
                    <th><?php echo __("Email"); ?></th>      
                    <th><?php echo __("Login Time"); ?></th>
                    <th><?php echo __("Login IP"); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sl=0;
                  foreach ($info as $key => $value) 
                  {
                     $sl++;
                     $id = $value->user_id;
                     echo "<tr>";
                       echo "<td>".$sl."</td>";
                       echo "<td><a href='".url('user/edit_user/'.$id)."'>".$value->user_name."</a></td>";
                       echo "<td>".$value->user_email."</td>";
                       echo "<td>".$value->login_time."</td>";
                       echo "<td>".$value->login_ip."</td>";
                     echo "</tr>";
                  }
                  ?>
                </tbody>
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
  var globaloptions = '{{ __("Options") }}';
  var Delete_old_data = '{{ __("Delete old data") }}';

  var delete_user_log = '{{ route("delete_user_log") }}';



</script>


<script>       


    var drop_menu = '<div class="btn-group dropleft float-right"><button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  '+globaloptions+'  </button>  <div class="dropdown-menu dropleft"> <a class="dropdown-item are_you_sure_datatable" data-refresh="0" href="'+delete_user_log+'"><i class="fas fa-trash"></i> '+Delete_old_data+'</a></div> </div>';
      setTimeout(function(){ 
        $("#mytable_filter").append(drop_menu); 
    }, 2000);
      
   
    $(document).ready(function() {

      var perscroll;
      var table = $("#mytable").DataTable({          
          processing:true,
          bFilter: true,
          order: [[ 3, "desc" ]],
          pageLength: 25,
          language: 
          {
            url: datatable_lang_file
          },
          dom: '<"top"f>rt<"bottom"lip><"clear">',
          columnDefs: [            
            {
                targets: [3,4],
                className: 'text-center'
            },
            {
                targets: [0],
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
              <form class="form-horizontal" action="{{route('change_user_password_action')}}" method="POST">
                @csrf
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
              <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-remove"></i> <?php echo __("Close"); ?></button>
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
          <textarea name="message" style="height:300px !important;" class="form-control" id="message"></textarea>
          <div class="invalid-feedback"><?php echo __("Message is required"); ?></div>
        </div>
     
      </div>

      <div class="modal-footer bg-whitesmoke br">
           <button id="send_sms_email" class="btn btn-primary btn-lg" > <i class="fas fa-paper-plane"></i>  <?php echo __("Send"); ?></button>
            <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-remove"></i> <?php echo __("Close"); ?></button>
      </div>
    </div>
  </div>
</div>


@endsection