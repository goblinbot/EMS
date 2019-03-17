
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
//for making forms appear/disappear
var FormStuff = {
  
	init: function() {
	  // kick it off once, in case the radio is already checked when the page loads
	  this.applyConditionalRequired();
	  this.bindUIActions();
	},
	
	bindUIActions: function() {
	  // when a radio or checkbox changes value, click or otherwise
	  $("input[type='radio'], input[type='checkbox']").on("change", this.applyConditionalRequired);
	},
	
	applyConditionalRequired: function() {
	  // find each input that may be hidden or not
	  $(".require-if-active").each(function() {
		var el = $(this);
		// find the pairing radio or checkbox
		if ($(el.data("require-pair")).is(":checked")) {
		  // if its checked, the field should be required
		  el.prop("required", true);
		} else {
		  // otherwise it should not
		  el.prop("required", false);
		}
	  });
	}
  
  };
  
  FormStuff.init();