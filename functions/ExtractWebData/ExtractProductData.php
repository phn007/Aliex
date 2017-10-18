<?php
class ExtractProductData extends ExtractData {
	var $vars;

	function getProductData($contents, $vars) {
		$this->vars = $vars;

		$results  = $this->findFootprint($this->productFootprint, $contents);
		$regxName = $results['regxName'];
		$matches  = $results['matches'];
		return $this->$regxName($matches);
	}
	function Li_Class_list_items($matches) {
		$classname = "Li_Class_list_items_FootPrint";
		setLogs($classname, $this->vars['logs']);
		return $this->dataCollection($classname, $matches[0]);
	}
	function Ul_Id_hs_below_list_items1($matches) {
		$classname = "Ul_Id_hs_below_list_items_Footprint";
		setLogs($classname . '-1', $this->vars['logs']);
		return $this->dataCollection($classname, $matches[1][0]);
	}
	function Ul_Id_hs_below_list_items2($matches) {
		$classname = "Ul_Id_hs_below_list_items_Footprint";
		setLogs($classname . '-2', $this->vars['logs']);
		return $this->dataCollection($classname, $matches[1][0]);
	}
	function Ul_Id_hs_below_list_items3($matches) {
		$classname = "Ul_Id_hs_below_list_items_Footprint";
		setLogs($classname . '-3', $this->vars['logs']);
		return $this->dataCollection($classname, $matches[1][0]);
	}
	function dataCollection($classname, $matches) {
		$e = new $classname();
		$html = $e->extractListItems($matches);
		foreach ($html as $item ) {
			$list = array(
				'title'    => $e->getTitle($item),
				'link'     => $e->getLink($item),
				'img'      => $e->getImageUrl($item),
				'altTitle' => $e->getAtagTitle($item)
			);
			if(!empty($list['title'])) $result[] = $list;
		}
		return $result;
	}
}/// Class ExtractProductData

class Ul_Id_hs_below_list_items_Footprint {
	use ExtractProductType1;

	function extractListItems($matches) {
		$html = str_get_html($matches);
		$li = $html->find('li');
		//$html->clear();
		unset($html);
		return $li;
	}
}
class Li_Class_list_items_FootPrint {
	use ExtractProductType1;

	function extractListItems($matches) {
		foreach( $matches as $list ) {
			$items = $items . $list;
		}
		$html = str_get_html($items);
		$li = $html->find('li');
		//$html->clear();
		unset($html);
		return $li;
	}
}

trait ExtractProductType1 {
		function getTitle($item) {
		//$title = $item->find('h3',0)->plaintext;
		$title = $item->find('a.product',0)->plaintext;
		$title = htmlspecialchars_decode($title, ENT_QUOTES);
		return $title;
	}
	function getLink($item) {
		$link = $item->find('a.product',0)->href;
		$link = htmlspecialchars_decode($link,ENT_QUOTES);
		$link = 'https:'. $link;
		return $link;
	}
	function getImageUrl($item) {
		$pattern = '/src="(.+?[^"])"/';
		preg_match($pattern, $item, $match);
		$img = str_replace('https:', '', $match[1]);
		$img = 'https:' . $img;
		return htmlspecialchars_decode($img, ENT_QUOTES);
	}
	function getAtagTitle($item) {
		return htmlspecialchars_decode($item->find('a.product',0)->title, ENT_QUOTES);
	}
}