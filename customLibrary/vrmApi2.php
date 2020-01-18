
<?php


function getDigiCustomerDetailsResponseAPI($ucic)
{

    $now = DateTime::createFromFormat('U.u', microtime(true));
    date_timezone_set($now, timezone_open('Asia/Kolkata'));
    $timestamp = $now->format("Y-m-d\TH:i:s.u");

    $uniqueId="Ameyo".uniqid();

    $ameyo_cnvId = $uniqueId; 
    $ameyo_bizObj = $uniqueId; 
    $ameyo_msgId = $uniqueId; 
    $ameyo_extRefId = $uniqueId; 
    $ameyo_timestamp = $timestamp;  
    $ameyo_brnchId='9999'; 
    $ameyo_appId='WIZ'; 
    $ameyo_usrId='TELLER01';
    $ameyo_ucic=$ucic;

    $requestArray = array();


    //$url='http://10.107.10.37:18000/getDigiCustomerDetails';
     $url = 'https://reqres.in/api/users?page=2';

    // $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['cnvId']=$ameyo_cnvId;
    // $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['bizObjId']=$ameyo_bizObj;
    // $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['appId']=$ameyo_appId;   
    // $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['msgId']=$ameyo_msgId;  
    // $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['extRefId']=$ameyo_extRefId;  
    // $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['timestamp']=$ameyo_timestamp;  
     
    // $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['authInfo']['brnchId']=$ameyo_brnchId;
    // $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['authInfo']['usrId']=$ameyo_usrId;

    // $requestArray['getDigiCustomerDetailsRequest']['msgBdy']['ucic']=$ameyo_ucic;


    $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['cnvId']="DIG2019110818005644800093";
    $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['bizObjId']="DIG2019110818005644800093";
    $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['appId']="WIZ";   
    $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['msgId']="DIG2019110818005644800093";  
    $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['extRefId']="DIG2019110818005644800093";  
    $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['timestamp']="2019-11-06T10:00:42.297Z";  
     
    $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['authInfo']['brnchId']="9999";
    $requestArray['getDigiCustomerDetailsRequest']['msgHdr']['authInfo']['usrId']="TELLER01";

    $requestArray['getDigiCustomerDetailsRequest']['msgBdy']['customerDetailsReq'][0]['ucic']="4209334";


 


$headr = array();
$headr[] = 'Content-type:application/json';

$data = json_encode($requestArray);

//  print_r($data);exit;

//echo $url.$data;
########## Execute API ################

    $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);
    $response = curl_exec($ch);
 
    debugLog("Sent API Request ( URL : $url ,  data formed: $data)");

    if(curl_error($ch))
    {
        // return "Error:-".curl_error($ch);
        debugLog("API Error :  ".curl_error($ch));
        return false;
        
    }


    if($response)
    {
        debugLog("API Response Received :  $response");
        return $response;
        
    }
}
   


?>
