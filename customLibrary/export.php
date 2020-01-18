<?php
   
  require_once('captureLog.php');
  require_once('postgresqlCommUtils.php');


    
  $dummyDBConnection = new CommunicationUtils(DUMMYDB_HOST, DUMMYDB_NAME); 

  

  if(isset($_GET["tag"]) && $_GET["tag"]=='exportAccount')
  {
    $account= $_GET["accountNo"];
    
    header('Content-Type: text/csv; charset=utf-8');  
    header('Content-Disposition: attachment; filename=data.csv');  
    $output = fopen("php://output", "w");  
    
    fputcsv($output, array('Customer Id', 'Account Number', 'Repay Com', 'Repay Start Date', 'Repay Due Date','Repay Amount Due','Repay Amount Set','Repay Payment'));


    $getDummyDataQuery6=
    "select
    cust_id, 
    repay_acc,
    repay_com,
    repay_stdate,
    repay_duedate,
    repay_amtdue,
    repay_amtset,
    repay_pmt 
    from repaymentschedule_table where repay_acc='".$account."'";	
    
    $dummyRepaymentData = $dummyDBConnection->getResult($getDummyDataQuery6);
    //print_r($dummyRepaymentData);

    foreach($dummyRepaymentData as $value)  
    {  
      fputcsv($output, $value);  
    }  

    fclose($output);  
  } 

////////////////////////////////////////Exporting Filter Data Start/////////////////////////////////////

  $userid= $_GET['user_id'];
  $CAMPAIGN_ID=$_GET['campaign_id'];
  $SERVER_BASE_URL=$_GET['server_base_url'];
  $USER_CRT_OBJECT_ID=$_GET['user_crt_object_id'];
  $SESSION_ID=$_GET['session_id'];

  $ameyoDBConnection = new CommunicationUtils(AMEYODB_HOST, AMEYODB_NAME);
    $getAgentLeadQuery = "SELECT 
        lead_id 
       FROM 
         campaign_lead_details 
      WHERE 
         id IN 
             (SELECT 
                     cl.campaign_lead_id 
                FROM 
                (
                     (SELECT 
                         campaign_lead_id, 
                         campaign_user_id 
                     FROM 
                         campaign_lead_user_mapping) cl 
                     JOIN 
                     (SELECT 
                         id 
                      FROM 
                         campaign_context_user 
                     WHERE 
                         user_id='".$userid."' AND 
                         campaign_context_id='".$CAMPAIGN_ID."' )ccu 
                     ON 
                         (cl.campaign_user_id=ccu.id)
                 )
             ) LIMIT 1";


    debugLog("GET AGENT LEAD ID QUERY: ".$getAgentLeadQuery, LOG_PATH_PGSQL);
    $getResult_Data = $ameyoDBConnection->getResult($getAgentLeadQuery);
    $agentLeadId  = $getResult_Data[0][0];



  if(isset($_GET["tag"]) && $_GET["tag"]=='exportFilterData')
  {
    $filterName=$_GET['filterName'];
    $filterValue=$_GET['filterValue'];
    $operatorValue=$_GET['operatorValue'];
    


    //echo $filterName." ".$filterValue." ".$operatorValue;exit;
    
    header('Content-Type: text/csv; charset=utf-8');  
    header('Content-Disposition: attachment; filename=FilterData.csv');  
    $output = fopen("php://output", "w");

    if($filterName=='ins_endate')
    {
      //$insDate = date("d-m-Y", strtotime($filterValue));

      $getCustomerDataQuery=
        "SELECT *  FROM insurance_table WHERE to_date($filterName,'DD-MM-YYYY') $operatorValue '$filterValue' and cust_id in (select (t1.equitas_customer_id) from dblink('dbname=equitasvrm','select equitas_customer_id,leadid from customer') as t1(equitas_customer_id varchar,leadid varchar) where leadid='".$agentLeadId."')";

      fputcsv($output, array('Customer Id', 'Insurance Name', 'Insurance No', 'Insurance Start Date', 'Insurance End Date')); 
    }

    else if($filterName=='lim_ren')
    {
      $limDate = date("d-m-Y", strtotime($filterValue));

      $getCustomerDataQuery=
        "SELECT * FROM limit_table WHERE $filterName $operatorValue '$limDate'  and cust_id in (select (t1.equitas_customer_id) from dblink('dbname=equitasvrm','select equitas_customer_id,leadid from customer') as t1(equitas_customer_id varchar,leadid varchar) where leadid='".$agentLeadId."')";
       
       fputcsv($output, array('Customer Id', 'Limit Code', 'Limit Amount', 'Limit Utilized', 'Limit Available','Limit Renewal')); 

    }
    
    else if($filterName=='loan_outs' || $filterName=='loan_prinod' || $filterName=='loan_intod')
    {
      $getCustomerDataQuery= "SELECT * FROM loan_table WHERE $filterName::float $operatorValue $filterValue AND cust_id in (select (t1.equitas_customer_id) from dblink('dbname=equitasvrm','select equitas_customer_id,leadid from customer') as t1(equitas_customer_id varchar,leadid varchar) where leadid='".$agentLeadId."')";

      //echo $getCustomerDataQuery;exit;

       fputcsv($output, array('Customer Id', 'Loan Account', 'Roi', 'Loan Outstanding', 'Loan Principal Outstanding','Loan Interest Outstanding'));
    }

    else if($filterName=='od_outs' || $filterName=='od_intod')
    { 
       $getCustomerDataQuery="SELECT * FROM od_table WHERE $filterName::float $operatorValue $filterValue and cust_id in (select (t1.equitas_customer_id) from dblink('dbname=equitasvrm','select equitas_customer_id,leadid from customer') as t1(equitas_customer_id varchar,leadid varchar) where leadid='".$agentLeadId."')"; 

       fputcsv($output, array('Customer Id', 'Od Account', 'Od Rate', 'Od Outstanding', 'Od Principal Outstanding','Od Interest Outstanding'));
    }
    

 
    $dummyRepaymentData = $dummyDBConnection->getResult($getCustomerDataQuery);
    //print_r($dummyRepaymentData);

    foreach($dummyRepaymentData as $value)  
    {  
         fputcsv($output, $value);  
    }  

    fclose($output);  
  }


    
