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

    if ($request->has('pocasie')) {
        dd($request->input('pocasie'));
        if ($request->session()->has('pocasie')) {
            //
        }
    }

    $limit = 10;
    $filter_tag = 'plenérizmus';
    $client = ClientBuilder::create()->build();
    $params = [
        'index' => config('elasticsearch.index'),
        'type' => 'items',
        'body' => [
            'query' => [
            ]
        ]
        // 'id' => 'SVK:SNG.O_4939',
        // 'id' => 'my_id',
        // 'body' => ['testField' => 'abc']
    ];
    $params['body']['size'] = $limit;
    $params['body']['query']['bool']['filter']['and'][]['term']['has_iip'] = true;
    $params['body']['query']['bool']['filter']['and'][]['term']['tag'] = $filter_tag;


    $response = $client->search($params);
    // dd($response);

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
        'studňa',
        'tieň',
        'vidiek',
        'voda',
        'zviera',
    ];

    $mood = [
        'idyla',
        'mäkkosť',
        'majestát',
        'malebnosť',
        'pochmúrnosť',
        'samota',
        'sviežosť',
        'ticho',
        'tvrdosť',
        'zamyslenie',
        'zvuk',
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
    $client = ClientBuilder::create()->build();
    $params = [
        'index' => config('elasticsearch.index'),
        'type' => 'items',
        'body' => [
            'query' => [
            ]
        ]
        // 'id' => 'SVK:SNG.O_4939',
        // 'id' => 'my_id',
        // 'body' => ['testField' => 'abc']
    ];
    $params['body']['size'] = $limit;
    $params['body']['sort'][] = [
        '_script' => [
            'script' => 'Math.random() * 200000',
            'type' => 'number',
            'params' => [],
            'order' => 'asc',
        ]
    ];


    $params['body']['query']['bool']['must'][]['term']['has_iip'] = true;
    $params['body']['query']['bool']['must'][]['term']['tag'] = $filter_tag;

    if ($request->has('pocasie')) {
        $weather = [];
        foreach ($request->input('pocasie') as $pocasie) {
            $weather[]['term']['tag'] = $pocasie;
        }
        $params['body']['query']['bool']['must'][]['bool']['should'] = $weather;
    }
    if ($request->has('motiv')) {
        $subject = [];
        foreach ($request->input('motiv') as $motiv) {
            $subject[]['term']['tag'] = $motiv;
        }
        $params['body']['query']['bool']['must'][]['bool']['should'] = $subject;
    }
    if ($request->has('nalada')) {
        $mood = [];
        foreach ($request->input('nalada') as $nalada) {
            $mood[]['term']['tag'] = $nalada;
        }
        $params['body']['query']['bool']['must'][]['bool']['should'] = $mood;
    }

    // dd($params);

    $response = $client->search($params);

    if (empty($response['hits']['total'])) {
        return 'nenaslo ziadne';
    }

    $item_id = $response['hits']['hits'][0]['_source']['id'];

    $item = \App\Item::find($item_id);
    $itemImages = $item->getZoomableImages();
    $fullIIPImgURLs = $itemImages->map(function ($itemImage) {
        return $itemImage->getFullIIPImgURL();
    });
    $index = 0;

    return view('dielo', [
        'item' => $item,
        'index' => $index,
        'fullIIPImgURLs' => $fullIIPImgURLs,
    ]);


});

Route::get('/{id}', function ($id) {

    $client = ClientBuilder::create()->build();
    $params = [
        'index' => config('elasticsearch.index'),
        'type' => 'items',
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
    dd($item);



});
