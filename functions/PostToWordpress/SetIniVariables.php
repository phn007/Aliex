<?php
class SetIniVariables {
	use CheckIniConfigVars, CheckFileAndDirectoryExists;

	protected $iniFilename;
	protected $iniObject;
	protected $productDir;
	protected $articleDir;
	protected $adTextDir;
	protected $layoutDir;
	protected $iniConfigDir;
	protected $errorLogDir;
	protected $cancel = "OFF";

	function __construct() {
		$this->productDir    = BASE_PATH . 'products/';
		$this->articleDir    = BASE_PATH . 'articles/';
		$this->adTextDir     = BASE_PATH . 'adText/';
		$this->layoutDir     = BASE_PATH . 'layouts/';
		$this->iniConfigDir  = BASE_PATH . 'configs/';
		$this->errorLogDir   = BASE_PATH . 'logs/';
	}
	function getDomainName() {
		return $this->getIniConfigValue('domain');
	}
	function getAffiliateId() {
		return $this->getIniConfigValue('aff_id');
	}
	function getProductDirForLocal() {
		$folderName = $this->getProductFolderName();
		$productDir = $this->productDir . $folderName . '/';
		$productDir = $this->IsDirExists($productDir);

		if ($productDir)  {
			return $this->IsFileInDirExists($productDir, 'html');
		} 
	}
	function getProductFolderName() {
		return $folderName = $this->getIniConfigArrayKeyValue('dir', 'product');
	}
	function getArticleDir() {
		$folderName = $this->getIniConfigArrayKeyValue('dir', 'article');
		$articleDir = $this->articleDir.$folderName . '/';
		$articleDir = $this->IsDirExists($articleDir);

		if ($articleDir)  {
			return $this->IsFileInDirExists($articleDir, 'txt');
		} 
	}
	function getAdTextDir() {
		$adTextDir = $this->IsDirExists($this->adTextDir);

		if ($adTextDir)  {
			return $this->IsFileInDirExists($adTextDir, 'txt');
		} 
	}
	function getLayoutDir() {
		return $this->layoutDir;
	}
	function getIniConfigDir() {
		return $this->iniConfigDir . $this->iniFilename;
	}
	function getPostNumberType() {
		return $this->getIniConfigArrayKeyValue('postNumber', 'type');
	}
	function getStartNumber() {
		return $this->getIniConfigArrayKeyValue('postNumber', 'start');
	}
	function getEndNumber() {
		return $this->getIniConfigArrayKeyValue('postNumber', 'end');
	}
	function getLogsDir() {
		$productDirName = $this->getIniConfigArrayKeyValue('dir', 'product');
		$dir = $this->errorLogDir . $productDirName;
		createDirectory($dir);//from functions.php file
		$filename = $productDirName . '_postLogs.txt';
		return $dir . '/' . $filename;
	}
	function getCategory() {
		return $this->getIniConfigValue('category');
	}
}//Class
class GetIniVariables extends SetIniVariables {

	function getVars($iniConf) {
		$this->iniObject = $iniConf['obj'];
		$this->iniFilename = $iniConf['filename'];
		$vars = array(
			'domain'            => $this->getDomainName(),
			'affiliateId'       => $this->getAffiliateId(),
			'productDir'        => $this->getProductDirForLocal(),
			'productFolderName' => $this->getProductFolderName(),
			'articleDir'        => $this->getArticleDir(),
			'adTextDir'         => $this->getAdTextDir(),
			'layoutDir'         => $this->getLayoutDir(),
			'iniConfigPath'     => $this->getIniConfigDir(),
			'logs'              => $this->getLogsDir(),
			'postNumberType'    => $this->getPostNumberType(),
			'start'             => $this->getStartNumber(),
			'end'               => $this->getEndNumber(),
			'category'			=> $this->getCategory();
			'cancel'            => $this->cancel
		);
		return $vars;
	}

}//class
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
}

trait CheckIniConfigVars {
	//Check ini config file section
	function getIniConfigValue($varName) {
		return $this->IsEmptyValue($varName);	
	}
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
		} catch(IniConfigNoneValueException $e) {
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


