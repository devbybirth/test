<?php
 
    require_once('config.php');
    require_once('captureLog.php');
    //     ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
    $curl = curl_init();

    debugLog('AMEYO MANUAL DIAL API URL: '.MANUAL_DIAL_API_URL, LOG_PATH_API);
    debugLog('AMEYO MANUAL DIAL API DATA: '."{\"customerId\":".$_GET['customerId'].", \"campaignId\":".$_GET['campaignId'].", \"requestId\":\"".$_GET['uuid']."\", \"userCRTObjectId\":\"".$_GET['userCRTObjectId']."\", \"additionalParams\":{\"dialFromKB\": \"true\"}}", LOG_PATH_API);
    debugLog('USER SESSION ID: '.$_GET['sessionId'], LOG_PATH_API);

    curl_setopt_array($curl, array(
      CURLOPT_PORT => SERVER_PORT,
      CURLOPT_URL => MANUAL_DIAL_API_URL,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\"customerId\":".$_GET['customerId'].", \"campaignId\":".$_GET['campaignId'].", \"requestId\":\"".$_GET['uuid']."\", \"userCRTObjectId\":\"".$_GET['userCRTObjectId']."\", \"additionalParams\":{\"dialFromKB\": \"true\"}}",
      CURLOPT_HTTPHEADER => array(
        "content-type: application/json",
        "sessionId: ".$_GET['sessionId']
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }
    debugLog('AMEYO MANUAL DIAL API RESPONSE: '.$response, LOG_PATH_API);
?>