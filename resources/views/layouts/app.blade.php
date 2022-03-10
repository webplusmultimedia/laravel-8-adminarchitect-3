<!DOCTYPE html>

<html lang="en-US">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Webplus Multimédia">
    <meta name="description" content="@yield('description')">
    <meta property="og:site_name" content="{{ options_find('nom_site') }}">
    <meta property="og:title" content="@yield('title')" />
    <meta content="{{ asset('assets/img/ico.png') }}" itemprop="image">
    <meta content="{{ asset('assets/img/ico.png') }}" property="og:image:secure_url">
    <meta property="og:type" content="website" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/ico.png') }}">

    <link href="{{ asset('assets/fonts/font-awesome.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/fonts/elegant-fonts.css')}}" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="{{ mix("assets/css/app.css")}}">

    @yield('lcss')
    <link rel="stylesheet" type="text/css" href="{{ mix("assets/css/style.css")}}">


    <title>@yield('title')</title>

</head>

<body class="homepage">
<div class="overlay"></div>

<div class="outer-wrapper">
    <div class="page-wrapper">

        <header class="navigation" id="top">
            <div class="container">
                <div class="secondary-nav">
                    <span><a href="mailto:{{ options_find('email') }}"><i class="icon_mail"></i>{{ options_find('email') }}</a></span>
                    <span><i class="icon_phone"></i>{{ options_find('telephone') }}</span>
                </div>
                <!--/.secondary-nav-->
                <div class="main-nav">
                    <div class="brand"><a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo luli coaching.png') }}" alt="luli coaching Martinique, Guadeloupe"></a></div>
                    <nav class="">
                        <ul>
                            <li class="active"><a href="{{ route('home') }}">Accueil</a></li>
                            <li><a href="change-your-life.html">Qui suis-je</a></li>
                            <li><a href="about.html">Mes Prestations</a></li>
                            <li><a href="blog.html">Blog</a></li>
                            <li><a href="meet-me.html">Contact</a></li>
                        </ul>
                        <div class="nav-toggle"><i class="icon_menu"></i></div>
                    </nav>
                    {{--<a href="#make-an-appointment" class="icon-shortcut"><i class="icon_calendar" title="Make an Appointment"></i></a>--}}<!--/.icon-->
                </div>
                <!--/.main-nav-->
            </div>
            <!--/.container-->
        </header>
        <!--/.header-->

       @yield('content')

        <footer id="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-sm-8">
                        <div class="left">
                            <span>(C) 2015 Jane Doe. All Right Reserved</span>
                            <div class="bg-left"></div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="right">
                            <span><a href="#top" class="scroll">To Top<i class="arrow_carrot-up_alt2"></i></a></span>
                            <div class="bg-right"><img src="{{ asset('assets/img/footer-bg.png') }}" alt=""></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ .container-->
        </footer>

    </div>
</div>


<script type="text/javascript" src="{{ asset('/assets/js/app.js') }}"></script>
@yield('ljs')
<script type="text/javascript" src="{{ asset('/assets/js/custom.js') }}"></script>




</body>
