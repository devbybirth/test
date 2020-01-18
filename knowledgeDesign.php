<?php require_once('customLibrary/config.php');
  // ini_set('display_errors', 1);
  // ini_set('display_startup_errors', 1);
  // error_reporting(E_ALL); 
?>

<!DOCTYPE html>
<html>
<head>
  <title>RM HOME</title>
    
  <!--jquery starts-->
    <script type="text/javascript" src="jquery/jquery.min.js"></script>
  <!--jquery end-->

  <!--bootstrap start-->
  <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
  <script src="bootstrap3/js/bootstrap.min.js"></script>
  <!--bootstrap end-->

  <!--datatable start-->
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="DataTables/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="DataTables/css/dataTables.bootstrap.min.css"/>
  <!--datatable end-->

  <!--Links for Datatable Export Button start--->
  <link rel="stylesheet" type="text/css" href="DataTables/Buttons-1.6.1/css/buttons.dataTables.min.css"/>

  <script type="text/javascript" src="DataTables/Buttons-1.6.1/js/dataTables.buttons.min.js"></script>

  <script type="text/javascript" src="DataTables/jszip/dist/jszip.min.js"></script>

  <script type="text/javascript" src="DataTables/Buttons-1.6.1/js/buttons.html5.min.js"></script>
  
  <!--Links for Datatable Export Button Ends--->

  <link rel="stylesheet" href="fontawesome5/css/all.css">
  
  <!--custom js start-->
  <script type="text/javascript" src="customLibrary/customJS.js"></script>
  <!--custom js end-->

</head>
<body>
  <div class="container-fluid">
      
    <div class="col-xs-4">
      <input type="text" id="myInputTextField" class="form-control" placeholder="Search...">
    </div>
    

    <div class="col-xs-2 text-center">
       
      <button
        type="button"
        class="btn btn-refresh btn-icon-large"
        onclick="location.reload(true);"
        title="Refresh">
        <i class="fas fa-retweet fa-lg"></i>
      </button>
           
    </div>

    <div class="col-xs-6">
      <form name="filterForm">
      <div class="input-group">
      

        <select id="filterNameList" name="filterName" class="form-control" style="width:30%" onclick="filterFunction(this.value)">
          <option value="0">Select Filter</option>
          <option value="ins_endate">Insurance Renewal</option>
          <option value="lim_ren">Limit Renewal</option>
          <option value="loan_outs">Loan Outstanding</option>
          <option value="loan_prinod">Loan Principle Overdue</option>
          <option value="loan_intod">Loan Interest Overdue</option>
          <option value="od_outs">OD Outstanding</option>
          <option value="od_prinod">OD Principle Overdue</option>
          <option value="birthday">Today's Birthdays</option>
          <option value="anniversary">Today's Anniversary</option>
        </select>

          
        <select id="operatorList" name="operatorList" class="form-control" style="width:30%">
          <option value="0">Select Operator</option>
          <option value=">">Greater Than</option>
          <option value="<">Less Than</option>
          <option value=">=">Greater Than Equal To</option>
          <option value="<=">Less Than Equal To</option>
          <option value="=">Equal To</option>
        </select>
          
          
        <span id="inputSelection">
          <input type="text" id="filterValue" name="filterValue" class="form-control" style="width: 40%" placeholder="Type Filter Value">
        </span>

        <span class="input-group-btn">
          <button  type="button" class="btn btn-default my-group-button" onclick="listsearch()"><span class="glyphicon glyphicon-search"></span>
          </button>

          <button type="button" class="btn btn-default my-group-button" onclick="exportFilterData()"><span class="glyphicon glyphicon-download-alt"></span>
          </button>
        </span>
      </div>
    </form>
    </div>
  </div>
  <br><br>
  

  <div id="res"></div>
</body>


<script type="text/javascript">

  var user_id="<?php echo USER_ID?>";
  var campaign_id="<?php echo CAMPAIGN_ID?>";
  var server_base_url="<?php echo SERVER_BASE_URL ?>";
  var user_crt_object_id="<?php echo USER_CRT_OBJECT_ID ?>";
  var session_id ="<?php echo SESSION_ID ?>";
   

  $(document).ready(function()
  {   
    
    
    showAll();
    

    function showAll()
    {
      var showall = "showall";
      
      
      $.ajax({
        method:"GET",
        url:"knowledgeBase.php",
        data:{tag:showall,user_id:user_id,campaign_id:campaign_id,server_base_url:server_base_url,user_crt_object_id:user_crt_object_id,session_id:session_id},
        success:function(result){
                 $('#res').html(result);
        }
      })
    }
  });


  
  function filterFunction(value)
  {
    
    if (value == "ins_endate" || value == "lim_ren") 
    {
            
      $("#inputSelection").html("<input type='date' id='filterValue' class='form-control' style='width: 40%'>");

    }

    else if (value == "loan_outs" || value == "loan_prinod" || value == "loan_intod" || value=="od_outs" || value=="od_prinod")
    {
            
      $("#inputSelection").html("<input type='text' id='filterValue' class='form-control' style='width: 40%'>");
    } 

    else if(value == "birthday" || value == "anniversary")
    {
       $("#filterValue").attr("disabled", true);

       $("#operatorList").attr("disabled", true);
    }
  }

  function listsearch()
  {
    // alert("hello");
    var filterName=$('#filterNameList').val();
    var filterValue=$('#filterValue').val();
    var operatorValue=$('#operatorList').val();
    var tag="listsearch";
    //alert(filterValue);
    $.ajax({
      method:"GET",
      url:"knowledgeBase.php",
      data:{
        tag:tag,
        filterName:filterName,
        filterValue:filterValue,
        operatorValue:operatorValue,
        user_id:user_id,
        campaign_id:campaign_id,
        server_base_url:server_base_url,
        user_crt_object_id:user_crt_object_id,
        session_id:session_id
      },
      success:function(result){
               $('#res').html(result);
      }
    })

  }

  function exportFilterData()
  {
    //alert("hello");
    var filterName=$('#filterNameList').val();
    var filterValue=$('#filterValue').val();
    var operatorValue=$('#operatorList').val();
    // var tag="exportFilterData";
    //alert(filterValue);
    window.location.href = 'customLibrary/export.php?tag=exportFilterData&filterName='+filterName+'&operatorValue='+operatorValue+'&filterValue='+filterValue+'&user_id='+user_id+'&campaign_id='+campaign_id+'&server_base_url='+server_base_url+'&user_crt_object_id='+user_crt_object_id+'&session_id='+session_id;

  }
  
  
</script>
</html>
