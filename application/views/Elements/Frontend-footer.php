<div id="snackbar"></div>
<div class="mainFooter">
    <div class="container">
        <ul class="wow fadeInDown"  data-wow-duration="2s" data-wow-delay="0">
            <li><a href="http://18.216.199.131/"><?php  echo $this->lang->line('message_Home'); ?></a></li>
            <!-- <li><a href=""><?php  echo $this->lang->line('food'); ?></a></li> -->
            <li><a href="http://18.216.199.131/about-us/"><?php  echo $this->lang->line('message_AboutUs'); ?></a></li>
            <li><a href="http://18.216.199.131/contact-us/"><?php  echo $this->lang->line('message_ContactUs'); ?></a></li>
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
            <input type="text" name="email" readonly onfocus="this.removeAttribute('readonly');" style="background-color:#fff" id="loginemail" placeholder="<?php  echo $this->lang->line('message_Email'); ?>">
          </div>
          <div class="login_form_fild">
            <input type="password" name="password" onfocus="this.removeAttribute('readonly');" style="background-color:#fff" readonly id="password" placeholder="<?php  echo $this->lang->line('message_Password'); ?>">
          </div>
          <div class="login_form_fild_btm">
            <div class="login_form_fild_btm_left">
              <label class="checkbox_custom"><?php  echo $this->lang->line('message_Rememberme'); ?>
                <input type="checkbox" id="remember_me">
                <span class="checkmark"></span>
            </label>
            </div>
            <div class="login_form_fild_btm_right" onclick="cleaPOPFields();">
              <a href="#forgot_pass" aria-label="open" class="forgot_pass"><?php  echo $this->lang->line('message_Forgotpassword?'); ?></a>
            </div>
          </div>
          <div class="login_form_btn">
              <!-- <input type="button" name="login" id="loginform" value="<?php  echo $this->lang->line('message_Login'); ?>"> -->
            <button type="button" name="login" id="loginform"><img style="display: none;" class="loginloader" height="80" width="80" src="<?php echo base_url('assets/images/front-end/spinner.gif'); ?>"><?php  echo $this->lang->line('message_Login'); ?></button>
          <!-- <a href="javascript:void(0);" onclick="fbLogin()" id="fbLink"><img src="<?php// echo base_url('assets/images/front-end/fblogin.png');?>"/></a> -->
                <!-- <div id="fSignIn" onclick="fbLogin()"></div> -->
          </div>
        </form>   
        <div class="sociallogin">
          <ul>
            <li>
                <a class="fblogin" onclick="fbLogin()" href="javascript:void(0)"><!-- <span><i class="fa fa-facebook-square"></i></span> --><?php  echo $this->lang->line('message_LoginWithFacebook'); ?></a>
            </li>
            <li>
               <a class="gplogin" onclick="onLoadGoogleCallback()" id="go" href="javascript:void(0)"><!-- <span><i class="fa fa-google-plus-square"></i></span> --><?php  echo $this->lang->line('message_LoginWithGoogle'); ?></a>
            </li>
          </ul>
        </div>
    </div>
    <div class="login_popup_btm">
      <div class="login_popup_btm_left">
        <p><?php  echo $this->lang->line('message_Donthaveanaccount'); ?></p>
      </div>
      <div class="login_popup_btm_right">
        <a href="#register" aria-label="open"><?php  echo $this->lang->line('message_Register'); ?></a>
      </div>
    </div>
  </div>
  <div class="popup_close">
      <a href="#login" aria-label="close"><img src="<?php echo base_url('assets/images/front-endold/icon/ic_cancel.svg') ?>"></a>
  </div>
</div>

  <!-- Register -->
<div id="register" class="yourModalClass login" role="dialog" aria-labelledby="yourModalHeading" style="display:none;">
    <div class="register_popup">
      <div class="register_popup_cnt">
        <h2><?php  echo $this->lang->line('message_CreateAccount'); ?></h2>
        <form id="customerregister" name="register" method="post">
          <div class="alert alert-danger alert-dismissible" id="regerror_notification" style="display:none;">
                <button aria-hidden="true" data-dismiss="alert" class="close closealert" type="button">×</button>
                <!-- <h4><i class="icon fa fa-warning"></i> Failed!</h4> -->
                <p id="regerror_message"></p>
            </div>
          <div class="register_form_fild">
            <input type="text" name="first_name" id="fname" placeholder="<?php  echo $this->lang->line('message_First')." ".$this->lang->line('message_Name'); ?>">
          </div>
          <div class="register_form_fild">
            <input type="text" name="last_name" id="lname" placeholder="<?php  echo $this->lang->line('message_Last')." ".$this->lang->line('message_Name'); ?>">
          </div>
          <div class="register_form_fild">
            <input type="text" name="email" id="user_email" placeholder="<?php  echo $this->lang->line('message_Email'); ?>" readonly onfocus="this.removeAttribute('readonly');">
          </div>
          <div class="register_form_fild">
            <input type="text" name="phoneno" id="phone" maxlength="14" placeholder="<?php  echo $this->lang->line('message_Phone'); ?>" >
                    <input type="hidden" name="country_code" id="country_code" value="+965">
          </div>
          <div class="register_form_fild">
            <input type="password" name="password" id="user_password" placeholder="<?php  echo $this->lang->line('message_Password'); ?>" readonly onfocus="this.removeAttribute('readonly');">
          </div>
          <div class="register_form_fild">
            <input type="password" name="cfnpassword" id="cfnpassword" placeholder="<?php  echo $this->lang->line('message_ConfirmPassword'); ?>">
          </div>
          <div class="register_form_btn">
            <input type="button" name="register" id="submitregister" value="<?php  echo $this->lang->line('message_Register'); ?>">
          </div>
        </form>
      </div>
        <div class="popup_close">
            <a href="#register" aria-label="close"><img src="<?php echo base_url('assets/images/front-endold/icon/ic_cancel.svg') ?>"></a>
        </div>
    </div>
</div>

<!-- Forgot Password -->
<div id="forgot_pass" class="yourModalClass forgot_pass" role="dialog" aria-labelledby="yourModalHeading" style="display:none;">
    <div class="forgot_pass_popup">
      <div class="forgot_pass_popup_cnt">
        <h2><?php  echo $this->lang->line('message_Forgotpassword'); ?></h2>
        <div class="alert alert-danger alert-dismissible" id="forgot_error_notification" style="display:none;">
                <!-- <h4><i class="icon fa fa-warning"></i> Failed!</h4> -->
                <p id="forgot_error_message"></p>
            </div>
            <div class="alert alert-success alert-dismissible" id="forgot_sucess_notification" style="display:none;">
                <!-- <h4><i class="icon fa fa-warning"></i> Failed!</h4> -->
                <p id="forgot_sucess_message"></p>
            </div>
        <div class="forgot_pass_form_fild">
          <input type="text" name="email" id="email" placeholder="<?php  echo $this->lang->line('message_Email'); ?>">
        </div>
        
        <div class="forgot_pass_form_btn">
          <input type="button" name="submit" value="<?php  echo $this->lang->line('message_Send'); ?>" id="forgotPassword" >
        </div>
      </div>
        <div class="popup_close">
            <a  href="#forgot_pass" aria-label="close"><img src="<?php echo base_url('assets/images/front-endold/icon/ic_cancel.svg') ?>"></a>
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
          <p>Delivery Address</p>
          <div class="checkout_fild">
            <select id="locality" name="locality">

              <option value="">Select Locality</option>
               <?php  foreach ($localitylist as $key => $value){ ?>
               <option value="<?php echo $value->locality_id ?>"><?php echo $value->name;?>
               </option><?php } ?>

            </select>
          </div>
          <div class="checkout_fild">
            <input type="hidden" id="lat" name="customer_latitude" value="29.3604396"> 
            <input type="hidden" id="long" name="customer_longitude" value="48.0183705"> 
            <input type="hidden" id="editAdd" value="0"> 
            <label class="control-label">Select location on map</label>
            <div id="map"></div>
          </div>
          <div class="checkout_fild">
            <input type="text" id="address_line1" name="address_line1" placeholder="Address">
                   <span style="display: none;" class="add_error complete_add"></span>
          </div>
          <div class="address_type">
            <h3>Address Type</h3>
            <div class="cus_radio">
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
 
<!--  popup content -->
<!-- <div  style="display:none;">
  <div id="address" class="yourModalClass" role="dialog" aria-labelledby="yourModalHeading" >
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
          <p>Delivery Address</p>
          <div class="checkout_fild">
            <select id="locality" name="locality">

              <option value="">Select Locality</option>
               <?php  foreach ($localitylist as $key => $value){ ?>
               <option value="<?php echo $value->locality_id ?>"><?php echo $value->name;?>
               </option><?php } ?>

            </select>
          </div>
          <div class="checkout_fild">
            <input type="hidden" id="lat" name="customer_latitude" value="29.3604396"> 
            <input type="hidden" id="long" name="customer_longitude" value="48.0183705"> 
            <input type="hidden" id="editAdd" value="0"> 
            <label class="control-label">Select location on map</label>
            <<div id="map"></div>
          </div>
          <div class="checkout_fild">
            <input type="text" id="address_line1" name="address_line1" placeholder="Address">
                   <span style="display: none;" class="add_error complete_add"></span>
          </div>
          <div class="address_type">
            <h3>Address Type</h3>
            <div class="cus_radio">
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
</div> -->

<input type="hidden" id="user_id" value="<?=  (isset($userdata[0]->user_id))?$userdata[0]->user_id:""; ?>">

<script type="text/javascript">

  var baseUrl               ="<?=  base_url() ?>";
  var site_url              ="<?=  site_url() ?>";
  var base_url              = "<?php echo base_url(); ?>";
  var dishDataUrl           ="<?=  base_url() ?>Home/getDishData";
  var getdishdetails        ="<?=  base_url() ?>Home/getdishdetails";
  var getlocalites          ="<?=  site_url() ?>Home/getlocalites";
  var loginurl              = 'Webservice_Customers/normalUserLogin';
  var signurl               = 'Webservice_Customers/normalUserSignup';
  var forgotPasswordurl     = 'Webservice_Customers/forgotPassword';
  //var getDishChoice         ="<?=  base_url() ?>Home/getDishChoice/";
  var getDishChoice         ="<?php echo site_url('Webservice_Customers/getchoicewisedish'); ?>";
  var getOrderSummary       ="<?=  base_url() ?>Home/orderSummary";
  var getRestaurantDetail   ="<?=  base_url() ?>Home/getRestaurantDetail";
  var addDiliverAddress     ="<?=  base_url() ?>Home/addDiliverAddress";
  var getCustomerAddress    ="<?=  base_url() ?>Home/getCustomerAddress";
  var addorder              ="<?=  base_url() ?>Home/addOrder";
  var allMyorderStatus      ="<?=  base_url() ?>Home/allMyorderStatus";
  var dishImageUrl          ="<?=  'http://18.216.199.131/admin/timthumb.php?src=http://18.216.199.131/admin/assets/images/front-endold/dishes/' ?>";


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
  var deleteDish            = "<?php  echo $this->lang->line('message_deleteDish'); ?>";
  var OrderPlaced           ="<?=  $this->lang->line('message_OrderPlaced') ?>";
  var Foodisbeingprepared   ="<?=  $this->lang->line('message_Foodisbeingprepared') ?>";
  var Foodoutfordelivery    ="<?=  $this->lang->line('message_Foodoutfordelivery') ?>";
  var Delivered             ="<?=  $this->lang->line('message_Delivered') ?>";
  var EstDeliveryTime       ="<?=  $this->lang->line('message_EstDeliveryTime') ?>";
  var crossimg              ="<?= base_url() ?>assets/front-end/images/closed.png";
  var less                  ="<?php echo $this->lang->line('message_less'); ?>";
  var more                  ="<?php echo $this->lang->line('message_more'); ?>";
  var showMore              ="<?php echo $this->lang->line('message_showMore'); ?>";
  var showLess              ="<?php echo $this->lang->line('message_showLess'); ?>";
  var ADD                   ="<?php echo $this->lang->line('message_add'); ?>";
  var required              ="<?php echo $this->lang->line('message_required'); ?>";
  var PriceOnSelection      ="<?php echo $this->lang->line('message_PriceOnSelection'); ?>";
  var noDishFound           ="<?php echo $this->lang->line('message_noDishFound') ?>";
  var phoneMinlenth         ="<?php echo $this->lang->line('message_phoneMinlenth') ?>";
  var phoneIsnumeric        ="<?php echo $this->lang->line('message_EnetrPhone1') ?>";
  var userNotLogin          ="<?php echo $this->lang->line('message_userLogin') ?>";
  var OrderPlaced           ="<?php echo $this->lang->line('message_OrderPlaced') ?>";
  var Foodisbeingprepared   ="<?php echo $this->lang->line('message_Foodisbeingprepared') ?>";
  var Foodoutfordelivery    ="<?php echo $this->lang->line('message_Foodoutfordelivery') ?>";
  var Delivered             ="<?php echo $this->lang->line('message_Delivered') ?>";
  var tryAgain              ="<?php echo $this->lang->line('message_tryAgain') ?>";
  var EstDeliveryTime       ="<?php echo $this->lang->line('message_EstDeliveryTime') ?>";
  var dishNotAvailable      ="<?php  echo $this->lang->line('message_dishNotAvailable'); ?>";
  var restaurantTime        ="<?php  echo $this->lang->line('message_restaurantTime'); ?>";
  var EnterOtherReason      ="<?php  echo $this->lang->line('message_EnterOtherReason'); ?>";
  var AddressReqiured       ="<?php  echo $this->lang->line('message_AddressReqiured'); ?>";
  var PaymentMethod         ="<?php  echo $this->lang->line('message_PaymentMethod'); ?>";
  var lang                  ="<?php echo $this->langs; ?>";
  var is_favourite          =$("#is_favourite").val();
  var cancelOrder           ="<?php echo $this->ORD_status[14]; ?>";
  var addRestaurantRating   = "<?php echo site_url('Home/addRestaurantRating'); ?>";
  var addDriverRating       = "<?php echo site_url('Home/addDriverRating'); ?>";

  var addDiliverAddress     = "<?php echo base_url('Home/addDiliverAddress')?>";
  var getLatlongdata        = "<?php echo base_url('Home/getLatlongdata')?>";
  var popularDishes         = "<?php echo site_url('Home/popularDishes'); ?>";
  var addorder              = "<?php echo base_url(); ?>Home/addOrder";
  var imagepath             = "<?php echo base_url('assets/images/front-end/dishes/'); ?>";
  var fbdata                = "<?php echo site_url('Home/fblogin'); ?>";
  var getCustomerAddress    = "<?php echo base_url("Home/getCustomerAddress"); ?>";
  var minimumOrderAmount    = "<?php echo base_url("Home/minimumOrderAmount"); ?>";
  var checkOrderPlaceTime    = "<?php echo base_url("Home/checkOrderPlaceTime"); ?>";
  var getRepeatOrder        ="<?=  base_url() ?>Home/getRepeatOrder";
  
  var AllFieldsReq          = "<?php  echo $this->lang->line('message_AllFieldsReq'); ?>";
  var FirstNameReq          = "<?php  echo $this->lang->line('message_FirstNameReq'); ?>";
  var LastNameReq           = "<?php  echo $this->lang->line('message_LastNameReq'); ?>";
  var EmailReq              = "<?php  echo $this->lang->line('message_EmailReq'); ?>";
  var ValidEmail            = "<?php  echo $this->lang->line('message_EnetrEmail1'); ?>";
  var PhoneReq              = "<?php  echo $this->lang->line('message_PhoneReq'); ?>";
  var PasswordReq           = "<?php  echo $this->lang->line('message_PasswordReq'); ?>";
  var ConfPasswordReq       = "<?php  echo $this->lang->line('message_ConfPasswordReq'); ?>";
  var PleEnterValidEmail    = "<?php  echo $this->lang->line('message_PleEnterValidEmail'); ?>";
  var Passwordnotmatch      = "<?php  echo $this->lang->line('message_Passwordnotmatch'); ?>";
  var UserSignupSucc        = "<?php  echo $this->lang->line('message_UserSignupSucc'); ?>";
  var UserLoginSucc         = "<?php  echo $this->lang->line('message_UserLoginSucc'); ?>";
  var deleteDish            = "<?php  echo $this->lang->line('message_deleteDish'); ?>";
  var OrderNow              = "<?php   echo $this->lang->line('message_OrderNow'); ?>";
  var NoDishes              = "<?php    echo $this->lang->line('message_NoDishes'); ?>";
  var canclledFBlogin       = "<?php  echo $this->lang->line('message_UsrcanclledFBlogin'); ?>";
  var GoogleloginError      = "<?php  echo $this->lang->line('message_GoogleloginError'); ?>";
  var TotalLable            = "<?php  echo $this->lang->line('message_Total'); ?>";
</script>
<!-- flaoting sidebar -->
<script src="<?= base_url() ?>assets/front-end/js/ResizeSensor.js" type="text/javascript"></script>

<script src="<?= base_url() ?>assets/js/chosen/chosen.jquery.min.js" type="text/javascript"></script>

<script src="<?= base_url() ?>assets/front-end/js/sticky-sidebar.js" type="text/javascript"></script>

<!--<script src="<?= base_url() ?>assets/front-end/js/wow.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/front-end/js/wow.min.js" type="text/javascript"></script>-->

<script src="<?php echo base_url('assets/js/front-end/fb.js'); ?>"></script>

<script src="<?php echo base_url('assets/front-end/js/modality.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/custom.js'); ?>"></script>
<script src="<?php echo base_url('assets/front-end/js//owl.carousel.js'); ?>"></script>
<script src="<?php echo base_url('assets/front-end/js/easy-responsive-tabs.js'); ?>"></script>


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
