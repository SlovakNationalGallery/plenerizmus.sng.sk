@extends('layouts.master')

@section('title', trans('intro.title'))

@section('body-class', 'fade-out')

@section('content')

  <form action="/dielo" method="GET" class="d-inline">
  <div class="d-table h-100" id="intro"><div class="d-table-cell align-middle">
    <div class="row">

      <div class="col-md-6 order-md-2 header text-center my-4">
        <h1>Nájdi svoje obľúbené dielo</h1>
        <h2 class="py-1">navoľ si vlastnosti diela podľa svojej aktuálnej nálady</h2>
      </div>

      <div class="col-md-3 order-md-1 my-md-4 px-4">
        <p class="text-center small">
          V druhej polovici 19. storočia objavili umelci a&nbsp;umelkyne čaro tvorby v plenéri. Krásy prírody, mesta či vidieka zachytávali priamo na plátno alebo na papier.
        </p>
      </div>

      <div class="col-md-3 order-md-3 my-md-4 px-4">
        <p class="text-center small">
          Vyber si z pestrej škály motívov, nálad a&nbsp;počasia a&nbsp;preskúmaj výslednú maľbu alebo kresbu naozaj zblízka.
        </p>
      </div>

    </div>


    <div class="row">

      <div class="col-md-5 text-center px-5">
        <h3 class="my-2 my-md-4 my-xxl-5"><em>1.</em> Motív</h3>

        <div class="row">
        @foreach ($subject as $s)
            <a href="/dielo?motiv[]={{ $s }}" class="display-block col-2dot4 subject-icon icon mb-4 text-center">
              <img src="/images/motivy/motiv-{{ str_slug($s) }}.jpg" alt="{{ $s }}" class="rounded-circle mb-2"><br>
              {{ $s }}
              <input type="checkbox" name="motiv[]" value="{{ $s }}" />
            </a>
        @endforeach
        </div>
      </div>

      <div class="col-md-4 text-center px-5">
        <h3 class="my-2 my-md-4 my-xxl-5"><em>2.</em> Nálada</h3>
        <div class="row">
        @foreach ($mood as $m)
            <a href="/dielo?nalada[]={{ $m }}" class="display-block col-3 icon mb-4 text-center">
              {{-- <span class="{{ str_slug($m) }} rounded-circle mb-2"></span><br> --}}
              <img src="/images/nalady/{{ str_slug($m) }}.svg" alt="{{ $m }}" class="rounded-circle mb-2"><br>
              {{ $m }}
              <input type="checkbox" name="nalada[]" value="{{ $m }}" />
            </a>
        @endforeach
        </div>
      </div>

      <div class="col-md-3 text-center px-5">
        <h3 class="my-2 my-md-4 my-xxl-5"><em>3.</em> Počasie</h3>

        <div class="row">
        @foreach ($weather as $w)
            <a href="/dielo?pocasie[]={{ $w }}" class="display-block col-4 weather-icon icon mb-4 text-center">
              <img src="/images/pocasie/{{ str_slug($w) }}.svg" alt="{{ $w }}" class="rounded-circle mb-2"><br>
              {{ $w }}
              <input type="checkbox" name="pocasie[]" value="{{ $w }}" />
            </a>
        @endforeach
        </div>


      </div>

    </div>

    {{-- <div class="text-center"> --}}
      {{-- <button type="button" class="btn btn-dark btn-lg">Vyhľadaj <i class="fas fa-arrow-right"></i></button> --}}
    {{-- </div> --}}

    <div class="mt-2 mt-md-4 mt-xxl-5 keep-height d-block">
        <div class="row">
            <div class="col-md-3 col-xxl-3"></div>
            <div class="col-md-6 col-xxl-6">
                <citation class=""></citation>
            </div>
            <div class="col-md-3 col-xxl-3 text-right">
                <button type="submit" class="btn btn-light btn-lg mr-4" id="submit"><span class="d-none d-md-inline">Vyhľadaj</span><img src="images/icons/arrow-right.svg" alt="arrow" class="pl-2"></button>
            </div>
        </div>
    </div>


  </div></div>
  </form>
@stop

@push('scripts')

  <script>
  (function() {

    $(".icon").each(function () {
      if ($(this).find('input[type="checkbox"]').first().attr("checked")) {
        $(this).addClass('active');
      }
      else {
        $(this).removeClass('active');
      }
    });

    $(".icon").on("click", function (e) {

      if ($(this).hasClass('disabled')) {
        e.preventDefault();
        return;
      }

      $(this).toggleClass('active');
      var $checkbox = $(this).find('input[type="checkbox"]');
      $checkbox.prop("checked",!$checkbox.prop("checked"));

      if ($("input:checkbox:checked").length > 0)
      {
          $('#submit').addClass('ready');
      }
      else
      {
        $('#submit').removeClass('ready');
      }

      e.preventDefault();

      var formData = new FormData($('form')[0]);

      $.ajax({
          type: 'GET',
          url: '{{ route('atributy') }}?' + $('form').find(":input").serialize(),
          dataType: 'json',
          success: function (data) {
              $.each($('form input'), function(index, element) {
                  if(jQuery.inArray($(element).val(), data) == -1) {
                    $(element).parent('a').addClass('disabled');
                  } else {
                    $(element).parent('a').removeClass('disabled');
                  }
              });
          }
      });


    });
  })();
  </script>

@endpush