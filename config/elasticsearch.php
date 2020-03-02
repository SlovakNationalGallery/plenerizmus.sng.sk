<?php

return [


    'index' => env('ES_INDEX', 'webumenia_sk'),
    'hosts' => [
        sprintf('http://%s:%d', env('ES_HOST'), env('ES_PORT'))
    ],

];
