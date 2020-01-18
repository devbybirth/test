<?php
    //author: pankajgupta@ameyo.com aka baba @ 9560838554
    require_once('config.php');

    $unique_id = uniqid("LOG-FLOWID-");

	function debugLog($message, $logfile=LOG_PATH_API)
	{
		global $$unique_id;
	  file_exists($logfile) ? : mkdir($logfile, 0777, true); 
	  $log_file_data = $logfile.'/'.date('d-M-Y').'.log';
	  $now     = "\n[" . date("Y-M-d H:i:s") . "] ";
	  $message = $now . $unique_id. $message;
	  error_log($message, 3, $log_file_data);
	}
?>