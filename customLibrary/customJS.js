    //author: pankajgupta@ameyo.com aka baba @ 9560838554
    function performAJAX(ajaxData, command) {
  var pageURL = document.location.href;
  var MyArray = pageURL.split("index.php?");
  var params = MyArray[1];

  if (command == "downloadVoiceMail") {
    $("#download" + ajaxData).html("Listen again");
    $("#dial" + ajaxData).attr("disabled", false);
    $("#resolved" + ajaxData).attr("disabled", false);
  }

  $.ajax({
    url:
      "customLibrary/callAPI.php?command=" +
      command +
      "&data=" +
      ajaxData +
      "&" +
      params,
    success: function(data) {
      if (command != "downloadVoiceMail") data = JSON.parse(data);
      if (command == "dialCustomer") {
        data.result == "success"
          ? Materialize.toast("Manual Dial Successful", 15000, "rounded")
          : Materialize.toast(
              "Manual Dial Unsuccessful. Reason: " + data.reason,
              1500,
              "rounded"
            );
      } else if (
        command == "LockCustomer" &&
        data.status == "SUCCESS" &&
        data.affectedRow == "YES"
      ) {
        $("#lockRelease" + ajaxData).html("Release");
        $("#download" + ajaxData).attr("disabled", false);
        Materialize.toast("Phone Number Lock Successful", 1500, "rounded");
      } else if (
        command == "LockCustomer" &&
        data.status == "SUCCESS" &&
        data.affectedRow == "NO"
      ) {
        Materialize.toast("Phone Number Lock Unsuccessful", 1500, "rounded");
        Materialize.toast(
          "Phone Number Locked By Another User",
          1500,
          "rounded"
        );
        Materialize.toast("Please Refresh the Page", 1500, "rounded");
      } else if (command == "LockCustomer" && data.status == "FAILURE") {
        Materialize.toast("Phone Number Lock Unsuccessful", 1500, "rounded");
      } else if (
        command == "ReleaseCustomer" &&
        data.status == "SUCCESS" &&
        data.affectedRow == "YES"
      ) {
        $("#lockRelease" + ajaxData).html("Lock");
        $("#download" + ajaxData).html("Listen");
        $("#download" + ajaxData).attr("disabled", true);
        $("#dial" + ajaxData).attr("disabled", true);
        $("#resolved" + ajaxData).attr("disabled", true);
        Materialize.toast("Phone Number Release Successful", 1500, "rounded");
      } else if (
        command == "ReleaseCustomer" &&
        data.status == "SUCCESS" &&
        data.affectedRow == "NO"
      ) {
        Materialize.toast("Phone Number Release Unsuccessful", 1500, "rounded");
      } else if (command == "ReleaseCustomer" && data.status == "FAILURE") {
        Materialize.toast("Phone Number Release Unsuccessful", 1500, "rounded");
      } else if (command == "downloadVoiceMail") {
        Materialize.toast(
          "To download in new tab click Download",
          1500,
          "rounded"
        );
      } else if (command == "resolveCustomer") {
        Materialize.toast("Contact Resolve Successful", 1500, "rounded");
        location.reload();
      } else {
        Materialize.toast("Unknown Error", 1500, "rounded");
      }

      console.log(
        "Module Author: pankajgupta@drishti-soft.com aka baba 9560838554"
      );
      console.log("AJAX Response:", data);

      if (command == "downloadVoiceMail") {
        $("#voicemail").attr("src", data);
        $("#downloadBtn").attr({
          href: data,
          target: "_blank"
        });
      }
    }
  });
}

function refreshLockRelease(voicemail_id) {
  $("#lockRelease" + voicemail_id).html("Release");
  $("#download" + voicemail_id).attr("disabled", false);
}

function create_UUID(){
    var dt = new Date().getTime();
    var uuid = 'manual-dialxxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (dt + Math.random()*16)%16 | 0;
        dt = Math.floor(dt/16);
        return (c=='x' ? r :(r&0x3|0x8)).toString(16);
    });
    return uuid;
}

function manualDial(customerId, serverBaseURL, campaignId, userCRTObjectId, sessionId)
{
  

  var url = 'customLibrary/manualDial.php?customerId=' + customerId + '&uuid=' + create_UUID() + '&campaignId=' + campaignId + '&userCRTObjectId=' + userCRTObjectId + '&sessionId=' + sessionId;
  console.log("MANUAL DIAL CUSTOMER REST API WRAPPER URL: ", url);

  var settings = {
    "async": true,
    "crossDomain": true,
    "url": url,
    "crossDomain": true
  }

  $.ajax(settings).done(function (response) {
    
  });
}


//--------------------For All the Datatables inside the modal cards starts here-----------------------
  $('.myTable1').DataTable({
    "bLengthChange": false,
    "bPaginate": false,
    "searching": false,
  });
//--------------------For All the Datatables inside the modal cards ends here-----------------------


//--------------Function to show or hide popup when clicking on branch button in modal card starts here-----------------------
  function myFunction() 
  {
    
    $(".popuptext").toggleClass("show"); // adding and removing show class
  }
//--------------Function to show or hide popup when clicking on branch button in modal card ends here-----------------------


//--------------Function to show or hide popup3 when clicking on branch button in modal card starts here-----------------------
  function myFunction3() 
  {
    
    $(".popuptext3").toggleClass("show"); // adding and removing show class
  }
//--------------Function to show or hide popup3 when clicking on branch button in modal card ends here-----------------------
function demo(id)
    {
    
     let x = document.getElementById("abc_"+id);

    //x.style.display = "block";
    //alert(x);
    if (x.style.display == "none") 
    {
      //alert(id);
      x.style.display = "block";
    } 
    else 
    {
      //alert("hello");
      x.style.display = "none";
    }
  }