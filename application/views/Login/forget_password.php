<div class="container">
  <div class="row">
  <div class="col-lg-4 col-lg-offset-4 text-center m-t-30">
        <img class="text-center" src="<?php echo base_url('assets/images/avtar/mughal_mahal_logo.png'); ?>" style="max-width: 60%;">
        <h4 class="m-t-20">Forgot password</h4>
        
        <?php 
        if($this->session->flashdata() != ""){

          if($this->session->flashdata('error') != ""){
        ?>
            <p class="text-danger m-t-30"><?php echo $this->session->flashdata('error'); ?></p>
        <?php
          }
          else{
        ?>
            <p class="text-success m-t-30"><?php echo $this->session->flashdata('success'); ?></p>
        <?php
          }
        }
        ?>
        <hr class="clean">
          <form action="<?php echo site_url('Login/forgotPassword'); ?>" method="post">

            <div class="form-group input-group">
              <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
              <input type="email" name="email" class="form-control" value="<?php if(isset($post)){ echo $post['email'];} ?>"  placeholder="Email Adress">
            </div>
            <div class="form-group input-group"><div class="color-red"><?php echo form_error('email'); ?></div></div>
            
            <button type="submit" name="sbtok" id="sbtok" class="btn btn-purple btn-block">Reset Password</button>
          </form>
        <hr>
        <a href="<?php echo site_url('Login');?>"><p class="text-center text-gray">Login</p></a>
        
    </div>
    </div>
</div>