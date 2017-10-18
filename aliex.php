<?php
define( 'BASE_PATH', __DIR__ . '/' );
require_once BASE_PATH . 'libs/Console/GetoptPlus.php';
require_once BASE_PATH . 'libs/Config/Lite.php';
require_once BASE_PATH . 'libs/simplehtmldom_1_5/simple_html_dom.php';
require_once BASE_PATH . 'libs/TablePrinter.php';
require_once BASE_PATH . 'libs/configLiteFunctions.php';
require_once BASE_PATH . 'libs/ErrorException.php';
require_once BASE_PATH . 'libs/filesDirectoryFunctions.php';
require_once BASE_PATH . 'functions/logs.php';

date_default_timezone_set("Asia/Bangkok");

try {
    $config = array(// /
        'header' => array('',''),
        'usage' => array('function', '--function <arg> -f [arg]'),
        'options' => array(// /
            array(
                'long' => 'function', 
                'type' => 'mandatory', 
                'short' => 'f', 
                'desc' => array('', '')
            )
        ),
        'parameters' => array('[param1] [param2]', 'Some additional parameters.'),
        'footer' => array('', ''),
        );

    $options = Console_Getoptplus::getoptplus($config);
    
}
catch(Console_GetoptPlus_Exception $e) {
    $error = array($e->getCode(), $e->getMessage());
    print_r($error);
}


$opt =parseOptions($options );
$optName = $opt['optionName'];
$optValue =$opt['optionValue'];
$param = $opt['param']; //array

//print_r( $opt );


switch ($optValue) {
    case 'get':
        include BASE_PATH . 'get.php';
        break;
    case 'post':
    	include BASE_PATH . 'post.php';
        break;
    case 'devLayout':
        include BASE_PATH . 'devLayout.php';
        break;
    default:
        include BASE_PATH . 'help.php';        

}//switch



function parseOptions( $options ) {
    $optName  = $options[0][0][0];
    $optValue = $options[0][0][1];

    if (preg_match( '/--(.+)/', $optName, $matches )) 
        $optName = $matches[1];

    return array(
        'optionName' => $optName, 
        'optionValue' => $optValue,
        'param' => $options[1]
    );
}//parseOptions