<?php
/**
 * Created by PhpStorm
 * Date: 19/10/2016
 * Time: 13:39
 * Author: Daniel Simangunsong
 *
 * Calm seas, never make skill full sailors
 */

namespace Webarq;


use Webarq\Info\ModuleInfo;
use Webarq\Info\TableInfo;
use Webarq\Manager\ConfigManager;

/**
 * Helper class. This is break the SOLID Principals but i can not do anything about that. I think this is the most
 * suitable way
 *
 * Class Wa
 * @package Webarq
 */
class Wa
{
    protected $instances = [];

    protected $space = 'Webarq';

    /**
     * @param $name
     * @param null $module
     * @param array $columns
     * @return null
     */
    public function table($name, $module = null, array $columns = [])
    {
// Ups, this is something unusual
        if (is_array($module)) {
            $columns = $module;
            $module = null;
        }
// Table should have module
        if (!isset($module)) {
            if ([] !== ($modules = $this->modules())) {
                foreach ($modules as $item) {
                    $manager = $this->module($item);
                    if ($manager->hasTable($name)) {
                        $module = $manager->getName();
                        break;
                    }
                }
            }
        } elseif (!Wa::module($module)->hasTable($name)) {
            return null;
        }
        if (!isset($module)) {
            return null;
        }
        return TableInfo::getInstance($name, $module, $columns);
    }

    /**
     * @return array
     *
     * Get config modules
     */
    public function modules()
    {
        return config('webarq.modules', []);
    }

    /**
     * @param $name
     * @return object
     */
    public function module($name)
    {
        if (in_array($name, $this->modules())) {
            return ModuleInfo::getInstance($name, $this->config($name, []));
        }
    }

    /**
     * Load class instance
     *
     * @param $class
     * @param mixed $arg [,... $arg, $arg] Uncountable argument
     * @return mixed
     */
    public function instance($class, $arg = [])
    {
// Get loaded class
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }
// Load new instances
        if (!isset($this->instances[$class])) {
// Get actual arguments
            $args = func_get_args();
// Remove class argument
            array_shift($args);
            if ($this->getGhost() === array_get($args, 1)) {
                $args = $args[0];
            }
// Get class full path
            $fp = $this->normalizeClass($class);

            if (class_exists($fp)) {
                if (is_callable([$fp, 'getInstance'])) {
                    $this->instances[$class] = $fp::getInstance($args, $this->getGhost());
                } else {
                        $this->instances[$class] = $this->load(false, $fp, $args, $this->getGhost());
                }
                return $this->instances[$class];
            } else {
                abort(500, 'Class ' . $class . ' not found on this system');
            }
        }
    }

    public function getGhost()
    {
        return config('webarq.system.ghost');
    }

    /**
     * Normalize class name, it would be prefixed by Webarq\ and suffixed by lastFolderName
     *
     * @param string $path
     * @return string Full class path
     */
    public function normalizeClass($path)
    {
        $path = str_replace(['\\', '/', '_'], '.', $path);
        $path = str_replace('-', ' ', $path);
        $path = explode('.', $path);
        if (count($path) > 1) {
            foreach ($path as &$item) {
                if (str_contains($item, '!')) {
                    $item = substr($item, 0, -1);
                } else {
                    $item = ucfirst(strtolower($item));
                }
            }

            $class = implode('\\', $path);
            if (!str_contains(last($path), '$')) {
                $class .= $path[0];
            }
        } else {
            $class = ucfirst(strtolower($path));
        }

        return $this->space . '\\' . $class;
    }

    /**
     * Load new given class name. To load class without normalize class name, set "false" as first parameter,
     * following by normal parameter
     *
     * @param string|false $class Class name
     * @param null|mixed $args [, mixed $args ...]
     * @return object
     */
    public function load($class, $args = null)
    {
        $args = func_get_args();
// Remove class argument
        array_shift($args);
        if (false === $class) {
            $class = array_shift($args);
        } else {
            $class = $this->normalizeClass($class);
        }

        if ($this->getGhost() === array_get($args, 1)) {
            $args = $args[0];
        }

        switch (count($args)) {
            case 0:
                return new $class();
            case 1:
                return new $class($args[0]);
            case 2:
                return new $class($args[0], $args[1]);
            case 3:
                return new $class($args[0], $args[1], $args[2]);
            case 4:
                return new $class($args[0], $args[1], $args[2], $args[3]);
            case 5:
                return new $class($args[0], $args[1], $args[2], $args[3], $args[4]);
            case 6:
                return new $class($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
            default:
                $o = new \ReflectionClass($class);
                $i = $o->newInstanceArgs($args);
                return $i;
        }
    }

    /**
     * A shortcut method to access Webarq\Manager\ConfigManager instances
     *
     * @param string $file
     * @param mixed $default
     * @return mixed
     */
    public function config($file, $default = null)
    {
        return ConfigManager::get($file, $default);
    }

    /**
     * Manager class loader
     *
     * @param $class
     * @param array $args , ... [$args]
     * @return object
     */
    public function manager($class, $args = [])
    {
        $args = func_get_args();
// Remove class argument
        array_shift($args);
        return $this->load('manager.' . $class, $args, $this->getGhost());
    }


    /**
     * HTML Manager class loader
     *
     * @param $class
     * @param array $args , ... [$args]
     * @return object
     */
    public function html($class, $args = [])
    {
        $args = func_get_args();
// Remove class argument
        array_shift($args);
        return $this->load('manager.HTML!.' . $class, $args, $this->getGhost());
    }
}