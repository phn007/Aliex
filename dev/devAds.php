<?php
define( 'BASE_PATH', __DIR__ . '/' );
include BASE_PATH . 'functions/PostToWordpress/AdText.php';
include BASE_PATH . 'functions/PostToWordpress/WordLibrary.php';

$adTextDir = BASE_PATH . 'AdText/';
$vars = array('adTextDir' => $adTextDir);
$keyword = "MyKeyword";
$ad = new AdText();
$adText = $ad->getAdText($keyword, $vars);

//print_r($adText);

echo $adText['ad1'];
