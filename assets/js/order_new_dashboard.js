/*setInterval(function(){
   getOrderData(newLinkName,getSearchData('new'));
},1000);*/

var orderStatus = ["Pending","Order Placed","Order Confirmed","Cooking","Driver Collected The Order","Driver On The Way","Driver Near To You","Delivered","Disputed"];
orderStatus[13] = "Discarded by Customer";
orderStatus[14] = "Discarded by Admin";

var lastOrderId = '';


setInterval(function(){ 
	$.post(getLatestOrderList,function(data){
		var obj =$.parseJSON(data);

		if(obj.length > 0){
			var Html = '';
			for(var i = 0; i < obj.length; i++ ){
				Html += '<tr>';
				Html += '<td>'+obj[i].order_id+'</td>';
				Html += '<td>'+obj[i].first_name+' '+obj[i].last_name+'</td>';
				Html += '<td>'+obj[i].total_price+'</td>';
				Html += '<td class="center"><span class="label label-success">'+orderStatus[parseInt(obj[i].order_status)]+'</span></td>';
				Html += '</tr>';
			}
		}else{
			var Html = '<tr><td colspan="6"><div align="center">Orders not found !</div></td></tr>';
		}
		$(".customerOrder-detail").html(Html);

		if(lastOrderId != '' && obj.length > 0){
			if(lastOrderId < obj[0].order_id){
				var sound = document.getElementById("notifySound");
				sound.play();
			}
		}
		lastOrderId = obj[0].order_id;
	})
},40000);

$(document).ready(function(){
	$("#tapButton").click();
});