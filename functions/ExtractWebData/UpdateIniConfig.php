<?php
class UpdateIniConfig {

	function update($pageInfo, $vars) {
		$nextPageUrl = $pageInfo['nextPageUrl'];
		$currentPage = $pageInfo['currentPage'];

		if   ($nextPageUrl == 'lastPage') $start = $vars['end'];
		else $start = $currentPage + 1;

		$config = new Config_Lite($vars['iniConfigPath'], LOCK_EX);
		$config->set('pageNumber', 'start', $start);
		$config->set('site','url', $nextPageUrl);
		try {
			$config->save();
			setLogs('UpdateIniConfig Class: Save Config', $vars['logs']);
		} catch(Config_Lite_Exception $e) {
			setLogs('UpdateIniConfig Class: Save Config failed...', $vars['logs']);
			echo "UpdatingIniConfig:\n";
			echo 'Exception Message: ' . $e->getMessage();
		}
	}
}
