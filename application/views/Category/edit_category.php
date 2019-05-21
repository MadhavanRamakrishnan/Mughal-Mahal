
<div class="warper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Update Dish Category</h1>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Update Dish Category Details</span></div>
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
                    <form method="post" action="<?php  echo site_url('Category/editCategory/'.$category_data[0]->category_id);?>" enctype="multipart/form-data">
                      
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="control-label">Dish Category<i class="reustarred">*</i></label>
                                    <input class="form-control" name="category_name" id="category_name" placeholder="Enter Dish Category Name"  type="text" value="<?php echo (set_value('category_name'))?set_value('category_name'):$category_data[0]->category_name; ?>">
                                    <div class="color-red"><?php echo form_error('category_name'); ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="control-label">Image</label>
                                    <input class="" name="image" id="image" type="file">
                                </div>
                                <div class="col-lg-6 m-t-30" >
                                    <?php
                                    $ImgLoc="./assets/uploads/category/".$category_data[0]->image; 
                                    ?>
                                    <div class="thumbnail edit-thumbnail">
                                        <?php  if(file_exists($ImgLoc) && !empty($category_data[0]->image)){  ?>
                                        <img src="<?php echo base_url().'assets/uploads/category/'.$category_data[0]->image; ?>" class="img-responsive" id="changeimages" style="text-align: center;margin: 0 auto;">
                                        <?php }else{ ?>
                                        <div class="thumbnail edit-thumbnail">
                                            <img src="<?php echo base_url().'assets/uploads/category/no_image.png'; ?>" class="img-responsive" id="changeimages" style="text-align: center;margin: 0 auto;">
                                            <?php  } ?>
                                        </div>
                                    </div>
                                    <?php if(isset($image_error)){echo $image_error;} ?>

                                </div>
                                
                            </div>
                            <hr class="dotted">
                            <div class="form-group">
                                <input type="hidden" name="category_id" value="<?php echo $category_data[0]->category_id;?>">
                                <input type="submit" class="btn btn-primary" name="update" value="Update">
                                <a href="<?php echo site_url('Category/dishCategory'); ?>" type="button" class="btn btn-primary ">Cancel</a>
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