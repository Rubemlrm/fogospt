@extends('app')

@section('content')
    <main role="main" class="mb-auto margin-top-10">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="card">
                        @isset($fire['kml'])
                                <div style="height: 400px" id="mymap"></div>
                        @else
                            <a  href="{{route('fireDetail', $fire['id'])}}" >
                                <img class="card-img-top img-fluid" src="https://api.mapbox.com/styles/v1/mapbox/satellite-streets-v11/static/url-https%3A%2F%2Ftomahock.com%2Fcenas%2Ffogos-icon.png({{$fire['lng']}},{{$fire['lat']}})/{{$fire['lng']}},{{$fire['lat']}},15,0,00/700x393?access_token={{env('MAPBOX_TOKEN')}}">
                            </a>
                        @endisset
                        <div class="card-body">

                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <h4 class="card-title">@lang('elements.cards.general.place')</h4>
                                    <p class="f-local mb-0">
                                        @isset($fire['location'])
                                            {{ $fire['location'] }} - {{ $fire['localidade'] }}
                                        @endisset
                                    </p>
                                    @isset($fire['detailLocation'])
                                    <p class="mt-0">{{$fire['detailLocation']}}
                                    </p>
                                    @endisset
                                    <h4 class="card-title">@lang('elements.cards.general.start_at')</h4>
                                    <p class="f-start">
                                        @isset($fire['date'])
                                            {{ $fire['hour'] }} {{ $fire['date'] }}
                                        @endisset
                                    </p>

                                    <h4 class="card-title">@lang('elements.cards.general.nature')</h4>
                                    <p class="f-nature">
                                        @isset($fire['natureza'])
                                            {{ $fire['natureza'] }}
                                        @endisset
                                    </p>

                                    @isset($fire['icnf']['fontealerta'])
                                        <h4 class="card-title">@lang('elements.cards.general.alertFrom')</h4>
                                        <p class="f-nature">
                                            {{ $fire['icnf']['fontealerta'] }}
                                        </p>
                                    @endisset

                                    @isset($fire['icnf']['burnArea'])
                                        <h4 class="card-title">@lang('elements.cards.detail.burn.title')</h4>
                                        <p class="f-nature">
                                                {{ $fire['icnf']['burnArea']['total'] }} HA
                                        </p>
                                    @endisset


                                    <h4 class="card-title">@lang('elements.cards.general.fireRisk')</h4>
                                    <div class="f-danger">
                                        @isset($fire['risk'])
                                            @include('elements.risk')
                                        @endisset
                                    </div>

                                    @if( !empty($fire['extra']))
                                        <div class="row extra active">
                                    @else
                                        <div class="row extra">
                                    @endif
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title">@lang('elements.cards.extra.title')</h4>
                                                        <div class="f-extra">
                                                            @include('elements.extra')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <h4 class="card-title">@lang('elements.cards.twitter.title')</h4>
                                    <p class="f-twitter">
                                        @include('elements.twitter')
                                    </p>


                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <h4 class="card-title">@lang('elements.cards.resources.units')</h4>
                                    <div class="assets d-flex flex-md-wrap align-items-center justify-content-center">
                                        <img class="assets-icon" src="/img/fireman.svg">
                                        <span class="assets-nr f-man">
                                            @isset($fire['man'])
                                                {{ $fire['man'] }}
                                            @endisset
                                        </span>
                                        <img class="assets-icon" src="/img/firetruck.svg">
                                        <span class="assets-nr f-terrain">
                                            @isset($fire['terrain'])
                                                {{ $fire['terrain'] }}
                                            @endisset
                                        </span>
                                        <img class="assets-icon" src="/img/plane.svg">
                                        <span class="assets-nr f-aerial">
                                            @isset($fire['aerial'])
                                                {{ $fire['aerial'] }}
                                            @endisset
                                        </span>
                                    </div>
                                    <canvas class="px-2 py-0" id="myChart" width="400" height="150" data-id="{{$fire['id']}}"></canvas>

                                    <h4 class="card-title">@lang('elements.cards.status.status')</h4>
                                    <div class="f-status">
                                        <div id="status">
                                            @isset($fire['statusHistory'])
                                                @foreach( $fire['statusHistory'] as $status)
                                                    <div>
                                                        <span class="dot status-{{ $status['statusCode'] }} timelineDot"></span>
                                                        <div>
                                                            <p class="status-hour">{{ $status['label'] }}</p>
                                                            <p class="status-label">{{ $status['status'] }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endisset
                                        </div>
                                    </div>

                                    <h4 class="card-title">@lang('elements.cards.meteo.title')</h4>
                                    <div class="f-meteo">
                                        @isset($fire['meteo'])
                                            @include('elements.meteo')
                                        @endisset
                                    </div>

                                    <h4 class="card-title">@lang('elements.cards.shares.title')</h4>
                                    <div class="row justify-content-center">
                                        <div class="col-8">
                                            <div class="f-shares">
                                                @include('elements.shares')
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection


@push('scripts')
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v1.5.0/mapbox-gl.css' rel='stylesheet' />
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v1.5.0/mapbox-gl.js'></script>

    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
            integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
            crossorigin=""></script>

    <script src="https://unpkg.com/mapbox-gl-leaflet/leaflet-mapbox-gl.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="/js/vendor/store2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/vendor/L.KLM.js') }}"></script>
    <script src="{{ asset('js/detail.js') }}"></script>

    <script>
        @isset($fire['kml'])
        $(document).ready( function () {
            // Make basemap
            const map = new L.Map('mymap', { center: new L.LatLng(58.4, 43.0), zoom: 11 });
            const osm = new L.TileLayer('https://api.mapbox.com/styles/v1/fogospt/cjksgciqsctfg2rp9x9uyh37g/tiles/256/{z}/{x}/{y}@2x?access_token=pk.eyJ1IjoiZm9nb3NwdCIsImEiOiJjamZ3Y2E5OTMyMjFnMnFxbjAxbmt3bmdtIn0.xg1X-A17WRBaDghhzsmjIA');

            map.addLayer(osm);

            // Load kml file
                var kmltext = '{!! $kml !!}';
                console.log(kmltext);
                // Create new kml overlay
                const parser = new DOMParser();
                const kml = parser.parseFromString(kmltext, 'text/xml');
                console.log(kml);
                const track = new L.KML(kml);
                map.addLayer(track);
                console.log(map);

                // Adjust map to show the kml
                const bounds = track.getBounds();
                console.log(bounds);
                map.fitBounds(bounds);
        } );
        @endisset
    </script>

@endpush
