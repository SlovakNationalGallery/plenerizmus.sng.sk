@extends('layouts.master')

@section('title', trans('intro.title'))

@section('content')

    <div class="header text-center my-4">
      <h1>Čo chceš vidieť?</h1>
      <h2 class="text-uppercase">zvoľ vlastnosti a vyhľadaj svoje dielo</h2>
    </div>

    <div class="row my-5">
      <div class="col-sm text-center px-5">
        <h3 class="my-3">Počasie</h3>

        <div class="row">
        @foreach ($weather as $w)
            <a href="?pocasie={{ $w }}" class="display-block col-4 weather-icon icon mb-2">
              <img src="/images/pocasie/{{ str_slug($w) }}.svg" alt="{{ $w }}">
              {{ $w }}
            </a>
        @endforeach
        </div>
      </div>
      <div class="col-sm text-center px-5">
        <h3 class="my-3">Motív</h3>

        <div class="row">
        @foreach ($subject as $s)
            <a href="?motiv={{ $s }}" class="display-block col-3 subject-icon icon mb-2">
              <img src="/images/motivy/motiv-{{ str_slug($s) }}.jpg" alt="{{ $s }}" class="rounded-circle">
              {{ $s }}
            </a>
        @endforeach
        </div>
      </div>
      <div class="col-sm text-center px-5">
        <h3 class="my-3">Nálada</h3>

        {{-- <div class="row"> --}}
        @foreach ($mood as $m)
            <a href="#" class="mood-icon icon mb-2 mx-2 py-2 px-4">
              {{ $m }}
            </a>
        @endforeach
        {{-- </div> --}}

      </div>
    </div>

    <div class="text-center">
      <button type="button" class="btn btn-dark btn-lg">Vyhľadaj →</button>
    </div>

@stop
