<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 12/8/2016
 * Time: 4:37 PM
 */

namespace Webarq\Manager\HTML\Form;


use Illuminate\Contracts\Support\Htmlable;
use Webarq\Manager\HTML\ElementManager;

class InputManager implements Htmlable
{
    protected $input;

    /**
     * @var object ContainerManager
     */
    protected $label;

    /**
     * @var object ContainerManager
     */
    protected $info;

    protected $container = '<div class="form-group"></div>';

    public function __construct(array $args)
    {
        foreach ($args as $i => $arg) {
            if (is_callable($arg)) {
                $arg($this);
                unset($args[$i]);
            }
        }
//Shift input type
        $type = array_shift($args);
// Call laravel form type method
        $this->input = call_user_func_array(array(app('form'), $type), $args);
    }

    /**
     * Decoration function.
     * Set container decoration
     *
     * @param $value
     * @return InputManager
     */
    public function setContainer($value)
    {
        $this->container = $value;

        return $this;
    }

    /**
     * Decoration function.
     * Set label decoration
     *
     * @param mixed $value
     * @param string $container Html tag name or full html tag (with any attributes)
     * @return InputManager
     */
    public function setLabel($value, $container = '<label class="control-label"></label>')
    {
        $this->label = new ElementManager($value, $container);

        return $this;
    }

    /**
     * Decoration function.
     * Set info decoration
     *
     * @param mixed $value
     * @param string $container Html tag name or full html tag (with any attributes)
     * @return InputManager
     */
    public function setInfo($value, $container = '<small id="fileHelp" class="form-text text-muted"></small>')
    {
        $this->info = new ElementManager($value, $container);

        return $this;
    }

    public function toHtml()
    {
        $str = '';
        if (isset($this->label) && $this->label instanceof ElementManager) {
            $str .= $this->label->toHtml();
        }
        $str .= $this->input->toHtml();
        if (isset($this->info) && $this->info instanceof ElementManager) {
            $str .= $this->info->toHtml();
        }
        return (new ElementManager($str, $this->container))->toHtml();
    }
}