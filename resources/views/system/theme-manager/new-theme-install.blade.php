@extends('design.app')
@section('title',$page_title)
@section('content')

<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-cloud-upload-alt"></i> <?php echo $page_title; ?></h1>    
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("System"); ?></div>
      <div class="breadcrumb-item active"><a href="{{route('theme_manager')}}"><?php echo __("Theme Manager"); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  @include('shared.message')

  <?php if(session()->flash('theme_upload_success')!="") echo "<div class='alert alert-success text-center'><i class='fa fa-check'></i> ".session()->flash('theme_upload_success')."</div>";?>

   <div class="section-body">
      <div class="row">

        <div class="col-12 col-md-6">
          <div class="card">
            <div class="card-header">
              <h4><i class="fas fa-cloud-upload-alt"></i> <?php echo __("Upload New Theme"); ?></h4>
            </div>
            <div class="card-body">
              <div class="form-group">    
                <div id="addon_url_upload"><?php echo __('Upload');?></div>
              </div>
            </div>
            <div class="card-footer bg-whitesmoke text-justify">
              <h6><?php echo __('After you upload theme file you will be taken to Theme Manager page, you need to active the theme there.');?> <?php echo __('If you are having trouble uploading file using our uploader then you can simply upload theme zip file in application/views/site folder, unzip it and then activate it from Theme Manager.');?></h6>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="card" id="server-status">
            <div class="card-header">
              <h4><i class="fas fa-server"></i> <?php echo __("Server Status"); ?></h4>
            </div>
            <div class="card-body">
              <?php
                $list1=$list2="";
                if(function_exists('ini_get'))
                {
                  $make_dir = (!function_exists('mkdir')) ? __("Not Enabled"):__("Enabled");
                  $zip_archive = (!class_exists('ZipArchive')) ? __("Not Enabled"):__("Enabled");
                  $list1 .= "<li class='list-group-item'><b>mkdir</b> : ".$make_dir."</li>"; 
                    $list1 .= "<li class='list-group-item'><b>upload_max_filesize</b> : ".ini_get('upload_max_filesize')."</li>";   
                  $list1 .= "<li class='list-group-item'><b>max_input_time</b> : ".ini_get('max_input_time')."</li>";
                  $list2 .= "<li class='list-group-item'><b>ZipArchive</b> : ".$zip_archive."</li>";  
                    $list2 .= "<li class='list-group-item'><b>post_max_size</b> : ".ini_get('post_max_size')."</li>"; 
                  $list2 .= "<li class='list-group-item'><b>max_execution_time</b> : ".ini_get('max_execution_time')."</li>";
                 }
                ?>
                <div class="row">
                  <div class="col-12 col-md-6">                     
                  <ul class="list-group">
                    <?php echo $list1; ?>
                  </ul>
                  </div>
                  <div class="col-12 col-md-6">
                    <ul class="list-group">
                      <?php echo $list2; ?>
                  </ul>
                  </div>
                </div>
            </div>
          </div>  
        </div>

      </div>
   </div>
</section>


<script>

  "use strict" 
  
  $("document").ready(function(){

    $('[data-toggle="popover"]').popover(); 
    $('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;}); 

    $("#addon_url_upload").uploadFile({
        url:base_url+"/themes/upload_addon_zip",
        fileName:"myfile",
        maxFileSize:100*1024*1024,
        showPreview:false,
        returnType: "json",
        dragDrop: true,
        showDelete: true,
        multiple:false,
        maxFileCount:1, 
        showDelete:false,
        acceptFiles:".zip",
        deleteCallback: function (data, pd) {
            var delete_url=base_url+"themes/delete_uploaded_zip";
              $.post(delete_url, {op: "delete",name: data},
                  function (resp,textStatus, jqXHR) {                         
                  });
           
         },
         onSuccess:function(files,data,xhr,pd)
           {
               var data_modified = data;
               window.location.assign(base_url+'themes/lists'); 
           }
    });
  });
</script>

@endsection