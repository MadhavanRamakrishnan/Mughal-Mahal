
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<style type="text/css">
    table thead tr th, .center{
      text-align: center;
    }
    #basic-datatable_wrapper{ padding-top: 50px;}
</style>
<div class="warper container-fluid">
    

    <div class="page-header">
        <h1 class="pageTitle" style="margin: 0;">Drivers</h1>
    </div>
    
    <div class="row">
        <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
          <?php  $successMsg=$this->session->flashdata('success_msg'); ?>
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
        <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
             <div class="page-header f-right m-b-10">
               <a class="addButtons" href="<?php echo site_url('Drivers/addDrivers'); ?>"><button type="button" class="btn btn-success">Add Driver</button></a> 
           </div>
       </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading">
          <i class="fa fa-car" aria-hidden="true"></i>
          <span class="nav-label" >List of Drivers</span>
        </div>  
        <div class="panel-body">            
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Branch</th>
                        <th>Vehicle</th>
                        <th>Vendor</th>
                        <th>Password</th>
                        <th>Status</th>
                        <th>Action</th>
                        
                    </tr>
                </thead>
                <tbody>
                   <?php if(is_array($drivers) && count($drivers)>0){
                      foreach($drivers as $key => $value){
                        $ImgLoc1="./assets/uploads/users/drivers/".$value->profile_photo;
                        if(file_exists($ImgLoc1) && !empty($value->profile_photo)){
                            $categoryImage = $value->profile_photo;
                        } else {
                            $categoryImage = 'no_image.png';
                        }
                        ?>
                        <tr id="driver_details_<?php echo $value->user_id; ?>">

                            <td><?php echo $value->first_name.' '.$value->last_name; ?></td>
                            <td><?php echo $value->email; ?></td>
                            <td class="center"> (+965) <?php echo $value->contact_no; ?></td>
                            <td class="center"><?php echo ($value->restaurant_name)?$value->restaurant_name:'N/A'; ?></td>
                            <td class="center" id="vehicleAssignName_<?= $value->user_id; ?>"><?php echo $value->brand.' '.$value->model; ?></td>
                            <td class="center"><?php echo $value->vendor; ?></td>
                            <td class="center"><?php echo $value->driver_password; ?></td>
                            <td class="center">
                              <?php if($value->is_active==1){
                                 echo "<span class='label label-success'>Active</span>";
                             } else {
                                 echo "<span class='label label-danger'>Inactive</span>";
                             }
                             ?>
                            </td>
                         

                         <td class="center"><a href="<?php echo site_url('Drivers/editDrivers/'.$value->user_id); ?>"><i class="fa fa-edit"> </i></a> |<a title="Delete" data-toggle="modal" data-target="#confirmationModal" data-backdrop="static" data-keyboard="false" onclick="deleteDriverDetail(<?php echo $value->user_id; ?>)"  id="delete"><i class="fa fa-trash"> </i></a>  |
                            <a class="changeVehicle changeVehicle_<?php echo $value->user_id; ?>" data-toggle="modal" data-target="#drvRating" title="<?php if($value->user_id){ echo'Change Vehicle'; } else { echo 'Assign Vehicle'; }?>" oid="<?php echo $value->user_id; ?>" rid="<?php echo $value->rid; ?>" style="display:<?php echo ($value->user_id>3)?'inline-block':'none'; ?>">
                                <i class="fa fa-user" ></i>
                            </a>
                        </td>                    
                     
                         
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="drvRating" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Change/Assign Vehicle</h4>
            </div>
            <div class="modal-body">
                <form role="form" method="POST">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Choose Vehicle</label>
                        <select class="form-control" name="vehicle" id="vehicle">
                            
                        </select>
                        <span style="color: red; display: none;" id="errDriver">Please choose any vehicles for vehicle</span>
                        <input type="hidden" name="hdn_oid" id="hdn_oid" value="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="assign">Assign</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var deleteDriverDetailUrl    = "<?php echo site_url('Drivers/deleteDriverDetail')?>";
    var countryId              = ""; 
    var stateId                = ""; 
    var cityId                 = ""; 
    var changeOrderStatus        = "<?php echo site_url('Orders/changeOrderStatus')?>";
    var deleteOrderDetailUrl     = "<?php echo site_url('Orders/deleteOrderDetails')?>";
    var getVehicle               = "<?php echo site_url('Drivers/getallVehicle')?>";
    var changeVehicle            = "<?php echo site_url('Drivers/changeVehicle')?>";
    var changeDriverAndOrderStatus = "<?php echo site_url('Orders/changeDriverAndOrderStatus')?>";
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/driver.js"></script>