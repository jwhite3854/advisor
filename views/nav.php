<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="<?php echo Url::render('/') ?>">Advisor</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColl" aria-controls="navbarColl" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColl">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo Url::render('movies') ?>">Movies</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo Url::render('tv-shows') ?>">TV Shows</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo Url::render('about') ?>">About</a>
            </li>
        </ul>
    </div>
</nav>