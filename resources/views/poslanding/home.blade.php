<!--
=========================================================
* Soft UI Design System - v1.0.3
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-design-system
* Copyright 2021 Creative Tim (https://www.creative-tim.com)

Coded by www.creative-tim.com

 =========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('soft') }}/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ asset('soft') }}/img/favicon.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('global.site_name','POS Cloud') }}</title>
  
    <!-- Global site tag (gtag.js) - Google Analytics -->
    @if (config('settings.google_analytics'))
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo config('settings.google_analytics'); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '<?php echo config('settings.google_analytics'); ?>');
        </script>
    @endif

    @yield('head')
    @laravelPWA

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <!-- Nucleo Icons -->
    <link href="{{ asset('soft') }}/css/nucleo-icons.css" rel="stylesheet" />
    <link href="{{ asset('soft') }}/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link href="{{ asset('argonfront') }}/css/font-awesome.css" rel="stylesheet" />
    <link href="{{ asset('argonfront') }}/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link href="{{ asset('soft') }}/css/soft-ui-dashboard.css?v=1.2.3" rel="stylesheet" />
    <link href="{{ asset('soft') }}/css/custom.css?v=1.2.3" rel="stylesheet" />

    <!-- Custom CSS defined by admin -->
    <link type="text/css" href="{{ asset('byadmin') }}/front.css" rel="stylesheet">

</head>

<body class="landing-page">
  
  <!-- Navbar -->
  @include('poslanding.partials.nav')

  <!-- MAIN WRAPPER-->
  <div class="wrapper">

     <!-- HERO -->
     @include('poslanding.partials.hero')

      <!-- EXPLAIN -->
    @include('poslanding.partials.explain')

      <!-- FEATURES -->
    @include('poslanding.partials.features')

     <!-- PRICING -->
     @include('poslanding.partials.pricing')

     <!-- Testimonials -->
    @include('poslanding.partials.testimonials')

     <!-- FOOTER -->
     @include('poslanding.partials.footer')
     <br/>

    <!-- MODALS -->
    @include('poslanding.partials.modals')
  

  </div>
   <!-- END MAIN WRAPPER-->
   

  <!--   Core JS Files   -->
  <script src="{{ asset('argonfront') }}/js/core/jquery.min.js" type="text/javascript"></script>
  <script src="{{ asset('soft') }}/js/core/popper.min.js" type="text/javascript"></script>
  <script src="{{ asset('soft') }}/js/core/bootstrap.min.js" type="text/javascript"></script>
  <script src="{{ asset('argonfront') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="{{ asset('soft') }}/js/soft-ui-dashboard.js?v=1.2.2" type="text/javascript"></script>

   <!-- All in one -->
   <script src="{{ asset('custom') }}/js/js.js?id={{ config('config.version')}}s"></script>

    <!-- Custom JS defined by admin -->
    <?php echo file_get_contents(base_path('public/byadmin/front.js')) ?>

  <!-- Notify JS -->
  <script src="{{ asset('custom') }}/js/notify.min.js"></script>

  <!-- CKEditor -->
  <script src="{{ asset('ckeditor') }}/ckeditor.js"></script>
  <script>
      var USER_ID = '{{  auth()->user()&&auth()->user()?auth()->user()->id:"" }}';
  </script>

  <script>
    window.onload = function () {

    showModalPostRegister();

    $('#termsCheckBox').click(function () {
        $('#submitRegister').prop("disabled", !$("#termsCheckBox").prop("checked")); 
    })
    var ifUser = {!! json_encode( auth()->user() && auth()->user()->hasRole('admin') ? true : false) !!};

    $('<i class="fas fa-edit mr-2 text-primary ckedit_btn" type="button" style="display: none;"></i>').insertBefore(".ckedit");
    if(ifUser){
        initializeCKEditor();

        changeContentEditable(true);

        showEditBtns();
        //$(".ckedit_edit").show();

    }else{
        changeContentEditable(false);

        //$(".ckedit_edit").hide();
    }

    CKEDITOR.on('instanceReady', function(event) {
        var editor = event.editor;

        editor.on('blur', function(e) {
            //console.log(editor.getSnapshot());
            var html=editor.getSnapshot()
            var dom=document.createElement("DIV");
            dom.innerHTML=html;
            var plain_text=(dom.textContent || dom.innerText);

            var APP_URL = {!! json_encode(url('/')) !!}

            var locale = {!! json_encode(Config::get('app.locale')) !!}

            changeContent(APP_URL, locale, editor.name, plain_text)
        });
    });

    function showEditBtns(){
        $('.ckedit_btn').each(function(i, obj) {
            $(this).show();
        });
    }

    function initializeCKEditor(){
        var elements = CKEDITOR.document.find('.ckedit'),
        i = 0,
        element;

        while ( ( element = elements.getItem( i++ ) ) ) {
            //CKEDITOR.inline(element);
            CKEDITOR.inline(element, {
                removePlugins: 'link, image',
                removeButtons: 'Bold,Italic,Underline,Strike,Subscript,Superscript,RemoveFormat,Scayt,SpecialChar,About,Styles,Cut,Copy,Undo,Redo,Outdent,Indent,Table,HorizontalRule,NumberedList,BulletedList,Blockquote,Format'
            } );
        }
    }

    $(".ckedit_btn").click(function() {
        var next = $(this).next().attr('key');

        var editor = CKEDITOR.instances[next];
        editor.focus();
    });

    function changeContentEditable(bool){
        $('.ckedit').each(function(i, obj) {
            $(this).attr("contenteditable",bool);
        });
    }

    function notify(text, type){
        $.notify.addStyle('custom', {
            html: "<div><strong><span data-notify-text /></strong></div>",
            classes: {
                base: {
                    "pos ition": "relative",
                    "margin-bottom": "1rem",
                    "padding": "1rem 1.5rem",
                    "border": "1px solid transparent",
                    "border-radius": ".375rem",

                    "color": "#fff",
                    "border-color": type == "success" ? "#4fd69c" : "#fc7c5f",
                    "background-color": type == "success" ? "#4fd69c" : "#fc7c5f",
                },
                success: {
                    "color": "#fff",
                    "border-color": type == "success" ? "#4fd69c" : "#fc7c5f",
                    "background-color": type == "success" ? "#4fd69c" : "#fc7c5f",
                }
            }
            });

            $.notify(text,{
                position: "bottom right",
                style: 'custom',
                className: 'success',
                autoHideDelay: 5000,
            }
        );
    }

    function changeContent(APP_URL, locale, key, value){
        var isDemo={!! env('is_demo',false)|env('IS_DEMO',false) !!};
        if(!isDemo){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type:'POST',
                url: APP_URL+'/admin/languages/'+locale,
                dataType: 'json',
                data: {
                    group: "poslanding",
                    key: key,
                    language: locale,
                    value: value
                },
                success:function(response){
                    if(response){
                        var msg = {!! json_encode(__('poslanding.success')) !!}

                        notify(msg, "success");
                    }
                }, error: function (response) {
                //alert(response.responseJSON.errMsg);
            }
        })

        }else{
          //ok
          notify("Changing strings not allowed in demo mode.", "warning");
        }
    }
    }
  </script>
</body>

</html>