<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 12/22/2016
 * Time: 12:55 PM
 */

namespace Webarq\Manager\Cms\HTML\Form;


use Webarq\Manager\HTML\FormManager;
use Webarq\Manager\setPropertyManagerTrait;

class InputManager
{
    use SetPropertyManagerTrait;

    /**
     * FormManager instance
     *
     * @var object FormManager
     */
    protected $form;

    /**
     * URL module segment
     *
     * @var string
     */
    protected $module;

    /**
     * URL panel segment
     *
     * @var string
     */
    protected $panel;

    /**
     * Input type
     *
     * @var string
     */
    protected $type;

    /**
     * Input attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Input information
     *
     * @var mixed
     */
    protected $info;

    /**
     * Input label
     *
     * @var string
     */
    protected $label;

    /**
     * Input name
     *
     * @var string
     */
    protected $name;

    /**
     * Input value
     *
     * @var null|mixed
     */
    protected $value;

    /**
     * Input permissions
     *
     * @var
     */
    protected $permissions = [];

    /**
     * Input rules
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Input container
     *
     * @var string
     */
    protected $container;

    /**
     * Input macros
     *
     * @var array
     */
    protected $macros = [];

    /**
     * Input is multilingual
     *
     * @var null|bool
     */
    protected $multilingual;

    /**
     * Input attribute keys that should be an array
     *
     * @var array
     */
    protected $couldBeArray = ['options'];

    /**
     * Create InputManager instance
     *
     * @param FormManager $form
     * @param string $module
     * @param string $panel
     * @param string $type
     * @param string $name
     * @param null $value
     * @param array $attributes
     */
    public function __construct(FormManager $form, $module, $panel, $type, $name, $value = null, array $attributes = [])
    {
        $this->form = $form;
        $this->module = $module;
        $this->panel = $panel;
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;

        $this->setPropertyFromOptions($attributes);

        $this->getRulesFromAttributes($attributes);

        $this->stringifyArrayAttributes($attributes);

        $this->shouldBeClassAttributes($attributes);

        $this->attributes = $attributes;
    }

    /**
     * Collect possible rules from input attributes
     *
     * @param array $attributes
     */
    protected function getRulesFromAttributes(array $attributes = [])
    {
        if (!is_array($this->rules)) {
            if (is_string($this->rules)) {
                $this->rules = ['laravel' => $this->rules];
            } else {
                $this->rules = [$this->rules];
            }
        }

        foreach (['length', 'notnull', 'required', 'numeric'] as $key) {
            if (null !== ($var = array_get($attributes, $key))) {
                $this->rules[$key] = $var;
            }
        }
    }

    /**
     * Any value other than the specified attributes key should not be an array
     *
     * @param array $attributes
     * @using $couldBeArray
     */
    protected function stringifyArrayAttributes(array &$attributes)
    {
        if ([] !== $attributes) {
            foreach ($attributes as $key => $value) {
                if (is_array($value) && !in_array($key, $this->couldBeArray)) {
                    $attributes[$key] = base64_encode(serialize($value));
                }
            }
        }
    }

    /**
     * Checking for class attributes item
     *
     * @param array $attributes
     */
    protected function shouldBeClassAttributes(array &$attributes)
    {
        if (!isset($attributes['class'])) {
            $attributes['class'] = '';
        }

        $elements = ['notnull' => 'required', 'required', 'numeric'];

        foreach ($elements as $key => $value) {
// For numeric keys assume key and value has the same value
            if (is_numeric($key)) {
                $key = $value;
            }

            if (null !== ($pull = array_pull($attributes, $key))) {
                $attributes['class'] .= ' ' . $value;
            }
        }
        $attributes['class'] = trim($attributes['class']);
    }

    /**
     * @return null|\Webarq\Manager\HTML\Form\InputManager
     */
    public function buildInput()
    {
        return $this->form->addCollection($this->type, $this->name, $this->value, $this->attributes);
    }

    /**
     * Magic method to get property value
     *
     * @param $key
     * @return null
     */
    public function __get($key)
    {
        return isset($this->{$key}) ? $this->{$key} : null;
    }
}