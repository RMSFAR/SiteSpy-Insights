"use strict";


$("document").ready(function(){
    
    $(document).on('click', '#new_search_button', function(event) {
      event.preventDefault();

      var is_google;
      var is_bing;
      var is_yahoo;


      if($('input[name=google_index]').prop('checked'))
        is_google = 1;
      else
        is_google = 0;

      if($('input[name=bing_index]').prop('checked'))
        is_bing = 1;
      else
        is_bing = 0;

      if($('input[name=yahoo_index]').prop('checked'))
        is_yahoo = 1;
      else
        is_yahoo = 0;

      var domain_name=$("#domain_name").val();

      if (is_google==0 && is_bing==0 && is_yahoo==0) {
        swal(global_lang_error, Please_Check_Any_Search_Engine, 'error');
        return false;
      }
      
      if (domain_name == '') {
        swal(global_lang_error, Please_enter_your_domain_name_first, 'error');
        return false;
      }


      
      $('#middle_column_content').html("");
      $("#new_search_button").addClass('btn-progress');
      $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/><p class="text-center">'+Please_wait_for_while+'</p>');


      $.ajax({
        url:search_engine_index_action,
        type:'POST',
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
      },
        data:{domain_name:domain_name,is_google:is_google,is_bing:is_bing,is_yahoo:is_yahoo},
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
               swal(global_lang_error, Something_went_wrong_please_choose_valid_file , 'error');
                return false;
               }

               $("#domain_name").val(data.content);
               var data_modified = data.content;
               files_list.push(data_modified);
               $("#domain_name").val(files_list.join());
                  
            
           }
    });




  });  