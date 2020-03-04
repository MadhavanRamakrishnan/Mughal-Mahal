<?php   $successMsg=$this->session->flashdata('success_msg'); ?>

<div class="alert alert-success alert-dismissible" id="success_notification" style="display: none;">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4><i class="icon fa fa-check"></i> Success!</h4>
  <p id="success_message"><?php echo $successMsg; ?></p>
</div>

<div class="alert alert-danger alert-dismissible" id="error_notification" style="display:none;">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4><i class="icon fa fa-warning"></i> Failed!</h4>
  <p id="error_message"></p>
</div>

<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order.js"></script> -->
<?php
$OrderStatus    = $this->config->item('OrderStatus');
$panelColor     = $this->config->item('panelColor');
$labelColor     = $this->config->item('labelColor');
$orderType      = $this->config->item('orderType');
?>

<!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" /> -->
<style type="text/css">
	table thead tr th, .center{
		text-align: center;
	}
</style>
<div class="warper container-fluid">
	<div class="page-header">
        <h1 style="width: auto;float: left;" class="pageTitle">Customer Details</h1>
        <a  href="<?php echo site_url('Customers/index'); ?>" class="btn btn-primary back"><i class="fa fa-arrow-left"></i> Back to customer</a>

        <!-- <a  style="margin-right: 25px;" href="<?php echo site_url('Customers/updateCustomer/').$this->uri->segment(3); ?>" class="btn btn-primary back"><i class="fa fa-edit"></i> Edit</a> -->
    </div>

    <div class="row">
        <div class="col-lg-12">

          <div class="col-lg-12">
            <div class="panel panel-primary">
              <div class="panel-heading">Edit Customer</div>
              <div class="panel-body address" >
                <!-- <form method="post" action="<?php  echo site_url('Customers/editCustomers/'.$customerDetails[0]->user_id);?>" enctype="multipart/form-data"> -->
                    <div class="row setup-content">
                        <div class="col-xs-12">
                            <div class="col-md-6">
                              <h4>First Name</h4>
                              <div class="form-group">
                                <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo $customerDetails[0]->first_name; ?>" />
                                <span class="first_name_error add_error"></span>
                              </div>  
                            </div>

                        <div class="col-md-6">
                          <h4>Last Name</h4>
                          <div class="form-group">
                            <input type="text" id="last_name" name="last_name" class="form-control" value="<?= $customerDetails[0]->last_name; ?>" />
                            <span class="last_name_error add_error"></span>
                          </div>  
                        </div>
                        <div class="clearfix"></div><br>
                    <div class="col-md-6">
                      <h4>Contact No</h4>
                      <div class="form-group">
                        <input type="text" class="form-control" name="contact_no" id="contact_no" value="<?php echo $customerDetails[0]->contact_no; ?>" onkeypress="return event.charCode >= 48 && event.charCode <= 57" />
                        <span class="contact_error add_error"></span>
                      </div>  
                    </div>
                <div class="col-md-6">
                  <h4>Customer Email ID</h4>
                  <div class="form-group">
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo $customerDetails[0]->email; ?>" />
                    <span class="email_error add_error"></span>
                  </div>  
                </div>
                <div class="clearfix"></div><br>
            <div class="col-md-6">
              <h4>New Password</h4>
              <div class="form-group">
                <input type='text' class="form-control" id='pass' />
                <span class="pass_error add_error"></span>
              </div>  
            </div>
            <div class="col-md-6">
              <h4>Confirm New Password</h4>
              <div class="form-group">
                <input type='text' class="form-control" id='conPass'/>
                <span class="pass_conf_error add_error"></span>
              </div>  
            </div>
            <div class="clearfix"></div><br>
            <div class="col-md-6">
              <h4>Date Of Birth</h4>
              <div class="form-group">
                <?php  
                  if (set_value('dob_date')) {
                      $dob_date=set_value('dob_date');
                  }
                  else if($vehicle_data[0]->purchase_date){
                      $dob_date=date("m-d-Y",strtotime($customerDetails[0]->dob));
                  }
                  else{
                      $dob_date="";
                  } ?>
                <input type='text' name="dob_date" class="form-control" id='datepicker' value="<?php echo ($dob_date)?$dob_date:""; ?>" />
                <span class="dob_error add_error"></span>
                <!-- <span class="data_error add_error" style="margin: 30px;"></span> -->
              </div>  
            </div>

    <div class="col-md-12">
        <div class="form-group">
          <div class="addressDiv" >
            <h4>Address <h6><span class="address_error add_error" style="display: none;">Select at least one address</span><h6>
            </h4>
            <div class="form-group address-box-wrapper address_box_div">
                <?php 
                foreach ($customerAddress as $key => $value){

                   $typeAddr = '';
                   if($value->address_type == "1"){
                      $typeAddr = "Home";
                  }else if($value->address_type == "2"){
                      $typeAddr = "Office";
                  }else if($value->address_type == "3"){
                      $typeAddr = "Other";
                  }
                  ?>
                    <div class="box-address" onclick="addressSelect(<?= $value->address_id;?>,<?= $value->locality_id;?>)">
                        <b><?= $typeAddr;?></b>
                        <p><?= $value->street;?></p>
                        <p><?= $value->fghgg;?></p>
                        <p><?= $value->appartment_no;?></p>
                        <p><?= $value->block;?></p>

                        <?php if($value->avenue){ ?>
                          <p> <?= $value->avenue;?></p>
                        <?php } ?>
                        <p><?= $value->floor;?></p>
                        <?php if($value->address1){ ?>
                          <p> <?= $value->address1;?></p>
                        <?php } ?>
                        <a onclick="editAddressData(<?= $value->address_id;?>,<?= $customerDetails[0]->user_id; ?>)">Edit</a>&nbsp;<a></a>&nbsp;
                        <!-- <a onclick="deleteAddressData(<?= $value->address_id;?>)">Delete</a> -->
                    </div>
              <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="col-md-12 text-center"> 
    <button class="btn btn-success saveBtn btn-lg pull-center" type="button" onclick="saveCustomerData(<?= $customerDetails[0]->user_id;?>)">Save</button>
</div>
</form>
</div>
</div>
</div>

</div>
</div>



<div class="modal fade" id="modal_add_address" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog" style="min-width: 900px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Address</h4>
    </div>
    <div class="modal-body">
        <div id="address">
          <div id="startorderonline" class="swForms fancybox-content" style="display: inline-block;">
            <div class="checkout_popup">
              <form method="post" name="addressadd" id="addressadd">
                <div class="ckeckout_form_top">
                  <p>Your Details</p>
                  <div class="checkout_fild">
                    <input type="text" id="customer_name" name="customer_name" placeholder="Name">
                    <span style="display: none;" class="add_error name_add_error">Enter Customer Name</span>
                </div>
                <div class="checkout_fild">
                    <input type="email" id="addemail" name="addemail" placeholder="Email">
                    <span style="display: none;" class="add_error email_add_error"></span>
                </div>
                <div class="checkout_fild">
                    <input type="tel" id="contact_no2" name="add_contact_no" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Phone" maxlength="10">
                    <span style="display: none;" class="add_error phone_add_error"></span>

                </div>
            </div>
            <div class="checkout_form_btm">
              <p>Address Details</p>
              <div class="checkout_fild">
                <select id="locality" name="locality">

                  <option value="">Select Locality</option>
                  <?php  foreach ($localitylist as $key => $value){ ?>
                   <option value="<?= $value->locality_id ?>"><?= $value->name;?>
                   </option><?php } ?>

               </select>
               <span style="display: none;" class="add_error locality_error"></span>
           </div>
           <div class="checkout_fild">
            <input type="hidden" id="lat" name="customer_latitude" value="29.3604396"> 
            <input type="hidden" id="long" name="customer_longitude" value="48.0183705"> 
            <input type="hidden" id="editAdd" value="0"> 
            <label class="control-label">Select location on map</label>
            <div id="map"></div>
        </div>
        <div class="address_type">
            <br>
            <div class="check_out_lf">
              <div class="check_out_fild cus_radio">
                <ul>
                  <li>
                    <input type="radio" id="home" name="address_type" value="1" checked="">
                    <label for="home">Home</label>
                </li>
                <li>
                    <input type="radio" id="office" name="address_type" value="2">
                    <label for="office">Office</label>
                </li>
                <li>
                    <input type="radio" id="other" name="address_type" value="3">
                    <label for="other">Other</label>
                </li>
            </ul>
        </div>
        <div class="check_out_fild">
            <input type="text" id="street" name="street" value="" placeholder="Street" >
            <span style="display: none;" class="add_error streetReq"></span>
        </div>
        <div class="check_out_fild">
            <input type="text" id="building" name="building" value="" placeholder="Bulilding" >
            <span style="display: none;" class="add_error buildingReq"></span>
        </div>
        <div class="check_out_fild">
            <input type="text" id="apartment_no" name="apartment_no" value="" placeholder="Apartment No:" >
            <span style="display: none;" class="add_error appartmentReq"></span>
        </div>
    </div>

    <div class="check_out_lf">
      <div class="check_out_fild">
        <input type="text" id="block" name="block" value="" placeholder="Block" >
        <span style="display: none;" class="add_error blockReq"></span>
    </div>
    <div class="check_out_fild">
        <input type="text" id="avenue" name="avenue" value="" placeholder="Avenue(Optional)" >
    </div>
    <div class="check_out_fild">
        <input type="text" id="floor" name="floor" value="" placeholder="Floor" >
        <span style="display: none;" class="add_error floorReq"></span>
    </div>
    <div class="check_out_fild">
        <textarea id="address_line1" placeholder="Additional Directions(Optional)" name="address_line1"></textarea>
    </div>
</div>
</div>
</div>
</form>
<div class="place_order_btn">
    <input type="hidden" id="is_add_address" value="">
    <a href="javascript:void(0)" onclick="addaddress(<?= $customerDetails[0]->user_id; ?>)">Save Address</a>
</div>
<div class="popup_close">
    <a href="#address" aria-label="close"></a>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="modal fade" id="SuccessModalShow" tabindex="-1" role="dialog" aria-labelledby="success" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content" style="height: 165px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="nodish">Success</h4>
      </div>
      <div class="modal-body">
        <div>
          <h4 id="msg">Customer address update successfully.</h4> 
        </div>
        <div class="model_footer">
          <button type="button" class="btn btn-success pull-right" data-dismiss="modal" onclick="closeModal()">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="address_id"> 
<input type="hidden" id="cuser_id"> 

<script type="text/javascript">
    
    $(document).ready(function(){
       // $('#bodCustomer').datepicker({
       //      autoclose: true,
       //      changeYear: true,
       //      endDate: '+0d',
       //      todayHighlight: true,
       //      format: 'yyyy-mm-dd'
       // });

       /*$('#bodCustomer').datepicker().on('changeDate', function (e) {
            var minDate = new Date(e.date.valueOf());
            console.log(minDate);
            $('#endDate').datepicker('setStartDate', minDate);
      })*/
    });
</script>

<script type="text/javascript">
    var updateUrl          = "<?php echo site_url('Customers/updateCustomerData')?>";
    var getCustomerAddress    ="<?=  base_url() ?>Home/getCustomerAddress";
    var addDiliverAddress     = "<?= base_url('Home/addDiliverAddress')?>";
    var redirectUrl     = "<?= base_url('Customers/getCustomerDetails')?>";
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/customer.js"></script>