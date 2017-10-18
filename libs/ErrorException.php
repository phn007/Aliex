<?php
/*
getMessage() − message of exception

getCode() − code of exception

getFile() − source filename

getLine() − source line

getTrace() − n array of the backtrace()

getTraceAsString() − formated string of trace

*/
Class FileExistsException extends Exception {
	function getErrorMessage() {
		$msg = "FileExistsException ERROR" . PHP_EOL;
		$msg = $msg . $this->getMessage() . PHP_EOL;
		$msg = $msg . 'Source: ' . $this->getFile() . ' Line: ' . $this->getLine(). PHP_EOL;
		return $msg;
	}
}

Class IniConfigNoneValueException extends Exception {
	function getErrorMessage() {
		$msg = PHP_EOL . "IniConfigNoneValueException: ";
		$msg = $msg . $this->getMessage() . PHP_EOL;
		$msg = $msg . 'Source: ' . $this->getFile() . ' Line: ' . $this->getLine(). PHP_EOL ;
		//$msg = $msg . PHP_EOL. $this->getTraceAsString(). PHP_EOL;
		return $msg;
	}
}