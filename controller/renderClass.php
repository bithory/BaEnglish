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

		$this->headingNo    = 0;
		$this->accordNo     = 0;

		$this->leftPanel    = '';
		$this->rightPanel   = '';

		$this->loadNav('top-menu');
		$this->loadNav('footer-menu');

		$this->imageDir = get_template_directory_uri() . '/assets/images/';
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

		$this->renderMenu($this->topMenu, 'down');
	}

	public function getFooterMenu($classes = ''){

		$this->renderMenu($this->footerMenu, 'up');
	}

	/**
	 * Render a Menu
	 *
	 * @param string $classes
	 */
	private function renderMenu($menu, $expDirection){

		if(is_array($menu)){

			$str = '';
			$arr = array();

			$i      = 0;
			$parEd  = false;

			$tempUrl    = '';
			$tempTitle  = '';

			foreach($menu as $item){

				if($item->menu_item_parent == 0){

					$arr[$i]['start']   = '<li class="' . ($expDirection == 'down' ? 'nav-item' : 'footer-item') . ' active align-content-lg-center ' . $classes . '"><a href="' . $item->url . '" class="nav-link">' . $item->title . '</a>';
					$arr[$i]['end']     = '</li>';
					$arr[$i]['nested']  = null;

					$tempUrl    = $item->url;
					$tempTitle  = $item->title;

					$i++;
				}
				else{
					$arr[$i - 1]['nested'][] = '<a class="dropdown-item" href="' . $item->url . '">' . $item->title . '</a>';

					if(!$parEd)
						$arr[$i - 1]['start'] = '<li class="' . ($expDirection == 'down' ? 'nav-item' : 'footer-item') . ' drop' . $expDirection . ' active align-content-lg-center ' . $classes . '"><a href="' . $tempUrl . '" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $tempTitle . '</a>';
				}

			}

			foreach($arr as $val){

				if($val['nested'] != null){

					$substr = '';

					foreach($val['nested'] as $val1){

						$substr .= $val1;
					}

					$str .= $val['start'] . '<div class="dropdown-menu" aria-labelledby="navbarDropdown">' . $substr . '</div>' . $val['end'];
				}
				else{

					$str .= $val['start'] . $val['end'];
				}
			}
		}

		echo $str;
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

		$str            = '';

		$initSt         = '{card-elements}';
		$initEn         = '{/card-elements}';
		$itemSt         = '{card-item}';
		$itemEn         = '{/card-item}';
		$itemTitleSt    = '{card-title}';
		$itemTitleGraySt= '{card-title-gray}';
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



		$cardNo = 0;
		$colClasses = 'col-md-12';

		$count = substr_count($paramStr, $itemSt);

		if($count == 2)
			$colClasses = 'col-xs-12 col-md-6';

		$paramStr = str_replace($initSt, $initPatternSt, $paramStr);
		$paramStr = str_replace($initEn, $initPatternEn, $paramStr);

		$paramStr = str_replace($itemSt, $itemPatternSt, $paramStr);
		$paramStr = str_replace($itemEn, $itemPatternEn, $paramStr);

		$paramStr = str_replace($itemTitleEn, $itemPatternTitleEn, $paramStr);

		//not only replacement because distinguis if left or right --> padding (because padding only in middel of both)
		for($i = 0; $i < $count && $i < 2; $i++){


			$blue = strpos($paramStr, $itemTitleSt);
			$gray = strpos($paramStr, $itemTitleGraySt);

			$order = '';

			if($blue < $gray){

				if($blue != null)
					$order = 'blue';
				else
					$order = 'gray';
			}
			else{

				if($gray != null)
					$order = 'gray';
				else
					$order = 'blue';
			}

			if($order === 'blue'){

				$itemPatternTitleSt =
					'<div class="' . $colClasses . ($count == 1 ? ' pl-0 pr-0 ' : ($i == 0 ? ' pl-0 pr-3 ' : ' pl-3 pr-0 ')) . '">' .
					'<div class="card cd-blue">'.
					'<div class="card-body">'.
					'<h5 class="card-title">';

				$paramStr = substr_replace($paramStr, $itemPatternTitleSt, $blue, strlen($itemTitleSt));
			}
			else{

				$itemPatternTitleSt =
					'<div class="' . $colClasses . ($count == 1 ? ' pl-0 pr-0 ' : ($i == 0 ? ' pl-0 pr-3 ' : ' pl-3 pr-0 ')) . '">' .
					'<div class="card cd-blue bg-secondary">'.
					'<div class="card-body">'.
					'<h5 class="card-title">';

				$paramStr = substr_replace($paramStr, $itemPatternTitleSt, $gray, strlen($itemTitleGraySt));
			}
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
				'<div id="collapse-' . $this->headingNo . '" class="collapse' . ($i == 0? ' show ' . $i . ' ' : '') . '" aria-labelledby="heading-' . $this->headingNo . '" data-parent="#accordion-' . $this->accordNo . '">'.
					'<div class="card-body acc-text">';

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

		if($this->AsidePic == null)
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

	public function renderLogo(){

		if(function_exists('the_custom_logo')){

			$html = get_custom_logo('logo');
			$html = str_replace('custom-logo"', 'custom-logo" id="logo" ', $html);

			echo $html;
		}
	}
}