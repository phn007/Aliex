<?php
class SendPost{

	function send($postData, $vars, $scriptName) {
		$url = $vars['domain'] .'/' . $scriptName;
        $options = array(
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_POST => true,
	        CURLOPT_POSTFIELDS => $postData,
	        CURLOPT_FOLLOWLOCATION => true,
	        CURLOPT_COOKIESESSION => true,
	        CURLOPT_COOKIEJAR => 'cookie.txt',
	        CURLOPT_URL => $url,
        );

        $ch = curl_init();  
        curl_setopt_array($ch, $options);  
        $data = curl_exec($ch);
        curl_close($ch); 
        return $data;
	}

function loadHtmlContentFromWebsite($url) {
		$ch = curl_init();
	    if (!ch) die("Couldn't initialize a cURL handle");

	    curl_setopt($ch, CURLOPT_URL,            $url);
	    curl_setopt($ch, CURLOPT_HEADER,         1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_AUTOREFERER   , 1);
	    curl_setopt($ch, CURLOPT_TIMEOUT,       120);
	    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

	    $result = curl_exec($ch);
	    $effUrl = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);
	    curl_close($ch); // close cURL handler


	    if ( $effUrl === $url ) {
	    	if ($this->IsNullOrEmptyString($result)) {
	    		$msg = "Emergency Stop: Empty web Contents";
	    		die($msg);
	    	}
	    	setLogs("Load html content successful...", $this->vars['logs']);
	       	return $result;
	    } else {
	    	$msg = "Request URL does not match Effective URL";
	      	die("Emergency Stop: " . $msg);
	    }    
	}
	function IsNullOrEmptyString($question){
    	return (!isset($question) || trim($question)==='');
	}//IsNullOrEmptyString
}