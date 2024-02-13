"use strict" 


$("document").ready(function(){
    $(document).on('click', '#new_search_button', function(event) {
      event.preventDefault();

        var emails=$("#bulk_email").val();
        
        if(emails==''){
          swal(global_lang_error, Please_enter_your_emails, 'error');
          return false;
        }

        $('#middle_column_content').html("");
        $("#new_search_button").addClass('btn-progress');
        $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');


        $.ajax({
          url:email_unique_maker,
          type:'POST',
          beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          },
          data:{emails:emails},
          success:function(response){ 
              $("#new_search_button").removeClass('btn-progress');
              $("#custom_spinner").html("");
              $("#middle_column_content").html(response)

            
          }
          
        });
        
    });


     var files_list = [];
      $("#file_upload_url").uploadFile({
        url:global_read_text_file,
        fileName:"myfile",
        maxFileSize:file_upload_limit*1024*1024,
        showPreview:false,
        returnType: "json",
        dragDrop: true,
        showDelete: true,
        multiple:true,
        maxFileCount:5,
        acceptFiles:".csv,.txt,.doc",
        deleteCallback: function (data, pd) {
            
              $.post(global_read_after_delete, {op: "delete",name: data.file_name},
                  function (resp,textStatus, jqXHR) {

                    var item_to_delete =data.content;
                    files_list = files_list.filter(item => item !== item_to_delete);
                    $("#bulk_email").val(files_list.join());

                  });

         },
         onSuccess:function(files,data,xhr,pd)
           {
               if (data.are_u_kidding_me =="yarki") {
               swal(global_lang_error, Something_went_wrong_please_choose_valid_file, 'error');
                return false;
               }

               $("#bulk_email").val(data.content);
               var data_modified = data.content;
               files_list.push(data_modified);
               $("#bulk_email").val(files_list.join());
                  
            
           }
    });

  });  