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

Route::get('/', function () {

    $limit = 10;
    $filter_tag = 'plenérizmus';
    $client = ClientBuilder::create()->build();
    $params = [
        'index' => 'webumenia_plenerizmus_sk',
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

Route::get('/{id}', function ($id) {

    $client = ClientBuilder::create()->build();
    $params = [
        'index' => 'webumenia_plenerizmus_sk',
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
