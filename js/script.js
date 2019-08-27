
$(document).ready(function(){

	initAccordion();

	// $('#hamburger').click();
	// console.log('hello');

	//to toggle the accordion angles
	$('.acc-btn').click(function (){

		$('.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-right');

		let expand = $(this).attr('aria-expanded');

		if(expand == 'false')
			$(this).children().removeClass('fa-angle-right').addClass('fa-angle-down');
	});
});

function initAccordion(){

	$('.acc-collapse').collapse();
}

function initHomePage(){

	let img = $('#hidden-background').children().attr('src');
	$('#content-wrapper').css('background', 'url(\'' +  img + '\'');
}