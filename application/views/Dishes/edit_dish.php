
<div class="warper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Update Dish Details</h1>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Update Dish Details</span></div>
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
                    <form method="post" action="<?php  echo site_url('Dishes/editDishDetail/'.$dish_data[0]->product_id);?>" enctype="multipart/form-data">
                       
                        <div class="form-group has-feedback">
                            <div class="form-group has-feedback">
                                <label class="control-label">Dish Category<i class="reustarred">*</i> </label>
                                 <select class="form-control" name="category_id" id="category_id">
                                    <?php  
                                    
                                    if (set_value('category_id')) {
                                        $catId=set_value('category_id');
                                    }

                                    else if($dish_data[0]->category_id){
                                        $catId=$dish_data[0]->category_id;
                                    }
                                    else{
                                        $catId="";
                                    } ?>
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
                                    <input class="form-control" name="dish_name" id="dish_name" placeholder="Enter Dish Name"  type="text" value="<?php echo (set_value('dish_name'))?set_value('dish_name'):stripslashes($dish_data[0]->product_en_name); ?>">
                                    <div class="color-red"><?php echo form_error('dish_name'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="control-label">Dish Name(Arabic)<i class="reustarred">*</i></label>
                                    <input class="form-control" name="product_ar_name" id="product_ar_name" placeholder="Enter Dish Name in arabic"  type="text" value="<?php echo (set_value('product_ar_name'))?set_value('product_ar_name'):$dish_data[0]->product_ar_name; ?>">
                                    <div class="color-red"><?php echo form_error('product_ar_name'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter Description"><?php echo (set_value('description'))?set_value('description'):$dish_data[0]->en_description; ?></textarea>
                            <div class="color-red"><?php echo form_error('description'); ?></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Description (Arabic)</label>
                            <textarea class="form-control" name="ar_description" id="ar_description" rows="3" placeholder="Enter Description in arabic"><?php echo (set_value('ar_description'))?set_value('ar_description'):$dish_data[0]->ar_description; ?></textarea>
                            <div class="color-red"><?php echo form_error('ar_description'); ?></div>
                        </div>
                        

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="control-label">Banner Image</label>
                                    <input class="" name="image" id="image" type="file">
                                </div>
                                <div class="col-lg-6 m-t-30" >
                                    <div class="thumbnail edit-thumbnail">
                                        <img src="<?php echo base_url().'assets/images/front-endold/dishes/'.$dish_data[0]->dish_image; ?>" class="img-responsive" id="changeimages" style="text-align: center;margin: 0 auto;">
                                    </div>
                                </div>
                            </div>
                            <?php if(isset($image_error)){echo $image_error;} ?>
                           
                        </div>
                         
                            <hr class="dotted">
                        <div class="form-group">
                            <input type="hidden" name="product_id" value="<?php echo $dish_data[0]->product_id;?>">
                             <input type="submit" class="btn btn-primary" name="update" value="Update">
                              <a href="<?php echo site_url('Dishes/dishList'); ?>" type="button" class="btn btn-primary ">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dishdetail.js"></script>