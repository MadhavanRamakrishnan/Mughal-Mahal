

<div class="warper container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Customers</h1>
            </div>
           <!--  <div class="page-header f-right m-t-10">
                <a href="<?php echo site_url('User/addHelpersUser'); ?>" type="button" class="btn btn-primary ">Customer List</a>
            </div> -->
        </div>
    </div>

    <?php 
        if($this->session->flashdata() != ""){
            if($this->session->flashdata('error') != ""){ 
    ?>
                <div class="alert alert-danger alert-dismissible" role="alert" id="flashmessages">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <?php echo $this->session->flashdata('error'); ?> 
                </div>
    <?php
            }
            else if($this->session->flashdata('success') != ""){
    ?>
                <div class="alert alert-success alert-dismissible" role="alert" id="flashmessages">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
    <?php
            }
            else{

            }
        }
    ?>
    
    <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
                <div class="panel-heading">Customer List</div>

                <div class="panel-body table-responsive">
                    <div class="row m-t-10 m-b-10">
                        <div class="col-sm-6 col-lg-4 col-lg-offset-8">
                        
                        <form action="<?php echo site_url('User/searchCustomer');?>" method="POST">
	                            <div class="input-group">
	                              <input class="form-control input-sm  form-control-flat" placeholder="Search record here..." type="text" name="Search" value="<?php if(isset($searchUser)){
														echo $searchUser;
									}
									 ?>">
	                              <span class="input-group-btn">
	                                <button class="btn btn-default btn-sm btn-flat" type="submit" name='go' value="go">Go!</button>
	                              </span>
	                            </div>
                            </form>
                        </div>
                    </div>
                    <table class="table table-striped no-margn">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th>User Role</th>
                                <th>Profile</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if(count($listuser)<1){
                            ?>
                                <tr><td colspan="6" ><div align="center">No Customer Data</div></td></tr>
                            <?php    }else{
                                foreach ($listuser as $key => $value) {
                                    if($value->photo != ""){
                                        if($value->register_via == "0"){
                                            $photos = base_url()."assets/uploads/users/".$value->photo;
                                        }
                                        else{
                                            $photos = $value->photo;
                                        }
                                    }
                                    else{
                                        $photos = base_url()."assets/images/userPhoto/not_img.png";
                                    }
                            ?>
                            <tr>
                                <td><img alt="user" class="media-object" src="<?php echo $photos; ?>"></td>
                                <td><?php echo $value->user_name; ?></td>
                                <td><?php echo $value->email; ?></td>
                                <td><?php echo $value->contact_no; ?></td>
                                <td><?php echo $value->role_name; ?></td>
                                <td>
                                    <a href="<?php echo site_url('User/userProfile/'.$value->user_id); ?>" >
                                        <button type="button" class="btn btn-success fileinput-button">
                                            <!-- <i class="glyphicon glyphicon-new-window"></i> -->
                                            <span>View</span>
                                        </button>
                                    </a>
                                </td>
                                    
                                </tr>

                                

                               <!--  <td>
                                   <a href="<?php echo site_url('User/userProfile/'.$value->user_id); ?>" >
                                        <button type="button" class="btn btn-success fileinput-button">
                                            
                                            <span>View</span>
                                        </button>
                                    </a>

                                    <a href="<?php echo site_url('User/editUser/'.$value->user_id); ?>" >
                                    <button type="button" class="btn btn-warning ">
                                        
                                        <span>Edit</span>
                                    </button>

                                    <a href="<?php echo site_url('User/userDelete/').$value->user_id; ?>" onclick="return confirm('Are you sure you want to permanently delete this user?');" ><button type="button" class="btn btn-danger delete">
                                        <span>Delete</span>
                                    </button>

                                </td> -->
                            </tr>
                            <?php 
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-12"> 
                            <ul class="pagination showcase-pagination pull-right">
                              <?php foreach ($links as $link) {
                                    echo "<li>".$link."</li>";
                                  } ?>
                                
                            </ul>
                        </div>
                    </div>
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