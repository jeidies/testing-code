<?php
/**
 * Created by PhpStorm
 * Date: 18/12/2016
 * Time: 10:29
 * Author: Daniel Simangunsong
 *
 * Calm seas, never make skill full sailors
 */

namespace Webarq\Manager\Cms\HTML;


use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Wa;
use Webarq\Info\ColumnInfo;
use Webarq\Manager\setPropertyManagerTrait;

/**
 * Panel form generator
 *
 * Generate form based on configuration module files
 *
 * Class FormManager
 * @package Webarq\Manager\Cms\HTML
 */
class FormManager implements Htmlable
{
    use SetPropertyManagerTrait;

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
     * Form type, create|edit
     *
     * @var string
     */
    protected $type;

    /**
     * Form method type, post|edit
     *
     * @var string
     */
    protected $method;

    /**
     * Form title
     *
     * @var string
     */
    protected $title;

    /**
     * Form action
     *
     * @var string
     */
    protected $action;

    /**
     * Form attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Transaction pairs, used on insert|update processing
     *
     * @var array
     */
    protected $pairs = [];

    /**
     * Form inputs collection
     *
     * @var array
     */
    protected $inputs = [];

    /**
     * Form builder
     *
     * @var object Webarq\Manager\HTML\FormManager
     */
    protected $builder;

    /**
     * Form view template
     *
     * @var string
     */
    protected $view = 'webarq.form.cms.index';

    /**
     * Input rules
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Input error message when value is not match
     *
     * @var array
     */
    protected $errorMessage = [];

    /**
     * Master table name
     *
     * @var string
     */
    protected $master;

    /**
     * @var
     */
    protected $multilingual;

    /**
     * @param string $module
     * @param string $panel
     * @param string $type
     * @param array $options
     */
    public function __construct($module, $panel, $type, array $options)
    {
        $this->module = $module;
        $this->panel = $panel;
        $this->type = $type;
        $this->compileOptions($options);
        $this->builder = new \Webarq\Manager\HTML\FormManager($this->finalizeAction(), $this->attributes);
    }

    /**
     * Extract options in to class property
     *
     * @param array $options
     */
    protected function compileOptions(array $options)
    {
        $this->master = array_pull($options, 'master');

        $this->setPropertyFromOptions($options);

        if ([] !== $options) {
            foreach ($options as $key => $value) {
                if (is_numeric($key)) {
// Multiple inputs in one container
                    if (is_array($value)) {
                        $group = [];
                        foreach ($value as $key1 => $value1) {
                            $group[$key1] = $value1;
                        }
                        $this->inputs[count($this->inputs) + 1] = $group;
                    } else {
                        $this->inputs[$value] = [];
                    }
                } elseif (str_contains($key, '.')) {
                    $this->inputs[$key] = $value;
                } else {
                    $this->attributes[$key] = $value;
                }
            }
        }
    }

    /**
     * Build form url action
     *
     * @return mixed
     */
    protected function finalizeAction()
    {
        return \URL::panel(\URL::detect($this->action, $this->module, $this->panel, 'form/' . $this->type));
    }

    /**
     * Compile form
     *
     * @return $this
     */
    public function compile()
    {
        $this->compileInputs();

        return $this;
    }

    /**
     * Add collections in to $builder by compiling $inputs property
     */
    protected function compileInputs()
    {
        if ([] !== $this->inputs) {
            foreach ($this->inputs as $key => $input) {
// Add collection group
                if (is_numeric($key)) {

                } else {
                    $this->buildInput($key, $this->getColumnInfo($key), $input);
                }
            }
        }
    }

    /**
     * Input builder
     *
     * @param string $path
     * @param ColumnInfo $column
     * @param array $settings
     */
    protected function buildInput($path, ColumnInfo $column, array $settings = [])
    {
// Input attributes
        $attr = $column->getInputAttribute();

// Merge with new setting
        if ([] !== $settings) {
            $attr = Arr::merge($attr, $settings);
        }

// Attribute type and name should not be empty
        if (null === ($type = array_pull($attr, 'type')) || null === ($name = array_pull($attr, 'name'))) {
            abort('405', config('webarq.system.error-message.configuration'));
        }

// This is could be pain on the process, but due to laravel input form method behaviour is different
// one from another, we need class helper to enable us adding collection with proper arguments
// @todo Build own form builder to simplify the logic
        $class = Wa::normalizeClass('manager.cms.HTML!.form.input.' . $type . ' input');
        if (class_exists($class)) {
            $input = Wa::load(false, $class, $this->builder, $this->module, $this->panel,
                    $type, $name, array_pull($attr, 'value'), $attr);
        } else {
            $input = Wa::load('manager.cms.HTML!.form.input',
                    $this->builder, $this->module, $this->panel, $type, $name, array_pull($attr, 'value'), $attr);
        }

// Check for input permissions
        if ([] === $input->permissions || \Auth::user()->hasPermission(
                        Wa::formatPermissions($input->permissions, $this->module, $this->panel))
        ) {
            $this->pairs[$name] = $path;

            if ([] !== $input->rules) {
                $this->rules[$name] = $input->rules;
            }

            if ([] !== $input->errorMessage) {
                foreach ($input->errorMessage as $validationType => $message) {
                    $this->errorMessage[$name . '.' . $validationType] = $message;
                }
            }

            $input->buildInput();
        }
    }

    /**
     * Get column information
     *
     * @param $column
     * @return mixed
     */
    protected function getColumnInfo($column)
    {
        list($module, $table, $column) = explode('.', $column);

        return Wa::module($module)->getTable($table)->getColumn($column);
    }

    /**
     * Set form inputs
     *
     * While inputs already exist and options not empty,
     * merge inputs options with the previous one
     *
     * @param $name
     * @param array $options
     * @return $this
     */
    public function setInput($name, array $options = [])
    {
        if (isset($this->inputs[$name]) && [] !== $options) {
            $this->inputs[$name] = Arr::merge($this->inputs[$name], $options);
        } else {
            $this->inputs[$name] = $options;
        }

        return $this;
    }

    /**
     * Convert $builder into well formatted HTML element
     *
     * @return string
     * @throws \Exception
     * @throws \Throwable
     */
    public function toHtml()
    {
        if (isset($this->title)) {
            $this->builder->setTitle($this->title);
        }

        return view($this->view, ['html' => $this->builder->toHtml()])->render();
    }

    /**
     * Get form pairs
     *
     * @return array
     */
    public function getPairs()
    {
        return $this->pairs;
    }

    /**
     * Get form input rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Get form input error messages
     *
     * @return array
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Get form inputs
     *
     * @return array
     */
    public function getInputs()
    {
        return $this->inputs;
    }

    /**
     * Get form master
     *
     * @return string
     */
    public function getMaster()
    {
        return $this->master;
    }
}