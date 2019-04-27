<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <meta name="description" content="Vyber si z pestrej škály motívov, nálad a počasia a preskúmaj výslednú maľbu alebo kresbu naozaj zblízka.">

  <meta property="og:title" content="Z akadémie do prírody" />
  <meta property="og:description" content="Vyber si z pestrej škály motívov, nálad a počasia a preskúmaj výslednú maľbu alebo kresbu naozaj zblízka." />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="{{ url()->current() }}" />
  <meta property="og:image" content="{{ asset('/images/og-image.jpg') }}" />
  <meta property="og:site_name" content="Z akadémie do prírody" />
  @show

  <title>
    Z akadémie do prírody
  </title>

  <!-- Favicons-->
  <link rel="shortcut icon" href="favicon.png">

  @yield('link')


  <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
  @stack('styles')

  @if (App::environment() == 'production' && config('app.ga_code'))
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', '{{ config('app.ga_code') }}', 'auto');
    ga('send', 'pageview');

  </script>
  @endif

</head>
<body class="@yield('body-class', '')">
  <div class="container-fluid py-4 h-100 px-lg-4 px-xxl-5" id="app">
    @yield('content')
  </div>
  {{-- <script type="text/javascript" src="{{ mix('/js/manifest.js') }}"></script> --}}
  {{-- <script type="text/javascript" src="{{ mix('/js/vendor.js') }}"></script> --}}
  <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
  @stack('scripts')

</body>
</html>
