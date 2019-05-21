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

                              var optionHtml='<option value="">select City</option>'
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