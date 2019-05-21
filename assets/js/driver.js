$(document).ready(function(){

  if(countryId>0){

    getState(countryId,stateId);        
  }
  if(stateId>0){

    getCity(stateId,cityId);        
  }
});
$("#country").change(function() {

 var countryId = $(this).val();

 getState(countryId,stateId=0);


});

function getState(countryId,stateId){

  if(countryId){

    $.ajax({

      url        : getStateUrl,
      type       : "POST",
      data       : {country_id:countryId},

      success     : function(response){

        var obj = JSON.parse(response);
        if(obj.success==1){ 


          var optionHtml='<option value="">Select State</option>'

          for (var i = 0; i<obj.data.length; i++){

            if(stateId == obj.data[i].state_id){

              selected="selected";
            }
            else{
              selected ="";
            }

            optionHtml+='<option '+selected+'  value='+obj.data[i].state_id+'>'+obj.data[i].state_name+'</option>';
          }
          $("#state").html(optionHtml);
        }
        else{
         var optionHtml='<option value="">No State Available</option>'

         $("#state").html(optionHtml);
       }
     }
   });
  }

  else{
    $("#state").empty();
  }
}
$("#state").change(function() {

 var stateId = $(this).val();

 getCity(stateId,cityId=0);


});

function getCity(stateId,cityId){

  if(stateId){

    $.ajax({

      url        : getCityUrl,
      type       : "POST",
      data       : {state_id:stateId},

      success     : function(response){
        var obj = JSON.parse(response);
        if(obj.success==1){ 

          var optionHtml='<option value="">Select City</option>'
          for (var i = 0; i<obj.data.length; i++){

            if(cityId == obj.data[i].city_id){
              selected="selected";
            }
            else{
              selected ="";
            }

            optionHtml+='<option '+selected+'  value='+obj.data[i].city_id+'>'+obj.data[i].city_name+'</option>';
          }
          $("#city").html(optionHtml);
        }
        else{
         var optionHtml='<option value="">No City Available</option>'

         $("#city").html(optionHtml);
       }
     }
   });
  }

  else{
    $("#city").empty();
  }
}

function deleteDriverDetail(id){

  $('#deleteMsg').text('Are you sure to delete Driver details?');
  $("#delete_btn").unbind().click(function(){

   $.ajax({
    url     : deleteDriverDetailUrl,
    type    : "POST",
    data    :  {user_id:id},

    success : function(response){
      var obj = JSON.parse(response);

      if(obj.success==1)  {
        $('#confirmationModal').modal('hide');
        $("#driver_details_"+id).remove();

        $("#success_message").text(obj.message);
        $("#success_notification").show();
        setTimeout(function(){ $("#success_notification").hide(); },5000);
      }
      else{
        $("#error_message").text(obj.message);
        $("#flasherror").show();
        setTimeout(function(){ $("#error_notification").hide(); },5000);

      }
    }
  });
 });
}
setTimeout(function(){ $("#success_notification").hide(); },5000);
setTimeout(function(){ $("#error_notification").hide(); },5000);


$(document).ready(function(){
  $('#datepicker').datepicker({
    autoclose: true,
    changeYear: true,
    endDate: '+0d'
  });
});

$(document).on("click",".changeOrderStatusAndDriver",function() {
  var oid = $(this).attr("oid");
  var os  = $(this).attr("os");

  $.ajax({
    type        :      "POST",
    data        :      {oid:oid},
    url         :      getDrivers,
    success     :      function(response)
    {
      var obj = JSON.parse(response);
      if(obj.success==1)
      {
        $("#driver").html(obj.drivers);
        $("#hdn_oid").val(oid);

        $("#assign").unbind().click(function(){
          var oid = $("#hdn_oid").val();
          var did = $("#driver").val();

          if(did=='0' || typeof did=='undefined')
          {
            $("#errDriver").show();
            return false;
          }
          else
          {
            $("#errDriver").hide();
            $.ajax({
              type        :      "POST",
              data        :      {oid:oid,did:did,os:os},
              url         :      changeDriverAndOrderStatus,
              success     :      function(response)
              {
                var obj = JSON.parse(response);
                if(obj.success==1)
                {
                  $('#modal-form').modal('hide');
                                //$(".changedOrderStatus").html(html);
                                $(".driverName"+oid).html(obj.driver_name);
                                $(".driverContact"+oid).html(obj.driver_contact);
                                $(".driverNameList_"+oid).html(obj.driver_name);
                                $(".driverContact_"+oid).html(obj.driver_contact);

                                $(".changedOrderStatus_"+oid).html(obj.data);
                                $(".orderStatusList_"+oid).html(obj.listOrderStatus);                    
                                $(".panel_"+oid).addClass(obj.newClass);
                                $(".panel_"+oid).removeClass(obj.oldClass);
                                $(".changeDriver_"+oid).css("display","inline-block");

                                $("#success_message").text(obj.message);
                                $("#success_notification").show();
                                setTimeout(function(){ $("#success_notification").hide(); },5000);
                              }
                              else
                              {
                                $("#error_message").text(obj.message);
                                $("#flasherror").show();
                                setTimeout(function(){ $("#error_notification").hide(); },5000);
                              }
                            }
                          });
          }
        });
      }
    }
  });
    
});
setTimeout(function(){ $("#success_notification").hide(); },5000);
setTimeout(function(){ $("#error_notification").hide(); },5000);

$(document).on("click",".changeVehicle",function() {
  var oid = $(this).attr("oid");
  var rid = $(this).attr("rid");

  $.ajax({
    type        :      "POST",
    data        :      {oid:oid,rid:rid},
    url         :      getVehicle,
    success     :      function(response)
    {
      var obj = JSON.parse(response);
      if(obj.success==1)
      {
        $("#vehicle").html(obj.Vehicle);
        $("#hdn_oid").val(oid);
      }
      else
      {
        $("#vehicle").html(obj.Vehicle);
      }
    }
  });
});

  //$(document).on("click","#assign",function() {

    $("#assign").unbind().click(function(){
      var oid = $("#hdn_oid").val();
      
      var did = $("#vehicle").val();

      $.ajax({
        type        :      "POST",
        data        :      {oid:oid,did:did},
        url         :      changeVehicle,
        success     :      function(response)
        {
          //console.log(response);
          var obj = JSON.parse(response);
          if(obj.success==1)
          {
            $('#drvRating').modal('hide');
            $("#vehicleAssignName_"+oid).text(obj.vehicle_name);
            $("#success_message").text(obj.message);
            $("#success_notification").show();
            setTimeout(function(){ $("#success_notification").hide(); },5000);
          }
          else
          {
            $("#error_message").text(obj.message);
            $("#flasherror").show();
            setTimeout(function(){ $("#error_notification").hide(); },5000);
          }
        }
      });
    });

    setTimeout(function(){ $("#success_notification").hide(); },5000);
    setTimeout(function(){ $("#error_notification").hide(); },5000);

    




