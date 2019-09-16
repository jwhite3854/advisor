<?php

class moviesController extends Controller {
    public function index($params){

        $movie_id = 0;
        if ( array_key_exists( "movie_id", $params ) ) {
            $movie_id = $params['movie_id'];
        }

        $movieAdvisor = new MovieAdvisor( $movie_id );

        $data = array(
            'statusMessage' => $movieAdvisor->getStatusMessage(),
            'postInputs' => $movieAdvisor->getPostInputs(),
            'allGenres' => $movieAdvisor->getGenres(),
            'with_keywords_keynames' => $movieAdvisor->getSelectedOptions( 'with_keywords_keynames' ),
            'without_keywords_keynames' => $movieAdvisor->getSelectedOptions( 'without_keywords_keynames' ),
            'with_cast_keynames' => $movieAdvisor->getSelectedOptions( 'with_cast_keynames' ),
            'with_crew_keynames' => $movieAdvisor->getSelectedOptions( 'with_crew_keynames' ),
            'resultsCount' => $movieAdvisor->getResultsCount(),
            'movie' => $movieAdvisor->getMovie()
        );

        $layoutData = array(
            'meta' => array(
                'title' => 'Movie Selector',
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