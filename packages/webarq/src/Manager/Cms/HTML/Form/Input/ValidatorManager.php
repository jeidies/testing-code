<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 12/22/2016
 * Time: 9:20 AM
 */

namespace Webarq\Manager\Cms\HTML\Form\Input;


use Request;
use Webarq\Manager\AdminManager;

/**
 * Helper class to validate form input
 *
 * Class ValidatorManager
 * @package Webarq\Manager\Cms\HTML\Form\Input
 */
class ValidatorManager extends \Webarq\Manager\Cms\ValidatorManager
{
    /**
     * Current login user
     *
     * @var object Webarq\Manager\AdminManager
     */
    protected $admin;

    /**
     * URL module segment
     *
     * @var string
     */
    protected $module;

    /**
     * URL panel segment
     *
     * @var string
     */
    protected $panel;

    /**
     * Input name
     *
     * @var string
     */
    protected $input;

    /**
     * Validator rules
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Create ValidatorManager instance
     *
     * @param AdminManager $admin
     * @param null|string $module URL module segment
     * @param null|string $panel URL panel segment
     * @param string $input Input type
     * @param array $rules
     */
    public function __construct(AdminManager $admin, $module = null, $panel = null, $input, array $rules = [])
    {
        $this->admin = $admin;
        $this->input = $input;
        $this->rules = $rules;
        $this->module = $module;
        $this->panel = $panel;
    }

    /**
     * Input is valid or not
     *
     * @return bool
     */
    public function valid()
    {
        return $this->compile();
    }

    protected function compile()
    {
        if ([] !== $this->rules) {
            foreach ($this->rules as $key => $value) {
                if (is_callable($value)) {
                    $value($this->admin, Request::input($this->input));
                }
            }
        }
        return true;
    }
}