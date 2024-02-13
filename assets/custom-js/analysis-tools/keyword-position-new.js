"use strict";


  
$("document").ready(function(){
  
    $(document).on('click', '#new_search_button', function(event) {
      event.preventDefault();

      var is_google;
      var is_bing;
      var is_yahoo;

      if($('input[name=keyword_google]').prop('checked'))
        is_google = 1;
      else
        is_google = 0;

      if($('input[name=keyword_bing]').prop('checked'))
        is_bing = 1;
      else
        is_bing = 0;

      if($('input[name=keyword_yahoo]').prop('checked'))
        is_yahoo = 1;
      else
        is_yahoo = 0;

      if(is_google == 0 && is_bing == 0 && is_yahoo == 0){
        swal(global_lang_error, Please_Select_Search_Engine, 'error');
        return false;
      }

      var language = $("#language_name").val();
      var country = $("#country_name").val();

      if(language == '') 
        language = "all";
      if(country == '') 
        country = "all";     

      var keyword=$("#keyword").val();
      var domain_name=$("#domain_name").val();  
      if (domain_name == '') {
        swal(global_lang_error, Please_enter_website_url, 'error');
        return false;
      }
      if (keyword == '') {
                swal(global_lang_error, Please_enter_keyword, 'error');
        return false;
      }

      
      $('#middle_column_content').html("");
      $("#new_search_button").addClass('btn-progress');
      $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/><p class="text-center">'+Please_wait_for_while+'</p>');


      $.ajax({
        url:keyword_position_action,
        type:'POST',
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        },
        data:{domain_name:domain_name,keyword:keyword,language:language,country:country,is_google:is_google,is_bing:is_bing,is_yahoo:is_yahoo},
        success:function(response){ 
          $("#new_search_button").removeClass('btn-progress');
          $("#custom_spinner").html("");
          $("#middle_column_content").html(response);

        }

      });
        
    });

    




  });  