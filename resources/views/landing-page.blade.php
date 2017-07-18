<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem Informasi Manajemen Publikasi Paten HKI | Universitas Sumatera Utara">
    <meta name="keywords" content="sipustaha, sistem informasi manajamen publikasi paten hki, usu, universitas sumatera utara">
    <meta name="author" content="PSI USU">

    <title>SIMKERMA USU - Landing Page</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{url('assets/' . 'bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="{{url('/' . 'css/freelancer.min.css')}}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{url('assets/' . 'global/plugins/bower_components/fontawesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body id="page-top" class="index">

<!-- Navigation -->
<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="#page-top">SIMKERMA</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="hidden">
                    <a href="#page-top"></a>
                </li>
                <li class="page-scroll">
                    <a href="#about">Tentang SIMKERMA</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>

<!-- Header -->
<header>
    <div class="container" id="maincontent" tabindex="-1">
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive" src="img/logo-1.png" alt="">
                <div class="intro-text">
                    <h1 class="name">SIMKERMA</h1>
                    <hr class="star-light">
                    <span class="skills">Sistem Informasi Manajemen Kerja Sama</span>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- About Section -->
<section id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2>Tentang SIMKERMA</h2>
                <hr class="star-primary">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-lg-offset-2">
                <p>SIMKERMA merupakan sistem informasi manajeman yang dibuat untuk mengolah kerjasama-kerjasama yang dilakukan antara Universitas Sumatera Utara dengan Instansi Partner lainnya.</p>
            </div>
            <div class="col-lg-4">
                <p>Untuk dapat menggunakan SIMKERMA, user harus melakukan login terlebih dahulu. Silahkan klik tombol login di bawah untuk melakukan login.</p>
            </div>
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <a href="{{$login_link}}" class="btn btn-lg btn-primary btn-outline">
                    <i class="fa fa-sign-in"></i> Login
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="text-center">
    <div class="footer-below">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    Copyright &copy; PSI 2016
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
<div class="scroll-top page-scroll hidden-sm hidden-xs hidden-lg hidden-md">
    <a class="btn btn-primary" href="#page-top">
        <i class="fa fa-chevron-up"></i>
    </a>
</div>

<!-- jQuery -->
<script src="{{url('assets/' . 'global/plugins/bower_components/jquery/dist/jquery.min.js')}}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{url('assets/' . 'bootstrap/js/bootstrap.min.js')}}"></script>

<!-- Plugin JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

<!-- Contact Form JavaScript -->
{{--<script src="js/jqBootstrapValidation.js"></script>--}}
{{--<script src="js/contact_me.js"></script>--}}

<!-- Theme JavaScript -->
<script src="{{url('js/freelancer.min.js')}}"></script>

</body>

</html>
