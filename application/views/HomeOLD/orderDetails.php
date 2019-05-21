<?php

$host =  $_SERVER['HTTP_HOST'];
//echo "<pre>"; print_r($_SERVER);exit;

if($host!='18.216.199.131'){

		$host="http://localhost";

}

if($host=="18.216.199.131"){

    $host = "http://18.216.199.131";
}



?>
<style type="text/css">
.order_progressbar span {
	font-size: 20px;
}
</style>
<div class="sub_page_main">

	<div class="order_detail">
		<div class="container">
			<div class="row">
				<div class="order_progressbar">
					<h2><?php echo $this->lang->line('message_OrderDeatil'); ?></h2>
					<div class="order_top">
								<div class="order_top_left">
								<span><?php echo $this->lang->line('message_Order')."#".$orderDetails['order_id']; ?></span>
								</div>
								<div class="order_top_right  trackingTime<?php echo ($orderDetails['order_status']==7)?"delivered":""; ?>">
								   <?php if($orderDetails['order_status']>=7){
								    
								    echo $this->ORD_status[$orderDetails['order_status']]; 
								    
								    ?>
										   
										<span><?php echo date('dS, F', strtotime($orderDetails['delivered_time'])); ?></span>
								   <?php }else{
								   		$datetime2 = new DateTime();
										$datetime1 = new DateTime($orderDetails['delivery_time']);
										$interval = $datetime1->diff($datetime2);
										$hr       =$interval->format('%h');
										$mn       =$interval->format('%i');
										echo ($hr != 0)?$hr."hrs":"";
										echo ($mn != 0)?" ".$mn."mins":"";
										
								    ?>
									<span><?php echo $this->lang->line('message_EstDeliveryTime'); ?></span>
									<?php }?>
								</div>
							</div>
					
					<ul id="progressbar" class="progressbar<?php echo $orderDetails['order_id']; ?>">

					    <li class="<?php echo ($orderDetails['order_status'] >1)?"active  complete dot":"active process dot"; ?> "><span><?php echo $this->lang->line('message_OrderPlaced'); ?></span></li>
					    <li class="<?php echo ($orderDetails['order_status'] >1)?"active":""; ?>  nodot"></li>
					    <li class="<?php echo ($orderDetails['order_status'] >=3)?($orderDetails['order_status'] ==3)?"active process dot":"active  complete dot":""; ?> "><span><?php echo $this->lang->line('message_Foodisbeingprepared'); ?></span></li>
					    <li class="<?php echo ($orderDetails['order_status'] >3)?"active":""; ?> nodot"></li>
					    <li class="<?php echo ($orderDetails['order_status'] >=5)?($orderDetails['order_status'] ==5)?"active process dot":"active  complete dot":""; ?>"><span><?php echo  $this->lang->line('message_Foodoutfordelivery'); ?></span></li>
					    <li class="<?php echo ($orderDetails['order_status'] >5)?"active ":""; ?> nodot"></li>
					    <li class="<?php echo ($orderDetails['order_status'] >6 )?"active  complete dot":"" ;?>"><span><?php echo $this->lang->line('message_Delivered'); ?></span></li>
				
					</ul>
				</div>
			</div>

			<div class="row">
				<?php if($orderDetails['order_status']==5){ ?>
		                    <button id="track_order" style="width: auto;float: right; " class="btn btn-success"><i class="fa fa-map-marker"></i> Track order</button>
		                <?php } ?>
				<div class="order_location" style="<?= ($orderDetails['order_status']==5)?"padding-top:20px;":""; ?>">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="order_address">
							<h5><?php echo $this->lang->line('message_Customer'); ?></h5>
							<p><i class="fa fa-user" aria-hidden="true"></i> &nbsp <?php echo $orderDetails['address']['cust_name']; ?></p>
							<p><i class="fa fa-phone" aria-hidden="true"></i> &nbsp (+965) <?php echo $orderDetails['address']['cust_contact_no']; ?></p>
							<p><i class="fa fa-home" aria-hidden="true"> </i>  &nbsp <?php echo $orderDetails['address']['cust_address']; ?></p>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

						<div class="order_map" id="map">
						</div>
					</div>
					<input type="hidden" name="location_change" id="new_location">
					<button id="routebtn" style="display: none;">Route</button>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<div class="order_list_main">
					<h4><?php echo $this->lang->line('message_YourOrder'); ?></h4>
					<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
						<div class="order_list_left">
							<div class="order_table">
								<div class="table-responsive">          
									<table class="table">
										<tbody>
									    	<tr>
											    <th><?php echo $this->lang->line('message_Dish'); ?></th>
										        <th><?php echo $this->lang->line('message_Prise'); ?></th>
										        <th><?php echo $this->lang->line('message_Qty'); ?></th>
										        <th class="t_prise"><?php echo $this->lang->line('message_Total')." ".$this->lang->line('message_Prise'); ?></th>
										    </tr>
										    <?php $subtotal = 0;
										    foreach ($orderDetails['dishes'] as $dishkey => $dishvalue) {
										    	
										     ?>
										    <tr>
										        <td>
										        	<?php echo $dishvalue['product_en_name'] ?><span><?php echo trim($dishvalue['choices'],','); ?></span>
										        </td>
										        <td>
										        	KD <?php echo number_format($dishvalue['price']/$dishvalue['quantity'],3) ?>
										       </td>
										        <td><?php echo $dishvalue['quantity']; ?></td>
										        <td class="t_prise">KD <?php echo number_format( $dishvalue['price'],3) ?></td>
									      	</tr>
										    <?php } ?>
									    </tbody>
									</table>
							    </div>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
						<div class="order_list_right">
							<div class="order_sammary">
								<ul>
									<li><?php echo $this->lang->line('message_Subtotal'); ?> <span>KD <?php echo number_format($orderDetails['total_price']-$orderDetails['delivery_charges'],3) ?></span></li>
									<li><?php echo $this->lang->line('message_DeliveryFee'); ?> <span>KD <?php echo number_format($orderDetails['delivery_charges'],3) ?></span></li>
									<li><?php echo $this->lang->line('message_Total')." ".$this->lang->line('message_Amount'); ?> <span>KD <?php echo number_format($orderDetails['total_price'],3) ?></span></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				</div>
				
			</div>
			<div class="row"> 
				<div class="col-lg-12">
					<div class="order_rating">
						<h4><?php echo $this->lang->line('message_RestaurantDetails'); ?></h4>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="food_rating restaurant_details">
								<h5><?php echo $this->lang->line('message_Restaurant'); ?></h5>
								<p><i class="fa fa-home" aria-hidden="true"></i> &nbsp <?php echo $orderDetails['restaurant']['name']; ?></p>
								<p><i class="fa fa-phone" aria-hidden="true"></i> &nbsp (+965) <?php echo $orderDetails['restaurant']['res_contact_no']; ?></p>
								<p><i class="fa fa-envelope" aria-hidden="true"></i> &nbsp <?php echo $orderDetails['restaurant']['res_email']; ?></p>
								<p><i class="fa fa-map-marker" aria-hidden="true"> </i>  &nbsp <?php echo $orderDetails['restaurant']['res_address']; ?></p>
							</div>
						</div>
						<?php if($orderDetails['order_status'] >3 && $orderDetails['order_status'] <7){ ?>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="delivery_rating restaurant_details">
								<h5><?php echo $this->lang->line('message_Driver'); ?></h5>
								<p><i class="fa fa-user" aria-hidden="true"></i> &nbsp <?php echo $orderDetails['driver']['d_first_name']." ".$orderDetails['driver']['d_last_name']; ?></p>
								<p><i class="fa fa-phone" aria-hidden="true"></i> &nbsp (+965) <?php echo $orderDetails['driver']['res_contact_no']; ?></p>
								
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				
			</div>
			<?php if($orderDetails['order_status'] >=7){ ?>
				<div class="row">
					<div class="col-lg-12">
						<div class="order_rating">
						<h4><?php echo $this->lang->line('message_Rating'); ?></h4>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="food_rating" >
								<h5><?php echo $this->lang->line('message_FoodRating'); ?></h5>
								<ul class="rate_list">
									<?php if($orderDetails['rating'] ==0){ 
										for ($i=1; $i <=5; $i++) { ?>
											<li>
												<a href="javascript:void(0)" class="res_rating" rating="<?php echo $i; ?>" rsId="<?php echo $orderDetails['restaurant_id'] ?>" oID="<?php echo $orderDetails['order_id'] ?>"><img src="<?php echo base_url('assets/css/images/icon/star-grey.png'); ?>" alt=""></a>
											</li>
									<?php }
									   }else{ 

									    for ($i=0; $i < $orderDetails['rating']; $i++) { ?>
											<li><img src="<?php echo base_url('assets/css/images/icon/star-color.png'); ?>" alt=""></li>
										<?php }?>
										<?php for ($i=$orderDetails['rating']; $i <5; $i++) { ?>
											<li><img src="<?php echo base_url('assets/css/images/icon/star-grey.png'); ?>" alt=""></li>
									<?php }
									   }
									?>

								</ul>
							</div>
							<div class="reason_list">
								<div class="positive_res" style="display:none;">
									<p><?php echo $this->lang->line('message_WentPerfect'); ?></p>
						   			<ul>
						   				<li>
						   					<input  type="radio" name="res_pot" value="<?php echo $this->lang->line('message_WellDriver'); ?>" checked><?php echo $this->lang->line('message_WellDriver'); ?>
						   				</li>
										<li>
											<input  type="radio" name="res_pot" value="<?php echo $this->lang->line('message_OnTimeDelivery'); ?> " >  <?php echo $this->lang->line('message_OnTimeDelivery'); ?>
										</li>
										<li>
											<input  type="radio" name="res_pot" value="<?php echo $this->lang->line('message_ValueForMoney'); ?>"><?php echo $this->lang->line('message_ValueForMoney'); ?>
										</li>
										<li>
											<input  type="radio" name="res_pot" value="<?php echo $this->lang->line('message_Other'); ?>"><?php echo $this->lang->line('message_Other'); ?>
										</li>
										<li>
											<textarea style="display:none" class="other_reason" placeholder="<?php echo $this->lang->line('message_EnterOtherReason'); ?>"></textarea>
										</li>
										<li>
											<span class="error"></span>
										</li>
										<li  class="addratebtn">
											<a href="javascript:void(0)"><?php echo $this->lang->line('message_add'); ?></a>
										</li>
						   			</ul>
						   		</div>
						   		<div class="negative_res" style="display:none;">
									<p><?php echo $this->lang->line('message_WentWrong'); ?></p>
						   			<ul>
						   				<li>
						   					<input name="res_pot" type="radio" value="<?php echo $this->lang->line('message_FoodNotWell'); ?>" checked><?php echo $this->lang->line('message_FoodNotWell'); ?>
						   				</li>
										<li>
											<input name="res_pot" type="radio" value="<?php echo $this->lang->line('message_DeliveredLate'); ?>"><?php echo $this->lang->line('message_DeliveredLate'); ?>
										</li>
										<li>
											<input name="res_pot" type="radio" value="<?php echo $this->lang->line('message_FoodWasRaw'); ?>"><?php echo $this->lang->line('message_FoodWasRaw'); ?>
										</li>
										<li>
											<input name="res_pot" type="radio" value="Other"><?php echo $this->lang->line('message_Other'); ?>
										</li>
										<li>
											<textarea style="display:none" class="other_reason" placeholder="<?php echo $this->lang->line('message_EnterOtherReason'); ?>"></textarea>
										</li>
										<li>
											<span class="error"></span>
										</li>
										<li class="addratebtn">
											<a href="javascript:void(0)" class="addResRate"><?php echo $this->lang->line('message_add'); ?></a>
										</li>
						   			</ul>
						   		</div>
						   	</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="delivery_rating">
								<h5><?php echo $this->lang->line('message_DeliveryRating'); ?></h5>
								<ul class="rate_list">
									<?php if($orderDetails['dr_rating'] ==0){ 
										 for ($i=1; $i <=5; $i++) { ?>
										
											<li>
												<a href="javascript:void(0)" class="driver_rating" rating="<?php echo $i; ?>" driver="<?php echo $orderDetails['driver_id'] ?>" rsId="<?php echo $orderDetails['restaurant_id'] ?>" oID="<?php echo $orderDetails['order_id'] ?>"><img src="<?php echo base_url('assets/css/images/icon/star-grey.png'); ?>" alt=""></a>
											</li>
									<?php }
									   }else{ 

									    for ($i=0; $i < $orderDetails['dr_rating']; $i++) { ?>
											<li><img src="<?php echo base_url('assets/css/images/icon/star-color.png'); ?>" alt=""></li>
										<?php }?>
										<?php for ($i=$orderDetails['dr_rating']; $i <5; $i++) { ?>
											<li><img src="<?php echo base_url('assets/css/images/icon/star-grey.png'); ?>" alt=""></li>
									<?php }
									   }
									?>
									
								</ul>
							</div>
							<div class="reason_list">
								<div class="positive_driv" style="display:none;">
									<p><?php echo $this->lang->line('message_WentPerfect'); ?></p>
						   			<ul>
						   				<li>
						   					<input  type="radio" name="res_pot" value="<?php echo $this->lang->line('message_WellDriver'); ?>" checked><?php echo $this->lang->line('message_WellDriver'); ?>
						   				</li>
										<li>
											<input  type="radio" name="res_pot" value="<?php echo $this->lang->line('message_OnTimeDelivery'); ?> " >  <?php echo $this->lang->line('message_OnTimeDelivery'); ?>
										</li>
										<li>
											<input  type="radio" name="res_pot" value="<?php echo $this->lang->line('message_ValueForMoney'); ?>"><?php echo $this->lang->line('message_ValueForMoney'); ?>
										</li>
										<li>
											<input  type="radio" name="res_pot" value="<?php echo $this->lang->line('message_Other'); ?>"><?php echo $this->lang->line('message_Other'); ?>
										</li>
										<li>
											<textarea style="display:none" class="other_reason" placeholder="<?php echo $this->lang->line('message_EnterOtherReason'); ?>"></textarea>
										</li>
										<li>
											<span class="error"></span>
										</li>
										<li  class="addratebtn">
											<a href="javascript:void(0)"><?php echo $this->lang->line('message_add'); ?></a>
										</li>
						   			</ul>						   		</div>
						   		<div class="negative_driv" style="display:none;">
									<p><?php echo $this->lang->line('message_WentWrong'); ?></p>
						   			<ul>
						   				<li>
						   					<input name="res_pot" type="radio" value="<?php echo $this->lang->line('message_FoodNotWell'); ?>" checked><?php echo $this->lang->line('message_FoodNotWell'); ?>
						   				</li>
										<li>
											<input name="res_pot" type="radio" value="<?php echo $this->lang->line('message_DeliveredLate'); ?>"><?php echo $this->lang->line('message_DeliveredLate'); ?>
										</li>
										<li>
											<input name="res_pot" type="radio" value="<?php echo $this->lang->line('message_FoodWasRaw'); ?>"><?php echo $this->lang->line('message_FoodWasRaw'); ?>
										</li>
										<li>
											<input name="res_pot" type="radio" value="Other"><?php echo $this->lang->line('message_Other'); ?>
										</li>
										<li>
											<textarea style="display:none" class="other_reason" placeholder="<?php echo $this->lang->line('message_EnterOtherReason'); ?>"></textarea>
										</li>
										<li>
											<span class="error"></span>
										</li>
										<li class="addratebtn">
											<a href="javascript:void(0)" class="addResRate"><?php echo $this->lang->line('message_add'); ?></a>
										</li>
						   			</ul>
						   		</div>
						   	</div>
						</div>
						</div>
					</div>
				</div>
			<?php } ?>
			
		</div>
	</div>
</div>
<input type="hidden" id="deliveryTime" value="<?php echo date('Y-m-d H:i:s'); ?>">
<script src="<?php echo base_url('assets/js/front-end/myOrder.js'); ?>"></script> 

<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAaWqUMoAlFiTao-1cDOQTtckOyA43eWQ4&libraries=places&callback=mapLocation"></script>
<script>
	
   var oID ="<?php echo $orderDetails['order_id'] ?>";
   var lat ="<?php echo $orderDetails['address']['cust_latitude'] ?>";
   var lon ="<?php echo $orderDetails['address']['cust_longitude'] ?>";
   
</script>
<script type="text/javascript" src="<?=$host; ?>:3333/socket.io/socket.io.js"></script>
<script type="text/javascript">

	var driverId = "<?= $orderDetails['driver_id']; ?>";
	var status   = "<?= $orderDetails['order_status']; ?>";
	driverLat = '';
	driverLon = '';
	if(status>3 && status<7)
	{
		jQuery(function ($) {

		var socket = io.connect('<?=$host; ?>'+':3333');
		$("#track_order").click(function(e){
            $(this).attr("disabled",true);
            socket.emit('new customer',driverId);
        });

		socket.on('connection_success',function(data){

		});

		socket.on('new location from driver',function(data){

			//var data = JSON.parse(data);
			document.getElementById('new_location').value = data.latitude+","+data.longitude;
			console.log("Data from socket"+data);
			//$("#new_location").val(data.latitude+","+data.longitude);
			
			if(data.user_id==driverId){

				driverLat = data.latitude;
				driverLon = data.longitude;
				$("#routebtn").click();
			}
			
		});
	});
	}
	



function mapLocation() {
	
    var directionsDisplay;
    var directionsService = new google.maps.DirectionsService();
    var map;

    function initialize() {

         /*directionsDisplay = new google.maps.DirectionsRenderer({
        suppressMarkers: true
    	});*/
    	directionsDisplay = new google.maps.DirectionsRenderer();
        var chicago = new google.maps.LatLng(lat, lon);
        var mapOptions = {
            zoom: 12,
            center: chicago
        };
        map = new google.maps.Map(document.getElementById('map'), mapOptions);
        directionsDisplay.setMap(map);

        
        google.maps.event.addDomListener(document.getElementById('routebtn'), 'click', calcRoute);
       
    }

    function calcRoute() {

        var start = new google.maps.LatLng(lat, lon);
        var end = new google.maps.LatLng(driverLat, driverLon);

        var bounds = new google.maps.LatLngBounds();
        bounds.extend(start);
        bounds.extend(end);
        map.fitBounds(bounds);
        var request = {
            origin: start,
            destination: end,
            travelMode: google.maps.TravelMode.DRIVING
        };

        

        /*var marker = new google.maps.Marker({
		    position: start,
		    map: map
		});

		var marker = new google.maps.Marker({
		    position: end,
		    map: map,
		    icon: '<?= base_url('assets/images/front-end/marker_small.png');?>'
		});*/

        directionsService.route(request, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
                directionsDisplay.setMap(map);
            } else {
                //alert("Directions Request from " + start.toUrlValue(6) + " to " + end.toUrlValue(6) + " failed: " + status);
            }
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
}


</script>

