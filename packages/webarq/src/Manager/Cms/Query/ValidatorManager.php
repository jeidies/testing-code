<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 12/22/2016
 * Time: 6:35 PM
 */

namespace Webarq\Manager\Cms\Query;


use Webarq\Manager\AdminManager;

class ValidatorManager
{
    /**
     * Current login user
     *
     * @var object AdminManager
     */
    protected $admin;

    /**
     * Rule collection based on laravel format
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Rule collection callback
     *
     * @var array
     */
    protected $callbacks = [];

    /**
     * Post data
     *
     * @var array
     */
    protected $post = [];

    /**
     * Laravel bail validator rule
     *
     * @var bool
     */
    protected $bail = false;

    /**
     * Keyword synonyms.
     * Register our own rules keyword into one that laravel used.
     * Eg. length in laravel validator must be max
     *
     * @var array
     */
    protected $synonyms = [
        'length' => 'max'
    ];

    /**
     * Create ValidatorManager instance
     *
     * @param AdminManager $admin
     * @param array $rules
     * @param array $post
     */
    public function __construct(AdminManager $admin, array $rules, array $post)
    {
        $this->admin = $admin;
        $this->post = $post;

        $this->synonyms += config('webarq.laravel.synonyms', []);
        dd($this->synonyms);
        $this->compileRules($rules);
    }

    /**
     * Compile
     *
     * @param array $rules
     */
    protected function compileRules(array $rules)
    {
        foreach ($rules as $input => $groups) {
            foreach ($groups as $key => $value) {
                if (is_callable($value)) {
                    $this->callbacks[$input] = $value;
                    unset($groups[$key]);
                }
            }
            $this->toLaravelRule($input, $groups);
        }
        dd($rules, $this->rules);
    }

    /**
     * Transform array rules into laravel format
     *
     * @param $input
     * @param array $rules
     */
    protected function toLaravelRule($input, array $rules)
    {
        $l = array_pull($rules, 'laravel');
        $rule = [];

        if (true === $this->bail) {
            $rule = ['bail'];
        }

        $this->requireRule($rules, $rule);

        $this->nonArgumentRules($rules, $rule);

        $this->needArgumentsRules($rules, $rule);

        if (isset($l)) {
            $rule[] = $l;
        }

        if ([] !== $rule) {
            $this->rules[$input] = implode('|', $rule);
        }
    }

    /**
     * Set required rule
     *
     * @param array $rules
     * @param array $rule
     */
    protected function requireRule(array &$rules, array &$rule)
    {
        if (null !== ($var = array_pull($rules, 'required')) || null !== ($var = array_pull($rules, 'notnull'))) {
            $rule[] = 'required';
        }
    }

    /**
     * Set rules which are does not need argument
     *
     * @param array $rules
     * @param array $rule
     */
    protected function nonArgumentRules(array &$rules, array &$rule)
    {
        foreach (['numeric'] as $key) {
            if (null !== array_pull($rules, $key)) {
                $rule[] = $key;
            }
        }
    }

    /**
     * Set rules which are need argument
     *
     * @param array $rules
     * @param array $rule
     */
    protected function needArgumentsRules(array &$rules, array &$rule)
    {
        if ([] !== $rules) {
            foreach ($rules as $key => $value) {
                $rule[] = $this->matchedLaravelKeyword($key) . ':' . $value;
            }
        }
    }

    /**
     * Matching our key with the one that laravel used
     *
     * @param $key
     * @return string
     */
    protected function matchedLaravelKeyword($key)
    {
        return array_get($this->synonyms, $key, $key);
    }

    public function valid()
    {

    }
}