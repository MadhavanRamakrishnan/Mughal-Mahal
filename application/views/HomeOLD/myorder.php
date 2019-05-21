<div class="sub_page_main">
	<div class="myorder_page">
		<div class="order_main">
			<div class="container">
				<div class="order">
					<div class="title">
						<h4><?php echo $this->lang->line('message_MyOrder'); ?></h4>
					</div>
					<?php 
					
					if(isset($myorders) && count($myorders)>0){
						foreach ($myorders as $key => $value) {

						 ?>
						<div class="order_box">
							<div class="order_top">
								<div class="order_top_left">
									<a href="<?php echo base_url('Home/orderDetails/'.$key); ?>">
										<h3><?php echo $this->lang->line('message_Order')."#".($key)  ?> <span><?php 
										echo $this->ORD_status[$value['order_status']]; ?></span></h3>
									</a>
									<ul>
										<?php if(count($value['dish_name']) >0){foreach ($value['dish_name'] as $key1 => $dish) {
												if($key1<2){?>
												<li><span><?php echo ++$key1; ?></span> <?php echo $dish; ?></li>
										<?php
											 	}
											 } if(count($value['dish_name'])>2){ ?>
											 <h5 class="moreDish"> 
												<a href="<?php echo base_url('Home/orderDetails/'.$key); ?>">
													<span><?php  echo "+ ".(count($value['dish_name'])-2)." ".$this->lang->line('message_more'); ?></span>
													
												</a>
											</h5>
										<?php }
										 } ?>
									</ul>
								</div>
								<div class="order_top_right <?php echo ($value['order_status']==7)?"delivered":""; ?>">
								   <?php if($value['order_status']>=7){ echo $this->ORD_status[$value['order_status']]; ?>
										   
										<span><?php echo date('dS, F', strtotime($value['delivered_time'])); ?></span>
								   <?php }else{
								   		$datetime2 = new DateTime();
										$datetime1 = new DateTime($value['expected_delivery_time']);
										$interval = $datetime1->diff($datetime2);
										$hr       =$interval->format('%h');
										$mn       =$interval->format('%i');
										echo ($hr != 0)?$hr."hrs":"";
										echo ($mn != 0)?" ".$mn."mins":"";
										
								    ?>
									<span><?php echo $this->lang->line('message_EstDeliveryTime'); ?></span>
									<?php }?>
									<div class="repeatMyOrder">
										<i data-toggle="modal" data-target="#repeatOrder"  aria-label="open"  onclick="repeatOrder(<?= $key ?>);"  class="fa fa-refresh repeatOrder" title="Repeat order" > </i>
									</div>
								</div>
							</div>
							<div class="order_btm">
								<ul id="progressbar" class="progressbar<?php echo $key; ?>">

								    <li class="<?php echo ($value['order_status'] >1)?"active  complete dot":"active process dot"; ?> "><span><?php echo $this->lang->line('message_OrderPlaced'); ?></span></li>
								    <li class="<?php echo ($value['order_status'] >1)?"active":""; ?>  nodot"></li>
								    <li class="<?php echo ($value['order_status'] >=3)?($value['order_status'] ==3)?"active process dot":"active  complete dot":""; ?> "><span><?php echo $this->lang->line('message_Foodisbeingprepared'); ?></span></li>
								    <li class="<?php echo ($value['order_status'] >3)?"active":""; ?> nodot"></li>
								    <li class="<?php echo ($value['order_status'] >=5)?($value['order_status'] ==5)?"active process dot":"active  complete dot":""; ?>"><span><?php echo $this->lang->line('message_Foodoutfordelivery'); ?></span></li>
								    <li class="<?php echo ($value['order_status'] >5)?"active ":""; ?> nodot"></li>
								    <li class="<?php echo ($value['order_status'] >6 )?"active  complete dot":"" ;?>"><span><?php echo $this->lang->line('message_Delivered'); ?></span></li>
								</ul>
					        </div>
			        	</div>
		        	<?php } 
						}else{?>
							<div class="order_box">
								<div class="order_top">
									
									<div class="delivered no_Order">
											<?php echo $this->lang->line('message_noOrderFound'); ?>										
									</div>
								
						        </div>
		        			</div>
						<?php } ?>
		        	
				</div>
			</div>
		</div>
	</div>
</div>	
<div class="modal fade" id="repeatOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php  echo $this->lang->line('message_confirmation'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <p><h5><?php  echo $this->lang->line('message_repeatOrder'); ?></h5></p>
                <span style="color: #ff0000 !important; " class="error_reason"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="repeat_ord_btn"><?php  echo $this->lang->line('message_yes'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php  echo $this->lang->line('message_no'); ?></button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="deliveryTime" value="<?php echo date('Y-m-d H:i:s'); ?>">
<script src="<?php echo base_url('assets/js/front-end/myOrder.js'); ?>"></script> 
<script>
	var oID =""; 
</script> 
