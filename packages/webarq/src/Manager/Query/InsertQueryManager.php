<?php
/**
 * Created by PhpStorm
 * Date: 18/12/2016
 * Time: 9:26
 * Author: Daniel Simangunsong
 *
 * Calm seas, never make skill full sailors
 */

namespace Webarq\Manager\Query;


use Arr;
use Webarq\Info\TableInfo;
use Webarq\Manager\QueryManager;

class InsertQueryManager extends QueryManager
{
    /**
     * @var bool
     */
    protected $ignore = false;

    /**
     * @var TableInfo
     */
    protected $table;

    /**
     * @var array
     */
    protected $rules = [];

    public function __construct(TableInfo $table, array $rows, array $rules)
    {
        $this->table = $table;
        $this->rules = $rules;
    }

    public function ignore()
    {
        $this->ignore = true;

        return $this;
    }
}