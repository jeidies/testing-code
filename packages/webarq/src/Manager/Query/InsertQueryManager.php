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
use Webarq\Manager\AdminManager;
use Webarq\Manager\QueryManager;

class InsertQueryManager extends QueryManager
{
    /**
     * Current login user
     *
     * @var object AdminManager
     */
    protected $admin;

    /**
     * Input rules
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Input pairs
     *
     * @var array
     */
    protected $pairs = [];

    /**
     * @var array
     */
    protected $post = [];

    /**
     * Master table
     *
     * @var null|string
     */
    protected $masterTable;

    /**
     * @var bool
     */
    protected $ignore = false;

    /**
     * Create InsertQueryManager instance
     *
     * @param AdminManager $admin
     * @param array $rules
     * @param array $pairs
     * @param array $post
     * @param null $master
     */
    public function __construct(AdminManager $admin, array $rules, array $pairs, array $post, $master = null)
    {
        $this->admin = $admin;
        $this->rules = $rules;
        $this->pairs = $pairs;
        $this->post = $post;
        $this->setMaster($master);
    }

    protected function setMaster($master)
    {

    }

    public function ignore()
    {
        $this->ignore = true;

        return $this;
    }
}