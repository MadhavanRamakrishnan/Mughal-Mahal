$(".alert").alert();
window.setTimeout(function() { $(".alert").alert('close'); }, 4000);

$(document).ready(function(){
    if(cuisineId>0){
      getCategory(cuisineId,categoryId);        
    }
});

$("#cuisine_id").change(function() {

    var cuisineId = $(this).val();
    getCategory(cuisineId,categoryId=0);
 });

function getCategory(cuisineId,categoryId){
 
  if(cuisineId){

        $.ajax({

              url        : getCategoryUrl,
              type       : "POST",
              data       : {cuisine_id:cuisineId},
              
             success     : function(response){

                            var obj = JSON.parse(response);
                            if(obj.success==1){ 

                              var optionHtml='<option value="">Select Dish Category</option>'

                             for (var i = 0; i<obj.data.length; i++){
                                if(categoryId == obj.data[i].category_id){
                                  selected="selected";
                                }
                                else{
                                  selected ="";
                                }
                                optionHtml+='<option '+selected+'  value='+obj.data[i].category_id+'>'+obj.data[i].category_name+'</option>';
                              }
                              $("#category_id").html(optionHtml);
                            }
                            else{
                             var optionHtml='<option value="">No Dish Category Available</option>'

                             $("#category_id").html(optionHtml);
                              }
                         }
            });
  }

  else{
      $("#category_id").empty();
  }
}
function deleteDishDetail(id){
       
  $('#deleteMsg').text('Are you sure to delete dish details?');
  $("#delete_btn").unbind().click(function(){

     $.ajax({
        url     : deleteDishDetailUrl,
        type    : "POST",
        data    :  {product_id:id},

        success : function(response){
                    var obj = JSON.parse(response);

                    if(obj.success==1)  {
                      $('#confirmationModal').modal('hide');
                      $("#dish_details_"+id).remove();

                      $("#success_message").text(obj.message);
                      $("#success_notification").show();
                    }
                    else{
                      $("#error_message").text(obj.message);
                      $("#flasherror").show();
                    }
                  }
      });
  });

  
}

$(document).on('change','.availibility',function(event, state){
  var id = $(this).attr('id');

  var status = "";
  if(this.checked)
  {
    status=1;
  }
  else
  {
    status=0;
  }

  $.ajax({
    url: dishStatusUpdate,
    type: "POST",
    data: {
      product_id: id,
      is_active: status
    },
    success: function(response){

      var obj = JSON.parse(response);

      if(obj.success){
        console.log('Status Update');
      }else{
        console.log('Someting went wrong');
      }
    },
    error: function(jqXHR, textStatus, errorThrown){
      console.log(textStatus, errorThrown);
    }
  });
})