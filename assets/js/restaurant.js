


function deleteRestaurantDetail(id){

  $('#deleteMsg').text('Are you sure to delete Restaurant details?');
  $("#delete_btn").unbind().click(function(){

   $.ajax({
    url     : deleteRestaurantDetailUrl,
    type    : "POST",
    data    :  {restaurant_id:id},

    success : function(response){
      var obj = JSON.parse(response);

      if(obj.success==1)  {
        $('#confirmationModal').modal('hide');
        var table = $('#basic-datatable123').DataTable();
        table.row( $('#res_details_'+id).closest('tr') ).remove().draw();
        $("#res_details_"+id).remove();

        $("#success_message").text(obj.message);
        $("#success_notification").show();
        setTimeout(function(){ $("#success_notification").hide(); },4000);
      }
      else{
        $("#error_message").text(obj.message);
        $("#flasherror").show();
        setTimeout(function(){ $("#error_notification").hide(); },4000);

      }
    }
  });
 });
}
function deleteOwnerDetail(id){

  $('#deleteMsg').text('Are you sure to delete owner details?');
  $("#delete_btn").unbind().click(function(){

   $.ajax({
    url     : deleteOwnerDetailUrl,
    type    : "POST",
    data    :  {user_id:id},

    success : function(response){
      var obj = JSON.parse(response);

      if(obj.success==1)  {
        $('#confirmationModal').modal('hide');
        var table = $('#basic-datatable').DataTable();
        table.row( $('#owner_details_'+id).closest('tr') ).remove().draw();
        $("#owner_details_"+id).remove();
        $("#success_message").text(obj.message);
        $("#success_notification").show();
        setTimeout(function(){ $("#success_notification").hide(); },4000);
      }
      else{
        $("#error_message").text(obj.message);
        $("#flasherror").show();
        setTimeout(function(){ $("#error_notification").hide(); },4000);

      }
      $('#deleteMsg').text('');
    }
  });
 });
}
function deleteManagerDetail(id){

  $('#deleteMsg').text('Are you sure to delete manager details?');
  $("#delete_btn").unbind().click(function(){

   $.ajax({
    url     : deleteManagerDetailUrl,
    type    : "POST",
    data    :  {user_id:id},

    success : function(response){
      var obj = JSON.parse(response);

      if(obj.success==1)  {
        $('#confirmationModal').modal('hide');
        $("#manager_details_"+id).remove();

        $("#success_message").text(obj.message);
        $("#success_notification").show();
        setTimeout(function(){ $("#success_notification").hide(); },4000);
      }
      else{
        $("#error_message").text(obj.message);
        $("#flasherror").show();
        setTimeout(function(){ $("#error_notification").hide(); },4000);

      }
    }
  });
 });
}

//$(".alert").alert();
//window.setTimeout(function() { $(".alert").alert('close'); }, 5000);

setTimeout(function(){ $("#success_notification").hide(); },4000);
setTimeout(function(){ $("#error_notification").hide(); },4000);

$(document).ready(function(){
  $('#datepicker').datepicker({
    autoclose: true,
    changeYear: true,
    endDate: '+0d'
  });
});

function restaurantAvailability(id){
  var clickCheckbox = document.querySelector('.activeSwitch'+id);
  var status;
  if(clickCheckbox.checked==true)
  {
      var status = 1;
      $.ajax({
        url: changeAvailability,
        type: "POST",
        data: {id:id,status:status},
        success: function (response)
        {
          var obj = JSON.parse(response);
          if (obj.success == 1) 
          {
            var massage = "Restaurant is Available.";
            $(".availability").text(massage).show();
            setTimeout(function() { $(".availability").hide('slow'); }, 5000);
          } 

        },
     
      });
    
  }
  else if(clickCheckbox.checked==false)
  {
    
      var status = 0;
      $.ajax({
        url: changeAvailability,
        type: "POST",
        data: {id:id,status:status},
        success: function (response) 
        {
          var obj = JSON.parse(response);
          if (obj.success == 1) 
          {
            var massage = "Restaurant is not Available.";
            $(".availability").text(massage).show();
            setTimeout(function() { $(".availability").hide('slow'); }, 5000);
          } 
        },
    
      }); 
   
  }
  else
  {
    return false;
  }
  setTimeout(function(){ $("#availability").hide('slow'); },5000);
  setTimeout(function(){ $("#error_notification").hide(); },5000);
}

//

function IsRamadhanMonth(res_id){
  var thisEle =$("#switch-button-2");
  if($(thisEle).prop("checked") == true)
  {
    var flag =1;
  }else{
    var flag =0;
  }
  $.post(IsRamadhanMonthdata,{res_id:res_id,is_ramadhan:flag},function(data){
      var obj =$.parseJSON(data);
      if(obj.success!=1){
          if(flag ==1)
          {
            $(thisEle).prop("checked",false);
          }else{
            $(thisEle).prop("checked",true);
          }
      }
  })
}


function setAdditionalDeliveryTime(rId)
{
  var time =$("#additionalDeliveryTime").val();
  $.ajax({
    type:'POST',
    url:setAdditionalDeliveryTimeUrl,
    data:{res_id:rId,time:time},
    success:function(data){
        var obj =$.parseJSON(data);
        if(obj.success==1)  {
          $(".additionalDeliveryTime").show();
          $(".additionalDeliveryTime").text(obj.message);
        }
        else{
          $(".additionalDeliveryTimeerror").show();
          $(".additionalDeliveryTimerror").text(obj.message);
        }
        
    }
  })
}

setTimeout(function(){ $(".additionalDeliveryTime").hide(); },3000);