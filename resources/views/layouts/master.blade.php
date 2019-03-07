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
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.1.0/nouislider.min.css" /> --}}

  <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
  @stack('styles')

</head>
<body>
  <div class="container py-4" id="app">
    @yield('content')
  </div>
  {{-- <script type="text/javascript" src="{{ mix('/js/manifest.js') }}"></script> --}}
  {{-- <script type="text/javascript" src="{{ mix('/js/vendor.js') }}"></script> --}}
  <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>

</body>
</html>
