{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Website analysis'))
@section('content')

<style>
    ::placeholder{color:#bbb !important;}
    .dropdown-toggle::after{content:none !important;}
    #domain_name{max-width: 57% !important;}
    .bbw{border-bottom-width: thin !important;border-bottom:solid .5px #f9f9f9 !important;padding-bottom:20px;}
    @media (max-width: 575.98px) { #domain_name{max-width: 90% !important;} }
</style>
  
  <section class="section section_custom">
      <div class="section-header">
          <h1><i class="fa fa-globe"></i>{{ __('Website analysis')}}</h1>
          <div class="section-header-button">
              <a class="btn btn-primary add_domain_modal" href="#">
                  <i class="fas fa-plus-circle"></i>{{ __("New Analysis")}}
              </a> 
          </div>
          <div class="section-header-breadcrumb">
              <div class="breadcrumb-item"><a href="{{route("analysis_tools")}}">{{ __("Analysis Tools")}}</a></div>
              <div class="breadcrumb-item">{{ __('Website analysis')}}</div>
          </div>
      </div>
  
      <div class="section-body">
          <div class="row">
              <div class="col-12">
                  <div class="card">
                      <div class="card-body data-card">
                          <div class="row">
  
                              <div class="col-md-6 col-12">
                                  <div class="input-group float-left" id="searchbox">
  
                                      <input type="text" class="form-control" id="domain_name" name="domain_name" placeholder="{{ __('Domain Name')}}" aria-label="" aria-describedby="basic-addon2">
                                      <div class="input-group-append">
                                          <button class="btn btn-primary" id="search_submit" title="{{ __('Search')}}" type="button"><i class="fas fa-search"></i> <span class="d-none d-sm-inline">{{ __('Search')}}</span></button>
                                      </div>
                                  </div>
                              </div>
  
                              <div class="col-md-6 col-12">
                                  <a href="javascript:;" id="post_date_range" class="btn btn-primary btn-lg icon-left float-right btn-icon"><i class="fas fa-calendar"></i>{{ __("Choose Date")}}</a><input type="hidden" id="post_date_range_val">
                                  <button class="btn btn-lg btn-outline-danger delet_all_domain float-right mr-1" title="{{ __('Delete Selected Domains')}}"><i class="fas fa-trash-alt"></i>{{ __('Delete')}}</button>
                              </div>
  
                          </div>
                          <div class="table-responsive2">
                              <table class="table table-bordered" id="mytable">
                                  <thead>
                                      <tr>
                                          <th>{{ __("#")}}</th>   
                                          <th style="vertical-align:middle;width:20px !important;">
                                              <input class="regular-checkbox" id="datatableSelectAllRows" type="checkbox"/>
                                              <label for="datatableSelectAllRows"></label>        
                                          </th>    
                                          <th>{{ __("ID")}}</th>      
                                          <th>{{ __("Domain")}}</th>    
                                          <th>{{ __("Search From")}}</th>    
                                          <th>{{ __("Searched At")}}</th>
                                          <th>{{ __("Actions")}}</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                              </table>
                          </div>             
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </section>
  
  <div class="modal fade" id="new_analysis_modal" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" style="max-width:50% !important;">
          <div class="modal-content">
              <div class="modal-header bbw">
                  <h5 class="modal-title blue"><i class="fa fa-hourglass-half"></i>{{ __('Analyze Website')}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
  
              <div class="modal-body">
                  <div class="row">
                      <div class="col-12">
                          <form action="#" id="domain_analyzing_form">
                              <div class="row">
                                  <div class="col-12">
                                      <div class="form-group">
                                          <div class="input-group mb-3">
                                              <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-signature"></i></div></div>
                                              <input type="text" class="form-control" placeholder="{{ __('Write or Paste Domain Name here')}}" id="analyzing_domain_name" name="analyzing_domain_name">
                                              <div class="input-group-append">
                                                  <button class="btn btn-primary" type="button" id="submit_domain"><i class="fa fa-hourglass-half"></i>{{ __('Analyze')}}</button>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>  
                          </form>
                      </div>
                  </div>
  
                  <div class="row">
                      <div class="col-12">
                          <div class="card shadow-none mb-0" id="analysis_progression">
                              <div class="card-body">
                                  <div class="text-center">
                                      <span style="font-size: 18px!important;font-weight:bold" id="domain_name_show"></span>
                                  </div>
                                  <div class="clearfix"></div>
                                  <div class="text-center" id="domain_success_msg"></div>    
  
                                  <div class="text-center" id="progress_msg">
                                      <span id="domain_progress_msg_text"></span>
                                      <div class="progress" style="display: none;height:20px;" id="domain_progress_bar_con"> 
                                          <div style="width:3%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="3" role="progressbar" class="progress-bar progress-bar-primary progress-bar-striped progress-bar-animated"><span>1%</span></div> 
                                      </div>
                                  </div>
                                  <div class="col-12 text-center"><br><h2 id="completed_result_link"></h2></div>
                                  <div class="row"><div class="col-12" id="completed_function_str"></div></div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
  
              <div class="modal-footer bg-whitesmoke">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>{{ __('Close')}}</button>
              </div>
          </div>
      </div>
  </div>
  {{-- var config_data = '{{' $has_google_api; '}}' --}}
<script>
    "use strict";

    var Something_went_wrong_please_try_once_again = '{{ __('Something went wrong, please try once again') }}';
    var Domain_has_been_Deleted_Successfully = '{{ __('Domain has been Deleted Successfully.') }}';
    var Selected_Domain_has_been_Deleted_Successfully = '{{ __('Selected Domains has been deleted Successfully.') }}';
    var Please_select_domain_to_delete = '{{ __('Please select domain to delete.') }}';
    var Connectivity_Settings = '{{ __('Connectivity Settings') }}';
    var completed = '{{ __('completed') }}';
    var please_wait = '{{ __('please wait') }}';
    var step_completed = '{{ __('step completed') }}';
    var Domain_Name_is_Required = '{{ __('Domain Name is Required') }}';
    var Please_provide_a_domain_name_in_valid_format = '{{ __('Please provide a domain name in valid format.') }}';
    var You_have_not_added_Google_API_Key = '{{ __('You have not added Google API Key, please add your Google API key from') }}';
    var website_analysis_lists_data = '{{ route('website_analysis_lists_data') }}';
    var connectivity_settings = '{{ route('connectivity_settings') }}';
    var delete_website_analysis_domain = '{{ route('delete_website_analysis_domain') }}';
    var ajax_delete_all_selected_domain = '{{ route('ajax_delete_all_selected_domain') }}';
    var bulk_scan_progress_count = '{{ route('bulk_scan_progress_count') }}';
    var ajax_domain_analysis_action = '{{ route('ajax_domain_analysis_action') }}';
    var loading_animations = "{{ asset('assets/pre-loader/loading-animations.gif') }}";


    var config_data = '<?php echo $has_google_api; ?>';;


</script>
    
{{-- <script src="{{asset('assets/custom-js/analysis-tools/ipdomain.js')}}"></script>     --}}
  
  <script>
    $(document).ready(function($){

         setTimeout(function(){ 
            $('#post_date_range').daterangepicker({
                ranges: {
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                    'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate  : moment()
            }, function (start, end) {
                $('#post_date_range_val').val(start.format('YYYY-M-D') + '|' + end.format('YYYY-M-D')).change();
            });
        }, 2000);

        var perscroll;
        var table = $("#mytable").DataTable({
            serverSide: true,
            processing:true,
            bFilter: false,
            order: [[ 2, "desc" ]],
            pageLength: 10,
            ajax: 
            {
                "url": website_analysis_lists_data,
                "type": 'POST',
                data: function ( d )
                {
                    d.domain_name = $('#domain_name').val();
                    d.post_date_range = $('#post_date_range_val').val();
                }
            },
            language: 
            {
                url: datatable_lang_file
            },
            dom: '<"top"f>rt<"bottom"lip><"clear">',
            columnDefs: [
                {
                    targets: [2],
                    visible: false
                },
                {
                    targets: [0,1,2,4,5,6],
                    className: 'text-center'
                },
                {
                    targets:[0,1,2,3,4,6],
                    sortable: false
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

        $(document).on('click', '#search_submit', function(event) {
            event.preventDefault(); 
            table.draw();
        });

        $(document).on('change', '#post_date_range_val', function(event) {
            event.preventDefault(); 
            table.draw();
        });

        $('#new_analysis_modal').on('hidden.bs.modal', function () {
            table.draw();
        });

        $(document).on('click','.delete_domain',function(e){
            e.preventDefault();
            swal({
                title: global_lang_confirmation,
                text: Doyouwanttodeletethisrecordfromdatabase,
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) 
                {
                    var table_id = $(this).attr('table_id');

                    $.ajax({
                        context: this,
                        type:'POST' ,
                        url:delete_website_analysis_domain,
                        data:{table_id:table_id},
                        success:function(response){ 

                            if(response == '1')
                            {
                                iziToast.success({title: '',message: Domain_has_been_Deleted_Successfully , position: 'bottomRight'});
                                table.draw();
                            } else
                            {
                                iziToast.error({title: '',message: Something_went_wrong_please_try_once_again , position: 'bottomRight'});
                            }
                        }
                    });
                } 
            });
        });


        $(document).on('click', '.delet_all_domain', function(event) {
            event.preventDefault();

            var domain_ids = [];
            $(".datatableCheckboxRow:checked").each(function ()
            {
                domain_ids.push(parseInt($(this).val()));
            });
            
            if(domain_ids.length==0) {

                swal(global_lang_warning, Please_select_domain_to_delete, 'warning');
                return false;

            }
            else {

                swal({title: global_lang_confirmation,text: Doyouwanttodeletealltheserecordsfromdatabase,icon: 'warning',buttons: true,dangerMode: true,})
                .then((willDelete) => {

                    if (willDelete) {

                        $(this).addClass('btn-progress');
                        $.ajax({
                            context: this,
                            type:'POST',
                            url: ajax_delete_all_selected_domain,
                            data:{info:domain_ids},
                            success:function(response){
                                $(this).removeClass('btn-progress');

                                if(response == '1') {

                                    iziToast.success({title: '',message: Selected_Domain_has_been_Deleted_Successfully ,position: 'bottomRight'});

                                } else {

                                    iziToast.success({title: '',message: Something_went_wrong_please_try_once_again,position: 'bottomRight'});

                                }

                                table.draw();
                            }
                        });

                    } 
                });
            }

        });
    });
</script>

<script type="text/javascript">



    $(document).on('click', '.add_domain_modal', function(event) {
    event.preventDefault();

        

        if(config_data == false) {

            var success_message = ""+You_have_not_added_Google_API_Key+" <a href='"+base_url+"/social_apps/connectivity_settings'> "+Connectivity_Settings+"</a>";

            var span = document.createElement("span");
            span.innerHTML = success_message;
            swal({ title:'', content:span,icon:'warning'});
            return;
            
        } else {
            $("#new_analysis_modal").modal();
            $("#analysis_progression").css("display","none");
        }
    });

    var interval="";

    function get_bulk_progress()
    {    
        var domain_name1 = $('#analyzing_domain_name').val();
        $.ajax({
            url:bulk_scan_progress_count,
            type:'POST',
            dataType:'json',
            data:{domain_name:domain_name1},
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            success:function(response){
                var search_complete=response.search_complete;
                var search_total=response.search_total;
                var latest_record=response.latest_record;
                var view_details_button = response.view_details_button;

                $("#domain_progress_msg_text").html(search_complete +" / "+ search_total +" "+step_completed+"");
                $("#completed_function_str").html(response.completed_function_str);
                var width=(search_complete*100)/search_total;
                width=Math.round(width);          
                var width_per=width+"%";
                if(width<3)
                {
                    $("#domain_progress_bar_con div").css("width","3%");
                    $("#domain_progress_bar_con div").attr("aria-valuenow","3");
                    $("#domain_progress_bar_con div span").html("1%");
                }
                else
                {
                    $("#domain_progress_bar_con div").css("width",width_per);
                    $("#domain_progress_bar_con div").attr("aria-valuenow",width);
                    $("#domain_progress_bar_con div span").html(width_per);
                }

                if(width==100) 
                {
                    $("#domain_progress_bar_con div").removeClass('progress-bar-animated');
                    $("#domain_progress_msg_text").html(completed);
                    $("#domain_success_msg").html('');
                    $("#completed_result_link").html(response.view_details_button);         
                    clearInterval(interval);
                }      

            }
        });
    }

    $(document).on('click', '#submit_domain', function(event) {
        event.preventDefault();

        $("#analysis_progression").css("display","block");
        var domain_name = $('#analyzing_domain_name').val().trim();
        $("#domain_name_show").html(domain_name);


        var reg = /^((?:(?:(?:\w[\.\-\+]?)*)\w)+)((?:(?:(?:\w[\.\-\+]?){0,62})\w)+)\.(\w{2,6})$/i;
        var output = reg.test(domain_name);
        if(output === false)
        {
          swal(global_lang_warning, Please_provide_a_domain_name_in_valid_format, 'warning');
          return;
        }

        if(domain_name == '') {
            swal(global_lang_warning, Domain_Name_is_Required, 'warning');
            return;

        } else {

            $("#domain_progress_bar_con div").css("width","3%");
            $("#domain_progress_bar_con div").attr("aria-valuenow","3");
            $("#domain_progress_bar_con div span").html("1%");
            $("#domain_progress_msg_text").html("");        
            $("#domain_progress_bar_con").show();       
            interval=setInterval(get_bulk_progress, 10000);

            $("#domain_success_msg").html('<img style="margin-top:20px;" class="center-block" src="'+loading_animations+'" width="150px" height="150px" alt="'+please_wait+'"><br/>');

            $("#completed_result_link").html('');

            $.ajax({
                type:'POST' ,
                url: ajax_domain_analysis_action,
                data:{domain_name:domain_name},
                success:function(response){
                    $("#domain_progress_bar_con div").css("width","100%");
                    $("#domain_progress_bar_con div").attr("aria-valuenow","100");
                    $("#domain_progress_bar_con div span").html("100%");
                    $("#domain_progress_msg_text").html(global_lang_completed);
                    $("#domain_success_msg").html('');
                    $("#completed_result_link").html(response);
                }
            }); 
        }
    });
</script>
  
@endsection