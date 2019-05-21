$ (window).load(function () {
  getdishcat(localityId,"");
  getMainDishes(localityId,"");
  getautocomplete();
});
cartflag = 0;
var curr = new Date;
var lastday = new Date(curr.getFullYear(), curr.getMonth() + 6, 0);


function getCookie(cname) {
    var pairs = document.cookie.split(";");
    var cookies = {};
    for (var i=0; i<pairs.length; i++){
      var pair = pairs[i].split("=");
      cookies[(pair[0]+'').trim()] = unescape(pair[1]);
    }
    return cookies;
}


function getdishcat(locality,search="")
{
  $("#loading-div-background").show();
  $.ajax({
    type  :'POST',
    url   :dish_caturl+locality,
    data  :{is_active:1,search:search},
    beforeSend  :        function(){
    },
    success     :        function(obj){
  
      var data = JSON.parse(obj);
      var html = "";
      $(".more_category").show();
      if(data.response=="true")
      {
        
        for (var i = 0; i < data.choice.length; i++) {
          if (i==0) 
          { var active = 'active';  }
          else 
          { var active = "";}

          if(data.choice[i].category_id != null){

            html += '<li class="'+active+'" ><a href="#'+data.choice[i].category_id+'" list="cat" >'+data.choice[i].category_name+'</a></li>';
          }

        }
        
      }else{
        $(".more_category").hide();
      }
      $("#loading-div-background").hide();
      $('.category_list').html(html);
    }
  });
}

function getMainDishes(locality,search="")
{
  
}

/*$(document).on('click','.showmore',function(){
  $(this).parent().parent().siblings('.more_dish_list').slideDown('slow');
  $(this).text(showLess);
  $(this).addClass('showless');
  $(this).removeClass('showmore');
});

$(document).on('click','.showless',function(){
  $(this).parent().parent().siblings('.more_dish_list').slideUp('slow');
  $(this).text(showMore);
  $(this).addClass('showmore');
  $(this).removeClass('showless');
});
*/
$isopen=0;

$(".more_category").click(function(){

    $box = $(".category_list");
    minimumHeight = 53;

    // get current height
    currentHeight = $box.height();

    // get height with auto applied
    autoHeight = $box.css('height', 'auto').height();

    // reset height and revert to original if current and auto are equal
    $box.css('height', currentHeight).animate({
        height: (currentHeight == autoHeight ? minimumHeight : autoHeight)
    });

  if($isopen==0){
    $isopen=1;
    $(".more_category").html(less+' <i class="fa fa-caret-up">');
  }
  else{
    $isopen=0;
    $(".more_category").html(more +' <i class="fa fa-caret-down">');
  }

});

$(document).on('stepperupdate','.stepperpopup',function(ev, data){
  var dish_count = data.value;
  var price = $('#product_price').val();
  var addonprice =  $('#addonprice').val();
  addonprice = (addonprice!='')?addonprice:0;
  var finalprice = dish_count * (parseFloat(price) + parseFloat(addonprice));
  $('#product_add').find('#stepper_val').val(dish_count);
  $('#product_add').find('.finalprice').html(parseFloat(finalprice).toFixed(2)+' KD');
  $('#product_add').find('#finalprice').val(parseFloat(finalprice).toFixed(2));
  $('#product_add').find('#dishcount').val(dish_count);
});

$("body").click(function() {
    $("#myDropdown").hide();
});


//Function for add dish to cart with option
function formsubmit(res_id)
{
  var fromdata = $('#dishDetails').serialize();
  
  var data = fromdata.split("&");
  var dishid = data[0].split("=");
  var choiceOfOne = data[1].split("=");
  var choiceOfOne2=["",""];

  if(data[2].includes('radio-group'))
  {
    var choiceOfOne2 = data[2].split("=");
  }
  else
  {
    var choiceOfOne2 = [];
    //choiceOfOne2[1] = "";
  }
  
  var disharr = [];
  var choiceofmultiple = [];

  if(choiceOfOne[0]!='radio-group1'){
    $('.alerticon1').show();
    $('.successicon1').hide();
    return false; 
  }
  else{
    $('.successicon1').show();  
    $('.alerticon1').hide();
  }

  if(choiceOfOne2.length>0 && choiceOfOne2[0]!='radio-group2'){
    //alert("in");
    $('.alerticon2').show();
    $('.successicon2').hide();
    return false; 
  }
  else{
    $('.successicon2').show();  
    $('.alerticon2').hide();
  }

  var dish_count = data[data.length - 1].split("=");
  var j = 0;
  
  /*for (var i = 2 ; i <= data.length - 5; i++) {
    var  choice = data[i].split('=');
    var choicedata = choice[0].split('_');

    choiceofmultiple[j] = choicedata[1];
    j++;
  }*/
  
  for (var i = 0 ; i < data.length; i++) {
    
    if(data[i].includes('chociecheck')){
      var  choice = data[i].split('=');
      var choicedata = choice[0].split('_');
      choiceofmultiple[j] = choicedata[1];
      j++;
    }
  }
  
  if(dishid[1]!='' && choiceOfOne[1]!='' && dish_count[1]!=''){
    
    var dishDetails = getCookie('dishDetail');
    var OldDishDetails = dishDetails.dishDetail;
    
    if(OldDishDetails!=undefined){
      disharr = JSON.parse(OldDishDetails);
    }
       
    adddish = 0;

    for (var i = 0; i < disharr.length; i++) {
      difference = $(choiceofmultiple).not(disharr[i].Multiplechoice).get();
      
      
      if(disharr[i].dishId==dishid[1])
      {
        if(disharr[i].choiceOfOne == choiceOfOne[1]  && difference.length ==0){
          adddish = 1;
          var id =disharr[i].id;
        }
        else if(choiceOfOne2[1]!=undefined && disharr[i].choiceOfOne2 == choiceOfOne2[1] && difference.length ==0){
          adddish = 1;
          var id =disharr[i].id;
        }else{
          var id =disharr.length + 1;
          adddish = 0;
        }
        if(adddish ==1){
          disharr[i].dishcount =parseInt(disharr[i].dishcount)+parseInt(dish_count[1]);
          disharr[i].id =id;
        }
            
      }else{
        
      } 
      
    }
    
    
    if(adddish==0){

      if(OldDishDetails != undefined){
        var id =disharr.length + 1;
      }else{
         var id=1;
      }
      var dishdata = {
        "id":id,
        "dishId":dishid[1],
        "choiceOfOne":choiceOfOne[1],
        "choiceOfOne2":choiceOfOne2[1],
        "Multiplechoice":choiceofmultiple,
        "dishcount":dish_count[1],
        "res_id":res_id
      };
      disharr.push(dishdata);
    }
    dishDetails = JSON.stringify(disharr);
    customtoastr("Dish Added Successfully",'show');
    addproduct.close();
    //console.log(dishDetails);
    document.cookie = "dishDetail="+dishDetails+"; expires="+lastday+"; path=/";  
  }
}

//Add dish to cart which is without choice
$(document).on('click','.addcart',function(){
  var dish_Id = $(this).attr('dish_id');
  var res_id  = $(this).attr('res_id');

  var dishDetails = getCookie('dishDetail');
  var OldDishDetails = dishDetails.dishDetail;
  var dishdata = new Array();
  var disharr = [];
  var dishno = 1;
  var restaurant_id =$("#restaurant_id").val();
  if(OldDishDetails!=undefined){

    disharr = JSON.parse(OldDishDetails);
  }

  adddish = 0;
  for (var i = 0; i < disharr.length; i++) {
    if(disharr[i].dishId == dish_Id)
    {
      disharr[i].dishcount =parseInt(disharr[i].dishcount)+parseInt(dishno);
      adddish = 1;
    }
  }
  if(OldDishDetails!=undefined && disharr.length>0){
    
    id=disharr.length+1;
  }else{
    id=1;
  } 

  if(adddish==0){
    dishdata = {
      "id":id,
      "dishId":dish_Id,
      "choiceOfOne":"",
      "choiceOfOne2":"",
      "Multiplechoice":[],
      "dishcount":dishno,
      "res_id":res_id
      
    };
    disharr.push(dishdata);
  }

  dishDetails = JSON.stringify(disharr);
  customtoastr("Dish Added Successfully",'show');
  //console.log(dishDetails);
  document.cookie = "dishDetail="+dishDetails+"; expires="+lastday+"; path=/";  

  
});

$(document).on('change','input[type=radio][name=radio-group1]',function() {
  var addonprice = $(this).attr('price');
  var dishcount = ($('#stepper_val').val()!='')?$('#stepper_val').val():1;
  var catPrice2 = $('#catPrice2').val();
  var catPrice3 = $('#catPrice3').val();
   $('#catPrice1').val(addonprice);

  if(addonprice!="null")
  {
    var product_price = $('#product_add').find('#product_price').val();
    var finalprice = (parseFloat(product_price) +  parseFloat(addonprice) +  parseFloat(catPrice2) +  parseFloat(catPrice3)) * dishcount;
    $('#product_add').find('#addonprice').val(parseFloat(addonprice).toFixed(3));
    $('#product_add').find('.finalprice').html(parseFloat(finalprice).toFixed(3)+' KD');
    $('#product_add').find('#finalprice').val(parseFloat(finalprice).toFixed(3));
  }
});
$(document).on('change','input[type=radio][name=radio-group2]',function() {
  var addonprice = $(this).attr('price');
  var dishcount = ($('#stepper_val').val()!='')?$('#stepper_val').val():1;
  var catPrice1 = $('#catPrice1').val();
  var catPrice3 = $('#catPrice3').val();
   $('#catPrice2').val(addonprice);

  if(addonprice!="null")
  {

    var product_price = $('#product_add').find('#product_price').val();
    var finalprice = (parseFloat(product_price) +  parseFloat(catPrice1) +  parseFloat(addonprice) +  parseFloat(catPrice3)) * dishcount;
    $('#product_add').find('#addonprice').val(parseFloat(addonprice).toFixed(3));
    $('#product_add').find('.finalprice').html(parseFloat(finalprice).toFixed(3)+' KD');
    $('#product_add').find('#finalprice').val(parseFloat(finalprice).toFixed(3));
  }
});
$(document).on('change','input[type=radio][name=radio-group3]',function() {
  var addonprice = $(this).attr('price');
  var dishcount = ($('#stepper_val').val()!='')?$('#stepper_val').val():1;
  var catPrice1 = $('#catPrice1').val();
  var catPrice2 = $('#catPrice2').val();
   $('#catPrice3').val(addonprice);

  if(addonprice!="null")
  {
    var product_price = $('#product_add').find('#product_price').val();
    var finalprice = (parseFloat(product_price) +  parseFloat(catPrice1) +  parseFloat(catPrice2) +  parseFloat(addonprice)) * dishcount;
    $('#product_add').find('#addonprice').val(parseFloat(addonprice).toFixed(3));
    $('#product_add').find('.finalprice').html(parseFloat(finalprice).toFixed(3)+' KD');
    $('#product_add').find('#finalprice').val(parseFloat(finalprice).toFixed(3));
  }
});

$(window).scroll(function(){
  //console.log($(window).scrollTop());
  if ($(window).scrollTop() >= 240 ) {
    $(".dishes_menu").addClass("sticky");
  }
  else 
  {
    $(".dishes_menu").removeClass("sticky");
  }
});

$(document).ready(function(){
  // Add smooth scrolling to all links
  $(document).on('click',"a",function(event) {
    // Make sure this.hash has a value before overriding default behavior
    var listattr = $(this).attr('list');

    if (this.hash !== "" && listattr=='cat') {
      // Prevent default anchor click behavior
      event.preventDefault();
      // Store hash
      var hash = this.hash;
      $('.active').removeClass('active');
      $(this).parent('li').addClass('active');
      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 1500, function(){
   
        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });
    } // End if
  });
});



$("body").click(function() {
    $("#myDropdown").slideUp('fast');
    cartflag = 0;

});

function checkMultipleChoiceIsSame(currChoise,preChoise){
  var is_same = (currChoise.length == preChoise.length) && currChoise.every(function(element, index) {
    return 1; 
});
}


$(document).on('keyup','#search',function(){
  var search =$(this).val();
  getdishcat(localityId,search);
  getMainDishes(localityId,search);
})