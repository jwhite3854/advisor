<?php
$postInputs = $data['postInputs'];
$movie = $data['movie'];

?>
<div class="row header">
    <div class="col-lg-12 text-center v-center">
        <h1>Movie Advisor</h1>
    </div>
</div> <!-- /row -->

<div class="py-1">
	<?php echo $data['statusMessage']; ?>
	<h3 class="mb-3">Selection Parameters</h3>
	<form action="<?php echo Url::render('/movies') ?>" method="post" class="needs-validation" id="picker_form" novalidate autocomplete="off">
		<div class="row">
			<div class="col-sm-6 mb-3">
				<label for="with_genres">Include Genre(s)</label>
				<select class="form-control custom-select d-block w-100" id="with_genres" name="with_genres[]" multiple="multiple">
					<option value=""></option>
				<?php foreach ( $data['allGenres'] as $id => $genre ): $select = ( is_array( $postInputs->with_genres ) && in_array($id, $postInputs->with_genres) ? 'selected' : '' ); ?>
					<option value="<?php echo $id ?>" <?php echo $select ?>><?php echo $genre ?></option>
				<?php endforeach; ?>
				</select>
			</div>

			<div class="col-sm-6 mb-3">
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
			<div class="col-md-6 col-sm-6 mb-3">
				<label for="with_cast">With Cast</label>
				<select class="form-control custom-select d-block w-100" id="with_cast" name="with_cast[]" multiple="multiple">
					<option value=""></option>
					<?php echo $data['with_cast_keynames']; ?>
				</select>
				<input type="hidden" name="with_cast_keynames" id="with_cast_keynames" />
			</div>
			<div class="col-md-6 col-sm-6 mb-3">
				<label for="with_crew">With Crew</label>
				<select class="form-control custom-select d-block w-100" id="with_crew" name="with_crew[]" multiple="multiple">
					<option value=""></option>
					<?php echo $data['with_crew_keynames']; ?>
				</select>
				<input type="hidden" name="with_crew_keynames" id="with_crew_keynames" />
			</div>
		</div>

		<div class="row">
			<div class="col-md-2 col-sm-12 mb-3">
				<label for="primary_release_year">Release Year</label>
				<input type="text" class="form-control" id="primary_release_year" name="primary_release_year" placeholder="Year" value="<?php echo $postInputs->primary_release_year ?>" />
			</div>
			<div class="col-md-2 col-6 mb-3">
				<label for="primary_release_date_gte">Release Yr >=</label>
				<input type="text" class="form-control" id="primary_release_date_gte" name="primary_release_date_gte" placeholder="Year" value="<?php echo $postInputs->primary_release_date_gte ?>" />
			</div>

			<div class="col-md-2 col-6 mb-3">
				<label for="primary_release_date_lte">Release Yr <=</label>
				<input type="text" class="form-control" id="primary_release_date_lte" name="primary_release_date_lte" placeholder="Year" value="<?php echo $postInputs->primary_release_date_lte ?>" />
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
		<input type="hidden" name="action" value="submit_movie_selector_form" />
	</form>
</div>
<?php if ( $movie ): ?>
<hr/>
<div id="selection_results" class="py-1">
	<h3 class="d-flex justify-content-between align-items-center mb-3">
		<span class="text-muted">Random Result</span>
		<span class="badge badge-secondary badge-pill"><?php echo $data['resultsCount']; ?></span>
	</h3>
	<div class="row">
		<div class="col-md-12">
			<div class="card flex-md-row mb-4 box-shadow h-md-250">
				<div class="card-img-left flex-auto d-none d-md-block align-items-start bg-mid" >
					<img src="<?php echo $movie->getPosterImage(); ?>" alt="<?php echo $movie->title ?>" />
				</div>
				<div class="card-body d-flex flex-column ">
					<h2 class="mb-2"><?php echo $movie->title ?></h2>
					<div class="mb-2 text-muted">
						Released: <?php echo ( !empty($movie->release_date) ? date('M j,', strtotime($movie->release_date)) : '?' ) ?> 
					<?php if ( !empty($movie->release_date) ): ?>
						<a href="#" class="set_search select_by_year" data-id="<?php echo date('Y', strtotime($movie->release_date)) ?>">
							<?php echo date('Y', strtotime($movie->release_date)) ?>
						</a>
					<?php endif; ?>
					</div>
					<div class="mb-2 text-muted">Rating: <?php echo $movie->vote_average ?> (<?php echo $movie->vote_count ?>)</div>
					<p class="card-text my-3"><?php echo $movie->overview ?></p>
					<p class="card-text mb-2">Cast: <?php echo $movie->getCastLinks() ?></p>
					<p class="card-text mb-2">Director: <?php echo $movie->getCrewLinks() ?></p>
					<p class="card-text mb-2">Writer(s): <?php echo $movie->getCrewLinks(false) ?></p>
					<p class="card-text mb-2">Genres: <?php echo $movie->getGenresLinks() ?></p>
					<p class="card-text mb-auto">Keywords: <?php echo $movie->getKeywordsLinks() ?></p>
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