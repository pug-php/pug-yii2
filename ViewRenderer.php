<?php

/**
 * Class ViewRenderer handle pug templates.
 *
 * Credits: https://github.com/pug-php/pug-yii2#credits
 *
 * Forked from rmrevin/yii2-pug:
 *
 * @link https://github.com/rmrevin/yii2-pug/blob/master/ViewRenderer.php
 */

namespace Pug\Yii;

use Pug\Pug;
use Yii;
use yii\base\View;
use yii\base\ViewRenderer as YiiViewRenderer;
use yii\helpers\FileHelper;

class ViewRenderer extends YiiViewRenderer
{
    /**
     * Directory or path alias pointing to where Pug cache will be stored. Set to `false` to
     * disable templates cache.
     *
     * @var string|false
     */
    public $cachePath = '@runtime/pug/cache';

    /**
     * Directory or path alias pointing to where Pug templates are located.
     *
     * @var string
     */
    public $viewPath = '@app/views';

    /**
     * Variable name for system data like `$app` and `$view`, let null to get those data as locals
     * without namespace.
     *
     * @var string|null
     */
    public $systemVariable = null;

    /**
     * Pug options.
     *
     * @var array
     *
     * @see https://www.phug-lang.com/#options
     */
    public $options = [
        'prettyprint'   => false,
        'extension'     => '.pug',
        'upToDateCheck' => true,
    ];

    /**
     * Custom filters.
     * Keys of the array are names to call in template, values are names of functions or static methods
     * of some class.
     * Example: `['rot13' => 'str_rot13', 'jsonEncode' => '\yii\helpers\Json::encode']`.
     * In the template you can use it like this: `{{ 'test'|rot13 }}` or `{{ model|jsonEncode }}`.
     *
     * @var array
     */
    public $filters = [];

    /**
     * Pug renderer class name.
     *
     * @var string
     */
    public $renderer;

    /**
     * Pug engine object that renders pug templates
     *
     * @var Pug
     */
    public $pug;

    /**
     * Add custom filters from ViewRenderer `filters` parameter.
     */
    protected function initFilters()
    {
        if (!empty($this->filters)) {
            foreach ($this->filters as $name => $handler) {
                $this->addFilter($name, $handler);
            }
        }
    }

    /**
     * Create if needed and return the cache path calculated from ViewRenderer `cachePath` parameter.
     *
     * @throws \yii\base\Exception
     *
     * @return bool|string
     */
    protected function initCachePath()
    {
        $cachePath = empty($this->cachePath) ? false : Yii::getAlias($this->cachePath);

        if (!empty($cachePath) && !file_exists($cachePath)) {
            FileHelper::createDirectory($cachePath);
        }

        return $cachePath;
    }

    /**
     * Create the pug renderer engine.
     *
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function init()
    {
        $cachePath = $this->initCachePath();

        // @codeCoverageIgnoreStart
        if (!empty($cachePath) && !is_readable($cachePath)) {
            throw new Exception(Yii::t('app', 'Pug cache path is not readable.'));
        }

        if (!empty($cachePath) && !is_writable($cachePath)) {
            throw new Exception(Yii::t('app', 'Pug cache path is not writable.'));
        }
        // @codeCoverageIgnoreEnd

        $className = empty($this->renderer) ? 'Pug\\Pug' : $this->renderer;
        $baseDir = realpath(Yii::getAlias($this->viewPath));
        $this->pug = new $className(array_merge([
            'cache'      => $cachePath, // pug-php 2
            'cache_dir'  => $cachePath, // phug / pug-php 3
            'cache_path' => $cachePath, // tale-pug
            'basedir'    => $baseDir,   // pug-php 2
            'paths'      => [$baseDir], // phug / pug-php 3
        ], $this->options));

        $this->initFilters();
    }

    /**
     * Renders a view file.
     *
     * This method is invoked by [[View]] whenever it tries to render a view.
     * Child classes must implement this method to render the given view file.
     *
     * @param View   $view   the view object used for rendering the file.
     * @param string $file   the view file.
     * @param array  $params the parameters to be passed to the view file.
     *
     * @return string the rendering result
     */
    public function render($view, $file, $params)
    {
        $method = method_exists($this->pug, 'renderFile')
            ? [$this->pug, 'renderFile']
            : [$this->pug, 'render'];
        // @codeCoverageIgnoreStart
        if ($this->pug instanceof \Tale\Pug\Renderer && !($this->pug instanceof \Phug\Renderer)) {
            $this->pug->compile(''); // Init ->files
            $path = realpath(Yii::getAlias($this->viewPath));
            $pieces = explode($path, realpath($file), 2);
            if (count($pieces) === 2) {
                $file = ltrim($pieces[1], '\\/');
            }
        }
        // @codeCoverageIgnoreEnd

        $systemVariables = [
            'app'  => Yii::$app,
            'view' => $view,
        ];
        if ($this->systemVariable) {
            $systemVariables = [
                $this->systemVariable => (object) $systemVariables,
            ];
        }

        return call_user_func($method, $file, array_merge($systemVariables, $params));
    }

    /**
     * Adds custom filter.
     *
     * @param string   $name
     * @param callable $handler
     */
    public function addFilter($name, $handler)
    {
        if (is_string($handler) && !is_callable($handler)) {
            $handler = new $handler();
        }

        $this->pug->filter($name, $handler);
    }
}
