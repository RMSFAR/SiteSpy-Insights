"use strict";
$(document).ready(function(){    
    $(document).on('click','#submit',function(e){
      e.preventDefault();
      var purchase_code = $("#purchase_code").val().trim();
      if(purchase_code=='')
      {
          $("#purchase_code").addClass('is-invalid');
          return false;
      }
      else
      {
          $("#purchase_code").removeClass('is-invalid');
      }

      

      $(this).addClass("btn-progress");
      $.ajax({
          context: this,
          type: "POST",
          url : purchase_code_active,
          beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          },
          data:{domain_name:base_url,purchase_code:purchase_code},
          dataType: 'JSON',
          // async: false,
          success:function(response)
          {
            response = JSON.parse(response)
            $(this).removeClass("btn-progress");
            if(response.status == "success")
            {
              var link = dashboard_url;
              window.location.assign(link);
            }
            else 
            {
              var success_message=response.reason;
              var span = document.createElement("span");
              span.innerHTML = success_message;
              swal({ title:global_lang_error, content:span,icon:'error'});
            }   
          }
        });


    });
  });