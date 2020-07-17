<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/open-iconic-bootstrap.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/kendo/styles/kendo.common.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/kendo/styles/kendo.default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/kendo/styles/kendo.default.mobile.min.css') }}" />

    <!-- Más Scripts -->
    <script src="{{ asset('/kendo/js/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/kendo/js/kendo.all.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/kendo/js/kendo.all.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/kendo/js/messages/kendo.messages.es-MX.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/qz/dependencies/rsvp-3.1.0.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/qz/dependencies/sha-256.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/qz/qz-tray.js') }}"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                  {{ config('app.name', 'Laravel') }} 
                  19.10-2 
                  {{ $taquilla? $taquilla->nombre: "" }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @else
                          <li class="nav-item">
                            @include('buscar')
                          </li>
                            <li class="nav-item">
                                <a class="nav-link disabled text-danger" href="#">
                               <!-- ${{ number_format($resumen["total"],2) }} {{$moneda->nombre }}-->

                                </a>

                            </li>

                            <li class="nav-item">
                              <a class="nav-link" href="{{ route('taquilla.asignar') }}"><span class="oi oi-star" title="Cambiar de taquilla" aria-hidden="true"></span> Cambiar de taquilla</a>
                            </li>
                            <li class="nav-item">
                                <!--nav-link-->
                              <a class="btn btn-light" href="{{ route('sesion.moneda', $moneda_alterna->id) }}"><span class="oi oi-loop-circular" title="Cambiar de taquilla" aria-hidden="true"></span> Cambiar a {{ $moneda_alterna->nombre}}</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
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

        <main class="py-4">

          @if (Session::has('alert-info')) 
          <div class="container">
            <div class="alert alert-info">
            {!! Session::get('alert-info') !!}
            </div>
          </div>
          @endif

            @yield('content')
        </main>
    </div>
<script>
document.addEventListener("DOMContentLoaded", function() {
  $('#texto').focus();
});
</script>
</body>
</html>
