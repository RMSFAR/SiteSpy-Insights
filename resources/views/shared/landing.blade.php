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
    var upload_lang_download = '{{ __("Download") }}';
    var global_lang_procced = '{{ __("Proceed") }}';
    var global_lang_success = '{{ __("Success") }}';
    var global_lang_error = '{{ __("Error") }}';
    var global_lang_warning = '{{ __("Warning") }}';
    var global_lang_choose_data = '{{ __("Choose Date") }}';
    var global_lang_delete = '{{ __("Delete") }}';
    var global_read_text_file = '{{ route("read_text_file") }}';
    var global_read_after_delete = '{{ route("read_after_delete") }}';
    var global_lang_something_wrong = '{{ __("Something went wrong.") }}';
    var global_lang_confirmation = '{{ __("Are you sure?") }}';

    
    var file_upload_limit = {{ $file_upload_limit }};
    var datatable_lang_file = '{{ $datatable_lang_file }}';

    <?php if(check_is_mobile_view()) echo 'var areWeUsingScroll = false;';
    else echo 'var areWeUsingScroll = true;';?>
</script>