"use strict" 

var number_dir = 0;
var all_robot = 1;
var custom_robot = 0;
$(document).ready(function(){

    $(document).on('click','#btn2',function(event){
        event.preventDefault();
        number_dir++;
        var added_dir = "restricted_dir"+number_dir;
        var str = '<div class="form-group col-12"><input type="text" class="form-control" id = "'+added_dir+'" placeholder="Eg. /temp/img/" name="directory[]"></div>';
        
        $("#custom_setting3").append(str);   

    });
    $(document).on('click','#generate_robot_code',function(event){
        event.preventDefault();
        var i;
        var dir_str = '';
        var dir = '';
        var restricted_dir = '';
        for(i = 0; i<= number_dir; i++){
          dir = 'restricted_dir'+i;
          dir_str = $('#'+dir+'').val();
          restricted_dir = restricted_dir+dir_str+',';
        }
        $("#set_auto_comment_templete_modal").modal();
        $("#generate_robot_code").addClass('btn-progress');
          $.ajax({
            type:'POST',
            url: robot_code_generator_action,
            beforeSend: function (xhr) {
              xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            data:{all_robot:all_robot,
                  custom_robot:custom_robot,

                  basic_all_robots:$("#basic_all_robots").val(),
                  crawl_delay:$("#crawl_delay").val(),
                  site_map:$("#site_map").val(),
                  custom_crawl_delay:$("#custom_crawl_delay").val(),
                  custom_site_map:$("#custom_site_map").val(),
                  google:$("#google").val(),
                  msn_search:$("#msn_search").val(),
                  yahoo:$("#yahoo").val(),
                  ask_teoma:$("#ask_teoma").val(),
                  cuil:$("#cuil").val(),
                  gigablast:$("#gigablast").val(),
                  scrub:$("#scrub").val(),
                  dmoz_checker:$("#dmoz_checker").val(),
                  nutch:$("#nutch").val(),
                  alexa_wayback:$("#alexa_wayback").val(),
                  baidu:$("#baidu").val(),
                  never:$("#never").val(),

                  google_image:$("#google_image").val(),
                  google_mobile:$("#google_mobile").val(),
                  yahoo_mm:$("#yahoo_mm").val(),
                  msn_picsearch:$("#msn_picsearch").val(),
                  SingingFish:$("#SingingFish").val(),
                  yahoo_blogs:$("#yahoo_blogs").val(),
                  restricted_dir:restricted_dir
                  
            },
            success:function(response){
             $("#unique_email_download_div").html('<p>'+Your_file_is_ready_download+'</p> <a href="'+link+'" target="_blank" class="btn btn-lg btn-warning"><i class="fa fa-cloud-download"></i> <b>'+download+'</b></a>');
                $("#generate_robot_code").removeClass('btn-progress');
                $("#success_msg").html(response);

            }

          });
    })
    $(document).on('change','#do_u_want_to_more_specific_robot',function(event){
      event.preventDefault();
     
     if($('input[name=do_u_want_to_more_specific_robot]').prop('checked')){
        all_robot = 0;
        custom_robot =1;
        $('#custom_setting').slideDown(500);
        $('#custom_setting2').slideDown(500);
        $('#custom_setting3').slideDown(500);
        $('#show_hide').hide();
     }

     else{
        all_robot = 1;
        custom_robot =0;
        $('#custom_setting').slideUp(500);
        $('#custom_setting2').slideUp(500);
        $('#custom_setting3').slideUp(500);
      }

    }); 





});
