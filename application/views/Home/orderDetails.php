<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
 
<!-- <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/css/bootstrap/bootstrap.min.css"> -->
<div class="banner bannerInner section" style="background-image: url(../../assets/images/front-end/orderBanner.jpg); ">
	<div class="bannerBack">
		<div class="container">
			<h1 class="wow fadeInDown" data-wow-duration="1s" data-wow-delay="0" style="visibility: visible; animation-duration: 2s; animation-name: fadeInDown;"><?php  echo $this->lang->line('message_OrderNow'); ?></h1>
		</div>
	</div>
</div>
<div class="section" id="ordernowSection">
	<div class="container">

		<div class="sub_page_main">
			<div class="your_order">
				<div class="container">

					<div class="row wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInUp;">
						<!-- <p class="orderNum"><?php echo $this->lang->line('message_Order')."#".$orderDetails['order_id']; ?></p> -->
						<p class="orderNum"><?php echo $this->lang->line('message_Order')."#".$orderDetails['sequence_no']; ?></p>						
						<p class="del_time">
							<?php if($orderDetails['order_status']>=7)
							{
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
							<?php
								if($orderDetails['order_status'] == 1){
							?>
									<p class='del_time cancelOrder_tag'><a style="color: red; font-size: 15px; cursor: pointer;" data-toggle="modal" data-target="#reasonModal">Cancel Order</a></p>
							<?php
								}
							?>
						</p>
					</div>

					<div class="clear"></div>

		      <!-- <div class="row orderStatus wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInUp;">
		            <div class="orderOuter">

		                <ul class="orderOuterUl">
		                    <li <?= ($orderDetails['order_status'] >1)? 'class="active" ' :"" ?> ><?php echo $this->lang->line('message_OrderPlaced'); ?></li>
		                    <li <?= ($orderDetails['order_status'] >2)? 'class="active" ' :"" ?> ><?php echo $this->lang->line('message_Foodisbeingprepared'); ?></li>
		                    <li <?= ($orderDetails['order_status'] >3)? 'class="active" ' :"" ?> ><?php echo  $this->lang->line('message_Foodoutfordelivery'); ?></li>
		                    <li <?= ($orderDetails['order_status'] >5)? 'class="active" ' :"" ?> ><span><?php echo $this->lang->line('message_Delivered'); ?></li>
		                </ul>
		            </div>
		        </div> -->

		        <ul id="progressbar" class="progressbar<?php echo $orderDetails['order_id']; ?>">

		        	<li class="<?php echo ($orderDetails['order_status'] >1)?"active  complete dot":"active process dot"; ?> "><span><?php echo $this->lang->line('message_OrderPlaced'); ?></span></li>
		        	<li class="<?php echo ($orderDetails['order_status'] >1)?"active":""; ?>  nodot"></li>
		        	<li class="<?php echo ($orderDetails['order_status'] >=3)?($orderDetails['order_status'] ==3)?"active process dot":"active  complete dot":""; ?> "><span><?php echo $this->lang->line('message_Foodisbeingprepared'); ?></span></li>
		        	<li class="<?php echo ($orderDetails['order_status'] >3)?"active":""; ?> nodot"></li>
		        	<li class="<?php echo ($orderDetails['order_status'] >=5)?($orderDetails['order_status'] ==5)?"active process dot":"active  complete dot":""; ?>"><span><?php echo  $this->lang->line('message_Foodoutfordelivery'); ?></span></li>
		        	<li class="<?php echo ($orderDetails['order_status'] >5)?"active ":""; ?> nodot"></li>
		        	<li class="<?php echo ($orderDetails['order_status'] >6 )?"active  complete dot":"" ;?>"><span><?php echo $this->lang->line('message_Delivered'); ?></span></li>

		        </ul>

		        <div class="clear"></div>


		        <div class="row customerrow">
		        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		        		<div class="order_address wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInUp;">
		        			<h4><?php echo $this->lang->line('message_Customer'); ?></h4>
		        			<div class="res_detail_left res_detail_left-top">
		        				<ul>
		        					<li><i class="fa fa-user" aria-hidden="true"></i> &nbsp <?php echo $orderDetails['address']['cust_name']; ?></li>
		        					<li><i class="fa fa-phone" aria-hidden="true"></i> &nbsp (+965) <?php echo $orderDetails['address']['cust_contact_no']; ?></li>
		        					<li><i class="fa fa-home" aria-hidden="true"> </i>  &nbsp 
		        						<font size="3" color="#00AE4A">
		        							<?= ($orderDetails['address']['address_type'] == 1 ? "Home" : ($orderDetails['address']['address_type'] == 2 ? "Office" : ucfirst($orderDetails['address']['other_address']))) ?>
		        						</font>
		        						<font size="1">
			        					   <?= ($orderDetails['address']['appartment_no'] !="")?$orderDetails['address']['appartment_no'].",&nbsp":""; ?>
	                                        <?= ($orderDetails['address']['floor']!="")?"Floor -".$orderDetails['address']['floor'].",&nbsp":"";  ?>
	                                        <?= ($orderDetails['address']['block']!="")?"Block -".$orderDetails['address']['block'].",&nbsp":""; ?>
	                                        <?= ($orderDetails['address']['building']!="")?"Building -".$orderDetails['address']['building'].",&nbsp":"" ?>
	                                        <?= ($orderDetails['address']['street'] !="")?$orderDetails['address']['street'].',&nbsp':''; ?>
	                                        <?= ($orderDetails['address']['avenue']!="")?$orderDetails['address']['avenue'].',&nbsp':''; ?>
	                                        <?= ($orderDetails['address']['cust_address']!="")?$orderDetails['address']['cust_address']:""; ?>
                                    	</font>
		        					</li>
		        				</ul>
		        			</div>            
		        		</div>
		        	</div>
		        	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		        	</div>
		        </div>


		        <div class="row yourorderrow wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.6s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInUp;">
		        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		        		<div class="order_address order_address-new wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInUp;">
		        			<h4>Your Order</h4>
		        			<div class="orderBox">
		        				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 yourorderleft">
		        					<div class="order_left order_left-new">

		        						<div class="order_table">
		        							<div class="table-responsive">          
		        								<table class="table dish_table">
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
		        													<span><?php echo 'Instruction:- '.$dishvalue['description']; ?></span>
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
		        				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 yourorderright">
		        					<div class="order_sammary order_sammary-new">
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

		        <div class="row restaurantDetailsrow">
		        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		        		<div class="order_address wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.9s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInUp;">
		        			<h4><?php echo $this->lang->line('message_RestaurantDetails'); ?></h4>
		        			<div class="res_detail_left res_detail_left-new">
		        				<ul>
		        					<li><i class="fa fa-home"></i> <span class="res_address"><?php echo $orderDetails['restaurant']['name']; ?></span></li>
		        					<li><i class="fa fa-phone"></i> (+965)  <span class="phone_address"><?= $orderDetails['restaurant']['res_contact_no']; ?></span></li>

		        					<li><i class="fa fa-envelope"></i> <span class="email_address">&nbsp;&nbsp;&nbsp <?php echo $orderDetails['restaurant']['res_address']; ?></span></li>
		        					<li><i class="fa fa-clock-o"></i> <span class="email_address">&nbsp;&nbsp;&nbsp <?php echo $orderDetails['restaurant']['res_time']; ?></span></li>
		        				</ul>
		        			</div>

		        		</div>
		        	</div>
		        </div>

		        <?php if($orderDetails['order_status'] >3 && $orderDetails['order_status'] <7){ ?>

		        	<div class="row restaurantDetailsrow">
		        		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		        			<div class="order_address wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.9s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInUp;">
		        				<h4><?php echo $this->lang->line('message_Driver'); ?></h4>
		        				<div class="res_detail_left res_detail_left-new">
		        					<ul>
		        						<li><i class="fa fa-user"></i> <span class="res_address"> &nbsp <?php echo $orderDetails['driver']['d_first_name']." ".$orderDetails['driver']['d_last_name']; ?></span></li>
		        						<li><i class="fa fa-phone"></i> (+965)  <?php echo $orderDetails['driver']['res_contact_no']; ?></li>
		        						<li><i class="fa fa-envelope"></i><?php echo $orderDetails['driver']['d_email']; ?></li>
		        					</ul>
		        				</div>

		        			</div>
		        		</div>
		        	</div>

		        <?php } ?>

		        <?php if($orderDetails['order_status'] >=7){ ?>
		        	<div class="row restaurantDetailsrow">
		        		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		        			<div class="order_rating wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.9s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInUp;">
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
		        									<input  type="checkbox" name="res_pot[]" value="<?php echo $this->lang->line('message_WellDriver'); ?>" ><?php echo $this->lang->line('message_WellDriver'); ?>
		        								</li>
		        								<li>
		        									<input  type="checkbox" name="res_pot[]" value="<?php echo $this->lang->line('message_OnTimeDelivery'); ?> " >  <?php echo $this->lang->line('message_OnTimeDelivery'); ?>
		        								</li>
		        								<li>
		        									<input  type="checkbox" name="res_pot[]" value="<?php echo $this->lang->line('message_ValueForMoney'); ?>"><?php echo $this->lang->line('message_ValueForMoney'); ?>
		        								</li>
		        								<li>
		        									<input  type="checkbox" name="res_pot[]" value="<?php echo $this->lang->line('message_Other'); ?>"><?php echo $this->lang->line('message_Other'); ?>
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
		        									<input name="res_pot[]" type="checkbox" value="<?php echo $this->lang->line('message_FoodNotWell'); ?>" ><?php echo $this->lang->line('message_FoodNotWell'); ?>
		        								</li>
		        								<li>
		        									<input name="res_pot[]" type="checkbox" value="<?php echo $this->lang->line('message_DeliveredLate'); ?>"><?php echo $this->lang->line('message_DeliveredLate'); ?>
		        								</li>
		        								<li>
		        									<input name="res_pot[]" type="checkbox" value="<?php echo $this->lang->line('message_FoodWasRaw'); ?>"><?php echo $this->lang->line('message_FoodWasRaw'); ?>
		        								</li>
		        								<li>
		        									<input name="res_pot[]" type="checkbox" value="Other"><?php echo $this->lang->line('message_Other'); ?>
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
		        									<input  type="checkbox" name="res_pot[]" value="<?php echo $this->lang->line('message_WellDriver'); ?>" ><?php echo $this->lang->line('message_WellDriver'); ?>
		        								</li>
		        								<li>
		        									<input  type="checkbox" name="res_pot[]" value="<?php echo $this->lang->line('message_OnTimeDelivery'); ?> " >  <?php echo $this->lang->line('message_OnTimeDelivery'); ?>
		        								</li>
		        								<li>
		        									<input  type="checkbox" name="res_pot[]" value="<?php echo $this->lang->line('message_ValueForMoney'); ?>"><?php echo $this->lang->line('message_ValueForMoney'); ?>
		        								</li>
		        								<li>
		        									<input  type="checkbox" name="res_pot[]" value="<?php echo $this->lang->line('message_Other'); ?>"><?php echo $this->lang->line('message_Other'); ?>
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
		        						<div class="negative_driv" style="display:none;">
		        							<p><?php echo $this->lang->line('message_WentWrong'); ?></p>
		        							<ul>
		        								<li>
		        									<input name="res_pot[]" type="checkbox" value="<?php echo $this->lang->line('message_FoodNotWell'); ?>" ><?php echo $this->lang->line('message_FoodNotWell'); ?>
		        								</li>
		        								<li>
		        									<input name="res_pot[]" type="checkbox" value="<?php echo $this->lang->line('message_DeliveredLate'); ?>"><?php echo $this->lang->line('message_DeliveredLate'); ?>
		        								</li>
		        								<li>
		        									<input name="res_pot[]" type="checkbox" value="<?php echo $this->lang->line('message_FoodWasRaw'); ?>"><?php echo $this->lang->line('message_FoodWasRaw'); ?>
		        								</li>
		        								<li>
		        									<input name="res_pot[]" type="checkbox" value="Other"><?php echo $this->lang->line('message_Other'); ?>
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

</div>
</div>

<div class="modal fade" id="reasonModal" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cancel Order</h4>
			</div>
			<div class="modal-body">
				<div class="cancelOrder_resion">
				<label style="padding-bottom: 15px; margin-bottom: 0;">Enter Reason</label>
				<textarea name="reason" id="reasonText" style="width: 100%;padding: 5px 10px; height: 80px;"></textarea>
				<span class="reasonTextError" style="color: red;display: none;">Please add reason for cancel.</span>
			</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" onclick="cancelUserOrder('<?= $orderDetails['order_id'] ?>')">Submit</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>

<input type="hidden" id="deliveryTime" value="<?php echo date('Y-m-d H:i:s'); ?>">
<script src="<?= base_url('assets/front-end/js/myOrder.js'); ?>"></script> 
<script type="text/javascript">
	var oID ="<?php echo $orderDetails['order_id'] ?>";
	var cancelOrderAPI     = "<?= base_url('Home/cancelOrder')?>";

	function cancelUserOrder(orderId){

		if($('#reasonText').val() == ''){
			$('.reasonTextError').show();
			return false;
		}else{
			$('.reasonTextError').hide();
		}

		$.ajax({
			url : cancelOrderAPI,
			type: 'POST',
			data: {
				order_id : orderId,
				reason : $('#reasonText').val()
			},
			success: function(response){

				var obj = JSON.parse(response);

				if(obj.success == 1){

					window.location.reload();
				}else{
					alert("Can not cancel order!");
					$('#reasonText').val('');
				}
			}
		});
	}
</script>