"use strict";




$("document").ready(function(){

    $(document).on('click', '#new_search_button', function(event) {
      event.preventDefault();



     var domain_name=$("#domain_name").val();

      if (domain_name == '') {
        swal(global_lang_error, Please_Enter_Domain_Name, 'error');
        return false;
      }

      
      $('#middle_column_content').html("");
      $("#new_search_button").addClass('btn-progress');
      $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/><p class="text-center">'+Please_wait_for_while+'</p>');


      $.ajax({
        url:ip_canonical_action,
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



  });  