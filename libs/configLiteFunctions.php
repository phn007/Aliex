<?php
function getConfig($path) {
	try {
		if (file_exists($path)) {
			$config = new Config_Lite($path);
			return $config;
		} else {
			throw new FileExistsException('File not found: ' . $path);
		}
	} catch(FileExistsException $e) {
		echo $e->getErrorMessage();
	}
}

function debugConfig($obj) {
	if (true === $obj->getBool(null, 'debug', true)) {
		echo "\n\n=================================\n";
		echo $obj;
	}
}

function getConfigValue($conf, $name) {
	$config = $conf['obj'];
	$filename = $conf['filename'];

	try {
		try{
			if ($result = $config->get(null, $name)) {
				return $result;
			} else {
				$msg = "None Value" . PHP_EOL;
				$msg = $msg . "Config Filename: " . $filename;
				$msg = $msg . " Variable name: " . $name;
				throw new ConfigNoneValueException($msg);
			}
		} catch (ConfigNoneValueException $e) {
			echo $e->getErrorMessage();
		}
	} catch (Config_Lite_Exception $e) {
		$msg = "Config_Lite_Exception " . PHP_EOL;
		$msg = "None Declare Variable" . PHP_EOL;
		$msg = $msg . "Config Filename: " . $filename;
		$msg = $msg . " Variable name: " . $name .PHP_EOL;
		$msg = $msg . $e->getMessage();
		echo $msg;
	}
}

function getConfigArray($conf, $arrayName ) {
	$config = $conf['obj'];
	$filename = $conf['filename'];
	try {
		if (isset($config[$arrayName])) {
			foreach (array_keys($config[$arrayName]) as $key ) {
				$c[$key] = getConfigArrayValue($conf, $arrayName, $key);
			}

			return $c;
		} else {
			throw new Exception( $filename . ': Variable: ' . $arrayName . ' is NULL');
		}
	} catch (Exception $e) {

		$msg = $e->getMessage() . PHP_EOL;
		$msg = $msg . 'Source: ' . $e->getFile() . ' Line: ' . $e->getLine(). PHP_EOL;
		echo $msg;
	}
}

function getConfigArrayValue($conf, $arrayName, $key) {
	$config = $conf['obj'];
	$filename = $conf['filename'];

	try{
		if ($result = $config->get($arrayName, $key)){
			return $result;
		} else {
			$msg = "None Value" . PHP_EOL;
			$msg = $msg . "Config Filename: " . $filename;
			$msg = $msg . " Array name: " . $arrayName;
			$msg = $msg . " Array key: " . $key;
			throw new ConfigNoneValueException($msg);
		}
	} catch (ConfigNoneValueException $e) {
		echo $e->getErrorMessage();
	}

}

