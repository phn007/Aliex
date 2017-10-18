<?php
include BASE_PATH . 'functions/PostToWordpress/Products.php';
include BASE_PATH . 'functions/PostToWordpress/GenerateContent.php';
include BASE_PATH . 'functions/PostToWordpress/AdText.php';
include BASE_PATH . 'functions/PostToWordpress/WordLibrary.php';
include BASE_PATH . 'functions/PostToWordpress/Article.php';
include BASE_PATH . 'functions/PostToWordpress/SendPost.php';

$vars = array(
	'domain'            => 'http://localhost/~phan/aliex/p2wp',
	'productDir'        => BASE_PATH . 'dev/layout/product/',
	'articleDir'        => BASE_PATH . 'dev/layout/article/',
	'adTextDir'         => BASE_PATH . 'adText/',
	'layoutDir'         => BASE_PATH . 'dev/layout/',
	'category'          => "Women/Clothing/Dresses",
);

$file = $productFile = getProductFile($vars['productDir']);
$p = new Products();
$productItems = $p->readContentFromfileDevForLayout($file);
$prod[0]=$productItems[1];
$g = new GenerateContent();
$data = $g->getPostContent($prod, $vars);

//PostToWordPress
$data = http_build_query($data);
$s = new SendPost();
$scriptName = 'p2wp.php';
$result = $s->send($data, $vars, $scriptName);
//$result = $s->loadHtmlContentFromWebsite($vars['domain'] . '/' . $scriptName);
echo $result;

//FUNCTION SECTION =========
function getProductFile($dir){
	$path = $dir . '*.txt';
	$files = glob($path);
	return $files[0];
}