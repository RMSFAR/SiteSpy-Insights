"use strict";


$(document).ready(function($){

    var perscroll;
    var table = $("#mytable").DataTable({
        serverSide: true,
        processing:true,
        bFilter: false,
        order: [[ 1, "desc" ]],
        pageLength: 10,
        ajax: 
        {
          "url": domain_list_visitor_data,
          "type": 'POST',
          beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          },
          data: function ( d )
          {
              d.domain_name = $('#domain_name').val();
          }
        },
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        columnDefs: [
            {
              targets: [0,1,4],
              visible: false
            },
            {
              targets: [2,3,4,5],
              className: 'text-center'
            },
            {
              targets:[3,4,5],
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

    $(document).on('click', '#search_submit', function(event) {
      event.preventDefault(); 
      table.draw();
    });

    $(document).on('click', '.add_domain_modal', function(event) {
      event.preventDefault(); 
      $("#analytic_code").html('');
      $("#domain_name_add").val('');
      $("#add_domain_modal").modal();
    });

    $('#add_domain_modal').on('hidden.bs.modal', function () { 
      table.draw();
    });


    $(document).on('click', '#add_domain', function(event) {
      event.preventDefault(); 
      var domain_name = $("#domain_name_add").val();
      if(domain_name.trim() == '')
      {
        swal(global_lang_warning, You_have_to_provide_a_domain_name, 'warning');
        return;
      }


      var waiting_content = '<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center" style="font-size: 40px;"></i></div>';
      $("#analytic_code").html(waiting_content);
      $(this).addClass('btn-progress');
      $.ajax({
        context: this,
        type:'POST' ,
        url: add_domain_action,
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        },
        data: {domain_name},
        // dataType : 'JSON',
        success:function(response){
          $(this).removeClass('btn-progress');
          $("#analytic_code").html(response);
        }
      });
    });

    $(document).on('click','.delete_template',function(e){
      e.preventDefault();

      swal({
        title: global_lang_delete,
        text: Are_you_sure_about_deleting_this_domain,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) 
        {
          $(this).addClass('btn-danger');
          $(this).addClass('btn-progress');
          var table_id = $(this).attr('table_id');

          $.ajax({
            context: this,
            type:'POST' ,
            url:ajax_delete_domain,
            beforeSend: function (xhr) {
              xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            // dataType: 'json',
            data:{table_id:table_id},
            success:function(response){ 
              $(this).removeClass('btn-danger');
              $(this).removeClass('btn-progress');
              if(response=='success')
              {
                iziToast.success({title: '',message: Domain_and_corresponding_data_has_been_deleted_successfully,position: 'bottomRight'});
                table.draw();
              }
              else if(response=='no_match')
              {
                iziToast.error({title: '',message: No_Domain_is_found_for_this_user_with_this_ID,position: 'bottomRight'});
              }
              else
              {
                iziToast.error({title: '',message: Something_went_wrong_please_try_once_again,position: 'bottomRight'});
              }
            }
          });
        } 
      });


    });

    $(document).on('click','.delete_30_days_data',function(e){
      e.preventDefault();

      swal({
        title: global_lang_delete,
        text: Are_you_sure_about_deleting_data_except_last_30,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) 
        {
          
          $(this).addClass('btn-danger');
          $(this).addClass('btn-progress');
          var table_id = $(this).attr('table_id');

          $.ajax({
            context: this,
            type:'POST' ,
            url:ajax_delete_last_30_days_data,
            beforeSend: function (xhr) {
              xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            // dataType: 'json',
            data:{table_id:table_id},
            success:function(response){ 
              $(this).removeClass('btn-danger');
              $(this).removeClass('btn-progress');
              if(response=='success')
              {
                iziToast.success({title: '',message: Data_except_last_30_days_has_been_deleted_successfully,position: 'bottomRight'});
                table.draw();
              }
              else if(response=='no_match')
              {
                iziToast.error({title: '',message: No_Domain_is_found_for_this_user_with_this_ID,position: 'bottomRight'});
              }
              else
              {
                iziToast.error({title: '',message: Something_went_wrong_please_try_once_again,position: 'bottomRight'});
              }
            }
          });
        } 
      });


    });

    $(document).on('click', '.get_js_code', function(event) {
        event.preventDefault(); 
        var waiting_content = '<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center" style="font-size: 40px;"></i></div>';
        var table_id = $(this).attr('table_id');
        $('#get_js_code_modal_body').html(waiting_content);
        $('#get_js_code').modal();
        
        $.ajax({
          context: this,
          type:'POST' ,
          url:get_js_code,
          beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          },
          // dataType: 'json',
          data:{table_id:table_id},
          success:function(response){ 
            $('#get_js_code_modal_body').html(response);
          }
        });
      });
    
    $(document).on('click','.show_in_dashboard',function(event){
      event.preventDefault();

      var dashboard = $(this).attr('dashboard');
      var warning_text;
      if (dashboard=='1') {
         warning_text = Do_you_want_to_remove_this_domain_showing_from_your_dashboard;
      }
      else{
        warning_text = Do_you_want_to_remove_this_domain_remove_from_your_dashboard;
      }


      swal({
        title: global_lang_confirmation,
        text: warning_text,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) 
        {
          var table_id = $(this).attr('table_id');
          var dashboard = $(this).attr('dashboard');

          $.ajax({
            context: this,
            type:'POST' ,
            url:display_in_dashboard,
            beforeSend: function (xhr) {
              xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            dataType: 'json',
            data:{table_id:table_id,dashboard:dashboard},
            success:function(response){ 
              if (response.status == 'exist') {

                swal(global_lang_warning, response.message, 'warning');
                table.draw();
              }
              else if (response.status == 'not_exist')
              {
               
                swal(global_lang_success, response.message, 'success');
                table.draw();
              }
              else if (response.status == 'remove')
              {
                swal(global_lang_success, response.message, 'success');
                table.draw();
              }
            }
          });
        } 
      });


    });


  });