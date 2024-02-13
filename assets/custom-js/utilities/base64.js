"use strict" 



$("document").ready(function(){


    $(document).on('click', '#new_search_button', function(event) {
      event.preventDefault();


        var base64=$("#base64").val();
        
        if(base64==''){
          swal(global_lang_error, You_have_not_enter_any_content, 'error');
          return false;
        }
        $('#middle_column_content').html("");
        $("#new_search_button").addClass('btn-progress');
        $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');
        
       
        $.ajax({
          url:base64_encode_action,
          type:'POST',
          beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          },
          data:{base64:base64},
          success:function(response){  

            $("#new_search_button").removeClass('btn-progress');
            $("#custom_spinner").html("");
            $("#middle_column_content").html(response);
            
          }
          
        });
        
    });

    $(document).on('click','#new_search_button_decode',function(event){
      event.preventDefault();

      var base64=$("#base64").val();
      var base_url="<?php echo base_url(); ?>";
      if(base_url==''){

        swal(global_lang_error, You_have_not_enter_any_content, 'error');
        return false;
      }
      $('#middle_column_content').html("");
      $("#new_search_button_decode").addClass('btn-progress');
      $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');
      
      
      $.ajax({
        url:base64_decode_action,
        type:'POST',
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        },
        data:{base64:base64},
        success:function(response){         
          
          $("#new_search_button_decode").removeClass('btn-progress');
          $("#custom_spinner").html("");
          $("#middle_column_content").html(response);


          
        }
        
      });

    });

  });  