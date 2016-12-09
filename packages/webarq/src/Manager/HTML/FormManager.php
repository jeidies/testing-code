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
    protected $collections = [];

    public function __construct($action, $attributes = [], $container = 'div')
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
     * @param array $inputs
     * @return FormManager
     */
    public function addCollectionGroup(array $inputs)
    {
        $inputs = func_get_args();
        foreach ($inputs as &$input) {
            $input = Wa::html('form.input',$input);
        }

        $this->collections[] = $inputs;

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
     * @return FormManager
     */
    public function addCollection($args = [])
    {
        $args = func_get_args();
        if (is_array($args[0])) {
            call_user_func_array(array($this,'addCollectionGroup'), $args);
        } else {
            $this->collections[] = Wa::html('form.input', $args);
        }

        return $this;
    }

    public function toHtml()
    {
        if ([] !== $this->collections) {
            $s = '';
            foreach ($this->collections as $collection) {
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
