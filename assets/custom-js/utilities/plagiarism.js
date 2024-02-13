"use strict" 

$("document").ready(function(){

    $(document).on('click', '#new_search_button', function(event) {
      event.preventDefault();

        var emails=$("#bulk_email").val();
        
        if(emails==''){
          swal(global_lang_error, Please_enter_your_contents, 'error');
          return false;
        }

        $('#middle_column_content').html("");
        $("#new_search_button").addClass('btn-progress');
        $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');


        $.ajax({
          url:plagarism_check_action,
          type:'POST',
          beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          },
          data:{emails:emails},
          success:function(response){ 
              $("#new_search_button").removeClass('btn-progress');
              $("#custom_spinner").html("");
             
            if(response == 2){

              swal(global_lang_error, your_bulk_limit_is_exceeded_for_this_module, 'error').then(function(){
                window.location = base_url+'payment/usage_history';
              });
              return false;

            }

            else if(response == 3){

              swal(global_lang_error, your_limit_is_exceeded_for_this_module, 'error').then(function(){
                window.location = base_url+'payment/usage_history';
              });
              return false;

            }

            else {
                
            
              var res = response.split("_sep_");
              var unique_per=100-res[1];        
              $("#middle_column_content").html(res[0]);
              /*if(response>0)*/
               // $("#success_msg").html('<center><h3 class = "text-info">'+res[0]+'</h3></center>');
                $("#unique_per").html('<center style="margin-top:10px;"><h6 class = "text-info">Unique: '+unique_per+'%</h6></center>');

              /*if(response==0)
                $("#success_msg").html('<center><h3 class = "text-danger"> Match Not Found </h3></center>');*/

              if(res[0]=='size_error'){

                swal(global_lang_error, You_have_entered_too_large_string, 'error');
                return false;
              }

              if(res[0]=='blank_error'){
                swal(global_lang_error, you_have_provided_a_blank_string, 'error');
                return false;
              }
            }

            
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