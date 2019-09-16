<?php

/*
 Add the routes needed for the app. 
 
 'Homepage' already assumed to exist at home (controller) / index (action)

 AdvisorApp::addRoute( request/uri/path, controller, action );

*/

AdvisorApp::addRoute( 'about', 'home', 'about' );
AdvisorApp::addRoute( 'movies', 'movies', 'index' );
AdvisorApp::addRoute( 'tv-shows', 'tvshows', 'index' );

AdvisorApp::addRoute( 'api/keyword', 'api', 'keyword' );
AdvisorApp::addRoute( 'api/person', 'api', 'person' );

AdvisorApp::addRoute( 'challenges/reverser', 'challenges', 'reverser' );
AdvisorApp::addRoute( 'challenges/primorial', 'challenges', 'primorial' );