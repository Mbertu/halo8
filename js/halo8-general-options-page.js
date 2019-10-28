(function ($) {
	"use strict";
	$(function () {
		$('#overlay').change(function(){
			var data = {
				'action': 'refresh_overlay_preview',
		        'selectedOverlay': $(this).val()
		    };

		    // The variable ajax_url should be the URL of the admin-ajax.php file
		    $.post( ajaxurl, data, function(response) {
		    	var overlay_preview_container = $('.overlay_preview_container');
		    	if(overlay_preview_container.length)
		    		$(overlay_preview_container[0]).replaceWith(response);
		    	else
		        	$('.empty_overlay_preview_container').replaceWith(response);
		    });

		});
	});
}(jQuery));