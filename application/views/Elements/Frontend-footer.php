<div id="snackbar"></div>
<div class="mainFooter">
    <div class="container">
        <ul class="wow fadeInDown"  data-wow-duration="2s" data-wow-delay="0">
            <li><a href="<?= base_url() ?>"><?= $this->lang->line('message_Home'); ?></a></li>
            <!-- <li><a href=""><?= $this->lang->line('food'); ?></a></li> -->
            <li><a href="<?= base_url() ?>about-us/"><?= $this->lang->line('message_AboutUs'); ?></a></li>
            <li><a href="<?= base_url() ?>contact-us/"><?= $this->lang->line('message_ContactUs'); ?></a></li>
        </ul>
        <a href="https://goo.gl/maps/z93gbxr1rP22" class="footLcoation">Khalifa Tower / Warba Bank, Block - 5, Khalid IBN Waleed Street, Floor - 5, Kuwait City, Sharq.</a>
        <div class="clear"></div>
        <a href="tel: +965 22 42 51 31" class="footTelicon">+965 22 42 51 31  /  32</a>
        <ul class="footerDown">
            <li class="wow zoomInDown">
                <a href="#"><img src="<?= base_url() ?>assets/front-end/images/iosapp-download.png" alt="Mughal Mahal App" /></a>
            </li>
            <li class="wow zoomInDown">
                <a href="#"><img src="<?= base_url() ?>assets/front-end/images/androidapp-download.png" alt="Mughal Mahal App" /></a>
            </li>
        </ul>
    </div>
    <div class="footer"  data-wow-duration="1s" data-wow-delay="0">
        <div class="container">
            <p class="copyright">Copyright &copy; Mughal Mahal. All Rights Reserved &nbsp;&nbsp;<a href="privacy-policy/">Privacy Policy</a> <a href="https://www.instagram.com/mughalmahalkuwait/" class="instalink"></a></p>
            <a class="swlogo" href="http://www.sweans.com" target="_blank" title="Sweans Digital Marketing">
                <img src="<?= base_url() ?>assets/front-end/images/SweansDigitalMarketingLogo.png" alt="Sweans Digital Marketing">
            </a>
        </div>
    </div>   
</div>

 <!-- Login -->
<div id="login" class="yourModalClass login" role="dialog" aria-labelledby="yourModalHeading" style="display:none;">
  <div class="login_popup">
    <div class="login_popup_cnt">
      <h2></h2>
        <div class="alert alert-danger alert-dismissible" id="error_notification" style="display:none;">
            <button aria-hidden="true" data-dismiss="alert" class="close closealert" type="button">×</button>
            <!-- <h4><i class="icon fa fa-warning"></i> Failed!</h4> -->
            <p id="error_message"></p>
        </div>
        <form id="loign_form"  method="post">
          <input type="hidden" name="checkOut-Click" class="checkOut-Click">
          <div class="login_form_fild">
            <input type="text" name="email" readonly onfocus="this.removeAttribute('readonly');" style="background-color:#fff" id="loginemail" placeholder="<?= $this->lang->line('message_Email'); ?>">
          </div>
          <div class="login_form_fild">
            <input type="password" name="password" onfocus="this.removeAttribute('readonly');" style="background-color:#fff" readonly id="password" placeholder="<?= $this->lang->line('message_Password'); ?>">
          </div>
          <div class="login_form_fild_btm">
            <div class="login_form_fild_btm_left">
              <label class="checkbox_custom"><?= $this->lang->line('message_Rememberme'); ?>
                <input type="checkbox" id="remember_me">
                <span class="checkmark"></span>
            </label>
            </div>
            <div class="login_form_fild_btm_right" onclick="cleaPOPFields();">
              <a href="#forgot_pass" aria-label="open" class="forgot_pass"><?= $this->lang->line('message_Forgotpassword?'); ?></a>
            </div>
          </div>
          <div class="login_form_btn">
              <!-- <input type="button" name="login" id="loginform" value="<?= $this->lang->line('message_Login'); ?>"> -->
            <button type="button" name="login" id="loginform"><img style="display: none;" class="loginloader" height="80" width="80" src="<?= base_url('assets/images/front-end/spinner.gif'); ?>"><?= $this->lang->line('message_Login'); ?></button>
          <!-- <a href="javascript:void(0);" onclick="fbLogin()" id="fbLink"><img src="<?php// echo base_url('assets/images/front-end/fblogin.png');?>"/></a> -->
                <!-- <div id="fSignIn" onclick="fbLogin()"></div> -->
          </div>
        </form>   
        <div class="sociallogin">
          <ul>
            <li>
                <a class="fblogin" onclick="fbLogin()" href="javascript:void(0)"><!-- <span><i class="fa fa-facebook-square"></i></span> --><?= $this->lang->line('message_LoginWithFacebook'); ?></a>
            </li>
            <li>
               <a class="gplogin" onclick="onLoadGoogleCallback()" id="go" href="javascript:void(0)"><!-- <span><i class="fa fa-google-plus-square"></i></span> --><?= $this->lang->line('message_LoginWithGoogle'); ?></a>
            </li>
          </ul>
        </div>
    </div>
    <div class="login_popup_btm">
      <div class="login_popup_btm_left">
        <p><?= $this->lang->line('message_Donthaveanaccount'); ?></p>
      </div>
      <div class="login_popup_btm_right">
        <a href="#register" aria-label="open"><?= $this->lang->line('message_Register'); ?></a>
      </div>
    </div>
  </div>
  <div class="popup_close">
      <a href="#login" aria-label="close"><img src="<?= base_url('assets/images/front-endold/icon/ic_cancel.svg') ?>"></a>
  </div>
</div>

  <!-- Register -->
<div id="register" class="yourModalClass login" role="dialog" aria-labelledby="yourModalHeading" style="display:none;">
    <div class="register_popup">
      <div class="register_popup_cnt">
        <h2><?= $this->lang->line('message_CreateAccount'); ?></h2>
        <form id="customerregister" name="register" method="post">
          <div class="alert alert-danger alert-dismissible" id="regerror_notification" style="display:none;">
                <button aria-hidden="true" data-dismiss="alert" class="close closealert" type="button">×</button>
                <!-- <h4><i class="icon fa fa-warning"></i> Failed!</h4> -->
                <p id="regerror_message"></p>
            </div>
          <div class="register_form_fild">
            <input type="text" name="first_name" id="fname" placeholder="<?= $this->lang->line('message_First')." ".$this->lang->line('message_Name'); ?>">
          </div>
          <div class="register_form_fild">
            <input type="text" name="last_name" id="lname" placeholder="<?= $this->lang->line('message_Last')." ".$this->lang->line('message_Name'); ?>">
          </div>
          <div class="register_form_fild">
            <input type="text" name="email" id="user_email" placeholder="<?= $this->lang->line('message_Email'); ?>" readonly onfocus="this.removeAttribute('readonly');">
          </div>
          <div class="register_form_fild">
            <input type="text" name="phoneno" id="phone" maxlength="14" placeholder="<?= $this->lang->line('message_Phone'); ?>" >
                    <input type="hidden" name="country_code" id="country_code" value="+965">
          </div>
          <div class="register_form_fild">
            <input type="password" name="password" id="user_password" placeholder="<?= $this->lang->line('message_Password'); ?>" readonly onfocus="this.removeAttribute('readonly');">
          </div>
          <div class="register_form_fild">
            <input type="password" name="cfnpassword" id="cfnpassword" placeholder="<?= $this->lang->line('message_ConfirmPassword'); ?>">
          </div>
          <div class="register_form_btn">
            <input type="button" name="register" id="submitregister" value="<?= $this->lang->line('message_Register'); ?>">
          </div>
        </form>
      </div>
        <div class="popup_close">
            <a href="#register" aria-label="close"><img src="<?= base_url('assets/images/front-endold/icon/ic_cancel.svg') ?>"></a>
        </div>
    </div>
</div>

<!-- Forgot Password -->
<div id="forgot_pass" class="yourModalClass forgot_pass" role="dialog" aria-labelledby="yourModalHeading" style="display:none;">
    <div class="forgot_pass_popup">
      <div class="forgot_pass_popup_cnt">
        <h2><?= $this->lang->line('message_Forgotpassword'); ?></h2>
        <div class="alert alert-danger alert-dismissible" id="forgot_error_notification" style="display:none;">
                <!-- <h4><i class="icon fa fa-warning"></i> Failed!</h4> -->
                <p id="forgot_error_message"></p>
            </div>
            <div class="alert alert-success alert-dismissible" id="forgot_sucess_notification" style="display:none;">
                <!-- <h4><i class="icon fa fa-warning"></i> Failed!</h4> -->
                <p id="forgot_sucess_message"></p>
            </div>
        <div class="forgot_pass_form_fild">
          <input type="text" name="email" id="email" placeholder="<?= $this->lang->line('message_Email_forget'); ?>">
        </div>
        
        <div class="forgot_pass_form_btn">
          <input type="button" name="submit" value="<?= $this->lang->line('message_Send'); ?>" id="forgotPassword" >
        </div>
      </div>
        <div class="popup_close">
            <a  href="#forgot_pass" aria-label="close"><img src="<?= base_url('assets/images/front-endold/icon/ic_cancel.svg') ?>"></a>
        </div>
  </div>
</div>

<div class="yourModalClass forgot_pass" role="dialog" aria-labelledby="yourModalHeading" style="display:none">
    <div id="minusDishData">
          <div id="startorderonline" class="swForms fancybox-content" style="display: inline-block;">
            <div class="dish_delete">
                <h3><?= $this->lang->line('sure_delete_dish') ?></h3> 
                <div class="model_footer">
                <ul>
                  <li id="delete_dish_btn">
                    <a><?= $this->lang->line('yes') ?></a>
                  </li>
                  <li id="cancel_delete_dish_btn">
                    <a><?= $this->lang->line('no') ?></a>
                  </li>
                </ul>
                </div>
            </div>
          </div>
    </div>
</div>

<div class="yourModalClass forgot_pass" role="dialog" aria-labelledby="yourModalHeading" style="display:none">
    <div id="address">
          <div id="startorderonline" class="swForms fancybox-content" style="display: inline-block;">
           <div class="checkout_popup">
      <h2>Address</h2>
      <form method="post" name="addressadd" id="addressadd">
        <div class="ckeckout_form_top">
          <p>Your Details</p>
          <div class="checkout_fild">
            <input type="text" id="customer_name" name="customer_name" placeholder="Name">
                  <span style="display: none;" class="add_error name">Enter Customer Name</span>
          </div>
          <div class="checkout_fild">
            <input type="email" id="addemail" name="addemail" placeholder="Email">
                  <span style="display: none;" class="add_error email"></span>
          </div>
          <div class="checkout_fild">
            <input type="tel" id="contact_no" name="contact_no" placeholder="Phone" maxlength="8">
                  <span style="display: none;" class="add_error phone"></span>
                  
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
                  <ul  style="display: flex;">
                    <li>
                      <input type="radio" id="home" name="address_type" onclick="displayOtherField()" value="1" checked="">
                      <label for="home">Home</label>
                    </li>
                    <li>
                      <input type="radio" id="office" onclick="displayOtherField()" name="address_type" value="2">
                      <label for="office">Office</label>
                    </li>
                    <li>
                      <input type="radio" id="other" onclick="displayOtherField()" name="address_type" value="3">
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
 
<div class="yourModalClass forgot_pass" role="dialog" aria-labelledby="yourModalHeading" style="display:none">
    <div id="removeaddress">
          <div id="startorderonline" class="swForms fancybox-content" style="display: inline-block;">
            <div class="dish_delete">
                <h3><?= $this->lang->line('message_removeAddress') ?></h3> 
                <span style="color: #ff0000 !important; " class="error_reason"></span>
                <div class="model_footer">
                <ul>
                  <li id="remove_address">
                    <a><?= $this->lang->line('yes') ?></a>
                  </li>
                  <li id="cancel_delete_dish_btn">
                    <a><?= $this->lang->line('no') ?></a>
                  </li>
                </ul>

                </div>
            </div>
          </div>
    </div>
</div>


<input type="hidden" id="user_id" value="<?=  (isset($userdata[0]->user_id))?$userdata[0]->user_id:""; ?>">

<script type="text/javascript">

  var baseUrl               ="<?=  base_url() ?>";
  var site_url              ="<?=  site_url() ?>";
  var base_url              = "<?= base_url(); ?>";
  var dishDataUrl           ="<?=  base_url() ?>Home/getDishData";
  var saveGoogleData        ="<?=  base_url() ?>Home/saveGoogleData";
  var getdishdetails        ="<?=  base_url() ?>Home/getdishdetails";
  var getlocalites          ="<?=  site_url() ?>Home/getlocalites";
  var loginurl              = 'Webservice_Customers/normalUserLogin';
  var signurl               = 'Webservice_Customers/normalUserSignup';
  var forgotPasswordurl     = 'Webservice_Customers/forgotPassword';
  //var getDishChoice         ="<?=  base_url() ?>Home/getDishChoice/";
  var getDishChoice         ="<?= site_url('Webservice_Customers/getchoicewisedish'); ?>";
  var getOrderSummary       ="<?=  base_url() ?>Home/orderSummary";
  var getRestaurantDetail   ="<?=  base_url() ?>Home/getRestaurantDetail";
  var addDiliverAddress     ="<?=  base_url() ?>Home/addDiliverAddress";
  var getCustomerAddress    ="<?=  base_url() ?>Home/getCustomerAddress";
  var addorder              ="<?=  base_url() ?>Home/addOrder";
  var allMyorderStatus      ="<?=  base_url() ?>Home/allMyorderStatus";
  var dishImageUrl          ="<?=  base_url() ?>timthumb.php?src=https://orderonline.mughalmahal.com/assets/images/front-endold/dishes/";


  var apply                 ="<?= $this->lang->line('apply') ?>";
  var price_on_selection    ="<?= $this->lang->line('price_on_selection') ?>";
  var termCondition         ="<?= $this->lang->line('term_condition') ?>";
  var termCondition         ="<?= $this->lang->line('term_condition') ?>";
  var instruction           ="<?= $this->lang->line('special_instruction') ?>";
  var itemCart              ="<?= $this->lang->line('item_cart') ?>";
  var addToCart             ="<?= $this->lang->line('add_to_cart') ?>";
  var cart                  ="<?= $this->lang->line('cart') ?>";
  var minutes               ="<?= $this->lang->line('minutes_approx') ?>";
  var EmailReq              ="<?= $this->lang->line('message_EmailReq'); ?>";
  var ValidEmail            ="<?= $this->lang->line('message_EnetrEmail1'); ?>";
  var PhoneReq              ="<?= $this->lang->line('message_PhoneReq'); ?>";
  var PleEnterValidEmail    ="<?= $this->lang->line('message_PleEnterValidEmail'); ?>";
  var phoneIsnumeric        ="<?= $this->lang->line('message_EnetrPhone1') ?>";
  var phoneMinlenth         ="<?= $this->lang->line('message_phoneMinlenth') ?>";
  var completeAdd           ="<?= $this->lang->line('complete_add') ?>";
  var streetReq             ="<?= $this->lang->line('street_req') ?>";
  var otherAddressReq       ="<?= $this->lang->line('other_address_req') ?>";
  var buildingReq           ="<?= $this->lang->line('building_req') ?>";
  var appartmentReq         ="<?= $this->lang->line('appartment_req') ?>";
  var blockReq              ="<?= $this->lang->line('block_req') ?>";
  var avenueReq             ="<?= $this->lang->line('avenue_req') ?>";
  var floorReq              ="<?= $this->lang->line('floor_req') ?>";
  
  var deleteDish            = "<?= $this->lang->line('message_deleteDish'); ?>";
  var OrderPlaced           ="<?=  $this->lang->line('message_OrderPlaced') ?>";
  var Foodisbeingprepared   ="<?=  $this->lang->line('message_Foodisbeingprepared') ?>";
  var Foodoutfordelivery    ="<?=  $this->lang->line('message_Foodoutfordelivery') ?>";
  var Delivered             ="<?=  $this->lang->line('message_Delivered') ?>";
  var EstDeliveryTime       ="<?=  $this->lang->line('message_EstDeliveryTime') ?>";
  var crossimg              ="<?=  base_url() ?>assets/front-end/images/closed.png";
  var less                  ="<?= $this->lang->line('message_less'); ?>";
  var more                  ="<?= $this->lang->line('message_more'); ?>";
  var showMore              ="<?= $this->lang->line('message_showMore'); ?>";
  var showLess              ="<?= $this->lang->line('message_showLess'); ?>";
  var ADD                   ="<?= $this->lang->line('message_add'); ?>";
  var required              ="<?= $this->lang->line('message_required'); ?>";
  var PriceOnSelection      ="<?= $this->lang->line('message_PriceOnSelection'); ?>";
  var noDishFound           ="<?= $this->lang->line('message_noDishFound') ?>";
  var phoneMinlenth         ="<?= $this->lang->line('message_phoneMinlenth') ?>";
  var phoneIsnumeric        ="<?= $this->lang->line('message_EnetrPhone1') ?>";
  var userNotLogin          ="<?= $this->lang->line('message_userLogin') ?>";
  var OrderPlaced           ="<?= $this->lang->line('message_OrderPlaced') ?>";
  var Foodisbeingprepared   ="<?= $this->lang->line('message_Foodisbeingprepared') ?>";
  var Foodoutfordelivery    ="<?= $this->lang->line('message_Foodoutfordelivery') ?>";
  var Delivered             ="<?= $this->lang->line('message_Delivered') ?>";
  var tryAgain              ="<?= $this->lang->line('message_tryAgain') ?>";
  var EstDeliveryTime       ="<?= $this->lang->line('message_EstDeliveryTime') ?>";
  var dishNotAvailable      ="<?= $this->lang->line('message_dishNotAvailable'); ?>";
  var restaurantTime        ="<?= $this->lang->line('message_restaurantTime'); ?>";
  var EnterOtherReason      ="<?= $this->lang->line('message_EnterOtherReason'); ?>";
  var AddressReqiured       ="<?= $this->lang->line('message_AddressReqiured'); ?>";
  var PaymentMethod         ="<?= $this->lang->line('message_PaymentMethod'); ?>";
  var lang                  ="<?= $this->langs; ?>";
  var is_favourite          =$("#is_favourite").val();
  var cancelOrder           ="<?= $this->ORD_status[14]; ?>";
  var addRestaurantRating   = "<?= site_url('Home/addRestaurantRating'); ?>";
  var addDriverRating       = "<?= site_url('Home/addDriverRating'); ?>";

  var addDiliverAddress     = "<?= base_url('Home/addDiliverAddress')?>";
  var getLatlongdata        = "<?= base_url('Home/getLatlongdata')?>";
  var popularDishes         = "<?= site_url('Home/popularDishes'); ?>";
  var addorder              = "<?= base_url(); ?>Home/addOrder";
  var imagepath             = "<?= base_url('assets/images/front-end/dishes/'); ?>";
  var fbdata                = "<?= site_url('Home/fblogin'); ?>";
  var getCustomerAddress    = "<?= base_url("Home/getCustomerAddress"); ?>";
  var minimumOrderAmount    = "<?= base_url("Home/minimumOrderAmount"); ?>";
  var checkOrderPlaceTime   = "<?= base_url("Home/checkOrderPlaceTime"); ?>";
  var getRepeatOrder        = "<?= base_url() ?>Home/getRepeatOrder";
  var deleteCustomerAddress = "<?= site_url('Home/deleteCustomerAddress') ?>";
  
  
  var AllFieldsReq          = "<?= $this->lang->line('message_AllFieldsReq'); ?>";
  var FirstNameReq          = "<?= $this->lang->line('message_FirstNameReq'); ?>";
  var LastNameReq           = "<?= $this->lang->line('message_LastNameReq'); ?>";
  var EmailReq              = "<?= $this->lang->line('message_EmailReq'); ?>";
  var ValidEmail            = "<?= $this->lang->line('message_EnetrEmail1'); ?>";
  var PhoneReq              = "<?= $this->lang->line('message_PhoneReq'); ?>";
  var PasswordReq           = "<?= $this->lang->line('message_PasswordReq'); ?>";
  var ConfPasswordReq       = "<?= $this->lang->line('message_ConfPasswordReq'); ?>";
  var PleEnterValidEmail    = "<?= $this->lang->line('message_PleEnterValidEmail'); ?>";
  var Passwordnotmatch      = "<?= $this->lang->line('message_Passwordnotmatch'); ?>";
  var UserSignupSucc        = "<?= $this->lang->line('message_UserSignupSucc'); ?>";
  var UserLoginSucc         = "<?= $this->lang->line('message_UserLoginSucc'); ?>";
  var deleteDish            = "<?= $this->lang->line('message_deleteDish'); ?>";
  var OrderNow              = "<?= $this->lang->line('message_OrderNow'); ?>";
  var NoDishes              = "<?= $this->lang->line('message_NoDishes'); ?>";
  var canclledFBlogin       = "<?= $this->lang->line('message_UsrcanclledFBlogin'); ?>";
  var GoogleloginError      = "<?= $this->lang->line('message_GoogleloginError'); ?>";
  var TotalLable            = "<?= $this->lang->line('message_Total'); ?>";
</script>
<!-- flaoting sidebar -->
<script src="<?= base_url() ?>assets/front-end/js/ResizeSensor.js" type="text/javascript"></script>

<script src="<?= base_url() ?>assets/js/chosen/chosen.jquery.min.js" type="text/javascript"></script>

<script src="<?= base_url() ?>assets/front-end/js/sticky-sidebar.js" type="text/javascript"></script>

<!--<script src="<?= base_url() ?>assets/front-end/js/wow.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/front-end/js/wow.min.js" type="text/javascript"></script>-->

<script src="<?= base_url('assets/js/front-end/fb.js'); ?>"></script>

<script src="<?= base_url('assets/front-end/js/modality.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/custom.js'); ?>"></script>
<script src="<?= base_url('assets/front-end/js//owl.carousel.js'); ?>"></script>
<script src="<?= base_url('assets/front-end/js/easy-responsive-tabs.js'); ?>"></script>


<!--<script type="text/javascript">
    $(document).ready(function() {
        wow = new WOW(
          {
            animateClass: 'animated',
            offset:       100,
            callback:     function(box) {
            }
          }
        );
        wow.init();
    });
</script> -->

</body>
</html>
