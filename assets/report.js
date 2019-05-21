
var curr    = new Date;
var lastday = new Date(curr.getFullYear(), curr.getMonth() + 6, 0);
$(document).on('change','#report_type',function(){
	 if($(this).val() == "7"){
	 	$(".res_div").hide();
	 	$(".payment_div").show();
	 }else{
	 	$(".payment_div").hide();
	 	$(".res_div").show();
	 }
});

$(document).on('click','.addButtons',function(){
	var opt        =$(this).val();
	var rep_type   =$("#report_type").val();
	var startDate  =$("#startDate").val();
	var endDate    =$("#endDate").val();
	var restaurant =$("#restaurant").val();
	var lastday    = new Date(curr.getFullYear(), curr.getMonth() + 6, 0);
	
	if(opt == 'export'){
		var data ={"type":rep_type,"startDate":startDate,"endDate":endDate,"restaurant":restaurant};
		document.cookie = "export="+JSON.stringify(data)+"; expires="+lastday+"; path=/";
		window.location.replace(getReport+"/"+opt);
	}else{
		var data ={oprion:opt,type:rep_type,startDate:startDate,endDate:endDate,restaurant:restaurant};
		$.post(getReport+"/"+opt,data,function(response){
		
			var obj =$.parseJSON(response);
			var html ="<thead><tr>";
			console.log(obj);
			if(obj.success =="1"){
				$.each(obj.data,function(key,val){
					if(key == 0){
						$.each(val,function(key1,val1){
							html +="<th>"+key1+"</th>";
						});
						html +="</tr></thead>";
					}
				});
				html +="<tbody>";
				$.each(obj.data,function(key,val){
						html +="<tr>";
						$.each(val,function(key1,val1){
							html +="<td>"+val1+"</td>";
						});
						html +="</tr>";
				});
			   html +="</tbody>";
				
			}else{
				html +="<thead><th>No Record found</th></thead>";
			}
			$(".report_table").html(html);
		});
	}
	
})

