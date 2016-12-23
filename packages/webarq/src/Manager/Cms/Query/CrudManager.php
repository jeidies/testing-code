<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 12/21/2016
 * Time: 6:33 PM
 */

namespace Webarq\Manager\Cms\Query;


use Wa;
use Webarq\Manager\AdminManager;

/**
 * Helper class
 *
 * Class CrudManager
 * @package Webarq\Manager\Cms\Query
 */
class CrudManager
{
    /**
     * Current login user
     *
     * @var object AdminManager
     */
    protected $admin;

    /**
     * Input error messages
     *
     * @var
     */
    protected $errorMessages;

    /**
     * Input rules
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Data pairing
     *
     * @var array
     */
    protected $pairs = [];

    /**
     * Original form data post
     *
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
     * Error message
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Create InsertQueryManager instance
     *
     * @param AdminManager $admin
     * @param array $errorMessages
     * @param array $rules
     * @param array $pairs
     * @param array $post
     * @param null $master
     */
    public function __construct(
            AdminManager $admin, array $rules, array $pairs, array $post, array $errorMessages, $master = null)
    {
        $this->admin = $admin;
        $this->rules = $rules;
        $this->post = $post;
        $this->errorMessages = $errorMessages;
        $this->pairingData($pairs, $post);
        $this->setMasterTable($master);

    }

    /**
     * Pairing post data with the column table
     *
     * @param array $pairs
     * @param array $post
     */
    protected function pairingData(array $pairs, array $post)
    {
        if ([] !== $pairs && [] !== $post) {
            foreach ($pairs as $input => $path) {
                list($module, $table, $column) = explode('.', $path);
                array_set($this->pairs, $table . '.' . $column, array_get($post, $input));
            }
        }
    }

    /**
     * @param $master
     */
    protected function setMasterTable($master)
    {
        if (!isset($master)) {
            foreach ($this->pairs as $table => $pairs) {
                if (is_null($master) || 0 === strpos($master, str_singular($table))) {
                    $master = $table;
                }
            }
        }
    }

    /**
     * @return bool|array
     */
    public function insert()
    {
        $validator = Wa::manager('cms.query.validator', $this->admin, $this->rules, $this->post, $this->errorMessages)
                ->make();
        if ($validator->fails()) {
            return $validator->errors()->all();
        }
        return true;
    }

    public function update()
    {

    }
}