$(document).ready(function () {

    $(".chosen-select1").chosen();
    $(".chosen-container").css('width', '20%');
    $('.chosen-select1-deselect').chosen({
        allow_single_deselect: true
    });

    $(".chosen-select2").chosen();
    $(".chosen-container").css('width', '50%');
    $('.chosen-select2-deselect').chosen({
        allow_single_deselect: true
    });

    $(".chosen-select3").chosen({
        include_group_label_in_selected: true
    });
    $(".chosen-container").css('width', '50%');
    $('.chosen-select3-deselect').chosen({
        allow_single_deselect: true
    });

    $(".chosen-select4").chosen();
    $(".chosen-container").css('width', '50%');
    $('.chosen-select4-deselect').chosen({
        allow_single_deselect: true
    });

    var role = $("#loginUserRole").val();


    if (userData.role_id == "2") {
        $('#restaurantId').prop('disabled', true).trigger("chosen:updated");

        let restaurantSelectedId = $('#restaurantId').val();
        getRestaurantDishes(restaurantSelectedId);
    }

    let d = new Date();
    let month = d.getMonth() + 1;
    let day = d.getDate();
    let year = d.getFullYear();
    let hours = d.getHours();
    let minutes = d.getMinutes();

    if (minutes < 10)
        minutesString = "0" + minutes;
    else
        minutesString = minutes;


    $('input[name="orderDateName"]').datetimepicker({
        maxDate: moment(),
        format: 'DD-MM-YYYY HH:mm',
    });
    $('#datetimepicker').val(day + "-" + month + "-" + year + " " + hours + ":" + minutesString);

    // initMap();

    $(document).on("change", ".choice_option", function () {

        var is_checked = $(this).is(":checked");
        var opId = $(this).attr('op_id');
        var dissId = $(this).attr('dish_ids');

        if (is_checked == true) {
            $.ajax({

                url: getDishChoiceUrl,
                type: "POST",
                data: {
                    dish_id: dissId
                },

                success: function (response) {

                    var obj = JSON.parse(response);
                    if (obj.success == 1) {

                        for (var i = 0; i < obj.data.length; i++) {

                            var arr = obj.data[i].message.split('##');
                            var optionHtml = '<div class="form-group">';
                            optionHtml += '<h4>' + obj.data[i].choice_category_name + '</h4>';

                            if (obj.data[i].is_multipl == 0) {

                                for (var j = 0; j < arr.length; j++) {

                                    var arr1 = arr[j].split('**');

                                    optionHtml += '<div class="radio-inline"><label><input type="radio" name="single_choice_' + opId + '" value="' + arr1[1] + '">' + arr1[0] + '</label></div>';
                                }

                            } else if (obj.data[i].is_multipl == 1) {

                                for (var j = 0; j < arr.length; j++) {

                                    var arr1 = arr[j].split('**');

                                    optionHtml += '<div class="checkbox-inline"><label><input type="checkbox" name="multi_choice[' + (opId - 1) + '][]" value="' + arr1[1] + '">' + arr1[0] + '</label></div>';
                                }

                            }

                            optionHtml += '</div>';
                            $('#unique' + opId).append(optionHtml);

                        }
                    } else {

                    }
                }
            });

        } else {
            $('#unique' + opId).empty();
        }
    });
    $(document).on("change", ".category", function () {
        var categoryId = $(this).val();
        var catId = $(this).attr('cat_id');
        getDish(categoryId, dishId = 0, catId);
        $('#unique' + catId).empty();
        $('#choice' + catId).empty();
    });

    /*$(document).on("click",".changeOrder",function()
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
    });*/

    $(document).on("click", ".changeOrderStatusAndDriver", function () {
        $("#driver").val("");
        var oid = $(this).attr("oid");
        var os = $(this).attr("os");
        var thisTd = $(this).parent();
        $.ajax({
            type: "POST",
            data: {
                oid: oid
            },
            url: getDrivers,
            success: function (response) {
                var obj = JSON.parse(response);
                if (obj.success == 1) {
                    $("#driver").html(obj.drivers);
                    $("#hdn_oid").val(oid);

                    $("#assign").unbind().click(function () {
                        var oid = $("#hdn_oid").val();
                        var did = $("#driver").val();

                        if (did == '0' || typeof did == 'undefined') {
                            $("#errDriver").show();
                            return false;
                        } else {
                            $("#errDriver").hide();
                            $.ajax({
                                type: "POST",
                                data: {
                                    oid: oid,
                                    did: did,
                                    os: os
                                },
                                url: changeDriverAndOrderStatus,
                                success: function (response) {
                                    var obj = JSON.parse(response);
                                    if (obj.success == 1) {
                                        $('#modal-form').modal('hide');
                                        thisTd.html(obj.data);
                                        $("#success_message").text(obj.message);
                                        $("#success_notification").show();
                                        setTimeout(function () {
                                            $("#success_notification").hide();
                                        }, 5000);
                                    } else {
                                        $("#error_message").text(obj.message);
                                        $("#flasherror").show();
                                        setTimeout(function () {
                                            $("#error_notification").hide();
                                        }, 5000);
                                    }
                                }
                            });
                        }
                    });
                } else {
                    $("#driver").html('<option value="0"> --- Select Driver --- </option>');
                }
            }
        });

    });


    $(document).on("click", ".changeDriver", function () {
        var oid = $(this).attr("oid");

        $.ajax({
            type: "POST",
            data: {
                oid: oid
            },
            url: getDrivers,
            success: function (response) {
                var obj = JSON.parse(response);
                if (obj.success == 1) {
                    $("#driver").html(obj.drivers);
                    $("#hdn_oid").val(oid);
                }
            }
        });
    });


    $("#assign").unbind().click(function () {
        var oid = $("#hdn_oid").val();
        var did = $("#driver").val();

        if (did == '0' || typeof did == 'undefined') {
            $("#errDriver").show();
            return false;
        } else {
            $("#errDriver").hide();
            $.ajax({
                type: "POST",
                data: {
                    oid: oid,
                    did: did
                },
                url: changeDriver,
                success: function (response) {
                    var obj = JSON.parse(response);
                    if (obj.success == 1) {
                        $('#modal-form').modal('hide');
                        //$(".changedOrderStatus").html(html);
                        $(".driverName" + oid).html(obj.driver_name);
                        $(".driverContact" + oid).html(obj.driver_contact);
                        $(".driverNameList_" + oid).html(obj.driver_name);
                        $(".driverContact_" + oid).html(obj.driver_contact);
                        $("#success_message").text(obj.message);
                        $("#success_notification").show();
                        setTimeout(function () {
                            $("#success_notification").hide();
                        }, 5000);
                    } else {
                        $("#error_message").text(obj.message);
                        $("#flasherror").show();
                        setTimeout(function () {
                            $("#error_notification").hide();
                        }, 5000);
                    }
                }
            });
        }
    });
    setTimeout(function () {
        $("#error_notification").hide();
    }, 5000);
    setTimeout(function () {
        $("#success_notification").hide('slow');
    }, 10000);
    setTimeout(function () {
        $("#error_message").hide('slow');
    }, 10000);

});

$("#state").change(function () {
    var stateId = $(this).val();
    getCity(stateId, cityId = 0);
});

function getCity(stateId, cityId) {
    if (stateId) {
        $.ajax({
            url: getCityUrl,
            type: "POST",
            data: {
                state_id: stateId
            },
            success: function (response) {
                var obj = JSON.parse(response);
                if (obj.success == 1) {

                    var optionHtml = '<option value="">Select City</option>'
                    for (var i = 0; i < obj.data.length; i++) {

                        if (cityId == obj.data[i].city_id) {
                            selected = "selected";
                        } else {
                            selected = "";
                        }

                        optionHtml += '<option ' + selected + '  value=' + obj.data[i].city_id + '>' + obj.data[i].city_name + '</option>';
                    }
                    $("#city").html(optionHtml);
                } else {
                    var optionHtml = '<option value="">No City Available</option>'

                    $("#city").html(optionHtml);
                }
            }
        });
    } else {
        $("#city").empty();
    }
}

$('#removeBtn').attr('disabled', 'disabled');

$('#addBtn').click(function () {

    var num = $('.clonedInput').length;
    var newNum = new Number(num + 1);

    var newElem = $('#clonedInput' + num).clone().attr('id', 'clonedInput' + newNum);

    newElem.find('.category').attr('id', 'category' + newNum).attr('name', 'category[]').attr('cat_id', +newNum);
    newElem.find('.dish_name').attr('id', 'dish_name' + newNum).attr('name', 'dish_name[]').attr('dis_id', +newNum);
    newElem.find('.quantity').attr('id', 'quantity' + newNum).attr('name', 'quantity[]');
    newElem.find('.choice').attr('id', 'choice' + newNum).attr('op_id', +newNum);
    newElem.find('.unique').attr('id', 'unique' + newNum);
    newElem.find('input[type="radio"]').prop('name', 'single_choice_' + newNum);

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

    if (num == 1) {
        $('#removeBtn').attr('disabled', 'disabled');


    } else {

        $('.clonedInput').last().remove();
    }

});

function getDish(categoryId, dishId, catId) {

    if (categoryId) {

        $.ajax({

            url: getDishUrl,
            type: "POST",
            data: {
                category_id: categoryId
            },

            success: function (response) {
                var obj = JSON.parse(response);

                if (obj.success == 1) {
                    var optionHtml = '<option value="">Select Dish</option>'
                    for (var i = 0; i < obj.data.length; i++) {

                        if (dishId == obj.data[i].product_id) {
                            selected = "selected";
                        } else {
                            selected = "";
                        }

                        optionHtml += '<option ' + selected + '  value=' + obj.data[i].product_id + '>' + obj.data[i].name + '</option>';
                    }

                    $("#dish_name" + catId).html(optionHtml);

                } else {
                    var optionHtml = '<option value="">No Dish Available</option>'

                    $("#city").html(optionHtml);
                }
            }
        });
    } else {
        $("#city").empty();
    }
}


$(document).on("change", ".dish_name", function () {

    var dishId = $(this).val();
    var disId = $(this).attr('dis_id');
    $('#unique' + disId).empty();
    $('#choice' + disId).empty();

    $.ajax({

        url: getDishUrl,
        type: "POST",
        data: {
            dish_id: dishId
        },

        success: function (response) {

            var obj = JSON.parse(response);
            if (obj.success == 1) {

                if (obj.data[0].is_option_available == 1) {

                    var optionHtml = '<label class="checkbox-inline"><input class="choice_option" autocomplete="off" type="checkbox" value="1" name="choice_option[' + (disId - 1) + ']" id="choice_option' + disId + '" dish_ids="' + dishId + '" op_id="' + disId + '">Add Dish Choice</label>';

                    $('#choice' + disId).html(optionHtml);

                }
            } else {

            }
        }
    });


});


function getDeliveryTimeRemains(date, oid, toDate) {
    // Set the date we're counting down to
    var countDownDate = new Date(date).getTime();
    var countDownToDate = new Date(toDate).getTime();

    // Update the count down every 1 second
    var x = setInterval(function () {

        // Get todays date and time
        var now = new Date().getTime();
        // Find the distance between now an the count down date
        var distance = countDownToDate - now;
        if (isNaN(distance) || distance <= 0) {
            var hours = 0;
            var minutes = 0;
            var seconds = 0;
        } else {
            // Time calculations for days, hours, minutes and seconds
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var hours = hours * 60;
            var minutes = (Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)));
            var minutes = minutes + hours;
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        }

        // Output the result in an element with id="demo"
        $('.showTime_' + oid + '> b').html(minutes + "m " + seconds + "s ");

        // If the count down is over, write some text 
        /*if (distance < 0) {
            clearInterval(x);
            $('.showTime_'+oid+'> b').html("Delivery time exceeded.");
        }*/
    }, 1000);
}

function getPlacedTime(date, oid) {
    // Set the date we're counting down to
    var countDownToDate = new Date(date).getTime();

    // Update the count down every 1 second
    var x = setInterval(function () {

        // Get todays date and time
        var now = new Date().getTime();
        var hours = 0;
        var minutes = 0;
        var seconds = 0;
        // Find the distance between now an the count down date
        var distance = now - countDownToDate;
        if (isNaN(distance) || distance <= 0) {
            var hours = 0;
            var minutes = 0;
            var seconds = 0;
        } else {
            // Time calculations for days, hours, minutes and seconds
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            //var hours = hours*60;
            var minutes = (Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)));
            //var minutes = minutes + hours;
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        }

        // Output the result in an element with id="demo"
        $('.showTime_' + oid + '> b').html(hours + "h " + minutes + "m " + seconds + "s ");

        // If the count down is over, write some text 
        /*if (distance < 0) {
            clearInterval(x);
            $('.showTime_'+oid+'> b').html("Delivery time exceeded.");
        }*/
    }, 1000);
}


//for collaps order Dish in order Details
$(document).on("click", ".toggleChoice", function () {
    var pid = $(this).attr("pid");
    $("#order_details_dishes_show_" + pid).toggle("slow");
    $("#order_details_dishes_show_"+pid).css("display","contents");
});

//for collaps order Dish in customer Details
$(document).on("click", ".showDishes", function () {
    var pid = $(this).attr("pid");
    $("#ord_details_dishes_show_" + pid).toggle("slow");
    $("#ord_details_dishes_show_"+pid).css("display","contents");

});
$(document).on("click", ".showChoices", function () {
    var pid = $(this).attr("pid");
    $("#ord_dishes_choices_show_" + pid).toggle("slow");
    $("#ord_dishes_choices_show_"+pid).css("display","contents");

});

function deleteOrder(id) {

    $(".error_reason").text("");
    $("#delete_btn1").unbind().click(function () {
        var reason = $("#discard_reason").val();
        if (reason == "") {
            $(".error_reason").text("Please add reason for discarding this order.");
        } else {
            $.ajax({
                url: deleteOrderDetailUrl,
                type: "POST",
                data: {
                    order_id: id,
                    reason: reason
                },
                success: function (response) {
                    console.log(response);
                    var obj = JSON.parse(response);

                    if (obj.success == 1) {
                        window.location.href = newOrder;
                    } else {
                        $("#error_reason").text(obj.message);
                    }
                }
            });
        }
    });
}

$("#replaceOredrButton").click(function () {
    var orderId = $("#orderId").val();

    $.ajax({

        type: 'POST',
        url: replaceOrder,
        data: {
            orderId: orderId
        },
        success: function (response) {

            var obj = $.parseJSON(response);
            if (obj.success == "1") {
                window.location.href = newOrder;
            } else {
                $(".replaceError").text(obj.message);
            }
        }
    });
});

function getCookie(cname) {
    var pairs = document.cookie.split(";");
    var cookies = {};
    for (var i = 0; i < pairs.length; i++) {
        var pair = pairs[i].split("=");
        cookies[(pair[0] + '').trim()] = unescape(pair[1]);
    }
    return cookies;
}


//confirm order   
var oid = 0;
$(document).on('click', '.confirmOrder', function () {
    $("#confirmOrderId").val($(this).attr('oId'));
});

$(document).on("click", "#confirm_order_btn", function () {
    $("#statusMsg").hide();
    var orderId = $("#confirmOrderId").val();
    $.ajax({
        url: confirmOrderDetailUrl,
        type: "POST",
        data: {
            order_id: orderId,
            order_status: "2"
        },

        success: function (response) {

            var obj = JSON.parse(response);

            if (obj.success == "1") {

                $('#confirmOrderModal').modal('hide');
                $(".panel-body").find('.change_status').find('span').removeClass('label-primary');
                $(".panel-body").find('.change_status').find('span').addClass(obj.next_status_lbl);
                $(".panel-body").find('.change_status').find('span').text(obj.next_status);
                $(".confirmOrder").remove();
                $("#success_message").text(obj.message);
                $("#success_notification").show();
                setTimeout(function () {
                    $("#success_notification").hide();
                }, 5000);
            } else {
                $("#statusMsg").show();
                $("#statusMsg").text(obj.message);
                setTimeout(function () {
                    $("#error_notification").hide();
                }, 5000);
            }
            $("#statusMsg").text("");
            $('#confirmOrderId').val("");
        }
    });
});

function refunrOrders(oid) {
    $("#refund_error").text("");
    $("#refundOredrButton").unbind().click(function () {
        $.ajax({
            url: confirmOrderDetailUrl,
            type: "POST",
            data: {
                order_id: oid,
                order_status: "9"
            },
            success: function (response) {

                var obj = JSON.parse(response);

                if (obj.success == "1") {

                    $('#confirmationRefund').modal('hide');
                    $(".panel-body").find('.change_status').find('span').removeClass('label-primary');
                    $(".panel-body").find('.change_status').find('span').addClass(obj.next_status_lbl);
                    $(".panel-body").find('.change_status').find('span').text(obj.next_status);
                    $("#refundOrdId").remove();
                    $("#replaceOrdId").remove();
                    $("#success_message").text(obj.message);
                    $("#success_notification").show();
                    setTimeout(function () {
                        $("#success_notification").hide();
                    }, 5000);
                } else {
                    $("#statusMsg").show();
                    $("#statusMsg").text(obj.message);
                    setTimeout(function () {
                        $("#error_notification").hide();
                    }, 5000);
                }
            }
        });
    })
}

$('input[type=radio][name=orderRadio]').change(function () {

    console.log(this.value);
    if (this.value == "present") {
        let d = new Date();
        let month = d.getMonth() + 1;
        let day = d.getDate();
        let year = d.getFullYear();
        let hours = d.getHours();
        let minutes = d.getMinutes();

        if (minutes < 10)
            minutesString = "0" + minutes;
        else
            minutesString = minutes;

        $('#datetimepicker').val(day + "-" + month + "-" + year + " " + hours + ":" + minutesString);

        $('#datetimepicker').prop("disabled", true);

        // $('#orderStatusId').hide();
        // $('#orderStatusId').val(0);
        var key = 1;
        $('#orderStatusId option[value=' + key + ']').prop('selected', true);
        $('#orderStatusId').trigger("chosen:updated");
        $('#orderStatusId').prop('disabled', true).trigger("chosen:updated");
    } else {
        $('#datetimepicker').prop("disabled", false);

        var key = '';
        var option = '<option value="">Select Order Status</option>';
        option += '<option  value="7">Completed</option>';
        option += '<option  value="8">Cancelled</option>';

        $("#orderStatusId").html(option);

        $('#orderStatusId option[value=' + key + ']').prop('selected', true);
        $('#orderStatusId').prop('disabled', false).trigger("chosen:updated");
        // $('#orderStatusId').show();
        $('#orderStatusId').val('');
    }

})

/*Code From Hardik Ghadshi*/
$("#phone_number").on('keypress focusout', function (event) {

    let phoneNumber = $("#phone_number").val();

    if (event.keyCode == 13 || event.keyCode == 9 || event.type == "focusout") {

        if (phoneNumber.length >= 6) {

            $(".loading-container").show();
            $(".nextBtn").prop('disabled', true);

            $.ajax({

                url: checkUser,
                type: "post",
                data: {
                    phone_no: phoneNumber
                },
                success: function (response) {

                    $(".loading-container").hide();
                    $(".nextBtn").prop('disabled', false);

                    let obj = JSON.parse(response);

                    console.log(obj);
                    if (obj.success == "1") {

                        userId = obj.data.customer_data.user_id;

                        $("#first_name").val(obj.data.customer_data.first_name);
                        $("#last_name").val(obj.data.customer_data.last_name);
                        $("#email").val(obj.data.customer_data.email);

                        $(".addressDiv").show();

                        let addresses = obj.data.addressDetail;
                        console.log(addresses);
                        if (addresses.length > 0) {

                            let addressHTML = '';
                            for (let i = 0; i < addresses.length; i++) {

                                let typeAddr = '';
                                if (addresses[i].address_type == "1") {
                                    typeAddr = "Home";
                                } else if (addresses[i].address_type == "2") {
                                    typeAddr = "Office";
                                } else if (addresses[i].address_type == "3") {
                                    //typeAddr = "Other";
                                    typeAddr = addresses[i].other_address.charAt(0).toUpperCase() + addresses[i].other_address.slice(1);
                                }

                                addressHTML += '<div class="box-address" onclick="addressSelect(' + addresses[i].address_id + ',' + addresses[i].locality_id + ')">';
                                addressHTML += '<b>' + typeAddr + '</b>';
                                //addressHTML += '<p>' + addresses[i].other_address + '</p>';
                                addressHTML += '<p>' + addresses[i].street + '</p>';
                                addressHTML += '<p>' + addresses[i].building + '</p>';
                                addressHTML += '<p>' + addresses[i].appartment_no + '</p>';
                                addressHTML += '<p>' + addresses[i].block + '</p>';

                                if (addresses[i].avenue) {
                                    addressHTML += '<p>' + addresses[i].avenue + '</p>';
                                }

                                addressHTML += '<p>' + addresses[i].floor + '</p>';

                                if (addresses[i].address1) {
                                    addressHTML += '<p>' + addresses[i].address1 + '</p>';
                                }
                                addressHTML += '<br><p>' + ((addresses[i].name) ? addresses[i].name : '') + '</p>';
                                addressHTML += '<a onclick="editAddressData(' + addresses[i].address_id + ')">Edit</a>&nbsp;<a>|</a>&nbsp;<a onclick="deleteAddressData(' + addresses[i].address_id + ')">Delete</a>';
                                addressHTML += '</div>';
                            }

                            addressHTML += '<div class="box-addressadd"><i class="fa fa-plus" aria-hidden="true"></i><p>Add Address</p></div>';

                            $(".address_box_div").html(addressHTML);
                        }

                    } else {

                        $(".loading-container").hide();
                        $(".nextBtn").prop('disabled', false);

                        $("#first_name").val('');
                        $("#last_name").val('');
                        $("#email").val('');

                        let addressHTML = '<div class="box-addressadd"><i class="fa fa-plus" aria-hidden="true"></i><p>Add Address</p></div>';
                        $(".address_box_div").html(addressHTML);
                        $(".addressDiv").hide();
                    }
                },
                error: function (error) {
                    console.log(error);
                    $(".loading-container").hide();
                    $(".nextBtn").prop('disabled', false);
                }
            });

            $(".phone_error").hide();
        } else {
            $(".phone_error").text('Enter valid phone number');
            $(".phone_error").show();
        }
    }

});

$(document).on('click', ".box-address", function () {
    $(".box-address").removeClass("active");
    $(this).addClass("active");

    setRestaurantFromLocality(localityId);
    getRestaurantDishes($("#restaurantId").val());
});

function addressSelect(id, locality) {
    console.log(id);
    // By Vasu
    getDishesData(locality);
    addressId = id;
    localityId = locality;
};

$(document).on('click', ".box-addressadd", function () {

    document.getElementById('addressadd').reset();
    document.getElementById('address_id').value = '';
    document.getElementById('addemail').value = $("#email").val();
    $("#modal_add_address").modal('toggle');
    initMap();
});

function initMap(lat = 29.3518587, lon = 47.9836915) {
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

$(".backBtn").click(function () {

    let stepId = $(".stepId").val();
    stepId--;

    if (stepId == "1") {

        /*Next button will be visible when it's on 1st page*/
        $(".nextBtn").show();
        $("#step-1").show();
        $("#step-2").hide();
        $("#step-3").hide();
        $(".stepId").val(stepId);

        $("#step1").removeClass("btn-primary");
        $("#step1").addClass("btn-default");

        addressId = "";
    } else if (stepId == "2") {

        if (userData.role_id == "1") {
            $('#restaurantId').val('');
        }

        addressId = "";
        localityId = 0;
        $(".box-address").removeClass("active");
        $("#step2").removeClass("btn-primary");
        $("#step2").addClass("btn-default");

        $("#step-1").hide();
        $("#step-2").show();
        $("#step-3").hide();
        $(".stepId").val(stepId);
    } else if (stepId == "3") {
        $("#step-1").hide();
        $("#step-2").hide();
        $("#step-3").show();
        $(".stepId").val(stepId);

        $("#step3").removeClass("btn-primary");
        $("#step3").addClass("btn-default");
    }
});

$(".nextBtn").click(function () {

    let stepId = $(".stepId").val();

    if (stepId == "1") {
        if ($("#orderTypeId").val() == '') {
            $(".order_type_error").show();
            return;
        } else {
            $(".order_type_error").hide();
        }

        if ($("#phone_number").val() == '') {
            $(".phone_error").text('This field is required');
            $(".phone_error").show();
            return;
        } else if ($("#phone_number").val().length < 6) {
            $(".phone_error").text('Enter valid phone number');
            $(".phone_error").show();
            return;
        } else {
            $(".phone_error").hide();
        }

    } else if (stepId == "2") {
        if ($("#first_name").val() == '') {
            $(".first_name_error").show();
            return;
        } else {
            $(".first_name_error").hide();
        }

        if ($("#last_name").val() == '') {
            $(".last_name_error").show();
            return;
        } else {
            $(".last_name_error").hide();
        }

        /*if ($("#email").val() == '') {
            $(".email_error").show();
            return;
        } else {
            $(".email_error").hide();
        }*/

        if (addressId == '') {
            $(".address_error").show();
            return;
        } else {
            $(".address_error").hide();
        }

    }

    stepId++;
    $(".stepId").val(stepId);

    /*If customer is not registered then add address(inderectly save button) will be shown*/
    if (stepId == "2" && $("#first_name").val() == "") {
        $(".nextBtn").hide();
        $(".saveBtn").show();
    } else {
        $(".nextBtn").show();
        $(".saveBtn").hide();
    }


    if (stepId == "1") {
        $("#step-1").show();
        $("#step-2").hide();
        $("#step-3").hide();
        $("#step1").removeClass("btn-default");
        $("#step1").addClass("btn-primary");

    } else if (stepId == "2") {

        $(".box-address").removeClass("active");

        $("#step-1").hide();
        $("#step-2").show();
        $("#step-3").hide();

        $("#step1").removeClass("btn-default");
        $("#step1").addClass("btn-primary");

    } else if (stepId == "3") {
        $("#step-1").hide();
        $("#step-2").hide();
        $("#step-3").show();

        $("#step2").removeClass("btn-default");
        $("#step2").addClass("btn-primary");
        $("#step1").removeClass("btn-default");
        $("#step1").addClass("btn-primary");
    }
});

$(".saveBtn").click(function () {

    if ($("#first_name").val() == '') {
        $(".first_name_error").show();
        return;
    } else {
        $(".first_name_error").hide();
    }

    if ($("#last_name").val() == '') {
        $(".last_name_error").show();
        return;
    } else {
        $(".last_name_error").hide();
    }

    /*if ($("#email").val() == '') {
        $(".email_error").show();
        return;
    } else {
        $(".email_error").hide();
    }*/

    $(".loading-container").show();

    $.ajax({

        url: addCustomer,
        type: "POST",
        data: {
            customer_type: $("#orderTypeId").val(),
            first_name: $("#first_name").val(),
            last_name: $("#last_name").val(),
            email: $("#email").val(),
            contact_no: $("#phone_number").val()
        },
        success: function (response) {

            $(".loading-container").hide();
            let obj = JSON.parse(response);

            console.log(obj);
            if (obj.success == "1") {

                userId = obj.data;

                $(".nextBtn").show();
                $(".saveBtn").hide();
                $(".addressDiv").show();
            } else {
                alert("Please try again later.");
            }
        },
        error: function (error) {
            $(".loading-container").hide();
            console.log(error);
        }
    });
});

function addaddress() {
    var user_id = userId;
    var address_type = $('input[name=address_type]:checked').val();
    var name = $('#customer_name').val();
    var email = $('#addemail').val();
    var phone = $('#contact_no').val();
    var locality = $('#locality').val();
    var editAdd = $('#editAdd').val();
    var locality_val = $("#locality option:selected").text();
    var street = $('#street').val();
    var other_address = $('#other_address').val();
    var building = $('#building').val();
    var appartmentNo = $('#apartment_no').val();
    var block = $('#block').val();
    var avenue = $('#avenue').val();
    var floor = $('#floor').val();
    var complete_add = $('#address_line1').val();
    var lat = $('#lat').val();
    var long = $('#long').val();
    var address_id = $("#address_id").val();

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
    } else if (!($.isNumeric(phone))) {
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
    } else if (address_type == 3 && other_address == '') {
        hideAddressError();
        $('.otherAddressReq').show();
        $('.otherAddressReq').text("Address tag should not be empty");
    }else if (street == '') {
        hideAddressError();
        $('.streetReq').show();
        $('.streetReq').text("Enter Street detail");
    } else if (building == '') {
        hideAddressError();
        $('.buildingReq').show();
        $('.buildingReq').text("Enter Building detail");

    } else if (appartmentNo == '') {
        hideAddressError();
        $('.appartmentReq').show();
        $('.appartmentReq').text("Enter Apartment detail");

    } else if (block == '') {
        hideAddressError();
        $('.blockReq').show();
        $('.blockReq').text("Enter Block detail");

    } else if (floor == '') {
        hideAddressError();
        $('.floorReq').show();
        $('.floorReq').text("Enter Floor detail");

    } else {

        $('.name_add_error').hide();
        $('.email_add_error').hide();
        $('.phone_add_error').hide();
        $('.streetReq').hide();
        $('.otherAddressReq').hide();
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
            other_address:other_address,
            street: street,
            building: building,
            appartmentNo: appartmentNo,
            block: block,
            avenue: avenue,
            floor: floor
        }

        $(".loading-container").show();
        $.post(addDiliverAddress, data).done(function (response) {

            $(".loading-container").hide();

            let obj = $.parseJSON(response);

            console.log(obj);

            if (obj.success == '1') {

                checkUserData();
                $("#modal_add_address").modal('toggle');
                addressId = "";
                localityId = 0;
            }
        });
    }
}

function hideAddressError() {
    $('.add_error').hide();
}

function validateEmail(sEmail) {
    var filter = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
    if (filter.test(sEmail)) {
        return true;
    } else {
        return false;
    }
}

function editAddressData(address_id, oSummary = "") {
    $(".otherAddressReq").text('');
    $("#modal_add_address").modal('toggle');
    $(".loading-container").show();

    $.ajax({
        url: getCustomerAddress + "/" + address_id + "/" + userId,
        type: "POST",
        success: function (data) {
            $(".loading-container").hide();
            var obj = $.parseJSON(data);
            if (obj.success == 1) {
                $("#is_add_address").val(obj.message[0].address_id);
                $("#address_id").val(address_id);
                $("#customer_name").val(obj.message[0].customer_name);
                $("#addemail").val(obj.message[0].email);
                $("#contact_no").val(obj.message[0].contact_no);
                $("#locality").val(obj.message[0].locality_id);
                $("#lat").val(obj.message[0].customer_latitude);
                $("#long").val(obj.message[0].customer_longitude);
                $("#other_address").val(obj.message[0].other_address);
                $("#street").val(obj.message[0].street);
                $("#building").val(obj.message[0].building);
                $("#apartment_no").val(obj.message[0].appartment_no);
                $("#block").val(obj.message[0].block);
                $("#avenue").val(obj.message[0].avenue);
                $("#floor").val(obj.message[0].floor);
                $("#address_line1").val(obj.message[0].address1);
                if (oSummary != "") {
                    $("#editAdd").val(address_id);
                }
                initMap(obj.message[0].customer_latitude, obj.message[0].customer_longitude);
                if (obj.message[0].address_type == "1") {
                    $("#home").prop("checked", true);
                    $("#other_address").css('display', 'none');
                } else if (obj.message[0].address_type == "2") {
                    $("#office").prop("checked", true);
                    $("#other_address").css('display', 'none');
                } else {
                    $("#other").prop("checked", true);
                    $("#other_address").css('display', 'block');
                }
            } else {}
        }
    })
}

function deleteAddressData(addId) {

    $("#modal_remove_address").modal("toggle");

    $("#remove_address").unbind().click(function () {

        $("#modal_remove_address").modal("toggle");
        $.ajax({
            url: deleteCustomerAddress + "/" + addId,
            type: "POST",
            beforeSend: function () {
                $(".loading-container").show();
            },
            success: function (data) {
                var obj = $.parseJSON(data);
                if (obj.success == 1) {

                    addressId = "";
                    localityId = 0;
                    if (userData.role_id == "1") {
                        $('#restaurantId').val('');
                    }


                    checkUserData();
                } else {
                    $(this).parent().append("<span state='color:red;'>" + obj.message + "</span>");
                }
            },
            complete: function () {
                $(".loading-container").hide();
            }

        })
    });

}

$("#cancel_delete_address_btn").click(function () {
    $("#modal_remove_address").modal("toggle");
});

$("#cancel_delete_dish_btn").click(function () {
    $("#modal_remove_dish").modal("toggle");
});

function checkUserData() {
    let phoneNumber = $("#phone_number").val();

    $(".loading-container").show();

    $.ajax({

        url: checkUser,
        type: "post",
        data: {
            phone_no: phoneNumber
        },
        success: function (response) {

            $(".loading-container").hide();

            let obj = JSON.parse(response);

            console.log(obj);
            if (obj.success == "1") {

                userId = obj.data.customer_data.user_id;

                $("#first_name").val(obj.data.customer_data.first_name);
                $("#last_name").val(obj.data.customer_data.last_name);
                $("#email").val(obj.data.customer_data.email);

                $(".addressDiv").show();

                let addresses = obj.data.addressDetail;
                console.log(addresses);
                if (addresses.length > 0) {

                    let addressHTML = '';
                    for (let i = 0; i < addresses.length; i++) {

                        let typeAddr = '';
                        if (addresses[i].address_type == "1") {
                            typeAddr = "Home";
                        } else if (addresses[i].address_type == "2") {
                            typeAddr = "Office";
                        } else if (addresses[i].address_type == "3") {
                            //typeAddr = "Other";
                            typeAddr = addresses[i].other_address.charAt(0).toUpperCase() + addresses[i].other_address.slice(1);
                        }

                        addressHTML += '<div class="box-address" onclick="addressSelect(' + addresses[i].address_id + ',' + addresses[i].locality_id + ')">';
                        addressHTML += '<b>' + typeAddr + '</b>';
                        //addressHTML += '<p>' + addresses[i].other_address + '</p>';
                        addressHTML += '<p>' + addresses[i].street + '</p>';
                        addressHTML += '<p>' + addresses[i].building + '</p>';
                        addressHTML += '<p>' + addresses[i].appartment_no + '</p>';
                        addressHTML += '<p>' + addresses[i].block + '</p>';

                        if (addresses[i].avenue) {
                            addressHTML += '<p>' + addresses[i].avenue + '</p>';
                        }

                        addressHTML += '<p>' + addresses[i].floor + '</p>';

                        if (addresses[i].address1) {
                            addressHTML += '<p>' + addresses[i].address1 + '</p>';
                        }
                        addressHTML += '<br><p>' + ((addresses[i].name) ? addresses[i].name : '') + '</p>';
                        addressHTML += '<a onclick="editAddressData(' + addresses[i].address_id + ')">Edit</a>&nbsp;<a>|</a>&nbsp;<a onclick="deleteAddressData(' + addresses[i].address_id + ')">Delete</a>';
                        addressHTML += '</div>';
                    }

                    addressHTML += '<div class="box-addressadd"><i class="fa fa-plus" aria-hidden="true"></i><p>Add Address</p></div>';

                    $(".address_box_div").html(addressHTML);
                } else {
                    var addressHTML = '<div class="box-addressadd"><i class="fa fa-plus" aria-hidden="true"></i><p>Add Address</p></div>';

                    $(".address_box_div").html(addressHTML)
                }
            }
        },
        error: function (error) {
            $(".loading-container").hide();
            console.log(error);
        }
    });
}

/*$("#restaurantId").change(function(){

    let restaurantId = $("#restaurantId").val();
    if(restaurantId){
        getRestaurantDishes(restaurantId);
    }
});*/


/**
 * [getDishesData description]
 * @author Vasu Ratanpara
 * @Created Date     2019-12-18T03:51:02+0530
 * @param   {[type]} localityId               [description]
 * @return  {[type]}                          [description]
 */
function getDishesData(localityId) {

    $(".loading-container").show();

    $.ajax({

        url: getDishesDataUrl,
        type: "POST",
        data: {
            locality: localityId
        },
        success: function (response) {

            $(".loading-container").hide();

            let obj = JSON.parse(response);

            // <div class="card">
            //   <div class="card-header" id="headingOne">
            //     <h2 class="mb-0">
            //       <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
            //         Category Name
            //       </button>
            //     </h2>
            //   </div>
            //   <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
            //     <div class="card-body">
            //       Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
            //     </div>
            //   </div>
            // </div>

            let options = "";

            options += '<div class="panel-group" id="accordion">';
        if(typeof obj['category'] !== 'undefined' && obj['category'].length > 0 ){

            for (let i = 0; i < obj['category'].length; i++) {

                options += `<div class="panel panel-default">
                                <div class="heading" id="heading${obj['category'][i].category_id}">
                                    <h2 class="mb-0 panel-title">
                                        <button class="btn btn-link collapsed " type="button" data-toggle="collapse" data-parent="#accordion" href="#collapse${obj['category'][i].category_id}">
                                            ${obj['category'][i].category_name}
                                        </button>
                                        <i class="fa fa-angle-down"></i>
                                    </h2>
                                </div>
                                <div id="collapse${obj['category'][i].category_id}" class="panel-collapse collapse">
                                    <div class="panel-body">`;

                options += `<ul class='category_collapse_ctn'>`;
                for (key in obj['dishes']) {
                    if (key == obj['category'][i].category_id) {
                        for (var j = 0; j < obj['dishes'][key].length; j++) {
                            console.log(obj['dishes'][key][j].product_en_name)
                            options += `<li onclick="getDishDetail(${obj['dishes'][key][j].product_id})">${obj['dishes'][key][j].product_en_name} <a class="btn btn_dishes"><i class="fa fa-plus" aria-hidden="true"></i></a></li>`;
                        }
                    }

                }
                options += `</ul>`;
                options += `</div></div></div>`;

            }
        }else{
            $(".box-address").removeClass("active");
                        addressId = "";
                        localityId = 0;
                        alert("Please select address from different locality");
        }

            options += "</div>";

            $('#categoryAccordion').html(options);
        },
        error: function (error) {

            console.log(error);
            $(".loading-container").hide();
        }
    });
}

function getRestaurantDishes(restaurantId) {

    $(".loading-container").show();

    $.ajax({

        url: getRestaurantDishesAJAX,
        type: "POST",
        data: {
            restaurant_id: restaurantId
        },
        success: function (response) {

            $(".loading-container").hide();

            let obj = JSON.parse(response);
            console.log(obj);

            let options = '<option value="">Select dish</option>';
            for (let i = 0; i < obj.length; i++) {
                options += "<option value='" + obj[i].fk_dish_id + "'>" + obj[i].product_en_name + "</option>";
            }

            $('.chosen-select2').html(options);
            $('.chosen-select2').trigger("chosen:updated");
        },
        error: function (error) {
            $(".loading-container").hide();
        }
    });
}

$("#dishId").change(function () {

    let dishId = $("#dishId").val();
    if (dishId) {
        getDishDetail(dishId);
    }
});

function getDishDetail(dishId) {

    $.ajax({
        async: false,
        url: getDishDetailAJAX,
        type: "POST",
        data: {
            dish_id: dishId,
            locality_id: localityId
        },
        success: function (response) {

            $(".loading-container").hide();

            let obj = JSON.parse(response);
            console.log(obj);

            if (!obj.dish_details.product_id) {
                $("#dishNotFound").modal("show");
                $("#dishId").val('');
            } else {
                $("#modal_add_dish").modal("toggle");

                var rightPopupHTML = '';

                rightPopupHTML += '<p class="item_name">Chicken Wings (15 Pcs)</p>';
                rightPopupHTML += '<div class="numbers-row-inner">';
                rightPopupHTML += '<input type="text" name="numId" id="numId" class="numDishId" value="1">';
                rightPopupHTML += '</div>';
                $(".openPopupAdd").html(rightPopupHTML);
                stapper(".numbers-row-inner");

                // $("#ch_dish_name").text(obj.dish_details.product_en_name);
                $(".item_name").text(obj.dish_details.product_en_name);
                $(".item_price").text(parseFloat(obj.dish_details.price).toFixed(3) + " KD");
                $('#addOnPrice').val('');
                $('#dish_id').val(obj.dish_details.product_id);
                $(".numDishId").val('1');
                $("#instruction").val('');
                $(".numDishId").attr("dishPrice", "0.000");
                $(".numDishId").attr("predishcount", "1");

                var formHtml = '';
                formHtml += '<input type="hidden" name="dish_id" id="dish_id" value="' + obj.dish_details.product_id + '"">';

                for (var i = 1; i <= Object.keys(obj.dish_details.choiceCategory).length; i++) {

                    if (obj.dish_details.choiceCategory[i].is_multiple == 0) {

                        formHtml += "<h3><i class='alerticon" + i + " fa fa-info-circle'></i><i class='successicon" + i + " fa fa-check-circle'></i> " + obj.dish_details.choiceCategory[i].category_name + " <span>required</span></h3>";

                        var catId = obj.dish_details.choiceCategory[i].category_id;
                        for (var j = 0; j < obj.dish_details.choiceCategory[i].choice['length']; j++) {
                            formHtml += "<input type='radio' name='category" + i + "' id='category" + i + "' class='radioCategory' value='" + obj.dish_details.choiceCategory[i].choice[j].choice_id + "' price=" + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + " onclick='priceChange(" + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + ")'>" + obj.dish_details.choiceCategory[i].choice[j].choice_name + "<span>";
                            if (parseFloat(obj.dish_details.choiceCategory[i].choice[j].price) != 0) {
                                formHtml += parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + " KD</span><br>";
                            } else {
                                formHtml += ' </span><br>';
                            }
                        }
                    } else {

                        formHtml += "<h3><i class='alerticon" + i + " fa fa-info-circle'></i><i class='successicon" + i + " fa fa-check-circle'></i> " + obj.dish_details.choiceCategory[i].category_name + " </h3>";

                        for (var j = 0; j < obj.dish_details.choiceCategory[i].choice['length']; j++) {
                            formHtml += "<label class='checkbox_custom'>" + obj.dish_details.choiceCategory[i].choice[j].choice_name + "<input type='checkbox' id='check_" + i + "' class='chociecheck' name='check_" + i + "' onchange='priceChange(" + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + ")' price=" + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + " value='" + obj.dish_details.choiceCategory[i].choice[j].choice_id + "'><span>" + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + "KD</span><span class='checkmark'></span></label>";
                        }
                    }
                }
                formHtml += '<input type="hidden" name="product_price" id="product_price" value="' + obj.dish_details.price + '"">';
                formHtml += '<input type="hidden" name="finalPrice" id="finalPrice">';
                formHtml += '<input type="hidden" name="addOnPrice" id="addOnPrice" value="' + obj.dish_details.price + '">';
                formHtml += '<input type="hidden" name="selectedPrice" id="selectedPrice">';
                formHtml += '<input type="hidden" name="dishCount" id="dishCount" value="1">';

                $("#dish_choices_form").html(formHtml);

                for (var i = 1; i <= Object.keys(obj.dish_details.choiceCategory).length; i++) {
                    var alertStyles = {
                        color:"red",
                        "font-size":"20px",
                        "display":"none"
                    };
                    $(".alerticon"+i).css(alertStyles);

                    var successStyles = {
                        color:"green",
                        "font-size":"20px",
                        "display":"none"
                    }
                    $(".successicon"+i).css(successStyles);
                }
            }

        },
        error: function (error) {
            $(".loading-container").hide();
        }
    });
}

function setRestaurantFromLocality(localityId) {

    var locality = localityId;

    $.ajax({
        async: false,
        url: getRestaurantDetail,
        type: "POST",
        data: {
            locality: locality
        },
        success: function (response) {
            var obj = $.parseJSON(response);

            if (obj.success == 1) {

                if ($('#restaurantId').val() == '') {
                    let restaurantSelectedId = $('#restaurantId').val(obj.restaurant.restaurant_id);
                    $('#restaurantId').prop('disabled', true).trigger("chosen:updated");
                } else {
                    if (userData.role_id == "2" && obj.restaurant.restaurant_id != $('#restaurantId').val()) {
                        $(".box-address").removeClass("active");
                        addressId = "";
                        localityId = 0;
                        alert("Please select address from different locality");
                    }
                }
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
}

/*increment js*/
function stapper(className) {


    $(className).prepend('<div class="dec button stapper">-</div>');
    $(className).append('<div class="inc button stapper">+</div>');

    $(".button").on("click", function () {

        var $button = $(this);
        var oldValue = $button.parent().find("input").val();
        if ($button.text() == "+") {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            // Don't allow decrementing below zero
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 1;
            }
        }
        $button.parent().find("input").val(newVal);

    });
}

jQuery('.openPopupAdd').on("click", ".stapper", function () {
    if ($(this).parent().find("input[name=numId]").val() == 0) {
        $(this).parent().find("input[name=numId]").val("1");
        return false;
    }
    setItem($(this).parent().find("input[name=numId]"));

    var finalPrice = $("#finalPrice").val();
    priceChange(finalPrice);
});

/*On change of stepper number*/
jQuery(document).on("keyup", "input[name=numId]", function () {
    setItem($(this));
    var finalPrice = $("#finalPrice").val();
    priceChange(finalPrice);
});

function priceChange(price) {

    var dishCount = $(".numDishId").attr("predishcount");

    if (typeof dishCount == "undefined") {
        dishCount = $(".numDishId").val();
    }

    $("#selectedPrice").val(price);

    //For radio button of both category
    if (($("input[name='category1']:checked").attr("price") != 0) && (typeof $("input[name='category1']:checked").attr("price") != "undefined")) {
        radioBtnPrice1 = $("input[name='category1']:checked").attr("price");
    } else {
        radioBtnPrice1 = 0;
    }
    if (($("input[name='category2']:checked").attr("price") != 0) && (typeof $("input[name='category2']:checked").attr("price") != "undefined")) {
        radioBtnPrice2 = $("input[name='category2']:checked").attr("price");
    } else {
        radioBtnPrice2 = 0;
    }

    var radioBtnPrice = parseFloat(radioBtnPrice1) + parseFloat(radioBtnPrice2);

    //If left radio button has no price and direct product price given
    if (radioBtnPrice == 0 && ($('#addOnPrice').val() == '')) {
        radioBtnPrice = 1;
    } else {
        radioBtnPrice = radioBtnPrice;
    }

    //For single radio button group who have no price
    if (radioBtnPrice == 0 && ($('#addOnPrice').val() != '' && $('#addOnPrice').val() != 0)) {
        radioBtnPrice = 1;
    }

    var addonprice = $('#addOnPrice').val();
    addonprice = (addonprice != '' && addonprice != 0) ? addonprice : 1;



    //For Checkbox
    var mulch = $(".chociecheck");
    finalPrice = parseFloat(radioBtnPrice).toFixed(3) * parseFloat(dishCount).toFixed(3) * parseFloat(addonprice).toFixed(3);

    var mulprice = 0;
    if (mulch.length > 0) {
        $.each(mulch, function (k, v) {
            if ($(v).is(":checked")) {
                mulprice = parseFloat(mulprice) + parseFloat($(this).attr('price'));
            }
        })
    }
    if (mulprice > 0) {
        finalPrice = parseFloat(finalPrice) + (parseFloat(mulprice) * parseFloat(dishCount).toFixed(3));
    }

    $("#finalPrice").val(parseFloat(finalPrice).toFixed(3));
    $(".item_price").html(parseFloat(finalPrice).toFixed(3) + " KD");
}

function setItem(ele) {
    var totalDish = parseInt((ele.val() != '') ? ele.val() : 1);
    //console.log(totalDish);
    var dishId = ele.attr('dishId');
    var dishPrice = parseFloat(ele.attr('dishPrice')).toFixed(3);
    var preDishCount = parseInt(ele.attr('preDishCount'));
    $('#dishCount').val(parseInt(totalDish));
    // $('.numId'+ele.attr("class")).val(totalDish);
    $("." + ele.attr("class")).val(totalDish);
    var total = parseFloat($("#total").text());
    var charge = parseFloat($("#deliveryCharge").text());
    var total = parseFloat(total + (totalDish - preDishCount) * dishPrice).toFixed(3);
    $("#dishPrice" + ele.attr("class")).text(parseFloat(dishPrice * totalDish).toFixed(3));
    $(".dishPriceCart" + ele.attr("class")).text(parseFloat(dishPrice * totalDish).toFixed(3) + ' KD');
    $("#total").text(parseFloat(total).toFixed(3));
    $("#totalprice").text(parseFloat(total).toFixed(3) + ' KD');
    $("#subtotal").text(parseFloat(total - charge).toFixed(3));
    $(".cartprice").text(parseFloat(total).toFixed(3) + ' KD');

    $(".totalOrderDown").html('Order Total : ' + parseFloat(total).toFixed(3) + ' KD');

    ele.attr('preDishCount', totalDish);

    var dishDetails = getCookie('dishDetail');
    var OldDishDetails = dishDetails.dishDetail;
    var disharr = [];
    if (OldDishDetails != undefined) {
        disharr = JSON.parse(OldDishDetails);
    }
    for (var i = 0; i < disharr.length; i++) {
        if (disharr[i].dishId == dishId && "num" + disharr[i].id == ele.attr("class")) {
            disharr[i].dishcount = totalDish;
        }
    }
    dishDetails = JSON.stringify(disharr);
    document.cookie = "dishDetail=" + dishDetails + "; expires=" + lastday + "; path=/";
}

function formsubmit(res_id) {
    var fromdata = $('#dish_choices_form').serialize();

    var data = fromdata.split("&");
    var dishid = data[0].split("=");
    var choiceofone = [];
    var adddish = 0;

    var radioCatCount = $(".radioCategory").map(function () {
        return $(this).attr('id');
    }).length;

    if ($('.radioCategory').length > 0) {
        for (var i = 1; i <= radioCatCount; i++) {
            if (!fromdata.includes("category" + i) && $("#category" + i).length > 0) {
                $(".alerticon" + i).show();
                return false;
            }
            if (fromdata.includes("category" + i) && $("#category" + i).length > 0) {
                $(".alerticon" + i).hide();
                $(".successicon" + i).show();
            }
            /*if (!fromdata.includes("check_" + i) && $("#check_" + i).length > 0) {
                $(".alerticon" + i).show();
                return false;
            }*/
            if (fromdata.includes("check_" + i) && $("#check_" + i).length > 0) {
                $(".alerticon" + i).hide();
                $(".successicon" + i).show();
            }
        }
    }

    var checkCatCount = $(".chociecheck").map(function () {
        return $(this).attr('id');
    }).length;

    if ($('.chociecheck').length > 0) {
        for (var i = 1; i <= checkCatCount; i++) {
            if (!fromdata.includes("check_" + i) && $("#check_" + i).length > 0) {
                $(".alerticon" + i).show();
                return false;
            }
            if (fromdata.includes("check_" + i) && $("#check_" + i).length > 0) {
                $(".alerticon" + i).hide();
                $(".successicon" + i).show();
            }
        }
    }

    var isUpdate = $('#updateValue').val();
    if (isUpdate != '') {
        isUpdate = parseInt(isUpdate);
        var dishDetails = getCookie('dishDetail');
        var OldDishDetails = JSON.parse(dishDetails.dishDetail);
        var removeIndex = OldDishDetails.map(function (item) {
            return item.id;
        }).indexOf(isUpdate);
        OldDishDetails.splice(removeIndex, 1);
        dishDetails = JSON.stringify(OldDishDetails);
        document.cookie = "dishDetail=" + dishDetails + "; expires=" + lastday + "; path=/";

    }

    var disharr = [];
    var choiceofmultiple = [];
    var j = 0;

    for (var i = 0; i < data.length; i++) {

        if (data[i].includes('check_')) {
            var choice = data[i].split('=');
            choiceofmultiple[j] = choice[1];
            j++;
        }

        if (data[i].includes('category')) {
            choice = data[i].split("=");
            choiceofone.push(choice[1]);
        }

        /*if (data[i].includes('category2')) {
            choice = data[i].split("=");
            choiceofone.push(choice[1]);
        }*/

    }

    var dish_count = data[data.length - 1].split("=");

    var dishDetails = getCookie('dishDetail');
    var OldDishDetails = dishDetails.dishDetail;

    if (OldDishDetails != undefined) {
        disharr = JSON.parse(OldDishDetails);
    }

    if (dishid[1] != '' && (choiceofone.length > 0 || choiceofmultiple.length > 0)) {

        for (var i = 0; i < disharr.length; i++) {

            singDifference = $(choiceofone).not(disharr[i].choiceOfOne).get();
            mulDifference = $(choiceofmultiple).not(disharr[i].Multiplechoice).get();

            if (disharr[i].dishId == dishid[1] && disharr[i].res_id == res_id) {
                if (singDifference.length == 0 && mulDifference.length == 0) {
                    disharr[i].dishcount = parseInt(disharr[i].dishcount) + parseInt(dish_count[1]);
                    adddish = 1;
                }
            }
        }
    }

    if (isUpdate != '') {
        dish_count[1] = $("#numId").val();
    }
    var instruction = $("#instruction").val();

    if (adddish == 0) {

        if (OldDishDetails != undefined) {
            if (disharr.length > 0) {
                var id = disharr[(disharr.length) - 1].id + 1;
            } else {
                var id = 1;
            }
        } else {
            var id = 1;
        }
        var dishdata = {
            "id": id,
            "dishId": dishid[1],
            "choiceOfOne": choiceofone,
            "Multiplechoice": choiceofmultiple,
            "dishcount": dish_count[1],
            "res_id": res_id,
            "instruction": instruction
        };
        disharr.push(dishdata);

    }

    dishDetails = JSON.stringify(disharr);
    document.cookie = "dishDetail=" + dishDetails + "; expires=" + lastday + "; path=/";
    document.cookie = "locality_id=" + localityId + "; expires=" + lastday + "; path=/";

    /*All details filled and now toggle the modal and set it to table*/
    $("#modal_add_dish").modal("toggle");
    showCart();
    $('.chosen-select2').val('').trigger('chosen:updated');
    $('#updateValue').val('');
}

function showCart() {
    $.ajax({
        type: 'post',
        url: getdishdetails,
        success: function (response) {
            var obj = JSON.parse(response);
            console.log(obj);
            var tableHTML = '';

            for (let i = 0; i < obj.length; i++) {

                let choices = '';

                if (obj[i].choice) {
                    if (obj[i].choice.length > 0) {
                        for (let j = 0; j < obj[i].choice.length; j++) {
                            choices += obj[i].choice[j].choice_name + ",";
                        }
                    }
                }

                choices = choices + obj[i].instruction;

                let str = choices.replace(/,\s*$/, "");

                tableHTML += '<tr>';
                tableHTML += '<td>' + (i + 1) + '</td>';
                tableHTML += '<td>' + obj[i].dishname + '<br><span>' + str + '</span></td>';
                tableHTML += '<td>' + parseFloat(obj[i].price).toFixed(3) + ' KD</td>';
                tableHTML += '<td>' + parseInt(obj[i].dishcount) + '</td>';
                tableHTML += '<td>' + parseFloat(parseFloat(obj[i].price).toFixed(3) * parseInt(obj[i].dishcount)).toFixed(3) + ' KD</td>';
                tableHTML += '<td><i class="fa fa-pencil text-success" aria-hidden="true" onclick="editOrderDetail(' + obj[i].id + ')"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-trash text-danger" aria-hidden="true" onclick="deleteOrderDetail(' + obj[i].id + ')"></i></td>';
                tableHTML += '</tr>';
            }

            if (obj.length == 0) {
                tableHTML += '<tr><td colspan="6">No dish selected</td></tr>';
            }

            $(".tableBody").html(tableHTML);
        }
    });
}

function editOrderDetail(id) {

    var dishDetails = getCookie('dishDetail');
    var OldDishDetails = JSON.parse(dishDetails.dishDetail);

    let searchDishId = OldDishDetails.find(o => o.id === id);

    editDishDetails(searchDishId, id);
}

function deleteOrderDetail(id) {

    $("#modal_remove_dish").modal("toggle");

    $('#remove_dish').unbind().on("click", function () {
        var dishDetails = getCookie('dishDetail');
        var OldDishDetails = dishDetails.dishDetail;

        if (OldDishDetails != undefined) {
            disharr = $.parseJSON(OldDishDetails);

            for (var i = 0; i < disharr.length; i++) {
                if (disharr[i].id == id) {
                    var removeindex = disharr.indexOf(disharr[i]);
                    disharr.splice(removeindex, 1);
                }
            }

            if (disharr.length == 0) {
                document.cookie = 'dishDetail=;expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/';
            } else {
                dishDetails = JSON.stringify(disharr);
                document.cookie = "dishDetail=" + dishDetails + "; expires=" + lastday + "; path=/";
            }
            showCart();
            $("#modal_remove_dish").modal("toggle");
        }
    })
}

/*on modal close, removes selected value*/
$('#modal_add_dish').on('hidden.bs.modal', function () {
    $('.chosen-select2').val('').trigger('chosen:updated');
    $('#updateValue').val('');
});

function editDishDetails(searchDish, id) {

    console.log(searchDish);

    $.ajax({
        async: false,
        url: getDishDetailAJAX,
        type: "POST",
        data: {
            dish_id: searchDish.dishId,
            locality_id: localityId
        },
        success: function (response) {

            $(".loading-container").hide();

            let obj = JSON.parse(response);
            console.log(obj);

            $("#modal_add_dish").modal("toggle");
            $('#updateValue').val(id);

            var rightPopupHTML = '';

            rightPopupHTML += '<p class="item_name">Chicken Wings (15 Pcs)</p>';
            rightPopupHTML += '<div class="numbers-row-inner">';
            rightPopupHTML += '<input type="text" name="numId" id="numId" class="numDishId" value="1">';
            rightPopupHTML += '</div>';
            $(".openPopupAdd").html(rightPopupHTML);
            stapper(".numbers-row-inner");

            // $("#ch_dish_name").text(obj.dish_details.product_en_name);
            $(".item_name").text(obj.dish_details.product_en_name);
            // $(".item_price").text(parseFloat(obj.dish_details.price).toFixed(3)+" KD");
            $('#addOnPrice').val('');
            $('#dish_id').val(obj.dish_details.product_id);
            $(".numDishId").val(searchDish.dishcount);
            $("#instruction").val(searchDish.instruction);
            $(".numDishId").attr("predishcount", searchDish.dishcount);

            var totalPrice=0;
            var price= obj.dish_details.price;

            var formHtml = '';
            formHtml += '<input type="hidden" name="dish_id" id="dish_id" value="' + obj.dish_details.product_id + '"">';

            for (var i = 1; i <= Object.keys(obj.dish_details.choiceCategory).length; i++) {

                if (obj.dish_details.choiceCategory[i].is_multiple == 0) {
                    
                    formHtml += "<h3><i class='alerticon" + i + " fa fa-info-circle'></i><i class='successicon" + i + " fa fa-check-circle'></i> " + obj.dish_details.choiceCategory[i].category_name + " <span>required</span></h3>";

                    var catId = obj.dish_details.choiceCategory[i].category_id;
                    for (var j = 0; j < obj.dish_details.choiceCategory[i].choice['length']; j++) {

                        var k = jQuery.inArray(obj.dish_details.choiceCategory[i].choice[j].choice_id, searchDish.choiceOfOne);
                        if (searchDish.choiceOfOne[k] == obj.dish_details.choiceCategory[i].choice[j].choice_id) {
                            formHtml += "<input type='radio' name='category" + i + "' id='category" + i + "' class='radioCategory' checked value='" + obj.dish_details.choiceCategory[i].choice[j].choice_id + "' price=" + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + " onclick='priceChange(" + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + ")'>" + obj.dish_details.choiceCategory[i].choice[j].choice_name + "<span>";

                            price = parseFloat(parseFloat(price) + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price)).toFixed(3);

                            totalPrice = parseFloat(parseFloat(totalPrice) + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price)).toFixed(3);
                        } else {
                            formHtml += "<input type='radio' name='category" + i + "' id='category" + i + "' class='radioCategory' value='" + obj.dish_details.choiceCategory[i].choice[j].choice_id + "' price=" + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + " onclick='priceChange(" + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + ")'>" + obj.dish_details.choiceCategory[i].choice[j].choice_name + "<span>";
                        }

                        if (parseFloat(obj.dish_details.choiceCategory[i].choice[j].price) != 0) {
                            formHtml += parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + " KD</span><br>";
                        } else {
                            formHtml += ' </span><br>';
                        }
                    }
                } else {

                    formHtml += "<h3><i class='alerticon" + i + " fa fa-info-circle'></i><i class='successicon" + i + " fa fa-check-circle'></i> " + obj.dish_details.choiceCategory[i].category_name + " </h3>";

                    for (var j = 0; j < obj.dish_details.choiceCategory[i].choice['length']; j++) {

                        var k = jQuery.inArray(obj.dish_details.choiceCategory[i].choice[j].choice_id, searchDish.Multiplechoice);

                        if (searchDish.Multiplechoice[k] == obj.dish_details.choiceCategory[i].choice[j].choice_id) {
                            formHtml += "<label class='checkbox_custom'>" + obj.dish_details.choiceCategory[i].choice[j].choice_name + "<input type='checkbox' checked id='check_" + i + "' class='chociecheck' name='check_" + i + "' onchange='priceChange(" + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + ")' price=" + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + " value='" + obj.dish_details.choiceCategory[i].choice[j].choice_id + "'><span>" + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + "KD</span><span class='checkmark'></span></label><br>";

                            totalPrice = parseFloat(parseFloat(totalPrice) + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price)).toFixed(3);

                            price = parseFloat(parseFloat(price) + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price)).toFixed(3);
                        } else {
                            formHtml += "<label class='checkbox_custom'>" + obj.dish_details.choiceCategory[i].choice[j].choice_name + "<input type='checkbox' id='check_" + i + "' class='chociecheck' name='check_" + i + "' onchange='priceChange(" + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + ")' price=" + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + " value='" + obj.dish_details.choiceCategory[i].choice[j].choice_id + "'><span>" + parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3) + "KD</span><span class='checkmark'></span></label><br>";
                        }
                    }
                }
            }
            formHtml += '<input type="hidden" name="product_price" id="product_price" value="' + obj.dish_details.price + '"">';
            formHtml += '<input type="hidden" name="finalPrice" id="finalPrice">';
            formHtml += '<input type="hidden" name="addOnPrice" id="addOnPrice" value="' + obj.dish_details.price + '">';
            formHtml += '<input type="hidden" name="selectedPrice" id="selectedPrice">';
            formHtml += '<input type="hidden" name="dishCount" id="dishCount" value="1">';

            $(".numDishId").attr("dishPrice", parseFloat(totalPrice).toFixed(3));

            let setOldPrice = parseFloat(price) * parseFloat(searchDish.dishcount);

            $(".item_price").text(setOldPrice.toFixed(3) + " KD");

            $("#dish_choices_form").html(formHtml);

            for (var i = 1; i <= Object.keys(obj.dish_details.choiceCategory).length; i++) {
                var alertStyles = {
                    color:"red",
                    "font-size":"20px",
                    "display":"none"
                };
                $(".alerticon"+i).css(alertStyles);

                var successStyles = {
                    color:"green",
                    "font-size":"20px",
                    "display":"none"
                }
                $(".successicon"+i).css(successStyles);
            }

        },
        error: function (error) {
            $(".loading-container").hide();
        }
    });
}


function saveOrderDetail() {
    $('#updateValue').val('');
    var orderDate = $("#datetimepicker").val();
    var restaurant = $("#restaurantId").val();
    var orderType = $("#paymentId").val();
    var orderStatus = $("#orderStatusId").val();
    var orderTypeId = $("#orderTypeId").val();
    var locality = addressId;
    var cookiedata = getCookie();
    var dishDetail = cookiedata.dishDetail;

    if (dishDetail == '' || dishDetail == undefined) {
        alert("No dish selected!");
    } else if (orderType == '') {
        alert("Payment type not selected!");
    } else if (orderStatus == '') {
        alert("Order status not selected");
    } else {

        $.ajax({
            type: 'post',
            url: addOrderData,
            data: {
                orderDate: orderDate,
                restaurant: restaurant,
                orderType: orderType,
                orderStatus: orderStatus,
                locality: locality,
                dishDetail: dishDetail,
                orderTypeId: orderTypeId,
                user: userId
            },
            success: function (response) {
                var obj = JSON.parse(response);
                $("#step3").removeClass("btn-default");
                $("#step3").addClass("btn-primary");

                $("#succssOrder_model").modal('show');

            }
        });
    }
}

function orderSuccess() {
    window.location.href = returnUrl;
}

$('#succssOrder_model').on('hidden.bs.modal', function () {
    window.location.href = returnUrl;
});

function displayOtherField()
{
  $(".otherAddressReq").text('');
  var address_type = $('input[name=address_type]:checked').val();
  if(address_type == "3" || address_type == 3)
  {
    $("#other_address").css('display','block');  
  }
  else 
  {
    $("#other_address").css('display','none');
    $(".otherAddressReq").hide();
  }
}
