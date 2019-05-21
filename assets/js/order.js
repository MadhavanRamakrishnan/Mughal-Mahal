$(document).ready(function(){

  $(document).on("change",".choice_option",function() {

        var is_checked  = $(this).is(":checked");
        var opId    = $(this).attr('op_id');
        var dissId  = $(this).attr('dish_ids');

        if(is_checked==true)
        {
            $.ajax({

              url     : getDishChoiceUrl,
              type    : "POST",
              data    : {dish_id:dissId},
              
              success : function(response){
                
                            var obj = JSON.parse(response);
                            if(obj.success==1){

                                for (var i = 0; i<obj.data.length; i++) {

                                    var arr = obj.data[i].message.split('##');
                                    var optionHtml ='<div class="form-group">';
                                    optionHtml +='<h4>'+obj.data[i].choice_category_name+'</h4>';

                                    if (obj.data[i].is_multipl == 0) {

                                        for (var j = 0; j<arr.length; j++) {

                                            var arr1 = arr[j].split('**');
                                                  
                                            optionHtml +='<div class="radio-inline"><label><input type="radio" name="single_choice_'+opId+'" value="'+arr1[1]+'">'+arr1[0]+'</label></div>';
                                        }
                                      
                                    }
                                    else if(obj.data[i].is_multipl == 1){
                                     
                                        for (var j = 0; j<arr.length; j++) {

                                            var arr1 = arr[j].split('**');
                                                  
                                            optionHtml +='<div class="checkbox-inline"><label><input type="checkbox" name="multi_choice['+(opId-1)+'][]" value="'+arr1[1]+'">'+arr1[0]+'</label></div>';
                                        }
                                       
                                    }

                                    optionHtml +='</div>';
                                    $('#unique'+opId).append(optionHtml);
                                   
                                }
                            }
                            else{
                                   
                            }
                        }
            });
   
        }
        else
        {
            $('#unique'+opId).empty();
        }
  });
  $(document).on("change",".category",function() {
      var categoryId = $(this).val();
      var catId = $(this).attr('cat_id'); 
      getDish(categoryId,dishId=0,catId);
      $('#unique'+catId).empty();
      $('#choice'+catId).empty();
  });

  $(document).on("click",".changeOrder",function()
  {
    var thisTd    =$(this).parent();
    var oid     = $(this).attr("oid");
    var os      = $(this).attr("os");
    $('#statusMsg').text('Are you sure to change the order status?');
    $("#cngOrder").unbind().click(function(){
        
        $.ajax({
            type        :      "POST",
            data        :      {oid:oid,os:os},
            url         :      changeOrderStatus,                
            success     :      function(response)
            {

                var obj = JSON.parse(response);
                if(obj.success==1)
                {
                    $('#cngStatusmodal').modal('hide');
                    thisTd.html(obj.data);
                    $("#success_message").text(obj.message);
                    $("#success_notification").show();
                    var pagLink =link_name;
                    getOrderData(pagLink,getSearchData(''));
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
  });

  $(document).on("click",".changeOrderStatusAndDriver",function() {
    $("#driver").val("");
    var oid       = $(this).attr("oid");
    var os        = $(this).attr("os");
    var thisTd    =$(this).parent();
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
                                thisTd.html(obj.data);
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
            }else{
               $("#driver").html('<option value="0"> --- Select Driver --- </option>');
            }
        }
    });
   
  });
  

  $(document).on("click",".changeDriver",function() {
      var oid = $(this).attr("oid");
      
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
              }
          }
      });
  });


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
            data        :      {oid:oid,did:did},
            url         :      changeDriver,
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
  setTimeout(function(){ $("#error_notification").hide(); },5000);
  setTimeout(function(){ $("#success_notification").hide('slow'); }, 10000);
  setTimeout(function(){ $("#error_message").hide('slow'); }, 10000);
});

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

$('#removeBtn').attr('disabled', 'disabled');
   
   $('#addBtn').click(function () {

       var num = $('.clonedInput').length;
       var newNum = new Number(num + 1);
    
       var newElem = $('#clonedInput' + num).clone().attr('id', 'clonedInput' + newNum);
    
        newElem.find('.category').attr('id', 'category' + newNum).attr('name', 'category[]').attr('cat_id',+ newNum);
        newElem.find('.dish_name').attr('id', 'dish_name' + newNum).attr('name', 'dish_name[]').attr('dis_id',+ newNum);
        newElem.find('.quantity').attr('id', 'quantity' + newNum).attr('name', 'quantity[]');
        newElem.find('.choice').attr('id', 'choice' + newNum).attr('op_id',+ newNum);
        newElem.find('.unique').attr('id', 'unique' + newNum);
        newElem.find('input[type="radio"]').prop('name', 'single_choice_'+newNum);

       $('#clonedInput' + num).after(newElem);

       $('#removeBtn').removeAttr('disabled', 'disabled');
       $('#clonedInput' + newNum + ' .category').val('');   
       $('#clonedInput' + newNum + ' .dish_name').val(''); 
       $('#clonedInput' + newNum + ' .quantity').val('');
       $('#clonedInput' + newNum + ' .choice').empty();
       $('#clonedInput' + newNum + ' .unique').empty();
     
});
   
   
$('#removeBtn').on('click', function () {

    var num = $('.clonedInput').length;

     if (num == 1)
     {
         $('#removeBtn').attr('disabled', 'disabled');


     }else{

     $('.clonedInput').last().remove();
     }

});

function getDish(categoryId,dishId,catId){
 
  if(categoryId){

        $.ajax({

          url        : getDishUrl,
          type       : "POST",
          data       : {category_id:categoryId},
          
         success     : function(response){
                        var obj = JSON.parse(response);

                        if(obj.success==1){ 
                          var optionHtml='<option value="">Select Dish</option>'
                         for (var i = 0; i<obj.data.length; i++){
                        
                            if(dishId == obj.data[i].product_id){
                              selected="selected";
                            }
                            else{
                              selected ="";
                            }

                            optionHtml+='<option '+selected+'  value='+obj.data[i].product_id+'>'+obj.data[i].name+'</option>';
                          }

                           $("#dish_name" +catId).html(optionHtml);
                    
                        }
                        else{
                         var optionHtml='<option value="">No Dish Available</option>'

                         $("#city").html(optionHtml);
                          }
                     }
        });
  }

  else{
      $("#city").empty();
  }
}


$(document).on("change",".dish_name",function() {

    var dishId = $(this).val();
    var disId = $(this).attr('dis_id'); 
   $('#unique'+disId).empty();
    $('#choice'+disId).empty();
   
     $.ajax({

              url     : getDishUrl,
              type    : "POST",
              data    : {dish_id:dishId},
              
              success : function(response){
                
                            var obj = JSON.parse(response);
                            if(obj.success==1){

                                if (obj.data[0].is_option_available == 1){

                                 var optionHtml = '<label class="checkbox-inline"><input class="choice_option" autocomplete="off" type="checkbox" value="1" name="choice_option['+(disId-1)+']" id="choice_option'+disId+'" dish_ids="'+dishId+'" op_id="'+disId+'">Add Dish Choice</label>';

                                  $('#choice'+disId).html(optionHtml);

                                }
                            }
                            else{
                                   
                            }
                        }
            });
    

});


function getDeliveryTimeRemains(date,oid,toDate)
{
  // Set the date we're counting down to
  var countDownDate     = new Date(date).getTime();
  var countDownToDate   = new Date(toDate).getTime();

  // Update the count down every 1 second
  var x = setInterval(function() {

      // Get todays date and time
      var now = new Date().getTime();
      // Find the distance between now an the count down date
      var distance = countDownToDate - now;
      if(isNaN(distance) || distance<=0)
      {
          var hours   = 0;
          var minutes = 0;
          var seconds = 0;
      }
      else
      {
        // Time calculations for days, hours, minutes and seconds
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var hours = hours*60;
        var minutes = (Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)));
        var minutes = minutes + hours;
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      }
      
      // Output the result in an element with id="demo"
      $('.showTime_'+oid+'> b').html(minutes + "m " + seconds + "s ");
      
      // If the count down is over, write some text 
      /*if (distance < 0) {
          clearInterval(x);
          $('.showTime_'+oid+'> b').html("Delivery time exceeded.");
      }*/
  }, 1000);
}

function getPlacedTime(date,oid)
{
  // Set the date we're counting down to
  var countDownToDate     = new Date(date).getTime();

  // Update the count down every 1 second
  var x = setInterval(function() {

      // Get todays date and time
      var now = new Date().getTime();
      var hours   = 0;
      var minutes = 0;
      var seconds = 0;
      // Find the distance between now an the count down date
      var distance = now - countDownToDate;
      if(isNaN(distance) || distance<=0)
      {
          var hours   = 0;
          var minutes = 0;
          var seconds = 0;
      }
      else
      {
        // Time calculations for days, hours, minutes and seconds
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        //var hours = hours*60;
        var minutes = (Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)));
        //var minutes = minutes + hours;
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      }
      
      // Output the result in an element with id="demo"
      $('.showTime_'+oid+'> b').html(hours + "h " + minutes + "m " + seconds + "s ");
      
      // If the count down is over, write some text 
      /*if (distance < 0) {
          clearInterval(x);
          $('.showTime_'+oid+'> b').html("Delivery time exceeded.");
      }*/
  }, 1000);
}


 //for collaps order Dish in order Details
   $(document).on("click",".toggleChoice",function() {
    var pid = $(this).attr("pid");
    $("#order_details_dishes_show_"+pid).toggle("slide");
  });

//for collaps order Dish in customer Details
  $(document).on("click",".showDishes",function() {
    var pid = $(this).attr("pid");
    $("#ord_details_dishes_show_"+pid).toggle("slide");
  });
  $(document).on("click",".showChoices",function() {
    var pid = $(this).attr("pid");
    $("#ord_dishes_choices_show_"+pid).toggle("slide");
  });

function deleteOrder(id)
{

  $(".error_reason").text("");
  $("#delete_btn1").unbind().click(function()
  {
      var reason=$("#discard_reason").val();
      if(reason =="")
      {
          $(".error_reason").text("Please add reason for discarding this order.");
      }
      else
      {
        $.ajax({
            url     : deleteOrderDetailUrl,
            type    : "POST",
            data    :  {order_id:id,reason:reason},
            success : function(response){
                    console.log(response);
                    var obj = JSON.parse(response);

                    if(obj.success==1)  {
                      window.location.href=newOrder;
                    }
                    else
                    {
                      $("#error_reason").text(obj.message);
                    }
            }
        });
      }
  });
}

 $("#replaceOredrButton").click(function()
 {
    var orderId =$("#orderId").val();
    
    $.ajax({

        type:'POST',
        url :replaceOrder,
        data:{orderId:orderId},
        success:function(response)
        {
       
            var obj=$.parseJSON(response);
            if(obj.success =="1")
            {
               window.location.href =newOrder;
            }
            else
            {
              $(".replaceError").text(obj.message);
            }
        }
    });
 });

function getCookie(cname) {
  var pairs   = document.cookie.split(";");
  var cookies = {};
  for (var i=0; i<pairs.length; i++){
    var pair = pairs[i].split("=");
    cookies[(pair[0]+'').trim()] = unescape(pair[1]);
  }
  return cookies;
}


//confirm order   
var oid=0;
$(document).on('click','.confirmOrder',function(){
  $("#confirmOrderId").val($(this).attr('oId'));
});

$(document).on("click","#confirm_order_btn",function(){
      $("#statusMsg").hide();
      var orderId =$("#confirmOrderId").val();
      $.ajax({
        url     : confirmOrderDetailUrl,
        type    : "POST",
        data    :  {order_id:orderId,order_status:"2"},

        success : function(response){
        
          var obj = JSON.parse(response);

          if(obj.success=="1")  {
            
            $('#confirmOrderModal').modal('hide');
            $(".panel-body").find('.change_status').find('span').removeClass('label-primary');
            $(".panel-body").find('.change_status').find('span').addClass(obj.next_status_lbl);
            $(".panel-body").find('.change_status').find('span').text(obj.next_status);
            $(".confirmOrder").remove();
            $("#success_message").text(obj.message);
            $("#success_notification").show();
              setTimeout(function(){ $("#success_notification").hide(); },5000);
          }
          else
          {
            $("#statusMsg").show();
            $("#statusMsg").text(obj.message);
            setTimeout(function(){ $("#error_notification").hide(); },5000);
          }
          $("#statusMsg").text("");
          $('#confirmOrderId').val("");
        }
      });
});

function refunrOrders(oid)
{
    $("#refund_error").text("");
    $("#refundOredrButton").unbind().click(function()
    {
       $.ajax({
        url     : confirmOrderDetailUrl,
        type    : "POST",
        data    : {order_id:oid,order_status:"9"},
        success : function(response){
        
          var obj = JSON.parse(response);

          if(obj.success=="1")  {
            
            $('#confirmationRefund').modal('hide');
            $(".panel-body").find('.change_status').find('span').removeClass('label-primary');
            $(".panel-body").find('.change_status').find('span').addClass(obj.next_status_lbl);
            $(".panel-body").find('.change_status').find('span').text(obj.next_status);
            $("#refundOrdId").remove();
            $("#replaceOrdId").remove();
            $("#success_message").text(obj.message);
            $("#success_notification").show();
              setTimeout(function(){ $("#success_notification").hide(); },5000);
          }
          else
          {
            $("#statusMsg").show();
            $("#statusMsg").text(obj.message);
            setTimeout(function(){ $("#error_notification").hide(); },5000);
          }
        }
      });
    })
}