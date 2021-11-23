<?php
return [
    'key' => env('DATA_COLLECTOR_KEY'),
    'url' => 'http://docs.ronasit.com',
    'temporary_path' => env('REMOTE_DATA_COLLECTOR_TMP_PATH', '/tmp/documentation.json')
];
