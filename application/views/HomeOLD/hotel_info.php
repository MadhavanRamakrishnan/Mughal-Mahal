<style type="text/css">
.order_progressbar span {
	font-size: 20px;
}
</style>
 <script type="text/javascript">
      $(document).ready(function () {
       google.maps.event.addDomListener(window, 'load', initialize);
    });

    function initialize() {
        var input = document.getElementById('order_map');
        var autocomplete = new google.maps.places.Autocomplete(input);
    }
</script>
<div class="sub_page_main">
	<div class="hotel_info">
		<div class="container">
			<div class="hotel_info_top">
				<h4><?php echo $resInfo[0]->restaurant_name; ?></h4>
				<p><i class="fa fa-phone"></i>&nbsp (+965) <?php echo $resInfo[0]->contact_no; ?></p>
				<p><i class="fa fa-envelope"></i>&nbsp<?php echo $resInfo[0]->email; ?></p>
				
			</div>
			<div class="hotel_info_btm">
				<ul>
					<li><i class="fa fa-home"></i>&nbsp<?php echo $resInfo[0]->address; ?></li>
				</ul>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
					<div class="hotel_info_left">
						<h4><?php echo $this->lang->line('message_hotel_timing'); ?></h4>
						<div class="table-responsive">          
							<table class="table">
								<tbody>
							    	<?php foreach ($days as $key => $value) { ?>
                                        <tr>
                                           <th ><?php echo $value; ?></th>
                                           <td class="list-group list-group-horizontal">
                                            <?php if(isset($resData[$key]) && count($resData[$key])>0){ 
                                                
                                                foreach ($resData[$key] as $k1 => $v1) {?>

                                                  <?php echo $v1['from_time']."-".$v1['to_time']; ?>&nbsp&nbsp&nbsp
                                            <?php }  } ?>
                                          </td>
                                        </tr>
                                      <?php } ?>
							    </tbody>
							</table>
					    </div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
					<div class="hotel_info_right">
						<h4><?php echo $this->lang->line('message_other_details'); ?></h4>
						<ul>
							<li>
								<span><?php echo $this->lang->line('message_Call'); ?></span> (+965) 
								<?php echo $resInfo[0]->delivery_contact_no ?>
							</li>
							<li>
								<span><?php echo $this->lang->line('message_minimum_order'); ?></span>
								<?php echo $resInfo[0]->min_order_amount ?> KD
							</li>
							<li>
								<span><?php echo $this->lang->line('message_standard_del_time'); ?></span>
								<?php echo $resInfo[0]->delivered_time ?> Min
							</li>
							<li>
								<span><?php echo $this->lang->line('message_delivery_charges'); ?></span>
								<?php echo $resInfo[0]->delivery_charge ?> KD
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url('assets/js/front-end/myOrder.js'); ?>"></script> 
<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAaWqUMoAlFiTao-1cDOQTtckOyA43eWQ4&libraries=places&callback=initMap"></script>
<script>
/*    setTimeout(function(){ location.reload(); }, 3000);*/
   var oID ="<?php echo $orderDetails['order_id'] ?>";
   var lat ="<?php echo $orderDetails['delivery_address']['customer_latitude'] ?>";
   var lon ="<?php echo $orderDetails['delivery_address']['customer_longitude'] ?>";
   initMap(lat,lon);

	function initMap(lat=23.029187,lon=72.594909) {
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

</script>

