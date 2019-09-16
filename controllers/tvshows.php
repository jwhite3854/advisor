<?php

class tvshowsController extends Controller {

    public function index($params){

        $show_id = 0;
        if ( array_key_exists( "show_id", $params ) ) {
            $show_id = $params['show_id'];
        }

        $showAdvisor = new TVShowAdvisor( $show_id );

        $data = array(
            'statusMessage' => $showAdvisor->getStatusMessage(),
            'postInputs' => $showAdvisor->getPostInputs(),
            'allGenres' => $showAdvisor->getGenres(),
            'with_keywords_keynames' => $showAdvisor->getSelectedOptions( 'with_keywords_keynames' ),
            'without_keywords_keynames' => $showAdvisor->getSelectedOptions( 'without_keywords_keynames' ),
            'resultsCount' => $showAdvisor->getResultsCount(),
            'show' => $showAdvisor->getShow()
        );

        $layoutData = array(
            'meta' => array(
                'title' => 'TV Show Selector',
                'robots' => 'index,follow'
            ),
        );

        $moreStylesheets = array(
            array(
                'href' =>  Url::render('/assets/css/select2.min.css'),
                'media' => 'all'
            ),
        );

        $moreScripts = array(
            array(
                'src' =>  Url::render('/assets/js/select2.min.js'),
                'async' => false,
                'defer' => false
            ),
            array(
                'src' =>  Url::render('/assets/js/advisor.js'),
                'async' => false,
                'defer' => false
            ),
        );
        
        return $this->render( $data, $layoutData, $moreStylesheets, $moreScripts );
    }
}