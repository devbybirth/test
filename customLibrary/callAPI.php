<?php
    //author: pankajgupta@ameyo.com aka baba @ 9560838554
    require_once('config.php');
    require_once('captureLog.php');

    function curlAPI($command, $data)
	{
        switch ($command) 
        {
            case 'downloadVoiceMail':
                $url = DOWNLOAD_VOICEMAIL_API_URL; 
                $dataArr = array(   "voiceMailId" => $data, 
                                    "sessionId" => SESSION_ID);
                $data = json_encode($dataArr);
                echo $url."&data=".urlencode($data); 
                debugLog('AMEYO VOICEMAIL DOWNLOAD URL: '. $url."&data=".urlencode($data), LOG_PATH_API);
                exit(0x0);
                break;
            case 'dialCustomer':
                $url = MANUAL_DIAL_API_URL;
                $dataArr = array(   "campaignId" => CAMPAIGN_ID,
                                    "sessionId" => SESSION_ID,
                                    "phone" => $data,
                                    "additionalParams" => array("dialFromKB" => "true"));
                $data = json_encode($dataArr);
                debugLog('AMEYO MANUAL DIAL API URL: '. $url."&data=".urlencode($data), LOG_PATH_API);
                break;
            case 'resolveCustomer':
                $url = UPDATE_VOICEMAIL_API_URL."?voiceMailIds=".$data."&isRead=true";
                require_once('resolveCustomer.php');
                break;
            case 'LockCustomer':
            case 'ReleaseCustomer':
                require_once('lockReleaseCustomer.php');
                exit(0x0);
                break;
            default:
                exit('0x3');
                break;
        }

        $finalURL= $url.'&data='.urlencode($data);
        $command != 'resolveCustomer' ? : $finalURL = $url;

        $curl = curl_init();

        debugLog('AMEYO REST API URL: '. $finalURL, LOG_PATH_API);
        debugLog('AMEYO REST API DATA: '. $data, LOG_PATH_API);
        
        curl_setopt_array($curl, array(
        CURLOPT_PORT => SERVER_PORT,
        CURLOPT_URL => $finalURL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET"       
    ));

        if($command != 'resolveCustomer') 
        {
            curl_setopt_array($curl, array(CURLOPT_HTTPHEADER => array("cache-control: no-cache")));
        }
        else
        { 
            curl_setopt_array($curl, array(CURLOPT_HTTPHEADER => array("Content-Type: application/json","cache-control: no-cache","sessionId: ".SESSION_ID)));
        }

        $response = curl_exec($curl);
        debugLog('AMEYO REST API RESPONSE: '.$response, LOG_PATH_API);	

        $err = curl_error($curl);
        curl_close($curl);
        $err ? debugLog('AMEYO REST API ERROR RESPONSE: '.$err, LOG_PATH_API): print_r($response);
    }
    
    curlAPI($_GET['command'], $_GET['data']);
?>