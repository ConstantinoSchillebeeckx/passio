
// collect analytics data
(function( $ ) {

	var isiOS = navigator.userAgent.match(/(iPad|iPhone|iPod)/) !== null;
	var eventName = isiOS ? "pagehide" : "beforeunload"; // apple doesn't respect beforeunload

	'use strict';
	$(document).ready(function() { // fires when user visits site
		var id_raw = $('main').attr("id"); // e.g. page-102, post-12, cat-1 stores as ID in <main> tag
		if (id_raw != '') { // don't run for random pages like search results
			var id = id_raw.split('-')[1];
			var type = id_raw.split('-')[0];
			var dir = 'arrive';
			$.ajax({
				type: 'POST',
				url: passioAnalytics.ajaxurl,
				data : {
					action : 'process_visit',
					id : id,
					type : type,
					dir : dir
				},
				/*beforeSend:function(){
					loader.html('&nbsp;<div class="loader">Loading...</div>');
				},i */
				success: function(response){
					console.log(response);
				}
			});
		}
		return false;
	});
	window.addEventListener(eventName, function() { // fires when user leaves
		var id_raw = $('main').attr("id"); // e.g. page-102, post-12, cat-1
		var id = id_raw.split('-')[1];
		var type = id_raw.split('-')[0];
		var dir = 'leave';
		$.ajax({
			type: 'POST',
			url: passioAnalytics.ajaxurl,
			data : {
				action : 'process_visit',
				id : id,
				type : type,
				dir : dir
			},
			async:false,
			success: function(response){
				//alert(response);
			}
		});
	});
})( jQuery );
