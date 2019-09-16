<?php

require MODEL_PATH.'/select.php';

class TVShowAdvisor extends Advisor_Selector
{
    private $queryString;
    private $postInputs = array();
    private $totalResults = false;
    private $show = false;
    private $statusMessage = false;

    private $with_keywords_keynames;
    private $without_keywords_keynames;

    public function __construct( $show_id = 0, $random_selection = true  )
    {
        parent::__construct();
        $show = false;

        // if the user submits the form to browse shows
        $action = filter_input(INPUT_POST, 'action' );
        if ( !empty($action) && $action == 'submit_tvshow_selector_form' ) {
            // Get all the values entered by the user
            $this->setPostInputs();

            // Parse those values into a string to pass to TMDb
            $this->buildQueryString();

            // Get the show selection
            $show = $this->doSelection( $random_selection );
        } elseif ( $show_id > 0 ) { // if the user provides the Show ID to get a single show directly
            // Get the single show
            $show = $this->getSingle( $show_id );
        }

        // if we found a show from form submission or single selection, set it
        if ( $show ) {
            $this->show = new Show_Result($this, $show);
        }
    }

    public function getShow()
    {
        return $this->show;
    }

    private function setPostInputs()
    {
        $with_genres = filter_input(INPUT_POST, 'with_genres', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );
        $without_genres = filter_input(INPUT_POST, 'without_genres', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );
        $with_keywords = filter_input(INPUT_POST, 'with_keywords', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
        $without_keywords = filter_input(INPUT_POST, 'without_keywords', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

        $first_air_date_year = filter_input(INPUT_POST, 'first_air_date_year', FILTER_VALIDATE_INT );
        $first_air_date_gte = filter_input(INPUT_POST, 'first_air_date_gte', FILTER_VALIDATE_INT );
        $first_air_date_lte = filter_input(INPUT_POST, 'first_air_date_lte', FILTER_VALIDATE_INT );
        $with_runtime_gte = filter_input(INPUT_POST, 'with_runtime_gte', FILTER_VALIDATE_INT );
        $with_runtime_lte = filter_input(INPUT_POST, 'with_runtime_lte', FILTER_VALIDATE_INT );

        $this->with_keywords_keynames = filter_input(INPUT_POST, 'with_keywords_keynames');
        $this->without_keywords_keynames = filter_input(INPUT_POST, 'without_keywords_keynames');;

        $inputs = array(
            'with_genres' => $with_genres,
            'without_genres' => $without_genres,
            'with_keywords' => $with_keywords,
            'without_keywords' => $without_keywords,
            'first_air_date_year' => $first_air_date_year,
            'first_air_date_gte' => $first_air_date_gte,
            'first_air_date_lte' => $first_air_date_lte,
            'with_runtime_gte' => $with_runtime_gte,
            'with_runtime_lte' => $with_runtime_lte,
        );

        $this->postInputs = new Show_Advisor_Input($inputs);
    }

    public function getPostInputs()
    {
        return $this->postInputs;
    }

    public function getResultsCount()
    {
        return $this->resultsCount;
    }

    public function getSelectedOptions( $options_type )
    {
        $opt = '';
        if ( !empty( $this->$options_type ) ) {
            $options = explode(';', $this->$options_type);
            foreach ( $options as $option ) {
                $keys = explode(':', $option);
                if ( count($keys) == 2 ) {
                    $opt .= '<option value="'.$keys[0].'" selected="selected">'.$keys[1].'</option>';
                }
            }
        }
        
        return $opt;
    }

    private function buildQueryString()
    {
        $next_year = date('Y') + 1;
        $query = array();

        $post = $this->postInputs;

        if ( !empty($post->with_genres) ) {
            $query[] = 'with_genres='.implode(',',$post->with_genres);
        }

        if ( !empty($post->without_genres) ) {
            $query[] = 'without_genres='.implode(',',$post->without_genres);
        }

        if ( count($post->with_keywords) > 0 ) {
            $query[] = 'with_keywords='.implode(',',$post->with_keywords);
        }

        if ( count($post->without_keywords) > 0 ) {
            $query[] = 'without_keywords='.implode(',',$post->without_keywords);
        }

        if ( $post->first_air_date_year >= 1900 && $post->first_air_date_year <= $next_year ) {
            $query[] = 'first_air_date_year='.$post->first_air_date_year;
        }
        
        if ( $post->first_air_date_gte >= 1900 && $post->first_air_date_gte <= $next_year ) {
            $query[] = 'first_air_date.gte='.$post->first_air_date_gte;
        }

        if ( $post->first_air_date_lte >= 1900 && $post->first_air_date_lte <= $next_year ) {
            $query[] = 'first_air_date.lte='.$post->first_air_date_lte;
        }

        if ( !empty($post->with_runtime_gte) && $post->with_runtime_gte > 0 ) {
            $query[] = 'with_runtime.gte='.$post->with_runtime_gte;
        }

        if ( !empty($post->with_runtime_lte) && $post->with_runtime_lte > 0 ) {
            $query[] = 'with_runtime.lte='.$post->with_runtime_lte;
        }

        $this->queryString = implode('&' , $query);
    }

    private function doSelection( $random = true )
    {
        $shows = array(false);
        if ( !empty($this->queryString) ) {
            $url = 'https://api.themoviedb.org/3/discover/tv?page=1&language=en-US&'.$this->queryString;
            $results = $this->getResults($url);

            if ( empty($results) ) {
                $this->statusMessage = 'Looks like TMDb is down.';
                return;
            }

            if ( array_key_exists('success', $results) && !$results['success'] ) {
                $this->statusMessage = $results['status_message'];
                return;
            }

            $this->totalResults = $results['total_results'];
            if ( $results['total_results'] > 0) {
                $shows = $results['results'];
            }
        } 
        
        $idx = 0;
        if ( $random && count($shows) > 1 ) {
            $ct = count($shows);
            $idx = rand(0, ($ct - 1));
        } 

        return $shows[$idx];
    }

    private function getSingle( $show_id )
    {
        $url = 'https://api.themoviedb.org/3/tv/'. $show_id .'?language=en-US';
        $this->totalResults = 1;

        return $this->getResults($url);
    }

    public function getStatusMessage()
    {
        $message = '';
        if ($this->statusMessage) {
            $message = '<div class="alert alert-warning" role="alert">'.$this->statusMessage.'</div>';
        }

        return $message;
    }
}


class Show_Advisor_Input
{
    public $with_genres = array();
    public $without_genres = array();
    public $with_keywords = array();
    public $without_keywords = array();

    public $first_air_date_year;
    public $first_air_date_gte;
    public $first_air_date_lte;
    public $with_runtime_gte;
    public $with_runtime_lte;

    public function __construct( $inputs )
    {
        foreach ( $inputs as $key => $value ) {
            if ( property_exists( $this, $key ) ) {
                $this->$key = $value;
            }
        }

        return $this;
    }
}


class Show_Result 
{
    private $selector;
    private $show;
    private $show_id;
    private $genre_ids;
    private $poster_path;
    private $credit_ids;

    public $title;
    public $release_date;
    public $overview;
    public $vote_average;
    public $vote_count;
    public $details;

    public function __construct( TVShowAdvisor $selector,  $show )
    {
        $this->selector = $selector;
      
        $this->show = $show;
        $this->show_id = $show['id'];
        $this->title = $show['name'];
        $this->release_date = $show['first_air_date'];
        $this->overview = $show['overview'];
        $this->genre_ids = ( array_key_exists( 'genre_ids', $show ) ? $show['genre_ids'] : array_column( $show['genres'], 'id' ) );
        $this->vote_average = $show['vote_average'];
        $this->vote_count = $show['vote_count'];

        $this->credit_ids = $this->getCreditsIds();
        $this->details = $this->getDetails();

    }

    public function getPosterImage()
    {
        return $this->selector->getImagePath( '300', $this->details['poster_path'] );
    }

    public function getNetworks()
    {
        $networks = array();
        if ( is_array( $this->details['networks'] ) ) {
            foreach ( $this->details['networks'] as $network ) {
                $networks[] = $network['name'];
            }
        }

        return implode(", ", $networks);
    }

    public function getCreatedBy()
    {
        $creators = array();
        if ( is_array( $this->details['created_by'] ) ) {
            foreach ( $this->details['created_by'] as $creator ) {
                $creators[] = $creator['name'];
            }
        }

        return implode(", ", $creators);
    }

    public function getGenresLinks()
    {
        $genres_links = array();
        $all_genres = $this->selector->getGenres();

        foreach ( $this->genre_ids as $genre_id ) {
			$genres_links[] = '<a href="#" class="set_search with_genre" data-id="'. $genre_id .'">' . $all_genres[$genre_id] .'</a>';
        }

        return implode(', ', $genres_links);
    }

    public function getKeywordsLinks()
    {
        $url = 'https://api.themoviedb.org/3/tv/'. $this->show_id .'/keywords?language=en-US';

        $keywords = $this->selector->getResults($url);
        $key_list = array();
		foreach ( $keywords['results'] as $keyword ) {
			$key_list[] = '<a href="#" class="set_search dyn_add" data-id="'. $keyword['id'] .'" data-target="#with_keywords">' . $keyword['name'] .'</a>';
        }
        
        return implode(', ', $key_list);
    }

    public function getCastLinks( $limit = 5 )
    {
        $cast_list = array();
        $i = 0;
        if ( count($this->credit_ids['cast']) > 0 ) {
            foreach ( $this->credit_ids['cast'] as $id => $cast ) {
                $cast_list[] = $cast;
                $i++;
                if ( $i == $limit ) {
                    break;
                }
            }
        }
        
        return implode(', ', $cast_list);
    }

    private function getCreditsIds()
    {
        $url = 'https://api.themoviedb.org/3/tv/'. $this->show_id  .'/credits?language=en-US';
        $credits = $this->selector->getResults($url);

        $credits_ids = array(
            'cast' => array(),
            'director' => array(),
            'writer' => array(),
        );

		foreach ( $credits['cast'] as $cast ) {
            $cast_id = $cast['id'];
			$credits_ids['cast'][$cast_id] = $cast['name'];
		}
        
        return $credits_ids;
    }

    public function getDetails()
    {
        $url = 'https://api.themoviedb.org/3/tv/'.$this->show_id.'?language=en-US';

        return $this->selector->getResults($url);
    }
}