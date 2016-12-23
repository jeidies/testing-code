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

/**
 * Helper class
 *
 * Class PanelInfo
 * @package Webarq\Info
 */
class PanelInfo
{
    use SetPropertyManagerTrait;

    /**
     * Panel name
     * Used when generate panel anchor <a/> html tag
     *
     * @var
     */
    protected $name;

    /**
     * Panel permalink
     * Used when generate panel anchor <a/> html tag
     *
     * @var mixed
     */
    protected $permalink;

    /**
     * Panel label
     * Used when generate panel anchor <a/> html tag
     *
     * @var
     */
    protected $label;

    /**
     * Panel permitted actions
     * Used when generate panel button in listing
     *
     * @var array
     */
    protected $actions = [];

    /**
     * Panel attributes.
     * Used when generate panel anchor <a/> html tag
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Create PanelInfo instance
     *
     * @param $name
     * @param array $options
     */
    public function __construct($name, array $options)
    {
        $this->name = $name;

        $this->setPropertyFromOptions($options);

        $this->attributes = $options;
    }

    /**
     * Get panel name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get panel action
     *
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