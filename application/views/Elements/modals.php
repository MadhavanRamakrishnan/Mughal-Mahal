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
        		<div class="login_form_fild">
        			<input type="text" name="email" readonly onfocus="this.removeAttribute('readonly');" style="background-color:#fff" id="email" placeholder="<?php  echo $this->lang->line('message_Email'); ?>">
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
        			<button type="submit" name="login" id="loginform"><img style="display: none;" class="loginloader" height="80" width="80" src="<?=  base_url('assets/images/front-end/spinner.gif'); ?>"><?php  echo $this->lang->line('message_Login'); ?></button>
        			<!-- <a href="javascript:void(0);" onclick="fbLogin()" id="fbLink"><img src="<?php// echo base_url('assets/images/front-end/fblogin.png');?>"/></a> -->
                    <!-- <div id="fSignIn" onclick="fbLogin()"></div> -->
            </form>   
                <div class="sociallogin">
                    <ul>
                        <li>
                            <a class="fblogin" onclick="fbLogin()" href="javascript:void(0)"><span><i class="fa fa-facebook-square"></i></span><?php  echo $this->lang->line('message_LoginWithFacebook'); ?></a>
                        </li>
                        <li>
                           <a class="gplogin" onclick="onLoadGoogleCallback()" id="go" href="javascript:void(0)"><span><i class="fa fa-google-plus-square"></i></span><?php  echo $this->lang->line('message_LoginWithGoogle'); ?></a>
                        </li>
                    </ul>
                </div>
    		</div>
    		<div class="login_popup_btm">
    			<div class="login_popup_btm_left">
    				<p><?php  echo $this->lang->line('message_Donthaveanaccount'); ?></p>
    			</div>
    			<div class="login_popup_btm_right">
    				<a href="#register" aria-label="open"><button data-target="#login" aria-label="close"><?php  echo $this->lang->line('message_Register'); ?></button></a>
    			</div>
    		</div>
    	</div>
        <div class="popup_close">
            <a href="#login" aria-label="close"><img src="<?=  base_url('assets/images/front-end/icon/ic_cancel.svg') ?>"></a>
        </div>
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
	    			<div class="register_form_fild_half left">
	        			<input type="text" name="first_name" id="fname" placeholder="<?php  echo $this->lang->line('message_First')." ".$this->lang->line('message_Name'); ?>">
	        		</div>
	        		<div class="register_form_fild_half right">
	        			<input type="text" name="last_name" id="lname" placeholder="<?php  echo $this->lang->line('message_Last')." ".$this->lang->line('message_Name'); ?>">
	        		</div>
	    		</div>
	    		<div class="register_form_fild">
	    			<input type="text" name="email" id="user_email" placeholder="<?php  echo $this->lang->line('message_Email'); ?>" readonly onfocus="this.removeAttribute('readonly');">
	    		</div>
	    		<div class="register_form_fild">
	    			<input type="text" name="phoneno" id="phone" maxlength="8" placeholder="<?php  echo $this->lang->line('message_Phone'); ?>" >
                    <input type="hidden" name="country_code" id="country_code" value="+965">
	    		</div>
	    		<div class="register_form_fild">
	    			<input type="password" name="password" id="user_password" placeholder="<?php  echo $this->lang->line('message_Password'); ?>" readonly onfocus="this.removeAttribute('readonly');">
	    		</div>
	    		<div class="register_form_fild">
	    			<input type="password" name="cfnpassword" id="cfnpassword" placeholder="<?php  echo $this->lang->line('message_ConfirmPassword'); ?>">
	    		</div>
	    		<div class="register_form_btn">
	    			<input type="submit" name="register" id="submitregister" value="<?php  echo $this->lang->line('message_Register'); ?>">
	    		</div>
    		</form>
    	</div>
        <div class="popup_close">
            <a href="#register" aria-label="close"><img src="<?=  base_url('assets/images/front-end/icon/ic_cancel.svg') ?>"></a>
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
    			<input type="text" name="email" id="forgot_email" placeholder="<?php  echo $this->lang->line('message_Email'); ?>">
    		</div>
    		
    		<div class="forgot_pass_form_btn">
    			<input type="button" name="submit" value="<?php  echo $this->lang->line('message_Send'); ?>" id="forgotPassword" >
    		</div>
    	</div>
        <div class="popup_close">
            <a  href="#forgot_pass" aria-label="close"><img src="<?=  base_url('assets/images/front-end/icon/ic_cancel.svg') ?>"></a>
        </div>
	</div>
</div>

<!-- Add Address -->
<div id="address" class="yourModalClass" role="dialog" aria-labelledby="yourModalHeading" style="display:none;">
    <div class="checkout_popup">
    	<h2><?php  echo $this->lang->line('message_Addres'); ?></h2>
    	<form method="post" name="addressadd" id="addressadd">
    	<div class="ckeckout_form_top">
    		<p><?php  echo $this->lang->line('message_YourDetails'); ?></p>
    		<div class="checkout_fild">
    			<input type="text" id="customer_name" name="customer_name" placeholder="<?php  echo $this->lang->line('message_Name'); ?>" id="addname">
                <span style="display: none;" class="error name"><?php  echo $this->lang->line('message_EnetrName'); ?></span>
    		</div>
    		<div class="checkout_fild">
    			<input type="email" id="addemail" name="addemail" placeholder="<?php  echo $this->lang->line('message_Email'); ?>" id="addemail">
                <span style="display: none;" class="error email"><?php  echo $this->lang->line('message_EnetrEmail'); ?></span>
                <span style="display: none;" class="error email1"><?php  echo $this->lang->line('message_EnetrEmail1'); ?></span>
    		</div>
    		<div class="checkout_fild">
    			<input type="tel" id="contact_no" name="contact_no" placeholder="<?php  echo $this->lang->line('message_Phone'); ?>" id="addphone" maxlength="8">
                <span style="display: none;" class="error phone"><?php  echo $this->lang->line('message_EnetrPhone'); ?></span>
                <span style="display: none;" class="error phone1"><?php  echo $this->lang->line('message_EnetrPhone1'); ?></span>
    		</div>
    	</div>
    	<div class="checkout_form_btm">
    		<p><?php  echo $this->lang->line('message_DeliveryAddress'); ?></p>
    		<div class="checkout_fild">
    			<select id="locality" name="locality"></select>

    		</div>
    		<div class="checkout_fild">
    			<input type="hidden" id="lat" name="customer_latitude"  value="29.3518587"/> 
                <input type="hidden" id="long" name="customer_longitude" value="47.9836915"/> 
                <input type="hidden" id="editAdd" value="0"/> 
                <label class="control-label"><?php  echo $this->lang->line('message_Selectlocationonmap'); ?></label>
                <div id="map"></div>

    		</div>

    		<div class="checkout_fild">
    			<input type="text" id="address_line1" name="address_line1" placeholder="<?php  echo $this->lang->line('message_Addres'); ?>" id="address">
                 <span style="display: none;" class="error complete_add"><?php  echo $this->lang->line('message_completeAddress'); ?></span>
    		</div>
    		<div class="address_type">
    			<h3><?php  echo $this->lang->line('message_AddressType'); ?></h3>
    			<div class="cus_radio">
	    			<ul  style="display: flex;">
		    			<li>
		    				<input type="radio" id="home" name="address_type" value="1" checked>
	    					<label for="home"><?php  echo $this->lang->line('message_Home'); ?></label>
		    			</li>
		    			<li>
		    				<input type="radio" id="office" name="address_type" value="2">
	    					<label for="office"><?php  echo $this->lang->line('message_Office'); ?></label>
		    			</li>
		    			<li>
		    				<input type="radio" id="other" name="address_type" value="3">
	    					<label for="other"><?php  echo $this->lang->line('message_Other'); ?></label>
		    			</li>
		    		</ul>
		    	</div>
    		</div>
    	</div>
    	</form>
    	<div class="place_order_btn">
    		<a href="javascript:void(0)" onclick="addaddress()"><?php  echo $this->lang->line('message_SaveAddress'); ?></a>
    	</div>
        <div class="popup_close">
            <a href="#address" aria-label="close"><img src="<?=  base_url('assets/images/front-end/icon/ic_cancel.svg') ?>"></a>
        </div>
	</div>
    </div>
</div>

<div id="product_add" class="yourModalClass" role="dialog" aria-labelledby="yourModalHeading" style="display:none;">
    <div class="open_cart_popup">
        <form method="post" name="dishDetails" id="dishDetails">
        <div class="product_name"></div>
        <input type="hidden" name="dish_id" id="dish_id" value="">
        <div class="product_description">
            <p></p>
        </div>
        <div class="popup_close">
            <a href="#product_add" aria-label="close"><img src="">X</a>
        </div>
        <div class="add_to_bag_btn">
            <input type="hidden" name="product_price" id="product_price" value="">
            <input type="hidden" name="finalprice"  prePrice="" id="finalprice" value="">
            <input type="hidden" name="addonprice" id="addonprice" value="">
            <input type="hidden" name="dishcount" id="dishcount" value="1">
            <button type="button" id="dishsubmit" onclick="formsubmit()"><?=  $this->lang->line('message_AddToBag'); ?> <span class="finalprice"></span></button>
        </div>
        </form>
    </div>
</div>