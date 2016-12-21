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

class FormManager implements Htmlable
{
    use setPropertyManagerTrait;

    /**
     * @var string
     */
    protected $module;

    /**
     * @var string
     */
    protected $panel;

    /**
     * Transaction type, create or edit
     *
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $pairs = [];

    /**
     * @var array
     */
    protected $inputs = [];

    /**
     * @var \Webarq\Manager\HTML\FormManager
     */
    protected $builder;

    /**
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
     * Master table name
     *
     * @var string
     */
    protected $master;

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
     * Compile rest options in to proper property
     *
     * @param array $options
     */
    protected function compileOptions(array $options)
    {
        $this->master = array_pull($options, 'master');
        $this->setup($options);
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
     * @return mixed
     */
    protected function finalizeAction()
    {
        return \URL::panel(\URL::detect($this->action, $this->module, $this->panel, 'form/' . $this->type));
    }

    /**
     * Finalize form configuration
     *
     * @return $this
     */
    public function finalize()
    {
        $this->compileInputs();

        return $this;
    }

    /**
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

    public function toHtml()
    {
        if (isset($this->title)) {
            $this->builder->setTitle($this->title);
        }

        return view($this->view, ['html' => $this->builder->toHtml()])->render();
    }

    protected function compileInputs()
    {
        if ([] !== $this->inputs) {
            foreach ($this->inputs as $key => $input) {
// Add collection group
                if (is_numeric($key)) {

                } else {
                    $this->buildColumn($key, $this->getColumnInfo($key), $input);
                }
            }
        }
    }

    /**
     * @param string $path
     * @param ColumnInfo $column
     * @param array $settings
     */
    protected function buildColumn($path, ColumnInfo $column, array $settings = [])
    {
// Re initiate ColumnInfo $column while setting is not empty
        if ([] !== $settings) {
            $column = Wa::load('info.column', Arr::merge($column->unserialize(), ['form' => $settings]));
        }
// Input attributes
        $attr = $column->getInputAttribute();
// Attribute type and name should not be empty
        if (null === ($type = array_pull($attr, 'type')) || null === ($name = array_pull($attr, 'name'))) {
            abort('405', config('webarq.system.error-message.configuration'));
        }
// Set pairs
        array_set($this->pairs, $path, $name);
// Set rules
        $this->rules[$name] = array_pull($attr, 'rules');
// Add input into builder
        $class = Wa::normalizeClass('manager.cms.HTML!.form.input.' . $type . ' input');
        if (class_exists($class)) {
            Wa::load(false, $class, $this->builder, $type, $name, array_pull($attr, 'value'), $attr);
        } else {
            Wa::load('manager.cms.HTML!.form.input',
                    $this->builder, $type, $name, array_pull($attr, 'value'), $attr);
        }
    }

    /**
     * @param $column
     * @return mixed
     */
    protected function getColumnInfo($column)
    {
        list($module, $table, $column) = explode('.', $column);

        return Wa::module($module)->getTable($table)->getColumn($column);
    }

    /**
     * Get input pairs
     *
     * @return array
     */
    public function getPairs()
    {
        return $this->pairs;
    }

    /**
     * Get input rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
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
     * @return string
     */
    public function getMaster()
    {
        return $this->master;
    }
}