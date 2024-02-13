"use strict" 

$("document").ready(function()	{
	
    $(document).on('click', '.action_button', function(event) {
        event.preventDefault();

        var url1 = $("#domain_name1").val();
        var url2 = $("#domain_name2").val();
        $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');
        $.ajax({
            type:'POST' ,
            url:base_url + "/tools/comparison_action",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            data: {url1:url1,url2:url2},
            dataType:"json",
            success:function(response){

                if (response.empty == 'empty' && response.empty1 == 'empty1') {
                        swal(global_lang_error, global_Please_enter_url, 'error');
                        $("#custom_spinner").html("");
                        return false;
                }
                if(response.status == '0'){
                    swal(global_lang_error, your_limit_is_exceeded_for_this_module, 'error');
                }
                $("#custom_spinner").html("");
                $(".one").html(response.output1);
                $(".two").html(response.output2);
                if(response.empty1 == 'empty1')
                    $('.two').html("");
                if(response.empty == 'empty')
                    $(".one").html("");


            }
        });
       

    });




});