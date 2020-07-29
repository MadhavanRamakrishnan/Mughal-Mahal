

<?php /*echo "<pre>";print_r($orderDetail); die;*/ $successMsg=$this->session->flashdata('success_msg'); ?>
    
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


<?php
    $OrderStatus    = $this->config->item('OrderStatus');
    $panelColor     = $this->config->item('panelColor');
    $labelColor     = $this->config->item('labelColor');
    $orderType      = $this->config->item('orderType');
    $paymentType      = $this->config->item('payment_type');
?>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<style type="text/css">
	table thead tr th, .center,table tbody tr td{
		text-align: center;
	}
</style>
    <div class="warper container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-header">
                    <h1 style="width: auto;float: left;" class="pageTitle">Order Details</h1>


                    <a style="width: auto;float: right; margin-top: 15px; margin-left: 10px;" href="<?php echo site_url('Orders/index'); ?>" class="btn btn-success"><i class="fa fa-arrow-left"></i> Back to order</a>

                    <!-- <a style="width: auto;float: right; margin-top: 15px; margin-left: 10px;" href="<?php echo site_url('Orders/exportOrderDetails/').$orderDetail['order_id']; ?>" class="btn btn-primary">Export</a> -->
                    <a style="width: auto;float: right; margin-top: 15px; margin-left: 10px;" onclick="window.open('<?php echo site_url('Orders/exportOrderDetails/').$orderDetail['order_id']; ?>', '_blank', 'location=yes,height=570,width=850,scrollbars=yes,status=yes');" class="btn btn-primary">Export</a>
                    <?php if($orderDetail['order_status']>=4 && $orderDetail['order_status'] <=5){ ?>
                        <button id="track_order" style="width: auto;float: right; margin-top: 15px;" class="btn btn-primary"><i class="fa fa-map-marker"></i> Track order</button>
                    <?php } ?>
                </div>
        	</div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="border-box">
                    <div class="col-lg-4">
                        <div class="panel <?php echo $panelColor[$orderDetail['order_status']]; ?>">
                            <div class="panel-heading">
                                <!-- Order # - <?php echo str_pad($orderDetail['order_id'],"6","0",STR_PAD_LEFT); ?> -->
                                Order # - <?php echo str_pad($orderDetail['sequence_no'],"6","0",STR_PAD_LEFT); ?>
                                
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label>Branch Name:</label>
                                    <span><?php echo $orderDetail['restaurant_name'] ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Order Placed Time :</label>
                                    <span>
                                        <?php $date = date_create($orderDetail['order_placed_time']);
                                            $formatted_date =  date_format($date,"M d Y H:i");
                                        echo $formatted_date; ?>
                                    </span>
                                </div>

                                <div class="form-group">
                                    <label>Delivery Time : </label>
                                    <span>
                                        <?php 
                                            echo ($orderDetail['delivered_time'] !=null)?date_format(date_create($orderDetail['delivered_time']),'M d Y H:i'):"";
                                        ?>
                                    </span>
                                </div>
                                <?php if(isset($orderDetail['d_first_name'])) { ?>
                                <div class="form-group">
                                    <label>Assinged To:</label> 
                                    <span class="driverName driverName<?php echo $orderDetail['order_id']; ?>">

                                        <?php echo ($orderDetail['order_status']>3)?$orderDetail['d_first_name'].' '.$orderDetail['d_last_name']:''; ?>
                                    </span>
                                </div>

                                <div class="form-group">
                                    <label>Driver Contact:</label> 
                                    <span class="driverContact driverContact<?php echo $orderDetail['order_id']; ?>">
                                         <?php echo ($orderDetail['order_status']>3 )?$orderDetail['d_contact_no']:''; ?>
                                    </span>
                                </div>
                               <?php } ?>
                                <div class="form-group form_btn">
                                    
                                    <?php
                                        if($orderDetail['order_status'] == 1){ ?>
                                         <a class="btn btn-green btn-sm btn-pdn confirmOrder" data-toggle="modal" data-target="#confirmOrderModal" title="Confirm Order" oId="<?php echo $orderDetail['order_id'] ?>"><i class="fa fa-check"></i></a>
                                        <?php }
                                        if($orderDetail['order_status'] == 8){?>

                                            <a id="replaceOrdId" data-toggle="modal" data-target="#confirmationReplace" data-backdrop="static" data-keyboard="false" title="Discard Order" oid="<?php echo $orderDetail['order_id']; ?>" onclick="setOrderId(<?php echo $orderDetail['order_id']; ?>)">
                                               <label class="pending label label-collect form-control">Replace</label>
                                            </a>

                                            <a id="refundOrdId" data-toggle="modal" data-target="#confirmationRefund" data-backdrop="static" data-keyboard="false" title="Discard Order" oid="<?php echo $orderDetail['order_id']; ?>" onclick="refunrOrders(<?php echo $orderDetail['order_id']; ?>)">
                                               <label class="pending label label-success  form-control">Refund</label>
                                            </a>

                                    <?php }
                                    else if($orderDetail['order_status'] < 4){ ?>
                                       
                                        <a class="btn btn-danger btn-sm btn-pdn"  data-toggle="modal" data-target="#confirmationModal1" data-backdrop="static" data-keyboard="false" title="Discard Order" oid="<?php echo $orderDetail['order_id']; ?>" onclick="deleteOrder(<?php echo $orderDetail['order_id']; ?>)">
                                            <i class="fa fa-trash"></i>
                                        </a>

                                    <?php } ?>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel <?php echo $panelColor[$orderDetail['order_status']]; ?>">
                            <div class="panel-heading">Customer Details</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label>Customer Name:</label>
                                    <span><?php echo $orderDetail['customer_name']; ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Contact No : </label>
                                    <span><?php echo ($orderDetail['customer_contact_no'] !='')?" (+965) ".$orderDetail['customer_contact_no']:''; ?></span>
                                </div>
                                <div class="form-group">
                                    <label>Email :</label>
                                    <span><?php echo $orderDetail['customer_email'] ?></span>
                                </div>
                                <div class="form-group">
                                    <label>Delivery Address :</label> 
                                    <span style="word-wrap: break-word;">
                                        <?= ($orderDetail['area'] !="")?"Area - ".$orderDetail['area'].",&nbsp":""; ?>
                                        <?= ($orderDetail['block']!="")?"Block -".$orderDetail['block'].",&nbsp":""; ?>
                                        <?= ($orderDetail['street'] !="")?"Street -".$orderDetail['street'].',&nbsp':''; ?>
                                        <?= ($orderDetail['avenue']!="")?"Avenue -".$orderDetail['avenue'].',&nbsp':''; ?>
                                        <?= ($orderDetail['building']!="")?"Building -".$orderDetail['building'].",&nbsp":"" ?>
                                        <?= ($orderDetail['floor']!="")?"Floor -".$orderDetail['floor'].",&nbsp":"";  ?>
                                        <?= ($orderDetail['appartment_no'] !="")?"Apartment No - ".$orderDetail['appartment_no'].",&nbsp":""; ?>
                                        <?= ($orderDetail['delivery_address']!="")?"Delivery Address -".$orderDetail['delivery_address']:""; ?>
                                        <br>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel <?php echo $panelColor[$orderDetail['order_status']]; ?>">
                            <div class="panel-heading">Order Details</div>
                            <div class="panel-body">
                                <div class="form-group change_status">
                                    <label>Status:</label>
                                    <span class="label <?php echo $labelColor[$orderDetail['order_status']]; ?>"><?php echo $OrderStatus[$orderDetail['order_status']]; ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Payment Type :</label> 
                                    <span class="label label-primary"><?php echo $paymentType[$orderDetail['order_type']]; ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Gross Amount :</label>
                                    <span><?php echo number_format(($orderDetail['total_price']-$orderDetail['delivery_charges']),3,'.','').' KD'; ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Delivery Charges :</label>
                                    <span><?php echo number_format($orderDetail['delivery_charges'],3,'.','').' KD'; ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Total Amount :</label>
                                    <span><?php echo number_format($orderDetail['total_price'],3,'.','').' KD'; ?></span>
                                </div>
                                <?php 
                                    if($orderDetail['order_status'] == 8 || $orderDetail['order_status'] == 13 || $orderDetail['order_status'] ==14){ ?>
                                    <div class="form-group">
                                        <label>Reason:</label>
                                        <span><?php echo $orderDetail['reason']; ?></span>
                                    </div>
                                    <?php }
                                ?>
                                <div class="form-group">
                                    <label>Special Request:</label>&nbsp;
                                    <span class="label label-primary"><?php echo (!empty($orderDetail['special_instruction']) && $orderDetail['special_instruction'] != null ? $orderDetail['special_instruction'] : "-"); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12" id="track_order_map" style="display: none;">
                <div class="panel <?php echo $panelColor[$orderDetail['order_status']]; ?>">
                    <div class="panel-heading">Track Order</div>            
                    <div class="panel-body">
                        <div  id="map" style="height:500px;"></div>
                        <button id="routebtn" style="display: none;">Route</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="panel <?php echo $panelColor[$orderDetail['order_status']]; ?>">
                <div class="panel-heading">Dish Details</div>            
                    <div class="panel-body">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th class="text-left">Dish</th>
                                    <th>Special Instruction</th>
                                    <th>Dish Price</th>
                                    <th>Quantity</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(is_array($orderDetail['dishes']) && count($orderDetail['dishes'])>0){
                                    foreach($orderDetail['dishes'] as $key => $value){
                                ?>
                                    <tr class="center" id="order_details_dishes_<?php echo $value['product_id']; ?>">
                                        <td style="font-size: 20px;" class="toggleChoice" pid="<?php echo $value['product_id']; ?>">
                                            <i class="fa fa-play-circle-o"></i>
                                        </td>
                                        <td class="text-left"><?php echo $value['product_en_name']; ?></td>
                                        <td class="text-left"><?php echo $value['description']; ?></td>
                                        <td class="">
                                            <?php echo number_format($value['price'],3,'.','').' KD'; ?>
                                        </td>
                                        <td><?php echo $value['quantity']; ?></td>
                                        <td class=""><?php echo number_format($value['amount'],3,'.','').' KD';?></td>
                                    </tr>
                                    <tr class="center" id="order_details_dishes_show_<?php echo $value['product_id']; ?>" style="display: none;">
                                        <td colspan="6">
                                            <table class="table table-striped" style="border: 1px solid #ccc; margin-bottom: 0;">
                                                <?php if(isset($orderDetail['dishes'][$key]['choice'])) { ?>
                                                    <thead class="thead-inverse">
                                                        <tr>
                                                            <th class="">Choice Title</th>
                                                            <th>Choice Name</th>
                                                            <th>Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if(is_array($orderDetail['dishes'][$key]['choice']) && count($orderDetail['dishes'][$key]['choice'])>0){
                                                            foreach($orderDetail['dishes'][$key]['choice'] as $key1 => $value1){
                                                        ?>
                                                            <tr id="order_details_choice_<?php echo $value1['choice_id']; ?>">
                                                                <td class=""><?php echo $value1['choice_category_name']; ?></td>
                                                                <td class="center"><?php echo $value1['choice_name']; ?></td>

                                                                <td class="">
                                                                <?php if(isset($value1['choice_price']) && $value1['choice_price']!=0){
                                                                        echo number_format($value1['choice_price'],3,'.','').' KD';
                                                                    } else {
                                                                        echo "0".' KD';
                                                                    }
                                                                ?>
                                                            </td>
                                                            </tr>
                                                        <?php } } ?>
                                                    </tbody>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td clospan="6">
                                                            No dish choice available.
                                                        </td>
                                                    </tr>
                                                <?php }  ?>
                                            </table>
                                        </td>
                                    </tr>
                                <?php  } } ?>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="odredId" value="">
<div id="confirmOrderModal" class="modal fade" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
                <p id="confirmMsg">Are you sure  to confirm this order?</p>
                <span id="confirmOrderMsg" class="error" style="display: none; color:red;"></span>
                <input type="hidden" id="confirmOrderId" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirm_order_btn">Confirm</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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
                        <input type="hidden" name="hdn_oid" id="hdn_oid" value="<?php echo $orderDetail['order_id']; ?>">
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
<div id="confirmationModal1" class="modal fade" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Discard Order</h4>
            </div>
            <div class="modal-body">
                <p id="deleteMsg1"><h5>Are you sure to discard this order?</h5></p>
                <p><h5>Discard Reason<i class="reustarred">*</i></h5></p>
                <span style="color: #ff0000 !important; " class="error_reason"></span>
                <textarea type="text" name="discard_reason" id="discard_reason" class="form-control" rows="5"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="delete_btn1">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<div id="confirmationReplace" class="modal fade" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
               
            </div>
            <div class="modal-body" >
                <label id="deleteMsg1" style="font-weight: bold; text-align: center;">Are you sure  to Replace this order?</label><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="replaceOredrButton">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<div id="confirmationRefund" class="modal fade" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
               
            </div>
            <div class="modal-body" >
                <label id="deleteMsg1" style="font-weight: bold; text-align: center;">Are you sure  to Refund this order?</label><br>
                <span style="color: #ff0000 !important; " id="refund_error"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="refundOredrButton">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="orderId" value="<?= $orderDetail['order_id']; ?>">

<?php

$host =  $_SERVER['HTTP_HOST'];

if($host!='18.216.199.131'){

        $host="http://localhost";

}

if($host=="18.216.199.131"){

    $host = "http://18.216.199.131";
}

?>

<script type="text/javascript">
    var getDrivers               = "<?php echo site_url('Orders/getDrivers')?>";
    var changeDriver             = "<?php echo site_url('Orders/changeDriver')?>";
    var deleteOrderDetailUrl     = "<?php echo site_url('Orders/deleteOrderDetails')?>";
    var replaceOrderUrl          = "<?php echo site_url('Orders/ChangeReplaceOrderStatus')?>";
    var refunrOrder              = "<?php echo site_url('Orders/changeOrderStatus/')?>";
    var newOrder                 = "<?php echo site_url('Orders')?>";
    var replaceOrder             = "<?php echo site_url('Orders/replaceOrder')?>";
    var confirmOrderDetailUrl    = "<?php echo site_url('Orders/confirmOrderDetails')?>";
    function setOrderId(orderId){
        $("#odredId").val(orderId);
    }
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order.js"></script>


<script>
    
   var lat ="<?= $orderDetail['customer_latitude'] ?>";
   var lon ="<?= $orderDetail['customer_longitude'] ?>";
   var driverName =" <?= ($orderDetail['order_status']>3)?$orderDetail['d_first_name'].' '.$orderDetail['d_last_name']:''?>";
   
</script>
<!-- <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4b-QnyfODx2VYNWhPMtJQsamBnd61w7I=places&callback=mapLocation"></script> -->

<script type="text/javascript" src="<?=$host; ?>:3333/socket.io/socket.io.js"></script>
<script type="text/javascript">
    var driverId = "<?= (isset($orderDetail['driver_id']))?$orderDetail['driver_id']:''; ?>";
    var driverLat = '';
    var driverLon = '';
     window['initialize'] = () => {
               mapLocation();
            }
    jQuery(function ($) {

        var socket = io.connect('<?=$host; ?>'+':3333');

        $("#track_order").click(function(e){
            $("#track_order_map").show();
            $(this).attr("disabled",true);
            socket.emit('new customer',driverId);
        });


        socket.on('connection_success',function(data){

        });

        socket.on('new location from driver',function(data){

            var data = JSON.parse(data);
            console.log("Data from socket"+data.user_id);
            //document.getElementById('new_location').value = data.latitude+","+data.longitude;
            //$("#new_location").val(data.latitude+","+data.longitude);
            
            if(data.user_id==driverId){

                driverLat = data.latitude;
                driverLon = data.longitude;
                $("#routebtn").click();
                console.log(driverLat+" "+driverLon);
            }
            
        });
    });



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

        console.log("start:"+start);
        console.log("start:"+end);

        var bounds = new google.maps.LatLngBounds();
        bounds.extend(start);
        bounds.extend(end);
        map.fitBounds(bounds);
        var request = {
            origin: start,
            destination: end,
            travelMode: google.maps.TravelMode.DRIVING
        };

        

        directionsService.route(request, function (response, status) {
            console.log(google.maps.DirectionsStatus);
            console.log("status"+status);
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
                directionsDisplay.setMap(map);
                directionsDisplay.setOptions( { suppressMarkers: true } );

                var markers = new Array();
                var iconCounter = 0;
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(driverLat,driverLon),
                    map: map,
                    title:"Driver:"+driverName,
                    icon: {                             
                        url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"                              
                    }
                  }); 

                var markers = new Array();
                var iconCounter = 0;
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(lat,lon),
                    map: map,
                    icon: {                             
                        url: "http://maps.google.com/mapfiles/ms/icons/green-dot.png"                              
                    }
                  });  

            } else {
                alert("Directions Request from " + start.toUrlValue(6) + " to " + end.toUrlValue(6) + " failed: " + status);
            }
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
}


</script>
<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDCRut52YvoPjefyPGTNeL4A6LZ9tJD5tk&libraries=places&callback=mapLocation"></script>