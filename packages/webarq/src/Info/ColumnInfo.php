<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 11/25/2016
 * Time: 5:59 PM
 */

namespace Webarq\Info;


use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Wa;

class ColumnInfo
{
    /**
     * @var string
     */
    protected $comment;

    /**
     * @var number
     */
    protected $length;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $primary = false;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var bool
     */
    protected $notnull = false;

    /**
     * @var null|mixed
     */
    protected $default = null;

    /**
     * @var
     * @todo Implement protected column
     */
    protected $protected = false;

    /**
     * @var
     */
    protected $engine;

    /**
     * Column extra information
     *
     * @var array
     */
    protected $extra;

    /**
     * Column options serial
     *
     * @var string
     */
    protected $serialize;

    /**
     * Unique column
     *
     * @var bool
     */
    protected $unique = false;

    /**
     * Uniques column
     *
     * @var bool
     */
    protected $uniques = false;

    public function __construct(array $options)
    {
        $this->default = Wa::getGhost();
// Merge data options with proper master data
        $this->mergeMasterData($options);
// Serialize column options before running the setup
        $this->serialize = serialize($options);
        $this->setUp($options);
        $this->extra = $options;
    }

    /**
     * @param array $options
     */
    protected function mergeMasterData(array &$options)
    {
        if (null !== ($name = array_get($options, 'master'))
                && [] !== ($master = config('webarq.data-type-master.' . $name, []))
        ) {

            $options = Arr::merge($master, $options);
        }
    }

    /**
     * @param array $options
     */
    protected function setUp(array &$options)
    {
        if ([] !== $options) {
            $vars = get_class_vars('Webarq\Info\ColumnInfo');
            unset($vars['extra']);
            if ([] !== $vars) {
                foreach (array_keys($vars) as $key) {
                    $this->{$key} = array_pull($options, $key, $this->{$key});
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return number
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isPrimary()
    {
        return $this->primary;
    }

    public function getEngine()
    {
        return $this->engine;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function nullable()
    {
        return false === $this->notnull;
    }

    public function getSerialize()
    {
        return $this->serialize;
    }

    public function isUnique()
    {
        return $this->unique;
    }

    public function isUniques()
    {
        return $this->uniques;
    }

    public function __get($key)
    {
        if (method_exists($this, ($method = Str::camel('get ' . $key)))) {
            return $this->{$method}();
        } else {
            return $this->getExtra($key);
        }
    }

    /**
     * @param $key
     * @param null|mixed $default
     * @return mixed
     */
    public function getExtra($key, $default = null)
    {
        return array_get($this->extra, $key, $default);
    }
}