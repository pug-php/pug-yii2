<?php
/**
 * main.php
 * @author Roman Revin http://phptime.ru
 */

$baseDir = realpath(__DIR__ . '/..');
define('UES_TALE', class_exists('Tale\\Pug\\Renderer'));
$renderer = UES_TALE ? 'Tale\\Pug\\Renderer' : 'Pug\\Pug';
$filters = [];
if (!UES_TALE) {
    file_put_contents($baseDir . '/views/main.pug', str_replace(
        'variable',
        '$variable',
        file_get_contents($baseDir . '/views/main.pug')
    ));
}
if (!UES_TALE) {
    include_once $baseDir . '/filters/EscapedFilter.php';
    $filters = [
        'escaped' => 'Pug\\Yii\\Tests\\Filters\\EscapedFilter',
    ];
}

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
                    'filters' => $filters,
                ],
            ],
        ],
    ],
];
