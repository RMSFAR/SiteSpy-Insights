"use strict";



$("document").ready(function(){

    $(document).on('click', '#new_search_button', function(event) {
      event.preventDefault();

        var keyword_domain_name=$("#keyword_domain_name").val();      


        if(keyword_domain_name==''){
          swal(global_lang_error, Please_enter_your_domain_name_first, 'error');
          return false;
        }        


        $('#middle_column_content').html("");
        $("#new_search_button").addClass('btn-progress');
        $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/><p class="text-center">'+Please_wait_for_while+'</p>');


        $.ajax({
          url:keyword_analyzer_data,
          type:'POST',
          beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          },
          data:{keyword_domain_name:keyword_domain_name},
          success:function(response){ 
              $("#new_search_button").removeClass('btn-progress');
              $("#custom_spinner").html("");
              $("#middle_column_content").html(response);
            
          }
          
        });
        
    });




  });  