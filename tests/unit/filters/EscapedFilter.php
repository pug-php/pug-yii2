<?php

/**
 * Class EscapedFilter, basic filter to escape HTML entities.
 *
 * Credits: https://github.com/pug-php/pug-yii2#credits
 *
 * Forked from rmrevin/yii2-pug:
 *
 * @link https://github.com/rmrevin/yii2-pug/blob/master/tests/unit/filters/EscapedFilter.php
 */

namespace Pug\Yii\Tests\Filters;

use Pug\Filter\AbstractFilter;

class EscapedFilter extends AbstractFilter
{
    public function parse($code)
    {
        return htmlentities($code);
    }
}
