<?php

class Controller
{
    protected $data;
    protected $model;
    protected $action;
    protected $params;

    /**
     * Controller constructor.
     * @param $model
     * @param $action
     * @param $params
     */
    public function __construct( $model, $action, $params = array())
    {
        $this->model = $model;
        $this->action = $action;
        $this->params = $params;

        $modelPath = MODEL_PATH.'/'.$this->model.'/main.php';

        // Is there a model for this controller?        
        if( file_exists( $modelPath ) ) {
            require_once $modelPath;
        }
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }


    /** Render controller, Web view is rendered if no path specified.
     * @param null $data
     * @param null $layoutData
     * @return string
     */
    function render( $data = array(), $layoutData = array(), $moreStylesheets = array(), $moreScripts = array() )
    {
        if ( array_key_exists( 'meta', $layoutData) && !empty( $layoutData['meta'] ) ) {
            $metaData = $layoutData['meta'];
        } else {
            $metaData = array();
        }

        //Layout Paths
        $layoutPath = VIEW_PATH . '/layout.php';
        $layoutNavPath = VIEW_PATH . '/nav.php';
        $layoutFooterPath = VIEW_PATH . '/footer.php';
        $layoutMetaPath = VIEW_PATH . '/meta.php';

        $bodyLayoutPath = VIEW_PATH . '/' .$this->model.'/'.$this->action.'.php';

        require CORE_PATH . '/view.php';

        //Create Meta / Nav / Footer / Body Views Instances
        $bodyView = new View( $data, $bodyLayoutPath );
        $navView = new View( $navData, $layoutNavPath );
        $footerView = new View( $footerData, $layoutFooterPath );
        $metaView = new View( $metaData, $layoutMetaPath );

        //Creates an array that contains layouts required data 
        $renderData = array(
            'meta' => $metaView->render(), 
            'nav' => $navView->render(), 
            'content' => $bodyView->render(), 
            'footer' => $footerView->render(), 
            'more_stylesheets' => $moreStylesheets, 
            'more_scripts' => $moreScripts, 
        );

        //Render Full Layout
        $layoutView = new View($renderData, $layoutPath);

        return $layoutView->render();
    }
}