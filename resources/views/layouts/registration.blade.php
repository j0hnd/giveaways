<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{ config('app.name') }}</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="{{ url('/css/reset.css') }}">
        <link rel="stylesheet" href="{{ url('/css/app.css') }}">
        <link rel="stylesheet" href="{{ url('/css/all.css') }}">
        <link rel="stylesheet" href="{{ url('/css/cmxform.css') }}">
        @yield('custom-css')
    </head>

    <body>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId=955103817906379&autoLogAppEvents=1';
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));

            /*
             * facebook APP init
             */
            window.fbAsyncInit = function () {
                FB.init({
                    appId      : '{{ env('FACEBOOK_APP_ID') }}',
                    cookie     : true,
                    xfbml      : true,
                    version    : 'v2.8'
                });
            };
        </script>

        <div id="app">
            <div class="container">
                <section class="wrapper">
                    <div class="row margin-top25">
                        <div class="col-md-12">
                            @yield('main-content')
                        </div>
                    </div>
                </section>
            </div><!-- ./wrapper -->
        </div>

        @include('Partials.Common._footer')
        @include('Partials.Common._scripts', ['full' => false])
        @yield('custom-js')
    </body>
</html>
