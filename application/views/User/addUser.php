<div class="warper container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Admin</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Add Admin, Angel And Survivor</div>
                <div class="panel-body">
                    <form method="post" action="<?php echo site_url('User/saveAddUser'); ?>" enctype="multipart/form-data">
                    <div class="form-group has-feedback">
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="control-label">Role <i class="reustarred">*</i></label>
                                <select class="form-control" name="role" data-bv-field="role">
                                    <option value="">-- Select a Role --</option>
                                    <?php
                                        foreach ($userRole as $key => $value) {   
                                            $s = "";
                                            if(isset($post['role']) && $post['role'] != ""){
                                                if($post['role'] == $value->role_id){
                                                    $s = "selected='selected'";
                                                }
                                            } 
                                        echo "<option value='".$value->role_id."' $s>".$value->role_name."</option>";
                                        }
                                    ?>
                                </select>
                                <div class="form-group input-group"><div class="color-red"><?php echo form_error('role'); ?></div></div>
                            </div>

                            <div class="col-lg-6">
                                <label class="control-label">User Name <i class="reustarred">*</i></label>
                                <input class="form-control" name="username" id="username" placeholder="User Name" data-bv-field="username" type="text" value="<?php if(isset($post['username']) && $post['username'] != ""){ echo $post['username'];} ?>">
                                <div class="form-group input-group"><div class="color-red"><?php echo form_error('username'); ?></div></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback">
                        <label class="control-label">Email <i class="reustarred">*</i></label>
                        <input class="form-control" name="email" id="email" data-bv-field="email" type="text" placeholder="Email" value="<?php if(isset($post['email']) && $post['email'] != ""){ echo $post['email'];} ?>">
                        <div class="form-group input-group"><div class="color-red"><?php echo form_error('email'); ?></div></div>
                    </div>

                    <div class="form-group has-feedback">
                        <label class="control-label">Contact Number <i class="reustarred">*</i></label>
                        <input class="form-control" name="contact_no" id="contact_no" data-bv-field="contact_no" type="text" placeholder="Contact Number" value="<?php if(isset($post['contact_no']) && $post['contact_no'] != ""){ echo $post['contact_no'];} ?>">
                        <div class="form-group input-group"><div class="color-red"><?php echo form_error('contact_no'); ?></div></div>
                    </div>

                    
                    <div class="form-group has-feedback">
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="control-label">Date of Birth</label>
                                <input class="form-control" name="dob" id="datepicker" placeholder="Date of Birth" data-bv-field="dob" type="text" value="<?php if(isset($post['dob']) && $post['dob'] != ""){ echo $post['dob'];} ?>">
                            </div>

                            <div class="col-lg-6">
                                <label class="control-label">Gender</label>
                                <select class="form-control" name="gender" data-bv-field="gender">
                                    <option value="">-- Select a Gender --</option>
                                    <option value="1" <?php if(isset($post['gender']) && $post['gender'] == '1'){ echo "selected";} ?> >Male</option>
                                    <option value="2" <?php if(isset($post['gender']) && $post['gender'] == '2'){ echo "selected";} ?>>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback">
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="control-label">Photo</label>
                                <input class="" name="photo" id="photo" type="file">
                                <?php
                                 if(isset($error)){?>
                                    <div class="form-group input-group"><div class="color-red"><?php echo $error;?></div></div>
                                <?php  }
                                 ?>
                            </div>
                            <div class="col-lg-6 m-t-30" style="display: none;">
                                <img src="<?php echo base_url().'assets/images/userPhoto/not_img.png'; ?>" class="img-responsive" id="changeimages" style="text-align: center;margin: 0 auto;width: 40%">
                            </div>
                        </div>
                    </div>
                    
                    <hr class="dotted">
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" name="signup" value="signup">Save</button>
                        <a href="<?php echo site_url('User/helperUsers'); ?>"><button type="button" class="btn btn-info" id="">cancel</button></a>
                    </div>
                    </form>
                
                
                </div>
            </div>
        </div>
    </div>
</div>


<!-- <script type="text/javascript">
    var deleteUser              = "<?php //echo site_url('User/deleteUser'); ?>";
    var edituserdata            = "<?php //echo site_url('User/editUserForm'); ?>";
    var updateUserData          = "<?php //echo site_url('User/updateUserData'); ?>";
</script>-->
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/pages/useroperation.js"></script> 