<?php
/**
 * Created by PhpStorm
 * Date: 05/12/2016
 * Time: 10:00
 * Author: Daniel Simangunsong
 *
 * Calm seas, never make skill full sailors
 */

namespace Webarq\Manager\HTML;


use Illuminate\Contracts\Support\Htmlable;
use Wa;
use Webarq\Manager\HTML\Form\InputManager;

/**
 * Class FormManager
 * @package Webarq\Manager\HTML
 */
class FormManager implements Htmlable
{
    /**
     * @var object Webarq\Manager\HTML\ContainerManager
     */
    protected $title;

    /**
     * @var array
     */
    protected $inputs = [];

    public function __construct($action = null, $attributes = [], $container = 'div')
    {

    }

    /**
     * @param $title
     * @param string $container
     * @param array $attr
     */
    public function setTitle($title, $container = 'h3', array $attr = [])
    {
        $this->title = Wa::html('element', $title, $container, $attr);
    }

    /**
     * Add collections and display it as inline group
     * See addCollection, for arguments
     *
     * @param array|callback $inputs
     * @return FormManager
     */
    public function addCollectionGroup($inputs = [])
    {
        if (is_callable($inputs)) {
            abort(500, 'Not implemented yet. Not to sure if we need this way of programming');
//            $inputs($c = new self());
//            $this->inputs[] = $c->inputs;
        } else {
            $inputs = func_get_args();
            foreach ($inputs as &$input) {
                $input = new InputManager($input);
            }

            $this->inputs[] = $inputs;
        }

        return $this;
    }

    /**
     * Deliberately this method will call related laravel form builder method (eg. Form::text, Form::radio, etc)
     * following by it is original parameter
     *
     * To change input decoration such as container, label, etc, add a callback argument (to make it nice just put
     * it as last arguments).
     *
     * @param mixed $args
     * @return InputManager
     */
    public function addCollection($args = [])
    {
        $args = func_get_args();
        if (is_array($args[0])) {
            call_user_func_array(array($this,'addCollectionGroup'), $args);
        } else {
            return $this->inputs[] = new InputManager($args);
        }

        return $this;
    }

    public function toHtml()
    {
        if ([] !== $this->inputs) {
            $s = '';
            foreach ($this->inputs as $collection) {
                if (is_array($collection)) {
                    foreach ($collection as $sub) {
                        $s .= $sub->toHtml();
                    }
                } else {
                    $s .= $collection->toHtml();
                }
            }
            return $s;
        }
    }
}
