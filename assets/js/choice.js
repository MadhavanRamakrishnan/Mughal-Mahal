// function deleteOrderDetail(id){
			 
//   $('#deleteMsg').text('Are you sure to delete Order details?');
//   $("#delete_btn").unbind().click(function(){

//      $.ajax({
//         url     : deleteOrderDetailUrl,
//         type    : "POST",
//         data    :  {order_id:id},

//         success : function(response){
//                     var obj = JSON.parse(response);

//                     if(obj.success==1)  {
//                       $('#confirmationModal').modal('hide');
//                       $("#order_details_"+id).remove();

//                       $("#success_message").text(obj.message);
//                       $("#success_notification").show();
//                     }
//                     else{
//                       $("#error_message").text(obj.message);
//                       $("#flasherror").show();
//                     }
//                   }
//       });
//   });
// }

// $(".clone").on("click", clone);

// function clone(){

// 	var cloneIndex = $(".clonedInput").length;
// 	var cloneData = $("#clonedInput").clone().attr("id", "clonedInput" +  cloneIndex);
// 	$('#clonecontainer').append(cloneData);
// 	cloneIndex++;

// }

// $("#remove").click(function(e) {
//     $(this).closest(".clonedInput").remove();
//     e.preventDefault();
// });

 $('#remove').attr('disabled', 'disabled');
   
   $('#Add').click(function () {

       var num = $('.clonedInput').length;
       //alert(num);
       var newNum = new Number(num + 1);
         
      var newElem = $('#clonedInput' + num).clone().attr('id', 'clonedInput' + newNum);

     // $('#clonecontainer').append(newElem);
    
       newElem.children('.cat_name').attr('id', 'cat_name' + newNum).attr('name', 'cat_name[]');
       newElem.children('.choice_name').attr('id', 'choice_name' + newNum).attr('name', 'choice_name[]');
       newElem.children('.description').attr('id', 'description' + newNum).attr('name', 'description[]');
      newElem.children('.remove').attr('id', 'remove' + newNum).attr('name', 'description[]');
   
       $('#clonedInput' + num).after(newElem);

       $('#remove').removeAttr('disabled', 'disabled');

       $('#clonedInput' + newNum + ' .cat_name').val('');   
       $('#clonedInput' + newNum + ' .choice_name').val(''); 
       $('#clonedInput' + newNum + ' .description').val('');
     
        });
   
   
   $('#remove').on('click', function () {

   		var num = $('.clonedInput').length;
   
       if (num  == 1)
       {

           $('#remove').attr('disabled', 'disabled');
           return true;
       }
       $('.clonedInput').last().remove();
   
   });
   
   
   $('#Add').on('click', function () {
   
       $('.clonedInput').last().add().find("input:text").val("");
   });



