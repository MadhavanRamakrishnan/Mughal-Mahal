
<style type="text/css">
	#myModalLabel ,.modal-body{text-align: center; color: #666;}
	.btn-success{    background-color: #00AE4A;}
	
</style>
<div class="sub_page_main">
	<div class="your_order">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="order_left">
						<h4><?php  echo $this->lang->line('message_YourOrder'); ?></h4>
						<div class="order_table">
							<div class="table-responsive">          
								<table class="table dish_table">
								  <thead>
							    	<tr>
								        <th><?php  echo $this->lang->line('message_Dish'); ?></th>
								       <!--  <th><?php  echo $this->lang->line('message_specialRequest'); ?></th> -->
								        <th><?php  echo $this->lang->line('message_Prise'); ?></th>
								        <th><?php  echo $this->lang->line('message_Qty'); ?></th>
								        <th class="t_prise"><?php  echo $this->lang->line('message_Total')." ".$this->lang->line('message_Prise'); ?></th>
								        <th></th>
								    </tr>
								    </thead>
								    <tbody>
								    <?php 
								    $resId= $_COOKIE['restaurant_id'];
									   
									    foreach ($finalDishData as $dishkey => $dishvalue) { ?>
									    	<tr class="<?php echo ($dishvalue['locality']==$resId)?'':'not_allow'; ?>">
									    		
									        	<td><?php echo $dishvalue['dish_name'] ?><span><?php echo trim($dishvalue['choice_name'],','); ?></span></td>

									        	<td><?php echo $dishvalue['subtotal']; ?> KD</td>
									        	<td><?php echo $dishvalue['dish_count']; ?></td>
									        	
									        	<td class="t_prise"><?php echo $dishvalue['total'] ?> KD</td>
									        	<td><a data-toggle="modal" data-target="#removeDish"  aria-label="open"  onclick="removeDishData(<?php echo $dishvalue['id'] ?>);"><i class="fa fa-times-circle"></i></a></td>
								      		</tr>
								      		<?php 
								      			
								      		} ?>
								      	
								    </tbody>
								</table>
						    </div>
						</div>
					</div>
				</div>
				
			</div>

			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="order_address">
						<h4><?php  echo $this->lang->line('message_Addres'); ?></h4>
						<ul>
							<?php
								if(count($addressDetail)>0){
									
									$check = (isset($_COOKIE['restaurant_id']))?$_COOKIE['restaurant_id']:"";
									 foreach ($addressDetail as $key => $value) {
										 
									  ?>
									
									<li>

										<input type="radio" id="address<?php echo $value->address_id; ?>" class="custAddress" loca="<?php echo $value->locality_id; ?>" loca_val="<?php echo $value->name; ?>" name="address-radio" value="<?php echo $value->address_id ?>"  <?php echo ($check == $value->locality_id)?"checked":""; ?>>
										<label for="address<?php echo $value->address_id; ?>"><span><?php echo $value->customer_name ?></span> <?php echo $value->address1 ?></label>
										<div class="edit_address">
											<a href="#address" aria-label="open"   onclick="editAddressData(<?php echo $value->address_id; ?><?php echo ($check == $value->address_id)?',1':''; ?>)"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										</div>
									</li>	 
							<?php } 
								}
							?>
							
						</ul>
						<div class="add_address">
							<a href="#address" aria-label="open">+ <?php  echo $this->lang->line('message_AddAddress'); ?></a>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="order_address">
						<h4><?php  echo $this->lang->line('message_restaurantDetail'); ?></h4>
						<div class="res_detail_left">
							<ul>
								<li><i class="fa fa-home"></i> <span class="res_address"></span></li>
								<li><i class="fa fa-phone"></i> (+965) <span class="phone_address"></span></li>
								<li><i class="fa fa-envelope"></i> <span class="email_address"></span></li>
							</ul>
						</div>
						<div class="res_detail_right">
							<ul class="res_time">
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="paymeny_summary">
						<h4><?php  echo $this->lang->line('message_PaymentSummary'); ?></h4>
						<div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
							<div class="order_delivery_option">
								<ul>
									<li>
										<input type="radio" id="cash_on_delivery" name="payment-radio" value="3">
										<label for="cash_on_delivery"><?php  echo $this->lang->line('message_CaseOnDelivery'); ?></label>
									</li>
									<li>
										<input type="radio" id="kent_payment_gateway" name="payment-radio" value="1">
										<label for="kent_payment_gateway"><?php  echo $this->lang->line('message_PayviaCard'); ?></label>
									</li>
								</ul>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
							<div class="order_sammary">
								<ul>
									<li><?php  echo $this->lang->line('message_Subtotal'); ?><span class="order_subtotal">KD <?php echo $subtotal; ?></span></li>
									<li><?php  echo $this->lang->line('message_DeliveryFee'); ?><span class="order_charge">KD <?php echo $del_charge; ?></span></li>
									<li><?php  echo $this->lang->line('message_Total')." ".$this->lang->line('message_Amount'); ?> <span class="order_total">KD <?php echo $total; ?></span></li>
								</ul>
								<div class="btn_order">
									<button class="placeorder" total="<?php echo $total;  ?>" remDish="<?php echo $removeDishTotal;  ?>" ><?php  echo $this->lang->line('message_PlaceOrder'); ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="removeDish" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php  echo $this->lang->line('message_confirmation'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <p id="deleteMsg1"><h5><?php  echo $this->lang->line('message_removeDish'); ?></h5></p>
                <span style="color: #ff0000 !important; " class="error_reason"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="remove_dish"><?php  echo $this->lang->line('message_yes'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php  echo $this->lang->line('message_no'); ?></button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		 getRestaurantData();
	})
</script>