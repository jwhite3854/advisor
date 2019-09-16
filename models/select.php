<?php

class Advisor_Selector 
{
    private $api_key;
    private $configuration;
    private $genres = array();
    protected $urlBase;
   
    public function __construct()
    {
        $this->api_key =  AdvisorApp::getConfig('tmdb_api_key');
        $this->setConfigs();
        $this->setGenres();
        $this->urlBase = 'https://api.themoviedb.org/3/';
    }

    private function setConfigs() 
    {
        $config_resp = '{"images":{"base_url":"http://image.tmdb.org/t/p/","secure_base_url":"https://image.tmdb.org/t/p/","backdrop_sizes":["w300","w780","w1280","original"],"logo_sizes":["w45","w92","w154","w185","w300","w500","original"],"poster_sizes":["w92","w154","w185","w342","w500","w780","original"],"profile_sizes":["w45","w185","h632","original"],"still_sizes":["w92","w185","w300","original"]},"change_keys":["adult","air_date","also_known_as","alternative_titles","biography","birthday","budget","cast","certifications","character_names","created_by","crew","deathday","episode","episode_number","episode_run_time","freebase_id","freebase_mid","general","genres","guest_stars","homepage","images","imdb_id","languages","name","network","origin_country","original_name","original_title","overview","parts","place_of_birth","plot_keywords","production_code","production_companies","production_countries","releases","revenue","runtime","season","season_number","season_regular","spoken_languages","status","tagline","title","translations","tvdb_id","tvrage_id","type","video","videos"]}';
        $this->configuration = json_decode($config_resp, true);
    }

    private function setGenres() 
    {
        $genres_resp = '{"genres":[{"id":28,"name":"Action"},{"id":12,"name":"Adventure"},{"id":16,"name":"Animation"},{"id":35,"name":"Comedy"},{"id":80,"name":"Crime"},{"id":99,"name":"Documentary"},{"id":18,"name":"Drama"},{"id":10751,"name":"Family"},{"id":14,"name":"Fantasy"},{"id":36,"name":"History"},{"id":27,"name":"Horror"},{"id":10402,"name":"Music"},{"id":9648,"name":"Mystery"},{"id":10749,"name":"Romance"},{"id":878,"name":"Science Fiction"},{"id":10770,"name":"TV Movie"},{"id":53,"name":"Thriller"},{"id":10752,"name":"War"},{"id":37,"name":"Western"}]}';
        $genres_sel = json_decode($genres_resp, true);
        foreach ( $genres_sel['genres'] as $genre ) {
            $key = $genre['id'];
            $this->genres[$key] = $genre['name'];
        }
    }

    public function getImagePath( $size, $image_file, $secure = true )
    {
        $base = $this->configuration['images']['base_url'];
        if ($secure) {
            $this->configuration['images']['secure_base_url'];
        }
        return $base . 'w' . $size . $image_file;
    }

    public function getGenres()
    {
        return $this->genres;
    }

    public function getResults($url)
    {
        $keyed_url = $url .'&api_key=' . $this->api_key;

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $keyed_url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $response = curl_exec($ch); 
        curl_close($ch); 
		
		return json_decode($response, true);
    }
}