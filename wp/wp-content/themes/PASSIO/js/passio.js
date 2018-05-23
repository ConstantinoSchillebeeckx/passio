
// handles the seeking of videos from the table of contents
function seek(el, time, clickPlayer) {

	var isiOS = navigator.userAgent.match(/(iPad|iPhone|iPod)/) !== null;
	var activePlayer = videojs(clickPlayer);

	if (!isiOS) {
		activePlayer.currentTime(time);
		activePlayer.play();
	} else { //http://stackoverflow.com/a/18283198/1153897
		activePlayer.load();
		activePlayer.pause();
		setTimeout(function(){ activePlayer.currentTime(time); activePlayer.play(); }, 1000);
	}
	$(el).parent().addClass("alert alert-info").siblings().removeClass("alert alert-info"); // add alert class to selected div
}

// function is called by videoJS on updateTime event
// used to highlight the current chatper user is seeing
function highlightChapter(time, player, times) {
	var next = find_cuepoint(time, times);

	$("[id^=toc-" + player + "-]").removeClass("alert alert-info");
	$('#toc-' + player + '-' + next).addClass("alert alert-info");
}

// given a number (represents current time of video)
// and an array of all que points
// this will return the next cue point
function find_cuepoint(num, arr) {

	for (var val = 0; val < arr.length; val++) {
		if (num >= arr[val]) {
			return arr[val];
		}
	}
}



function doAJAX(data) {

    // send via AJAX to process with PHP
    jQuery.ajax({
            url: ajax_object.ajax_url, 
            type: "GET",
            data: data, 
            dataType: 'json',
            success: function(response) {
                jQuery('#spin').html(''); // remove spinner
                jQuery('#response').html(response); 
            },
            error: function(xhr, status, error) {
            }
    });
}
