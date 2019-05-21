
<div class="warper container-fluid">
<div class="row">
    <div class="col-md-12">
        <div class="page-header f-left">
            <h1>Update Choice </h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-newspaper-o"></i> 
                <span class="nav-label">Update Choice Details</span></div>
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
                <form method="post" action="<?php  echo site_url('Choice/editChoices/'.$choicedata[0]->choice_id);?>" enctype="multipart/form-data">
                  <div id="clonecontainer"> 
                    <div id="clonedInput1" class="clonedInput">
                        <div class="form-group">
                            <label class="control-label cat_name">Choice Category<i class="reustarred">*</i></label>
                            <select class="form-control" name="cat_name" id="cat_name">
                            <?php 
                            if(set_value('cat_name')){$catId=set_value('cat_name');}
                            else {$catId="";} ?>
                            <option value="">Select Category Type</option>
                                <?php   //echo "<pre>";print_r($categoryList);exit;
                                foreach ($categoryList as $key => $value){ 

                                    ?>

                                    <option  value="<?php echo $value->choice_category_id ?>" <?php echo ($catId == $value->choice_category_id)?"selected":$choicedata[0]->fk_choice_category_id; ?>><?php echo $value->choice_category_name; ?>
                                    </option><?php 
                                } ?> 
                            </select>
                            <div class="color-red"><?php echo form_error('cat_name'); ?></div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="control-label">Choice Name<i class="reustarred">*</i></label>
                                    <input class="form-control choice_name" name="choice_name" id="choice_name" placeholder="Enter Choice Name"  type="text" value="<?php echo (set_value('choice_name'))?set_value('choice_name'):$choicedata[0]->choice_name; ?>">
                                    <div class="color-red"><?php echo form_error('choice_name'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Description</label>
                            <textarea class="form-control description" name="description" id="description" rows="3" placeholder="Enter Description"><?php echo (set_value('description'))?set_value('description'):$choicedata[0]->choice_description; ?></textarea>
                            <div class="color-red"><?php echo form_error('description'); ?></div>
                        </div>
                       
                          
                        <hr class="dotted dottd">
                        
                    </div>
                </div>
                        <div class="form-group">
                          
                            <input type="submit" class="btn btn-primary" name="Update" value="Update">
                           
                                
                            <a href="<?php echo site_url('Choice/choicesList'); ?>" type="button" class="btn btn-primary ">Cancel</a>

                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
// $(".alert").alert();
// window.setTimeout(function() { $(".alert").alert('close'); }, 3000);
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/choice.js"></script>
