$(document).ready(function(){
   if(typeof(countryId) !== 'undefined' && countryId>0)
   {
      getState(countryId,stateId);
   }
   if(typeof(stateId) !== 'undefined' && stateId>0)
   {
      getCity(stateId,cityId);
   }
});

$("#country").change(function(){
   var countryId = $(this).val();
   getState(countryId,stateId=0);         
});

function getState(countryId,stateId)
{ 
   if(countryId){
      $.ajax({
         url         : getStateUrl,
         type        : "POST",
         data        : {country_id:countryId},              
         success     : function(response){
            var obj = JSON.parse(response);
            if(obj.success==1)
            { 
               var optionHtml='<option value="">Select State</option>';

               for (var i = 0; i<obj.data.length; i++)
               {
                  if(stateId == obj.data[i].state_id)
                  {
                     selected="selected";
                  }
                  else
                  {
                     selected ="";
                  }
                  optionHtml += '<option '+selected+'  value='+obj.data[i].state_id+'>'+obj.data[i].state_name+'</option>';
               }
               $("#state").html(optionHtml);
            }
            else
            {
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
            if(obj.success==1)
            { 
               var optionHtml='<option value="">select City</option>';
               for (var i = 0; i<obj.data.length; i++)
               {
                  if(cityId == obj.data[i].city_id)
                  {
                     selected="selected";
                  }
                  else
                  {
                     selected ="";
                  }

                  optionHtml+='<option '+selected+'  value='+obj.data[i].city_id+'>'+obj.data[i].city_name+'</option>';
               }
               $("#city").html(optionHtml);
            }
            else
            {
               var optionHtml='<option value="">No City Available</option>'
               $("#city").html(optionHtml);
            }
         }
      });
   }
   else
   {
      $("#city").empty();
   }
}

function deleteCustomerDetail(id){
       
  $('#deleteMsg').text('Are you sure to delete customer details?');
  $("#delete_btn").unbind().click(function(){
    $.ajax({
      url     : deleteCustomerDetailUrl,
      type    : "POST",
      data    :  {user_id:id},

      success : function(response){
        var obj = JSON.parse(response);

        if(obj.success==1)  {
          $('#confirmationModal').modal('hide');
          $("#customers_"+id).remove();

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

//$(".alert").alert();
//window.setTimeout(function() { $(".alert").alert('close'); }, 5000);

setTimeout(function(){ $("#success_notification").hide(); },5000);
setTimeout(function(){ $("#error_notification").hide(); },5000);


$(document).ready(function(){
   $('#datepicker').datepicker({
      autoclose: true,
      changeYear: true,
      endDate: '+0d'
   });
});


$("#Add_address").click(function(e){
    e.preventDefault();
    var user_id       =$("#user_id").val();
    var address_type  =$('#address_type').val();
    var name          =$('#name').val();
    var email         =$('#email').val();
    var phone         =$('#Phone').val();
    var locality      =$('#address1').val();
    var complete_add  =$('#address2').val();
    var lat           =$('#lat').val();
    var long          =$('#long').val();

    if(name == ''){
        $('.name').text('Enter your name');
        $('.address2').text('');
        $('.phone').text('');
        $('.email').text('');
    }
    else if(email == ''){
        $('.email').text('Enter your email');
        $('.phone').text('');
        $('.name').text('');
        $('.address2').text('');
    }
    else if(validateEmail(email) == false){
        $('.email').text('Enter valid email');
        $('.phone').text('');
        $('.name').text('');
        $('.address2').text('');
    }
    else if(phone == ''){
        $('.phone').text('Enter your phone');
        $('.email').text('');
        $('.name').text('');
        $('.address2').text('');
    }
    else if(!($.isNumeric(phone))){
        $('.phone').text('Phone must be numeric');
        $('.email').text('');
        $('.name').text('');
        $('.address2').text('');
    }
    else if(complete_add == ''){
        $('.address2').text('Enter your complete address');
        $('.name').text('');
        $('.email').text('');
        $('.phone').text('');
    }
    else{

        $.post(addDiliverAddress,{
          
            user_id:user_id,
            address1:complete_add,
            address_type:address_type,
            customer_name:name,
            email:email,
            contact_no:phone,
            customer_latitude:lat,
            customer_longitude:long,
            locality_id:locality
        })
        .done(function(response){
         
          obj =$.parseJSON(response);
          if(obj.success == '1'){
             location.reload();
              /*$('#addDiliverAddress').modal().hide();*/
          }else{

          }
        });

    }
   
});

function setUserId(user_id){
  $("#user_id").val(user_id);
}
function validateEmail(sEmail) {
    var filter =/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
    if (filter.test(sEmail)) {
        return true;
    }
    else {
      return false;
    }
}