/*
* @author       : Purvesh Patel
* Created date  : 11 September 2017 3:30PM
*/

setTimeout(function() { $("#flashmessages").hide('slow'); }, 5000);

//$('#datetimepicker').datetimepicker();


/*
*  This function use for when select guest photo so show this selectd photos
*  @Purvesh Patel
*  April 26 2017
*/
function readURLGuestImages(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#changeimages').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#photo").change(function(){
    readURLGuestImages(this);
});

function allocate(panicId){
	$("#panicId").val(panicId);
}

$("#allocatePanic").submit(function(e){
	if($("#user_name").val() == ""){
		$("#error_msg").text("Please Select User");
		e.preventDefault();
	}
});