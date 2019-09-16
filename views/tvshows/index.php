<?php

$show = $data['show'];
$postInputs = $data['postInputs'];

?>
<div class="row header">
    <div class="col-lg-12 text-center v-center">
        <h1>TV Show Advisor</h1>
    </div>
</div> <!-- /row -->

<div class="py-1">
<?php echo $data['statusMessage']; ?>
    <h3 class="mb-3">Selection Parameters</h3>
    <form action="<?php echo Url::render('tv-shows') ?>" method="post" class="needs-validation" id="picker_form" novalidate autocomplete="off">
        <div class="row">
            <div class="col-6  mb-3">
                <label for="with_genres">Include Genre(s)</label>
                <select class="form-control custom-select d-block w-100" id="with_genres" name="with_genres[]" multiple="multiple">
                    <option value=""></option>
                <?php foreach ( $data['allGenres'] as $id => $genre ): $select = ( is_array( $postInputs->with_genres ) && in_array($id, $postInputs->with_genres) ? 'selected' : '' ); ?>
                    <option value="<?php echo $id ?>" <?php echo $select ?>><?php echo $genre ?></option>
                <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 mb-3">
                <label for="without_genres">Exclude Genre(s)</label>
                <select class="form-control custom-select d-block w-100" id="without_genres" name="without_genres[]" multiple="multiple">
                    <option value=""></option>
                <?php foreach ( $data['allGenres'] as $id => $genre ): $select = ( is_array( $postInputs->without_genres ) && in_array($id, $postInputs->without_genres) ? 'selected' : '' ); ?>
                    <option value="<?php echo $id ?>" <?php echo $select ?>><?php echo $genre ?></option>
                <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 mb-3">
                <label for="with_keywords">Include Keyword(s)</label>
                <select class="form-control custom-select d-block w-100" id="with_keywords" name="with_keywords[]" multiple="multiple">
                    <option value=""></option>
                    <?php echo $data['with_keywords_keynames']; ?>
                </select>
                <input type="hidden" name="with_keywords_keynames" id="with_keywords_keynames" />
            </div>
            <div class="col-sm-6 mb-3">
                <label for="without_keywords">Exclude Keyword(s)</label>
                <select class="form-control custom-select d-block w-100" id="without_keywords" name="without_keywords[]" multiple="multiple">
                    <option value=""></option>
                    <?php echo $data['without_keywords_keynames']; ?>
                </select>
                <input type="hidden" name="without_keywords_keynames" id="without_keywords_keynames" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 col-sm-12 mb-3">
                <label for="primary_release_year">Air Date Year</label>
                <input type="text" class="form-control" id="first_air_date_year" name="first_air_date_year" placeholder="Year" value="<?php echo $postInputs->first_air_date_year ?>" />
            </div>
            <div class="col-md-2 col-6 mb-3">
                <label for="primary_release_date_gte">Air Date Yr >=</label>
                <input type="text" class="form-control" id="first_air_date_gte" name="first_air_date_gte" placeholder="Year" value="<?php echo $postInputs->first_air_date_gte ?>" />
            </div>

            <div class="col-md-2 col-6 mb-3">
                <label for="primary_release_date_lte">Air Date Yr <=</label>
                <input type="text" class="form-control" id="first_air_date_lte" name="first_air_date_lte" placeholder="Year" value="<?php echo $postInputs->first_air_date_lte ?>" />
            </div>

            <div class="col-md-2 col-6 mb-3">
                <label for="with_runtime_gte">Runtime >=</label>
                <input type="text" class="form-control" id="with_runtime_gte" name="with_runtime_gte" placeholder="Min" value="<?php echo $postInputs->with_runtime_gte ?>" />
            </div>
            <div class="col-md-2 col-6  mb-3">
                <label for="with_runtime_lte">Runtime <=</label>
                <input type="text" class="form-control" id="with_runtime_lte" name="with_runtime_lte" placeholder="Min"  value="<?php echo $postInputs->with_runtime_lte ?>" />
            </div>

            <div class="col-md-2 col-sm-6 mb-3">
                <label>&nbsp;</label>
                <button class="btn btn-primary btn-block" type="submit">Search</button>
            </div>				
        </div>
        <input type="hidden" name="action" value="submit_tvshow_selector_form" />
    </form>
</div>
<?php if ( $show ): ?>
<hr/>
<div id="selection_results" class="py-1">
    <h3 class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-muted">Random Result</span>
        <span class="badge badge-secondary badge-pill"><?php echo $data['resultsCount']; ?></span>
    </h3>
    <div class="row">
        <div class="col-md-12">
            <div class="card flex-md-row mb-4 box-shadow h-md-250">
            <?php if( !empty($show->details['poster_path']) ):?>
                <div class="card-img-left flex-auto d-none d-md-block align-items-start bg-mid" >
                    <img src="<?php echo $show->getPosterImage(); ?>" alt="<?php echo $show->title ?>" />
                </div>
<?php endif; ?>
                <div class="card-body d-flex flex-column ">
                    <h2 class="mb-2"><?php echo $show->title ?></h2>
                    <div class="mb-2 text-muted">
                        First Aired: <?php echo ( !empty($show->release_date) ? date('M j,', strtotime($show->release_date)) : '?' ) ?> 
                    <?php if ( !empty($show->release_date) ): ?>
                        <a href="#" class="set_search select_by_year" data-id="<?php echo date('Y', strtotime($show->release_date)) ?>">
                            <?php echo date('Y', strtotime($show->release_date)) ?>
                        </a>
                    <?php endif; ?>
                    </div>
                    <div class="mb-2 text-muted">Rating: <?php echo $show->vote_average ?> (<?php echo $show->vote_count ?>)</div>
                    <?php if ( is_array($show->details['episode_run_time']) && array_key_exists(0, $show->details['episode_run_time']) ): ?>
                    <div class="mb-2 text-muted">Episode Runtime: <?php echo $show->details['episode_run_time'][0] ?> min</div>
<?php endif; ?>
                    <p class="card-text my-3"><?php echo $show->overview ?></p>
                    <p class="card-text mb-2">Cast: <?php echo $show->getCastLinks() ?></p>
                    <p class="card-text mb-2">Created By: <?php echo $show->getCreatedBy() ?></p>
                    <p class="card-text mb-2">Networks: <?php echo $show->getNetworks(); ?></p>
                    <p class="card-text mb-2">Genres: <?php echo $show->getGenresLinks() ?></p>
                    <p class="card-text mb-auto">Keywords: <?php echo $show->getKeywordsLinks() ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if ( $data['resultsCount'] === 0 ): ?>
<hr/>
<div id="selection_results" class="py-1">
	<div class="alert alert-warning" role="alert">Can't find any movies with the parameters given.</div>
</div>
<?php endif; ?>
