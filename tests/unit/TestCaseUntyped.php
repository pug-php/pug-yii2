<?php

namespace Phug\Util;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

// @codeCoverageIgnoreStart
if (!isset($testCaseInitialization) || !class_exists('Pug\\Yii\\Tests\\TestCaseTypeBase', false)) {
    return;
}

class TestCaseTypeBase extends PHPUnitTestCase
{
    protected function prepareTest()
    {
        // Override
    }

    protected function finishTest()
    {
        // Override
    }

    protected function setUp()
    {
        $this->prepareTest();
    }

    protected function tearDown()
    {
        $this->finishTest();
    }
}
// @codeCoverageIgnoreEnd
