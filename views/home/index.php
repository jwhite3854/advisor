<div class="row header">
    <div class="col-lg-12 text-center v-center">
        <h1>Advisor</h1>
        <h3>Give some parameters, have it advise you on a movie or TV show.</h3>
    </div>
</div> <!-- /row -->

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h3>Movies</h3></div>
            <div class="panel-body">
                Browse movies based on...
                <ul style="padding-left: 24px;">
                    <li>Genres - including or excluding</li>
                    <li>Keywords - including or excluding</li>
                    <li>Cast of actors, Directors, Writers</li>
                    <li>Release year - exact or in a range</li>
                    <li>Run time range</li>
                </ul>
                <a href="<?php echo Url::render('movies') ?>">Browse Movies</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h3>TV Shows</h3></div>
            <div class="panel-body">
                Browse TV shows based on...
                <ul style="padding-left: 24px;">
                    <li>Genres - including or excluding</li>
                    <li>Keywords - including or excluding</li>
                    <li>Release year - exact or in a range</li>
                    <li>Episode run time range</li>
                </ul>
                <a href="<?php echo Url::render('tv-shows') ?>">Browse TV Shows</a>
            </div>
        </div>
    </div>
</div>
