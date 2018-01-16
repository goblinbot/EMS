
function checkGridSupport() {
	var result;

		try {
			result = CSS.supports("display", "grid");
		}
		catch(err) {
			$('.account').html('<p style="font-size:1.7rem;font-weight:bold;" class="text-center">You are using a <u>severely outdated</u> browser. <br/>Please upgrade to a modern browser, like Firefox or Chrome.</p>');
		}

	if(result == false) {
		$('.account').html('<p style="font-size:1.7rem;font-weight:bold;" class="text-center">Your current browser does not support CSS grids. Please upgrade to a modern browser (i.e. Chrome/Firefox).</p>');
	} else {
		console.log('[[[ Grid support detected : Thank you for using a real browser! ]]]');
	}
}

/* tabjes navigeren */
function activateTab(target) {

	$('.tab').removeClass('on');
	$('#tab_'+target).addClass('on');

}


/* CLOCK */
function updateClock() {
	var currentTime = new Date();

  	var currentHours   = currentTime.getHours ( );
  	var currentMinutes = currentTime.getMinutes ( );
  	var currentSeconds = currentTime.getSeconds ( );

	/* Pad the minutes and seconds with leading zeros, if required */
	currentHours = ( currentHours < 10 ? "0" : "" ) + currentHours;
	currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
	currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

	/* currentHours = ( currentHours == 0 ) ? 00 : currentHours;*/

	/* Compose the string for display */
	var currentTimeString = currentHours + ":" + currentMinutes + "&nbsp;ECT";

 	$("#clock").html(currentTimeString);
}
$(document).ready(function() {
  setInterval('updateClock()', 5000);
});
