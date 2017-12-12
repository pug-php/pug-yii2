<?php
/**
 * Config for unit tests.
 *
 * Credits: https://github.com/pug-php/pug-yii2#credits
 * Forked from:
 * @link https://github.com/rmrevin/yii2-pug/blob/master/tests/unit/config/main.php
 */

$baseDir = realpath(__DIR__ . '/..');
define('UES_TALE', class_exists('Tale\\Pug\\Renderer'));
$renderer = UES_TALE ? 'Tale\\Pug\\Renderer' : 'Pug\\Pug';
$filters = [];
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
