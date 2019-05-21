   <!-- <?php echo"hi"; echo $image_error; //exit;?> -->
 <div class="warper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Edit Cuisine</h1>
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
                    <span class="nav-label">Edit Cuisine Details</span>s</div>
                <div class="panel-body">
                    <form method="post" action="<?php  echo site_url('Cuisines/editCuisine/'.$cuisine_data[0]->cuisine_id);?>" enctype="multipart/form-data">
                        <div class="form-group has-feedback">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="control-label">Cuisine Name<i class="reustarred">*</i></label>
                                    <input class="form-control" name="cuisine_name" id="cuisine_name" placeholder="Enter Cuisine Name"  type="text" value="<?php echo $cuisine_data[0]->cuisine_name; ?>">
                                    <div class="color-red"><?php echo form_error('cuisine_name'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback">
                            <label class="control-label">Description<i class="reustarred">*</i></label>
                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter Description"><?php echo $cuisine_data[0]->description; ?></textarea>
                            <div class="color-red"><?php echo form_error('description'); ?></div>
                        </div>

                        <div class="form-group has-feedback">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="control-label">Cuisine Image</label>
                                    <input class="" name="image" id="image" type="file">
                              
                                </div>
                                
                                <div class="col-lg-6 m-t-30" >
                                        <img src="<?php echo base_url().'assets/uploads/cuisines/'.$cuisine_data[0]->image; ?>" class="img-responsive" id="changeimages" style="text-align: center;margin: 0 auto;width: 40%">
                                </div>
                                 <?php if(isset($image_error)){echo $image_error;} ?>
                                
                            </div>

                        </div>
                            <hr class="dotted">
                        <div class="form-group">
                            <input type="hidden" name="cuisine_id" value="<?php echo $cuisine_data[0]->cuisine_id; ?>">
                             <input type="submit" class="btn btn-primary" name="update" value="Update">
                             <a href="<?php echo site_url('Cuisines'); ?>" type="button" class="btn btn-primary ">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

