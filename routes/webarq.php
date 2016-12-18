<?php
/**
 * Created by PhpStorm
 * Date: 21/10/2016
 * Time: 16:02
 * Author: Daniel Simangunsong
 *
 *
 * How this routes work
 * Url : something.com/{module}/{controller}/{action}/param1/param2/ ...
 * is {module} directory located directly under app/Http/Controllers/Site/?
 *  1. Yes
 *    is {controller} controller file exist inside app/Http/Controllers/Site/{module} directory ?
 *      2. Yes
 *        is {action} method exist on {controller} class ?
 *          3. Yes
 *            call App/Http/Controllers/Site/{module}/{controller}->{action}()
 *          4. No
 *            - call App/Http/Controllers/Site/{module}/{controller}->actionIndex()
 *            - un shift {action} from params
 *
 *      5. No
 *        use {module} as {controller}
 *        use {controller} as {action}
 *        is {module} controller file exist inside app/Http/Controllers/Site directory ?
 *          6. Yes
 *            is {controller} method exist on {module} class ?
 *              7. Yes
 *                - call App/Http/Controllers/Site/{module}->{controller}()
 *                - un shift {action} from params
 *              8. No
 *                - call App/Http/Controllers/Site/{module}->actionIndex()
 *                - un shift {controller}, {action} from params
 *
 *          9. No
 *            use App/Http/Controllers/Site/StaticController
 *            use {module} as action
 *            is {module} method exist on StaticController class ?
 *              10. Yes
 *                - call App/Http/Controllers/Site/StaticController->{module}()
 *                - un shift {controller}, {action} from params
 *              11. No
 *                - call App/Http/Controllers/Site/StaticController->actionIndex()
 *                - un shift {module}, {controller}, {action} from params
 *
 *  No
 *    Run point 5
 *
 */

if (Wa::config('system.configs.queryLog')) {
    DB::enableQueryLog();
}
// Starts with allowed url format
$urlFormat = '{directory?}/{module?}/{controller?}/{action?}';
// Allowing parameter
$paramLength = 4;
if ($paramLength > 0) {
    for ($i = 1; $i <= $paramLength; $i++) {
        $urlFormat .= '/{param' . $i . '?}';
    }
}
Route::match(['get', 'post'], $urlFormat,
        function ($directory = 'site', $module = 'system', $controller = 'static', $action = 'index')
        use ($paramLength, $urlFormat) {
// Since we do not need to write down directory name in url while accessing site page,
// we need to re-assign url section value
            if (config('webarq.system.panel-url-prefix') !== $directory) {
                $action = $controller;
                $controller = $module;
                $module = $directory;
                $directory = 'Site';
                $indexParam = 4;
                $paramLength++;
            } else {
                $indexParam = 5;
                $directory = 'Panel';
            }

// Server directory separator
            $sep = DIRECTORY_SEPARATOR;
// Controller action prefix
            $actPrefix = config('webarq.system.action-prefix') . ucfirst(strtolower(Request::method()));
// File controller should be under App/Http/Controllers/$directory
            $absolute = 'App' . $sep . 'Http' . $sep . 'Controllers' . $sep . $directory . $sep;
            $relative = '..' . $sep . 'app' . $sep . 'Http' . $sep . 'Controllers' . $sep . $directory . $sep;
// Set convention name
            $module = studly_case($module);
            $controller = studly_case($controller);
            $action = studly_case($action);
// Yes, normally module is a directory
            if (is_dir($relative . $module)) {
                if (is_file($relative . $module . $sep . $controller . 'Controller.php')) {
                    $class = $absolute . $module . $sep . $controller . 'Controller';
                    if (method_exists($class, $actPrefix . $action)) {
                        $method = $actPrefix . $action;
                    }
                }
            } elseif (is_file($relative . $module . $sep . 'Controller.php')) {
// But, in case it is not, module will act as controller, and so on
                $class = $absolute . $module . 'Controller';
                $indexParam--;
                if (method_exists($class, $actPrefix . $controller)) {
                    $method = $actPrefix . $controller;
                }
            } elseif (null !== ($class = config('webarq.system.default-controller'))) {
// Ups, controller still undetected, use default controller (if any)
                if (is_file($relative . $class . '.php')) {
                    $class = $absolute . $class;
                    $indexParam -= 2;
                    if (method_exists($class, $actPrefix . $module)) {
                        $method = $actPrefix . $module;
                    }
                } else {
                    $class = null;
                }
            }
// Yay, found a class
            if (isset($class)) {
                if (!isset($method)) {
                    $method = config('webarq.system.default-action');
                    $indexParam--;
                }
// Get params value from it is request segment
                $params = [];
                if ($paramLength > 0) {
                    $i = 0;
                    while ($i < $paramLength) {
                        $params[$i + 1] = \Request::segment($indexParam + $i);
                        $i++;
                    }
                }
// Resolving class
                $class = resolve($class, [
                        'module' => $module, 'controller' => $controller,
                        'method' => $action, 'params' => $params]);
// Execute class object "before" method if any
                if (method_exists($class, 'before')) {
                    if (null !== ($before = $class->before($params))) {
                        return $before;
                    }
                }
// Call method (do not forget about method injection)
                $call = App::call([$class, $method], $params);
                if (!is_null($call)) {
                    return $call;
                } elseif (method_exists($class, 'after')) {
                    return $class->after();
                } else {
// @todo change error in to content not found file
                    return view('webarq.errors.404');
                }
            } else {
                abort(404, 'Route not matched');
            }
        }
);