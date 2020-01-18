
<link rel="stylesheet" type="text/css" href="customLibrary/custom.css"/>
<!-- <script type="text/javascript" src="customLibrary/customJS.js"></script>
 -->
 <style type="text/css">
   .dpd_table{
    display: none;
   }
 </style>
  <script type="text/javascript">
  function demo(id)
    {
      alert(2);    
    // var y =$("#abc_"+id).html();
    // alert(y);

    console.log('abc_'+id);
    $("table#abc_"+id).removeClass(".dpd_table");
    //  let x = document.getElementById("abc_"+id);

    // //x.style.display = "block";
    // //alert(x);
    // if (x.style.display == "none") 
    // {
    //   //alert(id);
    //   x.style.display = "block";
    // } 
    // else 
    // {
    //   //alert("hello");
    //   x.style.display = "none";
    // }
  }
</script>

<?php

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
    require_once('captureLog.php');
    require_once('vrmApi1.php');  
    //require_once('vrmApi2.php');
    require_once('postgresqlCommUtils.php');

    $ucic = ltrim($_POST['ucic'],'#');
    $agentLeadId = $_POST['agentLeadId'];


    $crmDBConnection = new CommunicationUtils(CRMDB_HOST, CRMDB_NAME);
    
    $getCustomerDataQuery=
    "select equitas_customer_id,fname,contact_person,phone1,branch_id,acmcode,rmcode,zone,entity,customer_birthday,customer_anniversary from customer where equitas_customer_id='".$ucic."' and leadid='".$agentLeadId."'";

    // $getCustomerDataQuery=
    // "select equitas_customer_id,fname,contact_person,phone1,branch_id,acmcode,rmcode,zone,entity,customer_birthday,customer_anniversary from customer where equitas_customer_id='".$ucic."' and leadid='20'";

    //echo $getCustomerDataQuery; exit;

    debugLog("GET CUSTOMER DATA QUERY: ".$getCustomerDataQuery, LOG_PATH_PGSQL);
    $customerData = $crmDBConnection->getResult($getCustomerDataQuery);
    debugLog("CUSTOMER DATA FROM QUERY: ".json_encode($customerData), LOG_PATH_PGSQL);

    //print_r($customerData); exit;

    //---------------------------Customer Card Data From CRMDB starts------------------------

     foreach ($customerData as $value)
     {
        $customer_id=$value[0];
        $customer_name=$value[1];
        $contact_person=$value[2];
        $phone=$value[3];
        $branch=$value[4];
        $acmcode=$value[5];
        $rmcode=$value[6];
        $zone=$value[7];
        $entity=$value[8];
        $customer_birthday=$value[9];
        $customer_anniversary=$value[10];
     }
    //-----------------------------Customer Card Data From CRMDB ends--------------------
  
    
    //-----------------------------Rest of the card Info Starts--------------------

    $dummyDBConnection = new CommunicationUtils(DUMMYDB_HOST, DUMMYDB_NAME);
    
    $getDummyDataQuery1="select col_code,col_desc,col_cate,col_val from collateral_table where cust_id='".$ucic."'";

    $getDummyDataQuery2="select ins_name,ins_no,ins_stdate,ins_endate from insurance_table where cust_id='".$ucic."'";

    $getDummyDataQuery3="select lim_code,lim_amt,lim_utilized,lim_avail,lim_ren from limit_table where cust_id='".$ucic."'";

    $getDummyDataQuery4="select loan_acct,roi,loan_outs,loan_prinod,loan_intod from loan_table where cust_id='".$ucic."'";

    $getDummyDataQuery5="select od_acct,od_rate,od_outs,od_intod from od_table where cust_id='".$ucic."'";

    $getDummyDataQuery6="select distinct repay_acc from repaymentschedule_table where cust_id='".$ucic."'";

    $getDummyDataQuery7="select stock_code,stock_colval,stock_stdate,stock_endate from stock_table where cust_id='".$ucic."'";
   
    $getDummyDataQuery8="select distinct(dpd) from pendingdef_table where cust_id='".$ucic."'";
    //$getDummyDataQuery8="select name,type,nature,deferral,dod,duedate,status,remarks,dpd from pendingdef_table where cust_id='".$ucic."'";

    

    $getBranchDetail="select * from branch_table where bname='".$branch."'";

    $getpdDetails="select * from pendingdef_table where cust_id='".$ucic."'";
    


    //debugLog("GET CUSTOMER DATA QUERY: ".$getDummyDataQuery1, LOG_PATH_PGSQL);
    $dummyCollateralData = $dummyDBConnection->getResult($getDummyDataQuery1);
    //debugLog("CUSTOMER DATA FROM QUERY: ".json_encode($dummyCollateralData), LOG_PATH_PGSQL);

    $dummyInsuranceData = $dummyDBConnection->getResult($getDummyDataQuery2);
    $dummyLimitData = $dummyDBConnection->getResult($getDummyDataQuery3);
    $dummyLoanData = $dummyDBConnection->getResult($getDummyDataQuery4);
    $dummyOdData = $dummyDBConnection->getResult($getDummyDataQuery5);
    $dummyRepaymentData = $dummyDBConnection->getResult($getDummyDataQuery6);
    $dummyStockData = $dummyDBConnection->getResult($getDummyDataQuery7);
    $dummyPendingdefData_dpd = $dummyDBConnection->getResult($getDummyDataQuery8);
    
    $branchData =$dummyDBConnection->getResult($getBranchDetail);
    foreach ($branchData as $value) 
    { 
      
      $bname=$value[0];
      $bemail=$value[1];
      $bphone=$value[2];
    }
    // echo $bname; exit;
  //---------------------------------Rest of the card Info Ends-------------------------



    // Calling VRM API 1 start
        $api1_result=getDigiCaseListRequestAPI($ucic);
        if(!$api1_result)
        {
            echo "Api1 Error";exit;
        }
        
        $api1_data=json_decode($api1_result,true);

        $arrPendingTicket=array();
        $arrOpenTicket=array();
        $i=0;
        $k=0;

        foreach ($api1_data['getDigiCaseListResponse']['msgBdy']['cs'] as $value)
        {
          if($value['sts']=='Pending' || $value['sts']=='Open')
          {
            //$arrPending[]=$value['opnDt']." ".$value['id'].'&S.No13;&S.No10';
            $arrPendingTicket[$i]['date']=$value['opnDt'];
            $arrPendingTicket[$i]['ticket']=$value['id'];
            $i++;
          }
          if($value['sts']=='Closed')
          {
            //$arrOpen[]=$value['opnDt']." ".$value['id'].'&S.No13;&S.No10';
            $arrOpenTicket[$k]['date']=$value['opnDt'];
            $arrOpenTicket[$k]['ticket']=$value['id'];
            $k++;
          }
              
        }

        //$pendingList=implode(" ",$arrPending);
        //$openList=implode(" ",$arrOpen);
        //print_r($arrOpenTicket);exit;

    
    // Calling VRM API 1 ends




    


$response = '<form>';

 
 $response .= ' <div class="col-xs-5">
                
                    

                  <div class="panel panel-info scroll">
                    <div class="panel-heading text-center"><strong>Customer Details</strong></div>
                    <div class="panel-body" style="font-size:11px";>

                        <table>
                                  
                                    
                                <tr>
                                  <th style="padding:6px">Customer ID :</th>
                                  <td style="padding:6px">'.$customer_id.'</td>
                                </tr>
                             
                                <tr>
                                  <th style="padding:6px">Customer Name :</th>
                                  <td style="padding:6px">'.$customer_name.'</td>
                                </tr>

                                <tr>
                                  <th style="padding:6px">Contact Person :</th>
                                  <td style="padding:6px">'.$contact_person.'</td>
                                </tr>

                                <tr>
                                  <th style="padding:6px">Phone Number:</th>
                                  <td style="padding:6px">'.$phone.'</td>
                                </tr>

                                <tr>
                                  <th style="padding:6px">Base Branch :</th>
                                  <td style="padding:6px">
                                    <div class="popup" onclick="myFunction()">
                                      <button type="button" class="btn btn-xs btn-info">'.$branch.'</button>
                                      <span class="popuptext">
                                            Branch Name  : '.$bname.'
                                        <br>Branch Email : '.$bemail.'
                                        <br>Branch Phone : '.$bphone.'
                                      </span>
                                    </div>
                                  </td>
                                </tr>
			                          
                                <tr>
                                  <th style="padding:6px">ACM Code:</th>
                                  <td style="padding:6px">'.$acmcode.'</td>
                                </tr>

                                <tr>
                                  <th style="padding:6px">RM Code:</th>
                                  <td style="padding:6px">'.$rmcode.'</td>
                                </tr>
                               
                                 <tr>
                                  <th style="padding:6px">Zone:</th>
                                  <td style="padding:6px">'.$zone.'</td>
                                </tr>

                                  <tr>
                                  <th style="padding:6px">Entity:</th>
                                  <td style="padding:6px">'.$entity.'</td>
                                </tr>


                                <tr>
                                  <th style="padding:6px">DOB:</th>
                                  <td style="padding:6px">'.$customer_birthday.'</td>
                                </tr>

                                <tr>
                                  <th style="padding:6px">Anniversary :</th>
                                  <td style="padding:6px">'.$customer_anniversary.'</td>
                                </tr>
                                   
                            </table>
                        </div>
                  </div>

                
                  <div class="panel panel-info scroll">
                        <div class="panel-heading text-center"><strong>Service Request</strong></div>
                        <div class="panel-body" style="font-size:10px">
                            
                                
                                <table class="table table-bordered myTable1">
                                  <p class="text-center"><b>Pending Tickets</b></p>
                                  <thead>
                                    <tr>
                                      <th scope="col">S.No</th>
                                      <th scope="col">Date</th>
                                      <th scope="col">Ticket No</th>
                                      
                                    </tr>
                                  </thead>
                                  <tbody>';
                                    $j=1;
                                    foreach ($arrPendingTicket as $value)
                                    {
                                    
                        $response.=  '<tr>
                                      <th scope="row">'.$j.'</th>
                                      <td>'.$value["date"].'</td>
                                      <td>'.$value["ticket"].'</td>
                                     
                                    </tr>';
                                    $j++;
                                    }
                                    
                        $response.='</tbody>
                                </table>
                            

                            
                                
                                <table class="table table-bordered myTable1">
                                  <p class="text-center"><b>Closed Tickets</b></p>
                                  <thead>
                                    <tr>
                                      <th scope="col">S.No</th>
                                      <th scope="col">Date</th>
                                      <th scope="col">Ticket No</th>
                                      
                                    </tr>
                                  </thead>
                                  <tbody>';

                                    $w=1;
                                    foreach ($arrOpenTicket as $value)
                                    {
                                    
                        $response.=  '<tr>
                                      <th scope="row">'.$w.'</th>
                                      <td>'.$value["date"].'</td>
                                      <td>'.$value["ticket"].'</td>
                                     
                                    </tr>';
                                    $w++;
                                    }
                                     
                                    
                        $response.= '</tbody>
                                </table>
                            
                        </div>
                  </div>
                    
               
                  <div class="panel panel-info scroll">
                        <div class="panel-heading text-center"><strong>Repayment Schedule</strong></div>
                        <div class="panel-body" style="font-size:10px">

                            <table class="table table-bordered myTable1">
                                  
                                <thead>
                                    <tr>
                                      <th scope="col">S.No</th>
                                      <th scope="col">Account Number</th>
                                      <th scope="col">Action</th>

                                    </tr>

                                </thead>
                                
                                <tbody>';
                                    $s=1;
                                    foreach ($dummyRepaymentData as $value)
                                    {
                                    
                        $response.= '<tr>
                                      <th scope="row">'.$s.'</th>
                                      <td>'.$value[0].'</td>
                                      <td><a href="customLibrary/export.php?tag=exportAccount&accountNo='.$value[0].'" class="btn btn-info btn-xs">Export</a></td>
                                      
                                      
                                    </tr>';
                                    $s++;
                                    }
                                    
                        $response.='</tbody>
                                </table>
                        </div> 
                  </div>
                
                  <div class="panel panel-info scroll">
                        <div class="panel-heading text-center"><strong>Pending Deferrals</strong></div>
                        <div class="panel-body" style="font-size:10px">

                            <table class="table table-bordered myTable1">

                                <thead>
                                    <tr>
                                      <th scope="col">S.No</th>
                                      <th scope="col">DPD</th>

                                    </tr>

                                </thead>

                                <tbody>';
                                    $s=1;
                                    foreach ($dummyPendingdefData_dpd as $value1)
                                    {

                        $response.= '<tr>
                                      <th scope="row">'.$s.'</th>
                                      <td>
                                        
                                          <button type="button" class="btn btn-info btn-xs" onclick="demo(\''.$value1[0].'\')">'.$value1[0].'</button>';


                          $response.= '<table class="dpd_table" border="1" id="abc_'.$value1[0].'">
                                      <thead> 
                                        <tr class="text-center">
                                          <th>Name</th> 
                                          <th>Type</th> 
                                          <th>Nature</th> 
                                          <th>DOD</th> 
                                          <th>Due Date</th> 
                                          <th>Status</th> 
                                          <th>Remarks</th> 
                                          <th>DPD</th>
                                        </tr>
                                        </thead><tbody>';                
                                             
                                          $getpdDetails="select * from pendingdef_table where dpd='".$value1[0]."'";
                                          //echo $getpdDetails;exit;
                                          $pdData =$dummyDBConnection->getResult($getpdDetails);
                                          foreach ($pdData as $value)
                                          {
                                            
                          $response1=      '<tr><td>'.$value[1].'</td>
                                            <td>'.$value[2].'</td>
                                            <td>'.$value[3].'</td>
                                            <td>'.$value[5].'</td>
                                            <td>'.$value[6].'</td>
                                            <td>'.$value[7].'</td>
                                            <td>'.$value[8].'</td>
                                            <td>'.$value[9].'</td></tr>';
                                          }
                          $response.=     $response1.'</tbody></table>
                                        
                                      </td>


                                    </tr>';
                                    $s++;
                                    }

                        $response.='</tbody>
                                 </table>
                        </div>
                  </div>

                </div>


                <div class="col-xs-7">
                    
                    <div class="panel panel-info scroll">

                        <div class="panel-heading text-center"><strong>Loan Details</strong></div>

                        <div class="panel-body" style="font-size:10px">
                           <table class="table table-bordered myTable1">
                                  
                                  <thead>
                                    <tr>
                                      <th scope="col">S.No</th>
                                      <th scope="col">AccountNumber</th>
                                      <th scope="col">Roi</th>

                                      <th scope="col">Outstanding</th>
                                      <th scope="col">PrincipleOverDue</th>
                                      <th scope="col">InterestOverDue</th>
                                      
                                      
                                      
                                    </tr>
                                  </thead>
                                  <tbody>';
                                    $j=1;
                                    foreach ($dummyLoanData as $value)
                                    {
                                    
                        $response.=  '<tr>
                                      <th scope="row">'.$j.'</th>
                                      <td>'.$value[0].'</td>
                                      <td>'.$value[1].'</td>
                                      <td>'.$value[2].'</td>
                                      <td>'.$value[3].'</td>
                                      <td>'.$value[4].'</td>
                                      

                                     
                                    </tr>';
                                    $j++;
                                    }
                                    
                        $response.='</tbody>
                                </table>
                        </div>
                    </div>

                    <div class="panel panel-info scroll">

                        <div class="panel-heading text-center"><strong>OD Details</strong></div>

                        <div class="panel-body" style="font-size:10px">
                           <table class="table table-bordered myTable1">
                                  
                                  <thead>
                                    <tr>
                                      <th scope="col">S.No</th>
                                      
                                      <th scope="col">AccountNumber</th>
                                      <th scope="col">OD Rate</th>
                                      <th scope="col">Outstanding</th>
                                      <th scope="col">InterestOverDue</th>
                                                                        
                                    </tr>
                                  </thead>
                                  <tbody>';
                                    $j=1;
                                    foreach ($dummyOdData as $value)
                                    {
                                    
                        $response.=  '<tr>
                                      <th scope="row">'.$j.'</th>
                                      <td>'.$value[0].'</td>
                                      <td>'.$value[1].'</td>
                                      <td>'.$value[2].'</td>
                                      <td>'.$value[3].'</td>
                                    </tr>';
                                    $j++;
                                    }
                                    
                        $response.='</tbody>
                                </table>
                        </div>
                    </div> 

                    <div class="panel panel-info scroll">
                        <div class="panel-heading text-center"><strong>Insurance Details</strong></div>
                        <div class="panel-body" style="font-size:10px">

                            <table class="table table-bordered myTable1">
                                  
                                  <thead>
                                    <tr>
                                      <th scope="col">S.No</th>
                                      
                                      <th scope="col">Insurance Name</th>
                                      <th scope="col">Insurance No</th>
                                      <th scope="col">Insurance Start Date</th>
                                      <th scope="col">Insurance End Date</th>
                                      
                                    </tr>
                                  </thead>
                                  <tbody>';
                                    $j=1;
                                    foreach ($dummyInsuranceData as $value)
                                    {
                                    
                        $response.=  '<tr>
                                      <th scope="row">'.$j.'</th>
                                      <td>'.$value[0].'</td>
                                      <td>'.$value[1].'</td>
                                      <td>'.$value[2].'</td>
                                      <td>'.$value[3].'</td>
                                      

                                     
                                    </tr>';
                                    $j++;
                                    }
                                    
                        $response.='</tbody>
                                </table>
                            
                        </div>
                    </div>

                    <div class="panel panel-info scroll">
                        <div class="panel-heading text-center"><strong>Collateral Details</strong></div>
                        <div class="panel-body" style="font-size:10px">
                          
                          <table class="table table-bordered myTable1">
                                  
                                  <thead>
                                    <tr>
                                      <th scope="col">S.No</th>
                                      
                                      <th scope="col">Collateral Code</th>
                                      <th scope="col">Collateral Description</th>
                                      <th scope="col">Collateral Category</th>
                                      <th scope="col">Collateral Value</th>
                                      
                                    </tr>
                                  </thead>
                                  <tbody>';
                                    $j=1;
                                    foreach ($dummyCollateralData as $value)
                                    {
                                    
                        $response.=  '<tr>
                                      <th scope="row">'.$j.'</th>
                                      <td>'.$value[0].'</td>
                                      <td>'.$value[1].'</td>
                                      <td>'.$value[2].'</td>
                                      <td>'.$value[3].'</td>
                                      

                                     
                                    </tr>';
                                    $j++;
                                    }
                                    
                        $response.='</tbody>
                                </table>
                            
                        </div>
                    </div>       
                
                

                    <div class="panel panel-info scroll">
                        <div class="panel-heading text-center"><strong>Limit Details</strong></div>
                        <div class="panel-body" style="font-size:10px">

                            <table class="table table-bordered myTable1">
                                  
                                  <thead>
                                    <tr>
                                      <th scope="col">S.No</th>
                                     
                                      <th scope="col">Limit Code</th>
                                      <th scope="col">Limit Amount</th>
                                      <th scope="col">Limit Utilized</th>
                                      <th scope="col">Limit Available</th>
                                      <th scope="col">Limit Renewal</th>
                                    </tr>
                                  </thead>
                                  <tbody>';
                                    $j=1;
                                    foreach ($dummyLimitData as $value)
                                    {
                                    
                        $response.=  '<tr>
                                      <th scope="row">'.$j.'</th>
                                      <td>'.$value[0].'</td>
                                      <td>'.$value[1].'</td>
                                      <td>'.$value[2].'</td>
                                      <td>'.$value[3].'</td>
                                      <td>'.$value[4].'</td>	
						
                                     
                                    </tr>';
                                    $j++;
                                    }
                                    
                        $response.='</tbody>
                                </table>
                            
                            
                        </div>
                    </div>

                    <div class="panel panel-info scroll">
                        <div class="panel-heading text-center"><strong>Stock Details</strong></div>
                        <div class="panel-body" style="font-size:10px">

                            
                            
                            <table class="table table-bordered myTable1">
                                  
                                  <thead>
                                    <tr>
                                      <th scope="col">S.No</th>
                                      
                                      <th scope="col">Stock Code</th>
                                      <th scope="col">Stock Collateral Value</th>
                                      <th scope="col">Start Date</th>
                                      <th scope="col">End Date</th>
                                      
                                      
                                    </tr>
                                  </thead>
                                  <tbody>';
                                    $j=1;
                                    foreach ($dummyStockData as $value)
                                    {
                                    
                        $response.=  '<tr>
                                      <th scope="row">'.$j.'</th>
                                      <td>'.$value[0].'</td>
                                      <td>'.$value[1].'</td>
                                      <td>'.$value[2].'</td>
                                      <td>'.$value[3].'</td>
                                      
                                      

                                     
                                    </tr>';
                                    $j++;
                                    }
                                    
                        $response.='</tbody>
                                </table>
                        </div>
                    </div>

                </div>';




$response .= '</form>';

echo $response;
exit;

?>

