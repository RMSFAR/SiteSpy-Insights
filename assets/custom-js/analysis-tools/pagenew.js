"use strict";



  
$("document").ready(function(){
  
    $(document).on('click', '#new_search_button', function(event) {
      event.preventDefault();



     var domain_name=$("#domain_name").val();

      if (domain_name == '') {
        swal(global_lang_error, global_Please_enter_url, 'error');
        return false;
      }

      
      $('#middle_column_content').html("");
      $("#new_search_button").addClass('btn-progress');
      $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/><p class="text-center">'+Please_wait_for_while+'</p>');


      $.ajax({
        url:page_status_action,
        type:'POST',
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        },
        data:{domain_name:domain_name},
        success:function(response){ 
          $("#new_search_button").removeClass('btn-progress');
          $("#custom_spinner").html("");
          $("#middle_column_content").html(response);

        }

      });
        
    });

     var files_list = [];
      $("#file_upload_url").uploadFile({
        url:read_text_csv_file_backlink,
        fileName:"myfile",
        maxFileSize:file_upload_limit*1024*1024,
        showPreview:false,
        returnType: "json",
        dragDrop: true,
        showDelete: true,
        multiple:true,
        maxFileCount:5,
        acceptFiles:".csv,.txt",
        deleteCallback: function (data, pd) {
            
              $.post(read_after_delete_csv_txt, {op: "delete",name: data.file_name},
                  function (resp,textStatus, jqXHR) {

                    var item_to_delete =data.content;
                    files_list = files_list.filter(item => item !== item_to_delete);
                    $("#domain_name").val(files_list.join());

                  });

         },
         onSuccess:function(files,data,xhr,pd)
           {
               if (data.are_u_kidding_me =="yarki") {
               swal(global_lang_error, Something_went_wrong_please_choose_valid_file, 'error');
                return false;
               }

               $("#domain_name").val(data.content);
               var data_modified = data.content;
               files_list.push(data_modified);
               $("#domain_name").val(files_list.join());
                  
            
           }
    });




  }); 