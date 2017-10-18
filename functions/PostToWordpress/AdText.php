<?php
class AdText {

	function getAdText($keyword, $vars) {
		$fileList = $this->getAdTextFileList($vars['adTextDir']);
		$libs = new WordLibrary();
		$wordLibs = $libs->getWordLibrary($vars['adTextDir']);

		foreach ( $fileList as $key => $path ) {
			$lineText = $this->getRandomLineOfTextFile($path);
			$lineText = $this->getRandomLineOfTextFile($path);
			$adText   = $this->replaceWordLibsToAdText($wordLibs, $lineText);
			$adText   = $this->replaceKeywordToAdText($keyword, $adText);
			$adText   = $this->runTextSpinner($adText);
			$results[$key] = $adText;
		}
		return $results;

	}
	function getTestAdText() {//For Testing adTest
		$fileList = $this->getAdTextFileList($vars['adTextDir']);
		$libs = new WordLibrary();
		$wordLibs = $libs->getWordLibrary($vars['adTextDir']);
	}
	function runTextSpinner( $content ) {
		$returnArray = array();
		$pattern = "/\{[^\{]+?\}/ixsm";
		preg_match_all($pattern, $content, $returnArray, PREG_SET_ORDER);

		foreach ( $returnArray as $return ) {
			$code = $return[0];
			$str = str_replace( "{", "", $code );
			$str = str_replace( "}", "", $str );
			$tmp = explode( "|", $str );
			$c = count( $tmp );
			$rand = rand( 0, ($c-1) );
			$word = $tmp[$rand];
			$content = str_replace( $code, $word, $content );
		}

		$pos = strpos( $content, "{" );
		if ( $pos === false )
			return $content;
		else
			return $this->runTextSpinner($content);
	}
	function replaceKeywordToAdText($keyword, $adText) {
		return str_replace("#keyword#", $keyword, $adText);
	}
	function replaceWordLibsToAdText( $wordLibs, $lineText ) {
		$keys = array_keys( $wordLibs );
		$values = array_values( $wordLibs );
		return str_replace( $keys, $values, $lineText );
	}
	function getRandomLineOfTextFile( $file ) {
		$text = file( $file );
		shuffle($text);
		return $text[0];
	}
	function getAdTextFileList($dir) {
		$files = new FilesystemIterator($dir);
		$files->setFlags(FilesystemIterator::UNIX_PATHS);
		foreach($files as $file) {
			if ($file->getExtension() == 'txt') {
				$arr = preg_split('(/)',$file->getFilename());
				$name = preg_replace('/(.txt)/', '',$arr[0]);
				$list[$name] = $file->getPathname();
			}
		}
		return $list;
	}
	function getDirPath($folder){
		$path = WP_PATH . 'AdText/' . $folder ;
		try {
			if(is_dir($path)) {
				return $path;
			} else {
				throw new FileExistsException('Folder not found: ' . $path);
			}
		} catch(FileExistsException $e) {
			echo $e->getErrorMessage();
		}
	}
}