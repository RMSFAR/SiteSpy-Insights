@extends('design.app')
@section('title',$page_title)
@section('content')


<section class="section section_custom">
  <div class="section-header">
    <h1>
        <i class="fas fa-ticket-alt"></i> <?php echo $page_title; ?>
    </h1>  
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="<?php echo url('/simplesupport/tickets');?>"><?php echo __("Support Desk"); ?></a></div>     
      <div class="breadcrumb-item"><?php echo __("Open Ticket"); ?></div>
    </div>
  </div>

  {{-- <?php $this->load->view('admin/theme/message'); ?> --}}
  @include('shared.message')

  <div class="section-body">
    <div class="row">
      <div class="col-12">
          <form action="<?php echo url('/simplesupport/open_ticket_action'); ?>" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">             
              <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label><?php echo __("Ticket Title"); ?> *</label>
                            <input class="form-control" name="ticket_title" id="ticket_title" type="input" required>
                        </div>  
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                           <label><?php echo __('Ticket Type') ?> *</label>
                           <select  class="form-control select2" id="support_category" name="support_category" required>
                           <?php
                               echo "<option value=''>{__('Please select a category')}</option>";
                               foreach($support_category as $key=>$val)
                               {
                                   $id=$val->id;
                                   $group_name=$val->category_name;
                                   echo "<option value='{$id}'>{$group_name}</option>";
                               }
                            ?>
                           </select>
                        </div>
                    </div>
                </div>                 

                <div class="form-group" id="image_rich_content_block">
                    <label><?php echo __('Ticket Desctiption'); ?> *</label>
                    <!-- <div id="toolbar-container"></div> -->
                    <div id="ckeditor">
                        <textarea required class="form-control" name="ticket_text" id="ticket_text"></textarea>
                    </div>
                </div>
              </div>

              <div class="card-footer bg-whitesmoke">
                  <?php $red_link="simplesupport/tickets";?>
                  <button type="submit" class="btn btn-primary btn-lg open"><i class="fa fa-send"></i> <?php echo __('Open Ticket'); ?> </button> 
                  <a onclick="goBack('<?php echo $red_link ?>',1)" class="btn btn-light btn-lg float-right cancel from-show"><i class="fas fa-times"></i> <?php echo __("Cancel"); ?> </a>                        
              </div>
            </div>
          </form>
      </div>
    </div>
  </div>
</section>

<style type="text/css">
  .modal-backdrop, .modal-backdrop.in{
    display: none;
  }
</style>

<script>    
    $(document).ready(function() {
        $('#ticket_text').summernote(); 
        $(document.body).submit(function () {
                  $(".open").attr("disabled", true);
                  return true;
              });  
    });
</script>


@endsection