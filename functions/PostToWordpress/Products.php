<?php
class Products {
 	var $vars;
	function getProducts($pageNumber, $vars) {
		$this->vars = $vars;
		$filename = $this->getFileName($pageNumber);
    	$filePath = $this->vars['productDir'] . $filename;
    	return $this->readContentFromfile($filePath);
	} 
	function getFileName($number) {
		$folderName = $this->vars['productFolderName'];
		$pageNumber = str_pad($number,8,'0',STR_PAD_LEFT);
		return $filename = $folderName .'_page_'. $pageNumber . '.txt';
	}
	function readContentFromfile($file) {
		if (!$fn = fopen($file,"r")) {
			$msg = "Could not open ้textDatabase file from";
			setLogs($msg . ":" . $file, $this->vars['logs']);
			die($msg);
		}
		setLogs("Open textDatabase File: " . $file, $this->vars['logs']);
  		while(! feof($fn))  {
			$line = fgets($fn);
			$line = $this->extractColumnFromLine($line);
			if(!empty($line['title'])) $result[] = $line;
  		}
  		fclose($fn);
		return $result;
	}
	function extractColumnFromLine($line) {
		$result = array(
			'pageNumber' => $this->getColumn('pageNumber',$line),
			'title'      => $this->getColumn('title', $line),
			'link'       => $this->getColumn('link', $line),
			'img'        => $this->getColumn('img', $line),
			'altTitle'   => $this->getColumn('altTitle', $line)
		);
		return $result;
	}
	function getColumn($idName, $line) {
		$pattern = '/<span\sid="'.$idName.'">([^<]+)<\/span>/';
		preg_match($pattern, $line, $matches);
		return $matches[1];
	}
	function readContentFromfileDevForLayout($file) {
		if (!$fn = fopen($file,"r")) {
			$msg = "Could not open ้textDatabase file from";
			die($msg);
		}
  		while(! feof($fn))  {
			$line = fgets($fn);
			$line = $this->extractColumnFromLine($line);
			if(!empty($line['title'])) $result[] = $line;
  		}
  		fclose($fn);
		return $result;
	}		
}