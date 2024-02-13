"use strict";

$(document).ready(function() {
  
    var today = new Date();
    var next_date = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
    $('.datepicker_x').datetimepicker({
        theme:'light',
        format:'Y-m-d',
        formatDate:'Y-m-d',
        timepicker:false
    });

    
    

    $(document).on('click', '#start_searching', function(event) {
        event.preventDefault();
        
        var keyword = $("#keyword").val();
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();

        if (keyword == '' || from_date == '' || to_date == '') {
            swal(lang1, lang2, 'warning');
            return false;
        }

        $(this).addClass('btn-progress');
        var that = $(this);

        var go_back = Back;

        $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><p class="text-center">'+lang3+'</p>');
        $('#middle_column_content').html("");

        $.ajax({
            url: position_report,
            type: 'POST',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            data: {keyword: keyword,from_date: from_date,to_date: to_date},
            success:function(response) {

                $(that).removeClass('btn-progress');
                $("#custom_spinner").html("");
                $("#middle_column_content").html(response);

            }
        })
    });
});