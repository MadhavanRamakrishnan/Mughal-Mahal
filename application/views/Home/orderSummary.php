<style type="text/css">
 /* #removeaddress .modal-body,#removeaddress #myModalLabel{text-align: center; color: #666;}
  #removeaddress .btn-success{    background-color: #00AE4A;}*/
  .deleteAddress{color:red;}

  #myModel{display: none;}
</style>
<script type="text/javascript">
    jQuery(document).ready(function(){
          jQuery(".addressPopup").colorbox({inline:true, width:"90%" ,height:"90%" });
          jQuery(".addressPopup").colorbox({inline:true, width:"90%" ,height:"90%" });


          jQuery(".cartLink").click( function(event){
                event.stopPropagation();
                jQuery("div.cartPopup").toggle();
          });
          jQuery(document).click( function(){
              jQuery('div.cartPopup').hide();
          });
          //initMap();
    });
</script>

<div class="banner bannerInner section" style="background-image: url(../assets/images/front-end/orderBanner.jpg); ">
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
           
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="order_left wow fadeInUp" data-wow-duration="1s" data-wow-delay="0" style="visibility: visible; animation-duration: 2s; animation-name: fadeInUp;">
                  <h4>Your Order</h4>
                  <div class="order_table">
                    <div class="table-responsive">          
                      <table class="table dish_table">
                        <thead>
                          <tr>
                              <th>Dish</th>
                             <!--  <th>Special Request</th> -->
                              <th>Price</th>
                              <th>Qty</th>
                              <th class="t_prise">Total Price</th>
                              <th></th>
                          </tr>
                          </thead>
                          <tbody>
                            <?php 
                            $resId= $_COOKIE['locality_id'];
                            
                            foreach ($finalDishData as $key => $value) { 

                              ?>
                                <tr>                          
                                  <td><?= $value['dish_name'] ?><span><?= trim($value['choice_name']) ?></span></td>
                                  <td><?= number_format((float)$value['subtotal'], 3, '.', '') ?> KD</td>
                                  <td><?= $value['dish_count'] ?></td>                            
                                  <td class="t_prise"><?= $value['total'] ?> KD</td>
                                  <td><a href="#minusDishData" class="minusDishpopUp" onclick="removeDishData(<?= $value['id'] ?>);"><i class="fa fa-times-circle"></i></a></td>
                                </tr>
                            <?php } ?>                    
                          </tbody>
                      </table>
                      </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row"> 
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="order_address all-address wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.4s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInUp;">
                  <h4><?php  echo $this->lang->line('message_Addres'); ?></h4>
                  <ul>
                    <?php
                      if(count($addressDetail)>0){
                        
                        $check = (isset($_COOKIE['restaurant_id']))?$_COOKIE['restaurant_id']:"";
                        $fullAddress = "";
                         foreach ($addressDetail as $key => $value) {
                            $fullAddress =($value->appartment_no !="" || $value->appartment_no !=null)?$value->appartment_no.",":"";
                            $fullAddress .=($value->floor!="" || $value->floor!=null)?"Floor -".$value->floor.",":"";
                            $fullAddress .=($value->block!="" || $value->block!=null)?"Block -".$value->block.",":"";
                            $fullAddress .=($value->building !="" ||$value->building !=null)?"Building -".$value->building.",":"";
                            $fullAddress .=($value->street !="")?$value->street.',':'';
                            $fullAddress .=($value->avenue !="" || $value->avenue !=null)?$value->avenue.',':'';
                            $fullAddress .=($value->address1!="")?$value->address1:""; 
                          ?>
                        
                        <li class="address<?= $value->address_id; ?>">
                          <input type="radio" id="address<?= $value->address_id; ?>" <?= ($resId == $value->locality_id)?"checked":""; ?> class="custAddress" loca="<?= $value->locality_id; ?>" loca_val="<?= $value->name; ?>" name="address-radio" value="<?= $value->address_id ?>" >
                          <label for="address<?= $value->address_id; ?>" style="width: 85%">
                            <span><font size="3" color="#00AE4A"><?= ($value->address_type == 1 ? "Home" : ($value->address_type == 2 ? "Office" : ucfirst($value->other_address)) ) ?></font> &nbsp;<font size="1"> <?= $fullAddress ?></font></span>
                          </label>
                          <div class="edit_address" style="width: 15%">
                            <a href="#removeaddress" class="minusDishpopUp" style="color:red;float: right;"  onclick="deleteAddressData(<?= $value->address_id; ?>)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            <a href="#address" class="addressModel" aria-label="open" style="float: right;margin-right: 5px;"  onclick="editAddressData(<?= $value->address_id; ?><?= ($check == $value->address_id)?',1':''; ?>)"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
                          </div>

                        </li>  
                    <?php } 
                      }
                    ?>
                    
                  </ul>
                  <div class="add_address">
                    <a href="#address" class="addressModel" id="addressModel" aria-label="open" onclick="document.getElementById('addressadd').reset();document.getElementById('address_id').value = '';document.getElementById('addemail').value = '<?= $userdata[0]->email ?>';">+ Add Address</a>
                  
                   <!-- popup content ends -->
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="order_address wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.8s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInUp;">
                  <h4>Restaurant Detail</h4>
                  <div class="res_detail_left">
                    <ul>
                      <li><i class="fa fa-home"></i> <span class="res_address">Lulu Al –Watan Tower- Opp. Comm. Bank of Kuwait – Beside Centerpoint. Beirut St., Hawally.</span></li>
                      <li><i class="fa fa-phone"></i> (+965) <span class="phone_address">1234567890</span></li>
                      <li><i class="fa fa-envelope"></i> <span class="email_address">&nbsp;&nbsp;N/A</span></li>
                    </ul>
                  </div>
                  <div class="res_detail_right">
                    <ul class="res_time"><li><span>11:30:00 AM - 23:59:00 PM</span></li></ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="row"> 
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="paymeny_summary wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.8s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInUp;">
                  <h4>Payment Summary</h4>
                  <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                    <div class="order_delivery_option">
                      <ul>
                        <li>
                          <input type="radio" id="cash_on_delivery" name="payment-radio" value="3">
                          <label for="cash_on_delivery"><span><?php  echo $this->lang->line('message_CaseOnDelivery'); ?></span></label>
                        </li>
                        <li>
                          <input type="radio" id="credit_card" name="payment-radio" value="1">
                          <label for="credit_card"><?php  echo $this->lang->line('message_PayviaCreditCard'); ?></label>
                        </li>
                        <li>
                          <input type="radio" id="debit_card" name="payment-radio" value="2">
                          <label for="debit_card"><?php  echo $this->lang->line('message_PayviaDebitCard'); ?></label>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                    <div class="order_sammary">
                      <ul>
                        <li><?php  echo $this->lang->line('message_Subtotal'); ?><span class="order_subtotal">KD <?= $subtotal; ?></span></li>
                        <li><?php  echo $this->lang->line('message_DeliveryFee'); ?><span class="order_charge">KD <?= $del_charge; ?></span></li>
                        <li><?php  echo $this->lang->line('message_Total')." ".$this->lang->line('message_Amount'); ?> <span class="order_total">KD <?= $total; ?></span></li>
                      </ul>
                      <div class="btn_order">
                        <button class="placeorder"total="<?= $total;  ?>" remDish="<?= $removeDishTotal;  ?>">Place Order</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
</div>
<input type="hidden" id="address_id" value="">

<script type="text/javascript" src="<?= base_url() ?>assets/front-end/js/custom/orderSummary.js?v=1.2"></script>

