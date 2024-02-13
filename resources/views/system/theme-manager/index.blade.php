@extends('design.app')
@section('title',$page_title)
@section('content')

<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-plug"></i> <?php echo $page_title; ?></h1>    
    <div class="section-header-button">
      <a class="btn btn-primary" href="{{route('themes_upload')}}"><i class="fas fa-cloud-upload-alt"></i> <?php echo __('Install Theme');?></a>
    </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("System"); ?></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  {{-- <?php $this->load->view('admin/theme/message'); ?> --}}
	@include('shared.message')

  <?php if(session()->flash('theme_upload_success')!="") echo "<div class='alert alert-success text-center'><i class='fa fa-check'></i> ".session()->flash('theme_upload_success')."</div>";?>

   <div class="section-body">
      <?php 
      if(!empty($theme_list))
      {       
        $i=0;
        echo "<div class='row'>";
        foreach($theme_list as $value)
        {
          $i++;
          ?>
          <div class="col-12 col-sm-6 col-md-4">
            <?php 
              $asset_path=$value->thumb; 
              $base64file = xit_theme_thumbs($asset_path);
              if($base64file=="") $thumb = asset('assets/img/example-image.jpg');
              else $thumb = $base64file;

            ?>

            <div class="card">
              <div class="card-header">
                <h4>
                  <?php 
                    if($value->folder_name == config('my_config.current_theme')) 
                      echo "<i class='fas fa-check-circle blue' title='".__('active')."'></i> "; 
                    echo $value->theme_name;
                  ?>
                </h4>
              </div>
              <div class="card-body">
                <div class="chocolat-parent">
                  <a href="<?php echo $thumb; ?>" class="chocolat-image" title="<?php echo $value->theme_name;?>">
                    <div data-crop-image="275">
                      <img alt="image" src="<?php echo $thumb; ?>" class="img-fluid">
                    </div>
                  </a>
                </div>
                <div class="mb-2 text-muted"><?php echo $value->description; ?></div>
              </div>
              <div class="card-footer text-center">
                <?php if($value->folder_name != config('my_config.current_theme')): ?>
                  <a title="<?php echo __("activate"); ?>" class="btn btn-outline-primary activate_action" data-i='<?php echo $i; ?>' href="" data-unique-name="<?php echo $value->folder_name;?>"><i class="fa fa-check"></i> <?php echo __('activate');?></a>

                <?php else: ?>
                  <a title="<?php echo __("deactivate"); ?>" class="<?php if($is_demo=='1' || count($theme_list)<=1) echo 'disabled'; ?> btn btn-outline-dark deactivate_action" href="" data-i='<?php echo $i; ?>' data-unique-name="<?php echo $value->folder_name;?>"><i class="fa fa-ban"></i> <?php echo __('deactivate');?></a>
                <?php endif; ?>
                <?php if($value->folder_name != 'default'): ?>
                <a title="<?php echo __("delete"); ?>" class="<?php if($is_demo=='1') echo 'disabled'; ?> btn btn-outline-danger delete_action" href="" data-i='<?php echo $i; ?>' data-unique-name="<?php echo $value['folder_name'];?>"><i class="fa fa-trash"></i> <?php echo __('delete');?></a>
                <?php endif; ?>
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
              <h4><i class="fas fa-question"></i> <?php echo __("No Theme uploaded"); ?></h4>
            </div>
            <div class="card-body">
              <div class="empty-state" data-height="400" style="height: 400px;">
                <div class="empty-state-icon">
                  <i class="fas fa-question"></i>
                </div>
                <h2><?php echo __("System could not find any Theme."); ?></h2>
                <p class="lead">
                  <?php echo __("No Theme found. Your Theme will display here once uploaded."); ?>
                  
                </p>
                <a class="btn btn-primary" href="{{route('themes_upload')}}"><i class="fas fa-cloud-upload-alt"></i> <?php echo __('Upload Theme');?></a>
              </div>
            </div>
          </div>

        <?php
      }
      ?>   
   </div>
</section>

<script>
  "use strict";
  
  var is_demo = "<?php echo $is_demo; ?>";
  var Theme_Activation = '{{ __("Theme Activation") }}';
  var Do_you_really_want_to_activate_this_Theme = '{{ __("Do you really want to activate this Theme?") }}';
  var Theme_Deactivation = '{{ __("Theme Deactivation") }}';
  var Do_you_really_want_to_deactivate_this_Theme = '{{ __("Do you really want to deactivate this Theme? Your theme data will still remain") }}';
  var theme_Delete = '{{ __("Delete!") }}';
  var Do_you_really_want_to_delete_this_Theme = '{{ __("Do you really want to delete this Theme? This process can not be undone.") }}';

</script>

<script>

  "use strict" 
   
  $("document").ready(function(){

    $('[data-toggle="popover"]').popover(); 
    $('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;}); 

    $(".activate_action").click(function(e){ 
       e.preventDefault();
       var folder_name = $(this).attr('data-unique-name');
       swal({
           title: Theme_Activation,
           text: Do_you_really_want_to_activate_this_Theme,
           icon: 'info',
           buttons: true,
           dangerMode: true,
         })
         .then((willDelete) => {
           if (willDelete) 
           {
               $.ajax({
                  type:'POST' ,
                  url: base_url+"/themes/active_deactive_theme",
                  data:{folder_name:folder_name,active_or_deactive:'active'},
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

    $(".deactivate_action").click(function(e){ 
       e.preventDefault();
       var folder_name = $(this).attr('data-unique-name');
       swal({
           title: Theme_Deactivation,
           text: Do_you_really_want_to_deactivate_this_Theme,
           icon: 'warning',
           buttons: true,
           dangerMode: true,
         })
         .then((willDelete) => {
           if (willDelete) 
           {
               $.ajax({
                  type:'POST' ,
                  url: base_url+"/themes/active_deactive_theme",
                  data:{folder_name:folder_name,active_or_deactive:'deactive'},
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
       var folder_name = $(this).attr('data-unique-name');
       swal({
           title: theme_Delete,
           text: Do_you_really_want_to_delete_this_Theme,
           icon: 'warning',
           buttons: true,
           dangerMode: true,
         })
         .then((willDelete) => {
           if (willDelete) 
           {
               $.ajax({
                  type:'POST' ,
                  url: base_url+"/themes/delete_theme",
                  data:{folder_name:folder_name},
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

@endsection