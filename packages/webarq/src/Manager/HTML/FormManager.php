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


use Form;
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
     * @var array
     */
    protected $attributes;

    /**
     * @var string
     */
    protected $container;

    /**
     * @var object Webarq\Manager\HTML\ContainerManager
     */
    protected $title;

    /**
     * @var array
     */
    protected $inputs = [];

    /**
     * @var array
     */
    protected $br = [];

    /**
     * @var
     */
    protected $elementLabelDefaultContainer;

    /**
     * @var
     */
    protected $elementInfoDefaultContainer;

    /**
     * @var
     */
    protected $submit;

    /**
     * @param null $action
     * @param array $attributes
     * @param string $container
     */
    public function __construct($action = null, $attributes = [], $container = 'div')
    {
        $this->attributes = ['url' => $action] + (is_array($attributes) ? $attributes : ['id' => $attributes]);

        $this->container = $container;
    }

    /**
     * @param $title
     * @param string $container
     * @param array $attr
     * @return $this
     */
    public function setTitle($title, $container = 'h3', array $attr = [])
    {
        $this->title = Wa::html('element', $title, $container, $attr);

        return $this;
    }

    public function setElementLabelDefaultContainer($container)
    {
        $this->elementLabelDefaultContainer = $container;

        return $this;
    }

    public function setElementInfoDefaultContainer($container)
    {
        $this->elementInfoDefaultContainer = $container;

        return $this;
    }

    public function addBr($tag = 'br')
    {
        if (!isset($this->br['title']) && isset($this->title)) {
            $this->br['title'] = new ElementManager('', $tag);
        } elseif (isset($this->inputs[0])) {
            $this->br[count($this->inputs) - 1] = new ElementManager('', $tag);
        }

        return $this;
    }

    /**
     * Add collections and display it as inline group
     * See addCollection, for arguments
     *
     * @param array $inputs
     */
    public function addCollectionGroup(array $inputs = [])
    {
        $inputs = func_get_args();
        foreach ($inputs as $i => &$input) {
            if (is_array($input[0])) {
                $input = $this->setInputManager(
                        $input[0], array_get($input, 1), array_get($input, 2), array_get($input, 3));
            } elseif (is_array($input)) {
                $input = $this->setInputManager($input);
            } else {
                $inputs['container'] = $input;
                unset($inputs[$i]);
            }
        }
        $this->inputs[] = $inputs;
    }

    /**
     * @param $args
     * @param null $label
     * @param null $info
     * @param null $container
     * @return InputManager
     */
    private function setInputManager($args, $label = null, $info = null, $container = null)
    {
        if (isset($container)) {

        }
// Initiate InputManager class
        $manager = new InputManager(
                $args, $label ? : ucwords(snake_case(camel_case(array_get($args, 1)), ' ')), $info,
                array_filter([
                        'input' => $container,
                        'label' => $this->elementLabelDefaultContainer,
                        'info' => $this->elementInfoDefaultContainer]));

        return $manager;
    }

    /**
     * Deliberately this method will call related laravel form builder method (eg. Form::text, Form::radio, etc)
     * following by it is original parameter
     *
     * Single element
     *
     * $f->addCollection(['text', 'codename', 'value'], 'Code Name', 'Get before i take it from you', 'div');
     * $f->addCollection('text', 'codename', 'value')
     *  ->setLabel('Code Name')
     *  ->setInfo('Get before i take it from you')
     *  ->setContainer('div);
     *
     * Multiple element
     * $f->addCollectionGroup(
     *  [['text', 'codename', 'value'], 'Code Name', 'Get before i take it from you', 'div']
     *  ['text', 'weapon', 'trident', function($ipt){
     *      $ipt->setLabel('Weapon')->setInfo('Select your weapon')
     *  }]
     * )
     *
     * @param mixed $args
     * @param mixed $label
     * @param mixed $info
     * @param mixed $container
     * @return InputManager|null
     */
    public function addCollection($args = [], $label = null, $info = null, $container = null)
    {
        $args = func_get_args();
        if (is_array($args[0])) {
            if (isset($label) && !is_array($label)) {
                return $this->inputs[] = $this->setInputManager($args[0], $label, $info, $container);
            } else {
                call_user_func_array(array($this, 'addCollectionGroup'), $args);
            }
        } else {
            return $this->inputs[] = $this->setInputManager($args);

        }
    }

    /**
     * Set submit
     *
     * @param submit
     * @param $this
     */

    public function submit($string)
    {
        $this->submit = $string;

        return $this;
    }

    public function toHtml()
    {
        $s = '';
        if ([] !== $this->inputs) {
            if (isset($this->title)) {
                $s .= $this->title->toHtml();
                if (null !== ($br = array_get($this->br, 'title'))) {
                    $s .= $br->toHtml();
                }
            }

            return (new ElementManager($s . $this->compile(), $this->container))->toHtml();
        } else {
            $s = config('webarq.system.configuration-error',
                    'Inputs not provided yet. Please support me by doing the right thing :)');
        }
        return $s;
    }

    private function compile()
    {
        $s = Form::open($this->attributes);
        foreach ($this->inputs as $i => $collection) {
            if (is_array($collection)) {
                $s .= $this->compileGroup($collection);
            } else {
                $s .= $collection->toHtml();
            }
            if (null !== ($br = array_get($this->br, $i))) {
                $s .= $br->toHtml();
            }
        }
        if (!isset($this->submit)) {
            $s .= Wa::html('element', Form::submit('Submit'))->toHtml();
        } else {
            $s .= $this->submit;
        }
        return $s . Form::close();
    }

    private function compileGroup(array $collections)
    {
        $s = '';
        $container = array_pull($collections, 'container');
        foreach ($collections as $collection) {
            $s .= $collection->toHtml();
        }

        return isset($container) ? Wa::element($s, $container, ['length' => count($collections)]) : $s;
    }
}
