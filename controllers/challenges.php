<?php

class challengesController extends Controller {

    public function reverser($params){

        $layoutData = array(
            'meta' => array(
                'title' => 'String Reverser',
            ),
        );
        
        return $this->render( array(), $layoutData );
    }

    public function primorial($params){

        $layoutData = array(
            'meta' => array(
                'title' => 'Primorial Generator',
            ),
        );
        
        return $this->render( array(), $layoutData );
    }
}