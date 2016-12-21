<?php
/**
 * Created by PhpStorm
 * Date: 18/12/2016
 * Time: 11:03
 * Author: Daniel Simangunsong
 *
 * Calm seas, never make skill full sailors
 */

namespace Webarq\Info;


use Webarq\Manager\setPropertyManagerTrait;

class PanelInfo
{
    use setPropertyManagerTrait;

    protected $permalink;

    protected $label;

    protected $actions = [];

    protected $name;

    protected $attributes = [];

    public function __construct($name, array $options)
    {
        $this->name = $name;
// Pull out property value from options
        $this->setup($options);
// Rest options will be store in attributes
        $this->attributes = $options;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function getAction($key, $default = null)
    {
        return array_get($this->actions, $key, $default);
    }

    /**
     * Get panel label, if empty return panel name
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label ? : $this->name;
    }
}