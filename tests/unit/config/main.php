<?php
/**
 * main.php
 * @author Roman Revin http://phptime.ru
 */

$baseDir = realpath(__DIR__ . '/..');
include_once $baseDir . '/filters/EscapedFilter.php';

$renderer = class_exists('Tale\\Pug\\Renderer') ? 'Tale\\Pug\\Renderer' : 'Pug\\Pug';

return [
    'id' => 'testapp',
    'basePath' => $baseDir,
    'aliases' => [
        '@app' => $baseDir,
        '@runtime' => $baseDir . '/runtime',
    ],
    'components' => [
        'view' => [
            'renderers' => [
                'pug' => [
                    'class' => 'Pug\\Yii\\ViewRenderer',
                    'renderer' => $renderer,
                    'filters' => [
                        'escaped' => 'Pug\\Yii\\Tests\\Filters\\EscapedFilter',
                    ],
                ],
            ],
        ],
    ],
];
