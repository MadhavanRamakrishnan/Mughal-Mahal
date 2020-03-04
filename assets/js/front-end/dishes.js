$(window).load(function() {
    autoload();
});
$(window).on('resize', function(e) {
    autoload();
});

function autoload() {
    if ($(window).width() > 360) {
        if (is_favourite == '1') {
            getFavouriteDishes(localityId);
        } else if (is_favourite == '0') {
            getdishcat(localityId, "");
            getMainDishes(localityId, "");
            getautocomplete();
        }
    } else {
        if (is_favourite == '1') {
            getFavouriteDishesMobile(localityId);
        } else if (is_favourite == '0') {
            getdishcat(localityId, "");
            getMabiDishes(localityId, "");
            getautocomplete();
        }
    }
}
cartflag = 0;
var curr = new Date;
var lastday = new Date(curr.getFullYear(), curr.getMonth() + 6, 0);

function getCookie(cname) {
    var pairs = document.cookie.split(";");
    var cookies = {};
    for (var i = 0; i < pairs.length; i++) {
        var pair = pairs[i].split("=");
        cookies[(pair[0] + '').trim()] = unescape(pair[1]);
    }
    return cookies;
}

function getdishcat(locality, search = "") {
    $("#loading-div-background").show();
    $.ajax({
        type: 'POST',
        url: dish_caturl + locality,
        data: {
            is_active: 1,
            search: search
        },
        beforeSend: function() {},
        success: function(obj) {
            var data = JSON.parse(obj);
            var html = "";
            if (data.response == "true") {
                if (data.choice.length > 0) {
                    if(data.totalBestDishes >0)
                    {
                        html += '<li class="item ">';
                        html += '<a class="" href="#00">' + bestDish + '</a>';
                        html += '</li>';
                    }
                   
                    for (var i = 0; i < data.choice.length; i++) {
                        if (data.choice[i].category_id != null) {
                            html += '<li class="item ">';
                            html += '<a class="" href="#' + data.choice[i].category_id + '">' + data.choice[i].category_name + '</a>';
                            html += '</li>';
                        }
                    }
                }
            } else {}
            $("#loading-div-background").hide();
            $('.list').html(html);
            $(".menu_cat_main").find('ul').find("li:first").addClass('active');
            $(".menu_cat_main").find('ul').find("li:first").find("a").addClass('active');
        }
    });
}

function getMainDishes(locality, search = "") {
    $("#loading-div-background").show();
    $.ajax({
        type: 'POST',
        url: dishes + locality,
        data: {
            type: 'groupby',
            search: search
        },
        beforeSend: function() {},
        success: function(obj) {
            var data = JSON.parse(obj);
            var html = "";
            if (data.response == "true") {
                $("#restaurant_id").val(locality);
                
                if(data.totalBestDishes >0)
                {
                    html += setMainDishlistData(data, 1);
                }
                html += setMainDishlistData(data, 0);
                $("#loading-div-background").hide();
                $('.list_dish_category_wise').html(html);
                var dish_id = 0;
                $(".modalitypopup").click(function() {
                    dish_id = $(this).attr('dish_id');
                    res_id = $(this).attr('res_id');
                });
                addproduct = Modality.init('#product_add', {
                    effect: 'slide-up',
                    onOpen: function(e) {
                        var html = '';
                        $.ajax({
                            type: 'POST',
                            url: getdishchoice,
                            data: {
                                dish_id: dish_id
                            },
                            beforeSend: function() {
                                $('#product_add').find('.product_description').html('');
                                $('#product_price').val('');
                                $('#finalprice').val('');
                                $('#addonprice').val('');
                                $('#dishcount').val(1);
                            },
                            success: function(obj) {
                                var data = JSON.parse(obj);
                                if (data.response == "true") {
                                    $('#product_add').find('.product_name').html(data.dish_details.product_en_name);
                                    $('#product_add').find('#dish_id').val(data.dish_details.product_id);
                                    $('#product_add').find('#dishsubmit').attr('onclick', 'formsubmit()');
                                    html += '<p>';
                                    html += (data.dish_details.en_description != null) ? data.dish_details.en_description : +'';
                                    html += '</p>';
                                    var ri = 0;
                                    var ci = 0;
                                    for (var i = 1; i <= Object.keys(data.dish_details.choiceCategory).length; i++) {
                                        if (data.dish_details.choiceCategory[i].is_multiple == 0) {
                                            html += '<div class="cus_radio">';
                                            html += '<div class="top_tit">';
                                            html += '<span><i class="alerticon' + (ri + 1) + ' fa fa-info-circle"></i><i class="successicon' + i + ' fa fa-check-circle"></i>  ' + data.dish_details.choiceCategory[i].category_name + '</span> <p>' + required + '</p>';
                                            html += '<input type="hidden"  name="radio-text' + ri + '">';
                                            html += '</div><ul>';
                                            for (var ky in data.dish_details.choiceCategory[i].choice) {
                                                html += '<li>';
                                                html += '<input type="radio" price="' + data.dish_details.choiceCategory[i].choice[ky].price + '" id="dish' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '" value="' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '" name="radio-group' + ri + '">';
                                                html += '<label for="dish' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '">' + data.dish_details.choiceCategory[i].choice[ky].choice_name + '</label>';
                                                html += '<p style="float: right; font-size: 15px; color:#000;">';
                                                html += data.dish_details.choiceCategory[i].choice[ky].price == 0 ? '' : '+' + parseFloat(data.dish_details.choiceCategory[i].choice[ky].price).toFixed(3) + ' KD' + '</p>';
                                                html += '</li>';
                                            }
                                            html += '</ul></div>';
                                            ri++;
                                        } else {
                                            html += '<div class="cus_checkbox">';
                                            html += '<div class="top_tit">';
                                            html += '<span>' + data.dish_details.choiceCategory[i].category_name + '</span>';
                                            html += '<input type="hidden"  name="chbox_' + ci + '">';
                                            html += '</div><ul>';
                                            for (var ky in data.dish_details.choiceCategory[i].choice) {
                                                html += '<li>';
                                                html += '<label class="checkbox_custom">' + data.dish_details.choiceCategory[i].choice[ky].choice_name;
                                                html += '<input type="checkbox" class="chociecheck" price="' + data.dish_details.choiceCategory[i].choice[ky].price + '" name="chociecheck_' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '" id="chociecheck' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '">';
                                                html += '<span class="checkmark"></span>';
                                                html += '<p style="float: right; font-size: 15px; color:#000;">';
                                                html += data.dish_details.choiceCategory[i].choice[ky].price == 0 ? '' : '+' + parseFloat(data.dish_details.choiceCategory[i].choice[ky].price).toFixed(3) + ' KD' + '</p>';
                                                html += '</label>';
                                            }
                                            ci++;
                                        }
                                    }
                                    html += '<div class="pr_quantity">';
                                    html += '<div class="pr_quantity_in">';
                                    html += '<div class="stepper-widget stepperpopup1">';
                                    html += '<button type="button" class="js-qty-down btn btn-primary">-</button>';
                                    html += '<input type="text" class="js-qty-input form-control"  value="1" />';
                                    html += '<input type="hidden" id="stepper_val" value="1" />';
                                    html += '<input type="hidden" id="catPrice1" value="0" />';
                                    html += '<input type="hidden" id="catPrice2" value="0" />';
                                    html += '<input type="hidden" id="catPrice3" value="0" />';
                                    html += '<button type="button" class="js-qty-up btn btn-danger">+</button>';
                                    html += '</div></div></div>';
                                    $('#product_add').find('.product_description').html(html);
                                    $('#product_add').find('.finalprice').html(parseFloat(data.dish_details.price).toFixed(3) + ' KD');
                                    $('#product_add').find('#product_price').val(parseFloat(data.dish_details.price).toFixed(3));
                                    $('#product_add').find('#finalprice').val(parseFloat(data.dish_details.price).toFixed(3));
                                    $('#product_add').find('#finalprice').attr('prePrice', parseFloat(data.dish_details.price).toFixed(3));
                                    $('.stepper-widget').stepper();
                                }
                            },
                            complete: function() {}
                        });
                    },
                });
            } else {
                html += '<div class="order_top"><div class="delivered no_Order">';
                html += noDishFound;
                html += '</div></div></div>';
                $("#loading-div-background").hide();
                $('.list_dish_category_wise').html(html);
            }
        },
        complete: function() {}
    });
}

function getMabiDishes(locality, search = "") {
    $("#loading-div-background").show();
    $.ajax({
        type: 'POST',
        url: dishes + locality,
        data: {
            type: 'groupby',
            search: search
        },
        beforeSend: function() {},
        success: function(obj) {
            var data = JSON.parse(obj);
            var html = "";
            if (data.response == "true") {
                $("#restaurant_id").val(locality);
                
                if(data.totalBestDishes >0)
                {
                    html += setMobiDishlistData(data, 1);
                }
               
                html += setMobiDishlistData(data, 0);
                $("#loading-div-background").hide();
                $('.accordion').html(html);
                $(".accordion").accordion();
                var dish_id = 0;
                $(".modalitypopup").click(function() {
                    dish_id = $(this).attr('dish_id');
                    res_id = $(this).attr('res_id');
                });
                addproduct = Modality.init('#product_add', {
                    effect: 'slide-up',
                    onOpen: function(e) {
                        var html = '';
                        $.ajax({
                            type: 'POST',
                            url: getdishchoice,
                            data: {
                                dish_id: dish_id
                            },
                            beforeSend: function() {
                                $('#product_add').find('.product_description').html('');
                                $('#product_price').val('');
                                $('#finalprice').val('');
                                $('#addonprice').val('');
                                $('#dishcount').val(1);
                            },
                            success: function(obj) {
                                var data = JSON.parse(obj);
                                if (data.response == "true") {
                                    $('#product_add').find('.product_name').html(data.dish_details.product_en_name);
                                    $('#product_add').find('#dish_id').val(data.dish_details.product_id);
                                    $('#product_add').find('#dishsubmit').attr('onclick', 'formsubmit()');
                                    html += '<p>';
                                    html += (data.dish_details.en_description != null) ? data.dish_details.en_description : +'';
                                    html += '</p>';
                                    var ri = 0;
                                    var ci = 0;
                                    for (var i = 1; i <= Object.keys(data.dish_details.choiceCategory).length; i++) {
                                        if (data.dish_details.choiceCategory[i].is_multiple == 0) {
                                            html += '<div class="cus_radio">';
                                            html += '<div class="top_tit">';
                                            html += '<span><i class="alerticon' + (ri + 1) + ' fa fa-info-circle"></i><i class="successicon' + i + ' fa fa-check-circle"></i>  ' + data.dish_details.choiceCategory[i].category_name + '</span> <p>' + required + '</p>';
                                            html += '<input type="hidden"  name="radio-text' + ri + '">';
                                            html += '</div><ul>';
                                            for (var ky in data.dish_details.choiceCategory[i].choice) {
                                                html += '<li>';
                                                html += '<input type="radio" price="' + data.dish_details.choiceCategory[i].choice[ky].price + '" id="dish' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '" value="' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '" name="radio-group' + ri + '">';
                                                html += '<label for="dish' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '">' + data.dish_details.choiceCategory[i].choice[ky].choice_name + '</label>';
                                                html += '<p style="float: right; font-size: 15px; color:#000;">';
                                                html += data.dish_details.choiceCategory[i].choice[ky].price == 0 ? '' : '+' + parseFloat(data.dish_details.choiceCategory[i].choice[ky].price).toFixed(3) + ' KD' + '</p>';
                                                html += '</li>';
                                            }
                                            html += '</ul></div>';
                                            ri++;
                                        } else {
                                            html += '<div class="cus_checkbox">';
                                            html += '<div class="top_tit">';
                                            html += '<span>' + data.dish_details.choiceCategory[i].category_name + '</span>';
                                            html += '<input type="hidden"  name="chbox_' + ci + '">';
                                            html += '</div><ul>';
                                            for (var ky in data.dish_details.choiceCategory[i].choice) {
                                                html += '<li>';
                                                html += '<label class="checkbox_custom">' + data.dish_details.choiceCategory[i].choice[ky].choice_name;
                                                html += '<input type="checkbox" class="chociecheck" price="' + data.dish_details.choiceCategory[i].choice[ky].price + '" name="chociecheck_' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '" id="chociecheck' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '">';
                                                html += '<span class="checkmark"></span>';
                                                html += '<p style="float: right; font-size: 15px; color:#000;">';
                                                html += data.dish_details.choiceCategory[i].choice[ky].price == 0 ? '' : '+' + parseFloat(data.dish_details.choiceCategory[i].choice[ky].price).toFixed(3) + ' KD' + '</p>';
                                                html += '</label>';
                                            }
                                            ci++;
                                        }
                                    }
                                    html += '<div class="pr_quantity">';
                                    html += '<div class="pr_quantity_in">';
                                    html += '<div class="stepper-widget stepperpopup1">';
                                    html += '<button type="button" class="js-qty-down btn btn-primary">-</button>';
                                    html += '<input type="text" class="js-qty-input form-control"  value="1" />';
                                    html += '<input type="hidden" id="stepper_val" value="1" />';
                                    html += '<input type="hidden" id="catPrice1" value="0" />';
                                    html += '<input type="hidden" id="catPrice2" value="0" />';
                                    html += '<input type="hidden" id="catPrice3" value="0" />';
                                    html += '<button type="button" class="js-qty-up btn btn-danger">+</button>';
                                    html += '</div></div></div>';
                                    $('#product_add').find('.product_description').html(html);
                                    $('#product_add').find('.finalprice').html(parseFloat(data.dish_details.price).toFixed(3) + ' KD');
                                    $('#product_add').find('#product_price').val(parseFloat(data.dish_details.price).toFixed(3));
                                    $('#product_add').find('#finalprice').val(parseFloat(data.dish_details.price).toFixed(3));
                                    $('#product_add').find('#finalprice').attr('prePrice', parseFloat(data.dish_details.price).toFixed(3));
                                    $('.stepper-widget').stepper();
                                }
                            },
                            complete: function() {}
                        });
                    },
                });
            } else {
                html += '<div class="order_top"><div class="delivered no_Order">';
                html += noDishFound;
                html += '</div></div></div>';
                $("#loading-div-background").hide();
                $('.list_dish_category_wise').html(html);
            }
        },
        complete: function() {}
    });
}

$(document).on('stepperupdate', '.stepperpopup', function(ev, data) {
    var dish_count = data.value;
    var price = $('#product_price').val();
    var addonprice = $('#addonprice').val();
    addonprice = (addonprice != '') ? addonprice : 0;
    var finalprice = dish_count * (parseFloat(price) + parseFloat(addonprice));
    $('#product_add').find('#stepper_val').val(dish_count);
    $('#product_add').find('.finalprice').html(parseFloat(finalprice).toFixed(3) + ' KD');
    $('#product_add').find('#finalprice').val(parseFloat(finalprice).toFixed(3));
    $('#product_add').find('#finalprice').attr('prePrice', parseFloat(data.dish_details.price).toFixed(3));
    $('#product_add').find('#dishcount').val(dish_count);
});
$("body").click(function() {
    $("#myDropdown").hide();
});

function formsubmit() {
    var fromdata = $('#dishDetails').serialize();
    var data = fromdata.split("&");
    var dishid = data[0].split("=");
    var choiceofmultiple = [];
    var choiceOfOne = [];
    var isRadioChoice = $('#dishDetails').find(".cus_radio").find("input[type='radio']").length;
    if (isRadioChoice != undefined && isRadioChoice != 0) {
        var j = 0;
        for (var i = 1; i < data.length; i++) {
            if (data[i].includes('radio-text' + j)) {
                if (!(data[(i + 1)].includes('radio-group' + j))) {
                    $('.alerticon' + (j + 1)).show();
                    $('.successicon' + (j + 1)).hide();
                    return false;
                } else {
                    $('.successicon' + (j + 1)).show();
                    $('.alerticon' + (j + 1)).hide();
                    choiceo1 = data[(i + 1)].split("=");
                    choiceOfOne[j] = choiceo1[1];
                }
                j++;
            }
        }
    }
    var ischeckboxChoice = $('#dishDetails').find(".cus_checkbox").find("input[type='checkbox']").length;
    if (ischeckboxChoice != undefined && ischeckboxChoice != 0) {
        var j = 0;
        for (var i = 0; i < data.length; i++) {
            if (data[i].includes('chociecheck')) {
                var choice = data[i].split('=');
                var choicedata = choice[0].split('_');
                choiceofmultiple[j] = choicedata[1];
                j++;
            }
        }
    }
    var dish_count = data[data.length - 1].split("=");
    if (dishid[1] != '' && dish_count[1] != '') {
        var disharr = [];
        var dishDetails = getCookie('dishDetail');
        var OldDishDetails = dishDetails.dishDetail;
        var adddish = 0;
        if (OldDishDetails != undefined) {
            disharr = JSON.parse(OldDishDetails);
            for (var i = 0; i < disharr.length; i++) {
                if (disharr[i].dishId == dishid[1]) {
                    if (sameMultichoice(choiceOfOne, disharr[i].choiceOfOne) && sameMultichoice(choiceofmultiple, disharr[i].Multiplechoice)) {
                        adddish = 1;
                        var id = disharr[i].id;
                    } else {
                        adddish = 0;
                    }
                    if (adddish == 1) {
                        disharr[i].dishcount = parseInt(disharr[i].dishcount) + parseInt(dish_count[1]);
                        disharr[i].id = id;
                        break;
                    }
                }
            }
        }
        if (adddish == 0) {
            if (OldDishDetails != undefined) {
                var id = disharr.length + 1;
            } else {
                var id = 1;
            }
            var dishdata = {
                "id": id,
                "dishId": dishid[1],
                "choiceOfOne": choiceOfOne,
                "Multiplechoice": choiceofmultiple,
                "dishcount": dish_count[1]
            };
            disharr.push(dishdata);
        }
        dishDetails = JSON.stringify(disharr);
        customtoastr("Dish Added Successfully", 'show');
        addproduct.close();
        document.cookie = "dishDetail=" + dishDetails + "; expires=" + lastday + "; path=/";
    }
}

function sameMultichoice(arr1, arr2) {
    if (arr1.length == arr2.length && arr1.length == 0) {
        return true;
    }
    if (arr1.length != arr2.length) {
        return false;
    } else {
        var x = 0;
        for (var j = 0; j < arr1.length; j++) {
            for (var j = 0; j < arr1.length; j++) {
                if (jQuery.inArray(arr2[j], arr1) == -1) {
                    var x = 1;
                }
            }
        }
        if (x == 0) {
            return true;
        } else {
            return false;
        }
    }
}
$(document).on('click', '.addcart', function() {
    var dish_Id = $(this).attr('dish_id');
    var res_id = $(this).attr('res_id');
    var dishDetails = getCookie('dishDetail');
    var OldDishDetails = dishDetails.dishDetail;
    var dishdata = new Array();
    var disharr = [];
    var dishno = 1;
    var restaurant_id = $("#restaurant_id").val();
    if (OldDishDetails != undefined) {
        disharr = JSON.parse(OldDishDetails);
    }
    adddish = 0;
    for (var i = 0; i < disharr.length; i++) {
        if (disharr[i].dishId == dish_Id && disharr[i].res_id == res_id) {
            disharr[i].dishcount = parseInt(disharr[i].dishcount) + parseInt(dishno);
            adddish = 1;
        }
    }
    if (OldDishDetails != undefined && disharr.length > 0) {
        id = disharr.length + 1;
    } else {
        id = 1;
    }
    if (adddish == 0) {
        dishdata = {
            "id": id,
            "dishId": dish_Id,
            "choiceOfOne": [],
            "Multiplechoice": [],
            "dishcount": dishno,
            "res_id": res_id
        };
        disharr.push(dishdata);
    }
    dishDetails = JSON.stringify(disharr);
    customtoastr("Dish Added Successfully", 'show');
    document.cookie = "dishDetail=" + dishDetails + "; expires=" + lastday + "; path=/";
});

$(window).scroll(function() {
    if ($(window).scrollTop() >= 240) {
        $(".dishes_menu").addClass("sticky");
    } else {
        $(".dishes_menu").removeClass("sticky");
    }
});

$(document).ready(function() {
    $(document).on('click', "a", function(event) {
        var listattr = $(this).attr('list');
        if (this.hash !== "" && listattr == 'cat') {
            event.preventDefault();
            var hash = this.hash;
            $('.active').removeClass('active');
            $(this).parent('li').addClass('active');
            $('html, body').animate({
                scrollTop: $(hash).offset().top
            }, 1500, function() {
                window.location.hash = hash;
            });
        }
    });
});
$(".opencart").click(function(e) {
    e.stopPropagation();
    var dishDetails = getCookie('dishDetail');
    $("#loading-div-background").show();
    if (dishDetails.dishDetail == undefined || $.parseJSON(dishDetails.dishDetail).length == 0) {
        $("#loading-div-background").hide();
        $("#myDropdown").hide();
        customtoastr(NoDishes, 'errorshow');
    } else {
        if (cartflag == 0) {
            $('.cartitem').html("");
            $('.carttotal').html("");
            $.ajax({
                type: 'post',
                url: getdishdetails,
                data: {
                    dishdata: dishDetails.dishDetail
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    if (obj.length === 0) {
                        $("#loading-div-background").hide();
                        $("#myDropdown").hide();
                        customtoastr(NoDishes, 'errorshow');
                    } else {
                        var html = "";
                        var totalhtml = "";
                        var subtotalprice = 0;
                        for (var i = 0; i < obj.length; i++) {
                            var choices = "";
                            var dishprice = 0;
                            dishprice = parseFloat(obj[i].price);
                            if (obj[i].choice != undefined) {
                                for (var j = 0; j < Object.keys(obj[i].choice).length; j++) {
                                    dishprice = parseFloat(obj[i].choice[j].price) + dishprice;
                                    choices = obj[i].choice[j].choice_name.trim() + ',' + choices.trim();
                                }
                            }
                            dishpriceTotal = parseFloat(dishprice) * parseInt(obj[i].dishcount);
                            subtotalprice = parseFloat(dishpriceTotal) + parseFloat(subtotalprice);
                            if (obj[i].locality == undefined) {
                                var inLocality = "notInLocality";
                            } else {
                                var inLocality = "";
                            }
                            html += '<li id="dish' + obj[i].id + '" class="' + inLocality + '"><div class="quantity"><div class="pr_quantity">';
                            html += '<div class="pr_quantity_in">';
                            html += "<div class='stepper-widget steppercart'>";
                            html += '<button type="button" class="js-qty-down btn btn-primary">-</button>';
                            html += '<input type="text" class="js-qty-input form-control"  value="' + obj[i].dishcount + '" />';
                            html += '<input type="hidden" class="cart_stepper_val"    value="' + obj[i].dishcount + '" />';
                            html += '<input type="hidden" class="product_price"  value="' + dishprice + '" />';
                            html += '<input type="hidden" class="dish_id" value="' + obj[i].dishid + '" />';
                            html += '<input type="hidden" class="dishId" value="' + obj[i].id + '" />';
                            html += '<button type="button" class="js-qty-up btn btn-danger">+</button>';
                            html += "</div></div></div></div>";
                            html += "</div><div class='dish_name'><h3>";
                            html += obj[i].dishname;
                            if (choices != '') {
                                html += " <i class='fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='" + choices + "'></i>";
                            }
                            html += "</h3> </div>";
                            html += "<div class='dish_price' value=" + dishpriceTotal.toFixed(3) + "><p>";
                            html += dishpriceTotal.toFixed(3);
                            html += " KD </p></div><div class='order_cancle'><img dish_id='" + obj[i].dishid + "' dishId='" + obj[i].id + "' class='removedish' src='" + baseurl + "/assets/images/front-end/icon/ic_cancel.svg'></div></li>";
                        }
                        totalhtml += "<li><div class='left'><span>" + TotalLable + "</span></div><div class='right'><span class='ch_total' value='" + subtotalprice.toFixed(3) + "'>" + subtotalprice.toFixed(3) + " KD</span></div></li>";
                        $("#loading-div-background").hide();
                        $('.cartitem').append(html);
                        $('.carttotal').append(totalhtml);
                        $('.stepper-widget').stepper();
                        $('.removedish').click(function() {
                            $(this).hide();
                            var dish_id = $(this).attr('dish_id');
                            var dishId = $(this).attr('dishId');
                            var dish_price = $("#dish" + dishId).find(".dish_price").attr("value");
                            var ch_total = $(".ch_total").attr("value");
                            $(".ch_total").text((parseFloat(ch_total) - parseFloat(dish_price)).toFixed(3) + " KD");
                            $(".ch_total").attr('value', parseFloat(ch_total) - parseFloat(dish_price));
                            $('#dish' + dishId).fadeOut(30);
                            var dishDetails = getCookie('dishDetail');
                            var OldDishDetails = dishDetails.dishDetail;
                            if (OldDishDetails != undefined) {
                                disharr = JSON.parse(OldDishDetails);
                            }
                            for (var i = 0; i < disharr.length; i++) {
                                if (disharr[i].id == dishId) {
                                    var removeindex = disharr.indexOf(disharr[i]);
                                    disharr.splice(removeindex, 1);
                                }
                            }
                            if (disharr.length == 0) {
                                delete_cookie('dishDetail');
                                $("#loading-div-background").hide();
                                $("#myDropdown").hide();
                                customtoastr(NoDishes, 'errorshow');
                            }
                            dishDetails = JSON.stringify(disharr);
                            document.cookie = "dishDetail=" + dishDetails + "; expires=" + lastday + "; path=/";
                        });
                    }
                }
            });
            $("#myDropdown").slideDown('fast');
            cartflag = 1;
        } else {
            $("#loading-div-background").hide();
            $("#myDropdown").slideUp('fast');
            cartflag = 0;
        }
    }
    $("#login_after").hide();
});
$("body").click(function() {
    $("#myDropdown").slideUp('fast');
    cartflag = 0;
});

function checkMultipleChoiceIsSame(currChoise, preChoise) {
    var is_same = (currChoise.length == preChoise.length) && currChoise.every(function(element, index) {
        return 1;
    });
}
$(document).on('keyup', '#search', function() {
    var search = $(this).val();
    getdishcat(localityId, search);
    getMainDishes(localityId, search);
})
$(document).on('keyup', '#searchForMob', function() {
    var search = $(this).val();
    getdishcat(localityId, search);
    getMabiDishes(localityId, search);
})

function getFavouriteDishes(locality = "") {
    $("#loading-div-background").show();
    $.ajax({
        type: 'POST',
        url: favouriteDioshes + "/" + locality,
        data: {
            type: 'groupby'
        },
        beforeSend: function() {},
        success: function(obj) {
            var loginData = getCookie();
            var data = JSON.parse(obj);
            var html = "";
            html += '<div class="dish_list_title">';
            html += '<div class="dish_list">';
            if (data.response == "true") {
                
                var i = 0;
                html += '<input type="hidden" id="restaurant_id" value="' + locality + '">';
                html += '<div class="container">';
                if (data.favouriteDish == undefined) {
                    html += '<div class="order_top"><div class="delivered no_Order">';
                    html += noDishFound;
                    html += '</div></div>';
                } else {
                    var count = data.dishesData.dishes;
                    $.each(data.dishesData.dishes, function(key, value) {
                        if (count != 0 && value.is_favourite == "1") {
                            html += '<div class="col-sm-6   favourite' + value.product_id + '">';
                            html += '<div class="dish_box">';
                            html += '<div class="dish_im">';
                            html += '<img src="';
                            html += value.dish_image ? baseurl + 'assets/images/front-end/dishes/' + value.dish_image : baseurl + 'assets/images/front-end/dishes/no_image.png';
                            html += '" alt="">';
                            html += '</div><div class="dish_des"><h4>';
                            html += value.product_en_name ? value.product_en_name : '';
                            html += '</h4><p>';
                            html += value.en_description ? value.en_description : '';
                            html += '</p>';
                            html += '<ul is_favourite="1" dish_id ="' + value.product_id + '">';
                            if (data.dishRating == undefined) {
                                html += showStarRate();
                            } else if (data.dishRating[value.product_id] == undefined) {
                                html += showStarRate();
                            } else {
                                for (k = 1; k < 6; k++) {
                                    html += '<li><a  index="' + k + '" href="javascript:void(0)">';
                                    if (data.dishRating[value.product_id] >= k) {
                                        html += '<img src="' + baseurl + 'assets/images/front-end/icon/star.png" align="">';
                                    } else {
                                        html += '<img src="' + baseurl + 'assets/images/front-end/icon/star_grey.png" align="">';
                                    }
                                    html += '</a></li>';
                                }
                            }
                            html += '</ul>';
                            if (value.in_locality == "1") {
                                html += '<span>';
                                if (value.choices.length > 0) {
                                    html += PriceOnSelection;
                                } else {
                                    html += parseFloat(value.dish_price).toFixed(3) + 'KD';
                                    html += "<br>";
                                }
                                html += '</span>';
                                var option = (value.choices.length > 0) ? '"#product_add"' : 'javascript:void(0)';
                                var addoption = (value.choices.length > 0) ? '' : 'addcart';
                                html += '<a href=' + option + ' dish_id="' + value.product_id + '" res_id="' + locality + '" class="modalitypopup ' + addoption + '" aria-label="open">' + ADD + ' <i class="fa fa-plus" aria-hidden="true"></i></a>';
                            } else {
                                html += '<a href="javascript:void(0)"  class="modalitypopup notInLocality" aria-label="open">' + ADD + ' <i class="fa fa-plus" aria-hidden="true"></i></a>';
                            }
                            html += '<div class="favorite_icon removeFavourite" dish_id="' + value.product_id + '" is_favourite=""><a href="javascript:void(0)" title="Remove from favourite" ><h3>X</h3></a></div>';
                            html += '</div></div></div>';
                        }
                    });
                }
                html += '</div></div></div></div>';
                $("#loading-div-background").hide();
                $('.list_dish_category_wise').html(html);
                var dish_id = 0;
                $(".modalitypopup").click(function() {
                    dish_id = $(this).attr('dish_id');
                    res_id = $(this).attr('res_id');
                });
                addproduct = Modality.init('#product_add', {
                    effect: 'slide-up',
                    onOpen: function(e) {
                        var html = '';
                        $.ajax({
                            type: 'POST',
                            url: getdishchoice,
                            data: {
                                dish_id: dish_id
                            },
                            beforeSend: function() {
                                $('#product_add').find('.product_description').html('');
                                $('#product_price').val('');
                                $('#finalprice').val('');
                                $('#addonprice').val('');
                                $('#dishcount').val(1);
                            },
                            success: function(obj) {
                                var data = JSON.parse(obj);
                                if (data.response == "true") {
                                    $('#product_add').find('.product_name').html(data.dish_details.product_en_name);
                                    $('#product_add').find('#dish_id').val(data.dish_details.product_id);
                                    $('#product_add').find('#dishsubmit').attr('onclick', 'formsubmit()');
                                    html += '<p>';
                                    html += (data.dish_details.en_description != null) ? data.dish_details.en_description : +'';
                                    html += '</p>';
                                    var ri = 0;
                                    var ci = 0;
                                    for (var i = 1; i <= Object.keys(data.dish_details.choiceCategory).length; i++) {
                                        if (data.dish_details.choiceCategory[i].is_multiple == 0) {
                                            html += '<div class="cus_radio">';
                                            html += '<div class="top_tit">';
                                            html += '<span><i class="alerticon' + (ri + 1) + ' fa fa-info-circle"></i><i class="successicon' + i + ' fa fa-check-circle"></i>  ' + data.dish_details.choiceCategory[i].category_name + '</span> <p>' + required + '</p>';
                                            html += '<input type="hidden"  name="radio-text' + ri + '">';
                                            html += '</div><ul>';
                                            for (var ky in data.dish_details.choiceCategory[i].choice) {
                                                html += '<li>';
                                                html += '<input type="radio" price="' + data.dish_details.choiceCategory[i].choice[ky].price + '" id="dish' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '" value="' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '" name="radio-group' + ri + '">';
                                                html += '<label for="dish' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '">' + data.dish_details.choiceCategory[i].choice[ky].choice_name + '</label>';
                                                html += '<p style="float: right; font-size: 15px; color:#000;">';
                                                html += data.dish_details.choiceCategory[i].choice[ky].price == 0 ? '' : '+' + parseFloat(data.dish_details.choiceCategory[i].choice[ky].price).toFixed(3) + ' KD' + '</p>';
                                                html += '</li>';
                                            }
                                            html += '</ul></div>';
                                            ri++;
                                        } else {
                                            html += '<div class="cus_checkbox">';
                                            html += '<div class="top_tit">';
                                            html += '<span>' + data.dish_details.choiceCategory[i].category_name + '</span>';
                                            html += '<input type="hidden"  name="chbox_' + ci + '">';
                                            html += '</div><ul>';
                                            for (var ky in data.dish_details.choiceCategory[i].choice) {
                                                html += '<li>';
                                                html += '<label class="checkbox_custom">' + data.dish_details.choiceCategory[i].choice[ky].choice_name;
                                                html += '<input type="checkbox" class="chociecheck" price="' + data.dish_details.choiceCategory[i].choice[ky].price + '" name="chociecheck_' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '" id="chociecheck' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '">';
                                                html += '<span class="checkmark"></span>';
                                                html += '<p style="float: right; font-size: 15px; color:#000;">';
                                                html += data.dish_details.choiceCategory[i].choice[ky].price == 0 ? '' : '+' + parseFloat(data.dish_details.choiceCategory[i].choice[ky].price).toFixed(3) + ' KD' + '</p>';
                                                html += '</label>';
                                            }
                                            ci++;
                                        }
                                    }
                                    html += '<div class="pr_quantity">';
                                    html += '<div class="pr_quantity_in">';
                                    html += '<div class="stepper-widget stepperpopup1">';
                                    html += '<button type="button" class="js-qty-down btn btn-primary">-</button>';
                                    html += '<input type="text" class="js-qty-input form-control"  value="1" />';
                                    html += '<input type="hidden" id="stepper_val" value="1" />';
                                    html += '<input type="hidden" id="catPrice1" value="0" />';
                                    html += '<input type="hidden" id="catPrice2" value="0" />';
                                    html += '<input type="hidden" id="catPrice3" value="0" />';
                                    html += '<button type="button" class="js-qty-up btn btn-danger">+</button>';
                                    html += '</div></div></div>';
                                    $('#product_add').find('.product_description').html(html);
                                    $('#product_add').find('.finalprice').html(parseFloat(data.dish_details.price).toFixed(3) + ' KD');
                                    $('#product_add').find('#product_price').val(parseFloat(data.dish_details.price).toFixed(3));
                                    $('#product_add').find('#finalprice').val(parseFloat(data.dish_details.price).toFixed(3));
                                    $('#product_add').find('#finalprice').attr('prePrice', parseFloat(data.dish_details.price).toFixed(3));
                                    $('.stepper-widget').stepper();
                                }
                            },
                            complete: function() {}
                        });
                    },
                });
            } else {
                html += '<div class="order_top"><div class="delivered no_Order">';
                html += noDishFound;
                html += '</div></div></div>';
                $("#loading-div-background").hide();
                $('.list_dish_category_wise').html(html);
            }
        },
        complete: function() {}
    });
}

function getFavouriteDishesMobile(locality = "") {
    $("#loading-div-background").show();
    $.ajax({
        type: 'POST',
        url: favouriteDioshes + "/" + locality,
        data: {
            type: 'groupby'
        },
        beforeSend: function() {},
        success: function(obj) {
            var loginData = getCookie();
            var data = JSON.parse(obj);
            var html = "";
            html += '<input type="hidden" id="restaurant_id" value="' + locality + '">';
            html += '<div class="dish_list">';
            if (data.response == "true") {
                
                var i = 0;
                if (data.favouriteDish == undefined) {
                    html += '<div class="order_top"><div class="delivered no_Order">';
                    html += noDishFound;
                    html += '</div></div>';
                } else {
                    var count = data.dishesData.dishes;
                    var j = 0;
                    $.each(data.dishesData.dishes, function(key, value) {
                        if (count != 0 && value.is_favourite == "1") {
                            if (j % 2 == 0) {
                                html += '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padleft0 favourite' + value.product_id + '">';
                            } else {
                                html += '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padright0 favourite' + value.product_id + '"">';
                            }
                            j++;
                            html += '<div class="dish_box mobi_layout">';
                            html += '<div class="dish_im">';
                            html += '<img src="';
                            html += value.dish_image ? baseurl + 'assets/images/front-end/dishes/' + value.dish_image : baseurl + 'assets/images/front-end/dishes/no_image.png';
                            html += '" alt="">';
                            html += '</div><div class="dish_des"><div class="dish_des_left"><h4>';
                            html += value.product_en_name ? value.product_en_name : '';
                            html += '</h4><p>';
                            html += value.en_description ? value.en_description : '';
                            html += '</p>';
                            html += '<ul is_favourite="1" dish_id ="' + value.product_id + '">';
                            if (data.dishRating == undefined) {
                                html += showStarRate();
                            } else if (data.dishRating[value.product_id] == undefined) {
                                html += showStarRate();
                            } else {
                                for (k = 1; k < 6; k++) {
                                    html += '<li><a  index="' + k + '" href="javascript:void(0)">';
                                    if (data.dishRating[value.product_id] >= k) {
                                        html += '<img src="' + baseurl + 'assets/images/front-end/icon/star.png" align="">';
                                    } else {
                                        html += '<img src="' + baseurl + 'assets/images/front-end/icon/star_grey.png" align="">';
                                    }
                                    html += '</a></li>';
                                }
                            }
                            html += '</ul>';
                            html += '<div class="favorite_icon removeFavourite" dish_id="' + value.product_id + '" is_favourite=""><a href="javascript:void(0)" title="Remove from favourite" ><h3>X</h3></a></div>';
                            html += '</div>';
                            html += '<div class="dish_des_right">';
                            if (value.in_locality == "1") {
                                html += '<span>';
                                if (value.choices.length > 0) {
                                    html += PriceOnSelection;
                                } else {
                                    html += parseFloat(value.dish_price).toFixed(3) + 'KD';
                                    html += "<br>";
                                }
                                html += '</span>';
                                var option = (value.choices.length > 0) ? '"#product_add"' : 'javascript:void(0)';
                                var addoption = (value.choices.length > 0) ? '' : 'addcart';
                                html += '<a href=' + option + ' dish_id="' + value.product_id + '" res_id="' + locality + '" class="modalitypopup ' + addoption + '" aria-label="open"><i class="fa fa-plus" aria-hidden="true"></i></a>';
                            } else {
                                html += '<a href="javascript:void(0)"  class="modalitypopup notInLocality" aria-label="open"><i class="fa fa-plus" aria-hidden="true"></i></a>';
                            }
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                        }
                    });
                }
                html += '</div>';
                $("#loading-div-background").hide();
                $('.accordion').html(html);
                var dish_id = 0;
                $(".modalitypopup").click(function() {
                    dish_id = $(this).attr('dish_id');
                    res_id = $(this).attr('res_id');
                });
                addproduct = Modality.init('#product_add', {
                    effect: 'slide-up',
                    onOpen: function(e) {
                        var html = '';
                        $.ajax({
                            type: 'POST',
                            url: getdishchoice,
                            data: {
                                dish_id: dish_id
                            },
                            beforeSend: function() {
                                $('#product_add').find('.product_description').html('');
                                $('#product_price').val('');
                                $('#finalprice').val('');
                                $('#addonprice').val('');
                                $('#dishcount').val(1);
                            },
                            success: function(obj) {
                                var data = JSON.parse(obj);
                                if (data.response == "true") {
                                    $('#product_add').find('.product_name').html(data.dish_details.product_en_name);
                                    $('#product_add').find('#dish_id').val(data.dish_details.product_id);
                                    $('#product_add').find('#dishsubmit').attr('onclick', 'formsubmit()');
                                    html += '<p>';
                                    html += (data.dish_details.en_description != null) ? data.dish_details.en_description : +'';
                                    html += '</p>';
                                    var ri = 0;
                                    var ci = 0;
                                    for (var i = 1; i <= Object.keys(data.dish_details.choiceCategory).length; i++) {
                                        if (data.dish_details.choiceCategory[i].is_multiple == 0) {
                                            html += '<div class="cus_radio">';
                                            html += '<div class="top_tit">';
                                            html += '<span><i class="alerticon' + (ri + 1) + ' fa fa-info-circle"></i><i class="successicon' + i + ' fa fa-check-circle"></i>  ' + data.dish_details.choiceCategory[i].category_name + '</span> <p>' + required + '</p>';
                                            html += '<input type="hidden"  name="radio-text' + ri + '">';
                                            html += '</div><ul>';
                                            for (var ky in data.dish_details.choiceCategory[i].choice) {
                                                html += '<li>';
                                                html += '<input type="radio" price="' + data.dish_details.choiceCategory[i].choice[ky].price + '" id="dish' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '" value="' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '" name="radio-group' + ri + '">';
                                                html += '<label for="dish' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '">' + data.dish_details.choiceCategory[i].choice[ky].choice_name + '</label>';
                                                html += '<p style="float: right; font-size: 15px; color:#000;">';
                                                html += data.dish_details.choiceCategory[i].choice[ky].price == 0 ? '' : '+' + parseFloat(data.dish_details.choiceCategory[i].choice[ky].price).toFixed(3) + ' KD' + '</p>';
                                                html += '</li>';
                                            }
                                            html += '</ul></div>';
                                            ri++;
                                        } else {
                                            html += '<div class="cus_checkbox">';
                                            html += '<div class="top_tit">';
                                            html += '<span>' + data.dish_details.choiceCategory[i].category_name + '</span>';
                                            html += '<input type="hidden"  name="chbox_' + ci + '">';
                                            html += '</div><ul>';
                                            for (var ky in data.dish_details.choiceCategory[i].choice) {
                                                html += '<li>';
                                                html += '<label class="checkbox_custom">' + data.dish_details.choiceCategory[i].choice[ky].choice_name;
                                                html += '<input type="checkbox" class="chociecheck" price="' + data.dish_details.choiceCategory[i].choice[ky].price + '" name="chociecheck_' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '" id="chociecheck' + data.dish_details.choiceCategory[i].choice[ky].choice_id + '">';
                                                html += '<span class="checkmark"></span>';
                                                html += '<p style="float: right; font-size: 15px; color:#000;">';
                                                html += data.dish_details.choiceCategory[i].choice[ky].price == 0 ? '' : '+' + parseFloat(data.dish_details.choiceCategory[i].choice[ky].price).toFixed(3) + ' KD' + '</p>';
                                                html += '</label>';
                                            }
                                            ci++;
                                        }
                                    }
                                    html += '<div class="pr_quantity">';
                                    html += '<div class="pr_quantity_in">';
                                    html += '<div class="stepper-widget stepperpopup1">';
                                    html += '<button type="button" class="js-qty-down btn btn-primary">-</button>';
                                    html += '<input type="text" class="js-qty-input form-control"  value="1" />';
                                    html += '<input type="hidden" id="stepper_val" value="1" />';
                                    html += '<input type="hidden" id="catPrice1" value="0" />';
                                    html += '<input type="hidden" id="catPrice2" value="0" />';
                                    html += '<input type="hidden" id="catPrice3" value="0" />';
                                    html += '<button type="button" class="js-qty-up btn btn-danger">+</button>';
                                    html += '</div></div></div>';
                                    $('#product_add').find('.product_description').html(html);
                                    $('#product_add').find('.finalprice').html(parseFloat(data.dish_details.price).toFixed(3) + ' KD');
                                    $('#product_add').find('#product_price').val(parseFloat(data.dish_details.price).toFixed(3));
                                    $('#product_add').find('#finalprice').val(parseFloat(data.dish_details.price).toFixed(3));
                                    $('#product_add').find('#finalprice').attr("prePrice", parseFloat(data.dish_details.price).toFixed(3));
                                    $('.stepper-widget').stepper();
                                }
                            },
                            complete: function() {}
                        });
                    },
                });
            } else {
                html += '<div class="order_top"><div class="delivered no_Order">';
                html += noDishFound;
                html += '</div></div></div>';
                $("#loading-div-background").hide();
                $('.accordion').html(html);
            }
        },
        complete: function() {}
    });
}

$(document).on("click", ".dishRating", function() {
    var userData = getCookie("user_id");
    var rate = $(this).attr("index");
    var dishId = $(this).parent().parent().attr("dish_id");
    var is_favourite = $(this).parent().parent().attr("is_favourite");
    if (userData.user_id == undefined) {
        customtoastr(userNotLogin, 'errorshow');
    } else {
        $.post(addDishRating, {
            rating: rate,
            dish_id: dishId,
            user_id: userData.user_id
        }, function(data) {
            var obj = $.parseJSON(data);
            if (obj.success == 1) {
                if (is_favourite == "1") {
                    getFavouriteDishes(userData.restaurant_id);
                } else {
                    getMainDishes(userData.restaurant_id);
                }
            } else {
                customtoastr(tryAgain, 'errorshow');
            }
        });
    }
});

setInterval(function() {
    var dishDetails = getCookie('dishDetail');
    if (dishDetails.dishDetail == undefined || dishDetails.dishDetail == "[]") {
        $(".badge").hide();
    } else {
        var total = $.parseJSON(dishDetails.dishDetail);
        if (total.length > 0) {
            $(".badge").show();
            $(".badge").text(total.length);
            return false;
        }
    }
}, 1000);

$(document).on('click', '.notInLocality', function() {
    customtoastr(dishNotAvailable, 'errorshow');
});

$(document).on('stepperupdate', '.stepperpopup1', function(ev, data) {
    var total = $("#finalprice").val();
    var dish_count = data.value;
    var finalprice = dish_count * (parseFloat(total));
    $('#product_add').find('.finalprice').html(parseFloat(finalprice).toFixed(3) + ' KD');
    $("#dishcount").val(dish_count);
});
$(document).on("change", "input[name='radio-group0'],input[name='radio-group1'],.chociecheck", function() {
    totalInBag();
})

function totalInBag() {
    var ch = $("input[name='radio-group0']");
    var chprice0 = 0;
    var chprice1 = 0;
    var mulprice = 0;
    var finalprice = $("#finalprice").attr('prePrice');
    var ch0 = $("input[name='radio-group0']");
    var ch1 = $("input[name='radio-group1']");
    var mulch = $(".chociecheck");
    if (ch0.length > 0) {
        $.each(ch0, function(k, v) {
            if ($(v).is(":checked")) {
                chprice0 = $(this).attr('price');
            }
        })
    }
    if (ch1.length > 0) {
        $.each(ch1, function(k, v) {
            if ($(v).is(":checked")) {
                chprice1 = $(this).attr('price');
            }
        })
    }
    if (mulch.length > 0) {
        $.each(mulch, function(k, v) {
            if ($(v).is(":checked")) {
                mulprice = parseFloat(mulprice) + parseFloat($(this).attr('price'));
            }
        })
    }
    var total = parseFloat(chprice0) + parseFloat(chprice1) + parseFloat(mulprice) + parseFloat(finalprice);
    total = parseFloat(total).toFixed(3);
    $("#finalprice").val(total);
    $(".finalprice").html(total + " KD");
}

function showStarRate() {
    var html = "";
    for (var i = 1; i < 6; i++) {
        html += '<li><a class="dishRating" index="' + i + '" href="javascript:void(0)"><img src="' + baseurl + 'assets/images/front-end/icon/star_grey.png" align=""></a></li>';
    }
    return html;
}

function setMainDishlistData(data, best = 0) {
    var html = '';
    var loginData = getCookie();
    var ri = 0;

    if (best == "1") {
        html += '<div class="dishes_box" id="1">';
        html += '<div class="dish_list_title">';
        html += '<div class="container">';
        html += '<div class="row" id="00">';
        html += '<h3>' + bestDish + '</h3>';
        html += '</div></div></div>';
        html += '<div class="dish_list">';
        html += '<div class="container">';
        $.each(data.category, function(key, value) {
            var count = data.category[key].dishes.length;
            if (count != 0) {
                for (var j = 0; j < count; j++) {
                    if (data.category[key].dishes[j].is_best_dishes == "1") {
                        if (ri % 2 == 0) {
                            html += '<div class="row">';
                            html += '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padleft0">';
                        } else {
                            html += '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padright0">';
                        }
                        html += '<div class="dish_box">';
                        html += '<div class="dish_im">';
                        html += '<img src="';
                        html += data.category[key].dishes[j].dish_image ? baseurl + 'assets/images/front-end/dishes/' + data.category[key].dishes[j].dish_image : baseurl + 'assets/images/front-end/dishes/no_image.png';
                        html += '" alt="">';
                        html += '</div>';
                        html += '<div class="dish_des"><h4>';
                        html += '<h4>' + data.category[key].dishes[j].product_en_name ? data.category[key].dishes[j].product_en_name : '';
                        html += '</h4>';
                        html += '<p>';
                        html += data.category[key].dishes[j].en_description ? data.category[key].dishes[j].en_description : '';
                        html += '</p>';
                        html += '<ul is_favourite="0" dish_id ="' + data.category[key].dishes[j].product_id + '">';
                        if (data.dishRating == undefined) {
                            html += showStarRate();
                        } else if (data.dishRating[data.category[key].dishes[j].product_id] == undefined) {
                            html += showStarRate();
                        } else {
                            for (k = 1; k < 6; k++) {
                                html += '<li><a  index="' + k + '" href="javascript:void(0)">';
                                if (data.dishRating[data.category[key].dishes[j].product_id] >= k) {
                                    html += '<img src="' + baseurl + 'assets/images/front-end/icon/star.png" align="">';
                                } else {
                                    html += '<img src="' + baseurl + 'assets/images/front-end/icon/star_grey.png" align="">';
                                }
                                html += '</a></li>';
                            }
                        }
                        html += '</ul>';
                        html += '<span>';
                        if (data.category[key].dishes[j].choices.length > 0) {
                            html += PriceOnSelection;
                        } else {
                            html += parseFloat(data.category[key].dishes[j].resDishPrice).toFixed(3) + 'KD';
                            html += "<br>";
                        }
                        html += '</span>';
                        var option = (data.category[key].dishes[j].choices.length > 0) ? '"#product_add"' : 'javascript:void(0)';
                        var addoption = (data.category[key].dishes[j].choices.length > 0) ? '' : 'addcart';
                        html += '<a href=' + option + ' dish_id="' + data.category[key].dishes[j].product_id + '" res_id="' + locality + '" class="modalitypopup ' + addoption + '" aria-label="open">' + ADD + ' <i class="fa fa-plus" aria-hidden="true"></i></a>';
                        if (loginData.user_id != undefined) {
                            if (data.category[key].dishes[j].is_favourite == '1') {
                                html += '<div class="favorite_icon" dish_id="' + data.category[key].dishes[j].product_id + '" is_favourite="1"><a href="javascript:void(0)" ><img src="' + baseurl + 'assets/images/front-end/heart.png" alt="" title="Remove from favourite" ></a></div>';
                            } else {
                                html += '<div class="favorite_icon" dish_id="' + data.category[key].dishes[j].product_id + '" is_favourite=""><a href="javascript:void(0)" ><img src="' + baseurl + 'assets/images/front-end/heart_grey.png" alt="" title="Add to  favourite" ></a></div>';
                            }
                        }
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        if (ri % 2 != 0) {
                            html += '</div>';
                        }
                        ri++;
                    }
                }
            }
        });
        html += '</div></div></div>';
    } else {
        $.each(data.category, function(key, value) {
            var count = data.category[key].dishes.length;
            if (count != 0) {
                html += '<div class="dishes_box" id="' + data.category[key].category_id + '">';
                html += '<div class="dish_list_title">';
                html += '<div class="container">';
                html += '<div class="row" id="' + data.category[key].category_id + '">';
                html += '<h3>' + data.category[key].category_name + '</h3>';
                html += '</div></div></div>';
                html += '<div class="dish_list">';
                html += '<div class="container">';
                for (var j = 0; j < count; j++) {
                    if (j % 2 == 0) {
                        html += '<div class="row">';
                    }
                    if (j % 2 == 0) {
                        html += '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padleft0">';
                    } else {
                        html += '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padright0">';
                    }
                    html += '<div class="dish_box">';
                    html += '<div class="dish_im">';
                    html += '<img src="';
                    html += data.category[key].dishes[j].dish_image ? baseurl + 'assets/images/front-end/dishes/' + data.category[key].dishes[j].dish_image : baseurl + 'assets/images/front-end/dishes/no_image.png';
                    html += '" alt="">';
                    html += '</div><div class="dish_des"><h4>';
                    html += data.category[key].dishes[j].product_en_name ? data.category[key].dishes[j].product_en_name : '';
                    html += '</h4><p>';
                    html += data.category[key].dishes[j].en_description ? data.category[key].dishes[j].en_description : '';
                    html += '</p>';
                    html += '<ul is_favourite="0" dish_id ="' + data.category[key].dishes[j].product_id + '">';
                    if (data.dishRating == undefined) {
                        html += showStarRate();
                    } else if (data.dishRating[data.category[key].dishes[j].product_id] == undefined) {
                        html += showStarRate();
                    } else {
                        for (k = 1; k < 6; k++) {
                            html += '<li><a  index="' + k + '" href="javascript:void(0)">';
                            if (data.dishRating[data.category[key].dishes[j].product_id] >= k) {
                                html += '<img src="' + baseurl + 'assets/images/front-end/icon/star.png" align="">';
                            } else {
                                html += '<img src="' + baseurl + 'assets/images/front-end/icon/star_grey.png" align="">';
                            }
                            html += '</a></li>';
                        }
                    }
                    html += '</ul>';
                    html += '<span>';
                    if (data.category[key].dishes[j].choices.length > 0) {
                        html += PriceOnSelection;
                    } else {
                        html += parseFloat(data.category[key].dishes[j].resDishPrice).toFixed(3) + 'KD';
                        html += "<br>";
                    }
                    html += '</span>';
                    var option = (data.category[key].dishes[j].choices.length > 0) ? '"#product_add"' : 'javascript:void(0)';
                    var addoption = (data.category[key].dishes[j].choices.length > 0) ? '' : 'addcart';
                    html += '<a href=' + option + ' dish_id="' + data.category[key].dishes[j].product_id + '" res_id="' + locality + '" class="modalitypopup ' + addoption + '" aria-label="open">' + ADD + ' <i class="fa fa-plus" aria-hidden="true"></i></a>';
                    if (loginData.user_id != undefined) {
                        if (data.category[key].dishes[j].is_favourite == '1') {
                            html += '<div class="favorite_icon" dish_id="' + data.category[key].dishes[j].product_id + '" is_favourite="1"><a href="javascript:void(0)" ><img src="' + baseurl + 'assets/images/front-end/heart.png" alt="" title="Remove from favourite" ></a></div>';
                        } else {
                            html += '<div class="favorite_icon" dish_id="' + data.category[key].dishes[j].product_id + '" is_favourite=""><a href="javascript:void(0)" ><img src="' + baseurl + 'assets/images/front-end/heart_grey.png" alt="" title="Add to  favourite" ></a></div>';
                        }
                    }
                    html += '</div></div></div>';
                    if (j % 2 != 0) {
                        html += '</div>';
                    }
                }
                if (j % 2 != 0) {
                    html += '</div>';
                }
                html += '</div>';
                html += '</div></div></div>';
            }
        });
    }
    return html;
}

function setMobiDishlistData(data, best = 0) {
    var html = "";
    var ri = 0;
    var loginData = getCookie();
    if (best == "1") {
        html += '<h1>' + bestDish + '</h1>';
        html += '<div>';
        html += '<div class="dish_list">';
        $.each(data.category, function(key, value) {
            var count = data.category[key].dishes.length;
            if (count != 0) {
                for (var j = 0; j < count; j++) {
                    if (data.category[key].dishes[j].is_best_dishes == "1") {
                        if (ri % 2 == 0) {
                            html += '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padleft0">';
                        } else {
                            html += '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padright0">';
                        }
                        html += '<div class="dish_box mobi_layout">';
                        html += '<div class="dish_im">';
                        html += '<img src="';
                        html += data.category[key].dishes[j].dish_image ? baseurl + 'assets/images/front-end/dishes/' + data.category[key].dishes[j].dish_image : baseurl + 'assets/images/front-end/dishes/no_image.png';
                        html += '" alt="">';
                        html += '</div>';
                        html += '<div class="dish_des">';
                        html += '<div class="dish_des_left">';
                        html += '<h4>';
                        html += data.category[key].dishes[j].product_en_name ? data.category[key].dishes[j].product_en_name : '';
                        html += '</h4>';
                        html += '<p>';
                        html += data.category[key].dishes[j].en_description ? data.category[key].dishes[j].en_description : '';
                        html += '</p>';
                        html += '<ul is_favourite="0" dish_id ="' + data.category[key].dishes[j].product_id + '">';
                        if (data.dishRating == undefined) {
                            html += showStarRate();
                        } else if (data.dishRating[data.category[key].dishes[j].product_id] == undefined) {
                            html += showStarRate();
                        } else {
                            for (k = 1; k < 6; k++) {
                                html += '<li><a  index="' + k + '" href="javascript:void(0)">';
                                if (data.dishRating[data.category[key].dishes[j].product_id] >= k) {
                                    html += '<img src="' + baseurl + 'assets/images/front-end/icon/star.png" align="">';
                                } else {
                                    html += '<img src="' + baseurl + 'assets/images/front-end/icon/star_grey.png" align="">';
                                }
                                html += '</a></li>';
                            }
                        }
                        html += '</ul>';
                        if (loginData.user_id != undefined) {
                            if (data.category[key].dishes[j].is_favourite == '1') {
                                html += '<div class="favorite_icon" dish_id="' + data.category[key].dishes[j].product_id + '" is_favourite="1"><a href="javascript:void(0)" ><img src="' + baseurl + 'assets/images/front-end/heart.png" alt="" title="Remove from favourite" ></a></div>';
                            } else {
                                html += '<div class="favorite_icon" dish_id="' + data.category[key].dishes[j].product_id + '" is_favourite=""><a href="javascript:void(0)" ><img src="' + baseurl + 'assets/images/front-end/heart_grey.png" alt="" title="Add to  favourite" ></a></div>';
                            }
                        }
                        html += '</div>';
                        html += '<div class="dish_des_right">';
                        html += '<span>';
                        if (data.category[key].dishes[j].choices.length > 0) {
                            html += PriceOnSelection;
                        } else {
                            html += parseFloat(data.category[key].dishes[j].resDishPrice).toFixed(3) + 'KD';
                            html += "<br>";
                        }
                        html += '</span>';
                        var option = (data.category[key].dishes[j].choices.length > 0) ? '"#product_add"' : 'javascript:void(0)';
                        var addoption = (data.category[key].dishes[j].choices.length > 0) ? '' : 'addcart';
                        html += '<a href=' + option + ' dish_id="' + data.category[key].dishes[j].product_id + '" res_id="' + locality + '" class="modalitypopup ' + addoption + '" aria-label="open"><i class="fa fa-plus" aria-hidden="true"></i></a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        ri++;
                    }
                }
            }
        });
        html += '</div>';
        html += '</div>';
    } else {
        $.each(data.category, function(key, value) {
            var count = data.category[key].dishes.length;
            if (count != 0) {
                html += '<h1>' + data.category[key].category_name + '</h1>';
                html += '<div>';
                html += '<div class="dish_list">';
                for (var j = 0; j < count; j++) {
                    if (j % 2 == 0) {
                        html += '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padleft0">';
                    } else {
                        html += '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padright0">';
                    }
                    html += '<div class="dish_box mobi_layout">';
                    html += '<div class="dish_im">';
                    html += '<img src="';
                    html += data.category[key].dishes[j].dish_image ? baseurl + 'assets/images/front-end/dishes/' + data.category[key].dishes[j].dish_image : baseurl + 'assets/images/front-end/dishes/no_image.png';
                    html += '" alt="">';
                    html += '</div>';
                    html += '<div class="dish_des">';
                    html += '<div class="dish_des_left">';
                    html += '<h4>';
                    html += data.category[key].dishes[j].product_en_name ? data.category[key].dishes[j].product_en_name : '';
                    html += '</h4>';
                    html += '<p>';
                    html += data.category[key].dishes[j].en_description ? data.category[key].dishes[j].en_description : '';
                    html += '</p>';
                    html += '<ul is_favourite="0" dish_id ="' + data.category[key].dishes[j].product_id + '">';
                    if (data.dishRating == undefined) {
                        html += showStarRate();
                    } else if (data.dishRating[data.category[key].dishes[j].product_id] == undefined) {
                        html += showStarRate();
                    } else {
                        for (k = 1; k < 6; k++) {
                            html += '<li><a  index="' + k + '" href="javascript:void(0)">';
                            if (data.dishRating[data.category[key].dishes[j].product_id] >= k) {
                                html += '<img src="' + baseurl + 'assets/images/front-end/icon/star.png" align="">';
                            } else {
                                html += '<img src="' + baseurl + 'assets/images/front-end/icon/star_grey.png" align="">';
                            }
                            html += '</a></li>';
                        }
                    }
                    html += '</ul>';
                    if (loginData.user_id != undefined) {
                        if (data.category[key].dishes[j].is_favourite == '1') {
                            html += '<div class="favorite_icon" dish_id="' + data.category[key].dishes[j].product_id + '" is_favourite="1"><a href="javascript:void(0)" ><img src="' + baseurl + 'assets/images/front-end/heart.png" alt="" title="Remove from favourite" ></a></div>';
                        } else {
                            html += '<div class="favorite_icon" dish_id="' + data.category[key].dishes[j].product_id + '" is_favourite=""><a href="javascript:void(0)" ><img src="' + baseurl + 'assets/images/front-end/heart_grey.png" alt="" title="Add to  favourite" ></a></div>';
                        }
                    }
                    html += '</div>';
                    html += '<div class="dish_des_right">';
                    html += '<span>';
                    if (data.category[key].dishes[j].choices.length > 0) {
                        html += PriceOnSelection;
                    } else {
                        html += parseFloat(data.category[key].dishes[j].resDishPrice).toFixed(3) + 'KD';
                        html += "<br>";
                    }
                    html += '</span>';
                    var option = (data.category[key].dishes[j].choices.length > 0) ? '"#product_add"' : 'javascript:void(0)';
                    var addoption = (data.category[key].dishes[j].choices.length > 0) ? '' : 'addcart';
                    html += '<a href=' + option + ' dish_id="' + data.category[key].dishes[j].product_id + '" res_id="' + locality + '" class="modalitypopup ' + addoption + '" aria-label="open"><i class="fa fa-plus" aria-hidden="true"></i></a>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                }
                html += '</div>';
                html += '</div>';
            }
        });
    }
    return html;
}