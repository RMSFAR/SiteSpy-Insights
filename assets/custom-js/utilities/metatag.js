"use strict" 


var is_google = 0;
var is_twiter = 0;
var is_facebook = 0;

$("document").ready(function(){


  $(document).on('change','#google_check_box',function(event){
    event.preventDefault();
   
   if($('input[name=google_check_box]').prop('checked')){
       is_google = 1;
      $('#google_block').slideDown(500);
      $('#show_hide').hide();
   }

   else{
      is_google = 0;
      $('#google_block').slideUp(500);
    }

  });    
  $(document).on('change','#facebook_check_box',function(event){
    event.preventDefault();
   
   if($('input[name=facebook_check_box]').prop('checked')){
      is_facebook = 1;
       $('#show_hide').hide();
      $('#facebook_block').slideDown(500);

   }

   else{
     is_facebook = 0;
      $('#facebook_block').slideUp(500);
   }

  });    
  $(document).on('change','#twiter_check_box',function(event){
    event.preventDefault();
   
   if($('input[name=twiter_check_box]').prop('checked')){
      is_twiter = 1;
       $('#show_hide').hide();
      $('#twiter_block').slideDown(500);
   }

  else{
      is_twiter = 0;
      $('#twiter_block').slideUp(500);
  }

  });

  $(document).on('click', '#new_search_button', function(event) {
    event.preventDefault();

      var base64=$("#base64").val();
      
      if(is_google ==0 && is_twiter ==0 && is_facebook ==0){
        swal(global_lang_error, One_or_more_required_fields_are_missing, 'error');
        return false;
      }
       $("#set_auto_comment_templete_modal").modal();
      $("#new_search_button").addClass('btn-progress');
      //$("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');
      
     
      $.ajax({
        url:meta_tag_action,
        type:'POST',
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        },
        data:{is_google:is_google,
          is_facebook:is_facebook,
          is_twiter:is_twiter,
          google_description:$("#google_description").val(),          
          google_keywords:$("#google_keywords").val(),          
          google_copyright:$("#google_copyright").val(),          
          google_author:$("#google_author").val(),          
          google_application_name:$("#google_application_name").val(),          
          facebook_title:$("#facebook_title").val(),          
          facebook_type:$("#facebook_type").val(),          
          facebook_image:$("#facebook_image").val(),          
          facebook_url:$("#facebook_url").val(),          
          facebook_description:$("#facebook_description").val(),          
          facebook_app_id:$("#facebook_app_id").val(),          
          facebook_localization:$("#facebook_localization").val(),          
          twiter_card:$("#twiter_card").val(),          
          twiter_title:$("#twiter_title").val(),          
          twiter_description:$("#twiter_description").val(),          
          twiter_image:$("#twiter_image").val() },
          
        success:function(response){    
          $("#unique_email_download_div").html('<p>'+Your_file_is_ready_download+'</p> <a href="'+link+'" target="_blank" class="btn btn-lg btn-primary"><i class="fa fa-cloud-download"></i> <b>'+download+'</b></a>');
         $("#new_search_button").removeClass('btn-progress');
          $("#success_msg").html(response);
        
          
        }
        
      });
      
  });



});  