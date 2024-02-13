@extends('design.app')
@section('title',$page_title)
@section('content')

<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-ticket-alt"></i> <?php echo $page_title; ?></h1>  
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("Support Desk"); ?></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  {{-- <?php $this->load->view('admin/theme/message'); ?> --}}
  @include('shared.message')
  <?php 
  // if(session()->flashdata('mark_seen_success')!='')
  // echo "<div class='alert alert-primary text-center'><i class='fas fa-check-circle'></i> ".session()->flashdata('mark_seen_success')."</div>"; 
  ?>

  <div class="section-body">

    <div class="row">      
      <div class="col-12 col-md-7">
        <div class="input-group mb-3" id="searchbox">
          <div class="input-group-prepend">
              <select class="select2 form-control" id="ticket_status">
                <option value="1"><?php echo __("Open"); ?></option>
                <option value="3"><?php echo __("Resolved"); ?></option>
                <option value="2"><?php echo __("Closed"); ?></option>
                <?php if(Auth::user()->user_type=="Admin") { ?>
                <option value="hidden"><?php echo __("Hidden"); ?></option>
                <?php } ?>
                <option value=""><?php echo __("Everything"); ?></option>
              </select>
            </div>
          <input type="text" class="form-control" id="search" autofocus placeholder="<?php echo __('Search...'); ?>" aria-label="" aria-describedby="basic-addon2">
          <div class="input-group-append">
            <button class="btn btn-primary" id="search_submit" type="button"><i class="fas fa-search"></i> <?php echo __('Search'); ?></button>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-5">

        <?php if(Auth::user()->user_type=="Admin") 
        { ?>
          <a class="btn btn-outline-primary btn-lg float-right" href="{{route('support_category_manager')}}"><i class="fas fa-layer-group"></i> <?php echo __("Manage Category"); ?></a>
        <?php 
        } 
        else 
        { ?>
           <a class="btn btn-outline-primary btn-lg float-right"  href="<?php echo url('/simplesupport/open_ticket');?>">
              <i class="fas fa-plus-circle"></i> <?php echo __("New Ticket"); ?>
           </a> 
        <?php 
        } ?>


        
      </div>
    </div>

    <div class="activities">
        <div id="load_data" style="width: 100%;"></div>      
    </div> 


    <div class="text-center" id="waiting" style="width: 100%;margin: 30px 0;">
      <i class="fas fa-spinner fa-spin blue" style="font-size:60px;"></i>
    </div>  

    <div class="card" id="nodata" style="display: none">
      <div class="card-body">
        <div class="empty-state">
          <img class="img-fluid" style="height: 300px" src="<?php echo asset('assets/img/drawkit/drawkit-nature-man-colour.svg'); ?>" alt="image">
          <h2 class="mt-0"><?php echo __("We could not find any data.") ?></h2>
        </div>
      </div>
    </div>
 

    <button class="btn btn-outline-primary float-right" style="display: none;" id="load_more" data-limit="10" data-start="0"><i class="fas fa-book-reader"></i> <?php echo __("Load More"); ?></button>
      
  </div>
</section>


<script>     
	"use strict"  

	var No_data_found = "{{ __('No data found') }}";
	var Do_you_really_want_to_delete_it = "{{ __('Do you really want to delete it?') }}";


</script>


<script>       
  var counter=0;
  $(document).ready(function() {      

      setTimeout(function() {          
        var start = $("#load_more").attr("data-start");   
        load_data(start,false,false);
      }, 1000);


      $(document.body).on('click', '#load_more', function(e) {
        var start = $("#load_more").attr("data-start");   
        load_data(start,false,true);
      });

      $(document.body).on('change', '#ticket_status', function(e) {
        var start = '0';
        load_data(start,true,false);
      });


      $(document.body).on('click', '#search_submit', function(e) {
        var start = '0';
        load_data(start,true,false);
      });

      function load_data(start,reset,popmessage) 
      {
        var limit = $("#load_more").attr("data-limit");        
        var search = $("#search").val();
        var ticket_status = $("#ticket_status").val();
        $("#waiting").show();
        if(reset) 
        {
          $("#search_submit").addClass("btn-progress");
          counter = 0;
        }
        $.ajax({
          url: base_url+'/simplesupport/ticket_data',
          type: 'POST',
          dataType : 'JSON',
          data: {start:start,limit:limit,search:search,ticket_status:ticket_status},
            success:function(response)
            {
              $("#waiting").hide();
              $("#nodata").hide();
              $("#search_submit").removeClass("btn-progress");
              
              counter += response.found; 
              $("#load_more").attr("data-start",counter); 
              if(!reset)  $("#load_data").append(response.html);
              else $("#load_data").html(response.html);

              if(response.found!='0') $("#load_more").show();                
              else 
              {
                $("#load_more").hide();
                if(popmessage) 
                {
                  swal(No_data_found, "", "warning");
                  $("#nodata").hide();
                }
                else $("#nodata").show();
              }
            }
        });
      }

      $(document.body).on('click', '.ticket_action', function(e) {
        e.preventDefault();
        var id = $(this).attr("table_id");
        var action = $(this).attr("data-type");
        
        $(this).addClass('btn-progress');
        $.ajax({
          context: this,
          url: base_url+"/simplesupport/ticket_action",
          type: 'POST',
          dataType: 'JSON',
          data: {id:id,action:action},
            success:function(response)
            {
              $(this).removeClass('btn-progress');
              if(response.status == "1")  
              {
                iziToast.success({title: global_lang_success,message: response.message,position: 'bottomRight'});
                $(this).parent().parent().parent().parent().parent().parent().parent().hide();
              }
              else iziToast.error({title: global_lang_error,message: response.message,position: 'bottomRight'});
            }
        });
      });

      
      $(document.body).on('click', '.delete_ticket', function(e) {
          e.preventDefault();
          var id = $(this).attr("table_id");
          swal({
            title: global_lang_confirmation,
            text: Do_you_really_want_to_delete_it,
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => 
          {
            if (willDelete) 
            {
                $(this).addClass('btn-progress');
                $.ajax({
                  context: this,
                  url: base_url+"/simplesupport/delete_ticket",
                  type: 'POST',
                  dataType: 'JSON',
                  data: {id:id},
                  success:function(response)
                  {
                    $(this).removeClass('btn-progress');
                    if(response.status == "1")  
                    {
                        iziToast.success({title: global_lang_success,message: response.message,position: 'bottomRight'});
                        $(this).parent().parent().parent().parent().parent().parent().parent().hide();
                    }
                    else iziToast.error({title: global_lang_error,message: response.message,position: 'bottomRight'});
                    }                      
                });
              } 
          });
      
      });

  });
</script>


@endsection