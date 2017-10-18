<?php
include BASE_PATH . 'functions/PostToWordpress/SetIniVariables.php';
include BASE_PATH . 'functions/PostToWordpress/Products.php';
include BASE_PATH . 'functions/PostToWordpress/GenerateContent.php';
include BASE_PATH . 'functions/PostToWordpress/AdText.php';
include BASE_PATH . 'functions/PostToWordpress/WordLibrary.php';
include BASE_PATH . 'functions/PostToWordpress/Article.php';
include BASE_PATH . 'functions/PostToWordpress/SendPost.php';
//Get Config
$configName = $param[0];
$configPath = BASE_PATH . 'configs/'.$configName;
$configPath = checkFileExists($configPath);

//Fist time Check condition Stop
$con = new Config_Lite($configPath);
$iniStart = $con['postNumber']['start'];
$iniEnd   = $con['postNumber']['end'];
$msg = 'Start number is greater than end number: check it out at';
if ( $iniStart > $iniEnd ) die( $msg . ' ' . $configName . "\n");

function recursiveLoop($start) {
	global $configPath, $configName;

	$configObject = getConfig($configPath);
	$iniConf = array('obj' => $configObject, 'filename' => $configName);

	$var = new GetIniVariables();
	$vars = $var->getVars($iniConf);
	//Check Loop
	$end = $vars['end'];
	if ($end <= $start) return 1;

	//Start Loop
	setLogs(" | ***** START *****", $vars['logs']);
	$msg = "| Start/End: " . $vars['start'] . '/' . $vars['end'];
	setLogs($msg, $vars['logs']);
	setLogs($vars, $vars['logs']);

	$pageStart = $vars['start'];
	$pageEnd   = $vars['end'];
	
	for ($x = $pageStart; $x <= $pageEnd; $x++) {
		$p = new Products();
		$productList = $p->getProducts($x, $vars);

		$g = new GenerateContent();
		$postData = $g->getPostContent($productList, $vars);

		//PostToWordPress
		$s = new SendPost();
		$result = $s->send($postData, $vars, 'post2wp.php');
		echo $result;
		die();
	} 
	
	//end Loop
	die();
	$start = $vars['start'];
	recursiveLoop($start);
}
recursiveLoop($start);