<div class="warper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Update User</h1>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Update User Details</span>
                </div>
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
                    <form method="post" action="<?php  echo site_url('User/editProfile/'.$userdata[0]->user_id);?>" enctype="multipart/form-data">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">First Name<i class="reustarred">*</i></label>
                                        <input class="form-control" name="first_name" id="first_name" placeholder="Enter First Name"  type="text" value="<?php echo (set_value('first_name'))?set_value('first_name'):$userdata[0]->first_name; ?>">
                                        <div class="color-red"><?php echo form_error('first_name'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Last Name<i class="reustarred">*</i></label>
                                        <input class="form-control" name="last_name" id="last_name" placeholder="Enter Last Name"  type="text" value="<?php echo (set_value('last_name'))?set_value('last_name'):$userdata[0]->last_name; ?>">
                                        <div class="color-red"><?php echo form_error('last_name'); ?></div>
                                    </div>
                                </div>
                            </div>
                             <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Email<i class="reustarred">*</i></label>
                                        <input class="form-control" name="email" id="email" placeholder="Enter Email"  type="text" readonly  value="<?php echo (set_value('email'))?set_value('email'):$userdata[0]->email; ?>">
                                        <div class="color-red"><?php echo form_error('email'); ?></div>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                           
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Contact No.<i class="reustarred">*</i></label>
                                        <input class="form-control" name="contact_no" id="contact_no" placeholder="Enter Contact No."  type="text" value="<?php echo (set_value('contact_no'))?set_value('contact_no'):$userdata[0]->contact_no; ?>" maxlength="8">
                                        <div class="color-red"><?php echo form_error('contact_no'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Profile Image<i class="reustarred"></i></label>
                                        <input class="form-control" name="image" id="image" type="file">
                                    </div>
                                    <?php
                                        $ImgLoc=base_url().'assets/uploads/users/'.$userdata[0]->profile_photo;
                                     ?>
                                    <div class="col-lg-12 m-t-12" style="margin-top: 10px;">
                                        <div class="thumbnail edit-thumbnail">
                                            <img src="<?php echo $ImgLoc; ?>" width="100" height="100" class="img-responsive" id="changeimages" style="text-align: center;margin: 0 auto;width: 40%;height:100px">
                                        </div>
                                    </div>
                                <?php if(isset($image_error)){echo $image_error;} ?>
                            </div>
                          </div>
                            <hr class="dotted">
                         <div class="col-lg-12 col-md-12 col-sm-12"> 
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <input type="hidden" name="user_id" value="<?php echo $userdata[0]->user_id;?>">
                                     <input type="submit" class="btn btn-primary" name="update" value="Update">
                                       <a href="<?php echo site_url('Dashboard'); ?>" type="button" class="btn btn-primary ">Cancel</a>
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

  var getStateUrl          = "<?php echo site_url('User/getState')?>";
  var getCityUrl           = "<?php echo site_url('User/getCity')?>";
  var countryId            = "<?php echo $country;?>"; 
  var stateId              = "<?php echo $state;?>"; 
  var cityId               = "<?php echo $city;?>"; 
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/profile.js"></script>