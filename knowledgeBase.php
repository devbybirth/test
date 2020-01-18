<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
 
require_once('customLibrary/postgresqlCommUtils.php');
require_once('customLibrary/captureLog.php');
   
$userid= $_GET['user_id'];
$CAMPAIGN_ID=$_GET['campaign_id'];
$SERVER_BASE_URL=$_GET['server_base_url'];
$USER_CRT_OBJECT_ID=$_GET['user_crt_object_id'];
$SESSION_ID=$_GET['session_id'];

//echo $userid; exit;
   

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

      
//$getAgentLeadQuery ="select id from lead where owner_user_id='".$userid."' limit 1";
//$getAgentLeadQuery="select lead_id from campaign_context_lead where campaign_context_id ='".$CAMPAIGN_ID."' and lead_id in (select id from lead where owner_user_id='".$userid."') limit 1";

            

debugLog("GET AGENT LEAD ID QUERY: ".$getAgentLeadQuery, LOG_PATH_PGSQL);
$getResult_Data = $ameyoDBConnection->getResult($getAgentLeadQuery);
$agentLeadId  = $getResult_Data[0][0];
//echo $agentLeadId; exit;
debugLog("DATA AGENT LEAD ID FROM QUERY: ".$agentLeadId, LOG_PATH_PGSQL);

$crmDBConnection   = new CommunicationUtils(CRMDB_HOST, CRMDB_NAME);
    


if($_GET['tag']=='showall')
{    

  $getCustomerDataQuery = 
    "SELECT
      equitas_customer_id,
      fname,
      contact_person,
      phone1,
      branch_id,
      acmcode,
      customer_id
            
    FROM
      customer
    WHERE
    leadid = '".$agentLeadId."'";
}
//echo $getCustomerDataQuery; exit;

if($_GET['tag']=='listsearch')
{
  $filterName=$_GET['filterName'];
  $filterValue=$_GET['filterValue'];
  $operatorValue=$_GET['operatorValue'];

    // CREATED DBLINK AT CUSTOMER END]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]

    //echo $filterName.$filterValue.$operatorValue ; exit;
  if($filterName=='ins_endate')
  {
    //$insDate = date("d-m-Y", strtotime($filterValue));

    $getCustomerDataQuery=
      "SELECT equitas_customer_id,fname,contact_person,phone1,branch_id,customer_birthday,customer_anniversary,customer_id FROM customer WHERE leadid = '".$agentLeadId."' and equitas_customer_id in (select distinct(t1.cust_id) from dblink('dbname=dummyequitasvrmdb','select distinct(cust_id),$filterName from insurance_table') as t1(cust_id varchar,$filterName varchar) where to_date($filterName,'DD-MM-YYYY') $operatorValue '$filterValue')";
      //echo $getCustomerDataQuery; exit;
  }

  else if($filterName=='lim_ren')
  {
    $limDate = date("d-m-Y", strtotime($filterValue));

    $getCustomerDataQuery=
      "SELECT equitas_customer_id,fname,contact_person,phone1,branch_id,customer_birthday,customer_anniversary,customer_id FROM customer WHERE leadid = '".$agentLeadId."' and equitas_customer_id in (select distinct(t1.cust_id) from dblink('dbname=dummyequitasvrmdb','select distinct(cust_id),$filterName from limit_table') as t1(cust_id varchar,$filterName varchar) where $filterName $operatorValue '$limDate')";
      // echo $getCustomerDataQuery; exit;
  }

  else if($filterName=='loan_outs' || $filterName=='loan_prinod' || $filterName=='loan_intod')
  {
    $getCustomerDataQuery= "SELECT equitas_customer_id,fname,contact_person,phone1,branch_id,customer_birthday,customer_anniversary,customer_id FROM customer WHERE leadid = '".$agentLeadId."' AND equitas_customer_id in (select distinct(t1.cust_id) from dblink('dbname=dummyequitasvrmdb','select distinct(cust_id),$filterName from loan_table') as t1(cust_id varchar,$filterName varchar) where $filterName::float $operatorValue $filterValue)";

     //echo $getCustomerDataQuery; exit;
  }

  else if($filterName=='od_outs' || $filterName=='od_prinod')
  { 
     $getCustomerDataQuery="SELECT equitas_customer_id,fname,contact_person,phone1,branch_id,customer_birthday,customer_anniversary,customer_id FROM customer WHERE leadid = '".$agentLeadId."' AND equitas_customer_id in (select distinct(t1.cust_id) from dblink('dbname=dummyequitasvrmdb','select distinct(cust_id),$filterName from od_table') as t1(cust_id varchar,$filterName varchar) where $filterName::float $operatorValue $filterValue)"; 
  }

  else if($filterName=='birthday')
  {
    $getCustomerDataQuery=
  "SELECT equitas_customer_id,fname,contact_person,phone1,branch_id,acmcode,customer_id FROM customer WHERE leadid = '".$agentLeadId."' AND extract(month from to_date(customer_birthday,'DD-MM-YYYY'))= extract(month from NOW()) AND extract(day from to_date(customer_birthday,'DD-MM-YYYY')) = extract(day from NOW())";
  }

  else if($filterName=='anniversary')
  {
    $getCustomerDataQuery=
    "SELECT equitas_customer_id,fname,contact_person,phone1,branch_id,acmcode,customer_id FROM customer WHERE leadid = '".$agentLeadId."' AND extract(month from to_date(customer_anniversary,'DD-MM-YYYY'))= extract(month from NOW()) AND extract(day from to_date(customer_anniversary,'DD-MM-YYYY')) = extract(day from NOW())";
  }
    
    //echo $getCustomerDataQuery; exit;
}


debugLog("GET CUSTOMER DATA QUERY: ".$getCustomerDataQuery, LOG_PATH_PGSQL);
$customerData = $crmDBConnection->getResult($getCustomerDataQuery);
debugLog("CUSTOMER DATA FROM QUERY: ".json_encode($customerData), LOG_PATH_PGSQL);

$data= '<table id="myTable" class="table-hover">
          <thead>
            <tr>
              <th>Customer ID</th>
              <th>Customer Name</th>
              <th>Contact Person</th>
              <th>Phone</th>
              <th>Branch</th>
              <th>ACM Code</th>
            </tr>
          </thead><tbody>';
      
          foreach ($customerData as $key => $value)  
          {
            // include('customLibrary/modal.php');
            // $value[4] = strtotime($value[4]);
            // $value[4] = date('Y-m-d', $value[4]);
      
            $data .="<tr>
                        <td>
                            <a style='cursor: pointer;' class=\"userinfo\" data-toggle=\"modal\" data-id=\"#".$value[0]."\" data-target=\"#".$value[0]."\">".$value[0]."</a>
                        </td>
                        <td>".$value[1]."</td>
                        <td>".$value[2]."</td>
                        <td>
                            $value[3]&nbsp<button type=\"button\" id=\"dial".$value[3]."\"  class=\"btn btn-primary btn-large\" value=\"".$value[6]."\" onclick=\"manualDial(this.value, '".$SERVER_BASE_URL."', '".$CAMPAIGN_ID."', ".$USER_CRT_OBJECT_ID.", '".$SESSION_ID."')\"><i class=\"fas fa-tty\"></i></button>
                        </td>
                        <td>".$value[4]."</td>
                        <td>".$value[5]."</td>
                      </tr>";
            ?>
                

            <?php
         
          }
      
    $data.='</tbody></table>';

    echo $data;
?>

<!--Modal Starts Here-->
<div class="modal modal1" id="customerModal">
    
  <div class="panel panel-primary">

    <div class="panel-heading text-center"><strong>CUSTOMER CENTRIC INFORMATION</strong>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
     
    <div class="panel-body">
      
    </div>
  </div>
</div>
<!--Modal Ends Here-->

<script type="text/javascript">
  oTable=$('#myTable').DataTable({
    "bLengthChange": false,
    //"lengthMenu": [3],
    "bPaginate":false,
    "columnDefs": [{
      "className": "dt-center", "targets": "_all" //columnDefs for align text to center
    }],
    // "dom":"lrtip", //to hide default searchbox but search feature is not disabled hence customised searchbox can be made.
    "dom": 'Bfrtip',
    "buttons": [
                    
      {
          extend: 'csv',
          text: 'Export'
      }
    ]
});
    $(".dataTables_filter").hide(); //to hide the default searchbox

    $('#myInputTextField').keyup(function(){  
      oTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
})

// Script To Open Modal with Ajax Response Data Starts
$('.userinfo').click(function(){

   var agentLeadId="<?php echo $agentLeadId?>";
   var ucic = $(this).data('id');
   
   $('.panel-body').html('<div class="text-center"><img src="loader.gif"></div>'); //loader
   $("#customerModal").modal('show');
  
   // AJAX request
   $.ajax({
    url: 'customLibrary/modal.php',
    cache:false,
    type: 'post',
    data: {ucic:ucic,agentLeadId:agentLeadId},
    
    success: function(response){ 
      // Add response in Modal body
      $('.panel-body').html(response);
    }
  });
 }); 

</script>

<style type="text/css">
    .dt-buttons{
        left:39%;
}

.modal1 {
    padding-left: 0px !important;
}
</style>




