"use strict";

var counter=0;
$(document).ready(function() {      

    setTimeout(function() {          
      var start = $("#load_more").attr("data-start");   
      load_data(start,false,false);
    }, 1000);


    $(document).on('click', '#load_more', function(e) {
      var start = $("#load_more").attr("data-start");   
      load_data(start,false,true);
    });

    $(document).on('change', '#seen_type', function(e) {
      var start = '0';
      load_data(start,true,false);
    });


    $(document).on('click', '#search_submit', function(e) {
      var start = '0';
      load_data(start,true,false);
    });

    function load_data(start,reset,popmessage) 
    {
      var limit = $("#load_more").attr("data-limit");        
      var search = $("#search").val();
      var seen_type = $("#seen_type").val();
      $("#waiting").show();
      if(reset) 
      {
        $("#search_submit").addClass("btn-progress");
        counter = 0;
      }
      $.ajax({
        url:announcement_list_data,
        type: 'POST',
        dataType : 'JSON',
        data: {start:start,limit:limit,search:search,seen_type:seen_type},
          success:function(response)
          {
            $("#waiting").hide();
            $("#nodata").hide();
            $("#search_submit").removeClass("btn-progress");

            counter += response.found; 
            $("#load_more").attr("data-start",counter); 
            if(!reset)  $("#load_data").append(response.html);
            else $("#load_data").html(response.html);

            if(response.found!='0') $("#load_more").show();                
            else 
            {
              $("#load_more").hide();
              if(popmessage) 
              {
                swal(No_data_found, "", "warning");
                $("#nodata").hide();
              }
              else $("#nodata").show();
            }
          }
      });
    }

    $(document).on('click', '.mark_seen', function(e) {
      e.preventDefault();
      var link = $(this).attr("href");
      
      $(this).addClass('btn-progress');
      $.ajax({
        context: this,
        url: link,
        type: 'POST',
        dataType: 'JSON',
        data: {},
          success:function(response)
          {
            $(this).removeClass('btn-progress');
            if(response.status == "1")  
            {
              iziToast.success({title: global_lang_success , message: response.message,position: 'bottomRight'});
              $(this).parent().parent().parent().hide();
            }
            else iziToast.error({title: global_lang_error , message: response.message,position: 'bottomRight'});
          }
      });
    });

    $(document).on('click', '#mark_seen_all', function(e) {
        e.preventDefault();
        var mes=Do_you_really_want_to_mark_all_unseen_notifications_as_seen;  
        swal({
          title: global_lang_confirmation,
          text: mes,
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => 
        {
          if (willDelete) 
          {
              $(this).addClass('btn-progress');
              $.ajax({
                context: this,
                url: announcement_mark_seen_all,
                type: 'POST',
                dataType: 'JSON',
                data: {},
                  success:function(response)
                  {
                    $(this).removeClass('btn-progress');
                    if(response.status == "1")  
                    {
                      location.reload();
                    }
                    else iziToast.error({title: global_lang_error,message: response.message,position: 'bottomRight'});
                  }
              });
          } 
        });
    
    }); 

    $(document).on('click', '.delete_annoucement', function(e) {
        e.preventDefault();
        var link = $(this).attr("href");
        var mes=Do_you_really_want_to_delete_it;  
        swal({
          title: global_lang_confirmation,
          text: mes,
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => 
        {
          if (willDelete) 
          {
              $(this).addClass('btn-progress');
              $.ajax({
                context: this,
                url: link,
                type: 'POST',
                dataType: 'JSON',
                data: {},
                success:function(response)
                {
                  $(this).removeClass('btn-progress');
                  if(response.status == "1")  
                  {
                      iziToast.success({title: global_lang_success,message: response.message,position: 'bottomRight'});
                      $(this).parent().parent().parent().parent().parent().hide();
                  }
                  else iziToast.error({title: global_lang_error,message: response.message,position: 'bottomRight'});
                  }                      
              });
            } 
        });
    
    });

});