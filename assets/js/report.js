$(document).ready(function(){
	setPagiLinkDisable();
});

var curr    = new Date;
var lastday = new Date(curr.getFullYear(), curr.getMonth() + 6, 0);

$(document).on('click','.addButtons',function(){
	var opt        =$(this).val();
	var rep_type   =$("#report_type").val();
	
	if(rep_type >6){
		var paymt_type =$("#payment_type").val();
	}else{
		var paymt_type ="";
	}
	var startDate  =$("#startDate").val();
	var endDate    =$("#endDate").val();
	var restaurant =$("#restaurant").val();
	var lastday    = new Date(curr.getFullYear(), curr.getMonth() + 6, 0);
	$("#btn_type").val(opt);
	
	if(opt == 'export'){
		var data ={"type":rep_type,"startDate":startDate,"endDate":endDate,"restaurant":restaurant,"payment_type":paymt_type};
		document.cookie = "export="+JSON.stringify(data)+"; expires="+lastday+"; path=/";
		if(rep_type == 1 || rep_type == 2 || rep_type == 3 || rep_type == 7 || rep_type == 9) {
			window.location.replace(getReportdata+opt);
		}else{
			window.location.replace(getReport+"/"+opt);
		}
		
	}else{

		if(rep_type == 1 || rep_type == 2 || rep_type == 3 || rep_type == 7 || rep_type == 9) {
			$(".paging_simple_numbers").show();
			$("#datatable").hide();
			$("#report_table").show();
			getReportData(getReportdata);

		}else{
			
			$("#datatable").show();
			$("#report_table").hide();
			$(".paging_simple_numbers").hide();

			var data ={oprion:opt,type:rep_type,startDate:startDate,endDate:endDate,restaurant:restaurant,payment_type:paymt_type};
			$.post(getReport+"/"+opt,data,function(response){
			
				var obj =JSON.parse(response);
				var html ="<thead><tr>";
				$('.type_val').text(obj.type_val);
				if(obj.success =="1"){
					var dataArr =obj.data;
					
					if(rep_type == 4){
						dataArr.sort(SortByHours);
					}
					
					$.each(obj.data,function(key,val){
						$.each(val,function(key1,val1){
							html +="<th>"+key1+"</th>";
						});
						html +="</tr></thead>";
						return false; 
					});

					html +="<tbody>";
					$.each(obj.data,function(key,val){
						html +="<tr>";
						$.each(val,function(key1,val1){
							if(((rep_type >= 4 && rep_type <= 6) || rep_type == 8) && key1 == "TotalSales"){
								html +="<td>"+parseFloat(val1).toFixed(3)+" KD</td>";	
							}else{
								html +="<td>"+val1+"</td>";
							}
						});
						html +="</tr>";
					});
					 if(rep_type >= 4 && rep_type !=7){
						$.each(obj.data,function(key,val){
							html +="<tr>";
							$.each(val,function(key1,val1){
								html +="<th></th>";
							});
							html +="</tr>";
							return false; 
						});
					}
					html +="</tbody>";
					$("#datatable").html(html);
					$("#datatable tr:last").find('th:first').html('<b>Total</b>');
					$("#datatable tr:last").find('th:last').prev().html('<b>'+obj.TotalOd+'</b>');
					$("#datatable tr:last").find('th:last').html('<b>'+parseFloat(obj.TotalSl).toFixed(3)+' KD</b>');
				}else{
					html +="<thead><th>No Record found</th></thead>";
					$("#datatable").html(html);
					
				}
			});
		}
		
	}
	
});

function reloadEmptyTable(){
	$(".paging_simple_numbers").hide();
	$('#basic-datatable_paginate').hide();
	$('#basic-datatable_info').hide();
	$('#basic-datatable_length').hide();
	$('#basic-datatable_filter').hide();
}
function reloadDataTable(obj,rep_type){
	$(".paging_simple_numbers").hide();
	//make a table heading
	var columns =[];

	$.each(obj.data,function(key,val){

		$.each(val,function(key1,val1){
			columns.push({title:key1});
		});
		return false; 
	});
  	//END make a table heading

    //make a table row data
    var tableData =[];
     
     $.each(obj.data,function(key,val){
		var rewdata =[];
		$.each(val,function(key1,val1){
			 rewdata.push(val1);
		});
		tableData.push(rewdata);
	}); 
	//END make a table row data
	
    table = $('#datatable').dataTable();
    table.fnClearTable();
	table.fnDestroy();
	table.empty();
	table3 = $('#datatable').dataTable( {
        columnskey: "value", columns,
        data:tableData,
        order:[[0,"desc"]],
        "retrieve":true,
         "oLanguage": {
	        "sEmptyTable": "No Record found"
	    }
    } );

}

function SortByHours(x,y) {
  return x.Hours - y.Hours; 
}
function SortByYear(x,y) {
  return x.Year - y.Year; 
}



$(document).on('click','.page_link',function(){
     getReportData($(this).attr('href'));
});

function getReportData(aLink){
	var opt        =$("#btn_type").val();
	var rep_type   =$("#report_type").val();
	if(rep_type >6){
		var paymt_type =$("#payment_type").val();
	}else{
		var paymt_type ="";
	}
	var startDate  =$("#startDate").val();
	var endDate    =$("#endDate").val();
	var restaurant =$("#restaurant").val();
	var lastday    = new Date(curr.getFullYear(), curr.getMonth() + 6, 0);

    var data ={oprion:opt,type:rep_type,startDate:startDate,endDate:endDate,restaurant:restaurant,payment_type:paymt_type};
    $.ajax({
        type:"GET",
        url:aLink,
        data:data,
        success:function(response){
			var obj =JSON.parse(response);
			var html ="<thead><tr>";
			var links ="";
		    var link ="<li class='paginate_button previous' aria-controls='datatable-buttons' tabindex='0' id='datatable-buttons_previous'>";
			$('.type_val').text(obj.type_val);
			if(obj.success =="1"){
				var dataArr =obj.data;
				$.each(obj.data,function(key,val){

						$.each(val,function(key1,val1){
							html +="<th>"+key1+"</th>";
						});
						html +="</tr></thead>";
					return false; 
				});
				html +="<tbody>";
				
				$.each(obj.data,function(key,val){
					html +="<tr>";
					$.each(val,function(key1,val1){
						if(key1 == "Amount"){
							html +="<td>"+parseFloat(val1).toFixed(3)+" KD</td>";
						}else{
							html +="<td>"+val1+"</td>";
						}
					});
					html +="</tr>";
				});
			    html +="</tbody>";
				$(".report_table").html(html);
				//set links for pagination
		        $.each(obj.links,function(k,v){

		            links +=link+v+"</li>";
		        });

		        
			}else{
				html +="<thead><th>No Record found</th></thead>";
				$(".report_table").html(html);
			}
			$('.pagination').html(links);
			setPagiLinkDisable();
		}
	});
	
}

function setPagiLinkDisable(){
     var alink =$('.paginate_button').find('a');
     $.each(alink,function(key,val){
        $(val).attr('onclick','return false;');
        $(val).addClass('page_link');
     });
}