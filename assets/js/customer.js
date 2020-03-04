$(document).ready(function(){
    if(typeof(countryId) !== 'undefined' && countryId>0)
    {
        getState(countryId,stateId);
    }
    if(typeof(stateId) !== 'undefined' && stateId>0)
    {
        getCity(stateId,cityId);
    }

    // filterData();
    
});

$("#country").change(function(){
    var countryId = $(this).val();
    getState(countryId,stateId=0);         
});

function getState(countryId,stateId)
{ 
    if(countryId){
        $.ajax({
            url         : getStateUrl,
            type        : "POST",
            data        : {country_id:countryId},              
            success     : function(response){
                var obj = JSON.parse(response);
                if(obj.success==1)
                { 
                    var optionHtml='<option value="">Select State</option>';

                    for (var i = 0; i<obj.data.length; i++)
                    {
                        if(stateId == obj.data[i].state_id)
                        {
                            selected="selected";
                        }
                        else
                        {
                            selected ="";
                        }
                        optionHtml += '<option '+selected+'  value='+obj.data[i].state_id+'>'+obj.data[i].state_name+'</option>';
                    }
                    $("#state").html(optionHtml);
                }
                else
                {
                    var optionHtml='<option value="">No State Available</option>'
                    $("#state").html(optionHtml);
                }
            }
        });
    }
    else{
        $("#state").empty();
    }
}

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
                if(obj.success==1)
                { 
                    var optionHtml='<option value="">select City</option>';
                    for (var i = 0; i<obj.data.length; i++)
                    {
                        if(cityId == obj.data[i].city_id)
                        {
                            selected="selected";
                        }
                        else
                        {
                            selected ="";
                        }

                        optionHtml+='<option '+selected+'  value='+obj.data[i].city_id+'>'+obj.data[i].city_name+'</option>';
                    }
                    $("#city").html(optionHtml);
                }
                else
                {
                    var optionHtml='<option value="">No City Available</option>'
                    $("#city").html(optionHtml);
                }
            }
        });
    }
    else
    {
        $("#city").empty();
    }
}

function deleteCustomerDetail(id){

    $('#deleteMsg').text('Are you sure to delete customer details?');
    $("#delete_btn").unbind().click(function(){
        $.ajax({
            url     : deleteCustomerDetailUrl,
            type    : "POST",
            data    :  {user_id:id},

            success : function(response){
                var obj = JSON.parse(response);

                if(obj.success==1)  {
                    $('#confirmationModal').modal('hide');
                    var table = $('#customerTable').DataTable();
                    table.row( $('#customers_'+id).closest('tr') ).remove().draw();
                    $("#customers_"+id).remove();

                    $("#success_message").text(obj.message);
                    $("#success_notification").show();
                    setTimeout(function(){ $("#success_notification").hide(); },5000);
                }
                else{
                    $("#error_message").text(obj.message);
                    $("#flasherror").show();
                    setTimeout(function(){ $("#error_notification").hide(); },5000);

                }
            }
        });
    });
}

//$(".alert").alert();
//window.setTimeout(function() { $(".alert").alert('close'); }, 5000);

setTimeout(function(){ $("#success_notification").hide(); },5000);
setTimeout(function(){ $("#error_notification").hide(); },5000);


// $(document).ready(function(){
//     $('#datepicker').datepicker({
//         autoclose: true,
//         changeYear: true,
//         endDate: '+0d'
//     });
// });


$("#Add_address").click(function(e){
    e.preventDefault();
    var user_id       =$("#user_id").val();
    var address_type  =$('#address_type').val();
    var name          =$('#name').val();
    var email         =$('#email').val();
    var phone         =$('#Phone').val();
    var locality      =$('#address1').val();
    var complete_add  =$('#address2').val();
    var lat           =$('#lat').val();
    var long          =$('#long').val();

    if(name == ''){
        $('.name').text('Enter your name');
        $('.address2').text('');
        $('.phone').text('');
        $('.email').text('');
    }
    else if(email == ''){
        $('.email').text('Enter your email');
        $('.phone').text('');
        $('.name').text('');
        $('.address2').text('');
    }
    else if(validateEmail(email) == false){
        $('.email').text('Enter valid email');
        $('.phone').text('');
        $('.name').text('');
        $('.address2').text('');
    }
    else if(phone == ''){
        $('.phone').text('Enter your phone');
        $('.email').text('');
        $('.name').text('');
        $('.address2').text('');
    }
    else if(!($.isNumeric(phone))){
        $('.phone').text('Phone must be numeric');
        $('.email').text('');
        $('.name').text('');
        $('.address2').text('');
    }
    else if(complete_add == ''){
        $('.address2').text('Enter your complete address');
        $('.name').text('');
        $('.email').text('');
        $('.phone').text('');
    }
    else{

        $.post(addDiliverAddress,{

            user_id:user_id,
            address1:complete_add,
            address_type:address_type,
            customer_name:name,
            email:email,
            contact_no:phone,
            customer_latitude:lat,
            customer_longitude:long,
            locality_id:locality
        })
        .done(function(response){

            obj =$.parseJSON(response);
            if(obj.success == '1'){
                location.reload();
                /*$('#addDiliverAddress').modal().hide();*/
            }else{

            }
        });

    }

});

function setUserId(user_id){
    $("#user_id").val(user_id);
}
function validateEmail(sEmail) {
    var filter =/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
    if (filter.test(sEmail)) {
        return true;
    }
    else {
        return false;
    }
}

$(document).on('keyup','.dataSearch',function(e){

    var value = $(".dataSearch").val();
    if(value == '')
    {
        filterData();
    }
    if (e.which ==  13 )
    {
      filterData();
    }
    
});

function filterData() 
{
    var value = $(".dataSearch").val();
    var d     = new Date();
    document.cookie = "filter="+value+"; expires="+d+"; path=/"; 

    $.ajax({
        url     : searchUrl,
        type    : "POST",
        data    :  {value:value},

        success : function(response)
        {
            var obj = JSON.parse(response);

            if(obj)  
            {
                var htmlData ='';
                htmlData += '<table cellpadding="0" cellspacing="0" border="0" id="customerTable" class="table table-striped table-bordered">'
                htmlData += '<thead><tr>';
                htmlData += '<th>Sr #</th>';
                htmlData += '<th>Customer Name</th>';
                htmlData += '<th>Contact No.</th>';
                htmlData += '<th>Email</th>';
                htmlData += '<th>Order</th>';
                htmlData += '<th>Amount - KD</th>';
                htmlData += '<th>Action</th>';
                htmlData += '</tr></thead>';

                htmlData += '<tbody>';

                for(var i=0; i< obj.customers.length; i++)
                {
                    htmlData += '<tr id="customers_'+obj.customers[i].user_id+'" class="customers_'+obj.customers[i].user_id+'">';
                    htmlData += '<td class="center">'+(i+1)+'</td>';
                    if(obj.customers[i].first_name)
                    {
                        htmlData += '<td><a href="'+getCutomerData+obj.customers[i].user_id+'">'+obj.customers[i].first_name+' '+obj.customers[i].last_name+'</td>';
                    }
                    else
                    {
                        htmlData += '<td> N/A</td>';
                    }
                    if(obj.customers[i].contact_no)
                    {
                        htmlData += '<td class="center">(+965)'+obj.customers[i].contact_no+'</td>';
                    }
                    else
                    {
                        htmlData += '<td></td>';
                    }

                    htmlData += '<td class="text-left">'+obj.customers[i].email+'</td>';
                    var class1='',class2='';            
                    if(obj.customers[i].totalOrders<=10)
                    {
                        class1 = 'bg-info';
                    }
                    else if(obj.customers[i].totalOrders>10 && obj.customers[i].totalOrders<=25)
                    {
                        class1 = 'bg-warning';
                    }
                    else if(obj.customers[i].totalOrders>25 && obj.customers[i].totalOrders<=50)
                    {
                        class1 = 'bg-success';
                    }
                    else if(obj.customers[i].totalOrders>50)
                    {
                        class1 = 'bg-danger';
                    } 

                    htmlData += '<td class="center"><span class="badge noRadius '+class1+'">'+obj.customers[i].totalOrders+'</span></td>';

                    if(obj.customers[i].totalAmount<=50)
                    {
                        class2 = 'bg-info';
                    }
                    else if(obj.customers[i].totalAmount>50 && obj.customers[i].totalAmount<=250)
                    {
                        class2 = 'bg-warning';
                    }
                    else if(obj.customers[i].totalAmount>250 && obj.customers[i].totalAmount<=500)
                    {
                        class2 = 'bg-success';
                    }
                    else if(obj.customers[i].totalAmount>500)
                    {
                        class2 = 'bg-danger';
                    } 

                    if(obj.customers[i].totalAmount > 0){

                        htmlData += '<td class="text-right"><span class="badge noRadius '+class2+'">'+parseFloat(obj.customers[i].totalAmount).toFixed(3)+'</span></td>';
                    }else{
                        htmlData += '<td class="text-right"><span class="badge noRadius '+class2+'">0</span></td>';
                    }

                    if(obj.userdata[0].role_id <3)
                    { 
                        htmlData += '<td class="center"><a href="#" title="Add Address" data-toggle="modal" data-target="#addDiliverAddress" data-backdrop="static" data-keyboard="false" onclick="setUserId('+obj.customers[i].user_id+')"><i class="fa fa-plus-circle"> </i></a> |'; 
                        htmlData += '<a title="Edit Customer" href="'+editCustomer+obj.customers[i].user_id+'"><i class="fa fa-edit"> </i></a> |'; 
                        htmlData += '<a title="Delete" data-toggle="modal" data-target="#confirmationModal" data-backdrop="static" data-keyboard="false" onclick="deleteCustomerDetail('+obj.customers[i].user_id+')" id="delete"><i class="fa fa-trash"> </i></a></td>';
                        htmlData += '</tr>';
                    } 
                }
                htmlData += '</tbody></table>';

                htmlData += '<div class="dataTables_paginate paging_simple_numbers alig-right" id="datatable-buttons_paginate"><ul class="pagination">';

                htmlData += '<li class="paginate_button previous" aria-controls="datatable-buttons" tabindex="0" id="datatable-buttons_previous">';
                if(obj.links[1])
                {
                    for (var j=0; j< obj.links[1].length; j++) 
                    {
                        htmlData += obj.links[1][j];
                    }
                }
                htmlData += '</li></ul></div>';

                $(".customersDiv").html(htmlData);
                $(".dataSearch").val(value);
            }
        }
    });

}


function saveCustomerData(id)
{
    var first_name = $("#first_name").val();
    var last_name  = $("#last_name").val();
    var contact_no = $("#contact_no").val();
    var email      = $("#email").val();
    var dob        = $("#datepicker").val();
    var pass       = $("#pass").val();
    var conPass    = $("#conPass").val();

    var updateData = 1;
    $(".first_name_error").text('');
    $(".last_name_error").text("");
    $(".contact_error").text("");
    $(".email_error").text("");
    $(".dob_error").text("");
    $(".pass_conf_error").text("");

    if(first_name == "")
    {
        hideAddressError();
        $(".first_name_error").text("First name is required");
        updateData = 0;
    }
    
    if(last_name == "")
    {
        hideAddressError();
        $(".last_name_error").text("Last name is required");
        updateData = 0;
    }
    
    if(contact_no == "" || contact_no.length < 10)
    {
        hideAddressError();
        $(".contact_error").text("Contact no is required or valid");
        updateData = 0;
    }
    
    if(email == "")
    {
        hideAddressError();
        $(".email_error").text("Email is required");
        updateData = 0;
    }
    
    /*if(dob == "")
    {
        hideAddressError();
        $(".dob_error").text("Date of birth is required");
        updateData = 0;
    }*/

    if(pass)
    {    
        hideAddressError();
        if(pass != conPass)
        {
            $(".pass_conf_error").text("Password and confirm password not match");
            updateData = 0;
        }
    }

    if(updateData == 1)
    {
        hideAddressError();
        $.ajax({
        url     : updateUrl,
        type    : "POST",
        data    :  {first_name:first_name,last_name:last_name,contact_no:contact_no,email:email,dob:dob,pass:pass,id:id},

        success : function(response){
            var obj = JSON.parse(response);

            if(obj.success==1)  {
                $("#SuccessModalShow").modal('show');
                $("#msg").text(obj.message);
                $("#cuser_id").val(id);
                /*$("#success_notification").text(obj.message);
                $("#success_notification").show();
                setTimeout(function(){ $("#success_notification").hide(); },5000);*/
            }
            else{
                $("#error_notification").text(obj.message);
                $("#error_notification").show();
                setTimeout(function(){ $("#error_notification").hide(); },5000);
            }
        }
        });

    }

}


function editAddressData(address_id, userId) {

    $("#modal_add_address").modal('toggle');
    $(".loading-container").show();

    $.ajax({
        url: getCustomerAddress + "/" + address_id+ "/" +userId,
        type: "POST",
        success: function(data) {
            $(".loading-container").hide();
            var obj = $.parseJSON(data);
            if (obj.success == 1) {
                $("#is_add_address").val(obj.message[0].address_id);
                $("#address_id").val(address_id);
                $("#customer_name").val(obj.message[0].customer_name);
                $("#addemail").val(obj.message[0].email);
                $("#contact_no2").val(obj.message[0].contact_no);
                $("#locality").val(obj.message[0].locality_id);
                $("#lat").val(obj.message[0].customer_latitude);
                $("#long").val(obj.message[0].customer_longitude);
                $("#street").val(obj.message[0].street);
                $("#building").val(obj.message[0].building);
                $("#apartment_no").val(obj.message[0].appartment_no);
                $("#block").val(obj.message[0].block);
                $("#avenue").val(obj.message[0].avenue);
                $("#floor").val(obj.message[0].floor);
                $("#address_line1").val(obj.message[0].address1);
                if (userId != "") {
                    $("#editAdd").val(address_id);
                }
                initMap(obj.message[0].customer_latitude, obj.message[0].customer_longitude);
                if (obj.message[0].address_type == "1") {
                    $("#home").prop("checked", true);
                } else if (obj.message[0].address_type == "2") {
                    $("#office").prop("checked", true);
                } else {
                    $("#other").prop("checked", true);
                }
            } else {}
        }
    })
}

function initMap(lat=29.3518587,lon=47.9836915) {
    var uluru = new google.maps.LatLng(lat, lon);
    var myOptions;
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 17,
        gestureHandling: 'greedy',
        center: uluru,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    var marker = new google.maps.Marker({
        draggable: true,
        scrollwheel: true,
        position: uluru,
        map: map,
        title: "Your location"
    });
    google.maps.event.addListener(marker, 'dragend', function (event) {

        console.log('<p>Marker dropped: Current Lat: ' + event.latLng.lat().toFixed(3) + ' Current Lng: ' + event.latLng.lng().toFixed(3) + '</p>');
        $('#lat').val(event.latLng.lat().toFixed(3));
        $('#long').val(event.latLng.lng().toFixed(3));

    });
}

function hideAddressError()
{
    $('.add_error').val();
    $('.data_error').val();
}

function addaddress(userId) 
{
    var user_id      = userId;
    var address_type = $('input[name=address_type]:checked').val();
    var name         = $('#customer_name').val();
    var email        = $('#addemail').val();
    var phone        = $('#contact_no2').val();
    var locality     = $('#locality').val();
    var editAdd      = $('#editAdd').val();
    var locality_val = $("#locality option:selected").text();
    var street       = $('#street').val();
    var building     = $('#building').val();
    var appartmentNo = $('#apartment_no').val();
    var block        = $('#block').val();
    var avenue       = $('#avenue').val();
    var floor        = $('#floor').val();
    var complete_add = $('#address_line1').val();
    var lat          = $('#lat').val();
    var long         = $('#long').val();
    var address_id   = $("#address_id").val();

    var profile = "";
    if (name == '') {
        hideAddressError();
        $('.name_add_error').show();
    } else if (email == '') {
        hideAddressError();
        $('.email_add_error').show();
        $('.email_add_error').text("Enter Email");
    } else if (validateEmail(email) == false) {
        hideAddressError();
        $('.email_add_error').show();
        $('.email_add_error').text("Enter Valid email");
    } else if (phone == '') {
        hideAddressError();
        $('.phone_add_error').show();
        $('.phone_add_error').text("Enter Phone number");
    }else if (!($.isNumeric(phone))) {
        hideAddressError();
        $('.phone_add_error').show();
        $('.phone_add_error').text("Phone number should be numeric");
    } else if (phone.length < 8) {
        hideAddressError();
        $('.phone_add_error').show();
        $('.phone_add_error').text("Phone number should be greater than 8 digits");
    } else if (locality == '' || locality == 0 || locality == null) {
        hideAddressError();
        $('.locality_error').show();
        $('.locality_error').text("Please select locality");
    } else if (street == '') {
        hideAddressError();
        $('.streetReq').show();
        $('.streetReq').text("Enter Street detail");
    }
    else if (building == '') {
        hideAddressError();
        $('.buildingReq').show();
        $('.buildingReq').text("Enter Building detail");

    }else if (appartmentNo == '') {
        hideAddressError();
        $('.appartmentReq').show();
        $('.appartmentReq').text("Enter Apartment detail");

    }else if (block == '') {
        hideAddressError();
        $('.blockReq').show();
        $('.blockReq').text("Enter Block detail");

    }else if (floor == '') {
        hideAddressError();
        $('.floorReq').show();
        $('.floorReq').text("Enter Floor detail");

    } else {

        $('.name_add_error').hide();
        $('.email_add_error').hide();
        $('.phone_add_error').hide();
        $('.streetReq').hide();
        $('.buildingReq').hide();
        $('.appartmentReq').hide();
        $('.blockReq').hide();
        $('.floorReq').hide();

        var data = {
            user_id: user_id,
            address1: complete_add,
            address_type: address_type,
            customer_name: name,
            email: email,
            contact_no: phone,
            customer_latitude: lat,
            customer_longitude: long,
            locality_id: locality,
            address_id: address_id,
            street      :street,
            building    :building,
            appartmentNo:appartmentNo,
            block       :block,
            avenue      :avenue,
            floor       :floor
        }

        $(".loading-container").show();
        $.post(addDiliverAddress, data).done(function(response) {

            $(".loading-container").hide();
            let obj = $.parseJSON(response);

            $("#modal_add_address").modal('toggle');
            if(obj.success==1)  {
                $("#SuccessModalShow").modal('show');
                $("#cuser_id").val(user_id);
            }
            else{
                $("#error_notification").text(obj.message);
                $("#error_notification").show();
                setTimeout(function(){ $("#error_notification").hide(); },5000);
            }
            
        });
    }
}

$('#SuccessModalShow').on('hidden.bs.modal', function () {
    var user_id = $("#cuser_id").val();
    window.location.href = redirectUrl+'/'+user_id;
});

function closeModal()
{
    var user_id = $("#cuser_id").val();
  window.location.href = redirectUrl+'/'+user_id;  
}