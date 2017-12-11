<?php
/**
 * MainTest.php
 * @author Revin Roman
 * @link https://rmrevin.ru
 */

namespace Pug\Yii\Tests\Renderer;

use yii\helpers\FileHelper;

/**
 * Class MainTest
 */
class MainTest extends \Pug\Yii\Tests\TestCase
{
    public function tearDown()
    {
        parent::tearDown();

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

    public function testExtends()
    {
        $view = $this->getView();

        $result = $view->renderFile('@app/views/extend.pug');

        self::assertSame('<div class="test-block"><p>Hello world</p><p>This is a test.</p><p>this is additional</p></div>', $result);

        $this->checkAndRemoveCachePath(1);
    }

    public function testFilters()
    {
        $view = $this->getView();
        $pug = $this->getPugRenderer();

        $pug->addFilter('strip_tags', function ($code) {
            return strip_tags($code);
        });

        $result = $view->renderFile('@app/views/filters.pug');

        self::assertSame(
            "<style type=\"text/css\">p { font-size: 1rem; color: black; }</style><div>html string</div><div>&lt;p&gt;html string&lt;/p&gt;</div>",
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
