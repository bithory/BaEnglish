<?php get_header(); ?>

	<nav class="navbar navbar-expand-lg main-nav navbar-dark fixed-top">

		<!-- Mobile - Version -->
		<a class="navbar-brand d-lg-none nav-element" href="#">Never expand</a>
		<button class="navbar-toggler collapsed d-lg-none nav-element" type="button" data-toggle="collapse" data-target="#navbarsExample01" aria-controls="navbarsExample01" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<!-- PC - Version -->
		<div class="d-none d-lg-block col-sm-2">
			<!--			<a class="navbar-brand nav-element" href="#">Never expand</a>-->
			<!--			<button class="navbar-toggler collapsed nav-element" type="button" data-toggle="collapse" data-target="#navbarsExample01" aria-controls="navbarsExample01" aria-expanded="false" aria-label="Toggle navigation">-->
			<!--				<span class="navbar-toggler-icon"></span>-->
			<!--			</button>-->
			<div class="bg-logo">
				<a href="#">
					<img src="./wp-content/themes/ba/assets/images/logo/BA-Logo.png" id="logo">
				</a>
			</div>
		</div>

		<div class="navbar-collapse collapse col-lg-10 short-l-p" id="navbarsExample01" style="">
			<ul id="nav-list" class="navbar-nav mr-auto ">

				<?php
				$menuLocations  = get_nav_menu_locations('top-menu');
				$menuID         = $menuLocations['top-menu'];
				$primaryNav     = wp_get_nav_menu_items($menuID);

				foreach($primaryNav as $item){
//						var_dump($item);
					echo '<li class="nav-item active align-content-lg-center"><a href="' . $item->url . '" class="nav-link">' . $item->title . '</a></li>';
				}
				?>
			</ul>
			<div class="">
				hello
			</div>
		</div>
	</nav>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-2"></div>
			<div class="col-lg-7 middle-l-p">
				<div id="nav-content-divider" class=""></div>
				<h1 class="mr-5"><?php the_title(); ?></h1>
				<article class="mr-5">
					<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
						<?php the_content(); ?>
					<?php endwhile; endif;?>
				</article>
			</div>
			<div class="col-lg-3">

				<div id="nav-img-divider" class=""></div>
				<div class="position-fixed">
					<img id="aside-pic"  src="./wp-content/themes/ba/assets/images/juan-ramos-97385-unsplash.png">
				</div>
			</div>
		</div>
	</div>
	<div class="pt-5 mt-5"></div>
	<footer class="fixed-bottom">
		<div class="col-sm-12 footer-line">
			<div class="row">
				<div class="col-sm-6 pt-2 pl-5 ml-2 pr-5 mr-5">
					<span class="align-bottom corp-name">Deutsch & Englischkurs von BA-English Communication Training GmbH</span>
				</div>

				<div class="col-sm-1 pl-1 footer-item"><a href="#">KONTAKT</a></div>
				<div class="col-sm-1 pl-1 footer-item"><a href="#">IMPRESSUM</a></div>
				<div class="col-sm-2 pl-1 footer-item"><a href="#">DATENSCHUTZ</a></div>
			</div>
		</div>
		</div>
	</footer>

<?php get_footer(); ?>