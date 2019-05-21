<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<style type="text/css">
	table thead tr th, .center{
		text-align: center;
	}
   .panel-success .panel-heading{
    margin-bottom:10px;
  }
</style>
<div class="warper container-fluid">
    <div class="row">
    	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="page-header">
      		<h1 class="pageTitle" style="margin: 0;">Managers </h1>
      	</div>
        
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
          
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
    <div class="panel panel-success">
        <div class="panel-heading">
            <i class="fa fa-users" aria-hidden="true"></i>
            <span class="nav-label" >List of Managers</span>
            <div class="page-header f-right m-b-10">
              <a class="addButtons" href="<?php echo site_url('Restaurants/addOwner'); ?>"><button type="button" class="btn btn-success">Add Manager</button></a>
            </div>
        </div>
        <div class="panel-body">            
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Restaurant</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  <?php if(is_array($owners) && count($owners)>0){
                    foreach($owners as $key => $value){
                      $ImgLoc1="./assets/uploads/users/".$value->profile_photo;
                      if(file_exists($ImgLoc1) && !empty($value->profile_photo)){

                          $categoryImage = $value->profile_photo;
                      }
                      else{
                          $categoryImage = 'no_image.png';
                      } ?>
                      
                     <tr id="owner_details_<?php echo $value->user_id; ?>">
                                          
                        <td class="center"><?php echo $value->first_name.' '.$value->last_name; ?></td>
                        <td class="center"><?php echo $value->email; ?></td>
                        <td class="center"><?php echo ($value->contact_no !='')?" (+965) ".$value->contact_no:''; ?></td>
                        <td class="center"><?php echo $value->restaurant_name; ?></td>
                        <td class="center"><a href="<?php echo site_url('Restaurants/editOwner/'.$value->user_id); ?>"><i class="fa fa-edit"> </i></a> |<a title="Delete" data-toggle="modal" data-target="#confirmationModal" data-backdrop="static" data-keyboard="false" onclick="deleteOwnerDetail(<?php echo $value->user_id; ?>)"  id="delete"><i class="fa fa-trash"> </i></a></td>
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

  var deleteRestaurantDetailUrl  = "<?php echo site_url('Restaurants/deleteRestaurantDetail')?>";
  var deleteOwnerDetailUrl       = "<?php echo site_url('Restaurants/deleteOwnerDetail')?>";
  var ownerslist           = "";
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/restaurant.js"></script>