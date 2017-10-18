<?php
class WordLibrary {

	function getWordLibrary($dir) {
		$path = $this->getFilePath($dir);

		$arr = $this->wordLibsArray($path);
		$wordLibs = $this->createKeyFormatTypeAndSpinWord($arr);
		return $wordLibs;
	}

	function readCSVFile($path) {
		$csvFile = new SplFileObject($path);
		$csvFile->setFlags(SplFileObject::READ_CSV);
		foreach ($csvFile as $line) $arrCsv[] = $line;
		return $arrCsv;
	}

	function createKeyFormatTypeAndSpinWord($array) {
		foreach ( $array as $key => $value ) {
			$key = strtolower( $key );
			shuffle( $value );
			$string = trim( $value[0] );
			$key = '[' . $key . ']';
			$data[ $key ] = $string;
		}
		return $data;
	}

	function wordLibsArray($path) {
		$csv = $this->readCSVFile($path);
		$rows = array();
		foreach ($csv as $row => $cols) {
			if ( $row == 0 ){
				foreach ($cols as $col) $key[] = trim($col);
			} else {
				foreach ($cols as $num => $val) {
					if (!empty($val)) {
						$title = $key[$num];
						$rows[$title][]=$val;
					}
				}
			}
		}
		return $rows;
	}//wordLibsArray

	function getFilePath($dir) {
		$path = $dir . '/' . 'WordLibrary.csv';
		try {
			if(file_exists($path)) {
				return $path;
			} else {
				throw new FileExistsException('File not found: ' . $path);
			}
		} catch(FileExistsException $e) {
			echo $e->getErrorMessage();
		}
	}//getFilePath

	//source
	/*$list = array (
	    array('aaa', 'bbb', 'ccc', 'dddd'),
	    array('123', '456', '789'),
	    array('"aaa"', '"bbb"')
	);*/

	function writeCsv($list) {
		$file = new SplFileObject('csvfile.csv', 'w');
		foreach ($list as $fields) {
		    $file->fputcsv($fields);
		}
	}

}//WordLibrary