<?php get_header(); ?>
<?php


/**
 * @param $menuName
 * @param $classes      = string with all classes which shoul be included
 */
//function renderMenu(){
//
//
//
//	if(is_array($primaryNav)){
//
//		foreach($primaryNav as $item){
//
//			echo '<li class="nav-item active align-content-lg-center ' . $classes . '"><a href="' . $item->url . '" class="nav-link">' . $item->title . '</a></li>';
//		}
//	}
//}

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
	<nav class="navbar navbar-expand-lg main-pc-nav navbar-dark fixed-top d-none d-lg-flex">

		<div class="d-lg-block col-sm-2">
			<div class="bg-logo">
				<a href="#">
					<img src="./wp-content/themes/ba/assets/images/logo/BA-Logo.png" id="logo">
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
	<nav class="navbar navbar-expand-lg main-mobile-nav navbar-dark fixed-top d-md-block d-lg-none">

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
					<?php $renderer->rederContent(); ?>
				</article>
			</div>
			<div class="col-lg-3">

				<div id="nav-img-divider" class=""></div>
				<div class="position-fixed">
<!--					<img id="aside-pic"  src="./wp-content/themes/ba/assets/images/juan-ramos-97385-unsplash.png">-->
					<img id="aside-pic"  src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/<?php getSiteImage();?>">
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

<?php get_footer(); ?>

<?php

class RenderClass{

	private $topMenu;
	private $footerMenu;

	private $headingNo;

	public function __construct()
	{

		$this->headingNo = 0;

		$this->loadNav('top-menu');
		$this->loadNav('footer-menu');
	}


	private function loadNav($menuName, $classes = ''){

		$menuLocations  = get_nav_menu_locations($menuName);
		$menuID         = $menuLocations[$menuName];

		if($menuName == 'top-menu')
			$this->topMenu = wp_get_nav_menu_items($menuID);
		else
			$this->footerMenu = wp_get_nav_menu_items($menuID);
	}

	public function getTopMenu($classes = ''){


		if(is_array($this->topMenu)){

			foreach($this->topMenu as $item){

				echo '<li class="nav-item active align-content-lg-center ' . $classes . '"><a href="' . $item->url . '" class="nav-link">' . $item->title . '</a></li>';
			}
		}

		return $this->topMenu;
	}

	public function getFooterMenu($classes = ''){

		if(is_array($this->footerMenu)){

			foreach($this->footerMenu as $item){

				echo '<li class="nav-item active align-content-lg-center ' . $classes . '"><a href="' . $item->url . '" class="nav-link">' . $item->title . '</a></li>';
			}
		}

		return $this->footerMenu;
	}

	public function rederContent(){

		if(have_posts()){

			while(have_posts()){

				the_post();
//				the_content();

				$str = get_the_content();

				if(strpos($str,'{toggle-elements}') !== false)
					$this->renderTogglebars($str);

				echo $str;
			}
		}
	}

	private function renderTogglebars(&$paramStr){

		$initSt         = '{toggle-elements}';
		$initEn         = '{/toggle-elements}';
		$itemTitleSt    = '{item-title}';
		$itemTitleEn    = '{/item-title}';
		$itemSt         = '{item}';
		$itemEn         = '{/item}';

		$toggleFrameSt  = '<div id="accordion">'.
				'<div class="card">';



		$toggleFrameEn  = '</div>'.
				'</div>';

//		while(strpos($paramStr, $itemTitleSt) || strpos($paramStr, $itemSt)){
		while(strpos($paramStr, $itemTitleSt)){


			$toggleTitleSt     =
				'<div class="card-header" id="heading-' . $this->headingNo . '">'.
				'<h5 class="mb-0">'.
				'<button class="btn btn-link" data-toggle="collapse" data-target="#collapse-' . $this->headingNo . '" aria-expanded="true" aria-controls="collapse-' . $this->headingNo . '">';
//
			$toggleTitleEn =
				'</button>'.
				'</h5>'.
				'</div>';

			$toggleContentSt =
				'<div id="collapse-' . $this->headingNo . '" class="collapse show" aria-labelledby="heading-' . $this->headingNo . '" data-parent="#accordion">'.
				'<div class="card-body">';

			$toggleContentEn =
				'</div>'.
				'</div>';

			$paramStr = substr_replace($paramStr, $toggleTitleSt, strpos($paramStr, $itemTitleSt), strlen($itemTitleSt));
			$paramStr = substr_replace($paramStr, $toggleContentSt, strpos($paramStr, $itemSt), strlen($itemSt));
			$paramStr = substr_replace($paramStr, $toggleContentEn, strpos($paramStr, $itemEn), strlen($itemEn));
			$paramStr = substr_replace($paramStr, $toggleTitleEn, strpos($paramStr, $itemTitleEn), strlen($itemTitleEn));
//
			$this->headingNo++;
		}

		$paramStr = str_replace($initSt, $toggleFrameSt , $paramStr);
		$paramStr = str_replace($initEn, $toggleFrameEn, $paramStr);
	}
}

?>
