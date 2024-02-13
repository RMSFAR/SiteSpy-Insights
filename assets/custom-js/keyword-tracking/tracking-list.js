"use strict";

  $(document).ready(function() {
  
    setTimeout(function(){ 
        $('#post_date_range').daterangepicker({
          ranges: {
            global_lang_last_30_days : [moment().subtract(29, 'days'), moment()],
            global_lang_this_month  : [moment().startOf('month'), moment().endOf('month')],
            global_lang_last_month  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate  : moment()
        }, function (start, end) {
          $('#keyword_post_date_range_val').val(start.format('YYYY-M-D') + '|' + end.format('YYYY-M-D')).change();
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
          "url": keyword_list_data,
          "type": 'POST',
          beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          },
            data: function ( d )
            {
                d.searching = $('#keyword_searching').val();
                d.post_date_range = $('#keyword_post_date_range_val').val();
            }
        },
        
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        columnDefs: [
               {
                   targets: [2],
                   visible: false
               },
            {
                targets: [0,1,2,4,5,6,7,8],
                className: 'text-center'
            },
            {
                targets: [0,1,2,3,4,5,6,7,8],
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
    // End of datatable section
  
  
    $(document).on('keyup', '#keyword_searching', function(event) {
      event.preventDefault(); 
      table.draw();
    });
  
    $(document).on('change', '#keyword_post_date_range_val', function(event) {
        event.preventDefault(); 
        table.draw();
    });
  
    $(document).on('click', '#add_new_keyword', function(event) {
      event.preventDefault();
      $("#new_keyword_modal").modal();
    });
  
  
    $(document).on('click', '#new_search_button', function(event) {
      event.preventDefault();
  
      var keyword = $("#keyword").val();
      var website = $("#website").val();
      var country = $("#country").val();
      var language = $("#language").val();
      
      if(keyword == "" || website == "" || country == "" || language == "") {
        swal(global_lang_warning, global_all_files_required, 'warning');
        return false;
      }
  
      $(this).addClass('btn-progress');
      var that = $(this);
  
      
  
      var alldatas = new FormData($("#keyword_tracking_form")[0]);
  
      $.ajax({
        url: keyword_tracking_settings_action,
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        },
        data: alldatas,
        cache: false,
        contentType: false,
        processData: false,
        success:function(response)
        {
          $(that).removeClass('btn-progress');
  
          if(response.status == "1")
          {
            var success_message = response.msg+" <a href='"+redirect_url+"'><?php echo __('See Report'); ?></a>";
  
            var span = document.createElement("span");
            span.innerHTML = success_message;
            swal({ title:Keyword_Created, content:span,icon:'success'}).then((value) => {window.location.href=redirect_url;});
  
          } else if(response.status == "2") {
  
            
            var success_message = response.msg+" <a href='"+report_url+"'><?php echo __('see usage log.'); ?></a>";
  
            var span = document.createElement("span");
            span.innerHTML = success_message;
            swal({ title:Usage_Warning, content:span,icon:'success'}).then((value) => {window.location.href=report_url;});
  
          } else if(response.status == "3") {
  
            
            var success_message = response.msg+" <a href='"+report_url+"'><?php echo __('see usage log.'); ?></a>";
  
            var span = document.createElement("span");
            span.innerHTML = success_message;
            swal({ title:Usage_Warning, content:span,icon:'success'}).then((value) => {window.location.href=report_url;});
  
          } else {
  
            var success_message = response.msg+" <a href='"+redirect_url+"'><?php echo __('See Report'); ?></a>";
  
            var span = document.createElement("span");
            span.innerHTML = success_message;
            swal({ title:Keyword_Rejected, content:span,icon:'success'}).then((value) => {window.location.href=redirect_url;});
          }
  
        }
      })
  
  
    });
  
  
  
    $(document).on('click','.delete_keyword',function(e){
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
                var table_id = $(this).attr('table_id');
  
                $.ajax({
                    context: this,
                    type:'POST',
                    url:delete_keyword_action,
                    beforeSend: function (xhr) {
                      xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
                    },
                    data:{table_id:table_id},
                    success:function(response){ 
                      if(response == '1')
                      {
                        iziToast.success({title: '',message: Domain_has_been_Deleted_Successfully ,position: 'bottomRight'});
                      } else
                      {
                        iziToast.error({title: '',message: Something_went_wrong_please_try_once_again ,position: 'bottomRight'});
                      }
                      table.draw();
                    }
                });
            } 
        });
    });
  
    $(document).on('click', '.delet_all_keywords', function(event) {
      event.preventDefault();
  
      var keyword_ids = [];
      $(".datatableCheckboxRow:checked").each(function ()
      {
        keyword_ids.push(parseInt($(this).val()));
      });
      
      if(keyword_ids.length==0) {
  
        swal({title:global_lang_warning,text: Please_select_keyword_to_delete,icon: 'warning',buttons: true,dangerMode: true,});
        return false;
  
      }
      else {
  
        swal({title: global_lang_confirmation,text: Doyouwanttodeletealltheserecordsfromdatabase,icon: 'warning',buttons: true,dangerMode: true,})
        .then((willDelete) => {
  
            if (willDelete) {
  
                $(this).addClass('btn-progress');
                $.ajax({
                    context: this,
                    type:'POST',
                    url: delete_selected_keyword_action,
                    beforeSend: function (xhr) {
                      xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
                    },
                    data:{info:keyword_ids},
                    success:function(response){
                        $(this).removeClass('btn-progress');
  
                        if(response == '1') {
  
                            iziToast.success({title: '',message: Selected_Keyword_has_been_deleted_Successfully,position: 'bottomRight'});
  
                        } else {
  
                            iziToast.error({title: '',message: Something_went_wrong_please_try_once_again,position: 'bottomRight'});
  
                        }
  
                        table.draw();
                    }
                });
  
            } 
        });
      }
  
    });
    // End of reply table
  
  
  
    $("#new_keyword_modal").on('hidden.bs.modal', function(event) {
      event.preventDefault();
      table.draw();
    });
  
          
  });
  
