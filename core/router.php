<?php

class Router
{
    protected $uri;             // Uri
    protected $controller;      // Request controller
    protected $action;          // Request action
    protected $params;          // Request Parameters

    /**
     * Router constructor.
     * @param $uri
     */
    public function __construct(&$uri)
    {
        //Load Defaults
        $this->controller = 'home';
        $this->action = 'index';

        // Get the real $uri set in routes config
        $redirect_base = AdvisorApp::getConfig('redirect_base');
        $uri = str_replace( $redirect_base, '', $uri );

        $this->uri = urldecode(trim($uri, '/'));

        //Get Params
        $query = parse_url( $this->uri, PHP_URL_QUERY);
        parse_str($query, $this->params);

        // If uri is empty, we're either on the homepage or an error has occurred
        if ( empty( $this->uri ) ) {
            return;
        }

        // Get uri path
        $uri_path = parse_url( $this->uri, PHP_URL_PATH );

        //get established routes
        $routes = AdvisorApp::getRoutes();

        // If uri is found in array of established routes, set the controller/action
        if ( array_key_exists( $uri_path, $routes ) ) {
            $this->controller = $routes[$uri_path]['controller'];
            $this->action = $routes[$uri_path]['action'];
        }
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }
}