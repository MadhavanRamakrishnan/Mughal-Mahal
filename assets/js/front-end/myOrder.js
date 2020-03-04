
function liveTracking()
{
   if(oID != undefined){
   		var dataURL =allMyorderStatus+"/"+oID;
   }else{
   		var dataURL =allMyorderStatus+"/";
   }

	$.post(dataURL,function(data){
        var obj =$.parseJSON(data);
	 	if(obj.success ==1){
	 		$.each(obj.message,function(key,val){
	 			var html ="";
	 			var cl   =".progressbar"+val.order_id;

	 			if(val.status >1){
	 				var cl1="active  complete dot";
	 			}else{
	 				var cl1="active  process dot";
	 			}

	 			if(val.status >1){
	 				var cl2="active";
	 			}else{
	 				var cl2="";
	 			}

	 			if(val.status ==3){
	 				var cl3="active process dot";
	 			}else if(val.status > 3){
	 				var cl3="active  complete dot";
	 			}else {
	 				var cl3="";
	 			}

	 			if(val.status >3){
	 				var cl4="active";
	 			}else{
	 				var cl4="";
	 			}

	 			if(val.status ==5){
	 				var cl5="active process dot";
	 			}else if(val.status > 5){
	 				var cl5="active  complete dot";
	 			}else {
	 				var cl5="";
	 			}
	 			if(val.status >5){
	 				var cl6="active";
	 			}else{
	 				var cl6="";
	 			}
	 			if(val.status >6){
	 				var cl7="active  complete dot";
	 			}else{
	 				var cl7="";
	 			}
	 			html +="<li class='"+cl1+"'><span>"+OrderPlaced+"</span></li>";
	 			html +="<li class='"+cl2+" nodot'></li>";
	 			html +="<li class='"+cl3+"'><span>"+Foodisbeingprepared+"</span></li>";
	 			html +="<li class='"+cl4+" nodot'></li>";
	 			html +="<li class='"+cl5+"'><span>"+Foodoutfordelivery+"</span></li>";
	 			html +="<li class='"+cl6+" nodot'></li>";
	 			html +="<li class='"+cl7+"'><span>"+Delivered+"</span></li>";
	 			$(cl).html(html);
 				if(val.status >=7 ){
		 			$(".order_top_right").addClass( "delivered" );
		 		}else{
		 			$(".order_top_right").removeClass( "delivered" );
		 		}

		 		//dilivery time calculation 
		 		var ordTime ="";
		 		var dt = new Date(val.delivered_time);
		 		month = "January,February,March,April,May,June,July,August,September,October,November,December".split(",")[dt.getMonth()];
		 			function nth(d) {
				      if(d>3 && d<21) return 'th'; // thanks kennebec
				      switch (d % 10) {
				            case 1:  return "st";
				            case 2:  return "nd";
				            case 3:  return "rd";
				            default: return "th";
				        }
				    } 
				 var time = dt.getDate()+nth(dt.getDate()) +", " +month;
		 		if(dt < 0 ){ 
		 			ordTime +=cancelOrder+ "<span>"+time+"</span>";
			    }else if(val.status >=7){
			    	ordTime +=val.status_val+ "<span>"+time+"</span>";
			    }else{

			   		var datetime2 = new Date($("#deliveryTime").val());
					var datetime1 = new Date(val.expected_delivery_time);

					var interval =datetime1 - datetime2;
					var interval =interval/3600000;	
					interval     =interval.toString();
					var hours    =interval.split('.');
					var hour     =hours[0];
					var minutes  =parseFloat((interval-hour)*60).toFixed(0);

					
					if(hour==0){
						ordTime += minutes + "mins<span>"+EstDeliveryTime+"</span>";
					}else{
						ordTime +=hour + "  hrs " + minutes + "mins<span>"+EstDeliveryTime+"</span>";
					}
				 }
				 $(".order_top").find(".trackingTime").html(ordTime);
	 		});
	 		
	 	
	 	}
		
	});
}
setInterval(function(){ 
	  liveTracking(); 
 	}, 1000);

//Repeat Order functionality in my Order
function repeatOrder(orderId)
{
	$("#repeat_ord_btn").unbind().click(function()
	{
		$.post(getRepeatOrder+"/"+orderId,function(data)
		{
			var obj =$.parseJSON(data);
			document.cookie = "dishDetail="+obj.oData+"; expires="+lastday+"; path=/";
			document.cookie = "restaurant_id="+obj.locality_id+"; expires="+lastday+"; path=/";
			$.ajax({
	            type:'POST',
	            url:base_url+'/Home/setLocalityInSession',
	            data:{locality:obj.locality_id,locality_value:obj.locality_name},
	            success:function(response)
	            {
	               $(".autocomlocality").val(obj.locality_name);
	            }
	          }); 

			window.location.href=getOrderSummary;
		})
	})
}
