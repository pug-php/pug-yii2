<?php

namespace Pug\Yii\Tests;

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

    protected function setUp(): void
    {
        $this->prepareTest();
    }

    protected function tearDown(): void
    {
        $this->finishTest();
    }
}
// @codeCoverageIgnoreEnd
