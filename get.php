<?php
include BASE_PATH . 'functions/ExtractWebData/SetIniVariables.php';
include BASE_PATH . 'functions/ExtractWebData/ProductListItems.php';
include BASE_PATH . 'functions/ExtractWebData/ExtractData.php';
include BASE_PATH . 'functions/ExtractWebData/ExtractPageInfo.php';
include BASE_PATH . 'functions/ExtractWebData/ExtractProductData.php';
include BASE_PATH . 'functions/ExtractWebData/TextDatabase.php';
include BASE_PATH . 'functions/ExtractWebData/UpdateIniConfig.php';
include BASE_PATH . 'functions/ExtractWebData/functions.php';

//Get Config
$configName = $param[0];
$configPath = BASE_PATH . 'configs/'.$configName;
$configPath = checkFileExists($configPath);

//Fist time Check condition Stop
$con = new Config_Lite($configPath);
$iniStart = $con['pageNumber']['start'];
$iniEnd   = $con['pageNumber']['end'];
$msg = 'Start number is greater than end number: check it out at';
if ( $iniStart > $iniEnd ) die( $msg . ' ' . $configName . "\n");

$configObject = getConfig($configPath);
$iniConf = array('obj' => $configObject, 'filename' => $configName);

$var = new GetIniVariables();
$vars = $var->getVars($iniConf);
$start = $vars['start'];

function recursiveLoop($start, $iniConf, $vars) {
	global $configPath, $configName;
	//Check Loop
	$end = $vars['end'];
	if ($end <= $start) return 1;

	//Start Loop
	setLogs(" | ***** START *****", $vars['logs']);
	$msg = "| Start/End: " . $vars['start'] . '/' . $vars['end'];
	setLogs($msg, $vars['logs']);
	setLogs($vars, $vars['logs']);

	$prod = new ProductListItems();
	$listItems = $prod-> getProductListItems($vars);

	$db = new TextDatabase();
	$db->save($listItems, $vars);

	$ucon = new UpdateIniConfig();
	$ucon->update($listItems['pageInfo'],$vars);

	setPLogs($vars,'get');
	delayTime($vars);

	$configObject = getConfig($configPath);
	$iniConf = array('obj' => $configObject, 'filename' => $configName);
	$var = new GetIniVariables();
	$vars = $var->getVars($iniConf);

	//end Loop
	$start = $vars['start'];
	recursiveLoop($start, $iniConf, $vars);
}
recursiveLoop($start, $iniConf, $vars);
echo "\n\n";
echo "Successfully";
echo "\n\n";