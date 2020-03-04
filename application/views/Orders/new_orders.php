<link rel="stylesheet" href="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/front-end/modality.min.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/front-end/modality.min.js"></script> 
<?php
$labelColor     = $this->config->item('labelColor'); 
$OrderStatus    = $this->config->item('OrderStatus'); 
?>


<button id="tapButton" style="display: none;">Tap me</button>
<div class="warper container-fluid orders_page">

    <div class="page-header">
        <div class="row">
            <div class="col-sm-2"><h1>Orders</h1></div>
            <div class="col-sm-7">
                <?php $successMsg=$this->session->flashdata('success_msg'); ?>
                <div class="alert alert-success alert-dismissible" id="success_notification" style="display:<?php echo ($successMsg)?"block":"none"; ?>">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <h4><i class="icon fa fa-check"></i></h4>
                    <p id="success_message"><?php echo $successMsg; ?></p>
                </div>
            </div>
            <div class="col-sm-3">
                <h3 class="totalOrders">Total Orders:<span><?php echo $totalRow; ?></span></h3>
            </div>
        </div>
        
        <!-- <h3 class="totalOrders" style="float:right;">Total Orders:<b><?php echo $totalRow; ?></b></h3> -->
    </div>
    <div class="row">
        <div class="col-lg-12">
         <div class="panel panel-success">
            <div class="panel-heading" style="height: 50px;padding: 10px;">

                <div class="order_right">
                    <div class="order_new">
                        <i class="fa fa-cutlery" aria-hidden="true"></i>&nbsp
                        <span class="new_orders" total="0"> New Orders</span>
                        <span class="orderCount" id="newOrders"></span>
                    </div>
                    <button class="btn btn-success pull-right"><a href="<?= site_url('Orders/addOrder') ?>" style="color: #fff;"> + Add Order</a></button>
                </div>
            </div>
            <div class="panel-body" >
                <div class="row newOrderTab">
                    <div class="col-sm-2">
                        <input type="text" id="new_order_id" class="form-control" placeholder="Order ID">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="new_phone" class="form-control" placeholder="Mobile No.">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control"  id="new_restaurant" >
                            <?php if($resId == ""){?>
                                <option value="">All Branches</option>
                            <?php } 
                            foreach ($restaurants as $key => $value) { ?>
                                <option value="<?php echo $value->restaurant_id; ?>"><?php echo $value->restaurant_name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control"  id="new_order_type" >
                            <?php if($resId == "" || $this->userdata[0]->role_id == '2'){?>
                                <option value="">All OrderType</option>
                            <?php } 
                            foreach ($order_category as $key1 => $value1) { ?>
                                <option  value="<?php echo $value1->id; ?>"><?php echo $value1->type; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-2">  
                        <input type="text" id="new_date" class="datePicker form-control" placeholder="Order From Time">
                    </div>
                    <div class="col-sm-2">  
                        <input type="text" id="new_date1" class="datePicker form-control" placeholder="Order to Time">
                    </div>
                </div>
                <div class="row">
                   <div class="col-lg-12">
                    <table class="table table-striped table-bordered new_ordrers" >
                        <thead>
                            <th>#</th>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Mobile No.</th>
                            <th>Restaurant</th>
                            <th>Area</th>
                            <th>Order Type</th>
                            <th>Order Time</th>
                            <th>Payment</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="dataTables_paginate paging_simple_numbers" id="datatable-buttons_paginate"> 
                        <ul class="pagination new_ord_pagi"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="row">
    <div class="col-lg-12">
     <div class="panel panel-info">
        <div class="panel-heading" style="height: 50px;padding: 10px;">
            <div class="order_right">
                <div class="order_dispute">
                   <i class="fa fa-cutlery" aria-hidden="true"></i>&nbsp
                   <span class="disputed_order" total="<?php echo $totalDisputed; ?>">Punched orders</span>

               </div>
           </div>
       </div>
       <div class="panel-body">
        <div class="row">
            <div class="col-sm-2" style="margin-right: -23px;">
                <input type="text" id="order_id" class="form-control" placeholder="Order ID">
            </div>
            <div class="col-sm-2" style="margin-right: -23px;">
                <input type="text" id="phone" class="form-control" placeholder="Mobile No.">
            </div>
            <div class="col-sm-2" style="margin-right: -23px;">
                <select class="form-control"  id="restaurant" style="width:100%">
                    <?php if($resId == ""){?>
                        <option value="">All Branches</option>
                    <?php } 
                    foreach ($restaurants as $key => $value) { ?>
                        <option value="<?php echo $value->restaurant_id; ?>"><?php echo $value->restaurant_name; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm-2" style="margin-right: -23px;">
                <select class="form-control"  id="order_type" >
                    <?php if($resId == "" || $this->userdata[0]->role_id == '2'){?>
                        <option value="">All OrderType</option>
                    <?php } 
                    foreach ($order_category as $key1 => $value1) { ?>
                        <option  value="<?php echo $value1->id; ?>"><?php echo $value1->type; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm-1" style="margin-right: -23px; min-width: 165px;">  
                <input type="text" id="date" class="datePicker form-control" placeholder="Order From Time">
            </div>
            <div class="col-sm-1" style="margin-right: -23px; min-width: 145px;">  
                <input type="text" id="date1" class="datePicker form-control" placeholder="Order to Time">
            </div>
            <div class="col-sm-2" style="margin-right: -23px;">
                <select class="form-control"  id="status" data-placeholder="Status">
                    <option value="">All</option>
                    <?php 
                    unset($this->OrderStatus[1]);
                    unset($this->OrderStatus[6]);
                    foreach ($this->OrderStatus as $key => $value) { ?>
                     <option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                 <?php } ?>
             </select>
         </div>
     </div>
     <div class="row ">
        <div class="col-lg-12">
            <table class="table table-striped table-bordered punched_ordrers" >
                <thead>
                    <th>#</th>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Mobile No.</th>
                    <th>Restaurant</th>
                    <th>Area</th>
                    <th>Order Type</th>
                    <th>Order Time</th>
                    <th>Payment</th>
                    <th>Amount</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    <?php if(isset($Orders) && is_array($Orders) && count($Orders)>0){
                        foreach ($Orders as $key => $value){
                            ?>
                            <tr>
                               <td><?php echo $offset++; ?></td>
                               <td><a href="<?php echo site_url('Orders/getOrderDetails/'.(int)$value->order_id); ?>"><?php echo $value->order_id; ?></a></td>
                               <td><?php echo $value->name; ?></td>
                               <td> (+965) <?php echo $value->contact_no; ?></td>
                               <td><?php echo $value->restaurant_name; ?></td>
                               <td><?php echo $value->area; ?></td>
                               <td><?php echo $value->time; ?></td>
                               <td><?php echo $value->paymnet; ?></td>
                               <td><?php echo $value->amount; ?></td>
                               <td>
                                   <span class="active label <?php echo $labelColor[$value->status]; ?>">
                                    <?php echo $OrderStatus[$value->status]; ?>
                                </span>
                                <?php if(($value->status+1)==4){ ?>
                                   &nbsp&nbsp<i class="fa fa-arrow-right"></i>
                                   <span class="label <?php echo $labelColor[($value->status+1)]; ?> changeOrderStatusAndDriver " oid="<?php echo $value->oId; ?>" os="<?php echo ($value->status+1); ?>" data-toggle="modal" data-target="#modal-form" data-backdrop="static" data-keyboard="false" style="cursor: pointer;" title="Change Order Status" oid="<?php echo $value->order_id; ?>">
                                    <?php echo $OrderStatus[($value->status+1)]; ?>
                                </span>
                            <?php } else if($value->status <7) {
                                $os = ($value->status == 5)?$value->status+2:$value->status+1;
                                ?>
                                &nbsp&nbsp<i class="fa fa-arrow-right"></i>
                                <span class="label <?php echo $labelColor[($value->status+1)]; ?> changeOrder" oid="<?php echo $value->order_id; ?>" os="<?php echo $os; ?>" data-toggle="modal" data-target="#cngStatusmodal" data-backdrop="static" data-keyboard="false" style="cursor: pointer;" title="Change Order Status">
                                    <?php echo $OrderStatus[$os]; ?>
                                </span>

                            <?php } ?>
                        </td>
                    </tr>                                 
                <?php } } else {
                   ?>
                   <tr> 
                      <td colspan="10" class='orderNotFound'>
                        <h3 class="label label-success">Orders not found.</h3>
                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>
    <div class="dataTables_paginate paging_simple_numbers" id="datatable-buttons_paginate"> 
        <ul class="pagination punched_ord_pagi">
          <?php foreach ($links as $link) {
            echo "<li class='paginate_button previous' aria-controls='datatable-buttons' tabindex='0' id='datatable-buttons_previous'>". $link."</li>";
        } ?>
    </ul>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="row disputedOrderTab" style="display:block;">
    <div class="col-lg-12">
     <div class="panel panel-danger">
        <div class="panel-heading" style="height: 50px;padding: 10px;">
            <div class="order_right">
                <div class="order_dispute">
                   <i class="fa fa-cutlery" aria-hidden="true"></i>&nbsp
                   <span class="disputed_order" total="<?php echo $totalDisputed; ?>">Disputed Orders</span>
                   <span class="orderCount" id="newDisputed"><div></div></span>
               </div>
           </div>
       </div>
       <div class="panel-body" >
        <div class="row">
            <div class="col-sm-2">
                <input type="text" id="dis_order_id" class="form-control" placeholder="Order ID">
            </div>
            <div class="col-sm-2">
                <input type="text" id="dis_phone" class="form-control" placeholder="Mobile No.">
            </div>
            <div class="col-sm-2">
                <select class="form-control"  id="dis_restaurant" >
                    <?php if($resId == ""){?>
                        <option value="">All Branches</option>
                    <?php } 
                    foreach ($restaurants as $key => $value) { ?>
                        <option value="<?php echo $value->restaurant_id; ?>"><?php echo $value->restaurant_name; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control"  id="dis_order_type" >
                    <?php if($resId == "" || $this->userdata[0]->role_id == '2'){?>
                        <option value="">All OrderType</option>
                    <?php } 
                    foreach ($order_category as $key1 => $value1) { ?>
                        <option  value="<?php echo $value1->id; ?>"><?php echo $value1->type; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm-2">  
                <input type="text" id="dis_date" class="datePicker form-control" placeholder="Order From Time">
            </div>
            <div class="col-sm-2">  
                <input type="text" id="dis_date1" class="datePicker form-control" placeholder="Order to Time">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-striped table-bordered disputed_ordrers" >
                    <thead>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Mobile No.</th>
                        <th>Restaurant</th>
                        <th>Area</th>
                        <th>Order Type</th>
                        <th>Order Time</th>
                        <th>Payment</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </thead>
                    <tbody></tbody>
                </table>
                <div class="dataTables_paginate paging_simple_numbers" id="datatable-buttons_paginate"> 
                    <ul class="pagination dispued_ord_pagi"></ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

</div>
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Change/Assign Driver</h4>
            </div>
            <div class="modal-body">
                <form role="form" method="POST">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Choose Driver</label>
                        <select class="form-control" name="driver" id="driver"></select>
                        <span style="color: red; display: none;" id="errDriver">Please choose any driver for delivery</span>
                        <input type="hidden" name="hdn_oid" id="hdn_oid" value="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="assign">Assign</button>
            </div>
        </div>
    </div>
</div>

<div id="cngStatusmodal" class="modal fade" role="dialog" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Confirmation</h4>
    </div>
    <div class="modal-body">
        <p id="statusMsg">Are you sure to change the order status?</p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="cngOrder" onclick="updateOrder()">Yes</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
    </div>
</div>
</div>
</div>

<input type="hidden" name="loginUserRole" value="<?= $userdata[0]->role_id; ?>" id="loginUserRole">

<script type="text/javascript">
    var changeOrderStatus          = "<?php echo site_url('Orders/changeOrderStatus')?>";
    var ordDetailLink              ="<?php echo base_url('Orders/getOrderDetails'); ?>";
    var link_name                  ="<?php echo base_url('Orders/newOrders'); ?>";
    var deleteOrderDetailUrl       = "<?php echo site_url('Orders/deleteOrderDetails')?>";
    var getDrivers                 = "<?php echo site_url('Orders/getDrivers')?>";
    var changeDriver               = "<?php echo site_url('Orders/changeDriver')?>";
    var changeDriverAndOrderStatus = "<?php echo site_url('Orders/changeDriverAndOrderStatus')?>";
    var getNewOrderCounts          = "<?php echo site_url('Orders/getNewOrderCounts')?>";
    // added by Rajesh
    var getRestaurantDishesAJAX = "<?php echo site_url('Restaurants/getRestaurantDishes'); ?>";
</script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order_new.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order.js"></script>