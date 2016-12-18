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


class PanelInfo
{
    protected $options = [];

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function getAction($key, $default = [])
    {
        return $this->getOption('actions.' . $key, $default);
    }

    public function getOption($key, $default = null)
    {
        return array_get($this->options, $key, $default);
    }

    public function getList($default = [])
    {
        return $this->getOption('listing', $default);
    }
}