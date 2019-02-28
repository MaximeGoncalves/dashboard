<!doctype html>
<html lang="fr">
<head>
    <title>Support Softease</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--     Fonts and icons     -->
    <!-- Favicon -->
    <link href="/argon/img/brand/favicon.png" rel="icon" type="image/png">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <!-- Icons -->
    <link href="/argon/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="/argon/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!-- Argon CSS -->
    <link type="text/css" href="/argon/css/argon.min.css" rel="stylesheet">
    <link type="text/css" href="/css/app.css" rel="stylesheet">
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />--}}
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">--}}

</head>
<body>

<!-- Sidenav -->
<div id="app">

    <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
        <div class="container-fluid">
            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main"
                    aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Brand -->
            <router-link class="navbar-brand pt-0" to="/">
                <img src="/images/logo-sf.svg" class="navbar-brand-img" alt="..." width="75%">
            </router-link>
            <!-- User -->
            <ul class="nav align-items-center d-md-none">
                {{--<li class="nav-item dropdown">--}}
                    {{--<a class="nav-link nav-link-icon" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"--}}
                       {{--aria-expanded="false">--}}
                        {{--<i class="ni ni-bell-55"></i>--}}
                    {{--</a>--}}
                    {{--<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right"--}}
                         {{--aria-labelledby="navbar-default_dropdown_1">--}}
                        {{--<a class="dropdown-item" href="#">Action</a>--}}
                        {{--<a class="dropdown-item" href="#">Another action</a>--}}
                        {{--<div class="dropdown-divider"></div>--}}
                        {{--<a class="dropdown-item" href="#">Something else here</a>--}}
                    {{--</div>--}}
                {{--</li>--}}
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false">
                        <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                <img alt="Image placeholder" :src="user.photo">
              </span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                        <div class=" dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome!</h6>
                        </div>
                        <a href="./examples/profile.html" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>My profile</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#!" class="dropdown-item">
                            <i class="ni ni-user-run"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Collapse header -->
                <div class="navbar-collapse-header d-md-none">
                    <div class="row">
                        <div class="col-6 collapse-brand">
                            <a href="./index.html">
                                <img src="/argon/img/brand/blue.png">
                            </a>
                        </div>
                        <div class="col-6 collapse-close">
                            <button type="button" class="navbar-toggler" data-toggle="collapse"
                                    data-target="#sidenav-collapse-main" aria-controls="sidenav-main"
                                    aria-expanded="false" aria-label="Toggle sidenav">
                                <span></span>
                                <span></span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Form -->
                <form class="mt-4 mb-3 d-md-none">
                    <div class="input-group input-group-rounded input-group-merge">
                        <input type="search" class="form-control form-control-rounded form-control-prepended"
                               placeholder="Search" aria-label="Search">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fa fa-search"></span>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- Navigation -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/">
                            <i class="ni ni-tv-2 text-primary"></i> Dashboard
                        </router-link>
                    </li>
                </ul>
                <!-- Divider -->
                <hr class="my-3">
                <!-- Heading -->
                @can('isAdmin')
                    <h6 class="navbar-heading text-muted">Management</h6>
                    <ul class="navbar-nav">

                        <li class="nav-item">
                            <router-link class="nav-link" to="/societies">
                                <i class="fa fa-building text-red"></i> Sociétés
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/users">
                                <i class="fas fa-users text-blue"></i> Utilisateurs
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/developper">
                                <i class="ni ni-pin-3 text-orange"></i> Developpeur
                            </router-link>
                        </li>

                    </ul>
                @endcan()
                <hr class="my-3">
                <h6 class="navbar-heading text-muted">Software</h6>

                <ul class="navbar-nav">

                    <li class="nav-item">
                        <router-link class="nav-link" to="/tickets">
                            <i class="fas fa-file-alt text-primary"></i> Ticket Software
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="/nkeep">
                            <i class="fas fa-file-alt text-primary"></i> nkeep
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="/projects">
                            <i class="fas fa-project-diagram text-success"></i> Projects
                        </router-link>
                    </li>

                </ul>
                <!-- Divider -->
                <hr class="my-3">
                <!-- Heading -->
                @can('isAdmin')
                    <h6 class="navbar-heading text-muted">Documentation</h6>
                    <!-- Navigation -->
                    <ul class="navbar-nav mb-md-3">
                        <li class="nav-item">
                            <router-link class="nav-link" to="/knowledges">
                                <i class="ni ni-bullet-list-67 text-red"></i> Base de connaissance
                            </router-link>
                        </li>
                    </ul>
                @endcan()
            </div>
        </div>
    </nav>
    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <nav class="navbar navbar-top navbar-expand-md navbar-dark mb-5" id="navbar-main">
            <div class="container-fluid">
                <!-- Brand -->
                <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="./index.html">Dashboard</a>
                <!-- Form -->
                <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
                    <div class="form-group mb-0">
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input class="form-control" placeholder="Search" type="text">
                        </div>
                    </div>
                </form>
                <!-- User -->
                <ul class="navbar-nav align-items-center d-none d-md-flex">
                    <li class="nav-item dropdown">
                        <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">
                            <div class="media align-items-center">
                <span class="avatar avatar-sm rounded-circle">
                  <img alt="Image placeholder" :src="user.photo">
                </span>
                                <div class="media-body ml-2 d-none d-lg-block">
                                    <span
                                        class="mb-0 text-sm  font-weight-bold">@{{user.name}}</span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome!</h6>
                            </div>
                            <a href="./examples/profile.html" class="dropdown-item">
                                <i class="ni ni-single-02"></i>
                                <span>My profile</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item"
                               onclick="event.preventDefault(); document.getElementById('form-logout').submit()">
                                <i class="ni ni-user-run"></i>
                                <span>Logout</span>
                            </a>
                            <form action="{{route('logout')}}" method="post" class="d-none" id="form-logout">
                                @csrf
                                <button type="submit"></button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid">
            <router-view></router-view>
        </div>
        <vue-progress-bar></vue-progress-bar>
        <!-- Footer -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-xl-between">
                    <div class="col-xl-6">
                        <div class="copyright text-center text-xl-left text-muted">
                            &copy; 2019 <a href="https://softease.fr" class="font-weight-bold ml-1"
                                           target="_blank">Softease</a>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <ul class="nav nav-footer justify-content-center justify-content-xl-end">
                            <li class="nav-item">
                                <a href="https://www.softease.fr" class="nav-link" target="_blank">Softease</a>
                            </li>
                            <li class="nav-item">
                                <a href="https://www.softease.fr" class="nav-link" target="_blank">About
                                    Us</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

{{-- On defini la variable user (Javascript) si l'utilisateur est authentifié --}}
@auth()
    <script>
    window.user = @json(auth()->user())
    </script>
@endauth()

<script src="/js/app.js"></script>
<!-- Optional JS -->
<script src="/argon/vendor/chart.js/dist/Chart.min.js"></script>
<script src="/argon/vendor/chart.js/dist/Chart.extension.js"></script>
<script>
</script>
<!-- Argon JS -->
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>--}}
<script src="/argon/js/argon.min.js"></script>
</body>

</html>

