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

if (Wa::origin('system.queryLog')) DB::enableQueryLog();

$lengthOfParam = 4;
$url = '{directory?}/{module?}/{controller?}/{action?}';
if ($lengthOfParam > 0)
{
    for ($i = 1; $i <= $lengthOfParam; $i++)
        $url .= '/{param' . $i . '?}';
}

Route::match(['get', 'post'],$url,
        function($directory = '',$module='system', $controller='static', $action = 'index') use($lengthOfParam)
        {
            if ('admin-cp' !== $directory)
            {
                $action = $controller;
                $controller = $module;
                $module = $directory;
                $directory = 'Site';
                $openingParam = 4;
                $lengthOfParam++;
            }
            else
            {
                $openingParam = 5;
                $directory = 'Panel';
            }

            $params = [];

            if ($lengthOfParam > 0)
            {
                $i = 1;
                while($i <= $lengthOfParam)
                {
                    $params[$i] = \Request::segment($openingParam+$i);
                    $i++;
                }
            }

            /**
             * @todo Determine whether these view share item, could be done in controller i/of routing
             */
            View::share('routeModule',$module);
            View::share('routeController',$controller);
            View::share('routeAction',$action);
            View::share('routeParams',$params);

            //Controller File location
            $relative = '..'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'
                    .DIRECTORY_SEPARATOR.$directory.DIRECTORY_SEPARATOR;
            $absolute = 'App'.DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.$directory
                    .DIRECTORY_SEPARATOR;

            $moduleDir = preg_replace('/[^0-9a-zA-Z ]/',' ',strtolower($module));
            $moduleDir = \Illuminate\Support\Str::camel($moduleDir);
            $moduleDir = ucfirst($moduleDir);

            $camelController = preg_replace('/[^0-9a-zA-Z ]/',' ',strtolower($controller));
            $camelController = \Illuminate\Support\Str::camel($camelController);
            $camelController = ucfirst($camelController);

            //Default action
            $classAction = 'actionIndex';
            $camelAction = preg_replace('/[^0-9a-zA-Z ]/',' ',strtolower($action));
            $camelAction = \Illuminate\Support\Str::camel($camelAction);
            $camelAction = ucfirst($camelAction);

            if ( is_dir($relative . $moduleDir ) )
            {
                if ( is_file($relative . $moduleDir . DIRECTORY_SEPARATOR . $camelController . 'Controller.php') )
                {
                    $classObject = app()->make( $absolute . $moduleDir . DIRECTORY_SEPARATOR . $camelController . 'Controller' );
                    if ( method_exists($classObject,'action' . $camelAction))
                    {
                        $classAction = 'action' . $camelAction;
                    }
                }
            }

            if ( !isset($classObject) )
            {
                if ( is_file($relative . $moduleDir .  'Controller.php') )
                {
                    $classObject = app()->make( $absolute . $moduleDir . 'Controller' );
                    if ( method_exists($classObject,'action' . $camelController) )
                    {
                        $classAction = 'action' . $camelController;
                    }
                }
            }

            if ( !isset($classObject) )
            {
                $classObject = app()->make( 'App\Http\Controllers\\'.$directory.'\StaticController' );
                if ( method_exists($classObject,'action' . $moduleDir) )
                {
                    $classAction = 'action' . $moduleDir;
                }
            }

            if ( isset($classObject) )
            {
                //Before
                if ( method_exists($classObject,'before')) $classObject->before();
                //Action
                $callAction = $classObject->callAction($classAction,$params);
                if ( !is_null($callAction) )
                    return $callAction;
                elseif ( method_exists($classObject,'after'))
                    return $classObject->after();
                else
                    return '<html><head><title>Routing blank page</title></head></html>';
            }
        }
);