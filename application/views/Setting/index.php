

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<style type="text/css">
	table thead tr th, .center{
		text-align: center;
	}
</style>
<div class="warper container-fluid">
	<div class="page-header">
		<h1 class="pageTitle">Restaurants <small>List of Branches</small></h1>
	</div>
    <div class="row">
      <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
            <div class="page-header f-right m-b-10">
             <a class="addButtons" href="<?php echo site_url('Restaurants/addRestaurants'); ?>"><button type="button" class="btn btn-info">Add Restaurant</button></a>
              </div>
               
        </div>
    </div>
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
    <div class="panel panel-default">
        <div class="panel-body">            
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable">
                <thead>
                    <tr>
                        <th>Restaurant Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                	<?php if(is_array($restaurants) && count($restaurants)>0){
                		foreach($restaurants as $key => $value){
                	?>
                   <tr id="res_details_<?php echo $value->restaurant_id; ?>">
                                         
                        <td><?php echo $value->restaurant_name; ?></td>
                        <td><?php echo $value->email; ?></td>
                        <td class="center"><?php echo $value->contact_no; ?></td>
                        <td class="center"><?php echo ($value->city_name)?$value->city_name:'N/A'; ?> - <?php echo ($value->state_name)?$value->state_name:'N/A' ?></td>
                        <td class="center">
                        	<?php if($value->is_active==1){
                        			echo "<span class='label label-success'>Active</span>";
                    			} else {
                        			echo "<span class='label label-danger'>Inactive</span>";
                    			}
                			?>
                    	</td>
                          <td class="center"><a href="<?php echo site_url('Restaurants/editRestaurants/'.$value->restaurant_id); ?>"><i class="fa fa-edit"> </i></a> |<a title="Delete" data-toggle="modal" data-target="#confirmationModal" data-backdrop="static" data-keyboard="false" onclick="deleteRestaurantDetail(<?php echo $value->restaurant_id; ?>)"  id="delete"><i class="fa fa-trash"> </i></a></td>
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

  var deleteRestaurantDetailUrl  = "<?php echo site_url('Restaurants/deleteRestaurantDetail')?>";
  var countryId                  = ""; 
  var stateId                    = ""; 
  var cityId                     = ""; 
  var ownerslist                 = "";
  var managerslist               = "";
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/restaurant.js"></script>