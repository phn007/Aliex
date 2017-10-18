<?php
function setPLogs($vars, $type) {
	$date = date("d-m-y H:i:s ");
	$fileNumber = "start/end: " . $vars['start'] . '/' . $vars['end'];
	$siteUrl    = $vars['siteUrl'];
	
	$log = $date . ' ' . $fileNumber . ' | ' . $siteUrl;
	$log = $log . PHP_EOL . '===================== ' . PHP_EOL;

	echo PHP_EOL . $log;

    $path = $vars['pLogs'] . $vars['productFolderName'] . '/' . $type .'_PLogs.txt';
	file_put_contents($path, $log, FILE_APPEND);
}


function setLogs($msg,$path) {
	$date = date("d-m-y H:i:s ");
	if (is_array($msg)) {
		foreach( $msg as $key => $value ) {
			$log = $log . $key . " => " . $value . PHP_EOL;
		}
		$log = $date . PHP_EOL . $log;
	} else {
		$log = $date . $msg . PHP_EOL;
	}
	$log = $log . "======================================" . PHP_EOL;

	file_put_contents($path, $log, FILE_APPEND);
}
