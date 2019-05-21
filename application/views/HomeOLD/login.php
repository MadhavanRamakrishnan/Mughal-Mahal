<div id="login" class="yourModalClass login" role="dialog" aria-labelledby="yourModalHeading" style="display:none;">
    <div class="login_popup">
    	<div class="login_popup_cnt">
    		<h2>Login</h2>
    		<div class="alert alert-danger alert-dismissible" id="error_notification" style="display:none;">
                <button aria-hidden="true" data-dismiss="alert" class="close closealert" type="button">×</button>
                <!-- <h4><i class="icon fa fa-warning"></i> Failed!</h4> -->
                <p id="error_message"></p>
              </div>
    		<div class="login_form_fild">
    			<input type="email" name="email" id="email" placeholder="Email">
    		</div>
    		<div class="login_form_fild">
    			<input type="password" name="password" id="password" placeholder="Password">
    		</div>
    		<div class="login_form_fild_btm">
    			<div class="login_form_fild_btm_left">
    				<label class="checkbox_custom">Remember me
					  	<input type="checkbox">
					  	<span class="checkmark"></span>
					</label>
    			</div>
    			<div class="login_form_fild_btm_right">
    				<a href="#forgot_pass" aria-label="open">Forgot password?</a>
    			</div>
    		</div>
    		<div class="login_form_btn">
    			<input type="button" name="login" id="loginform" value="Login">
    		</div>
    		<div class="login_popup_btm">
    			<div class="login_popup_btm_left">
    				<p>Don’t have an account?</p>
    			</div>
    			<div class="login_popup_btm_right">
    				<a href="#register" aria-label="open">Register</a>
    			</div>
    		</div>
    	</div>
        <div class="popup_close">
            <a href="#login" aria-label="close"><img src="<?php echo base_url('assets/images/front-end/icon/ic_cancel.svg') ?>"></a>
        </div>
	</div>
</div>