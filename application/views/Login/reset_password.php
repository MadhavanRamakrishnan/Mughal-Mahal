<div class="container">
  <div class="row">
    <div class="col-lg-4 col-lg-offset-4 text-center m-t-30">
        <img class="text-center" src="<?php echo base_url('assets/images/avtar/mughal_mahal_logo.png'); ?>" style="max-width: 60%;">
        
        <?php 
        if($this->session->flashdata() != ""){
          if($this->session->flashdata('error') != ""){
        ?>
          <p class="text-danger m-t-30"><?php echo $this->session->flashdata('error'); ?></p>
        <?php } else { ?>
          <p class="text-success m-t-30"><?php echo $this->session->flashdata('success'); ?></p>
        <?php } } ?>
        
        <h4 class="m-t-20">Reset password</h4>
        <hr class="clean">
          <form action="<?php echo site_url('Login/updateUserpassword'); ?>" method="post">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $userdata[0]->user_id; ?>">
            <input type="hidden" name="user_role" id="user_role" value="<?php echo $user_role; ?>">
            <input type="hidden" name="security_token" id="security_token" value="<?php echo $userdata[0]->security_token; ?>">

            <div class="form-group input-group">
              <span class="input-group-addon"><i class="fa fa-key"></i></span>
              <input type="password" name="password" id="password" class="form-control" value="<?php if(isset($post)){ echo $post['password'];} ?>" placeholder="password">
            </div>
            <div class="form-group input-group"><div class="color-red"><?php echo form_error('password'); ?></div></div>

            <div class="form-group input-group">
              <span class="input-group-addon"><i class="fa fa-key"></i></span>
              <input type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?php if(isset($post)){ echo $post['confirm_password'];} ?>" placeholder="Confirm Password">
            </div>
            <div class="form-group input-group"><div class="color-red"><?php echo form_error('confirm_password'); ?></div></div>
            
            <button type="submit" name="sbtok" id="sbtok" class="btn btn-purple btn-block">Reset Password</button>
          </form>
        <hr>
         <a href="<?php echo ($user_role == '5')?site_url('Home'):site_url('Login');?>"><p class="text-center text-gray">Login</p></a>
    </div>
  </div>
</div>