// Antistar Theme JS.

(function ( $ ) {
	$(document).ready(function() {
		
		// Fancybox (Automatically group and apply fancyBox to all images)
		$("a[href$='.jpg'],a[href$='.jpeg'],a[href$='.png'],a[href$='.gif']").attr('rel', 'gallery').fancybox({
			helpers:  {
				title : {
					type : 'inside'
				},
				overlay : {
					css : {
						'background' : 'rgba(0,0,0,0.85)'
					}
				},
				 thumbs : {
					width: 50,
					height: 50
				}
			}
		});
		
	});
	
	
	/**
	* Skeleton Tabs
	* Copyright 2011, Dave Gamache
	* www.getskeleton.com
	* Free to use under the MIT license.
	* http://www.opensource.org/licenses/mit-license.php
	* 8/17/2011
	*/
	// hash change handler
	function hashchange () {
		var hash = window.location.hash
		, el = $('ul.tabs [href*="' + hash + '"]')
		, content = $(hash)

		if (el.length && !el.hasClass('active') && content.length) {
			el.closest('.tabs').find('.active').removeClass('active');
			el.addClass('active');
			content.show().addClass('active').siblings().hide().removeClass('active');
		}
	}

	// listen on event and fire right away
	$(window).on('hashchange.skeleton', hashchange);
	hashchange();
	$(hashchange);
}( jQuery ));