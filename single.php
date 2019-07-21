<?php get_header(); ?>
<?php

include_once './wp-content/themes/ba/controller/renderClass.php';


function getSiteImage(){

	$url        = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	$BASEURL    = 'http://localhost/wordpress/';

	$actPage    = substr($url, strlen($BASEURL));

	if(strlen($actPage) < 1)
		echo 'juan-ramos-97385-unsplash.png';
	else
		echo $actPage;
}

$renderer = new RenderClass();

?>

<!-- PC - NAV  -->
<nav class="navbar navbar-expand-lg main-pc-nav navbar-dark fixed-top d-none d-lg-flex cd-blue">

	<div class="d-lg-block col-sm-2">
		<div class="bg-logo">
			<a href="#">
				<img src="../wp-content/themes/ba/assets/images/logo/BA-Logo.png" id="logo">
			</a>
		</div>
	</div>

	<div class="navbar-collapse collapse col-lg-10 short-l-p" id="navbarsExample01" style="">
		<ul id="nav-list" class="navbar-nav mr-auto ">

			<?php //renderMenu('top-menu'); ?>
			<?php $renderer->getTopMenu(); ?>
		</ul>
		<div class="">
		</div>
	</div>
</nav>
<!-- MOBILE NAV -->
<nav class="navbar navbar-expand-lg main-mobile-nav navbar-dark fixed-top d-md-block d-lg-none cd-blue">

	<!-- Mobile - Version -->
	<button class="navbar-toggler collapsed d-lg-none nav-element" type="button" data-toggle="collapse" data-target="#navbarsExample01" aria-controls="navbarsExample01" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="navbar-collapse collapse col-lg-10 short-l-p" id="navbarsExample01" style="">
		<ul id="nav-list" class="navbar-nav mr-auto ">

			<?php //renderMenu('top-menu'); ?>
			<?php $renderer->getTopMenu(); ?>
		</ul>
		<div class="">
		</div>
	</div>
</nav>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-2"></div>
		<div class="col-lg-7 middle-l-p">
			<div id="nav-content-divider" class=""></div>
			<h1 class="mr-5 mb-2"><?php the_title(); ?></h1>
			<article class="mr-5 mt-5">
				<?php $renderer->renderContent(); ?>
			</article>
		</div>
		<div class="col-lg-3">

			<div id="nav-img-divider" class=""></div>
			<div class="position-fixed">
					<img id="aside-pic"  src="../wp-content/themes/ba/assets/images/juan-ramos-97385-unsplash.png">
<!--				<img id="aside-pic"  src="--><?php //bloginfo('stylesheet_directory'); ?><!--/assets/images/--><?php //getSiteImage();?><!--">-->
			</div>
		</div>
	</div>
</div>
<div class="pt-5 mt-5"></div>
<footer class="fixed-bottom">

	<nav class="navbar navbar-expand-lg footer-line fixed-bottom corp-name">


		<div class="navbar-brand navbar-expand col-sm-6">
			Deutsch & Englischkurs von BA-English Communication Training GmbH
		</div>

		<div class="navbar-collapse collapse col-sm-6 short-l-p" id="navbarsExample01" style="">
			<ul id="" class="navbar-nav mr-auto ">

				<?php //renderMenu('footer-menu', 'footer-item'); ?>
				<?php $renderer->getFooterMenu('footer-item'); ?>
			</ul>
		</div>
	</nav>
</footer>
<script>
	$(document).ready(function(){

		initAccordion();

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

		$('.collapse').collapse(function(){

			$('.first-collapse').click();
		});
	}


</script>

<?php get_footer(); ?>