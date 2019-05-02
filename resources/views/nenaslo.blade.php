@extends('layouts.master')

@section('title', trans('intro.title'))

@section('body-class', 'fade-out')

@section('content')

  <div class="d-table h-100 w-100" id="nenaslo"><div class="d-table-cell align-middle">

    <div class="row">

      <div class="col-md-12 text-center">
        <p class="text-center">
          pre zadané vlastnosti sa nanašlo žiadne dielo
        </p>
        <a href="/" class="btn btn-light d-inline-block mr-1">
          {{-- <img src="images/icons/arrow.svg" alt="arrow" class="pr-2" style="margin-top: -0.3rem;"> --}}
          Návrat naspäť
        </a>
        <a href="/dielo" class="btn btn-light d-inline-block ml-1">
          {{-- <img src="images/icons/arrow.svg" alt="arrow" class="pr-2" style="margin-top: -0.3rem;"> --}}
          Hocijaké dielo
        </a>
      </div>
    </div>

  </div></div>
@stop

@push('scripts')

  <script>
  (function() {
    console.log('redirect back');
  })();
  </script>

@endpush