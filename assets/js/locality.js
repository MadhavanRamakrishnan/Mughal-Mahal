window.setTimeout(function() { $(".alert").alert('close'); }, 3000);

     $(document).ready(function () {
        var lat=$("#lat").val();
        var lon=$("#lon").val();
        initMap(lat,lon);
    });
     function initMap(lat=29.3518587,lon=47.9836915) {
        var uluru = new google.maps.LatLng(lat, lon);
        var myOptions;
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            gestureHandling: 'greedy',
            center: uluru,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
         // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

         // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });
        var marker = new google.maps.Marker({
            draggable: true,
            scrollwheel: true,
            map: map,
            position: uluru,
            title: "Your location"
        });
        google.maps.event.addListener(marker, 'dragend', function (event) {
            document.getElementById("lat").value = this.getPosition().lat();
            document.getElementById("lon").value = this.getPosition().lng();
        });
        
        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function(event) {
           var places = searchBox.getPlaces();
         
          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              title: place.name,
              position: place.geometry.location,
              draggable: true,
              scrollwheel: true,
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
           document.getElementById("lat").value = place.geometry.location.lat();
           document.getElementById("lon").value = place.geometry.location.lng();
          });
          map.fitBounds(bounds);
        });
    }