#Advisor MVC

## Suggests movies or TV shows based on user input

Example at http://jwhite3854.com/advisor

Data files (keywords and people) hosted there.

Requires config.php file in web root directory to work. Example:
`
<?php

// Die if accessed directly
if ( !defined( 'APP_ROOT' ) ) {
	die();
}

$settings = array(
    "domain" => "http://jwhite3854.com",
    "redirect_base" => "/advisor/",
    "app_title" => "Media Advisor",
    "tmdb_api_key" => "----YOUR TMDb API KEY----"
);
`
