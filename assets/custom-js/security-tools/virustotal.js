"use strict";


  $(document).ready(function($) {
    setTimeout(function(){ 
      $('#post_date_range').daterangepicker({
        ranges: {
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      }, function (start, end) {
        $('#post_date_range_val').val(start.format('YYYY-M-D') + '|' + end.format('YYYY-M-D')).change();
      });
    }, 2000);


    var perscroll;
    var table = $("#mytable").DataTable({
        serverSide: true,
        processing:true,
        bFilter: false,
        order: [[ 2, "desc" ]],
        pageLength: 10,
        ajax: 
        {
          "url": virus_total_scan_data,
          "type": 'POST',
          beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          },
          data: function ( d )
          {
              d.searching = $('#searching').val();
              d.post_date_range = $('#post_date_range_val').val();
          }
        },

        dom: '<"top"f>rt<"bottom"lip><"clear">',
        columnDefs: [
          {
              targets: [2],
              visible: false
          },
            {
              targets: [0,2,3,5,6,7,8],
              className: 'text-center'
            },
            {
              targets:[0,1,2,3,4,5,6,7,8],
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



    $(document).on('change', '#post_date_range_val', function(event) {
      event.preventDefault(); 
      table.draw();
    });

    $(document).on('click', '#search_submit', function(event) {
      event.preventDefault(); 
      table.draw();
    });
    // End of datatable section

    $(document).on('click','.view_report',function(event){
      event.preventDefault();
      var id=$(this).attr('data-id');
      $('.view_report').addClass('btn-progress');
      $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');
      $.ajax({
        url:virus_total_report,
        type:'POST',
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        },
        data:{id:id},
        success:function(response){		
          
          $("#view_report_modal").modal();
          $("#report_content").html(response);	
          $("#custom_spinner").html("");
          $(".view_report").removeClass('btn-progress');	
        }
        
      });

    });

    $(document).on('keyup', '#searching1', function(event) {
      event.preventDefault(); 
      table1.draw();
    });

    $('#view_report_modal').on('hidden.bs.modal', function () {
      location.reload();
      $("#searching1").val("");
      table.draw();
    });

    // End of reply table

  $(document).on('click','#download_btn',function(event){
    event.preventDefault();

      var ids = [];
      $(".datatableCheckboxRow:checked").each(function ()
      {
          ids.push(parseInt($(this).val()));
      });
      
      if(ids.length==0) 
      {
        swal(global_lang_warning, You_have_to_select_list_from_data_table, 'warning');
        return false;
      }
      
      $.ajax({
        type:'POST',
        url:virus_total_scan_download,
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        },
        data:{ids:ids},
        success:function(response)
        {
          if (response !="") {

            $("#virus_total_download_selected").modal();
            $("#total_download_selected").html(response);
          }
          else {
            swal(global_lang_error, global_lang_something_wrong, 'error');
          }

        }
      });

  });

  $(document).on('click','#download_btn_all',function(event){
      event.preventDefault();
         var ids = 0;
      
      $.ajax({
        type:'POST',
        url:virus_total_scan_download,
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        },
        data:{ids:ids},
        success:function(response)
        {
          if (response !="") {

            $("#virus_total_download_selected").modal();
            $("#total_download_selected").html(response);
          }
          else {
            swal(global_lang_error, global_lang_something_wrong, 'error');
          }

        }
      });

  });

    $(document).on('click','#delete_btn',function(e){
      e.preventDefault();
      swal({
        title: global_lang_confirmation,
        text: Doyouwanttodeletealltheserecordsfromdatabase,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) 
        {
          var ids = [];
          $(".datatableCheckboxRow:checked").each(function ()
          {
              ids.push(parseInt($(this).val()));
          });
          
          if(ids.length==0) 
          {
            swal(global_lang_warning, You_have_to_select_list_from_data_table, 'warning');
            return false;
          }

          $.ajax({
            context: this,
            type:'POST' ,
            url:virus_total_scan_delete,
            beforeSend: function (xhr) {
              xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            data:{ids:ids},
            success:function(response){ 
              iziToast.success({title: '',message: Your_virus_total_item_has_been_deleted_successfully,position: 'bottomRight'});
              table.draw();
            }
          });
        } 
      });

    });

    $(document).on('click','#delete_btn_all',function(e){
      e.preventDefault();
      swal({
        title: global_lang_confirmation,
        text: Doyouwanttodeletealltheserecordsfromdatabase,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) 
        {
          var ids = 0;

          $.ajax({
            context: this,
            type:'POST' ,
            url:virus_total_scan_delete,
            beforeSend: function (xhr) {
              xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            data:{ids:ids},
            success:function(response){ 
              iziToast.success({title: '',message: Your_all_virus_total_item_has_been_deleted_successfully,position: 'bottomRight'});
              table.draw();
            }
          });
        } 
      });
    });






      
  });

