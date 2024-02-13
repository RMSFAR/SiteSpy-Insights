"use strict" 


$("document").ready(function(){

    $(document).on('click', '#new_search_button', function(event) {
      event.preventDefault();


      var long_url=$("#long_url").val();
      
      if (long_url == '') {
        swal(global_lang_error, Please_enter_long_url, 'error');
        return false;
      }

      var title = $("#title").val();
      
      $('#middle_column_content').html("");
      $("#new_search_button").addClass('btn-progress');
      $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/><p class="text-center">'+Please_wait_for_while+'</p>');


      $.ajax({
        url:rebrandly_shortener_action,
        type:'POST',
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        },
        data:{long_url:long_url,title:title},
        success:function(response){ 
          $("#new_search_button").removeClass('btn-progress');
          $("#custom_spinner").html("");
          $("#middle_column_content").html(response);

        }

      });
        
    });





  });  