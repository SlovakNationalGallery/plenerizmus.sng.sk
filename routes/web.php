<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;

Route::get('/', function (Request $request) {

    // check whether set app in kiosk mode - for timeouts and blocking outgoing links
    if ($request->has('kiosk') && $request->input('kiosk')) {
        $request->session()->put('kiosk', true);
    }

    $limit = 10;
    $filter_tag = 'plenérizmus';
    $client = ClientBuilder::create()->setHosts(config('elasticsearch.hosts'))->build();
    $params = [
        'index' => config('elasticsearch.index'),
        'body' => [
            'query' => []
        ]
    ];

    $params['body']['size'] = $limit;
    $params['body']['query']['bool']['must'][]['term']['has_iip'] = true;
    $params['body']['query']['bool']['must'][]['term']['tag'] = $filter_tag;


    $response = $client->search($params);

    $weather = [
        'jasno',
        'oblačno',
        'zima',
        'hmla',
        'dážď',
        'súmrak',
    ];

    $subject = [
        'cesta',
        'človek',
        'horizont',
        'hory',
        'kvety',
        'loď',
        'oddych',
        'pole',
        'práca',
        'stavba',
        'stromy',
        // 'studňa',
        'tieň',
        'vidiek',
        'voda',
        'zviera',
    ];

    $mood = [
        'idyla',
        'mäkkosť',
		'melanchólia',
		'sviežosť',
        'zvuk',
		'zamyslenie',
        'malebnosť',
        'majestát',
        'ticho',
        'pochmúrnosť',
        'samota',
        'tvrdosť',
    ];


    return view('intro', [
        'weather' => $weather,
        'subject' => $subject,
        'mood' => $mood,
    ]);




});

Route::get('/dielo', function (Request $request) {

    $limit = 1;
    $filter_tag = 'plenérizmus';
    $client = ClientBuilder::create()->setHosts(config('elasticsearch.hosts'))->build();
    $params = [
        'index' => config('elasticsearch.index'),
        'body' => []
    ];
    $params['body']['size'] = $limit;

    $query = [];
    $query['bool']['must'][]['term']['has_iip'] = true;
    $query['bool']['must'][]['term']['tag'] = $filter_tag;

    if ($request->has('pocasie')) {
        $weather = [];
        foreach ($request->input('pocasie') as $pocasie) {
            $weather[]['term']['tag'] = $pocasie;
        }
        $query['bool']['must'][]['bool']['must'] = $weather;
    }
    if ($request->has('motiv')) {
        $subject = [];
        foreach ($request->input('motiv') as $motiv) {
            $subject[]['term']['tag'] = $motiv;
        }
        $query['bool']['must'][]['bool']['must'] = $subject;
    }
    if ($request->has('nalada')) {
        $mood = [];
        foreach ($request->input('nalada') as $nalada) {
            $mood[]['term']['tag'] = $nalada;
        }
        $query['bool']['must'][]['bool']['must'] = $mood;
    }

    if ($request->has('exclude')) {
        $exclude = [];
        foreach ($request->input('exclude') as $pouzite) {
            $exclude[]['term']['id'] = $pouzite;
        }
        $query['bool']['must'][]['bool']['must_not'] = $exclude;
    }

    $params['body'] = [
                        'query' => [
                            'function_score' => [
                                'query' => $query,
                                'random_score' => new \stdClass,
                                'boost_mode' => 'replace',
                            ]
                        ]
                    ];

    $response = $client->search($params);

    if (empty($response['hits']['total']['value'])) {
        return view('nenaslo');
    }

    $item_id = $response['hits']['hits'][0]['_source']['id'];

    $total = $response['hits']['total']['value'];

    $reload_url = null;
    if ($total > 1) { //and $total < count(excluded)
        $allQueries = array_merge($request->query(), ['exclude[]' => $item_id]);
        //Generate the URL with all the queries:
        $reload_url = $request->fullUrlWithQuery($allQueries);
    }


    $item = \App\Item::find($item_id);
    $itemImages = $item->getZoomableImages();
    $fullIIPImgURLs = $itemImages->map(function ($itemImage) {
        return $itemImage->getFullIIPImgURL();
    });
    $index = 0;

    $item_visit = new \App\ItemVisit;
    $item_visit->item_id = $item->id;
    $item_visit->ip = $request->ip();
    $item_visit->is_kiosk = $request->session()->get('kiosk', 0);
    $item_visit->viewed_at = now();
    $item_visit->save();

    $params = [
        'index' => config('elasticsearch.index'),
    ];
    $response = $client->search($params);

    $items_count = (!empty($response['hits']['total']['value'])) ? $response['hits']['total']['value'] : 0;

    return view('dielo', [
        'item' => $item,
        'index' => $index,
        'fullIIPImgURLs' => $fullIIPImgURLs,
        'items_count' => $items_count,
        'reload_url' => $reload_url,
    ]);


});

Route::get('/{id}', function ($id) {

    $client = ClientBuilder::create()->setHosts(config('elasticsearch.hosts'))->build();
    $params = [
        'index' => config('elasticsearch.index'),
        'id' => $id,
    ];

    try {
        $response = $client->get($params);
    } catch (\Throwable $e) {
        if ($e->getCode() == 404) {
            abort(404);
        }
        throw e;
    }

    $item = $response['_source'];

    dd($response);



});
