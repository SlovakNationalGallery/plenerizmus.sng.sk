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
        {{-- <a id="zoom-in" href="#zoom-in" title="zoom in"><i class="fas fa-search-plus"></i></a> --}}
        {{-- <a id="zoom-out" href="#zoom-out" title="zoom out"><i class="fas fa-search-minus"></i></a> --}}
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

    <div class="top-right">
      <a href="#" class="mr-4" data-toggle="modal" data-target="#infoModal"><em>Info</em></a>
    </div>

    <div class="bottom-panel bg-overlay">
      <div class="row">
        <div class="col-12 col-sm-6 col-md-4 order-sm-2 p-2 text-center align-self-center">
          <em>{{ $item->getTitleWithAuthors() }} ({{ $item->dating }})</em>
          {{-- {{ $item->dating }}, {{ implode(', ', $item->techniques) }}, {{ implode(', ', $item->mediums) }}, {{ $item->gallery }} --}}
        </div>
        <div class="col-6 col-sm-3 col-md-4 order-sm-1 p-2 text-left">
          <a href="/" class="btn btn-link btn-lg ml-4" class=""><img src="images/icons/arrow.svg" alt="arrow" class="pr-2" style="margin-top: -0.3rem;"><span class="d-none d-md-inline">Naspäť</span></a>
        </div>
        <div class="col-md-2 p-2 order-md-3 text-center d-none d-md-block text-nowrap">
          <a id="zoom-in" href="#zoom-in" class="btn btn-link btn-lg px-1 px-lg-2"><img src="images/icons/plus.svg" alt="plus"></a>
          <a id="zoom-out" href="#zoom-out" class="btn btn-link btn-lg px-1 px-lg-2"><img src="images/icons/minus.svg" alt="minus"></a>
        </div>
        <div class="col-6 col-sm-3 col-md-2 p-2 order-sm-4 text-right">
          @if ($reload_url)
            <a href="{{ $reload_url }}" class="btn btn-link btn-lg mr-4" rel="nofollow"><span class="d-none d-md-inline">Ďalšie</span><img src="images/icons/reload.svg" alt="" class="pl-2"></a>
          @endif
        </div>
      </div>

    </div>

    {{-- Modal --}}
    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body text-center p-4">
            <em class="mt-2">{{ implode(', ', $item->authors) }}</em>
            <h3>{{ $item->title }}</h3>
            <p class="my-2">
              {{ $item->dating }},
              {{ $item->technique }},
              {{ $item->medium }},<br>
              {{ $item->gallery }}
              @if (session('kiosk', false))
                {{ $item->getUrl(false) }}
              @else
                <a href="{{ $item->getUrl() }}" class="link-underline" target="_blank">{{ $item->getUrl(false) }}</a>
              @endif
            </p>

            {!! QrCode::size(200)->backgroundColor(234,225,208)->margin(0)->generate($item->getUrl()); !!}

            <p class="mt-2">
              toto a ďaľších {{ number_format($items_count, 0, ',', ' ') }} výtvarných diel<br>
              nájdete na
              @if (session('kiosk', false))
                webumenia.sk
              @else
                <a href="https://www.webumenia.sk/kolekcia/173" class="link-underline" target="_blank">webumenia.sk</a>
              @endif
            </p>
          </div>
        </div>
      </div>
    </div>
@stop

@push('scripts')

<script type="text/javascript" src="{{ asset('js/openseadragon.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/components/zoomviewer.js') }}"></script>

@if (session('kiosk', false))
  {{-- return user back to intro screen if idle  > 60 seconds --}}
  <script>
  (function() {

      const idleDurationSecs = 60;
      const redirectUrl = '/';
      let idleTimeout;

      const resetIdleTimeout = function() {
          if(idleTimeout) clearTimeout(idleTimeout);
          idleTimeout = setTimeout(
            function(){
              $("#viewer .openseadragon-container").fadeOut("slow", "swing", function(){ location.href = redirectUrl });
            },
            idleDurationSecs * 1000);
      };

      // init on page load
      resetIdleTimeout();

      // reset the idle timeout on any of the events listed below
      ['click', 'touchstart', 'mousemove'].forEach(evt =>
          document.addEventListener(evt, resetIdleTimeout, false)
      );

      /* disable external links */
      $('a').filter(function() {
         return this.hostname && this.hostname !== location.hostname;
      }).addClass("external");

      $('a').click(function(e) {
          if($(this).hasClass('external')) {
              e.preventDefault();
          }
      });

  })();


  </script>
@endif

@endpush