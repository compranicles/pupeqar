<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') PUP eQAR</title>

        <link rel="icon" href="{{ url('favicon.ico') }}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css"/>
        <link rel="stylesheet" href="{{ asset('dist/markdown-toolbar.css') }}" type="text/css" />
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css" rel="stylesheet" />
        <link href="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css" rel="stylesheet" />
        <link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet" />
        <link href="{{ asset('lightbox2/dist/css/lightbox.css') }}" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css"> <!--added-->
        <link rel="stylesheet" href="{{ asset('dist/selectize.bootstrap4.css') }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->

        <script src="https://kit.fontawesome.com/b22b0c1d67.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>
        <script src="{{ asset('lightbox2/dist/js/lightbox.js') }}"></script>
        <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
         <!-- JavaScript Bundle with Popper -->
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

        <!-- Bootstrap Datepicker Resources -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
        <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css"/>

    </head>
    <body class="font-sans antialiased bg-light" style="background-image: {{ URL('storage/cover2.png') }}">
        <div id="loading">
            <div class="d-flex justify-content-center">
                <div class="spinner-border text-danger font-weight-bold page-spinner" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden"></span>
                </div>
            </div>
        </div>
        @include('navigation-menu')

        <!-- Page Heading -->
        @if(request()->routeIs('reports.*', 'chairperson.index', 'researcher.index', 'extensionist.index', 'director.index', 'sector.index', 'ipo.index', 'profile.*'))
        <!-- Page Heading -->
        <header class="d-flex py-3" style="background-color: #212529; border-color: #212529; color: whitesmoke;">
        <div class="container">
                <div>
                    {{ $header }}
                </div>
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main class="container my-5">
            <div class="row">
                <div class="col-md-12">
                    @if ($message = Session::get('success_switch'))
                        <div class="alert alert-success alert-index">
                            <i class="bi bi-check-circle"></i> {{ $message }}
                        </div>
                    @endif
                </div>
            </div>
                @if ($message = Session::get('error'))
                    <div class="modal fade" id="modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header text-light bg-danger">
                                    <h5 class="modal-title bold">Action Failed</h5>
                                    <button type="button" id="error-modal-button-1" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    {{ $message }}
                                </div>  
                            </div>
                        </div>
                    </div>
                @endif

                {{ $slot }}
        </main>

        @stack('modals')

        @stack('scripts')

        <!-- Messenger Chat Plugin Code -->
        <div id="fb-root"></div>

        <!-- Your Chat Plugin code -->
        <div id="fb-customer-chat" class="fb-customerchat">
        </div>

        <script>
        var chatbox = document.getElementById('fb-customer-chat');
        chatbox.setAttribute("page_id", "101461845962090");
        chatbox.setAttribute("attribution", "biz_inbox");
        </script>

        <!-- Your SDK code -->
        <script>
        window.fbAsyncInit = function() {
            FB.init({
            xfbml            : true,
            version          : 'v14.0'
            });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
        </script>
        <script>
            window.addEventListener('load', function () {
                $('#loading').fadeOut();
            });
        </script>
    </body>
</html>

<script>
    $(window).on('load',function(){
        const delayMs = 500; // delay in milliseconds
        setTimeout(function(){
            $('#modal').modal('show');
        }, delayMs);

        $("#error-modal-button-1").click(function() {
            $('#modal').modal('hide');
        }); 
        $("#error-modal-button-2").click(function() {
            $('#modal').modal('hide');
        });
    });
</script>
