<?php

//Check Folder, File Exists
function checkFileExists($path) {
	try {
		if (!file_exists($path)) {
			throw new FileExistsException('File not found: ' . $path);
		} else {
			return $path;
		}
	} catch(FileExistsException $e) {
		echo $e->getErrorMessage($path);
		die();
	}
}

function checkDirExists($path) {
	try {
		if(is_dir($path)) {
			return $path;
		} else {
			throw new FileExistsException('Directory not found: ' . $path);
		}
	} catch(FileExistsException $e) {
		echo $e->getErrorMessage();
		die();
	}
}

function createDirectory($path) {
	if (!file_exists($path)) {
		mkdir($path, 0777, true);
	}
}


