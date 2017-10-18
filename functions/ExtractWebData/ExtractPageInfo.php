<?php
class ExtractPageInfo extends ExtractData {
	use ExtractCategory;
	var $vars;

	function getPageInfo($contents, $vars) {
		$this->vars = $vars;
		$html = str_get_html($contents);

		return array(
			'categories'  => $this->getCategories($html),
			'currentPage' => $this->getCurrentPageNumber($html),
			'nextPageUrl' => $this->getNextPageUrl($html)
		);

		$html->clear();
		unset($html);
	}
	function getCategories($html) {
		if(empty($html->find('div#aliGlobalCrumb',0))) {
			setLogs("Can not find categories link to extract data", $vars['logs']);
			die("Emergency stop: Can not find categories link");
		}

		$div = $html->find('div#aliGlobalCrumb',0);
		foreach($div->find('a') as $a){
			$motherCat[] = htmlspecialchars_decode(trim($a->plaintext), ENT_QUOTES);
		}

		if(!empty($div->find('h1 span'))) {
			$currentCat = $this->currentDivH1Span($div);
		} else if (!empty($div->find('strong.active', 0))){
			$currentCat = $this->currentDivStrongActive($div);
		}
		array_push($motherCat, $currentCat);
		return $motherCat;
	}
	function getCurrentPageNumber($html) {
		$div = $html->find('div.ui-pagination-navi',0);
		$span = $div->find('span.ui-pagination-active',0);
		return trim($span->plaintext);
	}

	function getNextPageUrl($html) {
		$div = $html->find('div.ui-pagination-navi',0);
		/*if ($nextPage = $div->find('a.page-next',0)) {
			preg_match('/href="\/\/(.+?)"/',$nextPage,$matches);
			$nextPage = $matches[1];
			return 'https://' . htmlspecialchars_decode($nextPage, ENT_COMPAT);
		}
		else 
			$nextPage = 'lastPage';
		return $nextPage;
*/


		if(!empty($div->find('a.page-next',0))){
			$nextPage = $div->find('a.page-next',0);
			preg_match('/href="\/\/(.+?)"/',$nextPage,$matches);
			$nextPage = $matches[1];
			return 'https://' . htmlspecialchars_decode($nextPage, ENT_COMPAT);
		} else {
			return $nextPage = 'lastPage';
		}
	}
}

trait ExtractCategory {
	function currentDivH1Span($div) {
		setLogs("Use DivH1Span to extract current category", $this->vars['logs']);
		$span = $div->find('h1 span');
		$currentCat = end($span)->plaintext;
		return $currentCat = htmlspecialchars_decode(trim($currentCat), ENT_QUOTES);
	}

	function currentDivStrongActive($div) {
		setLogs("Use DivStringActive to extract current category", $this->vars['logs']);
		$currentCat = $div->find('strong.active', 0)->plaintext;
		preg_match('/&quot(.+?)&quot/', $currentCat, $matches);
		return $matches[1];
	}
}

