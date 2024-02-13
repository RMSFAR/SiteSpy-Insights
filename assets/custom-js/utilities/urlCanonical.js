"use strict" 


$("document").ready(function(){

    $(document).on('click', '#new_search_button', function(event) {
      event.preventDefault();

        var emails=$("#bulk_email").val();
        
        if(emails==''){
          swal(global_lang_error, Please_enter_your_urls, 'error');
          return false;
        }

        $('#middle_column_content').html("");
        $("#new_search_button").addClass('btn-progress');
        $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');


        $.ajax({
          url:url_canonical_action,
          type:'POST',
          beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          },
          data:{emails:emails},
          success:function(response){ 

              $("#new_search_button").removeClass('btn-progress');
              $("#custom_spinner").html("");      
              $("#middle_column_content").html(response);

          }
          
        });
        
    });


  });  