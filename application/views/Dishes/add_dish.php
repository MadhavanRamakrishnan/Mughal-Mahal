<style type="text/css">
    .lt_align{
        text-align:left;
    }
    .dish_choices1 {
    box-sizing: border-box;
    width: 100%;
    border: 1px solid #ddd;

}
</style>
<div class="warper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Add Dish</h1>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Add Dish Details</span></div>
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
                    <form method="post" action="<?php  echo site_url('Dishes/addDishDetail');?>" enctype="multipart/form-data">
                        <div class="form-group has-feedback">
                            <div class="form-group has-feedback">
                                <label class="control-label">Dish Category<i class="reustarred">*</i> </label>
                                 <select class="form-control" name="category_id" id="category_id">
                                 <?php 
                                    if(set_value('category_id')){$catId=set_value('category_id');}
                                    else {$catId="";} ?>
                                    <option value="">Select Dish Category</option>
                                    <?php 
                                        foreach ($categoryList as $key => $value){ ?>
                                            <option  value="<?php echo $value->category_id ?>" <?php echo ($catId == $value->category_id)?"selected":""; ?>><?php echo $value->category_name; ?>
                                            </option><?php 
                                        } ?> 
                                </select>
                            </div> 
                             <div class="color-red"><?php echo form_error('category_id'); ?></div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="control-label">Dish Name<i class="reustarred">*</i></label>
                                    <input class="form-control" name="dish_name" id="dish_name" placeholder="Enter Dish Name"  type="text" value="<?php echo (set_value('dish_name'))?set_value('dish_name'):""; ?>">
                                    <div class="color-red"><?php echo form_error('dish_name'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="control-label">Dish Name(Arabic)<i class="reustarred">*</i></label>
                                    <input class="form-control" name="ar_dish_name" id="ar_dish_name" placeholder="Enter Dish Name in arabic"  type="text" value="<?php echo (set_value('ar_dish_name'))?set_value('ar_dish_name'):""; ?>">
                                    <div class="color-red"><?php echo form_error('ar_dish_name'); ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter Description"><?php echo (set_value('description'))?set_value('description'):""; ?></textarea>
                            <div class="color-red"><?php echo form_error('description'); ?></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Description (Arabic)</label>
                            <textarea class="form-control" name="ar_description" id="ar_description" rows="3" placeholder="Enter Description in arabic"><?php echo (set_value('ar_description'))?set_value('ar_description'):""; ?></textarea>
                            <div class="color-red"><?php echo form_error('ar_description'); ?></div>
                        </div>
                    
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="control-label">Banner Image</label>
                                    <input class="" name="image" id="image" type="file">
                                </div>
                            </div>
                            <?php if(isset($image_error)){echo "<span style='color : red;'>".$image_error."</span>";} ?>
                           
                        </div>
                  
                        <hr class="dotted">
                        <div class="form-group">
                             <input type="submit" class="btn btn-primary" name="add" value="Save">
                              <a href="<?php echo site_url('Dishes/dishList'); ?>" type="button" class="btn btn-primary ">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
