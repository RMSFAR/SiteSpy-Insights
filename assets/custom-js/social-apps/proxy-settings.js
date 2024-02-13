"use strict";


$(document).ready(function($){
    
    var perscroll;
    var table_proxy = $("#mytable_proxy").DataTable({
        serverSide: true,
        processing:true,
        bFilter: false,
        order: [[ 1, "desc" ]],
        pageLength: 10,
        ajax: 
        {
            "url": proxy_settings_data,
            "type": 'POST',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            data: function ( d )
            {
                d.proxy_keyword = $('#proxy_keyword').val();
            }
        },

        dom: '<"top"f>rt<"bottom"lip><"clear">',
        columnDefs: [
            {
                targets: [1],
                visible: false
            },
            {
                targets: 'no-sort', 
                orderable: false
            },
            {
                targets: 'centering', 
                className: 'text-center'
            },
        ],
        fnInitComplete:function(){  // when initialization is completed then apply scroll plugin
            if(areWeUsingScroll)
            {
                if (perscroll) perscroll.destroy();
                perscroll = new PerfectScrollbar('#mytable_proxy_wrapper .dataTables_scrollBody');
            }
        },
        scrollX: 'auto',
        fnDrawCallback: function( oSettings ) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again 
            if(areWeUsingScroll)
            { 
                if (perscroll) perscroll.destroy();
                perscroll = new PerfectScrollbar('#mytable_proxy_wrapper .dataTables_scrollBody');
            }
        }
    });

    $(document).on('keyup', '#proxy_keyword', function(event) {
    event.preventDefault(); 
    table_proxy.draw();
    });

    $(document).on('click', '.insert_new_proxy', function(event) {
        event.preventDefault();
        $("#new_proxy_modal").modal();
        
    });

    $('#new_proxy_modal').on('hidden.bs.modal', function () {
        event.preventDefault();
        $("#new_proxy_form").trigger('reset');
        table_proxy.draw();
    });

    $(document).on('click','.delete_proxy',function(e){
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
                    type:'POST' ,
                    url:delete_proxy,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
                    },
                    data:{table_id:table_id},
                    success:function(response){ 

                        if(response == '1')
                        {
                            iziToast.success({title: '',message: Proxy_Settings_has_been_Deleted_Successfully ,position: 'bottomRight'});
                        } else
                        {
                            iziToast.error({title: '',message: Something_went_wrong_please_try_once_again ,position: 'bottomRight'});
                        }
                        table_proxy.draw();
                    }
                });
            } 
        });
    });

    $(document).on('click', '#proxy_save', function(event) {
        event.preventDefault();
        
        var proxy = $("#proxy").val();
        var proxy_port = $("#proxy_port").val();

        if(proxy =='') {
            swal(global_lang_warning, Proxy_is_required, 'warning');
            return;
        }

        if(proxy_port =='') {
            swal(global_lang_warning, Proxy_Port_is_required, 'warning');
            return;
        }

        $(this).addClass('btn-progress');
        var that = $(this);

    
        var queryString = new FormData($("#new_proxy_form")[0]);

        $.ajax({
            url:insert_proxy,
            type:'POST',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            data: queryString,
            dataType: 'JSON',
            cache: false,
            contentType: false,
            processData: false,
            success:function(response)
            {
                $(that).removeClass('btn-progress');

                if(response.status=='1')
                {
                var span = document.createElement("span");
                span.innerHTML = response.message;
                swal({ title:Proxy_Added, content:span,icon:'success'}).then((value) => {window.location.href=report_link;});
                }
                else 
                    swal(global_lang_error, response.message, 'error').then((value) => {window.location.href=report_link;});
            }
        });
    });

    $(document).on('click', '.edit_proxy', function(event) {
        event.preventDefault();
        
        $("#update_proxy_modal").modal();

        var table_id = $(this).attr("table_id");

        var loading = '<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center" style="font-size:60px"></i></div>';
        $(".proxyModalBody").html(loading);
        $(".update-proxy-modal-footer").addClass('hidden');

        $.ajax({
            url:ajax_update_proxy_info,
            type:'POST',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            data: {table_id: table_id},
            success:function(response)
            {
                $(".update-proxy-modal-footer").removeClass('hidden');
                $(".proxyModalBody").html(response);
            }
        });
    });

    $(document).on('click', '#proxy_update', function(event) {
        event.preventDefault();
        
        var proxy = $("#updated_proxy").val();
        var proxy_port = $("#updated_proxy_port").val();

        if(proxy =='') {
            swal(global_lang_warning , Proxy_is_required, 'warning');
            return;
        }

        if(proxy_port =='') {
            swal(global_lang_warning, Proxy_Port_is_required, 'warning');
            return;
        }

        $(this).addClass('btn-progress');
        var that = $(this);

        var queryString = new FormData($("#update_proxy_form")[0]);

        $.ajax({
            url:update_proxy_settings,
            type:'POST',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            data: queryString,
            dataType: 'JSON',
            cache: false,
            contentType: false,
            processData: false,
            success:function(response)
            {
                $(that).removeClass('btn-progress');

                if(response.status=='1')
                {
                var span = document.createElement("span");
                span.innerHTML = response.message;
                swal({ title: Proxy_Updated , content:span,icon:'success'}).then((value) => {window.location.href=report_link;});
                }
                else 
                    swal( global_lang_error, response.message, 'error').then((value) => {window.location.href=report_link;});
            }
        });
    });

    $('#update_proxy_modal').on('hidden.bs.modal', function () {
        event.preventDefault();
        table_proxy.draw();
    });

});
