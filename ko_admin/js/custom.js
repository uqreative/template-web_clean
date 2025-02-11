/*
 * custom js Document
 * koweb
*/

//scroll bar
$(function(){
	jQuery('.scrollbar-dynamic').scrollbar();

	//main check
	if ($(".log_area").length > 0) {
		$('body').addClass('main');
	}	
});
