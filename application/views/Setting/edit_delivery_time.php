<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<style type="text/css">
    table thead tr th, .center{
        text-align: center;
    }
</style>
<div class="warper container-fluid">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order.js"></script>
    <div class="row">
        <div class="col-lg-12">
                
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#delivery" role="tab" data-toggle="tab">Delivery Time</a></li>
                
                <!-- <li><a href="<?php echo site_url("Setting/RestaurantAvelibility"); ?>">Restaurant Avalibility</a></li> -->
            </ul>
            
            <?php $DeliveryTime = array("3600"=>"60 Minute", "4200"=>"70 Minute","4800"=>"80 Minute", "5400"=>"90 Minute", "6000"=>"100 Minute", "7200"=>"120 Minute");?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Update Delivery Time</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Update Delivery Time</span></div>
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
                     <?php  foreach ($restoruntData as $key => $value) { 
                            //echo "<pre>";print_r($value);exit;
                            ?>
                    <form method="post" action="<?php  echo site_url('Setting/index/');?>" enctype="multipart/form-data">
                       
                            <input type="hidden" name="restaurant_id[]" value="<?php echo $value->restaurant_id; ?>">
                      
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                              
                                    <div class="col-lg-12"><br><br>
                                        <label class="control-label"><?php echo $value->restaurant_name;?>:</label>
                                        <!-- <input class="form-control" name="restaurant_name[]" id="restaurant_name" placeholder="Enter Restaurant Name"  type="text" value="<?php echo (set_value('restaurant_name[]'))?set_value('restaurant_name[]'):$value->restaurant_name; ?>"> -->
                                        <div class="color-red"><?php echo form_error('restaurant_name[]'); ?></div>
                                 
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                          <div class="form-group">
                                <label class="control-label">Change Delivery Time<i class="reustarred">*</i></label>
                                 <select class="form-control" name="delivery_time[]" id="delivery_time">
                                  <?php  
                                    if (set_value('delivery_time')) {
                                        $dlv_time=set_value('delivery_time');
                                    }
                                    else if($value->custom_delivery_time){
                                        $dlv_time=$value->custom_delivery_time;
                                    }
                                    else{
                                        $dlv_time="";
                                    } ?>
                                    <option value="0">Select Delivery Time</option>
                                    <?php foreach ($DeliveryTime as $key => $value) { ?>
                                        <option value="<?php echo $key;?>" <?php echo ($dlv_time == $key)?"selected":"";?>><?php echo $value;?></option>
                                     <?php } ?>
                                </select>
                                <div class="color-red"><?php echo form_error('delivery_time'); ?></div>
                            </div>
                        </div>
                       
                        <?php  } ?>
                         <div class="col-lg-12 col-md-12 col-sm-12"> 
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                   
                                     <input type="submit" class="btn btn-primary" name="update" value="Update">
                                       
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>