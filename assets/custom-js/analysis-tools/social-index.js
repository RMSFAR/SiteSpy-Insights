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
          "url":social_list_data,
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
              targets: [0,1,2,3,4,5,6,7,8,9,10,11,12,13],
              className: 'text-center'
            },
            {
              targets:[0,1,2,3,4,5,6,7,8,9,10,11,12,13],
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


    $(document).on('keyup', '#searching', function(event) {
      event.preventDefault(); 
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

      $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');
      $.ajax({
        type:'POST',
        url:social_download,
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
      },
        data:{ids:ids},
        success:function(response)
        {
          if (response !="") {

            $("#social_network_download_selected").modal();
            $("#total_download_selected").html(response);
            $("#custom_spinner").html("");
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
        url:social_download,
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
      },
        data:{ids:ids},
        success:function(response)
        {
          if (response !="") {

            $("#social_network_download_selected").modal();
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
        text: Doyouwanttodeletethisrecordfromdatabase,
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
            url:social_delete,
            beforeSend: function (xhr) {
              xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          },
            data:{ids:ids},
            success:function(response){ 
              iziToast.success({title: '',message: Your_social_network_analysis_data_has_been_deleted_successfully,position: 'bottomRight'});
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
            url:social_delete,
            beforeSend: function (xhr) {
              xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          },
            data:{ids:ids},
            success:function(response){ 
              iziToast.success({title: '',message: Your_all_social_network_analysis_data_has_been_deleted_successfully , position: 'bottomRight'});
              table.draw();
            }
          });
        } 
      });
    });






      
});