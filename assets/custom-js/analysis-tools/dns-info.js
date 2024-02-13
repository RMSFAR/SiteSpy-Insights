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
        url:dns_info_action,
        type:'POST',
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        },
        data:{domain_name:domain_name},
        dataType:'json',
        success:function(response){ 
          $("#new_search_button").removeClass('btn-progress');
          $("#custom_spinner").html("");
          $("#middle_column_content").html(response.url_lists);

          
        }

      });
        
    });

    $(document).on('click','.details',function(event){
      event.preventDefault();

        let single_details = $(this).data('details');
        single_details = JSON.stringify(single_details);
        single_details  = JSON.parse(single_details);
        
        var html = '<div class="table-responsive table-invoice"><table class="table table-hover table-striped"><tbody><tr><th>'+Type+'</th><th>'+Host+'</th><th>'+Target+'</th><th>'+Class+'</th><th>'+lang_TTL+'</th></tr>';
        if (Array.isArray(single_details)) {
          single_details.forEach(function(single){
             if (single.ip)
                var ip = single.ip;
              else
                var ip = single.target;
             html += '<tr><td>'+single.type+'</td><td>'+single.host+'</td><td>'+ip+'</td><td>'+single.class+'</td><td>'+single.ttl+'</td></tr></tbody>';
          })
        }
        html += '</table></div>';

        $('#who_is_download_selected').modal();
        $("#total_download_selected").html(html);

    })



  });  