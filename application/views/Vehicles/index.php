<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<style type="text/css">
  table thead tr th, .center{
    text-align: center;
  }
  #basic-datatable_wrapper{ padding-top: 50px;}
</style>
<div class="warper container-fluid">
  <div class="page-header">
    <h1 class="pageTitle">Vehicles </h1>
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
          <a class="addButtons" href="<?php echo site_url('Vehicles/addVehicle'); ?>"><button type="button" class="btn btn-success">Add Vehicle</button></a>
        </div>
    </div>
  </div>
  <div class="panel panel-success">
      <div class="panel-heading">
          <i class="fa fa-car" aria-hidden="true"></i>
          <span class="nav-label" >List of Vehicles</span>
      </div>  
      <div class="panel-body">  
          <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable">
              <thead>
                  <tr>
                      <th>Image</th>
                      <th>Brand</th>
                      <th>Model</th>
                      <th>Restaurent</th>
                      <th>Description</th>
                      <th>Number Plat</th>
                      <th>Vendor</th>
                      <th>Purchase Date</th>
                      <th>Action</th>
                      
                  </tr>
              </thead>
              <tbody>
                <?php if(is_array($vehicles) && count($vehicles)>0){
                  foreach($vehicles as $key => $value){
                          $ImgLoc1="./assets/uploads/vehicles/".$value->image;
                       
                          if(file_exists($ImgLoc1)){

                              $photo = $value->image;
                          }
                          else{
                              $photo = 'no_image.png';
                          } ?>
                       
                 <tr id="vehicle_details_<?php echo $value->vehicle_id; ?>">

                      <td align="center">
                        <div class="img-height">
                          <img src="<?php echo base_url()."assets/uploads/vehicles/".$photo; ?>">
                        </div>
                      </td>
                      <td align="center"><?php echo ($value->brand)?$value->brand:'N/A'; ?></td>
                      <td align="center"><?php echo ($value->model)?$value->model:'N/A'; ?></td>
                      <td align="center"><?php echo ($value->restaurant_name)?$value->restaurant_name:'N/A'; ?></td>
                      <td align="center"><?php echo ($value->description)?$value->description:'N/A'; ?></td>
                      <td align="center"><?php echo ($value->number_plate)?$value->number_plate:'N/A'; ?></td>
                      <td class="center" ><?php echo $value->vendor; ?></td>
                      <td align="center"><?php echo ($value->purchase_date)?$value->purchase_date:'N/A'; ?></td>
                      <td class="center">
                          <a href="<?php echo site_url('Vehicles/editVehicle/'.$value->vehicle_id); ?>">
                            <i class="fa fa-edit"> </i>
                          </a> 
                          |
                          <a title="Delete" data-toggle="modal" data-target="#confirmationModal" data-backdrop="static" data-keyboard="false" onclick="deleteVehicleDetail(<?php echo $value->vehicle_id; ?>)"  id="delete">
                            <i class="fa fa-trash"> </i>
                          </a>
                      </td>
                  </tr>
                  <?php } }?>
              </tbody>
          </table>
      </div>
  </div>
</div>
<script type="text/javascript">
  
function deleteVehicleDetail(id){
       
  $('#deleteMsg').text('Are you sure to delete Driver details?');
  $("#delete_btn").unbind().click(function(){

     $.ajax({
        url     : '<?php echo site_url('Vehicles/deleteVehicleDetail')?>',
        type    : "POST",
        data    :  {vehicle_id:id},

        success : function(response){
                    var obj = JSON.parse(response);

                    if(obj.success==1)  {
                      $('#confirmationModal').modal('hide');
                      $("#vehicle_details_"+id).remove();

                       $("#success_message").text(obj.message);
                            $("#success_notification").show();
                            setTimeout(function(){ $("#success_notification").hide(); },5000);
                          }
                          else{
                            $("#error_message").text(obj.message);
                            $("#flasherror").show();
                            setTimeout(function(){ $("#error_notification").hide(); },5000);

                          }
                        }
            });
        });
    }

//$(".alert").alert();
//window.setTimeout(function() { $(".alert").alert('close'); }, 5000);

setTimeout(function(){ $("#success_notification").hide(); },5000);
setTimeout(function(){ $("#error_notification").hide(); },5000);
</script>