<?php
class ProductListItems {
	var $vars;

	function getProductListItems($vars) {
		$this->vars = $vars;

		$contents = $this->loadHtmlContentFromWebsite($vars['siteUrl']);
		$this->createDirToStoreHtmlFile();
		//$filename = $this->createHtmlFilename();
		$filename = 'temporaryHtmlFile.html';
		$this->saveHtmlContentToLocalDir($contents, $filename);

		$filenamePath = $this->vars['productDir'] . $filename;
		$contents = $this->openHtmlFileFromLocalDir($filenamePath);

		$epi = new ExtractPageInfo();
		$pageInfo = $epi->getPageInfo($contents, $vars);

		$epd = new ExtractProductData();
		$products = $epd->getProductData($contents, $vars);

		return array(
			'pageInfo' => $pageInfo,
			'products' => $products
		);
	}

	function loadHtmlContentFromWebsite($url) {
		$ch = curl_init();
	    if (!ch) die("Couldn't initialize a cURL handle");

	    curl_setopt($ch, CURLOPT_URL,            $url);
	    curl_setopt($ch, CURLOPT_HEADER,         1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_AUTOREFERER   , 1);
	    curl_setopt($ch, CURLOPT_TIMEOUT,       120);
	    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

	    $result = curl_exec($ch);
	    $effUrl = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);
	    curl_close($ch); // close cURL handler

	    if ( $effUrl === $url ) {
	    	if ($this->IsNullOrEmptyString($result)) {
	    		$msg = "Emergency Stop: Empty web Contents";
	    		setLogs($msg, $this->vars['logs']);
	    		die($msg);
	    	}
	    	setLogs("Load html content successful...", $this->vars['logs']);
	       	return $result;
	    } else {
	    	$msg = "Request URL does not match Effective URL";
	    	setLogs($msg, $this->vars['logs']);
	      	die("Emergency Stop: " . $msg);
	    }    
	}
	function createDirToStoreHtmlFile() {
		$path = $this->vars['productDir'];
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
			setLogs("createDirToStoreHtmlFile Function: Successful " . $path, $this->vars['logs']);
		}
	}
	function createHtmlFilename() {
		$dir = $this->vars['productDir'];
		$files = glob($dir . '*.html');
		$num = count($files);
		if (empty($num)) $num = 0;

		$number = $num + 1;
		$filename = str_pad($number,8,'0',STR_PAD_LEFT) . '.html';
		return $filename;
	}
	function saveHtmlContentToLocalDir($contents, $filename) {
		$path = $this->vars['productDir'] . $filename;
		
		try {
			file_put_contents($path, $contents, LOCK_EX);
		} catch(Exception $e) {
			$msg = "Emergency Stop: Save Html content to local dir failed...";
			die($msg);
		}
		setLogs("saveHtmlContentToLocalDir Function: Successful " . $path, $this->vars['logs']);
	}
	function openHtmlFileFromLocalDir($file) {
		if (!$fn = fopen($file,"r")) {
			$msg = "Could not open ้html file from";
			setLogs($msg . ":" . $file, $this->vars['logs']);
			die($msg);
		}
  		while(! feof($fn))  {
			$line = trim(fgets($fn));
			$result = $result . $line;
  		}
  		fclose($fn);
		setLogs("Open html file from local dir successful: " . $file, $this->vars['logs']);
		return $result;
	}
	function IsNullOrEmptyString($question){
    	return (!isset($question) || trim($question)==='');
	}//IsNullOrEmptyString
}















