<?php
class SetIniVariables {
	use CheckIniConfigVars, CheckFileAndDirectoryExists;

	protected $iniFilename;
	protected $iniObject;
	protected $productDir;
	protected $iniConfigDir;
	protected $errorLogDir;
	protected $logs;


	function __construct() {
		$this->productDir    = BASE_PATH . 'products/';
		$this->iniConfigDir  = BASE_PATH . 'configs/';
		$this->errorLogDir   = BASE_PATH . 'logs/';
	}
	function getProductDir() {
		$folderName = $this->getIniConfigArrayKeyValue('dir', 'product');
		return $this->productDir . $folderName . '/';
	}
	function getProductFolderName() {
		return $folderName = $this->getIniConfigArrayKeyValue('dir', 'product');
	}
	function getIniConfigDir() {
		return $this->iniConfigDir . $this->iniFilename;
	}
	function getStartPageNumber() {
		return $this->iniObject['pageNumber']['start'];
	}
	function getLastPageNumber() {
		return $this->getIniConfigArrayKeyValue('pageNumber', 'end');
	}
	function getSiteUrl() {
		return $this->getIniConfigArrayKeyValue('site', 'url');
	}
	function getLogsDir() {
		$productDirName = $this->getIniConfigArrayKeyValue('dir', 'product');
		$dir = $this->errorLogDir . $productDirName . '/';
		createDirectory($dir);//from functions.php file
		//$filename = $this->getLogsFilename($dir);
		$filename = 'getLogs.txt';
		return $dir . $filename;
	}
	function getPLogsDir() {
		return $this->errorLogDir;
	}
	function delayTime() {
		if ($delay = $this->IsEmptyValue('delayTime')) {
			return $delay;
		} else {
			return '10/60';
		}
	}
}//Class

class GetIniVariables extends SetIniVariables {
 	function getVars($iniConf) {
 		$this->iniObject = $iniConf['obj'];
		$this->iniFilename = $iniConf['filename'];
		$vars = array(
			'logs'  			=> $this->getLogsDir(),
			'pLogs'         	=> $this->getPLogsDir(),
			'productDir'    	=> $this->getProductDir(),
			'productFolderName' => $this->getProductFolderName(),
			'iniConfigPath' 	=> $this->getIniConfigDir(),
			'siteUrl'       	=> $this->getSiteUrl(),
			'start'         	=> $this->getStartPageNumber(),
			'end'           	=> $this->getLastPageNumber(),
			'delayTime' 		=> $this->delayTime()
		);
		return $vars;
 	}
}

trait CheckFileAndDirectoryExists {
		//Check File and Directory Section
	function IsDirExists($dir) {
		if (!is_dir($dir)) {
			$msg = "There is no Directory in this path: ";
			echo $msg = $msg . $dir;   
		}else {
			return $dir;
		} 
	}
	function IsFileInDirExists($dir, $extension) {
		$list = glob($dir . '*.' . $extension);
		if(count($list) == 0 ) {
		} else {
			return $dir;
		}
	}

	function getLogsFilename($dir){
		$files = glob($dir . '*.txt');
		$num = count($files);
		if (empty($num)) $num = 0;

		$number = $num + 1;
		$filename = str_pad($number,8,'0',STR_PAD_LEFT) . '.txt';
		return $filename;
	}
}

trait CheckIniConfigVars {
	//Check ini config file section
	function IsEmptyValue($varName) {
		try {
			if ($value = $this->iniObject->get(null, $varName)) {
				return $value;
			} else {
				throw new IniConfigNoneValueException();
			}
		} catch (IniConfigNoneValueException $e) {
			$msg = "Empty Value" . PHP_EOL;
			$msg = $msg . "IniConfig Filename: " . $this->iniFilename . PHP_EOL;
			$msg = $msg . "Variable name: " . $varName;
			echo $msg . PHP_EOL;
			echo $e->getErrorMessage();
		} catch (Config_Lite_Exception $e) {
			echo PHP_EOL . $e->getMessage() . PHP_EOL;
			echo "IniConfig Filename: " . $this->iniFilename . PHP_EOL;
			echo "Variable name: " . $varName . PHP_EOL;
		}		
	}
	function getIniConfigArrayKeyValue($arrName, $arrKey) {
		try {
			if ($value = $this->iniObject->get($arrName, $arrKey)) {
				return $value;	
			} else {
				throw new IniConfigNoneValueException();
			} 
		} 
		catch(IniConfigNoneValueException $e) {
			$msg = "Empty Value" . PHP_EOL;
			$msg = $msg . "IniConfig Filename: " . $this->iniFilename . PHP_EOL;
			echo $msg = $msg . "Array Variable name: " . $arrName . ' => ' . $arrKey . PHP_EOL;
			echo $e->getErrorMessage();
		}
		catch (Config_Lite_Exception $e) {
			echo PHP_EOL . $e->getMessage() . PHP_EOL;
			echo "IniConfig Filename: " . $this->iniFilename . PHP_EOL;
			echo "Array Variable name: " . $arrName . ' => '. $arrKsey . PHP_EOL;
		}	
	}
}


