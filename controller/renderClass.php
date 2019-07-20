<?php


class RenderClass
{


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

	/**
	 * This function search in the output string for accordion / togglebar placeholder and replace it with
	 * the bootstrap dom model
	 *
	 * @param string $paramStr = the html output string
	 */
	private function renderTogglebars(&$paramStr){

		$initSt         = '{toggle-elements}';
		$initEn         = '{/toggle-elements}';
		$itemTitleSt    = '{item-title}';
		$itemTitleEn    = '{/item-title}';
		$itemSt         = '{item}';
		$itemEn         = '{/item}';

		$toggleFrameSt  =
			'<div id="accordion">'.
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
				'<div id="collapse-' . $this->headingNo . '" class="collapse show" aria-labelledby="heading-' . $this->headingNo . '" data-parent="#accordion">'.
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
		}

		//delete false <br> from the frame (init elements)
		while(strpos($paramStr, $initSt) !== false){

			$rep    = '<br>';
			$leng   = strlen($rep);

			$pos        = strpos($paramStr, $initSt) + strlen($initSt);

			if($pos != false)
				$paramStr   = substr_replace($paramStr, '', $pos, $leng);

			$paramStr = substr_replace($paramStr, $toggleFrameSt, strpos($paramStr, $initSt), strlen($initSt));
			$paramStr = substr_replace($paramStr, $toggleFrameEn, strpos($paramStr, $initEn), strlen($initEn));
		}
	}
}