$(document).ready(function() {

    $('.menuButton').click(function(event) {
        $('ul.nav-menu').slideToggle();
        $('.menuButton').toggleClass('menuButtonActive');
    });
    getRestaurantData();

    //animation initialization
    wow = new WOW(
      {
        animateClass: 'animated',
        offset:       100,
        callback:     function(box) {
        }
      }
    );
    wow.init();
}); 
  

  // tabbed content
  // http://www.entheosweb.com/tutorials/css/tabs.asp
  $(".tab_content").hide();
  $(".tab_content:first").show();

  /* if in tab mode */
  $("ul.tabs li").click(function() {
      
      $(".tab_content").hide();
      var activeTab = $(this).attr("rel"); 
      $("#"+activeTab).fadeIn();        
        
      $("ul.tabs li").removeClass("active");
      $(this).addClass("active");

      $(".tab_drawer_heading").removeClass("d_active");
      $(".tab_drawer_heading[rel^='"+activeTab+"']").addClass("d_active");
    
  });

  $(".tab_container").css("min-height", function(){ 
    return $(".tabs").outerHeight() + 50;
  });
  /* if in drawer mode */
  $(".tab_drawer_heading").click(function() {
    
    $(".tab_content").hide();
    var d_activeTab = $(this).attr("rel"); 
    $("#"+d_activeTab).fadeIn();
    
    $(".tab_drawer_heading").removeClass("d_active");
    $(this).addClass("d_active");
    
    $("ul.tabs li").removeClass("active");
    $("ul.tabs li[rel^='"+d_activeTab+"']").addClass("active");

  });
  
    
  /*increment js*/
  $(function() 
  {
      $(".numbers-row").prepend('<div class="dec button">-</div>');
      $(".numbers-row").append('<div class="inc button">+</div>');

      $(".button").on("click", function() 
      {

            var $button = $(this);
            var oldValue = $button.parent().find("input").val();

            if ($button.text() == "+") 
            {
              var newVal = parseFloat(oldValue) + 1;
            }
            else
            {
               // Don't allow decrementing below zero
              if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
                }
                else{
                  newVal = 0;
                }
            }
            $button.parent().find("input").val(newVal);
      });
  });


//remove dish from dish list in order summary page
function removeDishData(id) 
{
    $('#delete_dish_btn').unbind().on("click",function()
    {
        var dishDetail = getCookie('dishDetail');
        var OldDishDetails = dishDetail.dishDetail;

        if (OldDishDetails != undefined) {
            disharr = JSON.parse(OldDishDetails);
        }

        for (var i = 0; i < disharr.length; i++) 
        {
            if (disharr[i].id == id)
            {
                var removeindex = disharr.indexOf(disharr[i]);
                disharr.splice(removeindex, 1);
            }
        }

        dishDetails = JSON.stringify(disharr);

        document.cookie = "dishDetail=" + dishDetails + "; expires=" + lastday + "; path=/";

        setOrderSummary(dishDetail.locality_id);
        
  //      $.fn.colorbox.close();
	 location.reload();
        if(disharr.length ==0)
        {
          window.location.href=baseUrl+'Home';
        }
    });
}

function setOrderSummary(locality, locality_val = "") {
    $.post(getOrderSummary, {
        locality: locality,
        locality_value: locality_val
    }, function(data) {
        var obj = $.parseJSON(data);
        if (obj.success == 1)
        {
            var html = "";
            $.each(obj.message, function(k, v) {
                if (v.locality != locality) {
                    var tr_cl = "not_allow";
                } else {
                    var tr_cl = "";
                }
                html += '<tr class="' + tr_cl + '">';
                html += '<td>' + v.dish_name + '<span>' + v.choice_name + '</span></td>';
                html += '<td>' + v.subtotal + 'KD</td>';
                html += '<td>' + v.dish_count + '</td>';
                html += '<td class="t_prise">' + v.total + 'KD</td>';
                html += '<td>';
                html += '<a data-toggle="modal" data-target="#removeDish"  aria-label="open"  onclick="removeDishData(' + v.id + ');"><i class="fa fa-times-circle"></i></a>';
                html += '</td>';
                html += '</tr>';
            });
        } else {
            window.location.href = base_url + "Home/dishes";
        }
        $(".dish_table").find('tbody').html(html);
        $(".order_subtotal").text(obj.subtotal + " KD");
        $(".order_charge").text(obj.del_charge + " KD");
        $(".order_total").text(obj.total + " KD");
        $('.placeorder').attr('total', obj.total);
        $('.placeorder').attr('remDish', obj.removeDishTotal);
    });
}

function addaddress() 
{
    var user_id = $("#user_id").val();
    var address_type = $('input[name=address_type]:checked').val();
    var name = $('#customer_name').val();
    var email = $('#addemail').val();
    var phone = $('#contact_no').val();
    var locality = $('#locality').val();
    var editAdd = $('#editAdd').val();
    var locality_val = $("#locality option:selected").text();
    var complete_add = $('#address_line1').val();
    var lat = $('#lat').val();
    var long = $('#long').val();
    var address_id = $("#is_add_address").val();
    var profile = "";
    if (name == '') {
        hideAddressError();
        $('.name').show();
    } else if (email == '') {
        hideAddressError();
        $('.email').show();
        $('.email').text(EmailReq);
    } else if (validateEmail(email) == false) {
        hideAddressError();
        $('.email').show();
        $('.email').text(PleEnterValidEmail);
    } else if (phone == '') {
        hideAddressError();
        $('.phone').show();
        $('.phone').text(PhoneReq);
    }else if (!($.isNumeric(phone))) {
        hideAddressError();
        $('.phone').show();
        $('.phone').text(phoneIsnumeric);
    } else if (phone.length < 8) {
        hideAddressError();
        $('.phone').show();
        $('.phone').text(phoneMinlenth);
    } else if (complete_add == '') {

        hideAddressError();
        $('.complete_add').show();
        $('.complete_add').text(completeAdd);

    } else {
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
            address_id: address_id
        }
        $.post(addDiliverAddress, data).done(function(response) {
            obj = $.parseJSON(response);

            if (obj.success == '1') {

                if (editAdd != "0") 
                {
                    document.cookie = "locality_id=" + locality_id + "; expires=" + lastday + "; path=/";
                    document.cookie = "delivery_address=" + address_id + "; expires=" + lastday + "; path=/";
                }

                location.reload();
            }
        });
    }
}
//hide address error fields
function hideAddressError()
{
    $('.add_error').hide();
}

$(document).on('change',"#address_locality",function(){
    $.ajax({
        type: 'POST',
        url: getlocalites+'/'+$(this).val(),
        data: {
            data: '1'
        },
        success: function(data) {
            
            var obj =$.parseJSON(data);
            if(obj.success =="1")
            {
                $("#lat").val(obj.message.lat);
                $("#lat").val(obj.message.lon);
                initMap(obj.message.lat,obj.message.lon);
            }

        }
    });
})

function getRestaurantData() {
    var dishDetails = getCookie();
    var locality = dishDetails.locality_id;
    $.post(getRestaurantDetail, {
        locality: locality
    }, function(response) {
        var obj = $.parseJSON(response);
        if (obj.success == 1) {

            $(".res_address").html(obj.restaurant.address);
            if(obj.restaurant.contact_no)
            {
                $(".phone_address").html(obj.restaurant.contact_no);
            }
            else
            {
                 $(".phone_address").html('&nbsp&nbspN/A');
            }
            if(obj.restaurant.email)
            {
                $(".email_address").html(obj.restaurant.email);
            }
            else
            {
                 $(".email_address").html('&nbsp&nbspN/A');
            }
            
            var html = "";
            if (obj.times.length > 0) {
                $.each(obj.times, function(k, v) {
                    html += "<li><span>" + v + "</span></li>";
                });
                $(".res_time").html(html);
            } else {
                $(".res_time").html(html);
            }
        }
    });
}

function validateEmail(sEmail) {
    var filter = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
    if (filter.test(sEmail)) {
        return true;
    } else {
        return false;
    }
}



function getlocality() {
    $.ajax({
        type: 'POST',
        url: getlocalites,
        data: {
            data: '1'
        },
        success: function(data) {

            var obj =$.parseJSON(data);
            if(obj.success =="1")
            {
                var obj = JSON.parse(data);
                var html = "";
                $.each(obj.message,function(k,v){

                    html += "<option value='" + v.locality_id + "'>" + v.name + "</option>";
                })
                
                $('#address_locality').html(html);
            }

            
        }
    });
}

function initMap(lat=29.3518587,lon=47.9836915) {
      var uluru = new google.maps.LatLng(lat, lon);
      var myOptions;
      var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 8,
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

          infoWindow.open(map, marker);

      });
}

$(document).on('change', '.custAddress', function() {
    var id           = $(this).val();
    var locality     = $(this).attr("loca");
    var locality_val = $(this).attr("loca_val");
    document.cookie  = "locality_id=" + locality + "; expires=" + lastday + "; path=/";
    document.cookie  = "delivery_address=" + id + "; expires=" + lastday + "; path=/";
    setOrderSummary(locality, locality_val);
    getRestaurantData();

});

$('.placeorder').on('click',function()
{
    var total = parseInt($(this).attr('remDish'));
    if(total == 0)
    {
        var address,payment,dishDetail,user_id;
        var addele = $('input[type=radio][name=address-radio]');
        var payele = $('input[type=radio][name=payment-radio]');
        for (var i=0, len=addele.length; i<len; i++) 
        {
            if(addele[i].checked==true)
            { 
                // radio checked?
                address = addele[i].value; // if so, hold its value in val
                break; // and break out of for loop
            }
        }
        
        for (var i=0, len=payele.length; i<len; i++) {
            if (payele[i].checked==true) 
            { 
                payment = payele[i].value; 
                break;
            }
        }

        if(address!=undefined && payment!=undefined)
        {
            var cookiedata = getCookie();
            dishDetail     = cookiedata.dishDetail;
            user_id        = cookiedata.user_id;

            $.ajax({

              type  :'POST',
              url   : addorder,
              data  :{user_id:user_id,dishDetail:dishDetail,address_id:address,payment:payment},
              success:function(response)
              {
                var obj = JSON.parse(response);
                if(obj.success==1)
                {
                  delete_cookie('dishDetail');
                  window.location.href = baseUrl+'Home/orderDetails/'+obj.order_id;
                }
                else if(obj.success==2)
                {
                  delete_cookie('dishDetail');
                  window.location.href =  obj.url;
                }
              }
            });
        }
        else
        {
          var msg = '';
          if(address==undefined){
            msg = "Address is not selected";
          }
          else if(payment==undefined){
            msg = "payment method is not selected"; 
          }
          alert(msg);
          // customtoastr(msg,"errorshow");
        }
    }else{
        $("#loading-div-background").hide();
        $("#myDropdown").hide();
        // customtoastr(deleteDish,'errorshow');
    }
});

/**
 * [close conformation popup on cancel]
 * Description:
 * @author: Manisha Kanazariya
 * @CreatedDate:2019-02-02T18:41:59+0530
 */
$("#cancel_delete_dish_btn").click(function(){
    location.reload();
})
