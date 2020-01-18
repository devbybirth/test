<?php


function getDigiCaseListRequestAPI($ucic)
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
  $ameyo_appId='FOS'; 
  $ameyo_usrId='IBUSER';
  $ameyo_ucic=$ucic;

  $requestArray = array();


  //$url='http://10.107.10.37:9899/getDigiCaseList';
  $url = 'https://reqres.in/api/users?page=2';

  // $requestArray['getDigiCaseListRequest']['msgHdr']['cnvId']=$ameyo_cnvId;
     //    $requestArray['getDigiCaseListRequest']['msgHdr']['bizObjId']=$ameyo_bizObj;
     //    $requestArray['getDigiCaseListRequest']['msgHdr']['appId']=$ameyo_appId;   
        // $requestArray['getDigiCaseListRequest']['msgHdr']['msgId']=$ameyo_msgId;  
        // $requestArray['getDigiCaseListRequest']['msgHdr']['extRefId']=$ameyo_extRefId;  
        // $requestArray['getDigiCaseListRequest']['msgHdr']['timestamp']=$ameyo_timestamp;  
         
        // $requestArray['getDigiCaseListRequest']['msgHdr']['authInfo']['brnchId']=$ameyo_brnchId;
        // $requestArray['getDigiCaseListRequest']['msgHdr']['authInfo']['usrId']=$ameyo_usrId;

        // $requestArray['getDigiCaseListRequest']['msgBdy']['ucic']=$ameyo_ucic;


        $requestArray['getDigiCaseListRequest']['msgHdr']['cnvId']="DIG2019110615304245600011";
        $requestArray['getDigiCaseListRequest']['msgHdr']['bizObjId']="DIG2019110615304245600011";
        $requestArray['getDigiCaseListRequest']['msgHdr']['appId']="FOS";   
        $requestArray['getDigiCaseListRequest']['msgHdr']['msgId']="DIG2019110615304245600011";  
        $requestArray['getDigiCaseListRequest']['msgHdr']['extRefId']="DIG2019110615304245600011";  
        $requestArray['getDigiCaseListRequest']['msgHdr']['timestamp']="2019-11-06T10:00:42.297Z";  
         
        $requestArray['getDigiCaseListRequest']['msgHdr']['authInfo']['brnchId']="9999";
        $requestArray['getDigiCaseListRequest']['msgHdr']['authInfo']['usrId']="IBUSER";

        $requestArray['getDigiCaseListRequest']['msgBdy']['ucic']="4209334";


     


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
            // echo "Error:-".curl_error($ch);
            debugLog("API Error :  ".curl_error($ch));
            return false;
            
        }


        if($response)
        {
            $res='{
  "getDigiCaseListResponse":{
    "msgHdr":{
      "rslt":"OK"
    },
    "msgBdy":{
      "cs":[
        {
          "id":"191107036115296",
          "sts":"Pending",
          "ctgry":"Customer Service",
          "subCtgry":"Assets Related",
          "subSubCtgry":"Request for New Product",
          "opnDt":"2019-11-07  15:44:54",
          "subject":"Request for New Product"
        },
        {
          "id":"191107036115292",
          "sts":"Pending",
          "ctgry":"Customer Service",
          "subCtgry":"Assets Related",
          "subSubCtgry":"Request for New Product",
          "opnDt":"2019-11-07  14:32:17",
          "subject":"Request for New Product"
        },
        {
          "id":"19110700115291",
          "sts":"Open",
          "ctgry":"Product management",
          "subCtgry":"Project Management",
          "subSubCtgry":"Product request",
          "opnDt":"2019-11-07  14:29:47",
          "subject":"Request for new product"
        },
        {
          "id":"191105036113944",
          "sts":"Pending",
          "ctgry":"Customer Service",
          "subCtgry":"Assets Related",
          "subSubCtgry":"Request for New Product",
          "opnDt":"2019-11-05  16:02:36",
          "subject":"Request for New Product"
        },
        {
          "id":"191105036113930",
          "sts":"Pending",
          "ctgry":"Customer Service",
          "subCtgry":"Assets Related",
          "subSubCtgry":"Request for New Product",
          "opnDt":"2019-11-05  15:51:02",
          "subject":"Request for New Product"
        },
        {
          "id":"191016036110812",
          "sts":"Pending",
          "ctgry":"Customer Service",
          "subCtgry":"Assets Related",
          "subSubCtgry":"Grievance - MFI",
          "opnDt":"2019-10-16  23:56:47",
          "subject":"Grievance - MFI"
        },
        {
          "id":"191016036110811",
          "sts":"Pending",
          "ctgry":"Customer Service",
          "subCtgry":"Assets Related",
          "subSubCtgry":"Address or Mobile Number updation",
          "opnDt":"2019-10-16  23:55:49",
          "subject":"Address or Mobile Number Updation"
        },
        {
          "id":"191016036110810",
          "sts":"Pending",
          "ctgry":"Customer Service",
          "subCtgry":"Assets Related",
          "subSubCtgry":"Address or Mobile Number updation",
          "opnDt":"2019-10-16  23:54:40",
          "subject":"Address or Mobile Number Updation"
        },
        {
          "id":"191016036110809",
          "sts":"Pending",
          "ctgry":"Customer Service",
          "subCtgry":"Assets Related",
          "subSubCtgry":"Request for New Product",
          "opnDt":"2019-10-16  23:52:34",
          "subject":"Request for New Product"
        },
        {
          "id":"191010036110652",
          "sts":"Pending",
          "ctgry":"Customer Service",
          "subCtgry":"Assets Related",
          "subSubCtgry":"Grievance - MFI",
          "opnDt":"2019-10-10  11:27:29",
          "subject":"Grievance - MFI"
        },
        {
          "id":"191010036110650",
          "sts":"Pending",
          "ctgry":"Customer Service",
          "subCtgry":"Assets Related",
          "subSubCtgry":"Address or Mobile Number updation",
          "opnDt":"2019-10-10  11:26:45",
          "subject":"Address or Mobile Number Updation"
        },
        {
          "id":"191010036110649",
          "sts":"Pending",
          "ctgry":"Customer Service",
          "subCtgry":"Assets Related",
          "subSubCtgry":"Address or Mobile Number updation",
          "opnDt":"2019-10-10  11:25:25",
          "subject":"Address or Mobile Number Updation"
        },
        {
          "id":"191010036110648",
          "sts":"Pending",
          "ctgry":"Customer Service",
          "subCtgry":"Assets Related",
          "subSubCtgry":"Request for New Product",
          "opnDt":"2019-10-10  11:24:05",
          "subject":"Request for New Product"
        },
        {
          "id":"191003036110598",
          "sts":"Closed",
          "ctgry":"Customer Service",
          "subCtgry":"Internet Banking / Mobile Banking",
          "subSubCtgry":"Enabling Internet Banking / Mobile Banking",
          "opnDt":"2019-10-03  15:45:14",
          "subject":"test"
        },
        {
          "id":"190924036110397",
          "sts":"Closed",
          "ctgry":"Customer Service",
          "subCtgry":"Internet Banking / Mobile Banking",
          "subSubCtgry":"Disable Internet Banking / Mobile Banking",
          "opnDt":"2019-09-24  17:16:07",
          "subject":"test"
        },
        {
          "id":"19110700115678",
          "sts":"Open",
          "ctgry":"Product management",
          "subCtgry":"Project Management",
          "subSubCtgry":"Product request",
          "opnDt":"2019-11-07  14:29:47",
          "subject":"Request for new product"
        },
        {
          "id":"190924036110396",
          "sts":"Closed",
          "ctgry":"Customer Service",
          "subCtgry":"Internet Banking / Mobile Banking",
          "subSubCtgry":"Disable Internet Banking / Mobile Banking",
          "opnDt":"2019-09-24  17:12:18",
          "subject":"test"
        }
      ]

    }
  }
}';
            debugLog("API Response Received :  $response");
            //return $response;
            return $res;
            
        }  
}

?>
