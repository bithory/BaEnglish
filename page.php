<?php get_header(); ?>
<?php

include_once './wp-content/themes/ba/controller/renderClass.php';


$renderer = new RenderClass();

?>
	<!-- PC - NAV  -->
	<nav class="navbar navbar-expand-lg main-pc-nav navbar-dark fixed-top d-none d-lg-flex cd-blue">

		<div class="d-lg-block col-sm-2">
			<div class="bg-logo">
				<?php $renderer->renderLogo(); ?>
			</div>
		</div>

		<div class="navbar-collapse collapse col-lg-10 short-l-p" id="navbarsExample01" style="">
			<ul id="nav-list" class="navbar-nav mr-auto ">
				<!--				--><?php $renderer->getTopMenu(); ?>
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
				<?php $renderer->getTopMenu(); ?>
			</ul>
			<div class="">
			</div>
		</div>
	</nav>
	<!-- mobile devices -->
	<div class="d-xl-none">
		<div class="row">
			<div class="col-lg-2"></div>
			<div class="col-lg-10 middle-l-p">
				<div id="nav-content-divider-mobile" class=""></div>
				<h1 class="mr-5 mb-2"><?php the_title(); ?></h1>
				<article class="mr-5 mt-5">
					<?php $renderer->renderContent(); ?>
				</article>
			</div>
		</div>
	</div>
	<!-- pc -->
	<div class="d-none d-xl-block container-fluid">
		<div class="row">
			<div class="col-lg-2 col-xl-2"></div>
			<div class="col-lg-7 col-xl-7 middle-l-p">
				<div id="nav-content-divider" class=""></div>
				<h1 class="mr-5 mb-2"><?php the_title(); ?></h1>
				<article class="mr-5 mt-5">
					<?php $renderer->renderContent(); ?>
				</article>
			</div>
			<div class="col-lg-3 col-xl-3">

<!--				<div id="nav-img-divider" class=""></div>-->
				<div id="" class=""></div>
				<div class="position-fixed">
					<img id="aside-pic" src="<?php $renderer->getSiteImage();?>">
				</div>
			</div>
			<div class="col-sm-2"></div>
		</div>
	</div>
	<div class="container-fluid">
	</div>
	<div class="pt-5 mt-5"></div>
	<footer class="fixed-bottom">

		<nav class="navbar navbar-expand-lg footer-line fixed-bottom">


			<div class="navbar-brand navbar-expand col-sm-6 corp-name">
				Deutsch & Englischkurs von BA-English Communication Training GmbH
			</div>

			<div class="navbar-collapse collapse col-sm-6 short-l-p" id="navbarsExample01" style="">
				<ul id="" class="navbar-nav mr-auto ">
					<?php $renderer->getFooterMenu('footer-item'); ?>
				</ul>
			</div>
		</nav>
	</footer>

<?php get_footer(); ?>