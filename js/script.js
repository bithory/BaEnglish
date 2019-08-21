
$(document).ready(function(){

	initAccordion();

	$('#hamburger').click();
	console.log('hello');

	//to toggle the accordion angles
	$('.acc-btn').click(function (){

		$('.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-right');

		let expand = $(this).attr('aria-expanded');

		if(expand == 'false')
			$(this).children().removeClass('fa-angle-right').addClass('fa-angle-down');
	});
});

function initAccordion(){

	let count = 0;

	// $('.collapse').collapse(function(){
	//
	// 	$('.first-collapse').click();
	// });
}