<?php

/**
 * PHPUnit bootstrap.
 *
 * Credits: https://github.com/pug-php/pug-yii2#credits
 *
 * Forked from rmrevin/yii2-pug:
 *
 * @link https://github.com/rmrevin/yii2-pug/blob/master/tests/unit/bootstrap.php
 */
define('YII_ENABLE_ERROR_HANDLER', true);
define('YII_DEBUG', true);
$_SERVER['SCRIPT_NAME'] = '/' . __DIR__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

require_once __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require_once __DIR__ . '/../../vendor/autoload.php';

Yii::setAlias('@yiiunit', __DIR__);

require_once __DIR__ . '/TestCase.php';
