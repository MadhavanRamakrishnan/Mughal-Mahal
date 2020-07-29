
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
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="control-label">Dish Category<i class="reustarred">*</i></label>
                                    <input class="form-control" name="category_name" id="category_name" placeholder="Enter Dish Category Name"  type="text" value="<?php echo (set_value('category_name'))?set_value('category_name'):""; ?>">
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
                            </div>
                            <?php if(isset($image_error)){echo $image_error;} ?>
                            
                        </div>
                        <span class="text-danger"><i class="reustarred">*</i>&nbsp; This category will be added as last in the priority be default. Please update priority from Dish Category listing page.</span>
                        <hr class="dotted">
                        <div class="form-group">
                           <input type="submit" class="btn btn-primary" name="add" value="Save">
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