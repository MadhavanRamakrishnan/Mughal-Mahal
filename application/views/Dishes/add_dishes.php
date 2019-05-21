
<div class="warper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Add Dish Category</h1>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Add Dish Category Details</span></div>
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
                    <form method="post" action="<?php  echo site_url('Category/addCategory');?>" enctype="multipart/form-data">
                        <div class="form-group has-feedback">
                            <div class="form-group has-feedback">
                                <label class="control-label">Cuisines<i class="reustarred">*</i> </label>
                                 <select class="form-control" name="cuisine_id" id="cuisine_id">
                                 <?php 
                                    if(set_value('cuisine_id')){$a=set_value('cuisine_id');}
                                    else {$a="";} ?>
                                    <option value="">Select Cuisine</option><?php 
                                        foreach ($cuisineList as $key => $value){ ?>
                                            <option  value="<?php echo $value->cuisine_id ?>" <?php echo ($a == $value->cuisine_id)?"selected":""; ?>><?php echo $value->cuisine_name; ?>
                                            </option><?php 
                                        } ?> 
                                </select>
                            </div> 
                             <div class="color-red"><?php echo form_error('cuisine_id'); ?></div>
                        </div>
                        <div class="form-group has-feedback">
                            <div class="form-group has-feedback">
                                <label class="control-label">Dish Category<i class="reustarred">*</i> </label>
                                 <select class="form-control" name="cuisine_id" id="cuisine_id">
                                 <?php 
                                    if(set_value('cuisine_id')){$a=set_value('cuisine_id');}
                                    else {$a="";} ?>
                                    <option value="">Select Cuisine</option><?php 
                                        foreach ($cuisineList as $key => $value){ ?>
                                            <option  value="<?php echo $value->cuisine_id ?>" <?php echo ($a == $value->cuisine_id)?"selected":""; ?>><?php echo $value->cuisine_name; ?>
                                            </option><?php 
                                        } ?> 
                                </select>
                            </div> 
                             <div class="color-red"><?php echo form_error('cuisine_id'); ?></div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="control-label">Dish Name<i class="reustarred">*</i></label>
                                    <input class="form-control" name="dish_name" id="dish_name" placeholder="Add Dish Name"  type="text" value="<?php echo (set_value('dish_name'))?set_value('dish_name'):""; ?>">
                                    <div class="color-red"><?php echo form_error('dish_name'); ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label">Description<i class="reustarred">*</i></label>
                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Add Description"><?php echo (set_value('description'))?set_value('description'):""; ?></textarea>
                            <div class="color-red"><?php echo form_error('description'); ?></div>
                        </div>

                         <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="control-label">Dish Price<i class="reustarred">*</i></label>
                                    <input class="form-control" name="price" id="price" placeholder="Add Dish Price"  type="text" value="<?php echo (set_value('price'))?set_value('price'):""; ?>">
                                    <div class="color-red"><?php echo form_error('price'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback">
                            <div class="form-group has-feedback">
                                <label class="control-label">Dish Category<i class="reustarred">*</i> </label>
                                 <select class="form-control" name="cuisine_id" id="cuisine_id">
                                 <?php 
                                    if(set_value('cuisine_id')){$a=set_value('cuisine_id');}
                                    else {$a="";} ?>
                                    <option value="">Select Cuisine</option><?php 
                                        foreach ($cuisineList as $key => $value){ ?>
                                            <option  value="<?php echo $value->cuisine_id ?>" <?php echo ($a == $value->cuisine_id)?"selected":""; ?>><?php echo $value->cuisine_name; ?>
                                            </option><?php 
                                        } ?> 
                                </select>
                            </div> 
                             <div class="color-red"><?php echo form_error('cuisine_id'); ?></div>
                        </div>
                          <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="control-label">Discount<i class="reustarred">*</i></label>
                                    <input class="form-control" name="price" id="price" placeholder="Add Dish Price"  type="text" value="<?php echo (set_value('price'))?set_value('price'):""; ?>">
                                    <div class="color-red"><?php echo form_error('price'); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="control-label">Image</label>
                                    <input class="" name="image" id="image" type="file">
                                
                                </div>
                            </div>
                            <?php if(isset($image_error)){echo $image_error;} ?>
                           
                        </div>
                            <hr class="dotted">
                        <div class="form-group">
                             <input type="submit" class="btn btn-primary" name="add" value="Save">
                              <a href="<?php echo site_url('Category/'); ?>" type="button" class="btn btn-primary ">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(".alert").alert();
window.setTimeout(function() { $(".alert").alert('close'); }, 3000);
</script>