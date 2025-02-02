"use strict";

var drop_menu = '<a href="javascript:;" id="payment_date_range" class="btn btn-primary btn-lg float-right icon-left btn-icon"><i class="far fa-calendar"></i> '+global_lang_choose_data+'</a><input type="hidden" id="payment_date_range">'

    setTimeout(function(){ 
        $("#mytable_filter").append(drop_menu)
        $('#payment_date_range').daterangepicker({
        ranges: {
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month'  : [moment().startOf('month'), moment().endOf('month')],
            'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
        }, function (start, end) {
        $('#payment_date_range_val').val(start.format('YYYY-M-D') + '|' + end.format('YYYY-M-D')).change();
        });
    }, 2000);


    $(document).ready(function() {

        var perscroll;
        var table = $("#mytable").DataTable({
            serverSide: true,
            processing:true,
            bFilter: true,
            order: [[ 2, "desc" ]],
            pageLength: 10,
            ajax: {
                url: transaction_log_data,
                type: 'POST',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
                },
                data: function ( d )
                {
                    d.payment_date_range = $('#payment_date_range_val').val();
                }
            },
            language: 
            {
            url: datatable_lang_file
            },
            dom: '<"top"f>rt<"bottom"lip><"clear">',
            columnDefs: [
            {
                targets: [1,2],
                visible: false
            },
            {
                targets: [6,7,8,9],
                className: 'text-center'
            },
            {
                targets: [10],
                className: 'text-right'
            },
            {
                targets: [0,1,2],
                sortable: false
            }
            ],
            footerCallback: function ( row, data, start, end, display ) {
                var api = this.api(), data;
                var payment_total = api
                .column( 10 )
                .data()
                .reduce( function (a, b) {
                return parseInt(a) + parseInt(b);
                }, 0 );
                $( api.column( 10 ).footer() ).html('$'+payment_total);
            },
            fnInitComplete:function(){  // when initialization is completed then apply scroll plugin
                if(areWeUsingScroll)
                {
                if (perscroll) perscroll.destroy();
                perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
                }
            },
            scrollX: 'auto',
            fnDrawCallback: function( oSettings ) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again 
                if(areWeUsingScroll)
                { 
                if (perscroll) perscroll.destroy();
                perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
                }
            }
        });

        $(document).on('change', '#payment_date_range_val', function(event) {
        event.preventDefault(); 
        table.draw();
        });
    });

