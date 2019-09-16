(function ($) {
	'use strict';

	window.addEventListener('load', function () {

		var picker_form = document.getElementById('picker_form');
		picker_form.addEventListener('submit', function (event) {
			event.preventDefault();
			event.stopPropagation();

			var with_keywords_keynames = document.getElementById('with_keywords_keynames');
			var without_keywords_keynames = document.getElementById('without_keywords_keynames');
			var with_cast_keynames = document.getElementById('with_cast_keynames');
			var with_crew_keynames = document.getElementById('with_crew_keynames');

			var with_keywords = [];
			Array.prototype.slice.call(document.querySelectorAll('#with_keywords option:checked'),0).map(function(v) { 
				with_keywords.push(v.value+":"+v.label);
			});

			if ( with_keywords.length > 0 ) {
				with_keywords_keynames.value = with_keywords.join(";");
			}

			var without_keywords = [];
			Array.prototype.slice.call(document.querySelectorAll('#without_keywords option:checked'),0).map(function(v) { 
				without_keywords.push(v.value+":"+v.label);
			});

			if ( without_keywords.length > 0 ) {
				without_keywords_keynames.value = without_keywords.join(";");
			}

			var with_cast = [];
			Array.prototype.slice.call(document.querySelectorAll('#with_cast option:checked'),0).map(function(v) { 
				with_cast.push(v.value+":"+v.label);
			});

			if ( with_cast.length > 0 ) {
				with_cast_keynames.value = with_cast.join(";");
			}

			var with_crew = [];
			Array.prototype.slice.call(document.querySelectorAll('#with_crew option:checked'),0).map(function(v) { 
				with_crew.push(v.value+":"+v.label);
			});

			if ( with_crew.length > 0 ) {
				with_crew_keynames.value = with_crew.join(";");
			}
			
			picker_form.submit();

		}, false);

	}, false);

	$(document).ready(function() {

		$('#with_genres').select2({
			allowClear: true,
			placeholder: 'Select genres',
		});
		$('#without_genres').select2({
			allowClear: true,
			placeholder: 'Select genres',
		});

		$('#with_keywords').select2({
			allowClear: true,
			placeholder: 'Enter keywords',
			minimumInputLength: 3,
			ajax: {
				url: 'http://jwhite3854.com/advisor/api/keyword',
				dataType: 'json',
				delay: 250
			}
		});

		$('#without_keywords').select2({
			allowClear: true,
			placeholder: 'Enter keywords',
			minimumInputLength: 3,
			ajax: {
				url: 'http://jwhite3854.com/advisor/api/keyword',
				dataType: 'json',
				delay: 250
			}
		});
		
		$('#with_cast').select2({
			allowClear: true,
			placeholder: 'Enter Cast Names',
			minimumInputLength: 3,
			ajax: {
				url: 'http://jwhite3854.com/advisor/api/person',
				dataType: 'json',
				delay: 250
			}
		});
		$('#with_crew').select2({
			allowClear: true,
			placeholder: 'Enter Crew Names',
			minimumInputLength: 3,
			ajax: {
				url: 'http://jwhite3854.com/advisor/api/person',
				dataType: 'json',
				delay: 250
			}
		});
	});

	$("#selection_results").on("click", ".set_search", function(e){
		e.preventDefault();

		if ( $(this).hasClass("with_genre") ) {
			var genre_ids = $('#with_genres').val();
			var genre_id = $(this).data("id");
			genre_ids.push(genre_id);
			$('#with_genres').val(genre_ids).trigger('change');
		}

		if ( $(this).hasClass("dyn_add") ) {
			var option_target = $(this).data("target");
			var option_id = $(this).data("id");
			var option_text = $(this).text();
			var new_oprion = new Option(option_text, option_id, false, true);
			$(option_target).append(new_oprion).trigger('change');
		}

		if ( $(this).hasClass("select_by_year") ) {
			var option_text = $(this).data("id");
			$('#primary_release_year').val(option_text);
		}
	});

})(jQuery);