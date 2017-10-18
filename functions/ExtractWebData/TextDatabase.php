<?php
class TextDatabase {
	var $vars;
	var $categories;
	var $currentPage;
	var $path;
	var $allProductPath;

	function save($listItems, $vars) {
		$this->vars = $vars;
		$this->setPageInfo($listItems);
		$this->setPath();
		$this->saveToDatabase($listItems['products']);
	}
	function saveToDatabase($products) {
		$num = 1;
		foreach ($products as $item) {
			$line     = "";
			$page     = $this->insertTagAsColumn($this->currentPage, 'pageNumber');
			$title    = $this->insertTagAsColumn($item['title'], 'title');
			$link     = $this->insertTagAsColumn($item['link'], 'link');
			$img      = $this->insertTagAsColumn($item['img'], 'img');
			$altTitle = $this->insertTagAsColumn($item['altTitle'], 'altTitle');
			$line = $line . $page . $title . $link . $img . $altTitle . PHP_EOL;
			$this->putContent($line);
			$num++;
		}
		$msg = 'Save product data to textDatabase successful... (' . $num . ' items)';
		setLogs($msg, $this->vars['logs']);
	}
	function putContent($line) {
		try {
			file_put_contents($this->path, $line, FILE_APPEND|LOCK_EX);
			file_put_contents($this->allProductPath, $line, FILE_APPEND|LOCK_EX);
		} catch(Exception $e) {
			$msg = "Emergency Stop: Save product data to text database failed...";
			setLogs($msg, $this->vars['logs']);
			die($msg);
		}
	}
	function insertTagAsColumn($text, $columnName) {
		return $text = '<span id="'.$columnName.'">'.$text.'</span>';
	}
	function setPath() {
		$dir = $this->vars['productDir'];
		/*$arr = explode('/', $dir);
		$dirname = $arr[count($arr)-2];*/
		$dirname = $this->vars['productFolderName'];
		$filename = str_pad($this->currentPage,8,'0',STR_PAD_LEFT) . '.txt';
		$filename = $dirname . '_page_' . $filename;
		$this->path = $dir . $filename;
		$this->allProductPath = $dir . $dirname . '.txt';
	}	
	function setPageInfo($listItems) {
		$this->categories  = $listItems['pageInfo']['categories'];
		$this->currentPage = $listItems['pageInfo']['currentPage'];
	}
}
