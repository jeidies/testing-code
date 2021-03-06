<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 12/15/2016
 * Time: 5:40 PM
 */

namespace Webarq\Manager;


use Illuminate\Contracts\Auth\Authenticatable;
use Webarq\Info\TableInfo;

abstract class WatchdogAbstractManager implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;

    /**
     * @var array
     */
    protected $profile = [];

    /**
     * User password
     *
     * @var mixed
     */
    protected $password;

    /**
     * @var object Webarq\Info\TableInfo
     */
    private $table;

    abstract public function identify($credentials = []);

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getKey()
    {
        return array_get($this->profile, $this->table->primaryColumn()->getName());
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getKeyName()
    {
        return array_get($this->profile, 'username');
    }

    public function getTable()
    {
        return $this->table;
    }

    protected function setTable(TableInfo $tableInfo)
    {
        $this->table = $tableInfo;
    }

    abstract protected function setProfile(array $data);
}