"use strict";


$(document).ready(function() {

   var perscroll;

   var table = $("#mytable").DataTable({
      serverSide: true,
      processing:true,
      bFilter: true,
      order: [[ 1, "desc" ]],
      pageLength: 10,
      ajax: 
      {
          "url": package_manager_data,
          "type": 'POST',
          beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        },
      },
      language: 
      {
        url: datatable_lang_file
      },
      dom: '<"top"f>rt<"bottom"lip><"clear">',
      columnDefs: [
        {
            targets: [1],
            visible: false
        },
        {
            targets: '',
            className: 'text-center'
        },
        {
            targets: [0,6],
            sortable: false
        },
        {
          targets: [3],
          "render": function ( data, type, row, meta ) 
          {
             if(row[5]=="1" && row[3]=="0")
             return "Free"; 
             else return data;  
          }
        },
        {
          targets: [4],
          "render": function ( data, type, row, meta ) 
          {
             if(row[5]=="1" && row[3]=="0")
             return "Unlimited"; 
             else return data; 
          }
        },
        {
          targets: [5],
          "render": function ( data, type, row, meta ) 
          {
             if(data==1) return "<i class='fas fa-check-circle green'></i>";            
             else return "<i class='fas fa-times-circle'></i>";
          }
        },
        {
          targets: [6],
          "render": function ( data, type, row, meta ) 
          {
              var url=base_url+'/payment/details_package/'+row[1];        
              var edit_url=base_url+'/payment/edit_package/'+row[1];
              var delete_url=base_url+'/payment/delete_package/'+row[1];

              var str="";   
              str="&nbsp;<a class='btn btn-circle btn-outline-primary' href='"+url+"'>"+'<i class="fas fa-eye"></i>'+"</a>";
              str=str+"&nbsp;<a class='btn btn-circle btn-outline-warning' href='"+edit_url+"'>"+'<i class="fas fa-edit"></i>'+"</a>";
             
              if(row[5]=='0')
              str=str+"&nbsp;<a href='"+delete_url+"' csrf_token='"+csrf_token+"' class='are_you_sure_datatable btn btn-circle btn-outline-danger'>"+'<i class="fa fa-trash"></i>'+"</a>";
              else str=str+"&nbsp;<a class='btn btn-circle btn-outline-light' data-toggle='tooltip' title='Default_package_can_not_be_deleted'>"+'<i class="fa fa-trash"></i>'+"</a>";
            
              return "<div style='min-weight:130px'>"+str+'</div>';
          }
        }
      ],
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
});



