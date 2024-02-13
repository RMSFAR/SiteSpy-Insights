{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Word Count'))
@section('content')



<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-plus-circle"></i> {{ __('Word Count') }}</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{route('utilities') }}">{{ __("Utilities")}}</a></div>
      <div class="breadcrumb-item">{{ __('Word Count') }}</div>
    </div>
  </div>
</section>
  

<div class="row multi_layout">

  <div class="col-12 col-md-6 col-lg-6 collef">
    <div class="card main_card">
      <div class="card-header">
        <h4><i class="fas fa-info-circle"></i> {{ __('Info')}}</h4>
      </div>
      <form enctype="multipart/form-data" method="POST"  id="new_search_form">
        @csrf
        <div class="card-body">

          <div class="form-group">
            <label class="form-label"> {{ __("Word Count")}} <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="{{ __("Word Count") }}" data-content='{{ __("put your text with contain 500 words(max)") }}'><i class='fa fa-info-circle'></i> </a></label>
            <textarea id="bulk_email" class="form-control" style="width:100%;min-height: 120px;" rows="10"  autofocus></textarea>
          </div>

      
        </div>

        <div class="card-footer bg-whitesmoke mt-66">

            <button type="button"  id="new_search_button" class="btn btn-primary "><i class="fa fa-search"></i> {{ __("Search")}}</button>
            <button class="btn btn-secondary btn-md float-right" onclick="goBack('menu_loader/utlities')" type="button"><i class="fa fa-remove"></i> {{ __('Cancel')}}</button>
          
        </div>

      </form>
    </div>          
  </div>

  <div class="col-12 col-md-6 col-lg-6 colmid">
    <div id="custom_spinner"></div>
    <div id="middle_column_content" style="background: #ffffff!important;">

      <div class="card">
        <div class="card-header">
          <h4> <i class="fas fa-plus-circle"></i> {{ __('Word Count Results')}}</h4>
          
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-6 col-lg-12 bck_clr" id="nodata">

        <div class="empty-state">
          <img class="img-fluid" src="{{asset("assets/img/drawkit/revenue-graph-colour.svg")}}" style="height: 300px" src=" " alt="image">
        </div>

      </div>
    </div>
  </div>
</div>

<script>     
  "use strict" 
  var Please_enter_your_contents = '{{ __('Please enter your contents') }}';
  var you_have_provided_a_blank_string = '{{ __('you have provided a blank string') }}';
  var You_have_entered_too_large_string = '{{ __('You have entered too large string') }}';
  var word_count_action = '{{route("word_count_action")}}'
</script>


<script>
  "use strict" 

$("document").ready(function(){

    $(document).on('click', '#new_search_button', function(event) {
      event.preventDefault();

        var emails=$("#bulk_email").val();
        
        if(emails==''){
          swal(global_lang_error, Please_enter_your_contents, 'error');
          return false;
        }

        $('#middle_column_content').html("");
        $("#new_search_button").addClass('btn-progress');
        $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');


        $.ajax({
          url:word_count_action,
          type:'POST',
          beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          },
          data:{emails:emails},
          success:function(response){ 
              $("#new_search_button").removeClass('btn-progress');
              $("#custom_spinner").html("");
             
            if(response == 2){

              swal(global_lang_error, your_bulk_limit_is_exceeded_for_this_module, 'error').then(function(){
                window.location = base_url+'payment/usage_history';
              });
              return false;

            }

            else if(response == 3){

              swal(global_lang_error, your_limit_is_exceeded_for_this_module, 'error').then(function(){
                window.location = base_url+'payment/usage_history';
              });
              return false;

            }

            else {
                
              var res = response.split("_sep_");
              $("#middle_column_content").html(res[0]);
              /*if(response>0)*/
               // $("#success_msg").html('<center><h3 class = "text-info">'+res[0]+'</h3></center>');

              /*if(response==0)
                $("#success_msg").html('<center><h3 class = "text-danger"> Match Not Found </h3></center>');*/

              if(res[0]=='size_error'){

                swal(global_lang_error, You_have_entered_too_large_string, 'error');
                return false;
              }

              if(res[0]=='blank_error'){
                swal(global_lang_error, you_have_provided_a_blank_string, 'error');
                return false;
              }
            }

            
          }
          
        });
        
    });

     var files_list = [];
      $("#file_upload_url").uploadFile({
        url:global_read_text_file,
        fileName:"myfile",
        maxFileSize:file_upload_limit*1024*1024,
        showPreview:false,
        returnType: "json",
        dragDrop: true,
        showDelete: true,
        multiple:true,
        maxFileCount:5,
        acceptFiles:".csv,.txt,.doc",
        deleteCallback: function (data, pd) {
            
              $.post(global_read_after_delete, {op: "delete",name: data.file_name},
                  function (resp,textStatus, jqXHR) {

                    var item_to_delete =data.content;
                    files_list = files_list.filter(item => item !== item_to_delete);
                    $("#bulk_email").val(files_list.join());

                  });

         },
         onSuccess:function(files,data,xhr,pd)
           {
               if (data.are_u_kidding_me =="yarki") {
               swal(global_lang_error, Something_went_wrong_please_choose_valid_file, 'error');
                return false;
               }

               $("#bulk_email").val(data.content);
               var data_modified = data.content;
               files_list.push(data_modified);
               $("#bulk_email").val(files_list.join());
                  
            
           }
    });

  });  
</script>

@endsection