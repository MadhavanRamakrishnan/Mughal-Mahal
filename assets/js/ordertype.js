$(document).ready(function(){

	$("#type_name").keypress(function(e){
		var str = $(this).val().trim().length;
		
		if(e.which == 13){
			if(str > 0) {
				let name = $('#type_name').val();
				$('#type_name').val('');

				$.ajax({
					url : addOrderType,
					type : 'POST',
					data : {
						type_name : name
					},
					success : function(response){

						let obj = JSON.parse(response);

						if(obj.success == "1"){
							$("#modal-title").text('Success');
							$("#statusMsg").text('Order Type added');
							$("#cngStatusmodal").modal();

							let html = '<div class="col-md-3 cat-items box_'+obj.data+'">';
	            				html += '<a href="javascript:void(0)" class="remove-icon" onclick="deleteTypeId('+obj.data+');">';
	            				html += '<i class="fa fa-trash-o" aria-hidden="true"></i></a>'+name+'</div>';

	            			$(".cat-box").append(html);

						}else{
							$("#modal-title").text('Error');
							$("#statusMsg").text(obj.message);
							$("#cngStatusmodal").modal();
						}
					},
					error : function(error){
						console.log(error);
					}
				})
			}
			$("#modal-title").text('Error');
			$("#statusMsg").text('Order Type Not Valid');
			$("#cngStatusmodal").modal();
		}
	});
});

function deleteTypeId(id){

	$("#delete_id").val(id);
	$("#deleteModal").modal();
}

$("#cngOrder").click(function(){

	let id = $("#delete_id").val();
	$("#deleteModal").modal('toggle');
	$.ajax({
		url : deleteOrderType,
		type: "POST",
		data: {
			id : id
		},
		success: function(response){
			let obj = JSON.parse(response);

			if(obj.success == "1"){
				$("#modal-title").text('Success');
				$("#statusMsg").text('Order Type deleted');
				$("#cngStatusmodal").modal();

				$(".box_"+id).remove();
			}else{
				$("#modal-title").text('Error');
				$("#statusMsg").text('Something went wrong');
				$("#cngStatusmodal").modal();
			}
		},
		error : function(error){
			console.log(error);
		}
	});
});