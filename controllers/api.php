<?php

class apiController extends Controller {
    public function keyword( $param ){
        
        $results = array();
        if ( array_key_exists( 'term', $param ) && strlen( $param['term'] ) > 2 ) {
            $results = Advisor_Selector_Keyword::suggest( $param['term'] );
        } 

        return json_encode($results);
    }

    public function person( $param ){
        $results = array();
        if ( array_key_exists( 'term', $param ) && strlen( $param['term'] ) > 2 ) {
            $results = Advisor_Selector_Person::suggest( $param['term'] );
        } 

        return json_encode($results);
    }

}