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
use Wa;
use Webarq\Info\ColumnInfo;

class FormManager implements Htmlable
{
    protected $action;

    protected $attributes;

    protected $pairs;

    protected $inputs;

    public function __construct(array $options)
    {
        $this->setup($options);
    }

    protected function setup(array $options)
    {
        if ([] !== $options) {
            foreach ($options as $key => $value) {
                if (is_numeric($key)) {
                    $this->setupNumericColumns($value);
                } elseif (str_contains($key, '.')) {
                    $this->setColumns($this->getColumnInfo($key), $value);
                } else {

                }
            }
        }
    }

    protected function setupNumericColumns($column)
    {
        $this->setColumns($this->getColumnInfo($column));
    }

    protected function setColumns(ColumnInfo $column, array $settings = [])
    {

    }

    protected function getColumnInfo($column)
    {
        list($module, $table, $column) = explode('.', $column);
        return Wa::module($module)->getTable($table)->getColumn($column);
    }

    public function toHtml()
    {
        return '';
    }
}