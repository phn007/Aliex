<?php

define( 'BASE_PATH', __DIR__ . '/' );
require_once BASE_PATH . '/libs/TablePrinter.php';


echo "\n";
echo "+++ HELP +++";
echo "\n";

$functions = array(
	array(
		'function' => 'Extract Web Data',
		'command' => 'php aliex -f get filename.ini'
	),
	array(
		'function' => 'Post to wordpres.',
		'command' => 'php aliex -f post filename.ini'
	),

);

$p = new TablePrinter( [ 'Function', 'Command' ] );
foreach ( $functions as $line ) {
	$p->addRow($line['function'], $line['command']);
}
$p->output();
