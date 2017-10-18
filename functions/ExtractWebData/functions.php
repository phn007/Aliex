<?php

function delayTime($vars) {
	$dTime = $vars['delayTime'];
	$arr = explode('/', $dTime);
	$t1 = $arr[0];
	$t2 = $arr[1];
	$rand = rand($t1, $t2);
	setLogs("DelayTime: ". $rand, $vars['logs']);
	echo "\n";
	echo "Sleep: " . date('h:i:s');
	echo " Delay " . $rand . " second";
	sleep($rand);
	echo "\n=====================\n";
	echo "Wake up: " . date('h:i:s');  "\n";
}