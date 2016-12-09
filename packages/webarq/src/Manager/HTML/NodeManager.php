<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 12/9/2016
 * Time: 1:18 PM
 */

namespace Webarq\Manager\HTML;


use Illuminate\Contracts\Support\Htmlable;

class NodeManager implements Htmlable
{
    protected $node;

    public function __construct($node)
    {
        $node = '<div class="some-thing">:html</div>';
        if (starts_with($node, ':')) {
// Using a view instead of pure HTML element tag
            $this->node = view($node);
        } elseif (false !== strpos($node, '.')) {
// Simple nested node
            $this->node = explode('.', $node);
        } elseif (false !== strpos($node, '></')) {
// Full HTML element tag
            $this->node = '';
        } else {
            $this->node = [$node];
        }
    }

    public function toHtml()
    {

    }

}