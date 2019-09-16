<?php

class View
{
    protected $data;
    protected $path;

    /**
     * View constructor.
     * @param $data
     * @param $path
     */
    public function __construct( &$data, $path )
    {
        if( file_exists($path) ) {
            $this->path = $path;   
        } 
            
        $this->data = $data;
    }

    public function render(){

        $data = &$this -> data;
        if ( !empty( $this->path ) ) {
            ob_start();
            require $this->path;
            $content = ob_get_clean();
        } else {
            $content = 'Template does not exist';
        }

        return $content;
    }
}