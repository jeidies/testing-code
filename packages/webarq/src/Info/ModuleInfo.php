<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 11/24/2016
 * Time: 2:45 PM
 */

namespace Webarq\Info;


use Wa;
use Webarq\Manager\SingletonManagerTrait as Singleton;

class ModuleInfo
{
    use Singleton;

    /**
     * Module configuration
     *
     * @var array
     */
    protected $configs = [];

    /**
     * Module name
     *
     * @var string
     */
    protected $name;

    /**
     * Module registered panel menu
     *
     * @var array
     */
    protected $panel = [];

    /**
     * Module registered tables
     *
     * @var array object Webarq\Info\TableInfo
     */
    protected $tables = [];

    public function __construct($name, array $configs = [])
    {
        $this->name = $name;

        $this->setup($configs);
    }

    /**
     * Setup module by given options
     *
     * @param array $options
     */
    protected function setup(array $options)
    {
        if ([] !== $options) {
            $this->configs = array_get($options, 'configs', []);

            $this->setupTables(array_get($options, 'tables', []));

            $this->setupPanels(array_get($options, 'panel-menus', []));
        }
    }

    /**
     * @param array $tables
     */
    private function setupTables(array $tables)
    {
        if ([] !== $tables) {
            foreach ($tables as $name) {
                $this->tables[$name] = TableInfo::getInstance(
                        $name, $this->name, Wa::config($this->name . '.tables.' . $name, []));
            }
        }
    }

    /**
     * @param array $menus
     */
    private function setupPanels(array $menus)
    {
        if ([] !== $menus) {
            foreach ($menus as $name => $config) {
                $this->panel[$name] = $config;
            }
        }
    }

    public function getConfig($key, $default = null)
    {
        return array_get($this->configs, $key, $default);
    }

    public function getConfigs()
    {
        return $this->configs;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return object Webarq\Info\TableInfo
     */
    public function getTable($name)
    {
        return array_get($this->tables, $name, TableInfo::getInstance(
                $name, $this->name, Wa::config($this->name . '.tables.' . $name, [])));
    }

    public function getTables()
    {
        return $this->tables;
    }

    /**
     * Determine if a table registered in module
     *
     * @param $tableName
     * @return bool
     */
    public function hasTable($tableName)
    {
        return isset($this->tables[$tableName]);
    }
}