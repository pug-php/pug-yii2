<?php

/**
 * Class MainTest, main rendering tests.
 *
 * Credits: https://github.com/pug-php/pug-yii2#credits
 *
 * Forked from rmrevin/yii2-pug:
 *
 * @link https://github.com/rmrevin/yii2-pug/blob/master/tests/unit/renderer/MainTest.php
 */

namespace Pug\Yii\Tests\Renderer;

use yii\helpers\FileHelper;

class MainTest extends \Pug\Yii\Tests\TestCase
{
    public function finishTest()
    {
        $cachePath = $this->getCachePath();

        FileHelper::removeDirectory($cachePath);
    }

    public function testMain()
    {
        $view = $this->getView();

        $result = $view->renderFile('@app/views/main.pug');

        self::assertSame('<div class="test-block"><p>Hello world</p><p>This is a test.</p></div>', $result);

        $this->checkAndRemoveCachePath(1);
    }

    public function testSystemVariable()
    {
        $view = $this->getView();
        $this->getPugRenderer()->systemVariable = 'system';

        $result = $view->renderFile('@app/views/sys-vars.pug');

        self::assertSame('<p>php</p>', $result);

        $this->checkAndRemoveCachePath(1);
    }

    public function testExtends()
    {
        $view = $this->getView();

        $result = $view->renderFile('@app/views/extend.pug');

        self::assertSame('<div class="test-block"><p>Hello world</p><p>This is a test.</p><p>this is additional</p></div>', $result);

        $this->checkAndRemoveCachePath(1);
    }

    public function testFilters()
    {
        if (UES_TALE) {
            self::markTestSkipped('Skip filters tests for Tale-pug');
        }

        $view = $this->getView();
        $pug = $this->getPugRenderer();

        $pug->addFilter('strip_tags', function ($node, $compiler) {
            if (is_string($node)) {
                return strip_tags($node);
            }

            $output = [];

            foreach ($node->block->nodes as $line) {
                $output[] = $compiler->interpolate($line->value);
            }

            return strip_tags(implode("\n", $output));
        });

        $result = $view->renderFile('@app/views/filters.pug');

        self::assertSame(
            '<style type="text/css">p { font-size: 1rem; color: black; }</style><div>html string</div><div>&lt;p&gt;html string&lt;/p&gt;</div>',
            str_replace(["\n", "\r"], '', $result)
        );

        $this->checkAndRemoveCachePath(1);
    }

    protected function checkAndRemoveCachePath($count)
    {
        $cachePath = $this->getCachePath();
        $files = array_filter(FileHelper::findFiles($cachePath), function ($file) {
            return !preg_match('/\.imports\.serialize\.txt$/', $file);
        });

        self::assertCount($count, $files);

        FileHelper::removeDirectory($cachePath);
    }

    protected function getCachePath()
    {
        $pug = $this->getPugRenderer();

        return \Yii::getAlias($pug->cachePath);
    }
}
