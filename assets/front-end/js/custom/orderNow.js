$(document).ready(function(){
    var coockie  =getCookie();
    var locality =coockie.locality_id;
    showDish(locality);
    showCart();
}); 
  
//show dishes and its category
function showDish(locality,search="")
{
    $.ajax({
        type: 'POST',
        url: dishDataUrl,
        data: {locality:locality,search:search},
        success: function(response) {
            var obj      =$.parseJSON(response);
            var coockie  =getCookie();
            var lang     =coockie.lang;
            var category = "";
            var dishes   = "";

            //show dish category
            $.each(obj.category,function(key,val)
            {
                if(lang =="AR")
                {
                    categoryName =val.category_ar_name;
                }
                else{
                    categoryName =val.category_name;
                }
                category +='<a href="#tab'+val.category_id+'"><li rel="tab'+val.category_id+'">'+categoryName+'</li></a>';
                
                dishes +='<div id="tab'+val.category_id+'" class="tabClickcontent">';
                dishes +='<h3 class="tab_drawer_heading" rel="tab'+val.category_id+'" >'+categoryName+'</h3>';
                dishes +='<div  class="tab_content">';
                if(obj.dishes[val.category_id])
                {
                    $.each(obj.dishes[val.category_id],function(dk,dv)
                    {

                        if(lang =="AR")
                        {
                            dishName    =dv.product_ar_name;
                            description =dv.ar_description;
                        }
                        else
                        {
                            dishName    =dv.product_en_name;
                            description =dv.en_description;
                        }

                        dishes +='<div class="productContent wow fadeIn" data-wow-duration="1s" data-wow-delay="0" style="visibility: visible; animation-duration: 2s; animation-name: fadeIn;">';
                       
                        dishes +='<a href="#" class="tooltip">';
                        if(dv.dish_image){
                          dishes +='<img src="'+dishImageUrl+dv.dish_image+'&w=302&h=226" alt="dishimaage" />';
                          dishes +='<span><img src="'+dishImageUrl+dv.dish_image+'&w=302&h=226" alt="dishimaage" /></span>';
                        }
                        else{
                            dishes +='<img src="'+dishImageUrl+dv.dish_image+'&w=302&h=226" alt="dishimaage" />'; 
                            dishes +='<span><img src="'+dishImageUrl+dv.dish_image+'&w=302&h=226" alt="dishimaage" /></span>';  
                        }
                        dishes +='</a>';

                        
                        dishes +='<div class="productnamedescription">';

                        dishes +='<h3 id="'+dv.product_id+'" >'+dishName+'</h3>';
                        if(description!=''){
                          dishes +='<p>'+description+'</p>';
                        }

                        dishes +='</div>';

                        if(dv.price > 0){
                            dishes +='<a id="iframe" href="#data" class="orderPopup addChoice" dishId="'+dv.product_id+'">'+apply+' '+parseFloat(dv.price).toFixed(3)+' KD</a>';
                        }else{
                            dishes +='<a id="iframe" href="#data" class="orderPopup addChoice" dishId="'+dv.product_id+'">'+price_on_selection+'</a>';
                        }
                        /*dishes += '<span>*'+termCondition+'</span>';*/
                        dishes +='</div>';
                        
                    })
                }
                

                dishes +='</div>';
                dishes +='</div>';
            })
          
            $("#category_tab").html(category);
            $("#dishes_tab").html(dishes);

            jQuery(".orderPopup").colorbox({
                inline:true, 
                width:"70%" ,
                
                onComplete : function() { 
                   $(this).colorbox.resize(); 
                } 

            });



           /* jQuery('ul.tabs a').click(function(e) {
              e.preventDefault();
              $('li').removeClass('activeLi');
              $(this).find('li').addClass('activeLi');
            });*/



            if (jQuery(window).width() > 800) {
              var stickySidebar3 = new StickySidebar('.tabs_wrapper', {
                  topSpacing: 100,
                  bottomSpacing: 20,
                  containerSelector: '.contentSection',
                  innerWrapperSelector: '.tabs'
              });
              } else{
              var stickySidebar3 = new StickySidebar('.tabs_wrapper', {
                  topSpacing: 0,
                  bottomSpacing: 20,
                  containerSelector: '.contentSection',
                  innerWrapperSelector: '.tabs'
                  });
              }


            /* smooth scroll tab 1*/
              
            /*  jQuery('ul.tabs a[href^="#"]').click(function(e) {
                  jQuery('html,body').animate({ scrollTop: (jQuery(this.hash).offset().top-100)}, 1000);   
                  return false;
                  e.preventDefault();
                });

                jQuery('ul.tabs a').click(function(e) {
                   e.preventDefault();
                    $('li').removeClass('activeLi');
                    $(this).find('li').addClass('activeLi');
                 });*/


            /* smooth scroll tab ends*/
             /* smooth scroll tab 2*/
            jQuery(document).ready(function() {
                     jQuery('ul.tabs a[href^="#"]').bind('click', function(e) {
                            e.preventDefault(); // prevent hard jump, the default behavior

                            var target = $(this).attr("href"); // Set the target as variable

                            // perform animated scrolling by getting top-position of target-element and set it as scroll target
                            jQuery('html,body').animate({ scrollTop: (jQuery(this.hash).offset().top-100)}, 1000);
                            return false;
                            e.preventDefault();
                    });
            });

            jQuery('ul.tabs a').click(function(e) {
                   e.preventDefault();
                    $('li').removeClass('activeLi');
                    $(this).find('li').addClass('activeLi');
            });

            $(window).scroll(function() {
                            $('.tabs li.activeLi').removeClass('activeLi');
                            var scrollDistance = $(window).scrollTop();
                            $('.tabClickcontent').each(function(i) {
                                    if ($(this).position().top <= scrollDistance) {
                                            $('.tabs li.active').removeClass('active');
                                            $('.tabs li').eq(i).addClass('active');
                                    }
                            });
            }).scroll();

        /* smooth scroll tab ends*/


             
        }
    })
}

//search dishj functionality
$(document).on("keyup",'#searchBox',function(){
    var search =this.value;
    showDish(1,search);
})

//function for set side cart data 
function showCart()
{
    //var data        =JSON.stringify([{"id":1,"dishId":"225","choiceOfOne":["2"],"Multiplechoice":[],"dishcount":"1"},{"id":2,"dishId":"226","choiceOfOne":["14","3"],"Multiplechoice":[],"dishcount":"1"},{"id":3,"dishId":"445","choiceOfOne":["4"],"Multiplechoice":[],"dishcount":"1"}]);
    
    //document.cookie = "dishDetail="+data+"; expires=" + lastday + "; path=/";
    $.ajax({
        type:'post',
        url:getdishdetails,
        success:function(data)
        {
            var obj     =$.parseJSON(data);
            var html    ="";
            var summary ="";

            var total   =0.000;
            var charge  =0.000;
            var carthtml = '';
            
            if(obj.length >0)
            {
                $.each(obj,function(k,v){
                    var choice = '';
                    if(v.choice)
                    {
                        $.each(v.choice,function(ck,cv){
                            choice +=cv.choice_name+',';
                        })
                        choice = choice.replace(/(^,)|(,$)/g, "");
                    }


                    html   +='<div class="rightTwo">';
                    html   +='<div class="left">';
                    html   +='<div class="tooltip"><p title="'+v.instruction+'('+choice+')">'+v.dishname+'<i><img src="http://18.216.199.131/admin/assets/front-end/images/info.png" alt="" ></i></div>';
                    html   +='</p><br/>';
                    html   +='<div class="numbers-row">';
                    html   +='<input type="text" name="numId" id="numId" class="num'+v.id+'" value="'+v.dishcount+'" preDishCount="'+v.dishcount+'" dishId="'+v.dishid+'" dishPrice="'+parseFloat(v.price).toFixed(3)+'">';
                    html   +='</div>';
                    html   +='</div>';
                    html   +='<div class="right">';
                    html   +='<div class="tooltip">';
                    html   +='<a href="#minusDishData" class="minusDishpopUp inline" onclick="$.colorbox({href:\'#minusDishData\', inline:\'true\'});deleteCartItem('+v.id+')"><p> <span id="dishPricenum'+v.id+'" class="dishPrice'+v.id+'">'+parseFloat(parseFloat(v.price).toFixed(3) * (v.dishcount)).toFixed(3)+'</span> KD  <img class="minusDish" dish_id="'+v.dishid+'" src="http://18.216.199.131/admin/assets/front-end/images/minus.png" alt="" /> </p></a>';
                    html   +='</div>';
                    html   +='</div>';
                    html   +='</div>';
                    total  = parseFloat(total) + (parseFloat(v.price) * parseFloat(v.dishcount));
                    charge = parseFloat(v.delivery_charges).toFixed(3);

                    carthtml += '<div class="rightTwopopup rightTwopopup-cart"><div class="row11"><div class="left left-cart"><div class="numbers-row numbers-row-cart">';
                    carthtml += '<input type="text" name="numId" id="numId" class="num'+v.id+'" value="'+v.dishcount+'" preDishCount="'+v.dishcount+'" dishId="'+v.dishid+'" dishPrice="'+parseFloat(v.price).toFixed(3)+'"></div>';
                    carthtml += '<p>'+v.dishname+'</p></div><div class="right right-cart">';
                    carthtml += '<p class="dishPriceCartnum'+v.id+'">'+parseFloat(parseFloat(v.price).toFixed(3) * (v.dishcount)).toFixed(3)+' KD</p></div>';
                    carthtml += '<div class="order_cancle"><a href="#minusDishData" class="minusDishpopUp inline" onclick="$.colorbox({href:\'#minusDishData\', inline:\'true\'});deleteCartItem('+v.id+')"><img dish_id="226" dishid="2" class="minusDish" src="'+crossimg+'"></a></div></div><div class="clear"></div>';
                
                })

                carthtml += '<div class="row2"><div class="left left-cart"><p>Total</p></div><div class="right right-cart totalprice">';
                carthtml += '<p class="totalprice" id="totalprice"></p></div>';
                carthtml += '</div><div class="clear"></div><a class="check-out checkoutbutton">Checkout</a></div>';

                total = parseFloat(total).toFixed(3) ;
                $("#subtotal").text(parseFloat(total).toFixed(3));
                $("#deliveryCharge").text(parseFloat(charge).toFixed(3));
                
                total = (parseFloat(total)  + parseFloat(charge)).toFixed(3);

                var displayvalue = parseFloat(total).toFixed(3);
                $("#total").text(displayvalue);
                $(".totalprice").text(parseFloat(total).toFixed(3));

                //$(".cartPopup").html(carthtml);

                dishData ="<h3>"+cart+" : "+obj.length+" ITEMS <span class='cartprice'>  "+total+" KD</span></h3>";
            }
            else
            {
                dishData ="<h3>"+cart+" : 0 ITEMS <span class='cartprice'>  0.000 KD</span></h3>";

                $(".rightOne").html(dishData);
                carthtml = '';
                //$(".cartPopup").html("");
                $("#subtotal").text("0.000");
                $("#deliveryCharge").text("0.000");
                $("#total").text("0.000");
                
            }
            $(".rightOne").html(dishData);
            $(".itemCart").html(html);
            $(".cartPopup").html(carthtml);

            $("#totalprice").html(parseFloat(total).toFixed(3)+" KD");
            //console.log(carthtml);
            
            $(".totalOrderDown").html('Order Total : '+parseFloat(total).toFixed(3)+ ' KD');
            
            $("#cartCount").text(obj.length);
            $(".cartCountDown").text(obj.length);
            jQuery(".minusDishpopUp").colorbox({inline:true });
            stapper(".numbers-row");
        }
    })
}

//It is used for dish count  and manage cookie and side cart
jQuery(document).ready(function(){

    jQuery(document).on("keyup","input[name=numId]",function()
    {
        setItem($(this));
        var finalPrice = $("#finalPrice").val();
        priceChange(finalPrice);
    });

    jQuery('.cartPopup').on("click",".stapper",function()
    {
        if($(this).parent().find("input[name=numId]").val() == 0){
            $(this).parent().find("input[name=numId]").val("1");
            return false;
        }
        setItem($(this).parent().find("input[name=numId]"));

        var finalPrice = $("#finalPrice").val();
        priceChange(finalPrice);
    })
    jQuery('.openPopupAdd').on("click",".stapper",function()
    {
        if($(this).parent().find("input[name=numId]").val() == 0){
            $(this).parent().find("input[name=numId]").val("1");
            return false;
        }
        setItem($(this).parent().find("input[name=numId]"));

        var finalPrice = $("#finalPrice").val();
        priceChange(finalPrice);
    })
    jQuery('#sidebar').on("click",".stapper",function()
    {
        if($(this).parent().find("input[name=numId]").val() == 0){
            $(this).parent().find("input[name=numId]").val("1");
            return false;
        }
        setItem($(this).parent().find("input[name=numId]"));

        var finalPrice = $("#finalPrice").val();
        priceChange(finalPrice);
    })
});

jQuery('.cartPopup').on('click',".checkoutbutton",function(){

    $('.cartPopup').hide();

      var cookiedetail = getCookie('access_token');
      if(cookiedetail.access_token==undefined){
        $(".checkOut-Click").val("1");
        login.open();
      }
      else
      {
        var total = parseFloat($("#total").text()).toFixed(3);
        if(total > 0){
            window.location.href = site_url+"Home/orderSummary";    
        }else{
            alert("Please select dish to proceed");
        }
      }  
});

jQuery('.buttonMob').on('click',".checkoutbuttonDown",function(){

    $('.cartPopup').hide();

      var cookiedetail = getCookie('access_token');
      if(cookiedetail.access_token==undefined){
        $(".checkOut-Click").val("1");
        login.open();
      }
      else
      {
        var total = parseFloat($("#total").text()).toFixed(3);
        if(total > 0){
            window.location.href = site_url+"Home/orderSummary";    
        }else{
            alert("Please select dish to proceed");
        }
      }  
});

/**
 * [priceChange Change the Final Price]
 * @author Hardik Ghadshi
 * @Created Date     2019-01-29T20:41:26+0530
 * @param   {[type]} price                    [description]
 * @return  {[type]}                          [description]
 */
function priceChange(price){

    var dishCount = $(".numDishId").attr("predishcount");

    if(typeof dishCount == "undefined"){
        dishCount = $(".numDishId").val();
    }

    $("#selectedPrice").val(price);

    //For radio button of both category
    if(($("input[name='category1']:checked"). attr("price") != 0) && (typeof $("input[name='category1']:checked"). attr("price") != "undefined")){   
        radioBtnPrice1 = $("input[name='category1']:checked"). attr("price");
    }else{
        radioBtnPrice1 = 0;
    }
    if(($("input[name='category2']:checked"). attr("price") != 0) && (typeof $("input[name='category2']:checked"). attr("price") != "undefined")){   
        radioBtnPrice2 = $("input[name='category2']:checked"). attr("price");
    }else{
        radioBtnPrice2 = 0;
    }

    var radioBtnPrice = parseFloat(radioBtnPrice1) + parseFloat(radioBtnPrice2);

    //If left radio button has no price and direct product price given
    if(radioBtnPrice == 0 && ($('#addOnPrice').val() == '')){
        radioBtnPrice = 1;
    }else{
        radioBtnPrice = radioBtnPrice;
    }

    //For single radio button group who have no price
    if(radioBtnPrice == 0 && ($('#addOnPrice').val() != '' && $('#addOnPrice').val() != 0)){
        radioBtnPrice =1;
    }

    var addonprice = $('#addOnPrice').val();
    addonprice = (addonprice != '' && addonprice != 0) ? addonprice : 1;

    

    //For Checkbox
    var mulch = $(".chociecheck");
    finalPrice = parseFloat(radioBtnPrice).toFixed(3) * parseFloat(dishCount).toFixed(3) * parseFloat(addonprice).toFixed(3);

    var mulprice = 0;
    if (mulch.length > 0) {
        $.each(mulch, function(k, v) {
            if ($(v).is(":checked")) {
                mulprice = parseFloat(mulprice) + parseFloat($(this).attr('price'));
            }
        })
    }
    if(mulprice>0){
        finalPrice = parseFloat(finalPrice) + (parseFloat(mulprice) * parseFloat(dishCount).toFixed(3));
    }

    $("#finalPrice").val(parseFloat(finalPrice).toFixed(3));
    $(".item_price").html(parseFloat(finalPrice).toFixed(3)+" KD");
}
//set item while changes in stapper or item count
function setItem(ele)
{
    var totalDish    =parseInt((ele.val() != '')?ele.val():1);
    //console.log(totalDish);
    var dishId       =ele.attr('dishId');
    var dishPrice    =parseFloat(ele.attr('dishPrice')).toFixed(3);
    var preDishCount =parseInt(ele.attr('preDishCount'));
    $('#dishCount').val(parseInt(totalDish));
    // $('.numId'+ele.attr("class")).val(totalDish);
    $("."+ele.attr("class")).val(totalDish);
    var total        =parseFloat($("#total").text());
    var charge       =parseFloat($("#deliveryCharge").text());
    var total        =parseFloat(total +(totalDish - preDishCount) * dishPrice).toFixed(3);
    $("#dishPrice"+ele.attr("class")).text(parseFloat(dishPrice*totalDish).toFixed(3));
    $(".dishPriceCart"+ele.attr("class")).text(parseFloat(dishPrice*totalDish).toFixed(3) + ' KD');
    $("#total").text(parseFloat(total).toFixed(3));
    $("#totalprice").text(parseFloat(total).toFixed(3) + ' KD');
    $("#subtotal").text(parseFloat(total -charge).toFixed(3));
    $(".cartprice").text(parseFloat(total).toFixed(3)+ ' KD');

    $(".totalOrderDown").html('Order Total : '+parseFloat(total).toFixed(3)+ ' KD');

    ele.attr('preDishCount',totalDish);

    var dishDetails     = getCookie('dishDetail');
    var OldDishDetails  = dishDetails.dishDetail;
    var disharr         = [];
    if(OldDishDetails!=undefined){
      disharr = JSON.parse(OldDishDetails);
      }
    for (var i = 0; i < disharr.length; i++) {
        if(disharr[i].dishId==dishId && "num"+disharr[i].id == ele.attr("class"))
        {
            disharr[i].dishcount = totalDish;
        }
    }
    dishDetails = JSON.stringify(disharr);
    document.cookie = "dishDetail="+dishDetails+"; expires="+lastday+"; path=/";
}

//remove dish form cart 
function deleteCartItem(id)
{

        $('#delete_dish_btn').unbind().on("click",function()
        {
            var dishDetails    = getCookie('dishDetail');
            var OldDishDetails = dishDetails.dishDetail;
            //console.log(dishId);
            if (OldDishDetails != undefined) 
            {
                disharr = $.parseJSON(OldDishDetails);

                for (var i = 0; i < disharr.length; i++) 
                {
                    if (disharr[i].id == id) 
                    {
                        var removeindex = disharr.indexOf(disharr[i]);
                        disharr.splice(removeindex, 1);
                    }
                }
                
                if (disharr.length == 0) 
                {
                    $(".itemCart").html("");
                    delete_cookie('dishDetail');
                    dishData ="<h3>"+cart+" : 0 ITEMS <span> 0.000 KD </span></h3>";
                    $(".rightOne").html(dishData);
                    $("#subtotal").text("0.000");
                    $("#deliveryCharge").text("0.000");
                    $("#total").text("0.000");
                }
                else
                {
                    dishDetails = JSON.stringify(disharr);
                    document.cookie = "dishDetail=" + dishDetails + "; expires=" + lastday + "; path=/";
                }
                showCart();
            }
            $.fn.colorbox.close();
        })

}

/*$("#cancel_delete_dish_btn").click(function(){
    $.fn.colorbox.close();
})*/

$(document).on("click",".addChoice",function(){
    
    var dishId   =$(this).attr('dishId');
    var coockie  =getCookie();
    var locality =coockie.locality_id;

    $.ajax({
        type:'POST',
        url:getDishChoice,
        data:{locality_id:locality,dish_id:dishId},
        success:function(response){
            var obj =$.parseJSON(response);
            if(obj.response == "true")
            {

                var rightPopupHTML = '';

                rightPopupHTML += '<p class="item_name">Chicken Wings (15 Pcs)</p>';
                rightPopupHTML += '<div class="numbers-row-inner">';
                rightPopupHTML += '<input type="text" name="numId" id="numId" class="numDishId" value="1">';
                rightPopupHTML += '</div>';
                $(".openPopupAdd").html(rightPopupHTML);
                stapper(".numbers-row-inner");

                // $("#ch_dish_name").text(obj.dish_details.product_en_name);
                $(".item_name").text(obj.dish_details.product_en_name);
                $(".item_price").text(parseFloat(obj.dish_details.price).toFixed(3)+" KD");
                $('#addOnPrice').val('');
                $('#dish_id').val(obj.dish_details.product_id);
                $(".numDishId").val('1');
                $("#instruction").val('');
                $(".numDishId").attr("dishPrice","0.000");
                $(".numDishId").attr("predishcount","1");

                var formHtml = '';
                    formHtml += '<input type="hidden" name="dish_id" id="dish_id" value="'+obj.dish_details.product_id+'"">';
                    
                for (var i = 1; i <= Object.keys(obj.dish_details.choiceCategory).length; i++) {
                    formHtml += "<h3><i class='alerticon"+i+" fa fa-info-circle'></i><i class='successicon"+i+" fa fa-check-circle'></i> "+obj.dish_details.choiceCategory[i].category_name+" <span>required</span></h3>";

                    if(obj.dish_details.choiceCategory[i].is_multiple == 0){
                        var catId = obj.dish_details.choiceCategory[i].category_id;
                        for(var j = 0; j < obj.dish_details.choiceCategory[i].choice['length']; j++)
                        {
                            formHtml += "<input type='radio' name='category"+i+"' id='category"+i+"' class='radioCategory' value='"+obj.dish_details.choiceCategory[i].choice[j].choice_id+"' price="+parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3)+" onclick='priceChange("+parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3)+")'>"+obj.dish_details.choiceCategory[i].choice[j].choice_name+"<span>";
                                if(parseFloat(obj.dish_details.choiceCategory[i].choice[j].price) != 0){     
                                    formHtml += parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3)+" KD</span><br>";
                                }else{
                                    formHtml += ' </span><br>';
                                }
                        }
                    }else{
                        for(var j = 0; j < obj.dish_details.choiceCategory[i].choice['length']; j++)
                        {
                            formHtml += "<label class='checkbox_custom'>"+obj.dish_details.choiceCategory[i].choice[j].choice_name+"<input type='checkbox' id='check_"+i+"' class='chociecheck' name='check_"+i+"' onchange='priceChange("+parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3)+")' price="+parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3)+" value='"+obj.dish_details.choiceCategory[i].choice[j].choice_id+"'><span>"+parseFloat(obj.dish_details.choiceCategory[i].choice[j].price).toFixed(3)+"KD</span><span class='checkmark'></span></label><br>";
                        }
                    }
                }
                formHtml += '<input type="hidden" name="product_price" id="product_price" value="'+obj.dish_details.price+'"">';
                formHtml += '<input type="hidden" name="finalPrice" id="finalPrice">';
                formHtml += '<input type="hidden" name="addOnPrice" id="addOnPrice" value="'+obj.dish_details.price+'">';
                formHtml += '<input type="hidden" name="selectedPrice" id="selectedPrice">';
                formHtml += '<input type="hidden" name="dishCount" id="dishCount" value="1">';

                $("#dish_choices_form").html(formHtml);
            }
        }
    });
});

function formsubmit(res_id)
{
  var fromdata = $('#dish_choices_form').serialize();
    
  var data = fromdata.split("&");
  var dishid = data[0].split("=");
  var choiceofone = [];
  var adddish = 0;
  
    var radioCatCount = $(".radioCategory").map(function(){return $(this).attr('id');}).length;

    if($('.radioCategory').length > 0){
        for(var i = 1; i <= radioCatCount; i++){
            if(!fromdata.includes("category"+i) && $("#category"+i).length > 0){
                $(".alerticon"+i).show();
                return false;
            }
            if(fromdata.includes("category"+i) && $("#category"+i).length > 0){
                $(".alerticon"+i).hide();
                $(".successicon"+i).show();
            }
            if(!fromdata.includes("check_"+i) && $("#check_"+i).length > 0){
                $(".alerticon"+i).show();
                return false;
            }
            if(fromdata.includes("check_"+i) && $("#check_"+i).length > 0){
                $(".alerticon"+i).hide();
                $(".successicon"+i).show();
            }
        }
    }

    var checkCatCount = $(".chociecheck").map(function(){return $(this).attr('id');}).length;

    if($('.chociecheck').length > 0){
        for(var i = 1; i <= checkCatCount; i++){
            if(!fromdata.includes("check_"+i) && $("#check_"+i).length > 0){
                $(".alerticon"+i).show();
                return false;
            }
            if(fromdata.includes("check_"+i) && $("#check_"+i).length > 0){
                $(".alerticon"+i).hide();
                $(".successicon"+i).show();
            }
        }
    }

// if(!fromdata.includes("check_") || !fromdata.includes("category1") || !fromdata.includes("category2")){
//     $(".alerticon1").show();
//     return false;
// }
  
  var disharr = [];
  var choiceofmultiple = [];
  var j = 0; 

  for (var i = 0 ; i < data.length; i++) {
    
    if(data[i].includes('check_')){
      var  choice = data[i].split('=');
      choiceofmultiple[j] = choice[1];
      j++;
    }

    if(data[i].includes('category1'))
      {
        choice = data[i].split("=");
        choiceofone.push(choice[1]);
      }


      if(data[i].includes('category2'))
      {
        choice = data[i].split("=");
         choiceofone.push(choice[1]);
      }
     
  }

  // if(choiceofone.length>0 && choiceofone[0]!='category1'){
  //   $('.alerticon1').show();
  //   $('.successicon1').hide();
  //   return false; 
  // }
  // else{
  //   $('.successicon1').show();  
  //   $('.alerticon1').hide();
  // }

  // if(choiceofone2.length>0 && choiceofone2[0]!='category2'){
    
  //   $('.alerticon2').show();
  //   $('.successicon2').hide();
  //   return false; 
  // }
  // else{
  //   $('.successicon2').show();  
  //   $('.alerticon2').hide();
  // }

  var dish_count = data[data.length - 1].split("=");
  // console.log(dish_count);return false;
  // var j = 0;
  
  /*for (var i = 2 ; i <= data.length - 5; i++) {
    var  choice = data[i].split('=');
    var choicedata = choice[0].split('_');

    choiceofmultiple[j] = choicedata[1];
    j++;
  }*/
  
  var dishDetails = getCookie('dishDetail');
    var OldDishDetails = dishDetails.dishDetail;
    
    if(OldDishDetails!=undefined){
      disharr = JSON.parse(OldDishDetails);
    }

  if(dishid[1]!='' && (choiceofone.length > 0 || choiceofmultiple.length > 0))
  {
    
    
     


    for (var i = 0; i < disharr.length; i++) {

      singDifference = $(choiceofone).not(disharr[i].choiceOfOne).get();
      mulDifference = $(choiceofmultiple).not(disharr[i].Multiplechoice).get();
    
      if(disharr[i].dishId==dishid[1] && disharr[i].res_id == res_id)
      {
        if(singDifference.length ==0 && mulDifference.length ==0){
           disharr[i].dishcount =parseInt(disharr[i].dishcount)+parseInt(dish_count[1]);
           adddish=1;
        }
      } 
    }
  }
  var instruction = $("#instruction").val();
    
    if(adddish==0){

      if(OldDishDetails != undefined){
        if(disharr.length > 0){
            var id = disharr[(disharr.length)-1].id + 1;
        }else{
            var id = 1;
        }
      }else{
         var id=1;
      }
      var dishdata = {
        "id":id,
        "dishId":dishid[1],
        "choiceOfOne":choiceofone,
        "Multiplechoice":choiceofmultiple,
        "dishcount":dish_count[1],
        "res_id":res_id,
        "instruction":instruction
      };
      disharr.push(dishdata);
    }

    dishDetails = JSON.stringify(disharr);
    //customtoastr("Dish Added Successfully",'show');
    //addproduct.close();
    //console.log(dishDetails);
    document.cookie = "dishDetail="+dishDetails+"; expires="+lastday+"; path=/"; 
   
    parent.jQuery.fn.colorbox.close();

   showCart();
}

$(document).bind('cbox_cleanup', function(){
    showCart();
});

jQuery(document).ready(function(){
        var hash = window.location.hash.substr(1);
        if(hash) {
          jQuery('html,body').animate({ scrollTop: (jQuery("#"+hash).offset().top-120)}, 1000);
        }
});