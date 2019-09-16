<?php

require MODEL_PATH.'/select.php';

class MovieAdvisor extends Advisor_Selector
{
    private $queryString;
    private $postInputs = array();
    private $totalResults = false;
    private $movie = false;
    private $statusMessage = false;

    private $with_keywords_keynames;
    private $without_keywords_keynames;
    private $with_cast_keynames;
    private $with_crew_keynames;

    
    public function __construct( $movie_id = 0, $random_selection = true  )
    {
        parent::__construct();
        $movie = false;

        // if the user submits the form to browse movies
        $action = filter_input(INPUT_POST, 'action' );
        if ( !empty($action) && $action == 'submit_movie_selector_form' ) {

            // Get all the values entered by the user
            $this->setPostInputs();

            // Parse those values into a string to pass to TMDb
            $this->buildQueryString();

            // Get the movie selection
            $movie = $this->doSelection( $random_selection );

        } elseif ( $movie_id > 0 ) { // if the user provides the Movie ID to get a single movie directly
            // Get the single movie
            $movie = $this->getSingle( $movie_id );
        }

        // if we found a movie from form submission or single selection, set it
        if ( $movie ) {
            $this->movie = new Movie_Result($this, $movie);
        }
    }

    public function getMovie()
    {
        return $this->movie;
    }

    public function getResultsCount()
    {
        return $this->totalResults;
    }

    private function setPostInputs()
    {
        $with_genres = filter_input(INPUT_POST, 'with_genres', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );
        $without_genres = filter_input(INPUT_POST, 'without_genres', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );
        $with_keywords = filter_input(INPUT_POST, 'with_keywords', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
        $without_keywords = filter_input(INPUT_POST, 'without_keywords', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
        $with_cast = filter_input(INPUT_POST, 'with_cast', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
        $with_crew = filter_input(INPUT_POST, 'with_crew', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
        
        $primary_release_year = filter_input(INPUT_POST, 'primary_release_year', FILTER_VALIDATE_INT );
        $primary_release_date_gte = filter_input(INPUT_POST, 'primary_release_date_gte', FILTER_VALIDATE_INT );
        $primary_release_date_lte = filter_input(INPUT_POST, 'primary_release_date_lte', FILTER_VALIDATE_INT );
        $with_runtime_gte = filter_input(INPUT_POST, 'with_runtime_gte', FILTER_VALIDATE_INT );
        $with_runtime_lte = filter_input(INPUT_POST, 'with_runtime_lte', FILTER_VALIDATE_INT );

        $this->with_keywords_keynames = filter_input(INPUT_POST, 'with_keywords_keynames');
        $this->without_keywords_keynames = filter_input(INPUT_POST, 'without_keywords_keynames');
        $this->with_cast_keynames = filter_input(INPUT_POST, 'with_cast_keynames');
        $this->with_crew_keynames = filter_input(INPUT_POST, 'with_crew_keynames');

        $inputs = array(
            'with_genres' => $with_genres,
            'without_genres' => $without_genres,
            'with_keywords' => $with_keywords,
            'without_keywords' => $without_keywords,
            'with_cast' => $with_cast,
            'with_crew' => $with_crew,
            'primary_release_year' => $primary_release_year,
            'primary_release_date_gte' => $primary_release_date_gte,
            'primary_release_date_lte' => $primary_release_date_lte,
            'with_runtime_gte' => $with_runtime_gte,
            'with_runtime_lte' => $with_runtime_lte,
        );

        $this->postInputs = new Movie_Advisor_Input($inputs);
    }

    public function getPostInputs()
    {
        return $this->postInputs;
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

        $post = $this->getPostInputs();

        if ( !empty($post->with_genres) ) {
            $query[] = 'with_genres='.implode(',',$post->with_genres);
        }

        if ( !empty($post->without_genres) ) {
            $query[] = 'without_genres='.implode(',',$post->without_genres);
        }

        if ( count( $post->with_keywords ) > 0 ) {
            $query[] = 'with_keywords='.implode(',', $post->with_keywords);
        }

        if ( count( $post->without_keywords ) > 0 ) {
            $query[] = 'without_keywords='.implode(',', $post->without_keywords);
        }

        if ( count($post->with_cast) > 0 ) {
            $query[] = 'with_cast='.implode(',', $post->with_cast);
        }

        if ( count($post->with_crew) > 0 ) {
            $query[] = 'with_crew='.implode(',', $post->with_crew);
        }

        if ( $post->primary_release_year >= 1900 && $post->primary_release_year <= $next_year ) {
            $query[] = 'primary_release_year='.$post->primary_release_year;
        }
        
        if ( $post->primary_release_date_gte >= 1900 && $post->primary_release_date_gte <= $next_year ) {
            $query[] = 'primary_release_date.gte='.$post->primary_release_date_gte;
        }

        if ( $post->primary_release_date_lte >= 1900 && $post->primary_release_date_lte <= $next_year ) {
            $query[] = 'primary_release_date.lte='.$post->primary_release_date_lte;
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
        $movies = array(false);
        if ( !empty($this->queryString) ) {
            $url = $this->urlBase . 'discover/movie?page=1&include_video=false&include_adult=false&language=en-US&'.$this->queryString;
                        
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
                $movies = $results['results'];
            }
        } 
        
        $idx = 0;
        if ( $random && count($movies) > 1 ) {
            $ct = count($movies);
            $idx = rand(0, ($ct - 1));
        } 

        return $movies[$idx];
    }

    private function getSingle( $movie_id )
    {
        $url = $this->urlBase . 'movie/'. $movie_id .'?language=en-US';
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

class Movie_Advisor_Input
{
    public $with_genres = array();
    public $without_genres = array();
    public $with_keywords = array();
    public $without_keywords = array();
    public $with_cast = array();
    public $with_crew = array();

    public $primary_release_year;
    public $primary_release_date_gte;
    public $primary_release_date_lte;
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


class Movie_Result
{
    private $selector;
    private $movie;
    private $movie_id;
    private $genre_ids;
    private $poster_path;
    private $credit_ids;

    public $title;
    public $release_date;
    public $overview;
    public $vote_average;
    public $vote_count;

    public function __construct( MovieAdvisor $selector,  $movie )
    {
        $this->selector = $selector;
        $this->movie = $movie;
        $this->movie_id = $movie['id'];
        $this->title = $movie['title'];
        $this->genre_ids = ( array_key_exists( 'genre_ids', $movie ) ? $movie['genre_ids'] : array_column( $movie['genres'], 'id' ) );
        $this->overview = $movie['overview'];
        $this->release_date = $movie['release_date'];
        $this->overview = $movie['overview'];
        $this->poster_path = $movie['poster_path'];
        $this->vote_average = $movie['vote_average'];
        $this->vote_count = $movie['vote_count'];

        $this->credit_ids = $this->getCreditsIds($this->movie_id);
    }

    public function getPosterImage()
    {
        return $this->selector->getImagePath( '300', $this->poster_path );
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
        $url = 'https://api.themoviedb.org/3/movie/'. $this->movie_id .'/keywords?language=en-US';

        $keywords = $this->selector->getResults($url);
		$key_list = array();
		foreach ( $keywords['keywords'] as $keyword ) {
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
                $cast_list[] = '<a href="#" class="set_search dyn_add" data-id="'. $id .'" data-target="#with_cast">' . $cast .'</a>';
                $i++;
                if ( $i == $limit ) {
                    break;
                }
            }
        }
        
        return implode(', ', $cast_list);
    }

    public function getCrewLinks( $is_director = true )
    {
        $crew_links = array();
        $crews = ( $is_director ? $this->credit_ids['director'] : $this->credit_ids['writer'] );
        if ( count($crews) > 0 ) {
            foreach ( $crews as $id => $crew ) {
                $crew_links[] = '<a href="#" class="set_search dyn_add" data-id="'. $id .'" data-target="#with_crew">' . $crew .'</a>';
            }
        }

        return implode(', ', $crew_links);
    }

    private function getCreditsIds()
    {
        $url = 'https://api.themoviedb.org/3/movie/'. $this->movie_id  .'/credits?language=en-US';
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

		foreach ( $credits['crew'] as $crew ) {
			
			if ( strtolower($crew['job']) == 'director' ) {
				$id = $crew['id'];
				$credits_ids['director'][$id] = $crew['name'];
			}

			if ( strtolower($crew['job']) == 'writing' || strtolower($crew['department']) == 'writing' ) {
				$id = $crew['id'];
				$credits_ids['writer'][$id] = $crew['name'];
			}
        }
        
        return $credits_ids;
    }

    public function getDetails()
    {
        $url = 'https://api.themoviedb.org/3/movie/'.$movie['id'].'?language=en-US&api_key='.$api_key.$data;

        var_dump($url);

		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		$response = curl_exec($ch); 
		curl_close($ch); 

		$details = json_decode($response, true);
    }
}