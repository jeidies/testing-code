<?php
/**
 * Created by PhpStorm
 * Date: 05/12/2016
 * Time: 10:21
 * Author: Daniel Simangunsong
 *
 * Calm seas, never make skill full sailors
 */

namespace Webarq\Manager\HTML;


use Html;
use Illuminate\Contracts\Support\Htmlable;

class ElementManager implements Htmlable
{
    /**
     * @var string
     */
    protected $html = '';

    protected $node;

    protected $htmlContainers = ['h1', 'h2', 'h3', 'h4', 'h5', 'div', 'p', 'span'];

    /**
     * Hint.
     * - Use dot notation for simple nested tag
     * - Starts with : (colon) to use a view file
     *
     * @param $html
     * @param string $container Html tag name or full html tag (with any attributes)
     * @param array $attr
     */
    public function __construct($html, $container = 'div', array $attr = [])
    {
        $this->wrapHtml($html, $container, $attr);
    }

    protected function wrapHtml($html, $container, array $attr)
    {
        if (starts_with($container, ':')) {
            $this->html = view(substr($container, 1), ['html' => $html] + $attr)->render();
        } elseif(false !== ($midPoint = strpos($container, '></'))) {
            $midPoint++;
            $this->html = substr($container, 0, $midPoint) . $html . substr($container, $midPoint);
        } else {
            $this->html = '<' . $container . Html::attributes($attr) . '>' . $html . '</' . $container . '>';
        }
    }

    public function toHtml()
    {
        return $this->html;
    }
}