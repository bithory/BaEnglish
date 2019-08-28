
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

	let node0   = $('#count-0');
	let node1   = $('#count-1');
	let node2   = $('#count-2');

	let no0     = $(node0).attr('no');
	let no1     = $(node1).attr('no');
	let no2     = $(node2).attr('no');

	count(no0, node0);
	count(no1, node1);
	count(no2, node2);
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