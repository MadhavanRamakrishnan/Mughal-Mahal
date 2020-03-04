$(document).on("click","#searchDriverOrderBtn",function () {
	setReportData(driverReport,"first");
})

$.each($('.pagination').find("a"),function(key,val){
    $(val).attr('onclick','return false;');
    $(val).addClass('first_link');
});

$(document).on("click",".first_link",function () {
	setReportData($(this).attr('href'),"first");
});

/**
 * [setReportData description]
 * Description:
 * @author: Manisha Kanazariya
 * @CreatedDate:2019-07-23T12:53:27+0530
 */
function setReportData(url,btn)
{
	
	var data={
		"ajaxData":1,
		"driverId":$("#driverList").val(),
		"resID":$("#restaurantList").val(),
		"startDate":$("#startDate").val(),
		"endDate":$("#endDate").val(),
		"paymentType":$("#paymentType").val(),
	}
	
	$.ajax({
		type:"POST",
		url:url,
		data:data,
		success:function(response){
			var obj =$.parseJSON(response);
			// console.log(obj.data.offset++);
			var html ="";
			if(obj.success=="1"){
				console.log(obj);
				$("#totalOrders").text(obj.data.totalOrders);
				$("#totalAmount").text(obj.data.totalAmount);

				$.each(obj.data.repData,function(k,v){
					html +="<tr>";
					html +="<td>"+(obj.data.offset++)+"</td>";
					html +="<td>"+v.order_id+"</td>";
					html +="<td>"+v.first_name+" "+v.last_name+"</td>";
					html +="<td>"+v.total_price+" KD"+"</td>";
					html +="<td>"+v.delivered_time+"</td>";
					html +="<td>"+v.restaurant_name+"</td>";
					html +="<td>"+v.order_type+"</td>";
					html +="<td>"+v.order_status+"</td>";
					html +="<tr>";
				})
				$("#report_table").find("tbody").html(html);
				$(".paginate_button").html("");
				$(".paginate_button:nth-child(2)").html(obj.data.links);
				$.each($('.pagination').find("a"),function(key,val){
				    $(val).attr('onclick','return false;');
				   	$(val).addClass((btn =="first")?'first_link':'second_link');
				});
			}else{
				$("#report_table").find("tbody").html("<tr><td colspan='8' >"+obj.data+"</td></tr");
				$(".paginate_button").html("");
				$("#totalOrders").text("0");
				$("#totalAmount").text(" 0");
			}
		}
	})

}


$(document).on("click","#searchDriverOrderCODBtn",function () {
	$("#paymentType").val("3");
	setReportData(driverReport,"second");
})

$(document).on("click",".second_link",function () {
	setReportData($(this).attr('href'),"second");
});

$(document).on("click",".btn",function () {
	$(".btn").addClass("btn-info");
	$(this).removeClass("btn-info");
	$(this).addClass("btn-success");
});

$(document).on("click","#exportBtn",function () {
	var data={
		"ajaxData":1,
		"driverId":$("#driverList").val(),
		"resID":$("#restaurantList").val(),
		"startDate":$("#startDate").val(),
		"endDate":$("#endDate").val(),
		"paymentType":$("#paymentType").val(),
	}
	var curr    = new Date;
	var lastday = new Date(curr.getFullYear(), curr.getMonth() + 6, 0);
	document.cookie = "exportDriverReport="+JSON.stringify(data)+"; expires="+lastday+"; path=/";
	window.location.replace(exportReportData);
});