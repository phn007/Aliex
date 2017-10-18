<?php
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

