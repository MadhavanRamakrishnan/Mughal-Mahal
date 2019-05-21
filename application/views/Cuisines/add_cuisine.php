
<div class="warper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Add Cuisine</h1>
            </div>
        </div>
    </div>
    <?php 
        if($this->session->flashdata() != ""){

            if($this->session->flashdata('error_msg') != ""){ ?>
                <div class="alert alert-danger alert-dismissible" role="alert" id="flashmessages">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <?php echo $this->session->flashdata('error_msg'); ?> 
                </div>
            <?php }
            else if($this->session->flashdata('success_msg') != ""){ ?>
                <div class="alert alert-success alert-dismissible" role="alert" id="flashmessages">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <?php echo $this->session->flashdata('success_msg'); ?>
                </div>
             <?php   }
        }?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Add Cuisine Details</span>s</div>
                <div class="panel-body">
                    <form method="post" action="<?php  echo site_url('Cuisines/addCuisine');?>" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="control-label">Cuisine Name<i class="reustarred">*</i></label>
                                    <input class="form-control" name="cuisine_name" id="cuisine_name" placeholder="Enter Cuisine Name"  type="text" value="<?php echo (set_value('cuisine_name'))?set_value('cuisine_name'):""; ?>">
                                    <div class="color-red"><?php echo form_error('cuisine_name'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Description<i class="reustarred">*</i></label>
                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter Description"><?php echo (set_value('description'))?set_value('description'):""; ?></textarea>
                            <div class="color-red"><?php echo form_error('description'); ?></div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="control-label">Cuisine Image</label>
                                    <input class="" name="image" id="image" type="file">
                                
                                </div>
                            </div>
                            <?php if(isset($image_error)){echo $image_error;} ?>
                           
                        </div>
                            <hr class="dotted">
                        <div class="form-group">
                             <input type="submit" class="btn btn-primary" name="add" value="Save">
                              <a href="<?php echo site_url('Cuisines'); ?>" type="button" class="btn btn-primary ">Cancel</a>
                        </div>
                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

