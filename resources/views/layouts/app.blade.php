<?php session_start(); ?>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IHRDC') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=EB+Garamond" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.6/highcharts.js" charset="utf-8"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div id="app">
        <!-- Bootstrap NavBar -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm @guest no-display @endguest">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/poc-logo-main.png') }}"/>
            </a>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav><!-- NavBar END -->
        <!-- Bootstrap row -->
        @guest
            @yield('content')
        @else
        <div class="row" id="body-row">
            <!-- Sidebar -->
            <div id="sidebar-container" class="sidebar-collapsed d-none d-md-block">
                <!-- d-* hiddens the Sidebar in smaller devices. Its itens can be kept on the Navbar 'Menu' -->
                <!-- Bootstrap List Group -->
                <ul class="list-group">
                    @hasanyrole('general_director')
                    <a href="https://employer.jobtest.vn/vi/dashboard" class="bg-dark list-group-item
                    list-group-item-action" target="_blank">
                        <div class="d-flex w-100 justify-content-start align-items-center">
                            <span class="fa fa-external-link fa-fw mr-3"></span>
                            <span class="menu-collapsed d-none">{{ __('Pre-Test') }}</span>
                        </div>
                    </a>
                    @endhasanyrole
                    <a href="https://cms.phuquocpoc.vn/" class="bg-dark list-group-item list-group-item-action" target="_blank">
                        <div class="d-flex w-100 justify-content-start align-items-center">
                            <span class="fa fa-cog fa-fw mr-3"></span>
                            <span class="menu-collapsed d-none">{{ __('CMS') }}</span>
                        </div>
                    </a>
                    <!-- Menu with submenu -->
                    <a href="#submenu1" data-toggle="collapse" aria-expanded="true" class="bg-dark list-group-item
                    list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-start align-items-center">
                            <span class="fa fa-dashboard fa-fw mr-3"></span>
                            <span class="menu-collapsed d-none">{{ __('Performance Management') }}</span>
                            <span class="submenu-icon ml-auto d-none"></span>
                        </div>
                    </a>
                    <!-- Submenu content -->
                    <div id='submenu1' class="collapse sidebar-submenu d-none">
                        <a href="{{ route('performaceManagementDashboard') }}" class="list-group-item
                        list-group-item-action bg-dark text-white lvl-1">
                            <span class="menu-collapsed d-none">{{ __('Dashboard')  }}</span>
                        </a>
                        @hasanyrole('employees|super-admin')
                        <a href="{{ URL::to('pdf/guidelines_MSC.pdf') }}" class="list-group-item
                        list-group-item-action bg-dark text-white lvl-1 d-none" target="_blank">
                            <span class="menu-collapsed d-none">{{ __('Guideline for MSC Performance Management System ')  }}</span>
                        </a>
                        @endhasanyrole
                        <li class="list-group-item sidebar-separator-content d-flex align-items-center
                        menu-collapsed text-white d-none">
                            @hasanyrole('general_director')
                                {{ __('Employees MSC') }}
                            @else
                                {{ __('My MSC') }}
                            @endhasanyrole
                        </li>
                        <a href="{{ route('BMAMO',Auth::user()->id) }}" class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed d-none">{{ __('Annual MSC') }}</span>
                        </a>
                        <a href="{{ route('BMMMO',Auth::user()->id) }}" class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed d-none">{{ __('Monthly MSC') }}</span>
                        </a>
                        <li class="list-group-item sidebar-separator-content d-flex align-items-center
                        menu-collapsed text-white d-none">
                            @hasanyrole('general_director')
                                {{ __('Employees Rating') }}
                            @else
                                {{ __('My Rating') }}
                            @endhasanyrole
                        </li>
                        <a href="{{ route('RMMP',Auth::user()->id) }}" class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed d-none">{{ __('Monthly Rating') }}</span>
                        </a>
                        <a href="{{ route('RMAP',Auth::user()->id) }}" class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed d-none">{{ __('Annual Rating') }}</span>
                        </a>
                        @hasanyrole('department_managers|director|super-admin')
                        <li class="list-group-item sidebar-separator-content d-flex align-items-center
                        menu-collapsed text-white d-none">
                            {{ __('Employees MSC') }}
                        </li>
                        <a href="{{ route('AMEAMO',Auth::user()->id) }}" class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed d-none">{{ __('Annual MSC') }}</span>
                        </a>
                        <a href="{{ route('AMEMMO',Auth::user()->id) }}" class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed d-none">{{ __('Monthly MSC') }}</span>
                        </a>
                        <li class="list-group-item sidebar-separator-content d-flex align-items-center
                        menu-collapsed text-white d-none">
                            {{ __('Employees Rating') }}
                        </li>
                        <a href="{{ route('AMEMP',Auth::user()->id) }}" class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed d-none">{{ __('Monthly Rating') }}</span>
                        </a>
                        <a href="{{ route('AMEAP',Auth::user()->id) }}" class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed d-none">{{ __('Annual Rating') }}</span>
                        </a>
                        @endhasanyrole
                        @hasanyrole('supervisors|department_managers|director|general_director|super-admin')
                        <a href="{{route('performaceManagement',Auth::user()->id)}}" class="list-group-item
                        list-group-item-action bg-dark text-white lvl-1">
                            <span class="menu-collapsed d-none">{{ __('Performance Reports') }}</span>
                        </a>
                        @endhasanyrole
                    </div>
                    <a href="#top" data-toggle="sidebar-colapse" class="bg-dark list-group-item list-group-item-action d-flex align-items-center">
                        <div class="d-flex w-100 justify-content-start align-items-center">
                            <span id="collapse-icon" class="fa fa-2x mr-3"></span>
                            <span id="collapse-text" class="menu-collapsed d-none">Collapse</span>
                        </div>
                    </a>
                </ul><!-- List Group END-->
            </div><!-- sidebar-container END -->
            <!-- MAIN -->
            <div class="col p-4 main-content main-content-expanded">
                @yield('content')
            </div>
        </div><!-- body-row END -->
        @endguest
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm no-display">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/poc-logo.png') }}"/>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @guest
                            <!-- Left Side Of Navbar -->
                        @else
                            @hasanyrole('general_director|super-admin')
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ __('Pre-Test Management System') }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarTrainningManagement">
                                    <div class="dropright dropdown-item submenu">
                                        <a id="nav-approve-training" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ __('Candidate Page') }}
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="approve-training-content">
                                            <a class="dropdown-item" href="#">
                                                {{ __('Test Invitation Email') }}
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                {{ __('Input Personal Info') }}
                                            </a>
                                            <div class="dropright dropdown-item submenu">
                                                <a id="nav-approve-training" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ __('Test Selection') }}
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="approve-training-content">
                                                    <a class="dropdown-item" href="#"onclick="">{{ __('Personality Test') }}</a>
                                                    <a class="dropdown-item" href="#"onclick="">{{ __('IQ/Aptitude Test') }}</a>
                                                </div>
                                            </div>
                                            <div class="dropright dropdown-item submenu">
                                                <a id="nav-approve-training" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ __('Test Results') }}
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="approve-training-content">
                                                    <a class="dropdown-item" href="#"onclick="">{{ __('Personality Test Results') }}</a>
                                                    <a class="dropdown-item" href="#"onclick="">{{ __('IQ/Aptitude Test Results') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropright dropdown-item submenu">
                                        <a id="nav-approve-training" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ __('Recruiter Page') }}
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="approve-training-content">
                                            <a class="dropdown-item" href="#">
                                                {{ __('Candidate List') }}
                                            </a>
                                            <div class="dropright dropdown-item submenu">
                                                <a id="nav-approve-training" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ __('Test Results Selection') }}
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="approve-training-content">
                                                    <a class="dropdown-item" href="#"onclick="">{{ __('Test Results Selectioni by Candidate') }}</a>
                                                    <a class="dropdown-item" href="#"onclick="">{{ __('Test Results Selection by Job Titles') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropright dropdown-item submenu">
                                        <a id="nav-approve-training" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ __('Admin Page') }}
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="approve-training-content">
                                            <a class="dropdown-item" href="#">
                                                {{ __('Add the Questions') }}
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                {{ __('Delete the Questions') }}
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                {{ __('Edit the Questions') }}
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                {{ __('Edit the Tests') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endhasanyrole

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ __('Perfomance Management') }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarTrainningManagement">
                                    @hasanyrole('employees|super-admin')
                                    <a class="dropdown-item" href="{{ URL::to('pdf/guidelines_MSC.pdf') }}"
                                       onclick="" target="_blank">
                                        {{ __('Guideline for MSC Performance Management System ')  }}
                                    </a>
                                    @endhasanyrole
                                    <div class="dropright dropdown-item submenu">
                                        <a id="nav-approve-training" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            @hasanyrole('general_director')
                                            {{ __('Company Performance') }}
                                            @else
                                                {{ __('My Performance') }}
                                            @endif
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="approve-training-content">
                                            @hasanyrole('employees|department_managers|general_director|super-admin')
                                            <div class="dropright dropdown-item submenu">
                                                <a id="nav-approve-training" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    @hasanyrole('general_director')
                                                        {{ __('Company MSC Performance') }}
                                                    @else
                                                        {{ __('Building My MSC Objectives') }}
                                                    @endif
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="approve-training-content">
                                                    <a class="dropdown-item" href="{{ route('BMAMO',Auth::user()->id) }}"onclick="">{{ __('Annual MSC') }}</a>
                                                    <a class="dropdown-item" href="{{ route('BMMMO',Auth::user()->id) }}"onclick="">{{ __('Monthly MSC') }}</a>
                                                </div>
                                            </div>
                                            @endhasanyrole
                                            @hasanyrole('employees|department_managers|general_director|super-admin')
                                            <div class="dropright dropdown-item submenu">
                                                <a id="nav-approve-training" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    @hasanyrole('general_director')
                                                        {{ __('Company Rate MSC Performance') }}
                                                    @else
                                                        {{ __('Rating My Performances') }}
                                                    @endif
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="approve-training-content">
                                                    <a class="dropdown-item" href="{{ route('RMMP',Auth::user()->id) }}"onclick="">{{ __('Monthly Performance') }}</a>
                                                    <a class="dropdown-item" href="{{ route('RMAP',Auth::user()->id) }}"onclick="">{{ __('Annual Performance') }}</a>
                                                </div>
                                            </div>
                                            @endhasanyrole
                                        </div>
                                    </div>
                                    @hasanyrole('department_managers|director|super-admin')
                                    <div class="dropright dropdown-item submenu">
                                        <a id="nav-approve-training" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ __('My Staff Performance') }}
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="approve-training-content">
                                            <div class="dropright dropdown-item submenu">
                                                <a id="nav-approve-training" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ __('Approving My Staff MSC Objectives') }}
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="approve-training-content">
                                                    <a class="dropdown-item" href="{{ route('AMEAMO',Auth::user()->id) }}"onclick="">{{ __('Annual MSC Objectives') }}</a>
                                                    <a class="dropdown-item" href="{{ route('AMEMMO',Auth::user()->id) }}"onclick="">{{ __('Monthly MSC Objectives') }}</a>
                                                </div>
                                            </div>
                                            <div class="dropright dropdown-item submenu">
                                                <a id="nav-approve-training" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ __('Approving My Staff Performance') }}
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="approve-training-content">
                                                    <a class="dropdown-item" href="{{ route('AMEMP',Auth::user()->id) }}"onclick="">{{ __('Monthly Performance') }}</a>
                                                    <a class="dropdown-item" href="{{ route('AMEAP',Auth::user()->id) }}"onclick="">{{ __('Annual Performance') }}</a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    @endhasanyrole
                                    @hasanyrole('department_managers|director|general_director|super-admin')
                                    <a class="dropdown-item" href="{{route('performaceManagement',Auth::user()->id)}}">
                                        @hasanyrole('general_director')
                                            {{ __('Company Performance Appraisal Reports') }}
                                        @else
                                            {{ __('Performance Management Reports') }}
                                        @endif
                                    </a>
                                    @endhasanyrole
                                </div>
                            </li>

                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</body>
</html>
