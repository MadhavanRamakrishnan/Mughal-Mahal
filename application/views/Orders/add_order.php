<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

<style type="text/css">
  .setup-content table tr th  {
    background-color: #fff;
    color: #b7aeae;
}
</style>
<div class="container-fluid">
  <h4 class="cat-title">Add Order</h4>
  <div class="border-addorder">
    <div class="stepwizard col-md-offset-3">
      <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step">
          <a href="#step-1" type="button" id="step1" class="btn btn-default btn-circle">1</a>
        </div>
        <div class="stepwizard-step">
          <a href="#step-2" type="button" id="step2" class="btn btn-default btn-circle" disabled="disabled">2</a>
        </div>
        <div class="stepwizard-step">
          <a href="#step-3" type="button" id="step3" class="btn btn-default btn-circle" disabled="disabled">3</a>
        </div>
      </div>
    </div>
    <form role="form" action="" method="post">
      <input type="hidden" name="stepId" class="stepId" value="1">
      <div class="row setup-content" id="step-1">
        <div class="col-xs-12">
          <div class="col-md-12">
            <h4>Order Type</h4>
            <div class="radiobtn-group">
              <label class="rdbcontainer">Unpunched Orders
                <input type="radio" checked="checked" name="orderRadio" value="past">
                <span class="checkmark"></span>
              </label>
              <label class="rdbcontainer">Live Orders
                <input type="radio" name="orderRadio" value="present">
                <span class="checkmark"></span>
              </label>
            </div>
            <h4>Select Order Category</h4>
            <div class="form-group">
              <select class="form-control chosen-select" id="orderTypeId">

                <option value="">Select Order Category</option>
                <?php 
                foreach ($order_category as $key => $value){ ?>
                  <option  value="<?php echo $value->id; ?>"><?php echo $value->type; ?>
                  </option><?php 
                } ?> 

              </select>
              <span class="order_type_error add_error" style="display: none;">This field is required</span>
            </div>
            <h4>Phone No.</h4>
            <div class="form-group">
              <input type="text" required="required" class="form-control" name="phone_number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="phone_number" />
              <span class=" phone_error add_error" style="display: none;">This field is required</span>
            </div>
            <h4>Order Date and Time</h4>
              <div class="form-group">
                <input type="text" name="orderDateName" id="datetimepicker" class="form-control " required/>
                <input type="hidden" id="datetimepicker2"/>
              </div>

            <button class="btn btn-success nextBtn btn-lg pull-right" type="button" >Next</button>
          </div>
        </div>
      </div>
      <div class="row setup-content" id="step-2" style="display: none;">
        <div class="col-xs-12">
          <div class="col-md-6">
            <h4>First Name</h4>
            <div class="form-group">
              <input type="hidden" id="address_id" name="address_id" class="form-control" />
              <input type="text" id="first_name" name="first_name" class="form-control" />
              <span class="first_name_error add_error" style="display: none;">This field is required</span>
            </div>  
          </div>
          <div class="col-md-6">
            <h4>Last Name</h4>
            <div class="form-group">
              <input type="text" id="last_name" name="last_name" class="form-control" />
              <span class="last_name_error add_error" style="display: none;">This field is required</span>
            </div>  
          </div>
          <div class="col-md-12">
            <h4>Customer Email ID</h4>
            <div class="form-group">
              <input type="text" class="form-control" name="email" id="email" />
              <span class="email_error add_error" style="display: none;">This field is required</span>
              <div class="addressDiv" style="display: none;">
                <h4>Address <h6><span class="address_error add_error" style="display: none;">Select at least one address</span><h6>
                </h4>
                <div class="form-group address-box-wrapper address_box_div">
                  <div class="box-addressadd">
                    <i class="fa fa-plus" aria-hidden="true">
                    </i>
                    <p>Add Address</p>
                  </div>
                </div>
              </div>
            </div>
            <button class="btn btn-default backBtn btn-lg pull-left" type="button">Back</button>
            <button class="btn btn-success nextBtn btn-lg pull-right" type="button" >Next</button>

            <button class="btn btn-success saveBtn btn-lg pull-right" type="button" style="display: none;" > + Add Address</button>
          </div>
        </div>
      </div>
      <div class="row setup-content" id="step-3" style="display: none;">
        <div class="col-xs-12">
          <div class="col-md-12">
            <h3>Restaurant</h3>

            <div class="form-group">
              <select class="form-control chosen-select1" id="restaurantId">

                <option value="">Select Restaurant</option>
                <?php 
                foreach ($restaurant as $key => $value){

                  if($userdata[0]->role_id == "2" && $userdata[0]->restaurant_name == $value->restaurant_name){
                    ?>
                    <option  value="<?php echo $value->restaurant_id; ?>" selected><?php echo $value->restaurant_name; ?>
                    </option><?php 
                  }else{?>
                    <option  value="<?php echo $value->restaurant_id; ?>"><?php echo $value->restaurant_name; ?>
                  </option>
                <?php }
              } ?> 
            </select>
          </div>
          <h3>Search Dishes</h3>
          <div class="form-group">
            <select class="form-control chosen-select2" id="dishId">

              <option value="">Select Dish</option>
            </select>
          </div>
          <!-- by Vasu -->
          <div class="form-group" id="categoryAccordion">
          </div>
          <div class="table-responsive">
            <table class="table table-bordered text-center">
              <thead>
                <tr>
                  <th class="text-center">S. No:</th>
                  <th class="text-center">Dish</th>
                  <th class="text-center">Price</th>
                  <th class="text-center">Quantity</th>
                  <th class="text-center">Total Price</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody class="tableBody">
                <tr>
                  <td colspan="6">
                    No dish selected
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <h3>Payment Method</h3>
          <div class="form-group">
            <select class="form-control chosen-select3" id="paymentId">
              <option value="">Select Payment method</option>
              <optgroup label="Store Pickup">
                <option  value="6">- Self cash</option>
                <option  value="7">- Self KNET</option>
                <option  value="8">- Self CC</option>
              </optgroup>
              <option  value="3">COD</option>
              <option  value="2">KNET Debit Card</option>
              <option  value="1">KNET Credit Card</option>
              <option  value="4">Talabat Credit</option>
              <option  value="5">Loyalty Redemption</option>
            </select>
            <span class="order_type_error add_error" style="display: none;">This field is required</span>
          </div>
          <h3>Order Status</h3>
          <div class="form-group">
            <select class="form-control chosen-select4" id="orderStatusId" >
              <option value="">Select Order Status</option>
              <option  value="1">Pending</option>
              <option  value="7">Completed</option>
              <option  value="8">Cancelled</option>
            </select>
            <span class="order_type_error add_error" style="display: none;">This field is required</span>
          </div>
          <button class="btn btn-default backBtn btn-lg pull-left" type="button">Back</button>
          <button class="btn btn-success nextBtn btn-lg pull-right" type="button" onclick="saveOrderDetail()">Finish</button>
        </div>
      </div>
    </div>
  </form>
</div>
</div>

<!-- Modals starts from here -->
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
                    <input type="tel" id="contact_no" name="add_contact_no" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Phone" maxlength="10">
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
                            <input type="radio" id="home" name="address_type" onclick="displayOtherField()" value="1" checked="">
                            <label for="home">Home</label>
                          </li>
                          <li>
                            <input type="radio" id="office" name="address_type" onclick="displayOtherField()" value="2">
                            <label for="office">Office</label>
                          </li>
                          <li>
                            <input type="radio" id="other" name="address_type" onclick="displayOtherField()" value="3">
                            <label for="other">Other</label>
                          </li>
                        </ul>
                      </div>
                      <div class="check_out_fild">
                        <input type="text" id="other_address" style="display: none;" name="other_address" value="" placeholder="For example: Joe's Home">
                         <span style="display: none;" class="add_error otherAddressReq"></span>
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
                <a href="javascript:void(0)" onclick="addaddress()">Save Address</a>
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

<div class="modal fade" id="modal_remove_address" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Address</h4>
      </div>
      <div class="modal-body">
        <div id="removeaddress">
          <div id="startorderonline" class="swForms fancybox-content" style="display: inline-block;">
            <div class="dish_delete">
              <h3>Are you want to remove this address?</h3> 
              <span style="color: #ff0000 !important; " class="error_reason"></span>
              <div class="model_footer">
                <ul>
                  <li id="remove_address">
                    <a>Yes</a>
                  </li>
                  <li id="cancel_delete_address_btn">
                    <a>No</a>
                  </li>
                </ul>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_add_dish" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display: none;">
  <div class="modal-dialog" id="cboxLoadedContent" style="width: 900px;height: 575px;">
    <div class="modal-content">
      <div class="modal-header  dish_popup">
        <button type="button" class="close" data-dismiss="modal">
          <button type="button" id="cboxClose" data-dismiss="modal">close</button>
          <span class="sr-only">Close</span>
        </button>

        <h4 class="modal-title" id="myModalLabel ch_dish_name" style="color: #fff; font-weight: bold;">Add Item Choices</h4>
      </div>
      <div id = "data">
        <div class="modal-body" style="position: relative; padding: 15px; width: 100%; display: inline-block;">
          <div id="startorderonline" class="swForms fancybox-content" style="display: block;float: left;width: 100%;">

            <div class="popupContent">
              <div class="leftPopup">
                <div class="dish_choices">
                  <form id="dish_choices_form">
                    <input type="hidden" name="finalPrice" id="finalPrice">
                    <h3>Your Choice Of(Choose 1) <span style="color: #">required</span></h3>
                    <input type="radio" name="test" value="test" checked>Half<span>+2.350KD</span><br>
                    <input type="radio" name="test" value="test">Full<span>+3.950KD</span><br>
                    <h3>Your Choice Of(Choose 1) <span>required</span></h3>
                    <input type="radio" name="test1" value="test" checked>Non Spicy<br>
                    <input type="radio" name="test1" value="test">Less Spicy<br>
                    <input type="radio" name="test1" value="test" checked>Medium Spicy<br>
                    <input type="radio" name="test1" value="test">Spicy<br>
                  </form> 

                </div>
                <div class="clear"></div>
                <h3>Special Instruction</h3>
                <input type="textarea" name="instruction" id="instruction" value="" placeholder="Type your comment" />
              </div>
              <div class="rightPopup">
                <h3>Item Cart<!-- <span>$68.00</span> --></h3>
                <div class="rightTwopopup">
                  <div class="left openPopupAdd">
                    <p class="item_name">Chicken Wings (15 Pcs)</p>
                    <div class="numbers-row">
                      <input type="text" name="numId" id="numId" class="numDishId" value="1" dishPrice="" predishcount="">
                    </div>
                  </div>
                  <div class="right">
                    <p class="item_price">39 KD</p>
                  </div>
                </div>
                <div class="clear"></div>
                <a href="javascript:void(0)" class="cartButton addcart" onclick="formsubmit();">ADD TO CART</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> 



<div class="modal fade" id="modal_remove_dish" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Address</h4>
      </div>
      <div class="modal-body">
        <div id="removeaddress">
          <div id="startorderonline" class="swForms fancybox-content" style="display: inline-block;">
            <div class="dish_delete">
              <h3>Are you want to remove this dish?</h3> 
              <span style="color: #ff0000 !important; " class="error_reason"></span>
              <div class="model_footer">
                <ul>
                  <li id="remove_dish">
                    <a>Yes</a>
                  </li>
                  <li id="cancel_delete_dish_btn">
                    <a>No</a>
                  </li>
                </ul>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal ends here -->

<div class="modal fade" id="succssOrder_model" tabindex="-1" role="dialog" aria-labelledby="success" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content" style="height: 165px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onclick="orderSuccess()"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="success">Success</h4>
      </div>
      <div class="modal-body">
        <div>
          <h4>Your order add successfully.</h4> 
        </div>
        <div class="model_footer">
          <button type="button" class="btn btn-success pull-right" onclick="orderSuccess()">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal ends here -->

<!-- Modal ends here -->

<div class="modal fade" id="dishNotFound" tabindex="-1" role="dialog" aria-labelledby="success" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content" style="height: 165px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="nodish">Success</h4>
      </div>
      <div class="modal-body">
        <div>
          <h4>This Dish is not available at this restaurant.</h4> 
        </div>
        <div class="model_footer">
          <button type="button" class="btn btn-success pull-right" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>


<input type="hidden" id="address_id" value="">
<input type="hidden" id="updateValue" value="">

<!-- Js link start -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script type="text/javascript">

  var userId = "";
  var addressId = "";
  var localityId = 0;
  var curr = new Date();
  var lastday = new Date(curr.getFullYear(), curr.getMonth() + 6, 0);

  var checkUser = "<?php echo site_url('Customers/getCustomerData'); ?>";
  var addCustomer = "<?php echo site_url('Customers/createCustomer'); ?>";
  var addDiliverAddress     = "<?= base_url('Home/addDiliverAddress')?>";
  var getCustomerAddress    ="<?=  base_url() ?>Home/getCustomerAddress";
  var deleteCustomerAddress = "<?= site_url('Home/deleteCustomerAddress') ?>";
  var getRestaurantDishesAJAX = "<?php echo site_url('Restaurants/getRestaurantDishes'); ?>";
  var getDishDetailAJAX ="<?= site_url('Webservice_Customers/getchoicewisedish'); ?>";
  var getRestaurantDetail   ="<?=  base_url() ?>Home/getRestaurantDetail";
  var getdishdetails        ="<?=  base_url() ?>Home/getdishdetails";
  // By Vasu
  var getDishesDataUrl      ="<?=  base_url() ?>Home/getDishData";
  // End by Vasu
  var addOrderData        ="<?=  base_url() ?>Orders/saveOrder";
  var returnUrl        ="<?=  base_url() ?>Orders/index";

  // var userData = <?php echo json_encode($userdata[0]); ?>;


</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/front-end/js/custom/dish.js"></script> -->


<!-- nicksarnicola_19_11_13_120413.pdf -->