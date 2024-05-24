<!DOCTYPE html>
<html lang="{{str_replace('_', '-', app()->getLocale())}}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{env('APP_NAME', 'Bee')}}</title>
        <link rel="shortcut icon" href="{{asset('factcolombia\assets\images\favicon_dian.ico')}}">
        {{-- Styles --}}
        <link href="{{asset('css\factcolombia1.css')}}" rel="stylesheet">
        <link href="{{asset('factcolombia\assets\css\main.css')}}" rel="stylesheet">
        <link href="{{asset('factcolombia\assets\css\custom.css')}}" rel="stylesheet">
        @yield('css')
    </head>
    <body>
        <div id="app" class="wrapper">

            @if (Auth::check())
                @if (app(Hyn\Tenancy\Contracts\CurrentHostname::class))
                    @include('app.tenant.navigation')
                    @include('app.tenant.toolbar')
                @else
                    @include('app.system.navigation')
                    @include('app.system.toolbar')
                @endif
            @endif

            @yield('content')

            @if (Auth::check())
                @if (app(Hyn\Tenancy\Contracts\CurrentHostname::class))
                    @include('app.tenant.footer')
                @else
                    @include('app.system.footer')
                @endif
                @include('notification.snackbarNotification')
            @endif

        </div>
    </body>
    {{-- JavaScript --}}
    {{-- <script src="{{asset('js/app.js')}}"></script>
    <script src="{{asset('assets/js/app.js')}}"></script> --}}
    
    <script src="{{asset('js/factcolombia1.js')}}"></script>

    @yield('js')
</html>
