$(window).load(function() {
    var cookie = getCookie();
    getpopulardishes(cookie.restaurant_id);
    getautocomplete();
});

function getpopulardishes(locality) {
    $.ajax({
        type: 'POST',
        url: bestDishes + locality,
        beforeSend: function() {},
        success: function(obj) {
            var data = JSON.parse(obj);
            var html = "";
            if (data.success == "1") {
              if(data.dish.length>0)
              {
                  html += '<div class="owl-carousel owl-theme" id="popular_dish">';
                  for (var i = 0; i < data.dish.length; i++) {
                      html += '<div class="item">';
                      html += '<div class="popular_dishes_box">';
                      html += '<div class="popular_dishes_box_top">';
                      html += '<img src="' + imagepath + data.dish[i].dish_image + '">';
                      html += '<div class="order_btn">';
                      html += '<a href="' + baseurl + 'Home/dishes">' + OrderNow + '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>';
                      html += '</div>';
                      html += '</div>';
                      html += '<div class="popular_dishes_box_btm">';
                      if (data.dish[i].dishName != null) {
                          html += '<h3>' + data.dish[i].dishName + '</h3>';
                      } else {
                          html += '<h3></h3>';
                      }
                      if (data.dish[i].description != null) {
                          html += '<p>' + data.dish[i].description + '</p>';
                      } else {
                          html += '<h3></h3>';
                      }
                      html += '</div></div></div>';
                  }
                  html += '</div>';
              }
              
                $('.dishesappend').html(html);
                initializeslider('#popular_dish');
            } else 
            {
                $(".popular_dishes").remove();
            }
            $(".restaurant_img").attr('src', base_url + "assets/uploads/restaurants/" + data.Restaurant[0].banner_image);
            $(".resDetails").find('p').find('span').text(data.Restaurant[0].restaurant_name);
            if (data.mostSellingDishes.length > 0) {
                $("#foodOfMonthName").text(data.mostSellingDishes[0].dishName)
                $("#foodOfMonthImage").attr('src', imagepath + data.mostSellingDishes[0].dish_image);
            }
        }
    });
}

function initializeslider(sliderid) {
    $(sliderid).owlCarousel({
        loop: true,
        margin: 30,
        dots: false,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: true
            },
            600: {
                items: 2,
                nav: true
            },
            1000: {
                items: 3,
                nav: true,
                loop: false,
                margin: 30
            }
        }
    });
}

function initMap(lat = 29.3117, lon = 47.4818) {

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
    google.maps.event.addListener(marker, 'dragend', function(event) {
        document.getElementById("lat").value = event.latLng.lat();
        document.getElementById("long").value = event.latLng.lng();
        //infoWindow.open(map, marker);
    });
}