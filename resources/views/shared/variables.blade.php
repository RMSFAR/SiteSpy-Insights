<?php 
        if(config("my_config.xeroseo_file_upload_limit") != "") {
            $file_upload_limit = config("my_config.xeroseo_file_upload_limit");
        }
        else{
            $file_upload_limit = 4;
        }
        
?>


<?php
    if(!isset($is_admin)) $is_admin = '0';
    if(!isset($is_member)) $is_member = '0';

    $language = config('my_config.language');
    $language_exp = explode('-', $language);
    $language_code = $language_exp[0] ?? 'en';
    $datatable_lang_file_path = public_path('assets').DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'datatables'.DIRECTORY_SEPARATOR.'language'.DIRECTORY_SEPARATOR.$language_code.'.json';
    if(file_exists($datatable_lang_file_path))
    $datatable_lang_file = asset('assets/modules/datatables/language/'.$language_code.'.json');
    else $datatable_lang_file = asset('assets/modules/datatables/language/english.json');
?>


<script>

    var base_url = '{{url('/')}}';
    var site_url = base_url;
    var csrf_token = '{{ csrf_token() }}';

    var upload_lang_drag_drop_files = '{{ __("Drag drop files") }}';
    var upload_lang_upload = '{{ __("Upload") }}';
    var upload_lang_abort = '{{ __("Abort") }}';
    var upload_lang_cancel = '{{ __("Cancel") }}';
    var upload_lang_delete = '{{ __("Delete") }}';
    var upload_lang_done = '{{ __("Done") }}';
    var upload_lang_multiple_file_drag_drop_is_not_allowed = '{{ __("Multiple File Drag & Drop is not allowed.") }}';
    var upload_lang_is_not_allowed_allowed_extensions = '{{ __("is not allowed. Allowed extensions:") }}';
    var upload_lang_is_not_allowed_file_already_exists = '{{ __("is not allowed. File already exists.") }}';
    var upload_lang_is_not_allowed_allowed_max_size = '{{ __("is not allowed. Allowed Max size: ") }}';
    var upload_lang_is_not_allowed_maximum_allowed_files_are = '{{ __("is not allowed. Maximum allowed files are:") }}';
    var upload_lang_upload_is_not_allowed = '{{ __("Upload is not allowed") }}';
    var upload_lang_download = '{{ __("Download") }}';
    var global_lang_procced = '{{ __("Proceed") }}';
    var global_lang_success = '{{ __("Success") }}';
    var global_lang_error = '{{ __("Error") }}';
    var global_lang_completed = '{{ __("completed") }}';
    var global_lang_warning = '{{ __("Warning") }}';
    var global_lang_choose_data = '{{ __("Choose Date") }}';

    var global_lang_delete = '{{ __("Delete") }}';
    var You_have_to_select_list_from_data_table = '{{ __("You have to select list from data table") }}';
    var You_have_to_provide_a_domain_name = '{{ __("You have to provide a domain name.") }}';
    var global_lang_last_30_days = '{{ __("Last 30 Days") }}';
    var global_lang_this_month = '{{ __("This Month") }}';
    var global_lang_cancel = '{{ __("Cancel") }}';
    var global_lang_apply = '{{ __("Apply") }}';
    var global_lang_from = '{{ __("From") }}';
    var global_lang_to = '{{ __("To") }}';
    var globaloptions = '{{ __("Options") }}';


    var global_read_text_file = '{{ route("read_text_file") }}';
    var global_read_after_delete = '{{ route("read_after_delete") }}';
    var visior_domain_session = '{{ route("visior-domain-session") }}';

    var global_lang_custom = '{{ __("Custom") }}';
    var global_You_have_to_select_users_to_send_email = '{{ __("You have to select users to send email.") }}';
   
    var global_lang_See_Report = '{{ __("See Report") }}';
    var global_lang_last_month = '{{ __("Last Month") }}';
    var global_lang_something_wrong = '{{ __("Something went wrong.") }}';
    var global_lang_confirmation = '{{ __("Are you sure?") }}';
    var global_all_fields_are_required = '{{ __("All fields are required.") }}';
    var global_all_files_required = '{{ __("All Fields are required, please fill all required fields.") }}';
    var your_limit_is_exceeded_for_this_module = '{{ __("Sorry, your limit is exceeded for this module. See your usage log") }}';
    var your_bulk_limit_is_exceeded_for_this_module = '{{ __("Sorry, your bulk limit is exceeded for this module. See your usage log") }}';


    var global_Please_enter_url = '{{ __("Please enter url") }}';
    var Please_wait_for_while = '{{ __("Please wait for while...")}}';
    var Please_enter_your_domain_name_first = '{{ __("Please enter domain name first") }}';
    var Something_went_wrong_please_choose_valid_file = '{{ __("Something went wrong, please choose valid file") }}';
    var Something_went_wrong_please_try_once_again = "{{ __('Something went wrong, please try once again.') }}";
    
    var Doyouwanttodeletealltheserecordsfromdatabase = "{{ __('Do you want to detete all the records from the database?') }}";
    var Doyouwanttodeletethisrecordfromdatabase = "{{ __('Do you want to detete this record from the database?') }}";
    
    var file_upload_limit = {{ $file_upload_limit }};
    var datatable_lang_file = '{{ $datatable_lang_file }}';

    <?php if(check_is_mobile_view()) echo 'var areWeUsingScroll = false;';
    else echo 'var areWeUsingScroll = true;';?>
</script>