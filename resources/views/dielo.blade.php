@extends('layouts.master')

@section('title', trans('intro.title'))

@section('content')


    <div
      class="zoomviewer"
      data-index="{!! $index !!}"
      data-item-url=""
      data-image-count="{!! count($fullIIPImgURLs) !!}"
      data-tile-sources='{!! json_encode($fullIIPImgURLs) !!}'
      >
      <div id="viewer"></div>

      <div id="toolbarDiv" class="autohide">
        <a id="zoom-in" href="#zoom-in" title="zoom in"><i class="fas fa-search-plus"></i></a>
        <a id="zoom-out" href="#zoom-out" title="zoom out"><i class="fas fa-search-minus"></i></a>
        {{-- <a id="home" href="#home" title="zoom to fit"><i class="fa fa-home"></i></a> --}}
        {{-- <a id="full-page" href="#full-page" title="zobraz fullscreen"><i class="fa fa-expand"></i></a> --}}
        @if (count($fullIIPImgURLs) > 1)
          <a id="previous" href="#previous" title="predchádzajúce súvisiace dielo"><i class="fa fa-arrow-up"></i></a>
          <a id="next" href="#next" title="nasledujúce súvisiace dielo"><i class="fa fa-arrow-down"></i></a>
        @endif
      </div>

      @if (count($fullIIPImgURLs) > 1)
        <div class="autohide"><div class="currentpage"><span id="index">{!! $index + 1 !!}</span> / {!! count($fullIIPImgURLs) !!}</div></div>
      @endif


    </div>
    <div class="bottom-panel">
      <div class="row">
        <div class="col-12 col-sm-6 order-sm-2 p-2 text-center bg-overlay">
          {{ $item->getTitleWithAuthors() }}<br>
          {{ $item->dating }}, {{ implode(', ', $item->techniques) }}, {{ implode(', ', $item->mediums) }}, {{ $item->gallery }}
        </div>
        <div class="col-6 col-sm-3 order-sm-1 p-2 text-left">
          <a href="/" class="btn btn-dark ml-4"><i class="fas fa-arrow-left"></i> Skús znova</a>
        </div>
        <div class="col-6 col-sm-3 p-2 order-sm-3 text-right">
        <a href="{{ url()->full() }}" class="btn btn-dark mr-4">Iné dielo <i class="fas fa-redo-alt"></i></a>
        </div>
      </div>

    </div>
@stop

@push('scripts')

<script type="text/javascript" src="{{ asset('js/openseadragon.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/components/zoomviewer.js') }}"></script>

@if (session('kiosk', false))
  {{-- return user back to intro screen if idle  > 60 seconds --}}
  <script>
  (function() {

      const idleDurationSecs = 5;
      const redirectUrl = '/';
      let idleTimeout;

      const resetIdleTimeout = function() {
          if(idleTimeout) clearTimeout(idleTimeout);
          idleTimeout = setTimeout(() => location.href = redirectUrl, idleDurationSecs * 1000);
      };

      // init on page load
      resetIdleTimeout();

      // reset the idle timeout on any of the events listed below
      ['click', 'touchstart', 'mousemove'].forEach(evt =>
          document.addEventListener(evt, resetIdleTimeout, false)
      );

  })();
  </script>
@endif

@endpush