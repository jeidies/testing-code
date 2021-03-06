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

/**
 * Helper class
 *
 * Class TableInfo
 * @package Webarq\Info
 */
class TableInfo
{
    use SingletonManagerTrait;

    /**
     * Table columns
     *
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
     * Table column(s) that used to record any table transaction.
     * Like who doing what
     *
     * @var array
     */
    protected $log = [];

    /**
     * Module name
     * In what module this table is register
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
     * Table primary column name
     *
     * @var object Webarq\Info\ColumnInfo
     */
    protected $primaryColumn;

    /**
     * Is table multilingual?
     *
     * @var bool
     */
    protected $multilingual = false;

    /**
     * Serialize table options
     *
     * @var array
     */
    protected $serialize;

    /**
     * Create TableInfo instance
     *
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
     * Set table column
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
     * Get column item by given $name
     *
     * @param $name
     * @return mixed
     */
    public function getColumn($name)
    {
        return array_get($this->columns, $name, null);
    }

    /**
     * Get all table column items
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get table log information by given $key
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function getLog($key, $default = null)
    {
        return array_get($this->log, $key, $default);
    }

    /**
     * Get all logs
     *
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

    /**
     * Get table name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get table primary column
     *
     * @return string
     */
    public function primaryColumn()
    {
        return $this->primaryColumn;
    }

    /**
     * Is table multilingual?
     *
     * @return bool
     */
    public function isMultiLingual()
    {
        return $this->multilingual;
    }

    /**
     * Get table extra information by given $key
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function getExtra($key, $default = null)
    {
        return array_get($this->extra, $key, $default);
    }

    /**
     * Unserialize table options which already serialized
     *
     * @return array|string
     */
    public function getSerialize()
    {
        return $this->serialize;
    }

    /**
     * Get table reference key
     *
     * @return string
     */
    public function getReferenceKeyName()
    {
        return str_singular($this->name) . '_id';
    }
}

