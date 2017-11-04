<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 26.08.16
 * Time: 11:49
 */

return [
    'route' => '/',
    'info' => [
        'description' => 'swagger-description',
        'version' => '0.0.0',
        'title' => 'Name of Your Application',
        'termsOfService' => '',
        'contact' => [
            'email' => 'your@email.com'
        ],
        'license' => [
            'name' => '',
            'url' => ''
        ]
    ],
    'swagger' => [
        'version' => '2.0'
    ],
    'basePath' => '/',
    'schemes' => [],
    'definitions' => [],
    'security' => '',
    'defaults' => [
        'code-descriptions' => [
            '200' => 'Operation successfully done',
            '204' => 'Operation successfully done',
            '404' => 'This entity not found'
        ]
    ],
    'data_collector' => \RonasIT\Support\DataCollectors\RemoteDataCollector::class
];
