
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

	//set background image
	let img = $('#hidden-background').children().attr('src');
	$('#content-wrapper').css('background', 'url(\'' +  img + '\')  no-repeat center center fixed');
	// $('#content-wrapper-mobile').css('background', 'url(\'' +  img + '\') no-repeat ');
	$('#content-wrapper-mobile').css('background', 'url(\'' +  img + '\') no-repeat center fixed');

	//init counter
	let nodes = $('.count-no');

	$.each(nodes, function(val){

		let no = $(this).attr('no');
		count(no, this);
	});
}

function count(no, node){

	let i       = 0;
	let inter   = parseInt('' + no * 0.1);

	let func    = setInterval(function(){

		$(node).children().text(i);

		i += inter;

		if(i > no){

			$(node).children().text(no);
			clearInterval(func);
		}
	}, 80);
}