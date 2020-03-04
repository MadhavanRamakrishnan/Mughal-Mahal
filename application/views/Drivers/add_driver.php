
<div class="warper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Add Driver</h1>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Create Driver Profile</span></div>
                    <div class="col-md-12 col-lg-12" style="margin-top:10px;">
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

                    </div>
                <div class="panel-body">
                    <form method="post" action="<?php  echo site_url('Drivers/addDrivers');?>" enctype="multipart/form-data">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                             <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">First Name<i class="reustarred">*</i></label>
                                        <input class="form-control" name="fname" id="fname" placeholder="Enter First Name"  type="text" value="<?php echo (set_value('fname'))?set_value('fname'):""; ?>">
                                        <div class="color-red"><?php echo form_error('fname'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Last Name<i class="reustarred">*</i></label>
                                        <input class="form-control" name="lname" id="lname" placeholder="Enter Last Name"  type="text" value="<?php echo (set_value('lname'))?set_value('lname'):""; ?>">
                                        <div class="color-red"><?php echo form_error('lname'); ?></div>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Email<!-- <i class="reustarred">*</i> --></label>
                                        <input class="form-control" name="email" id="email" placeholder="Enter Email"  type="text" value="<?php echo (set_value('email'))?set_value('email'):""; ?>">
                                        <div class="color-red"><?php echo form_error('email'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Vendor</label>
                                        <input class="form-control" name="vendor" id="vendor" placeholder="Enter Vendor"  type="text" value="<?php echo (set_value('vendor'))?set_value('vendor'):""; ?>">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Contact No.<i class="reustarred">*</i></label>
                                        <div class="input-group">
                                          <span class="input-group-addon">+965</span>
                                          <input class="form-control" name="contact_no" id="contact_no" placeholder="Enter Contact No."  type="text" value="<?php echo (set_value('contact_no'))?set_value('contact_no'):""; ?>" maxlength="8">
                                        </div>
                                        
                                        <div class="color-red"><?php echo form_error('contact_no'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Password<i class="reustarred">*</i></label>
                                        <input class="form-control" name="driver_password" id="driver_password" placeholder="Enter Password"  type="text" value="<?php echo (set_value('driver_password'))?set_value('driver_password'):""; ?>" >
                                        
                                        <div class="color-red"><?php echo form_error('driver_password'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Restaurant Branch<i class="reustarred">*</i> </label>
                                <select class="form-control chosen-select" name="branch" id="branch">
                                     <?php 
                                        if(set_value('branch')){$branch=set_value('branch');}
                                        else {$branch="";} 
                                        
                                        if($userdata[0]->role_id ==$this->admin_Role || $userdata[0]->role_id ==$this->sales_Role){?>
                                            <option value="">Select Restaurant Branch</option>
                                        <?php  }

                                            foreach ($resList as $key => $value){ ?>
                                                <option  value="<?php echo $value->restaurant_id ?>" <?php echo ($branch == $value->restaurant_id)?"selected":""; ?> <?php echo ($resId == $value->restaurant_id)?"selected":""; ?>><?php echo $value->restaurant_name; ?>
                                                </option>
                                        <?php } ?> 
                                </select>
                                <div class="color-red"><?php echo form_error('branch'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label class="control-label">Profile Image<i class="reustarred">*</i></label>
                                        <input class="form-control" name="image" id="image" type="file">
                                    </div>
                                </div>
                               <div class="color-red"> <?php if(isset($image_error)){echo $image_error;}  echo form_error('image'); ?> </div>
                            </div>
                        </div>
                            <hr class="dotted">
                         <div class="col-lg-12 col-md-12 col-sm-12"> 
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                     <input type="submit" class="btn btn-primary" name="add" value="Save">
                                       <a href="<?php echo site_url('Drivers/index'); ?>" type="button" class="btn btn-primary ">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

  var getStateUrl          = "<?php echo site_url('Drivers/getState')?>";
  var getCityUrl           = "<?php echo site_url('Drivers/getCity')?>";
  var countryId            = "<?php echo $country;?>"; 
  var stateId              = "<?php echo $stateId;?>"; 
  var cityId               = "<?php echo $city;?>"; 
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/driver.js"></script>