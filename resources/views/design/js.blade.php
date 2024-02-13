

<script src="{{asset('assets/modules/jquery.min.js')}}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/iziToast.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>

<!-- Template JS File -->
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<script src="{{ asset('assets/js/custom/custom.js') }}"></script>





<script src="{{asset('assets/modules/moment.min.js')}}"></script>
<script src="{{asset('assets/modules/chart.min.js')}}"></script>
<script src="{{asset('assets/modules/owlcarousel2/dist/owl.carousel.min.js')}}"></script>

<!-- General JS Scripts -->
<!-- <script src="{{asset('assets/modules/jquery.min.js"></')}}script> -->
<script src="{{asset('assets/modules/popper.js')}}"></script>
<script src="{{asset('assets/modules/tooltip.js')}}"></script>
<script src="{{asset('assets/modules/nicescroll/jquery.nicescroll.min.js')}}"></script>

<script src="{{asset('assets/js/stisla.js')}}"></script>


<!-- JS Libraies -->

<script src="{{asset('assets/modules/jquery.sparkline.min.js')}}"></script>
<script src="{{asset('assets/modules/summernote/summernote-bs4.js')}}"></script>
<script src="{{asset('assets/modules/chocolat/dist/js/jquery.chocolat.min.js')}}"></script>

<script src="{{asset('assets/modules/simple-weather/jquery.simpleWeather.min.js')}}"></script>

<script src="{{asset('assets/modules/jqvmap/dist/jquery.vmap.min.js')}}"></script>
<script src="{{asset('assets/modules/jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>


<script src="{{asset('assets/modules/jquery-ui/jquery-ui.min.js')}}"></script>

<script src="{{asset('assets/js/clipboard.min.js')}}"></script>
<script src="{{asset('assets/modules/prism/prism.js')}}"></script>

<script src="{{asset('assets/modules/sticky-kit.js')}}"></script>



<script src="{{asset('assets/modules/dropzonejs/min/dropzone.min.js')}}"></script>


<script src="{{asset('assets/modules/jqvmap/dist/maps/jquery.vmap.indonesia.js')}}"></script>



<script src="{{asset('assets/modules/cleave-js/dist/cleave.min.js')}}"></script>
<script src="{{asset('assets/modules/cleave-js/dist/addons/cleave-phone.us.js')}}"></script>
<script src="{{asset('assets/modules/jquery-pwstrength/jquery.pwstrength.min.js')}}"></script>
<script src="{{asset('assets/modules/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('assets/modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('assets/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}"></script>
<script src="{{asset('assets/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
<script src="{{asset('assets/modules/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/modules/jquery-selectric/jquery.selectric.min.js')}}"></script>



<script src="{{asset('assets/modules/codemirror/lib/codemirror.js')}}"></script>
<script src="{{asset('assets/modules/codemirror/mode/javascript/javascript.js')}}"></script>


<script src="{{asset('assets/modules/gmaps.js')}}"></script>

<script src="{{asset('assets/modules/fullcalendar/fullcalendar.min.js')}}"></script>



<script src="{{asset('assets/modules/datatables/datatables.js')}}"></script>
<script src="{{asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js')}}"></script>



<script src="{{asset('assets/modules/sweetalert/sweetalert.min.js')}}"></script>






<script src="{{asset('assets/modules/upload-preview/assets/js/jquery.uploadPreview.min.js')}}"></script>




<!-- js for ajax multiselect [zilani 02-07-2019] -->
<link rel="stylesheet" href="{{asset('assets/plugins/multiselect_tokenize/jquery.tokenize.css')}}" type="text/css" />
<script src="{{asset('assets/plugins/multiselect_tokenize/jquery.tokenize.js')}}" type="text/javascript"></script>

<!-- Scrollbar -->
<script src="{{asset('assets/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js')}}" type="text/javascript"></script>

<!-- Slimscroll -->
<script src="{{asset('assets/plugins/perfect-scrollbar-1.4.0/dist/perfect-scrollbar.js')}}"></script>

<!-- Alerfify https://alertifyjs.com/guide.html -->
<script src="{{asset('assets/modules/alertifyjs/alertify.min.js')}}"></script>

<!--Jquery Date Time Picker  https://github.com/xdan/datetimepicker-->
<script type="text/javascript" src="{{asset('assets/plugins/datetimepickerjquery/jquery.datetimepicker.js')}}"></script>
<link href="{{asset('assets/plugins/upload/uploadfile.css')}}" rel="stylesheet">
<script src="{{asset('assets/plugins/upload/jquery.uploadfile.min.js')}}"></script>

<!-- Emoji Library-->
<script src="{{asset('assets/plugins/emoji/dist/emojionearea.js')}}" type="text/javascript"></script>

@include('shared.variables')


<!-- Custom Universal JS -->
<script>

    // $("document").ready(function(){
    //   $('.modal').on('shown.bs.modal', function () { 
    //     $(this).find('.table-responsive').attr('style','overflow','scroll !important;');
    //   });
    // });

    function goBack(link,insert_or_update,add_base_url) //used to go back to list as crud
    {
    
    // insert_or_update does not have any effect from v6.0
    if (typeof(insert_or_update)==='undefined') insert_or_update = 0;
    if (typeof(add_base_url)==='undefined') add_base_url = 1;

    var mes='';
    mes="Your data may not be saved.";
    swal({
        title: "Do you want to go back?",
        text: mes,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => 
    {
        if (willDelete) 
        {
        if(add_base_url==1)
        link=base_url+'/'+link;
        window.location.assign(link);
        } 
    });
    }

    $(document).ready(function() {
    
    $('[data-toggle="popover"]').popover(); 
    $('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});


    $(document).on('click','.are_you_sure',function(e){
        e.preventDefault();
        var link = $(this).attr("href");
        var mes='Do you really want to delete it?';  
        swal({
        title: "Are you sure?",
        text: mes,
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => 
        {
        if (willDelete) 
        {
            window.location.href = link;
        } 
        });
    });

    $(document).on('click','.are_you_sure_datatable',function(e){
        e.preventDefault();
        var link = $(this).attr("href");
        var refresh = $(this).attr("data-refresh");
        var csrf_token = $(this).attr('csrf_token');
        if (typeof(csrf_token)==='undefined') csrf_token = '';
        var mes='Do you really want to delete it?';  
        swal({
        title: "Are you sure?",
        text: mes,
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => 
        {
        if (willDelete) 
        {
            $(this).addClass('btn-progress btn-danger').removeClass('btn-outline-danger');
            $.ajax({
                context: this,
                url: link,
                type: 'POST',
                dataType: 'JSON',
                data: {csrf_token:csrf_token},
                success:function(response)
                {
                    $(this).removeClass('btn-progress btn-danger').addClass('btn-outline-danger');
                    if(response.status == 1)  
                    {
                    iziToast.success({title: '',message: response.message,position: 'bottomRight'});
                    if(refresh!='0')
                    {
                        if($(this).hasClass('non_ajax')) $(this).parent().parent().hide();
                        else $('#mytable').DataTable().ajax.reload();
                    }
                    }
                    else iziToast.error({title: '',message: response.message,position: 'bottomRight'});
                }
            });
        } 
        });
    });

    $(".account_switch").click(function(e){
        e.preventDefault();
        var id=$(this).attr('data-id');
        $.ajax({
        url: base_url + '/social_accounts/fb_rx_account_switch',
        type: 'POST',
        data: {id:id},
        success:function(response){
            location.reload(); 
        }
        })
        
    });

    $(".language_switch").click(function(e){3
        e.preventDefault();
        var language=$(this).attr('data-id');
        $.ajax({
        url: base_url + '/home/language_changer',
        type: 'POST',
        data: {language:language},
        success:function(response){
            location.reload(); 
        }
        });      
    });

    $("#datatableSelectAllRows").change(function(){
        if ($(this).is(':checked')) 
        $(".datatableCheckboxRow").prop("checked",true);
        else
        $(".datatableCheckboxRow").prop("checked",false);
    });
    });
</script>


<!-- scrollbar -->
<!-- theme:"rounded-dark",
theme: "dark", "light",
theme: "light-2", "dark-2",
theme: "light-thick", "dark-thick",
theme: "light-thin", "dark-thin",
theme "rounded", "rounded-dark", "rounded-dots", "rounded-dots-dark",
theme "3d", "3d-dark", "3d-thick", "3d-thick-dark",
theme: "minimal", "minimal-dark",
theme "light-3", "dark-3",
theme "inset", "inset-dark", "inset-2", "inset-2-dark", "inset-3", "inset-3-dark", -->
<script>
    //iframe auto higth
    function resizeIframe(obj) {

    setTimeout(function(){
        var cacl_height = obj.contentWindow.document.body.scrollHeight;
        if(parseFloat(cacl_height)<800) cacl_height = '800';
        obj.style.height =  cacl_height + 'px';
    }, 3000);


    $(obj).contents().on("mousedown, mouseup, click", function(){
        setTimeout(function(){
            var cacl_height2 = obj.contentWindow.document.body.scrollHeight;
            if(parseFloat(cacl_height2)<800) cacl_height2 = '800';
            obj.style.height = cacl_height2 + 'px';
        }, 500);
    });
    }

    $(document).ready(function() { 
    
        $(".xscroll").mCustomScrollbar({
        autoHideScrollbar:true,
        theme:"rounded-dark",
        axis: "x"
        });
        $(".yscroll").mCustomScrollbar({
        autoHideScrollbar:true,
        theme:"rounded-dark"
        });
        $(".xyscroll").mCustomScrollbar({
        autoHideScrollbar:true,
        theme:"rounded-dark",
        axis:"yx"
        });

        $("div:not(.data-card) > .table-responsive").niceScroll();      

        $(".nicescroll,.makeNiceScroll").niceScroll();
        $(".makeNiceScroll").niceScroll();
        $(".makeScroll,.video-widget-info,.account_list").mCustomScrollbar({
        autoHideScrollbar:true,
        theme:"rounded-dark"
        });
    });

</script>



<script>
    let loggedInUser =@json(\Illuminate\Support\Facades\Auth::user());
    let loginUrl = '{{ route('login') }}';
    // Loading button plugin (removed from BS4)
    (function ($) {
        $.fn.button = function (action) {
            if (action === 'loading' && this.data('loading-text')) {
                this.data('original-text', this.html()).html(this.data('loading-text')).prop('disabled', true);
            }
            if (action === 'reset' && this.data('original-text')) {
                this.html(this.data('original-text')).prop('disabled', false);
            }
        };
    }(jQuery));
</script>

