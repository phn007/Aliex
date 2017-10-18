<?php
define( 'BASE_PATH', __DIR__ . '/' );
require_once BASE_PATH . 'libs/simplehtmldom_1_5/simple_html_dom.php';
include BASE_PATH . "dev/scraping/functions.php";
date_default_timezone_set('Asia/Bangkok');

$url = "https://www.aliexpress.com/wholesale?minPrice=&maxPrice=100&isBigSale=n&isFreeShip=n&isNew=n&isFavorite=n&isMobileExclusive=n&isLocalReturn=n&shipCountry=TH&shipFromCountry=&shipCompanies=&SearchText=xiaomi&CatId=0&needQuery=y";
$url = "https://www.aliexpress.com/wholesale?site=glo&page=1&SearchText=xiaomi";

$url = "https://www.aliexpress.com/category/200003482/dresses.html?spm=2114.search0103.4.2.MKEbQU&site=glo&g=y";//invalid
$url = "https://www.aliexpress.com/wholesale?minPrice=&maxPrice=100&isBigSale=n&isFreeShip=n&isNew=n&isFavorite=n&isMobileExclusive=n&isLocalReturn=n&shipCountry=TH&shipFromCountry=&shipCompanies=&SearchText=clothing&CatId=0&g=y&initiative_id=SB_20171006161741&needQuery=y";



$url = "https://www.aliexpress.com/category/200214496/bikinis-set.html?spm=2114.search0103.4.2.1swwDO&site=glo";
$url = "https://www.aliexpress.com/category/200000567/baby-girls-clothing.html?spm=2114.search0103.4.1.XOHAse&site=glo&g=y";
$url = "https://www.aliexpress.com/category/322/shoes.html?spm=2114.search0103.108.2.LRlXBv";
$url = "https://www.aliexpress.com/category/100004817/kitchen-tools-gadgets.html?spm=2114.search0103.4.5.PoLFVh&site=glo&g=y";
$url = "https://www.aliexpress.com/category/200214074/women-bracelet-watches.html?spm=2114.search0103.4.1.pbvYGC&site=glo&g=y";
$url = "https://www.aliexpress.com/category/200002395/camera-photo.html?spm=2114.search0103.2.1.pXFXDf&site=glo";
$url = "https://www.aliexpress.com/category/200002361/tablet-accessories.html?spm=2114.search0103.2.19.SuNQb1&site=glo";
$url = "https://www.aliexpress.com/category/5090301/mobile-phones.html?spm=2114.search0103.1.1.XUPESj&site=glo";
$url = "https://www.aliexpress.com/category/200000707/tops-tees.html?spm=2114.search0103.4.3.m5rzug&site=glo&g=y";
$url = "https://www.aliexpress.com/category/200000783/sweaters.html?spm=2114.search0103.4.7.9sc1uh&site=glo&g=y";


$url = "https://www.aliexpress.com/category/200215713/car-light-assembly.html?spm=2114.search0103.2.4.j5bjIW&site=glo";
$url = "https://www.aliexpress.com/category/39/lights-lighting.html?spm=2114.search0103.4.2.1EPE6m&site=glo";
$url = "https://www.aliexpress.com/category/3306/skin-care.html?spm=2114.search0103.4.7.LRlXBv&site=glo&g=y";

$savePath = BASE_PATH . "dev/scraping/searchClothing.html";
$savePath = BASE_PATH . "dev/scraping/hs-below-list-items.html";
$savePath = BASE_PATH . "dev/scraping/lightingHomeImprovement.html";
$savePath = BASE_PATH . "dev/scraping/carLightAutoMobile.html";
$savePath = BASE_PATH . "dev/scraping/skinCareHealth.html";//No Footprint can be found.
$savePath = BASE_PATH . "dev/scraping/bikinisSport.html";
$savePath = BASE_PATH . "dev/scraping/babygirlClothingToy.html";
$savePath = BASE_PATH . "dev/scraping/shoes.html";
$savePath = BASE_PATH . "dev/scraping/kitchenToolHomeGarden.html";
$savePath = BASE_PATH . "dev/scraping/watchJewelry.html";
$savePath = BASE_PATH . "dev/scraping/cameraElectronic.html";
$savePath = BASE_PATH . "dev/scraping/tabletComputer.html";
$savePath = BASE_PATH . "dev/scraping/mobilePhone.html";
$savePath = BASE_PATH . "dev/scraping/topTeesClothing.html";
$savePath = BASE_PATH . "dev/scraping/sweaterClothing.html";
$savePath = BASE_PATH . "dev/scraping/temporaryHtmlFile.html";


//getContentFromWebsite($url, $savePath);


//die();
$contents = openHtmlFile($savePath);

preg_match_all('/<ul\s+class="util-clearfix\s+son-list\s+util-clearfix"\s+id="hs-below-list-items">(.+)<\/ul>/', $contents, $matches);

$p = new ExtractProductData();
$p->getProductData($contents);


class ExtractData {
	protected $productFootprint;

	function __construct() {
		$this->productFootprint();
	}

	function productFootprint() {
		$this->productFootprint = array(
			'Ul_Id_hs_below_list_items1' => '/<ul\sclass="util-clearfix\sson-list\sutil-clearfix"\sid="hs-below-list-items">(.+?)<\/ul>/',
			'Ul_Id_hs_below_list_items2' => '/<ul\s+class="util-clearfix\s+son-list\s+util-clearfix"\s+id="hs-below-list-items">(.+?)<\/ul>/',
			'Ul_Id_hs_below_list_items3' => '/<ul\sclass="util-clearfix\sson-list">(.+?)<\/ul>/',
			'Li_Class_list_items' => '/<li\s+class="list-item(.+?)<\/li>/',
		);		
	}

	function findFootprint($footprint, $contents) {
		foreach ($footprint as $key => $regx) {
			preg_match_all($regx, $contents, $matches);

			echo "count " . $regx . " : " . count($matches[0]);
			echo "\n";
			if (count($matches[0]) != 0 ) {
				$results = array(
					'regxName' => $key,
					'matches'  => $matches
				);
				return $results;
			} 
		}//foreach

		die("No footprint can be found.");
	
	}//function
}//Class ExtractData


class ExtractProductData extends ExtractData {
	function getProductData($contents) {
		$results  = $this->findFootprint($this->productFootprint, $contents);
		$regxName = $results['regxName'];
		$matches  = $results['matches'];
		$this->$regxName($matches);
	}
	function Li_Class_list_items($matches) {
		$classname = "Li_Class_list_items_FootPrint";
		$this->dataCollection($classname, $matches[0]);
	}
	function Ul_Id_hs_below_list_items1($matches) {
		$classname = "Ul_Id_hs_below_list_items_Footprint";
		$this->dataCollection($classname, $matches[1][0]);
	}
	function Ul_Id_hs_below_list_items2($matches) {
		$classname = "Ul_Id_hs_below_list_items_Footprint";
		$this->dataCollection($classname, $matches[1][0]);
	}
	function Ul_Id_hs_below_list_items3($matches) {
		$classname = "Ul_Id_hs_below_list_items_Footprint";
		$this->dataCollection($classname, $matches[1][0]);
	}
	function dataCollection($classname, $matches) {
		$e = new $classname();
		$html = $e->extractListItems($matches);
		foreach ($html as $item ) {
			$title = $e->getTitle($item);
			$link  = $e->getLink($item);
			$img   = $e->getImageUrl($item);
			$alt   = $e->getAtagTitle($item);
			echo $title;
			echo "\n";
			echo $link;
			echo "\n";
			echo $img;
			echo "\n";
			echo $alt;
			echo "\n\n";
		}
		//$html->clear();
		unset($html);
	}
}/// Class ExtractProductData

class Ul_Id_hs_below_list_items_Footprint {
	function extractListItems($matches) {
		$html = str_get_html($matches);
		return $html->find('li');
	}
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

class Li_Class_list_items_FootPrint {
	function extractListItems($matches) {
		foreach( $matches as $list ) {
			$items = $items . $list;
		}
		$html = str_get_html($items);
		return $html->find('li');
	}
	function getTitle($item) {
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


