"use strict";


$('document').ready(function(){

    $("#submit").click(function(e){
      e.preventDefault();

      $("#msg").removeAttr('class');
      $("#msg").html("");

      var code=$("#code").val();
      var email=$("#email").val();  

      if(email=='')
      {
          $("#email").addClass('is-invalid');
          return false;
      }
      else
      {
          $("#email").removeClass('is-invalid');
      }

      if(code=='')
      {
          $("#code").addClass('is-invalid');
          return false;
      }
      else
      {
          $("#code").removeClass('is-invalid');
      }
      
      $(this).addClass('btn-progress');
      $.ajax({
        context: this,
        type:'POST',
        url: activation_action,
        beforeSend: function (xhr) {
              xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        },
        data:{code:code,email:email},
        success:function(response){
              $(this).removeClass('btn-progress');
              if(response == 0)
              {
                swal(lang_error, Account_activation_code_does_not_match, 'error');
              }
              if(response == 2)
              {
                var string='<div class="alert alert-primary alert-has-icon"><div class="alert-icon"><i class="far fa-check-circle"></i></div><div class="alert-body"><div class="alert-title"><a href="'+login+'">'+You_can_login_here+'</a></div>'+your_account_has_been_activated+'</div></div>';
                $("#recovery_form").slideUp();
                $("#recovery_form").html(string);
                $("#recovery_form").slideDown();
              }
          }
      });
      
    });
  });