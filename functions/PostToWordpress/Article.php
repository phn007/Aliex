<?php
Class Article {
	function getArticleFile($vars) {
		$path = checkDirExists($vars['articleDir']); //function from functions.php
		$textFile = $this->getRandomArticle($path);
		$arrArticle = $this->spinArticle($textFile);
		$result = $this->editArrayResult($arrArticle);
		return $result;
	}

	function editArrayResult($arrArticle) {
		$title = $arrArticle[0];
		unset($arrArticle[0]);
		foreach ( $arrArticle as $line ) {
			if (!empty($line)){
				$text = $text . '<p>' .$line .'</p>';
			}
		}
		return array(
			'articleTitle' => $title,
			'articleContent' => $text
		);
	}

	function spinArticle($textFile) {
		foreach ( $textFile as $line ) {
			$line = $this->runTextSpinner($line);
			$line = preg_replace('/\r\n/','',$line);
			if($line != '') $text[] = $line; 
	 	}
		return $text;
	}

	function getRandomArticle($path) {
		$files = $this->getFileSystem($path);
		$number = count($files)-1;
		$rand = rand(0, $number);

		$textFile = $files[$rand]->openFile();
		//$textFile = $files[5]->openFile();

		return $textFile;
	}

	function getFileSystem($path) {
		$dir = new FileSystemIterator($path);
		$dir->setFlags(FileSystemIterator::UNIX_PATHS | FileSystemIterator::KEY_AS_PATHNAME);
		foreach ($dir as $key => $file) {
			$files[] = $file;
		}
		return $files;
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

}//Article
