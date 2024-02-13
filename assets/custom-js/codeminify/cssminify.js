"use strict";

$("document").ready(function() {

    $(document).on('click', '#minify_css', function(event) {
        event.preventDefault();

        var css_code = $("#css_code").val().trim();

        if (css_code == '') {
            swal(global_lang_error, Please_write_css_first, 'error');
            return false;
        }

        $('#middle_column_content').html("");
        $("#minify_css").addClass('btn-progress');
        $("#custom_spinner").html(
            '<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>'
            );


        $.ajax({
            url: css_minifier_textarea,
            type: 'POST',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            data: {
                css_code: css_code
            },
            success: function(response) {
                $("#minify_css").removeClass('btn-progress');
                $("#custom_spinner").html("");
                $("#middle_column_content").html(response.trim())

            }

        });

    });

     var files_list = [];
      $("#file_upload_url").uploadFile({
        url:read_text_file_css,
        fileName:"myfile",
        maxFileSize:file_upload_limit*1024*1024,
        showPreview:false,
        returnType: "json",
        dragDrop: true,
        showDelete: true,
        multiple:true,
        maxFileCount:5,
        acceptFiles:".css",
        deleteCallback: function (data, pd) {
              $.post(read_after_delete_css, {op: "delete",name: data.file_name},
                  function (resp,textStatus, jqXHR) {

                    var item_to_delete =data.content;
                    files_list = files_list.filter(item => item !== item_to_delete);
                    $("#css_code").val(files_list.join());

                  });

         },
         onSuccess:function(files,data,xhr,pd)
           {
               if (data.are_u_kidding_me =="yarki") {
               swal(global_lang_error, Something_went_wrong_please_choose_valid_file, 'error');
                return false;
               }

               $("#css_code").val(data.content);
               var data_modified = data.content;
               files_list.push(data_modified);
               $("#css_code").val(files_list.join());


           }
    });

});