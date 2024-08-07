<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])


    @include('layouts.styles');
    @yield('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">


</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('users.index') }}">
                   Artist Management System
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <div class="d-flex justify-content-end">
                                <!-- Logout Button -->
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        {{ __('Logout') }}
                                    </button>
                                </form>
                            </div>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar mt-4">
                    <div class="position-sticky">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Navigation</h5>
                            </div>
                            <div class="card-body">
                                <ul class="nav flex-column">
                                    <li class="nav-item mb-2">
                                        <a class="nav-link btn btn-primary text-start d-flex align-items-center {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                            <i class="bi bi-people-fill me-2"></i>
                                            Users
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link btn btn-primary text-start d-flex align-items-center {{ request()->routeIs('artists.index') ? 'active' : '' }}" href="{{ route('artists.index') }}">
                                            <i class="bi bi-palette me-2"></i>
                                            Artists
                                        </a>
                                    </li>


                                    <li class="nav-item">
                                        <a class="nav-link btn btn-primary text-start d-flex align-items-center href="#">
                                            <i class="bi bi-music-note-list me-2"></i>
                                            Songs
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>


                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

@include('layouts.scripts')
@yield('scripts')

</body>
</html>