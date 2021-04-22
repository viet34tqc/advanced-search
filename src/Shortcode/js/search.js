( function ( $ ) {
	"use strict";

	let searchCache = {};

	// track the currect AJAX request.
	let sameAjaxRequest;

	// get cached post titles from the params object of wp_localize_script.
	let cachedPostTitles = ( params?.cached_post_titles && Object.keys( params.cached_post_titles ).length ) ? params.cached_post_titles : false;

	$( ".nv-as-input" ).autocomplete( {
		delay: 300,
		minLength: 3,
		source: function ( request, response ) {
			const searchTitlesForSuggestions = cachedPostTitles => {
				// Filter the title.
				const suggestions = cachedPostTitles.filter( post => {
					return post.title.toLowerCase().includes( request.term.toLowerCase() );
				} );

				// Save into global searchCache.
				searchCache[ request.term ] = suggestions;
				return suggestions;
			};

			// check if the search key was already cached in the HashMap.
			if ( request.term in searchCache ) {
				// return suggestions using the cache object.
				response( searchCache[ request.term ] );
				// exit and avoid an ajax call as we can use data that was cached in earlier ajax calls.
				return;
			}
			// else check if cached post tiles exists and search key is not cached.
			// Do the search.
			else if ( cachedPostTitles ) {
				const searchSuggestions = searchTitlesForSuggestions( cachedPostTitles );
				response( searchSuggestions );

				// exit and avoid an ajax call as we can use data that was cached in earlier ajax calls.
				return;

			}

			// Else Make an AJAX Request.
			// AJAX call is made if wp_localize_script sent an empty array for post titles.
			sameAjaxRequest = $.post( {
				url: params.ajaxurl,
				dataType: "json",
				data: {
					action: "nv_advanced_search",
					term: request.term
				}
			} );

			// on success.
			sameAjaxRequest
				.done( function ( data ) {
					// data contains the post titles sent by the AJAX handler.
					var searchSuggestions = searchTitlesForSuggestions( data );
					// return the suggestions for the search term.
					response( searchSuggestions );
				} )
				// on failure.
				.fail( function () {
					$( ".nv-as-input" ).css( "background-color", "yellow" );
					$( ".nv-as-input" ).val( "An error occurred ..." );
				} );
		}
	} )
		.autocomplete( "instance" )._renderItem = function ( ul, item ) {
			return $( "<li>" )
				.append( `<a href=${ item.link }>${ item.title }</a>` )
				.appendTo( ul );
		};

} )( jQuery );
