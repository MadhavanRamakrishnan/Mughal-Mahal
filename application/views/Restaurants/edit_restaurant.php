<?php $DeliveryTime = array("3600"=>"60 Minute", "4200"=>"70 Minute", "4800"=>"80 Minute","5400"=>"90 Minute", "6000"=>"100 Minute", "6600"=>"110 Minute", "7200"=>"120 Minute");

 // $m_data   = implode(',', $managData);
 // $o_data   = implode(',', $ownData);

?>
<link href="<?php echo base_url(); ?>assets/chosen/css/chosen.min.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/chosen/js/chosen.jquery.min.js"></script>
<div class="warper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Update Restaurants</h1>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Update Restaurants Details</span></div>
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
                    <form method="post" action="<?php  echo site_url('Restaurants/editRestaurants/'.$res_data[0]->restaurant_id);?>" enctype="multipart/form-data">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Restaurant Name<i class="reustarred">*</i></label>
                                        <input class="form-control" name="restaurant_name" id="restaurant_name" placeholder="Enter Restaurant Name"  type="text" value="<?php echo (set_value('restaurant_name'))?set_value('restaurant_name'):$res_data[0]->restaurant_name; ?>">
                                        <div class="color-red"><?php echo form_error('restaurant_name'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Restaurant Headline<i class="reustarred">*</i></label>
                                        <input class="form-control" name="headline" id="headline" placeholder="Enter Headline"  type="text" value="<?php echo (set_value('headline'))?set_value('headline'):$res_data[0]->headline; ?>">
                                        <div class="color-red"><?php echo form_error('headline'); ?></div>
                                    </div>
                                </div>
                            </div>
                             <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Contact No.<i class="reustarred">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">+965</span>
                                            <input class="form-control" name="contact_no" id="contact_no" placeholder="Enter Contact No."  type="text" value="<?php echo (set_value('contact_no'))?set_value('contact_no'):$res_data[0]->contact_no; ?>" maxlength="8">
                                        </div>
                                        
                                        <div class="color-red"><?php echo form_error('contact_no'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Restaurant Owner</label>
                                <select class="form-control chosen-select"  data-placeholder="Select Restaurant Owner" name="owner_name" id="">
                                   <option value="">Select Restaurant Owner</option>
                                   <?php  foreach ($ownerList as $key => $value){ ?>
                                   <option  value="<?php echo $value->user_id ?>"  <?php if ($value->user_id ==  $res_data[0]->o_uid) echo 'selected' ; ?>><?php echo $value->first_name.' '.$value->last_name; ?>
                                   </option><?php } ?> 
                               </select>
                               <div class="color-red"><?php echo form_error('owner_name'); ?></div>
                            </div>
                           <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Email<i class="reustarred">*</i></label>
                                        <input class="form-control" name="email" id="email" placeholder="Enter Email"  type="text" readonly  value="<?php echo (set_value('email'))?set_value('email'):$res_data[0]->email; ?>">
                                        <div class="color-red"><?php echo form_error('email'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label pdn-lbl">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="6" placeholder="Enter Description"><?php echo (set_value('description'))?set_value('description'):$res_data[0]->description; ?></textarea>
                                <div class="color-red"><?php echo form_error('description'); ?></div>
                            </div>
                            
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label class="control-label">Banner Image<i class="reustarred">*</i></label>
                                        <div class="color-red"><?php echo (isset($image_error))?$image_error:""; ?></div>
                                        <input class="form-control" name="image" id="image" type="file">
                                    </div>
                                    <?php
                                    $ImgLoc="./assets/uploads/restaurants/".$res_data[0]->banner_image;
                                    ?>
                                    <div class="col-lg-6 " >
                                        <div class="thumbnail edit-thumbnail">
                                            <?php  if(file_exists($ImgLoc) && !empty($res_data[0]->banner_image)){  ?>
                                            <img src="<?php echo base_url().'assets/uploads/restaurants/'.$res_data[0]->banner_image; ?>" class="img-responsive" id="changeimages" style="text-align: center;margin: 0 auto;width: 100%;height:100%">

                                            <?php }else{ ?>
                                            <img src="<?php echo base_url().'assets/uploads/restaurants/no_image.png'; ?>" class="img-responsive" id="changeimages" style="text-align: center;margin: 0 auto;width: 40%;height:100px">
                                            <?php  } ?>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Delivery Contact No.<i class="reustarred">*</i></label>

                                        <div class="input-group">
                                            <span class="input-group-addon">+965</span>
                                           <input class="form-control" name="delivery_no" id="delivery_no" placeholder="Enter Delivery Contact No."  type="text" value="<?php echo (set_value('delivery_no'))?set_value('delivery_no'):$res_data[0]->delivery_contact_no; ?>" maxlength='8'>
                                        </div>
                                        
                                        <div class="color-red"><?php echo form_error('delivery_no'); ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="control-label">Address<i class="reustarred">*</i></label>
                                        <input class="form-control" name="address" id="location" placeholder="Enter Address"  type="text" value="<?php echo (set_value('address'))?set_value('address'):$res_data[0]->address; ?>">
                                        <div class="color-red"><?php echo form_error('address'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Locality</label>
                                <select   class=" form-control chosen-select chosen" data-placeholder="Select Locality" multiple    name="localities[]" id="">
                                       <?php 
                                       
                                            foreach ($Localities as $key => $value){ ?>
                                            <option  value="<?php echo $value->locality_id ?>" <?php if ($value->restaurant_id ==  $res_data[0]->restaurant_id) echo 'selected' ; ?>><?php echo $value->name; ?>
                                                </option><?php   } ?> 
                                </select>
                                <div class="color-red"><?php echo form_error('manager_name'); ?></div>
                            </div>
                            
                        </div>
                        <hr class="dotted">
                        <div class="col-lg-12 col-md-12 col-sm-12"> 
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <input type="hidden" name="restaurant_id" value="<?php echo $res_data[0]->restaurant_id;?>">
                                    <input type="submit" class="btn btn-success" name="update" value="Update">

                                    <a href="<?php echo ($userdata[0]->role_id == 1)?site_url('Restaurants/index'):site_url('Restaurants/owners'); ?>" type="button" class="btn btn-success ">Cancel</a>
                                    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>
