$(document).ready(function(){

	$('.cookie-info').on('click', function(){
		var v = $(this).data('v');

		$('.cookie-description').hide(0);
		$(v).fadeIn(300);
	});

});