<style type="text/css">
    .alig-right{
        text-align:right;
    }
     .alig-left{
        text-align:left;
    }
    .panel-body {
        margin-top: 50px;
    }
</style>

<?php
    $OrderStatus    = $this->config->item('OrderStatus');
    $panelColor     = $this->config->item('panelColor');
    $labelColor     = $this->config->item('labelColor');
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<style type="text/css">
    table thead tr th, .center{
        text-align: center;
    }
</style>
<div class="warper container-fluid">
        <div class="row m-t-15">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="page-header">
                    <h1 class="pageTitle" style="margin: 0;">Customers </h1>
                </div>
                <h5>List of Customers</h5>
            </div>
        </div>
    <?php $successMsg=$this->session->flashdata('success_msg'); ?>

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
    <div class="row">
        <div class="col-lg-12"> 
            <div class="panel panel-success">
                <div class="panel-heading">
                  <div class="row">
                        <div class="col-sm-10">
                            <i class="fa fa-car" aria-hidden="true"></i>
                            <span class="nav-label" >List of Customers</span>
                        </div>
                        <!-- <div class="col-sm-2">
                            <a href="<?php echo site_url('Customers/addCustomers'); ?>" type="button" class="btn btn-success">Add Customers</a>
                        </div> -->
                  </div>
                </div>

                <div class="row">
                    <div class="col-lg-8" style="margin: 20px 20px -55px 14px;">
                        <input type="text" class="dataSearch" placeholder="Search..." value="<?= $text; ?>" style="width: 185px;height: 30px;">
                        <button class="btn btn-success btn-sm" type="button" onclick="filterData()">Search</button>
                    </div>
                    

                </div>
                <div class="panel-body customersDiv">
                    <table cellpadding="0" cellspacing="0" border="0" id="customerTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sr #</th>
                                <th>Customer Name</th>
                                <th>Contact No.</th>
                                <th>Email</th>
                                <th>Order</th>
                                <th>Amount - KD</th>

                                <?php   if($userdata[0]->role_id < 3){?>
                                <th>Action</th>
                                <?php } ?>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(is_array($customers) && count($customers)>0){
                                foreach($customers as $key => $value){
                            ?>
                            <tr id="customers_<?php echo $value->user_id; ?>" class="customers_<?php echo $value->user_id; ?>">
                                <td class="center"><?php echo $offset++; ?></td>
                                <td>
                                    <a href="<?php echo site_url('Customers/getCustomerDetails/'.$value->user_id); ?>"><?php echo $value->first_name? trim($value->first_name.' '.$value->last_name) : 'N/A'; ?></a>
                                </td>
                                <td class="center"><?php echo ($value->contact_no !="")?"(+965) ".$value->contact_no:''; ?></td>
                                <td class="text-left"><?php echo $value->email;?></td>
                                <td class="center">
                                    <?php 
                                        if($value->totalOrders<=10)
                                        {
                                            $class = 'bg-info';
                                        }
                                        else if($value->totalOrders>10 && $value->totalOrders<=25)
                                        {
                                            $class = 'bg-warning';
                                        }
                                        else if($value->totalOrders>25 && $value->totalOrders<=50)
                                        {
                                            $class = 'bg-success';
                                        }
                                        else if($value->totalOrders>50)
                                        {
                                            $class = 'bg-danger';
                                        } 
                                    ?>
                                    <span class="badge noRadius <?php echo $class; ?>">
                                        <?php echo $value->totalOrders; ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <?php 
                                        if($value->totalAmount<=50)
                                        {
                                            $class1 = 'bg-info';
                                        }
                                        else if($value->totalAmount>50 && $value->totalAmount<=250)
                                        {
                                            $class1 = 'bg-warning';
                                        }
                                        else if($value->totalAmount>250 && $value->totalAmount<=500)
                                        {
                                            $class1 = 'bg-success';
                                        }
                                        else if($value->totalAmount>500)
                                        {
                                            $class1 = 'bg-danger';
                                        } 
                                    ?>
                                    <span class="badge noRadius <?php echo $class1; ?>">
                                        <?php echo ($value->totalAmount)?number_format($value->totalAmount,3,'.',''):"0"; ?>
                                    </span>
                                </td>
                                <?php if($userdata[0]->role_id <3){ ?>
                                        <td class="center">
                                            <a href="#" title="Add Address" data-toggle="modal" data-target="#addDiliverAddress" data-backdrop="static" data-keyboard="false" onclick="setUserId(<?php echo $value->user_id; ?>)"><i class="fa fa-plus-circle"> </i></a> | 
                                            <a title="Edit Customer" href="<?php echo site_url('Customers/updateCustomer/'.$value->user_id); ?>"><i class="fa fa-edit"> </i></a> | 
                                            <a title="Delete" data-toggle="modal" data-target="#confirmationModal" data-backdrop="static" data-keyboard="false" onclick="deleteCustomerDetail(<?php echo $value->user_id; ?>)" id="delete"><i class="fa fa-trash"> </i></a>         
                                        </td>
                                <?php } ?>
                            </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                    <div class="dataTables_paginate paging_simple_numbers alig-right" id="datatable-buttons_paginate"> 
                        <ul class="pagination">
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
<div id="addDiliverAddress" class="modal fade" role="dialog" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Delivery Address Details</h4>
      </div>
      <div class="modal-body">
        <div class="row">

             <div class="col-lg-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 alig-right">
                            <label class="control-label" data-placeholder="Select Address Type">Address Type <i class="reustarred">*</i></label>
                        </div>
                         <div class="col-sm-8 alig-left">
                            <select class="form-control" name="address_type" id="address_type" >
                                <option value="1">Home</option>
                                <option value="2">Office</option>
                                <option value="3">Other</option>
                            </select>
                            <div class="color-red address_type"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 alig-right">
                            <label class="control-label">Name <i class="reustarred">*</i></label>
                        </div>
                         <div class="col-sm-8 alig-left">
                             <input class="form-control" name="name" id="name" placeholder="Enter Your Name"  type="text">
                            <div class="color-red name"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 alig-right">
                            <label class="control-label">Email <i class="reustarred">*</i></label>
                        </div>
                         <div class="col-sm-8 alig-left">
                             <input class="form-control" name="email" id="email" placeholder="Enter Your Email"  type="text">
                            <div class="color-red email"></div>
                        </div>
                    </div>
                </div>
            </div>

             <div class="col-lg-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 alig-right">
                            <label class="control-label">Phone <i class="reustarred">*</i></label>
                        </div>
                         <div class="col-sm-8 alig-left">
                             <input class="form-control" name="Phone" id="Phone" maxlength="12" minlength="10" placeholder="Enter Your Phone"  type="text">
                            <div class="color-red phone"></div>
                        </div>
                    </div>
                </div>
            </div>
          
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 alig-right">
                            <label class="control-label">Locality<i class="reustarred">*</i></label>
                        </div>
                         <div class="col-sm-8 alig-left">
                             <select class="form-control" name="address1" id="address1" >
                                <option value="">Select Locality</option>
                                 <?php  foreach ($localitylist as $key => $value){ ?>
                                   <option value="<?php echo $value->locality_id ?>"><?php echo $value->name;?>
                                   </option><?php } ?>
                             </select>
                            <div class="color-red address1"></div>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 alig-right">
                           <style>
                           #map {
                            height: 170px;
                            width: 100%;
                                }
                            </style>
                            <label class="control-label">Select location on map</label>
                        </div>
                         <div class="col-sm-8 alig-left">
                            <input type="hidden" id="lat" name="lat" value="29.36972" /> 
                            <input type="hidden" id="long" name="long" value="47.97833" /> 
                             <div id="map"></div>
                        </div>
                    </div>
                </div>
            </div> 
              <div class="col-lg-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 alig-right">
                            <label class="control-label">Complete Address<i class="reustarred">*</i></label>
                        </div>
                         <div class="col-sm-8 alig-left">
                            <input class="form-control" name="address2" id="address2" placeholder="Enter Your Complete Address"  type="text">
                            <div class="color-red address2"></div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary" id="Add_address">Add</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <input type="hidden" id="user_id" value="">
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    var deleteCustomerDetailUrl  = "<?php echo site_url('Customers/deleteCustomerDetails')?>";
    var getLatlongdata       = "<?php echo site_url('Restaurants/getLatlongdata')?>";
    var addDiliverAddress    = "<?php echo site_url('Customers/addDiliverAddress')?>";
    var searchUrl            = "<?php echo site_url('Customers/filterDataGet')?>";
    var getCutomerData       = "<?php echo site_url('Customers/getCustomerDetails/')?>";
    var editCustomer         = "<?php echo site_url('Customers/editCustomers/')?>";
    var returnUrl            = "<?php echo site_url('Customers/index/')?>";
    //var countryId                = '';
    //var stateId                  = '';
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/customer.js"></script>
<!-- <script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAwLVfszKFujmgzHlkVLgUObIdmRgFXt2g&callback=initMap">
</script> -->
<script>
  //var latlong_id = $('.LatiLngi').find(":selected").val();
 /* $(document).ready(function(){
        $("#address1").val(7);
        initMap(lat=29.36972,lon=47.97833);

  });*/

  $("#address1").change(function() {

   var latlongId = $(this).val();
   getLatlong(latlongId);

  });
  
  function getLatlong(latlongId){

      if(latlongId){

        $.ajax({

          url        : getLatlongdata,
          type       : "POST",
          data       : {latlong_id:latlongId},

          success     : function(response){
            var obj = JSON.parse(response);
            if(obj.success==1){ 
               lat =obj.data[0].lat;
               lon =obj.data[0].lon;
               $("#lat").val(lat);
               $("#long").val(lon);
               initMap(lat,lon);
           }
           else{

           }
       }
   });
    }else{
        $("#address1").empty();
    }
}
var map;
function initMap(lat=29.36972,lon=47.97833) {


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

        document.getElementById("lat").value = event.latLng.lat();

        document.getElementById("long").value = event.latLng.lng();

        infoWindow.open(map, marker);

    });

}
</script>