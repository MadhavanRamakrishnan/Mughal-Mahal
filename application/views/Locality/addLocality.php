    <style type="text/css">
    .map{
       
        min-height: 373px;
       /* margin-top:10px;*/
    }
    .minutes{
        font-weight:12px;
    }
</style>
<div class="warper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Add Locality</h1>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Add Locality Details</span></div>
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
                    <form method="post" action="<?php  echo site_url('Localities/addLocality');?>" >
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="control-label">Name<i class="reustarred">*</i></label>
                                            <input class="form-control" name="name" id="name" placeholder="Enter locality name"  type="text" value="<?php echo (set_value('name'))?set_value('name'):""; ?>">
                                            <div class="color-red"><?php echo form_error('name'); ?></div>
                                         </div>
                                    </div>
                                    
                                </div>
                       
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="control-label">Arabic Name<i class="reustarred">*</i></label>
                                            <input class="form-control" name="name_ar" id="name_ar" placeholder="Enter locality arabic name"  type="text" value="<?php echo (set_value('name_ar'))?set_value('name_ar'):""; ?>">
                                            <div class="color-red"><?php echo form_error('name_ar'); ?></div>
                                         </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="control-label">Delivery Time   </label><span class="minutes">   (Minutes)</span><i class="reustarred">*</i>
                                            <select class="form-control" name="delivered_time" id="delivered_time">
                                                <option value="">Select delivery time</option>
                                                <?php foreach ($deliveryTimes as $key) { ?>
                                                    <option value="<?php echo $key ?>"><?php echo $key ?></option>
                                                <?php  } ?>
                                            </select>
                                           
                                            <div class="color-red"><?php echo form_error('delivered_time'); ?></div>
                                         </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="control-label">Delivery Charge<i class="reustarred">*</i></label>
                                            <input class="form-control" name="delivery_charge" id="delivery_charge" placeholder="Enter delivery charge"  type="text" value="<?php echo (set_value('delivery_charge'))?set_value('delivery_charge'):""; ?>">
                                            <div class="color-red"><?php echo form_error('delivery_charge'); ?></div>
                                         </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="control-label">Minimum Amount for Order <i class="reustarred">*</i></label>
                                            <input class="form-control" name="min_order_amount" id="min_order_amount" placeholder="Enter minimum order amount"  type="text" value="<?php echo (set_value('min_order_amount'))?set_value('min_order_amount'):""; ?>">
                                            <div class="color-red"><?php echo form_error('min_order_amount'); ?></div>
                                         </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-12 " >
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label class="control-label">Select Location<i class="reustarred">*</i></label>
                                                    <input id="pac-input" name="searchBosx" class="controls" type="text" placeholder="Search Box" value="<?php echo set_value('searchBosx'); ?>">
                                                    <div class="map" id="map" ></div>
                                                    <input type="hidden" name="lat" id="lat" value="<?= (set_value('lat'))?set_value('lat'):'29.3518587'; ?>" >
                                                    <input type="hidden" name="lon" id="lon" value="<?= (set_value('lon'))?set_value('lon'):'47.9836915'; ?>">
                                                    <div class="color-red"><?php echo form_error('lat'); ?></div>
                                                </div>
                                            </div>
                                        
                                         </div>
                                    </div>
                                </div>
                             </div>
                            </div>
                         

                        <hr class="dotted">
                        <div class="form-group" style="text-align: center;">
                           <input type="submit" class="btn btn-success" name="add" value="Save">
                           <a href="<?php echo site_url('Localities'); ?>" type="button" class="btn btn-success ">Cancel</a>
                       </div>
                   </form>
               </div>
           </div>
       </div>
   </div>
</div>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/locality.js">

