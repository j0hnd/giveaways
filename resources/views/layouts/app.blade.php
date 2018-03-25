<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{ config('app.name') }}</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @include('Partials.Common._styles')
        
        @yield('custom-css')
    </head>

    <body>
        <div id="app">
            <div class="container">
                <section class="wrapper">
                    @if (Auth::check())
                    <div class="row margin-top15 margin-bottom15">
                        @include('Partials.Menu.nav')
                    </div>
                    @endif

                    @yield('main-content')

                    @include('Partials.Modals._modals')
                    @include('Partials.Modals._prizes')
                </section>
            </div><!-- ./wrapper -->
        </div>

        @include('Partials.Common._footer')
        @include('Partials.Common._scripts', ['full' => true])
        @yield('custom-js')
    </body>
</html>
