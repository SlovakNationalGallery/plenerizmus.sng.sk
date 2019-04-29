<?php

namespace App\Http\Controllers;

use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;

class AttributesController extends Controller
{
    protected $filter_tag = 'plenérizmus';

    public function get(Request $request)
    {
        $client = ClientBuilder::create()->build();
        $params = [
            'index' => config('elasticsearch.index'),
            'type' => 'items',
            'body' => [
                'query' => [
                ]
            ]
        ];

        $params['body']['query']['bool']['must'][]['term']['has_iip'] = true;
        $params['body']['query']['bool']['must'][]['term']['tag'] = $this->filter_tag;

        if ($request->has('pocasie')) {
            $weather = [];
            foreach ($request->input('pocasie') as $pocasie) {
                $weather[]['term']['tag'] = $pocasie;
            }
            $params['body']['query']['bool']['must'][]['bool']['must'] = $weather;
        }
        if ($request->has('motiv')) {
            $subject = [];
            foreach ($request->input('motiv') as $motiv) {
                $subject[]['term']['tag'] = $motiv;
            }
            $params['body']['query']['bool']['must'][]['bool']['must'] = $subject;
        }
        if ($request->has('nalada')) {
            $mood = [];
            foreach ($request->input('nalada') as $nalada) {
                $mood[]['term']['tag'] = $nalada;
            }
            $params['body']['query']['bool']['must'][]['bool']['must'] = $mood;
        }
        $attribute = 'tag';

        $json_params = '
        {
         "aggs" : {
            "'.$attribute.'" : {
                "terms" : {
                  "field" : "'.$attribute.'",
                  "size": 1000
                }
            }
        }
        }
        ';

        $params['body'] = array_merge(json_decode($json_params, true), $params['body']);

        $response = $client->search($params);
        $buckets = $response['aggregations'][$attribute]['buckets'];

        $return_list = [];
        foreach ($buckets as $bucket) {
            $single_value = $bucket['key'];

            if ($single_value!=$this->filter_tag) {
                $return_list[] = $single_value;
            }
        }

        return $return_list;
    }

}

