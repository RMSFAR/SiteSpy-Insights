@extends('design.app')
@section('title',$page_title)
@section('content')



<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-plug"></i> <?php echo $page_title; ?></h1>    
    <div class="section-header-button">
      <a class="btn btn-primary" href="{{route('addons_upload')}}"><i class="fas fa-cloud-upload-alt"></i> <?php echo __('Install Add-on');?></a>
    </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("System"); ?></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  {{-- <?php $this->load->view('admin/theme/message'); ?> --}}
	@include('shared.message')

  <?php if(session()->flash('addon_uplod_success')!="") echo "<div class='alert alert-success text-center'><i class='fa fa-check'></i> ".session()->flash('addon_uplod_success')."</div>";?>

   <div class="section-body">
      <?php 
      if(!empty($add_on_list))
      {       
        $i=0;
        echo "<div class='row'>";
        foreach($add_on_list as $value)
        {
          $i++;
          //(removing .php from controller name, that makes moduleFolder/controller name)
          $module_controller=str_replace('.php','',strtolower($value['controller_name']));
          ?>
          <div class="col-12 col-sm-6 col-md-4">
            <?php 
              $asset_path=$module_controller.'/thumb.png'; 
              $thumb = get_addon_asset($style="",$type="image",$asset_path,$css_class="img-thumbnail profile-widget-picture","");
              if($thumb=="") $thumb ='<img src="'.asset('assets/img/addon.jpg').'" class="img-thumbnail profile-widget-picture">';
            ?>
            <div class="card profile-widget">
                <div class="profile-widget-header">
                  <?php echo $thumb; ?>
                  <div class="profile-widget-items">
                    <div class="profile-widget-item">
                      <div class="profile-widget-item-value">                        
                        <span class='badge badge-light'>v<?php echo $value->version; ?></span>
                      </div>
                    </div>
                    <div class="profile-widget-item">
                      <div class="profile-widget-item-value">
                        <?php 
                        if($value->installed=="0") echo "<span class='badge badge-light'><i class='fas fa-ban'></i> ".__("Inactive")."</span>";
                        else echo "<span class='badge badge-light'><i class='fas fa-check-circle'></i> ".__("Active")."</span>"; 
                        ?> 
                      </div>
                    </div>
                  </div>
                </div>
                <div class="profile-widget-description" style="padding-bottom: 0;">
                  <div class="profile-widget-name text-center"><?php echo $value->addon_name;?></div>
                </div>
                <div class="card-footer text-center" style="padding-top: 10px;">

                  <?php if($value->installed == '0'): ?>
                    <a title="<?php echo __("activate"); ?>" class="btn btn-outline-primary activate_action" data-i='<?php echo $i; ?>' href="" data-href="<?php echo $module_controller.'/activate';?>"><i class="fa fa-check"></i> <?php echo __('activate');?></a>
                  <?php endif; ?>

                  <?php if($value->installed == '1'): ?>
                    <a title="<?php echo __("deactivate"); ?>" class="<?php if(config('app.is_demo')=='1') echo 'disabled'; ?> btn btn-outline-dark deactivate_action" href="" data-i='<?php echo $i; ?>' data-href="<?php echo $module_controller.'/deactivate';?>"><i class="fa fa-ban"></i> <?php echo __('deactivate');?></a>
                  <?php endif; ?>
                  <a title="<?php echo __("delete"); ?>" class="<?php if(config('app.is_demo')=='1') echo 'disabled'; ?> btn btn-outline-danger delete_action" href="" data-i='<?php echo $i; ?>' data-href="<?php echo $module_controller.'/delete';?>"><i class="fa fa-trash"></i> <?php echo __('delete');?></a>
                </div>
              </div>
            
          </div>            
          <?php 
        }
        echo "</div>";
      }
      else
      { ?>
        <div class="card">
            <div class="card-header">
              <h4><i class="fas fa-question"></i> <?php echo __("No add-on uploaded"); ?></h4>
            </div>
            <div class="card-body">
              <div class="empty-state" data-height="400" style="height: 400px;">
                <div class="empty-state-icon">
                  <i class="fas fa-question"></i>
                </div>
                <h2><?php echo __("System could not find any add-on."); ?></h2>
                <p class="lead">
                  <?php echo __("No add-on found. Your add-on will display here once uploaded."); ?>
                  
                </p>
                <a class="btn btn-primary" href="{{route('addons_upload')}}"><i class="fas fa-cloud-upload-alt"></i> <?php echo __('Upload Add-on');?></a>
              </div>
            </div>
          </div>

        <?php
      }
      ?>   
   </div>
</section>

<script>
  "use strict" 
  var is_demo = "<?php echo $is_demo; ?>";
  var global_lang_alert = '{{ __("Alert") }}';
  var Deactive_Add_on = '{{ __("Deactive Add-on?") }}';
  var Do_you_really_want_to_deactive_this_add_on = '{{ __("Do you really want to deactive this add-on? Your add-on data will still remain.") }}';
  var Delete_Add_on = '{{ __("Delete Add-on?") }}';
  var Do_you_really_want_to_delete_this_add_on = '{{ __("Do you really want to delete this add-on? This process can not be undone.") }}';

  var preloader_img = '{{ asset('assets/pre-loader/color/Preloader_9.gif') }}';
</script>


<script>
  
  $("document").ready(function(){

    $('[data-toggle="popover"]').popover(); 
    $('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;}); 

    $(".activate_action").click(function(e){ 
       e.preventDefault();
       var action = $(this).attr('data-href');
       var datai = $(this).attr('data-i');
       $("#href-action").val(action);      
       $(".put_add_on_title").html($("#get_add_on_title_"+datai).html());       
       $("#activate_action_modal_refesh").val('0');      
       $("#activate_action_modal").modal();       
    });

    $('#activate_action_modal').on('hidden.bs.modal', function () { 
      if($("#activate_action_modal_refesh").val()=="1")
      location.reload(); 
    })

    $("#activate_submit").click(function(){    
       if(is_demo=='1') 
       {
         alertify.alert(global_lang_alert,'Permission denied',function(){ });
         return false;
       }        
       var action = base_url+$("#href-action").val();
       var purchase_code=$("#purchase_code").val(); 

       $("#activate_submit").addClass('disabled');
       $("#activate_action_modal_msg").removeClass('alert').removeClass('alert-success').removeClass('alert-danger');
       var loading = '<img src="'+preloader_img+'" class="center-block" height="30" width="30">';
       $("#activate_action_modal_msg").html(loading);

       $.ajax({
             type:'POST' ,
             url: action,
             data:{purchase_code:purchase_code},
             dataType:'JSON',
             success:function(response)
             {
                $("#activate_action_modal_msg").html('');

                if(response.status == '1')
                {
                  swal(global_lang_success, response.message, 'success')
                  .then((value) => {
                    location.reload();
                  });
                }
                else
                {
                  swal(global_lang_error, response.message, 'error');
                }
             }
         });        
    });

    $(".deactivate_action").click(function(e){ 
       e.preventDefault();
       if(is_demo=='1') 
       {
         alertify.alert(global_lang_alert,'Permission denied',function(){ });
         return false;
       } 
       var action = base_url+$(this).attr('data-href');

       swal({
            title: Deactive_Add_on,
            text: Do_you_really_want_to_deactive_this_add_on,
            icon: 'error',
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) 
            {
                $.ajax({
                   type:'POST' ,
                   url: action,
                   dataType:'JSON',
                   success:function(response)
                   {
                      if(response.status == '1')
                      {
                        swal(global_lang_success, response.message, 'success')
                        .then((value) => {
                          location.reload();
                        });
                      }
                      else
                      {
                        swal(global_lang_error, response.message, 'error');
                      }
                   }
               }); 
            } 
          });
    });


    $(".delete_action").click(function(e){ 
       e.preventDefault();
       if(is_demo=='1') 
       {
         alertify.alert(global_lang_alert,'Permission denied',function(){ });
         return false;
       } 
       var action =  base_url+$(this).attr('data-href');

        swal({
            title: Delete_Add_on,
            text: Do_you_really_want_to_delete_this_add_on,
            icon: 'error',
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) 
            {
                $.ajax({
                   type:'POST' ,
                   url: action,
                   dataType:'JSON',
                   success:function(response)
                   {
                      if(response.status == '1')
                      {
                        swal(global_lang_success, response.message, 'success')
                        .then((value) => {
                          location.reload();
                        });
                      }
                      else
                      {
                        swal(global_lang_error, response.message, 'error');
                      }
                   }
               }); 
            } 
          });
    });

  

  });
</script>


<div class="modal fade" tabindex="-1" role="dialog" id="activate_action_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-check"></i> <?php echo __("activate");?>  <span class="put_add_on_title"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">             
        
              <div id="activate_action_modal_msg" class="text-center"></div>
              <div class="form-group">
                <label>
                  <?php echo __("add-on purchase code");?>
                </label>
                <input type="text" class="form-control" name="purchase_code" id="purchase_code">
                <input type="hidden" id="href-action" value="">
                <input type="hidden" id="activate_action_modal_refesh" value="0">
              </div>
           
            </div>

            <div class="modal-footer bg-whitesmoke">
              <button type="button" id="activate_submit" class="btn btn-primary btn-lg"><i class="fa fa-check-circle"></i> <?php echo __("Activate"); ?></button>
              <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fas fa-remove"></i> <?php echo __("Close"); ?></button>
            </div>
        </div>
    </div>
</div>




<style type="text/css">
  .profile-widget .profile-widget-picture {margin-top: -25px;}
</style>


@endsection