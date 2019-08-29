<?php


class RenderClass
{


	private $topMenu;
	private $footerMenu;

	private $headingNo;
	private $accordNo;

	private $imageDir;

	private $AsidePic;

	//for the home site
	private $leftPanel;
	private $rightPanel;
	private $rightPanelMobile;

	public function __construct()
	{

		$this->AsidePic = get_the_post_thumbnail( null, 'large');

//		echo $this->AsidePic;

		$this->headingNo    = 0;
		$this->accordNo     = 0;

		$this->leftPanel    = '';
		$this->rightPanel   = '';

		$this->loadNav('top-menu');
		$this->loadNav('footer-menu');

		$this->imageDir = './wp-content/themes/ba/assets/images/';

		$this->correctImageDir();
	}

	/**
	 * correct the $this->imageDir link in relation to the actual url
	 * filters folder before the links
	 */
	private function correctImageDir(){

		$basic  = 'wordpress/';
		$url    = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$dir    = $this->imageDir;

		$substr = substr($url, strpos($url, $basic) + strlen($basic));

		if(strlen($substr) > 0)
			$dir = '.' . $dir;

		$this->imageDir = $dir;
	}

	public function renderImageDir(){

		echo $this->imageDir;
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

	public function renderContent(){

		$paraArr    = array();
		$count      = false;

		if(have_posts()){
			$i = 0;
			while(have_posts()){

				the_post();

				$str = get_the_content();

				if(strpos($str, '{/count}'))
					$count = true;

				$paraArr    = explode('<!-- wp:paragraph -->', $str);
				$str        = '';

				if($count){

					$this->renderCounter($paraArr);
					return;
				}

				if(is_array($paraArr)){

					foreach($paraArr as $val){

						$val = '<!-- wp:paragraph -->' . $val;

						if(strpos($val,'{accordion-elements}') !== false)
							$str .= $this->renderTogglebars($val);
						else if(strpos($val,'{card-elements}') !== false)
							$str .= $this->renderCards($val);
						else
							$str .= $val;
					}
				}

				$str = do_shortcode($str, true);

				echo $str;
			}
		}
	}

	private function renderCards($paramStr){

		$initSt         = '{card-elements}';
		$initEn         = '{/card-elements}';
		$itemSt         = '{card-item}';
		$itemEn         = '{/card-item}';
		$itemTitleSt    = '{card-title}';
		$itemTitleEn    = '{/card-title}';


		$initPatternSt =
			'<div class="container">'.
			'<div class="row">';

		$itemPatternTitleEn =
						'</h5>';

		$itemPatternSt =
						'<p class="card-text">';

		$itemPatternEn =
						'</p>'.
					'</div>'.
				'</div>'.
				'</div>';

		$initPatternEn =
			'</div>'.
			'</div>';

		$cardAmount = substr_count($paramStr, $itemSt);
		$cardNo     = 0;

//		echo $cardAmount . '<br>';
//		echo htmlspecialchars($paramStr);
//		echo '<hr>';

		while(strpos($paramStr, $initSt) !== false){


			//maximum of one card column are 3 cards
			//to check for the bootstrap col-<value>
			$start  = strpos($paramStr, $initSt);
			$end    = strpos($paramStr, $initEn) - $start + strlen($initEn);
			$substr = substr($paramStr, $start, $end);

			$count  = substr_count($substr, $itemSt);


			$rep    = '<br>';
			$leng   = strlen($rep);
			$pos    = $start + strlen($initSt);

			if($pos !== false)
				$paramStr   = substr_replace($paramStr, '', $pos, $leng);

			//3 as maximum of cards as columns
			for($i = 0; $i < $count && $i < 2; $i++){



				$pos        = strpos($paramStr, $itemEn) + strlen($itemEn);

				if($pos !== false)
					$paramStr   = substr_replace($paramStr, '', $pos, $leng);


				$colClasses = 'col-md-12';

				if($count == 2)
					$colClasses = 'col-xs-12 col-md-6';
//				elseif($count == 3)
//					$colClasses = 'col-xs-12 col-sm-6 col-md-4';

				$itemPatternTitleSt =
					'<div class="' . $colClasses . ($cardAmount == 1 ? ' pl-0 pr-0 ' : ($cardNo == 0 ? ' pl-0 pr-3 ' : ' pl-3 pr-0 ')) . '">' .
					'<div class="card cd-blue">'.
					'<div class="card-body">'.
					'<h5 class="card-title">';

				$cardNo++;

				//can be optimated: strlengths could be calculated on time globaly and replace it in the functions
				$paramStr = substr_replace($paramStr, $itemPatternTitleSt, strpos($paramStr, $itemTitleSt), strlen($itemTitleSt));
				$paramStr = substr_replace($paramStr, $itemPatternSt, strpos($paramStr, $itemSt), strlen($itemSt));
				$paramStr = substr_replace($paramStr, $itemPatternEn, strpos($paramStr, $itemEn), strlen($itemEn));
				$paramStr = substr_replace($paramStr, $itemPatternTitleEn, strpos($paramStr, $itemTitleEn), strlen($itemTitleEn));
			}

			$paramStr = substr_replace($paramStr, $initPatternSt, $start, strlen($initSt));
			$paramStr = substr_replace($paramStr, $initPatternEn , strpos($paramStr, $initEn), strlen($initEn));
		}

		return $paramStr;
	}

	/**
	 * This function search in the output string for accordion / togglebar placeholder and replace it with
	 * the bootstrap dom model
	 *
	 * @param string $paramStr = the html output string
	 */
	private function renderTogglebars($paramStr){

		$initSt         = '{accordion-elements}';
		$initEn         = '{/accordion-elements}';
		$itemTitleSt    = '{acc-item-title}';
		$itemTitleEn    = '{/acc-item-title}';
		$itemSt         = '{acc-item}';
		$itemEn         = '{/acc-item}';

		$i  = 0;

		$toggleFrameSt  =
			'<div id="accordion-' . ++$this->accordNo . '">'.
				'<div class="card">';



		$toggleFrameEn  =
				'</div>'.
			'</div>';

		while(strpos($paramStr, $itemTitleSt) || strpos($paramStr, $itemSt)){


			$toggleTitleSt     =
				'<div class="card-header" id="heading-' . $this->headingNo . '">'.
					'<h5 class="mb-0">'.
						'<button class="btn btn-link acc-btn" data-toggle="collapse" data-target="#collapse-' . $this->headingNo . '" aria-expanded="false" aria-controls="collapse-' . $this->headingNo . '"><i class="fas fa-angle-right"></i> ';

			$toggleTitleEn =
						'</button>'.
					'</h5>'.
				'</div>';

			$toggleContentSt =
				'<div id="collapse-' . $this->headingNo . '" class="collapse ' . ($i == 0? ' show ' . $i . ' ' : '') . '" aria-labelledby="heading-' . $this->headingNo . '" data-parent="#accordion-' . $this->accordNo . '">'.
					'<div class="card-body">';

			$toggleContentEn =
					'</div>'.
				'</div>';


			//<br> have to deleted as first because it is simple to use placeholders as needles to find them
			//because card text could have <br> but card frames shouldn't have some
			//delete false <br>
			$rep    = '<br>';
			$leng   = strlen($rep);

			$pos        = strpos($paramStr, $itemTitleEn) + strlen($itemTitleEn);

			if($pos != false)
				$paramStr   = substr_replace($paramStr, '', $pos, $leng);

			$pos        = strpos($paramStr, $itemEn) + strlen($itemEn);

			if($pos != false)
				$paramStr   = substr_replace($paramStr, '', $pos, $leng);

			//replace placeholders
			$paramStr = substr_replace($paramStr, $toggleTitleSt, strpos($paramStr, $itemTitleSt), strlen($itemTitleSt));
			$paramStr = substr_replace($paramStr, $toggleContentSt, strpos($paramStr, $itemSt), strlen($itemSt));
			$paramStr = substr_replace($paramStr, $toggleContentEn, strpos($paramStr, $itemEn), strlen($itemEn));
			$paramStr = substr_replace($paramStr, $toggleTitleEn, strpos($paramStr, $itemTitleEn), strlen($itemTitleEn));

			$this->headingNo++;
			$i++;
		}

		//delete false <br> from the frame (init elements)
		while(strpos($paramStr, $initSt) !== false){

			$rep    = '<br>';
			$leng   = strlen($rep);

			$pos    = strpos($paramStr, $initSt) + strlen($initSt);

			if($pos != false)
				$paramStr   = substr_replace($paramStr, '', $pos, $leng);

			$paramStr = substr_replace($paramStr, $toggleFrameSt, strpos($paramStr, $initSt), strlen($initSt));
			$paramStr = substr_replace($paramStr, $toggleFrameEn, strpos($paramStr, $initEn), strlen($initEn));
		}

		return $paramStr;
	}

	private function renderCounter(Array &$param){

		$str        = '';
		$th         = '';
		$td         = '';

		$itemSt     = '{count}';
		$itemEn     = '{/count}';
		$titleSt    = '{count-title}';
		$titleEn    = '{/count-title}';
		$noSt       = '{count-no}';
		$noEn       = '{/count-no}';

		$pattern    = '<div class="col-sm-4">' .
							'<div class="col-sm-12 count-title">' .
								$titleSt .
							'</div>' .
							'<div id="count-{id}" class="col-sm-12 count-no" no="' . $noSt . '">' .
								'<span>' . $noSt . '</span>' .
							'</div>' .
						'</div>';

		$patternMobile = '<thead>' .
							'<tr>' .
								'{th}' .
							'</tr>' .
						'</thead>' .
						'<tbody>' .
							'<tr>' .
								'{td}' .
							'</tr>' .
						'</tbody>';

		$this->leftPanel    = str_replace('<!-- /wp:paragraph -->', '', $param[1]);

		unset($param[0]);
		unset($param[1]);

		if($param != null){

			foreach($param as $key => $val){

				//pc
				$st     = strpos($val, $titleSt) + strlen($titleSt);
				$end    = strpos($val, $titleEn) - $st;

				$title  = substr($val, $st, $end);

				$st     = strpos($val, $noSt) + strlen($noSt);
				$end    = strpos($val, $noEn) - $st;

				$no     = substr($val, $st, $end);

				$temp   = $pattern;

				$temp   = str_replace($titleSt, $title, $pattern);
				$temp   = str_replace($noSt, $no, $temp);

				$temp   = str_replace('{id}', $this->headingNo++, $temp);

				$this->rightPanel .= $temp;

				//mobile
				$th .= '<th class="count-title" scope="col">' . $title . '</th>';
				$td .= '<td class="count-no" no="' . $no . '"><span>' . $no . '</span></td>';
			}

			$patternMobile = str_replace('{th}', $th, $patternMobile);
			$patternMobile = str_replace('{td}', $td, $patternMobile);

			$this->rightPanelMobile = $patternMobile;
		}
	}

	public function getSiteImage(){

		if($this->AsidePic == '')
			$this->AsidePic = $this->imageDir . 'juan-ramos-97385-unsplash.png';

		echo $this->AsidePic;
	}

	public function getBackgroundImage(){

		echo get_the_post_thumbnail( null, 'full');
	}

	public function renderLeftPanel(){
		echo $this->leftPanel;
	}

	public function renderRightPanel(){
		echo $this->rightPanel;
	}

	public function renderRightPanelMobile(){
		echo $this->rightPanelMobile;
	}
}