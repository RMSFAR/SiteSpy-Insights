"use strict";


$("document").ready(function(){
    
    $(document).on('click', '#new_search_button', function(event) {
      event.preventDefault();

      var is_google;
      var is_bing;
      var is_yahoo;
      var is_wiki;
      var is_amazon;

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

      if($('input[name=keyword_wiki]').prop('checked'))
        is_wiki = 1;
      else
        is_wiki = 0;

      if($('input[name=keyword_amazon]').prop('checked'))
        is_amazon = 1;
      else
        is_amazon = 0;


      if(is_google == 0 && is_bing == 0 && is_yahoo == 0 && is_wiki == 0 && is_amazon == 0){
        swal(global_lang_error, Please_Select_Search_Engine, 'error');
        return false;
      }



     var keyword=$("#keyword").val();

      if (keyword == '') {
        swal(global_lang_error, Please_enter_keyword, 'error');
        return false;
      }

      
      $('#middle_column_content').html("");
      $("#new_search_button").addClass('btn-progress');
      $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/><p class="text-center">'+Please_wait_for_while+'</p>');


      $.ajax({
        url:keyword_suggestion_action,
        type:'POST',
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        },
        data:{keyword:keyword,is_google:is_google,is_bing:is_bing,is_yahoo:is_yahoo,is_wiki:is_wiki,is_amazon:is_amazon},
        success:function(response){ 
          $("#new_search_button").removeClass('btn-progress');
          $("#custom_spinner").html("");
          $("#middle_column_content").html(response);

        }

      });
        
    });

    




});  