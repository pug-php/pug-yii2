<?php
/**
 * EscapedFilter.php
 * @author Revin Roman
 * @link https://rmrevin.com
 */

namespace Pug\Yii\Tests\Filters;

use Pug\Filter\AbstractFilter;

/**
 * Class EscapedFilter
 */
class EscapedFilter extends AbstractFilter
{
    public function parse($code)
    {
        return htmlentities($code);
    }
}
