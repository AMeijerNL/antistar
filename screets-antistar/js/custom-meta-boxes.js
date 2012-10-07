// Antistar Theme JS.

(function ( $ ) {
	$(document).ready(function() {
		
		// Hide unrelated meta boxes
		$('#screets_opts_embed').hide();
			
		// Display Video Embed meta box
		if($('#post-format-video').is(":checked")) {
		
			$('#screets_opts_embed').show();
		}
	});
}( jQuery ));