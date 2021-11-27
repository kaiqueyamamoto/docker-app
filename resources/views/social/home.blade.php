<!--
=========================================================
* Argon Design System - v1.2.2
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-design-system
* Copyright 2020 Creative Tim (https://www.creative-tim.com)

Coded by www.creative-tim.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('social') }}/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ asset('social') }}/img/favicon.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('global.site_name','WhatsMenu') }}</title>
  
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
    <link href="{{ asset('social') }}/css/nucleo-icons.css" rel="stylesheet" />
    <link href="{{ asset('social') }}/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link href="{{ asset('social') }}/css/font-awesome.css" rel="stylesheet" />
    <link href="{{ asset('social') }}/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link href="{{ asset('social') }}/css/argon-design-system.css?v=1.2.3" rel="stylesheet" />
    <link href="{{ asset('social') }}/css/custom.css?v=1.2.3" rel="stylesheet" />

    <!-- Custom CSS defined by admin -->
    <link type="text/css" href="{{ asset('byadmin') }}/front.css" rel="stylesheet">

</head>

<body class="landing-page">
  
  <!-- Navbar -->
  @include('social.partials.nav')

  <!-- MAIN WRAPPER-->
  <div class="wrapper">

    <!-- HERO -->
    @include('social.partials.hero')

    <!-- FEATURES -->
    @include('social.partials.features')

    <!-- EXPLAIN -->
    @include('social.partials.explain')

    <!-- PRICING -->
    @include('social.partials.pricing')
    
    <!-- DEMO -->
    @include('social.partials.demo')

    <!-- Testimonials -->
    @include('social.partials.testimonials')
    
    <!-- FOOTER -->
    @include('social.partials.footer')

    <!-- MODALS -->
    @include('social.partials.modals')

  </div>
   <!-- END MAIN WRAPPER-->
   

  <!--   Core JS Files   -->
  <script src="{{ asset('social') }}/js/core/jquery.min.js" type="text/javascript"></script>
  <script src="{{ asset('social') }}/js/core/popper.min.js" type="text/javascript"></script>
  <script src="{{ asset('social') }}/js/core/bootstrap.min.js" type="text/javascript"></script>
  <script src="{{ asset('social') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="{{ asset('social') }}/js/argon-design-system.min.js?v=1.2.2" type="text/javascript"></script>

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
                    group: "whatsapp",
                    key: key,
                    language: locale,
                    value: value
                },
                success:function(response){
                    if(response){
                        var msg = {!! json_encode(__('whatsapp.success')) !!}

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

  @if (strlen(config('settings.futy_key'))>2)
  <script>
    var key='{{ config('settings.futy_key') }}';
    window.Futy = { key: key };
    (function (e, t) {
        var n = e.createElement(t);
        n.async = true;
        n.src = 'https://v1.widget.futy.io/js/futy-widget.js';
        var r = e.getElementsByTagName(t)[0];
        r.parentNode.insertBefore(n, r);
    })(document, 'script');
    </script>
  @endif


  
</body>

</html>