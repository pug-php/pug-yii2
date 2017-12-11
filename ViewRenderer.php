<?php
/**
 * View.php
 * @author Revin Roman
 * @link https://rmrevin.com
 */

namespace Pug\Yii;

use Pug\Pug;
use Yii;
use yii\base\View;
use yii\helpers\FileHelper;

/**
 * Class ViewRenderer
 * @package rmrevin\yii\pug
 */
class ViewRenderer extends \yii\base\ViewRenderer
{
    /**
     * @var string the directory or path alias pointing to where Pug cache will be stored. Set to false to disable
     * templates cache.
     */
    public $cachePath = '@runtime/pug/cache';

    /**
     * @var array Pug options.
     * @see https://github.com/pug-php/pug
     */
    public $options = [
        'prettyprint' => false,
        'extension' => '.pug',
        'upToDateCheck' => true,
    ];

    /**
     * @var array Custom filters.
     * Keys of the array are names to call in template, values are names of functions or static methods of some class.
     * Example: `['rot13' => 'str_rot13', 'jsonEncode' => '\yii\helpers\Json::encode']`.
     * In the template you can use it like this: `{{ 'test'|rot13 }}` or `{{ model|jsonEncode }}`.
     */
    public $filters = [];

    /**
     * @var string pug renderer class name
     */
    public $renderer;

    /**
     * @var Pug pug environment object that renders pug templates
     */
    public $pug;

    public function init()
    {
        $cachePath = empty($this->cachePath) ? false : Yii::getAlias($this->cachePath);

        if (!empty($cachePath) && !file_exists($cachePath)) {
            FileHelper::createDirectory($cachePath);
        }

        // @codeCoverageIgnoreStart
        if (!empty($cachePath) && !is_readable($cachePath)) {
            throw new Exception(Yii::t('app', 'Pug cache path is not readable.'));
        }

        if (!empty($cachePath) && !is_writeable($cachePath)) {
            throw new Exception(Yii::t('app', 'Pug cache path is not writable.'));
        }
        // @codeCoverageIgnoreEnd

        $className = empty($this->renderer) ? 'Pug\\Pug' : $this->renderer;
        $this->pug = new $className(array_merge([
            'cache'     => $cachePath,
            'cache_dir' => $cachePath,
        ], $this->options));

        // Adding custom filters
        if (!empty($this->filters)) {
            foreach ($this->filters as $name => $handler) {
                if (is_string($handler) && !is_callable($handler)) {
                    $handler = new $handler();
                }
                $this->addFilter($name, $handler);
            }
        }
    }

    /**
     * Renders a view file.
     *
     * This method is invoked by [[View]] whenever it tries to render a view.
     * Child classes must implement this method to render the given view file.
     *
     * @param View $view the view object used for rendering the file.
     * @param string $file the view file.
     * @param array $params the parameters to be passed to the view file.
     *
     * @return string the rendering result
     */
    public function render($view, $file, $params)
    {
        $method = method_exists($this->pug, 'renderFile')
            ? [$this->pug, 'renderFile']
            : [$this->pug, 'render'];

        return call_user_func($method, $file, $params);
    }

    /**
     * Adds custom filter
     * @param string $name
     * @param callable $handler
     */
    public function addFilter($name, $handler)
    {
        $this->pug->filter($name, $handler);
    }
}
