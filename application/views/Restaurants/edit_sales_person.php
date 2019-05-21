                            
<div class="warper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Update Sales Person</h1>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Update sales person profile</span></div>
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
                    <form method="post" action="<?php  echo site_url('Restaurants/editSalesPerson/'.$salesData[0]->user_id);?>" enctype="multipart/form-data">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                             <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">First Name<i class="reustarred">*</i></label>
                                        <input class="form-control" name="fname" id="fname" placeholder="Enter First Name"  type="text" value="<?php  echo (set_value('fname'))?set_value('fname'):$salesData[0]->first_name; ?>">
                                        <div class="color-red"><?php echo form_error('fname'); ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Last Name<i class="reustarred">*</i></label>
                                        <input class="form-control" name="lname" id="lname" placeholder="Enter Last Name"  type="text" value="<?php echo (set_value('lname'))?set_value('lname'):$salesData[0]->last_name; ?>">
                                        <div class="color-red"><?php echo form_error('lname'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Email<i class="reustarred">*</i></label>
                                        <input class="form-control" readonly name="email" id="email" placeholder="Enter Email"  type="text" value="<?php echo (set_value('email'))?set_value('email'):$salesData[0]->email; ?>">
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
                                            
                                            
                                            <input class="form-control" name="contact_no" id="contact_no" placeholder="Enter Contact No."  type="text" value="<?php echo (set_value('contact_no'))?set_value('contact_no'):$salesData[0]->contact_no; ?>" maxlength="8">
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
                                <input type="hidden" name="user_id" value="<?php echo $salesData[0]->user_id;?>">
                                     <input type="submit" class="btn btn-success" name="update" value="Update">
                                       <a href="<?php echo site_url('Restaurants/sales_person'); ?>" type="button" class="btn btn-success ">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
