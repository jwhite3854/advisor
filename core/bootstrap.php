<?php

define('MODEL_PATH',        APP_ROOT.'/models');
define('VIEW_PATH',         APP_ROOT.'/views');
define('CONTROLLER_PATH',   APP_ROOT.'/controllers');

require APP_ROOT.'/routes.php';

class AdvisorApp
{
    protected static $settings = array();
    protected static $routes = array();
    protected static $request;
    public static $isDevMode;

    public static function setConfigs()
    {
        require APP_ROOT.'/config.php';
        self::$settings = $settings;
    }

     /**
     * Start the process...
     * @param $request
     * @param $is_dev_mode
     */
    public static function run( $request, $isDevMode = false )
    {
        self::$request = $request;
        self::$isDevMode = $isDevMode;
        $controllerOutput = 'Error! Unable to Render Page.';
        try {

            //GET Controller / Action / Params from request
            require CORE_PATH . '/router.php';
            $router = new Router($request);
            $controllerName = $router->getController();
            $actionName = $router->getAction();
            $params = $router->getParams();


            // Get the path of the controller
            $controllerPath = CONTROLLER_PATH.'/'.$controllerName.'.php';
            $controllerOutput = 'Error! Cannot find the URL.';

            //Does the controller file exist?
            if( file_exists( $controllerPath ) ) {
                require CORE_PATH . '/controller.php';
                require $controllerPath;

                // Does the controller class exixt?
                $controllerClassName = $controllerName.'Controller';
                if ( class_exists( $controllerClassName ) ) {

                    // Create the new controller class with the action and parameters
                    $controllerObject = new $controllerClassName( $controllerName, $actionName, $params );
                    if ( method_exists($controllerObject, $actionName) ) {

                        // Get the output of the controller of the given action with the params provided
                        $controllerOutput = $controllerObject->$actionName( $params );
                    } else {
                        self::printError( 'Cannot find '.$controllerName.' method: '. $actionName );
                    }
                } else {
                    self::printError( 'Cannot find controller class: ' .$controllerClassName );
                }
            } else {
                self::printError( 'Cannot find controller file: ' . $controllerPath );
            }
        } catch ( Exception $e ) {
            if ( $isDevMode ) {
                self::printException( $e );
            } 
        } finally {
            echo $controllerOutput;
        }
    }

    /**
     * Get Global Config setting
     * @param $key
     */
    public static function getConfig($key)
    {
        return !empty(self::$settings[$key]) ? self::$settings[$key] : null;
    }

    /**
     * Prints exceptions for debugging
     * @param $e
     * @param $is_dev_mode
     */
    private static function printException( $e )
    {
        echo '<pre>';
        echo $e->getMessage() . "\n\n";
        echo $e->getTraceAsString();
        echo '</pre>';
    }

    /**
     * Prints error for debugging
     * @param $e
     * @param $is_dev_mode
     */
    private static function printError( $error )
    {
        echo '<pre>', $error, '</pre>';
        die();
    }

    /**
     * Add a route to the app, with the given uri to the specified controller / action
     * @param $uri
     * @param $controller
     * @param $action
     */
    public static function addRoute($uri, $controller, $action)
    {
        $key = trim($uri, '/');
        self::$routes[$key] = array(
            'controller' => $controller,
            'action' => $action
        );
    }

    /**
     * Gets all routes for the app
     */
    public static function getRoutes()
    {
        return !empty(self::$routes) ? self::$routes : array();
    }
}


class Url
{
    public static function render( $uri, $params = array())
    {
        $domain = AdvisorApp::getConfig('domain');
        $redirect_base = AdvisorApp::getConfig('redirect_base');
        
        if ( AdvisorApp::$isDevMode ) {
            $param['dev_mode'] = 1;
        }

        $param_string = '';
        if ( count($params) > 0 ) {
            $param_parts = array();
            foreach ( $param as $key => $value ) {
                $param_parts[] = urlencode(urldecode($key)) . '=' . urlencode(urldecode($value));
            }
            $param_string = '?' . implode('&', $param_parts);
        }

        return $domain . $redirect_base . trim( $uri, '/' ) . $param_string;
    }

    public static function isActive($uri)
    {

    }
}