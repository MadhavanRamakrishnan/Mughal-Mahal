<?php $DeliveryTime = array("3600"=>"60 Minute", "4200"=>"70 Minute", "4800"=>"80 Minute","5400"=>"90 Minute", "6000"=>"100 Minute", "6600"=>"110 Minute", "7200"=>"120 Minute");
?>

<div class="warper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Add Vehicle</h1>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Add Vehicle Details</span></div>
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
                    <form method="post" action="<?php  echo site_url('Vehicles/addVehicle');?>" enctype="multipart/form-data">
                        <div class="col-lg-6">
                            <div class="form-group">
                                
                                <label class="control-label">Brand Name<i class="reustarred">*</i></label>
                                <input class="form-control" name="brand" id="brand" placeholder="Enter Brand Name"  type="text" value="<?php echo (set_value('brand'))?set_value('brand'):""; ?>">
                                <div class="color-red"><?php echo form_error('brand'); ?></div>
                                  
                            </div>
                            <div class="form-group">
                        
                                <label class="control-label">Model Name<i class="reustarred">*</i></label>
                                <input class="form-control" name="model" id="model" placeholder="Enter Model Name"  type="text" value="<?php echo (set_value('model'))?set_value('model'):""; ?>">
                                <div class="color-red"><?php echo form_error('model'); ?></div>
                                    
                            </div>
                            <div class="form-group">
                                <label class="control-label">Number Plate<i class="reustarred">*</i></label>
                                <input class="form-control" name="no_plate" id="no_plate" placeholder="Enter Number Plate"  type="text" value="<?php echo (set_value('no_plate'))?set_value('no_plate'):""; ?>">
                                <div class="color-red"><?php echo form_error('no_plate'); ?></div>
                                   
                            </div>
                            <div class="form-group ">
                                <label class="control-label">Restaurant Branch <i class="reustarred">*</i></label>
                                <select class="form-control chosen-select" name="restaurant_id" id="branch">
                                    <?php if($userdata[0]->role_id ==$this->admin_Role){?>
                                        <option value="" >Select Restaurant Branch</option>
                                    <?php  }                                             foreach ($restaurants as $key => $value){ ?>
                                                <option  value="<?php echo $value->restaurant_id ?>" <?= ( $value->restaurant_id ==set_value('restaurant_id'))?'selected':''; ?>><?php echo $value->restaurant_name; ?>
                                            </option>
                                    <?php } ?> 
                                </select>
                                <div class="color-red"><?php echo form_error('restaurant_id'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                             <div class="form-group">
                               
                               
                                <label >Purchase Date<i class="reustarred">*</i></label>
                              
                                    <input type="text" class="form-control"  placeholder="Enter Purchase Date" name="purchase_date" value="<?php echo (set_value('purchase_date'))?set_value('purchase_date'):""; ?>" id="datepicker">
                                <div class="color-red"><?php echo form_error('purchase_date'); ?></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Vendor</label>
                                <input class="form-control" name="vendor" id="vendor" placeholder="Enter vendor"  type="text" value="<?php echo (set_value('vendor'))?set_value('vendor'):""; ?>">
                            </div>
                             <div class="form-group">
                                <label class="control-label">Image<i class="reustarred">*</i></label>
                                <input class="form-control" name="image" id="image" type="file">
                            
                                <div class="color-red"><?php if(isset($image_error)){echo $image_error;} ?></div>
                            </div>
                            <div class="form-group">
                                
                                <label class="control-label pdn-lbl">Description<i class="reustarred">*</i></label>
                                <textarea class="form-control" name="description" id="description" rows="5" placeholder="Enter Description"><?php echo (set_value('description'))?set_value('description'):""; ?></textarea>
                                <div class="color-red"><?php echo form_error('description'); ?></div>
                                    
                            </div>
                           
                           
                        
                        </div>
                        <hr class="dotted">
                         <div class="col-lg-12 col-md-12 col-sm-12"> 
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                     <input type="submit" class="btn btn-primary" name="add" value="Save">
                                       <a href="<?php echo site_url('Vehicles/index'); ?>" type="button" class="btn btn-primary ">Cancel</a>
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
    $(document).ready(function(){
        $('#datepicker').datepicker({
            autoclose: true,
            changeYear: true,
            endDate: '+0d'
         });
    });
</script>