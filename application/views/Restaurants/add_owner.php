<div class="warper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Add Manager</h1>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Create Manager Profile</span></div>
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
                    <form method="post" action="<?php  echo site_url('Restaurants/addOwner');?>" enctype="multipart/form-data">
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
                             <div class="form-group ">
                                <label class="control-label">Restaurant Branch </label>
                                <select class="form-control chosen-select" name="restaurant_id" id="branch">
                                     
                                        <option value="" >Select Restaurant Branch</option><?php 
                                            foreach ($resList as $key => $value){ ?>
                                                <option  value="<?php echo $value->restaurant_id ?>" <?php echo (set_value('restaurant_id')== $value->restaurant_id)?"selected":"";  ?>><?php echo $value->restaurant_name; ?>
                                            </option><?php } ?> 
                                </select>
                                <div class="color-red"><?php echo form_error('restaurant_id'); ?>
                                </div>
                            </div>
                             <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Email<i class="reustarred">*</i></label>
                                        <input class="form-control" name="email" id="email" placeholder="Enter Email"  type="text" value="<?php echo (set_value('email'))?set_value('email'):""; ?>">
                                        <div class="color-red"><?php echo form_error('email'); ?></div>
                                    </div>
                                </div>
                            </div>
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
                        </div>
                           <!--  <hr class="dotted"> -->
                         <div class="col-lg-12 col-md-12 col-sm-12"> 
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                     <input type="submit" class="btn btn-success" name="add" value="Save">
                                       <a href="<?php echo site_url('Restaurants/ownersList'); ?>" type="button" class="btn btn-success ">Cancel</a>
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

  var getStateUrl          = "<?php echo site_url('Restaurants/getState')?>";
  var getCityUrl           = "<?php echo site_url('Restaurants/getCity')?>";
  var ownerslist           = "";
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/restaurant.js"></script>