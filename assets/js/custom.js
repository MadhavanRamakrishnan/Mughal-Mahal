var curr = new Date;
var lastday = new Date(curr.getFullYear(), curr.getMonth() + 6, 0);

$(window).load(function () {
  getlocality();
   var lat="29.3518587";
    var lon="47.9836915";
    //initMap(lat,lon);  
});

$(document).ready(function() {
var langcookie = getCookie();
if(langcookie.lang_id==1)
{
  var val = true;
}
else
{
  var val = false;
}
//console.log(langcookie.lang_id);
  var owl = $('#restaurant');
  owl.owlCarousel({
    margin: 0,
    rtl:val,
    nav: false,
    loop: true,
    dots: true,
    responsive: {
      0: {
        items: 1
      },
      600: {
        items: 1
      },
      1000: {
        items: 1
      }
    }
  });

    if (langcookie.lang == "AR") {
        var val = true;
    } else {
        var val = false;
    }
    var owl = $('#restaurant');
    owl.owlCarousel({
        margin: 0,
        rtl: val,
        nav: false,
        loop: true,
        dots: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    }); 

    $('#profiletab').easyResponsiveTabs({
        type: 'default',
        width: 'auto',
        fit: true,
        closed: 'accordion',
        activate: function(event) {
            var $tab = $(this);
            var $info = $('#tabInfo');
            var $name = $('span', $info);
            $name.text($tab.text());
            $info.show();
        }
    });
});
$(".accordion").accordion();
login = Modality.init( '#login', {effect:'slide-up'} );
// address = Modality.init( '#address', {effect:'slide-up'} );
forgotpassword = Modality.init( '#forgot_pass', {effect:'slide-up'} );
Modality.init( '#register', {effect:'slide-up'} );
Modality.init( '#change_pass', {effect:'slide-up'} );


$(document).on('change','#userstate',function(){
  var state = $(this).val();
    $.ajax({
      type:'post',
      url:base_url+'/Home/City/',
      data:{state_id:state},
      success:function(data)
      {
        var obj = JSON.parse(data);
         var citydrop = '';
          citydrop += "<select name='usercity' id='usercity'>";
          for (var key in obj.data){
          citydrop += "<option value='"+obj.data[key].city_id+"'>";
          citydrop += obj.data[key].city_name;
          citydrop += "</option>";    
        }
          citydrop += "</select>";
          //console.log(citydrop);
          $('.cityfield').html(citydrop);
      }
    });
});

$('#loginform').on('click',function()
{
  var email = $('#loginemail').val();
  var password = $('#password').val();
  //alert(loginurl)
  $.ajax({
    type:'post',
    url:base_url +'/'+ loginurl,
    data:{email:email,password:password},
    beforeSend:function(){
      $("#loading-div-background").show();
    },
    success:function(data){
      
      var obj = JSON.parse(data);
      if(obj.response=='true')
      {
        var cookie   = getCookie();
        var locality = cookie.locality_id;

        $("#loading-div-background").hide();
        document.cookie = "access_token="+obj.data.access_token+"; expires="+lastday+"; path=/";
        document.cookie = "user_id="+obj.data.user_id+"; expires="+lastday+"; path=/";
        var x = document.getElementById("snackbar");
        $('#snackbar').text(UserLoginSucc);
        x.className = "show";
        setTimeout(function(){ 
          x.className = x.className.replace("show", "");
          if($(".checkOut-Click").val() == "1"){
            window.location.href = baseUrl+"Home/orderSummary/"+locality;
            return false;
          }
          location.reload(); 
        }, 300);
        
      }
      else
      {
        $("#loading-div-background").hide();
        $('#error_message').text(obj.message);
        $('#error_notification').fadeIn("slow");
        setTimeout(function(){ $('#error_notification').fadeOut("slow"); }, 3000);
      }

    },
    complete:function()
    {
        $('.loginloader').hide();
    }
  });
});

$('.closealert').on('click',function(){
  $('#error_notification').hide();
  $('#regerror_notification').hide();
});

$(document).on('stepperupdate','.steppercart',function(ev, data){
  var dish_count    = data.value;
  var price         = $(this).find('.product_price').val();
  var preCount      = $(this).find('.cart_stepper_val').val();
  var ch_total      = parseFloat($(".ch_total").attr("value"));
  var dishNum       = $(this).find('.dishId').val();
  var oldDishprice  = parseInt(preCount)   * parseFloat(price);
  var dishprice     = parseInt(dish_count) * parseFloat(price);
  var total         = parseFloat(ch_total - oldDishprice + dishprice).toFixed(3);

  $('#dish'+dishNum).find('.dish_price').find('p').html(dishprice.toFixed(3)+'KD ');
  $('#dish'+dishNum).find('.dish_price').attr('value',dishprice);

  $(".ch_total").text(total +" KD");
  $(".ch_total").attr('value',total);
  $(this).find('.cart_stepper_val').val(dish_count);

  var dishDetails     = getCookie('dishDetail');
  var OldDishDetails  = dishDetails.dishDetail;
  var disharr         = [];
  if(OldDishDetails!=undefined){
    disharr = JSON.parse(OldDishDetails);
  }
  for (var i = 0; i < disharr.length; i++) {
    if(disharr[i].id==dishNum)
    {
      disharr[i].dishcount = dish_count;
    }
  }
  dishDetails = JSON.stringify(disharr);
  document.cookie = "dishDetail="+dishDetails+"; expires="+lastday+"; path=/";  


});

$('#myDropdown > .add_cart').click(
    function(e) {
        e.stopPropagation();
    }
);

$("body").click(function() {
    $("#myDropdown1").hide();
});

$(".dropbtn").click(function(e) {
    e.stopPropagation();
    $("#myDropdown1").show();
    $("#login_after1").hide();
});

$('#myDropdown1 > .add_cart').click(
    function(e) {
        e.stopPropagation();
    }
);


$("body").click(function() {
    $("#login_after").hide();
});

$(".dropbtn1").click(function(e) {
    e.stopPropagation();
    $("#login_after").show();
    $("#myDropdown").hide();
});

$('#login_after > .login_dropdown').click(
    function(e) {
        e.stopPropagation();
    }
);

$("body").click(function() {
    $("#login_after1").hide();
});

$(".dropbtn1").click(function(e) {
    e.stopPropagation();
    $("#login_after1").show();
    $("#myDropdown1").hide();
});
$('#login_after1 > .login_dropdown').click(function(e) {
    e.stopPropagation();
});

$('#login_after1 > .login_dropdown').click(
    function(e) {
        e.stopPropagation();
    }
);

$(document).ready(function () {
  $('#profiletab').easyResponsiveTabs({
    type: 'default', //Types: default, vertical, accordion           
    width: 'auto', //auto or any width like 600px
    fit: true,   // 100% fit in a container
    closed: 'accordion', // Start closed if in accordion view
    activate: function(event) { // Callback function if tab is switched
    var $tab = $(this);
    var $info = $('#tabInfo');
    var $name = $('span', $info);
    $name.text($tab.text());
    $info.show();
    }
  });
});

$('#submitregister').on('click',function(){
  var fname = $('#fname').val();
  var lname = $('#lname').val();
  var email = $('#user_email').val();
  var phone = $('#phone').val();
  var country_code = $('#country_code').val();
  var password = $('#user_password').val();
  var cfnpassword = $('#cfnpassword').val();

  if(fname=='') {   showregerror(FirstNameReq);  }
  else if (lname=='') {   showregerror(LastNameReq);  }
  else if (email=='') { showregerror(EmailReq); }
  else if(phone=='') { showregerror(PhoneReq); }
  else if(password=='') { showregerror(PasswordReq); }
  else if(cfnpassword=='') { showregerror(ConfPasswordReq); }
  else if(password==cfnpassword)
  {
    $.ajax({
      type:'post',
      url:base_url +'/'+ signurl,
      data:{first_name:fname,last_name:lname,email:email,contact:phone,password:password,default_language:'en',country_code:country_code},
      beforeSend:function(){
          $("#loading-div-background").show();
      },success:function(data){
        var obj = JSON.parse(data);
        
        if(obj.response=='true')
        {
          
          document.cookie = "access_token="+obj.data.access_token+"; expires="+lastday+"; path=/";
          document.cookie = "user_id="+obj.data.user_id+"; expires="+lastday+"; path=/";
          var x = document.getElementById("snackbar");
          $('#snackbar').text(UserSignupSucc);
          x.className = "show";
          setTimeout(function(){ 
            x.className = x.className.replace("show", "");
            location.reload(); 
          }, 2000);
        }
        else
        {
           $("#loading-div-background").hide();
          showregerror(obj.message)
        }
      },
      complete:function()
      {
          $("#loading-div-background").hide();
      }
    });
  }
  else
  {
    showregerror(Passwordnotmatch);
  }
});

$('.check-out').on('click',function(){
  var cookie   = getCookie();
  var locality = cookie.locality_id;

  var cookiedetail = getCookie('access_token');
  if(cookiedetail.access_token==undefined){
    $(".checkOut-Click").val("1");
    login.open();
  }
  else
  {
    var total = parseFloat($("#total").text()).toFixed(3);
    if(total > 0){
        window.location.href = site_url+"Home/orderSummary/"+locality;    
    }else{
        alert("Please select dish to proceed");
    }
  }    
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

$('#addaddress').on('click',function(){

 var user = {
    'customer_name':"'"+$('#username').val()+"'",
    'access_token':"'"+getCookie('access_token')+"'",
    'user_id':"'"+getCookie('user_id')+"'",
    'address_type':'1',
    'email':"'"+$('#useremail').val()+"'",
    'phone':"'"+$('#userphone').val()+"'",
    'address1':"'"+$('#useradd1').val()+"'",
    'address2':"'"+$('#useradd2').val()+"'",
    'state':"'"+$('#userstate').val()+"'",
    'city':"'"+$('#usercity').val()+"'",
  };
  $.ajax({
    type:'post',
    url:base_url + '/' + addDelAddress,
    data:{user},
    success:function(data){
      location.reload();
    }
  });
});

$('.changelang').on('click',function(){
  var lang = $(this).attr('lang');
  document.cookie = "lang="+lang+"; expires="+lastday+"; path=/";
  location.reload();
});

$('.forgot_pass').on('click',function(){
  login.close();
})

function langonchange(element)
{
  var lang = element.lang
  document.cookie = "lang="+lang+"; expires="+lastday+"; path=/";
  location.reload();
}

function showregerror(msg)
{
  $('#regerror_message').text(msg);
  $('#regerror_notification').fadeIn("slow");
  setTimeout(function(){ $('#regerror_notification').fadeOut("slow"); }, 3000);
}

function getCookie(cname) {
    var pairs = document.cookie.split(";");
  var cookies = {};
  for (var i=0; i<pairs.length; i++){
    var pair = pairs[i].split("=");
    cookies[(pair[0]+'').trim()] = unescape(pair[1]);
  }
  return cookies;
}

function customtoastr(text,classname)
{
  var x = document.getElementById("snackbar");
  $('#snackbar').text(text);
    x.className = classname;
    setTimeout(function(){ 
      x.className = x.className.replace(classname, "");
    }, 3000);
}

function getlocality()
{
  var lang =getCookie('lang');
  
  $.ajax({
    type:'POST',
    url:getlocalites+'/'+lang.lang,
    data:{data:'1'},
    success:function(data){
      var obj = JSON.parse(data);
        var html = "";
        for (var i = 0; i < obj.length ; i++) {
            html += "<option value='"+obj[i].locality_id+"'>"+obj[i].name+"</option>";
        }
        $('#locality').append(html);
    }
  });
}

$(document).on('change','#locality',function() {
   getLatlong($(this).val());
});

function getLatlong(latlongId=""){

  if(latlongId != ""){
    $.ajax({
      url        : getLatlongdata,
      type       : "POST",
      data       : {latlong_id:latlongId},
      success     : function(response){
       
        var obj = JSON.parse(response);
        if(obj.success==1){ 
           lat =obj.data[0].lat;
           lon =obj.data[0].lon;
           $("#lat").val(lat);
           $("#long").val(lon);
           initMap(lat,lon);
       }
     }
   });
  }else{
    $("#locality").val('');
  }
}

// function addaddress()
// {
//       var user_id       =$("#user_id").val();
//       var address_type  =$('input[name=address_type]:checked').val();
//       var name          =$('#customer_name').val();
//       var email         =$('#addemail').val();
//       var phone         =$('#contact_no').val();
//       var locality      =$('#locality').val();
//       var complete_add  =$('#address_line1').val();
//       var lat           =$('#lat').val();
//       var long          =$('#long').val();
//       var address_id    = $("#address_id").val();
//       var profile       ="";
//       if($("#addaddFromPro").val() != undefined){
//        var profile= $("#addaddFromPro").val();
//       }
//       if(name == ''){
//           $('.name').show();
//           $('.address2').hide();
//           $('.phone').hide();
//           $('.email').hide();
//           $('.email1').hide();
//           $('.phone1').hide();
//       }
//       else if(email == ''){
//           $('.email').show();
//           $('.phone').hide();
//           $('.name').hide();
//           $('.address2').hide();
//           $('.email1').hide();
//           $('.phone1').hide();
//       }
//       else if(validateEmail(email) == false){
//           $('.email1').show();
//            $('.email').hide();
//           $('.phone').hide();
//           $('.phone1').hide();
//           $('.name').hide();
//           $('.address2').hide();
//       }
//       else if(phone == ''){
//           $('.phone').show();
//           $('.email').hide();
//           $('.name').hide();
//           $('.address2').hide();
//           $('.email1').hide();
//           $('.phone1').hide();
//       }
//       else if(!($.isNumeric(phone))){
//           $('.phone1').show();
//           $('.email').hide();
//           $('.email1').hide();
//           $('.name').hide();
//           $('.address2').hide();
//           $('.phone').hide();
//       }
//       else if(complete_add == ''){
//           $('.complete_add').show();
//           $('.name').hide();
//           $('.email').hide();
//           $('.phone').hide();
//           $('.email1').hide();
//           $('.phone1').hide();
//       }
//       else{
//         var data ={
//               user_id:user_id,
//               address1:complete_add,
//               address_type:address_type,
//               customer_name:name,
//               email:email,
//               contact_no:phone,
//               customer_latitude:lat,
//               customer_longitude:long,
//               locality_id:locality,
//               address_id:address_id
//           }
          
//           $("#loading-div-background").show();
//           $.post(addDiliverAddress,data)
//           .done(function(response){
          
//             obj =$.parseJSON(response);
//             if(obj.success == '1'){
//              $("#loading-div-background").hide();
//              location.reload();
              
//             }else{

//             }
//           });

//       }
  
// }

function getautocomplete()
{
  $.ajax({
    type:'POST',
    url:base_url+'/Home/getlocalites',
    data:{data:1},
    success:function(response){
      //console.log(response);
      var data = JSON.parse(response)
       $('#autocomlocality').autocomplete({
          source: data,
          select: function(event,ui) {
            var autocompletefield = $(this);
            $("#autocomlocality").val(ui.item.label);
              $.ajax({
                type:'POST',
                url:base_url+'/Home/setLocalityInSession',
                data:{locality:ui.item.value,locality_value:ui.item.label},
                success:function(response){
                   $("#autocomlocality").val(ui.item.label);
                    getdishes(ui.item.value);
                    getdishcat(ui.item.value)
                }
              });  
            }
          });
    }
  });  
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
var delete_cookie = function(name) {
    document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/';
};





// function editAddressData(address_id){
//   getlocality();
//   $.ajax({
//        url:getCustomerAddress+"/"+address_id,
//        type:"POST",
//        success:function(data){

//           var obj =$.parseJSON(data);
         
//           if(obj.success == 1){
//             $("#address_id").val(obj.message[0].address_id);
//             $("#customer_name").val(obj.message[0].customer_name);
//             $("#addemail").val(obj.message[0].email);
//             $("#contact_no").val(obj.message[0].contact_no);
//             $("#locality").val(obj.message[0].locality_id);
//             $("#lat").val(obj.message[0].customer_latitude);
//             $("#long").val(obj.message[0].customer_longitude);
//             $("#address_line1").val(obj.message[0].address1);
//             initMap(obj.message[0].customer_latitude,obj.message[0].customer_longitude);
//             if(obj.message[0].address_type =="1"){
//               $("#home").prop("checked",true);
//             }else if(obj.message[0].address_type =="2"){
//               $("#office").prop("checked",true);
//             }else{
//               $("#other").prop("checked",true);
//             }
//           }else{
//           }
//        }

//    })
// }
/*$("#forgotPassword").click(function() {
    var email = $("#forgot_pass").find('#forgot_email').val();
    $.ajax({
        type: 'post',
        url: base_url + forgotPasswordurl,
        data: {
            email: email,
            default_language: lang
        },
        beforeSend: function() {
            $("#loading-div-background").show();
        },
        success: function(data) {
            var obj = JSON.parse(data);
            if (obj.response == 'true') {
                $("#loading-div-background").hide();
                $('#forgot_error_notification').hide();
                $('#forgot_sucess_message').text(obj.message);
                $('#forgot_sucess_notification').fadeIn("slow");
            } else {
                $("#loading-div-background").hide();
                $('#forgot_error_message').text(obj.message);
                $('#forgot_error_notification').fadeIn("slow");
            }
        }
    });
})*/

function cleaPOPFields() {
    var logineletext = $("#login").find("input");
    var passeletext = $("#forgot_pass").find("input");
    $.each(logineletext, function(key, val) {
        if ($(val).attr('type') == "checkbox") {
            $(val).prop('checked', false);
        }
        if ($(val).attr('type') == "text") {
            $(val).val("");
        }
    })
    $.each(passeletext, function(key, val) {
        if ($(val).attr('type') == "text") {
            $(val).val("");
        }
        $('#forgot_error_notification').hide();
    })
}
$(document).on('click', '.favorite_icon', function() {
    var user = getCookie('user_id');
    var dish_id = $(this).attr('dish_id');
    var is_favourite = $(this).attr('is_favourite');
    var thisELE = $(this);
    if (is_favourite == '') {
        $.ajax({
            type: 'post',
            url: addfavourite,
            data: {
                user_id: user.user_id,
                dish_id: dish_id
            },
            success: function(data) {
                var obj = JSON.parse(data);
                if (obj.success == 'true') {
                    var favImg = '<img src="' + baseurl + 'assets/images/front-end/heart.png" alt="" title="Remove from favourite">';
                    thisELE.find('a').html(favImg);
                    thisELE.attr('is_favourite', '1');
                } else {
                    $('#error_message').text(obj.message);
                    $('#error_notification').fadeIn("slow");
                    setTimeout(function() {
                        $('#error_notification').fadeOut("slow");
                    }, 3000);
                }
            },
            complete: function() {
                $('.loginloader').hide();
            }
        });
    } else {
        $.ajax({
            type: 'post',
            url: removefavourite,
            data: {
                user_id: user.user_id,
                dish_id: dish_id
            },
            success: function(data) {
                var obj = JSON.parse(data);
                if (obj.success == 'true') {
                    var favImg = '<img src="' + baseurl + 'assets/images/front-end/heart_grey.png" alt="" title="Add to favourite">';
                    thisELE.find('a').html(favImg);
                    thisELE.attr('is_favourite', '');
                } else {
                    $('#error_message').text(obj.message);
                    $('#error_notification').fadeIn("slow");
                    setTimeout(function() {
                        $('#error_notification').fadeOut("slow");
                    }, 3000);
                }
            },
            complete: function() {
                $('.loginloader').hide();
            }
        });
    }
})
$(document).on("click", ".removeFavourite", function() {
    var user = getCookie('user_id');
    var dish_id = $(this).attr('dish_id');
    var thisELE = $(this);
    $("#loading-div-background").show();
    $.ajax({
        type: 'post',
        url: removefavourite,
        data: {
            user_id: user.user_id,
            dish_id: dish_id
        },
        success: function(data) {
            var obj = JSON.parse(data);
            $("#loading-div-background").hide();
            if (obj.success == 'true') {
                $(".favourite" + dish_id).remove();
                var x = document.getElementById("snackbar");
                $('#snackbar').text(obj.message);
                x.className = "show";
                setTimeout(function() {
                    x.className = x.className.replace("show", "");
                }, 3500);
            } else {
                $('#snackbar').text(obj.message);
                x.className = "show";
                setTimeout(function() {
                    x.className = x.className.replace("show", "");
                }, 3000);
            }
        }
    });
})

function validateEmail(sEmail) {
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test(sEmail)) {
        return true;
    } else {
        return false;
    }
}

/*function initMap(lat=29.3518587,lon=47.9836915) {
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

  }*/


  /*function removeDishData(id){
    $("#remove_dish").unbind().click(function(){
         var dishDetails = getCookie('dishDetail');
        var OldDishDetails = dishDetails.dishDetail;

        if(OldDishDetails!=undefined){
          disharr = JSON.parse(OldDishDetails);
        }
        //console.log(disharr);
        for (var i = 0; i < disharr.length; i++) {
          if(disharr[i].id==id)
          {
            var removeindex = disharr.indexOf(disharr[i]); 
            disharr.splice(removeindex,1);
          }
        }
        dishDetails = JSON.stringify(disharr);
        document.cookie = "dishDetail="+dishDetails+"; expires="+lastday+"; path=/";
        location.reload();
    })
    
  }*/

$("#forgotPassword").click(function(){
    var email =  $("#forgot_pass").find('#email').val();
    
    $.ajax({
      type:'post',
      url:base_url + forgotPasswordurl,
      data:{email:email,default_language:lang},
      beforeSend:function(){
        $("#loading-div-background").show();
      },
      success:function(data){
          var obj = JSON.parse(data);
          console.log(obj);
          if(obj.response=='true')
          {
            $("#loading-div-background").hide();
            
            $("#loading-div-background").hide();
            $('#forgot_error_message').text(obj.message);
            $('#forgot_error_notification').fadeIn("slow");
            setTimeout(function(){ 
              x.className = x.className.replace("show", "");
              location.reload(); 
            }, 2000);
            
          }
          else
          {
            $("#loading-div-background").hide();
            $('#forgot_error_message').text(obj.message);
            $('#forgot_error_notification').fadeIn("slow");
           
          }
        

      },
      complete:function()
      {
           setTimeout(function(){ $('#forgot_error_notification').fadeOut("slow"); }, 3000);
      }
    });
})

function cleaPOPFields(){
     
      var logineletext =$("#login").find("input");
      var passeletext  =$("#forgot_pass").find("input");
      $.each(logineletext,function(key,val){
        if($(val).attr('type') =="checkbox"){
          $(val).prop('checked',false);
        }
        if($(val).attr('type') == "text"){
            $(val).val("");
          }
        
      })
      $.each(passeletext,function(key,val){
          if($(val).attr('type') == "text"){
            $(val).val("");
          }
      })
}


$(document).on('click', '.res_rating', function() {
    var rating = $(this).attr('rating');
    var rsId = $(this).attr('rsId');
    var oID = $(this).attr('oID');
    setRating(rating, "food_rating");
    if (rating > 3) {
        $('.positive_res').show();
        $('.negative_res').hide();
    } else {
        $('.positive_res').hide();
        $('.negative_res').show();
    }
    $("input[name='res_pot[]']").unbind().click(function() {
        // var reason = $(this).val();
        $('input[name="res_pot[]"]:checked').each(function(i){
          console.log($(this).val());
          if ($(this).val() == "Other") {
              $(this).parent().parent().find('.other_reason').show();
          } else {
              $(this).parent().parent().find('.other_reason').hide();
          }
        });
    })
    $('.addratebtn').unbind().click(function() {

      var reason = '';
      $('input[name="res_pot[]"]:checked').each(function(i){
        console.log($(this).val());
        if ($(this).val() == "Other") {
            reason = reason + $(this).parent().parent().find('.other_reason').val();
        } else {
            reason = reason + $(this).val() + ',';
        }
      });

        /*var reason = $(this).parent().parent().find("input[name='res_pot']:checked").val();
        if (reason == "Other") {
            reason = $(this).parent().parent().find('.other_reason').val();
        }*/
        if (reason == "" || reason == undefined) {
            $(this).parent().parent().find('span').text(EnterOtherReason);
            return false;
        } else {
            var data = {
                restaurant_id: rsId,
                order_id: oID,
                rating: rating,
                reason: reason
            }
            $.post(addRestaurantRating, data, function(response) {
                var obj = $.parseJSON(response);
                if (obj.success == 1) {
                    setRating(rating, "food_rating");
                } else {
                    setRating(0, "food_rating");
                }
                $('.positive_res').hide();
                $('.negative_res').hide();
            });
        }
    })
})
$(document).on('click', '.driver_rating', function() {
    var rating = $(this).attr('rating');
    var rsId = $(this).attr('rsId');
    var oID = $(this).attr('oID');
    var driver = $(this).attr('driver');
    setRating(rating, "delivery_rating");
    var data = {
        order_id: oID,
        rating: rating,
        driver_id: driver
    }
    if (rating > 3) {
        $('.positive_driv').show();
        $('.negative_driv').hide();
    } else {
        $('.positive_driv').hide();
        $('.negative_driv').show();
    }
    $("input[name='res_pot[]']").unbind().click(function() {
        /*var reason = $(this).val();
        if (reason == "Other") {
            $(this).parent().parent().find('.other_reason').show();
        } else {
            $(this).parent().parent().find('.other_reason').hide();
        }*/

        $('input[name="res_pot[]"]:checked').each(function(i){
          console.log($(this).val());
          if ($(this).val() == "Other") {
              $(this).parent().parent().find('.other_reason').show();
          } else {
              $(this).parent().parent().find('.other_reason').hide();
          }
        });
    });
    $('.addratebtn').unbind().click(function() {

      var reason = '';
      $('input[name="res_pot[]"]:checked').each(function(i){
        console.log($(this).val());
        if ($(this).val() == "Other") {
            reason = reason + $(this).parent().parent().find('.other_reason').val();
        } else {
            reason = reason + $(this).val() + ',';
        }
      });
      
        /*var reason = $(this).parent().parent().find("input[name='res_pot']:checked").val();
        if (reason == "Other") {
            reason = $(this).parent().parent().find('.other_reason').val();
        }*/
        if (reason == "" || reason == undefined) {
            $(this).parent().parent().find('span').text(EnterOtherReason);
            return false;
        } else {
            var data = {
                order_id: oID,
                rating: rating,
                driver_id: driver,
                reason: reason
            }
            $.post(addDriverRating, data, function(response) {
                var obj = $.parseJSON(response);
                if (obj.success == 1) {
                    setRating(rating, "delivery_rating");
                } else {
                    setRating(0, "delivery_rating");
                }
                $('.positive_driv').hide();
                $('.negative_driv').hide();
            });
        }
    })
})

function setRating(rating, classId) {
    var html = '';
    for (var i = 0; i < rating; i++) {
        html += '<li><img src="' + base_url + 'assets/css/images/icon/star-color.png" alt=""></li>'
    }
    for (var i = rating; i < 5; i++) {
        html += '<li><img src="' + base_url + 'assets/css/images/icon/star-grey.png" alt=""></li>'
    }
    $('.' + classId).find('.rate_list').html(html);
}
