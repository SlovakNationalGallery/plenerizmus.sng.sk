<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <meta name="description" content="Počas 19. storočia objavovali umelci a umelkyne čaro tvorby v plenéri. Krásy prírody, mesta či vidieka zachytávali priamo na plátno alebo na papier. Vyber si z pestrej škály motívov, nálad a počasia a preskúmaj výslednú maľbu alebo kresbu naozaj zblízka">

  <meta property="og:title" content="Z akadémie do prírody" />
  <meta property="og:description" content="Počas 19. storočia objavovali umelci a umelkyne čaro tvorby v plenéri. Krásy prírody, mesta či vidieka zachytávali priamo na plátno alebo na papier. Vyber si z pestrej škály motívov, nálad a počasia a preskúmaj výslednú maľbu alebo kresbu naozaj zblízka" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="{{ url()->current() }}" />
  <meta property="og:image" content="{{ asset('/images/og_image.jpg') }}" />
  <meta property="og:site_name" content="Z akadémie do prírody" />
  @show

  <title>
    Z akadémie do prírody
  </title>

  <!-- Favicons-->
  <link rel="shortcut icon" href="favicon.png">

  @yield('link')

  <!-- Styles -->
  <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/solid.css" integrity="sha384-r/k8YTFqmlOaqRkZuSiE9trsrDXkh07mRaoGBMoDcmA58OHILZPsk29i2BsFng1B" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/fontawesome.css" integrity="sha384-4aon80D8rXCGx9ayDt85LbyUHeMWd3UiBaWliBlJ53yzm9hqN21A+o1pqoyK04h+" crossorigin="anonymous">


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
<body>
  <div class="container-fluid py-4 h-100" id="app">
    @yield('content')
  </div>
  {{-- <script type="text/javascript" src="{{ mix('/js/manifest.js') }}"></script> --}}
  {{-- <script type="text/javascript" src="{{ mix('/js/vendor.js') }}"></script> --}}
  <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
  @stack('scripts')

</body>
</html>
