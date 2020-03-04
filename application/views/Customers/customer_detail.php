<?php   $successMsg=$this->session->flashdata('success_msg'); ?>

<div class="alert alert-success alert-dismissible" id="success_notification" style="display:<?php echo ($successMsg)?"block":"none"; ?>">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <h4><i class="icon fa fa-check"></i> Success!</h4>
    <p id="success_message"><?php echo $successMsg; ?></p>
</div>

<div class="alert alert-danger alert-dismissible" id="error_notification" style="display:none;">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <h4><i class="icon fa fa-warning"></i> Failed!</h4>
    <p id="error_message"></p>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order.js"></script>
<?php
    $OrderStatus    = $this->config->item('OrderStatus');
    $panelColor     = $this->config->item('panelColor');
    $labelColor     = $this->config->item('labelColor');
    $orderType      = $this->config->item('orderType');
?>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<style type="text/css">
	table thead tr th, .center{
		text-align: center;
	}
</style>
<div class="warper container-fluid">
	<div class="page-header">
        <h1 style="width: auto;float: left;" class="pageTitle">Customer Details</h1>
        <a  href="<?php echo site_url('Customers/index'); ?>" class="btn btn-primary back"><i class="fa fa-arrow-left"></i> Back to customer</a>

        <a  style="margin-right: 25px;" href="<?php echo site_url('Customers/updateCustomer/').$this->uri->segment(3); ?>" class="btn btn-primary back"><i class="fa fa-edit"></i> Edit</a>
	</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">Customer Details</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>Customer Name:</label>
                            <span><?php echo $customerDetails[0]->first_name."  ".$customerDetails[0]->last_name; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Contact No : </label>
                            <span>(+965) <?php echo $customerDetails[0]->contact_no; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Email :</label>
                            <span><?php echo $customerDetails[0]->email; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Date of Birth :</label>
                            <span><?php echo  ($customerDetails[0]->dob !='0000-00-00')?$customerDetails[0]->dob:""; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Language :</label>
                            <span><?php echo $customerDetails[0]->lang_name; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Customer Rating :</label>
                            <span><?php echo $customerDetails[0]->customer_rating; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Customer Type :</label>
                            <span><?php echo $this->config->item('customer_type')[$customerDetails[0]->customer_type]; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Customer Branch :</label>
                            <span><?php echo $customerDetails[0]->restaurant_name; ?></span>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="panel panel-primary">
                    <div class="panel-heading">Delivery Addresses</div>
                    <div class="panel-body address" >
                        <table>
                            <tr>
                            <?php

                                if(count($customerAddress)==0){
                                    echo "<td colspan='3'>Address not found</td>";
                                }else{
                                    foreach ($customerAddress as $key => $value) { 
                                            if($key <=1){
                                        ?>

                                        <td style="width:45%;">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading custom-pannel"><?php echo $value->customer_name; ?></div>
                                                <div class="panel-body" >

                                                    <div class="form-group">
                                                         
                                                        <span ><i class="fa fa-envelope"></i>&nbsp &nbsp<?php echo $value->email; ?></span>
                                                    </div>

                                                    <div class="form-group">
                                                        
                                                        <span><i class="fa fa-phone"></i>&nbsp &nbsp (+965) <?php echo $value->contact_no; ?></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <span><i class="fa fa-map-marker"></i>&nbsp &nbsp<?php echo $value->locality_name; ?></span>
                                                    </div>

                                                    <div class="form-group">
                                                       <span style="word-wrap: break-word;">
                                                           <i class="fa fa-home"></i>&nbsp
                                                            <?= ($value->appartment_no !="")?$value->appartment_no.',&nbsp':''; ?>
                                                            <?= ($value->floor !="")?"Floor -".$value->floor.',&nbsp':''; ?>
                                                            <?= ($value->block !="")?"Block -".$value->block.',&nbsp':''; ?>
                                                            <?= ($value->building !="")?"Building -".$value->building.',&nbsp':''; ?>
                                                            <?= ($value->street !="")?$value->street.',&nbsp':''; ?>
                                                            <?= ($value->avenue !="")?$value->avenue.',&nbsp':''; ?>
                                                            <?= ($value->address1 !="")?$value->address1:'';  ?>
                                                            <br>
                                                        </span>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td style="width:5%;">
                            <?php   } 
                                }
                            } ?>
                            </tr>
                        </table>
                        <?php if(count($customerAddress) > 2){ ?>
                            <a href="#" title="All Addresses " data-toggle="modal" data-target="#customerAddress" data-backdrop="static" data-keyboard="false" class="btn btn-primary seeMore">See more..</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            
          
            <div class="panel panel-primary">
            <div class="panel-heading">Order Details</div>           
                    <div class="panel-body">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered OrderTable">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>Order</th>
                                    <th>Delivery Charges</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(is_array($orders) && count($orders)>0){
                                    foreach($orders as $key => $value){
                                ?>
                                 
                                    <tr class="center" id="ord_details_dishes_<?php echo $value['order_id']; ?>">
                                        <td style="font-size: 20px;" class="showDishes" pid="<?php echo $value['order_id']; ?>">
                                            <i class="fa fa-play-circle-o"></i>
                                        </td>
                                        <!-- <td><?php echo "Order:".$value['order_id']; ?></td> -->
                                        <td><?php echo "Order:".$value['sequence_no']; ?></td>
                                        <td class="center"><?php echo $value['delivery_charges'].' KD';?></td>
                                        <td class="center"><?php echo ($value['total_price']).' KD';?></td>
                                    </tr>
                                   <tr class="center" id="ord_details_dishes_show_<?php echo $value['order_id']; ?>" style="display: none;">
                                        <td colspan="6">
                                            <table class="table table-striped dishTable">
                                               
                                                <thead class="thead-inverse dishHead">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Dish</th>
                                                        <th>Dish Price</th>
                                                        <th>Quantity</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                               <tbody>
                                                      <?php if(isset($dishes[$value['order_id']])){
                                                        foreach($dishes[$value['order_id']] as $key1 => $value1){
                                                    ?>

                                                           <tr id="ord_details_choice_<?php echo $value1['product_id']; ?>">
                                                            <td style="font-size:14px;" class="showChoices" pid="<?php echo $value1['order_id'].$value1['product_id']; ?>">
                                                             <i class="fa fa-play-circle-o"></i>
                                                         </td>
                                                            <td><?php echo $value1['dishName']; ?></td>
                                                            <td class="center">
                                                            <?php if(isset($value1['price']) && $value1['price']!=0){
                                                                    echo $value1['price'].' KD';
                                                                } else {
                                                                    echo '0'.' KD';
                                                                }
                                                            ?>
                                                            </td>
                                                            <td><?php echo $value1['quantity'] ?></td>
                                                            <td class="center">
                                                            <?php if(isset($value1['amount']) && $value1['amount']!=0){
                                                                    echo $value1['amount'].' KD';
                                                                } else {
                                                                    echo '0'.' KD';
                                                                }
                                                            ?>
                                                            </td>
                                                        </tr>
                                                        <tr class="center dishHiddenRow" id="ord_dishes_choices_show_<?php echo $value1['order_id'].$value1['product_id']; ?>" style="display: none;">
                                                            <td colspan="5">
                                                                <table class="table table-striped choiceTable">
                                                                        <thead class="thead-inverse choiceHead">
                                                                            <tr>
                                                                                <th >Choices</th>
                                                                                <th >Choice Category</th>
                                                                                <th>Choice price</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                            <?php  if(isset($value1['choice']) && count($value1['choice'])>0){
                                                                                foreach($value1['choice'] as $k1 => $v1){
                                                                            ?>

                                                                                   <tr>
                                                                                    <td><?php echo $v1; ?></td>
                                                                                    <td><?php if(isset($value1['choice_cat'][$k1])){ echo $value1['choice_cat'][$k1]; } ?></td>
                                                                                    <td>
                                                                                    <?php if(isset($value1['choice_price'][$k1])){
                                                                                            echo $value1['choice_price'][$k1].' KD';
                                                                                        } else {
                                                                                            echo '0'.' KD';
                                                                                        }
                                                                                    ?>
                                                                                    </td>
                                                                                    
                                                                                    
                                                                                </tr>
                                                                            <?php }
                                                                            } else { ?>
                                                                                <tr>
                                                                                    <td colspan="2"  class="address">
                                                                                        No dish choice available.
                                                                                    </td>
                                                                                </tr>
                                                                            <?php }  ?>
                                                                        </tbody>
                                                                   
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="5"  class="address">
                                                                No dish available.
                                                            </td>
                                                        </tr>
                                                    <?php }  ?>
                                                </tbody>
                                               
                                            </table>
                                        </td>
                                    </tr>
                                   
                                <?php  }
                                   }else{?>
                                    <tr>
                                        <td colspan="6" class="address">Order not available
</td>
                                    </tr>
                             <?php  } ?>
                            </tbody>
                        </table>
                    </div>
                    
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="customerAddress" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Customer Delivery Addresses</h4>
            </div>
            <div class="modal-body" >
                <div class="scrollbar">
                    <div class="panel-heading"></div>
                    <div class="panel-body address" >
                         <?php

                                if(count($customerAddress)==0){
                                     echo "<div class='col-lg-6'>Address not found</div>";
                               }else{
                                  
                                    foreach ($customerAddress as $key => $value) {
                                        if($key >1){
                                     ?>
                                        <div class="col-lg-6">
                                            <div class="panel panel-primary">
                                               <div class="panel-heading custom-pannel"><?php echo $value->customer_name; ?></div>
                                                <div class="panel-body" >

                                                    <div class="form-group">
                                                         
                                                        <span ><i class="fa fa-envelope"></i>&nbsp &nbsp<?php echo $value->email; ?></span>
                                                    </div>

                                                    <div class="form-group">
                                                        
                                                        <span><i class="fa fa-phone"></i>&nbsp &nbsp (+965) <?php echo $value->contact_no; ?></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <span><i class="fa fa-map-marker"></i>&nbsp &nbsp<?php echo $value->locality_name; ?></span>
                                                    </div>

                                                    <div class="form-group">
                                                        <span style="word-wrap: break-word;">
                                                            <i class="fa fa-home"></i>&nbsp
                                                            <?= ($value->appartment_no !="")?$value->appartment_no.',&nbsp':''; ?>
                                                            <?= ($value->floor !="")?"Floor -".$value->floor.',&nbsp':''; ?>
                                                            <?= ($value->block !="")?"Block -".$value->block.',&nbsp':''; ?>
                                                            <?= ($value->building !="")?"Building -".$value->building.',&nbsp':''; ?>
                                                            <?= ($value->street !="")?$value->street.',&nbsp':''; ?>
                                                            <?= ($value->avenue !="")?$value->avenue.',&nbsp':''; ?>
                                                            <?= ($value->address1 !="")?$value->address1:'';  ?>
                                                            <br>
                                                        </span>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                       </div>     
                            <?php      } 
                                    }

                                }
                             ?>
                      

                     
                  </div>
                   
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                
            </div>
        </div>
    </div>
</div>
