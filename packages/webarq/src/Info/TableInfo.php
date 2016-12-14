<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 11/25/2016
 * Time: 10:28 AM
 */

namespace Webarq\Info;


use Wa;
use Webarq\Manager\SingletonManagerTrait;

class TableInfo
{
    use SingletonManagerTrait;

    /**
     * @var array object Webarq\Info\ColumnInfo
     */
    protected $columns = [];

    /**
     * Table extra information
     *
     * @var array
     */
    protected $extra = [];

    /**
     * @var array
     */
    protected $log = [];

    /**
     * Module name
     *
     * @var string
     */
    protected $module;

    /**
     * Table name
     *
     * @var string
     */
    protected $name;

    /**
     * Primary key column name
     *
     * @var object Webarq\Info\ColumnInfo
     */
    protected $primaryColumn;

    /**
     * @var bool
     */
    protected $multilingual = false;

    /**
     * Table options serialization
     *
     * @var array
     */
    protected $serialize;

    /**
     * @param $name
     * @param $module
     * @param array $options
     */
    public function __construct($name, $module, array $options = [])
    {
        $this->serialize = serialize($options);
        $this->name = $name;
        $this->module = $module;
        $this->setup($options);
    }

    /**
     * Setup class environment
     *
     * @param array $configs
     */
    protected function setup(array $configs)
    {
        if ([] !== $configs) {
            foreach ($configs as $i => $value) {
// This is a column
                if (is_numeric($i)) {
                    $this->setColumn($value);
                } elseif (property_exists($this, $i)) {
                    $this->{$i} = $value;
                } else {
                    switch ($i) {
                        case 'timestamps':
                            $this->setColumn(config('webarq.data-type-master.createOn'));
                            $this->setColumn(config('webarq.data-type-master.lastUpdate'));
                            break;
                        case 'timestamp':
                            $this->setColumn(config('webarq.data-type-master.createOn'));
                            break;
                        default:
                            $this->extra[$i] = $value;
                            break;
                    }
                }
            }
        }
    }

    /**
     * Set column options in to table
     *
     * @param array $options
     */
    protected function setColumn(array $options)
    {
        $column = Wa::load('info.column', $options);

        if ($column->isPrimary()) {
            $this->primaryColumn = $column;
        }
        if (true === $column->getExtra('multilingual')) {
            $this->multilingual = true;
        }

        $this->columns[$column->getName()] = $column;
    }

    /**
     * Get column options from table by column name
     *
     * @param $name
     * @return mixed
     */
    public function getColumn($name)
    {
        return array_get($this->columns, $name, null);
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function getLog($key, $default = null)
    {
        return array_get($this->log, $key, $default);
    }

    /**
     * @return array
     */
    public function getLogs()
    {
        return $this->log;
    }

    /**
     * Get table module
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function primaryColumn()
    {
        return $this->primaryColumn;
    }

    public function isMultiLingual()
    {
        return $this->multilingual;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function getExtra($key, $default = null)
    {
        return array_get($this->extra, $key, $default);
    }

    public function getSerialize()
    {
        return $this->serialize;
    }

    public function getReferenceKeyName()
    {
        return str_singular($this->name) . '_id';
    }
}

