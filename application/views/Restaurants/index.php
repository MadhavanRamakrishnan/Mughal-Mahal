  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />

  <style type="text/css">
  table thead tr th, .center{
    text-align: center;
  }
  .panel-success .panel-heading{
    margin-bottom:10px;
  }
  .update_restaurant_btn{ width:100%; float: left; padding:20px 0 0; }
  </style>
  <style type="text/css">
  table thead tr th, .center{
    text-align: center;
  }
 .ui-autocomplete {
  z-index: 99999;
  display: inline-block!important;
  overflow: visible;
  background-color: #fff;
  position: absolute;
} 
.ui-autocomplete-input{
  position: relative;
}

  .chosen-container{
    width: 100% !important;
  }
  .center .btn{border-radius: 18px;}

  </style>
  <div class="warper container-fluid">
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="page-header">
          <h1 class="pageTitle" style="margin: 0;">Restaurants</h1>
        </div>
        
      </div>
      
   </div>
   <?php $successMsg=$this->session->flashdata('success_msg'); ?>

   <div class="alert alert-success alert-dismissible availability" id="success_notification" style="display:<?php echo ($successMsg)?"block":"none"; ?>">
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
      <i class="fa fa-cutlery" aria-hidden="true"></i>
        <span class="nav-label" >List of Branches</span>
        <div class="page-header f-right m-b-10">
         <a class="addButtons" href="<?php echo site_url('Restaurants/addRestaurants'); ?>"><button type="button" class="btn btn-success">Add Restaurant</button></a>
       </div>
    </div>   
    <div class="panel-body">           
      <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable123">
        <thead>
          <tr>
            <th>Restaurant Name</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Owner</th>
            <th>Availability</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if(is_array($restaurants) && count($restaurants)>0){
            foreach($restaurants as $key => $value){
          ?>
          <tr id="res_details_<?php echo $value->restaurant_id; ?>">

            <td class="center"><a href="<?php echo site_url('Restaurants/RestaurantDetails/').$value->restaurant_id;?>"><?php echo $value->restaurant_name; ?></a></td>
            <td class="center"><?php echo $value->o_email; ?></td>
            <td class="center"><?php echo ($value->contact_no !='')?" (+965) ".$value->contact_no:''; ?></td>
            <td class="center"><?php echo $value->o_fname.' '.$value->o_lname; ?></td>
            <td class="center">
              <input type="checkbox" onchange="restaurantAvailability('<?php echo $value->restaurant_id; ?>');" class="activeSwitch<?php echo $value->restaurant_id; ?> avalability" <?php echo ($value->is_availability == 1)?"checked":"";  ?>   availability="<?= $value->is_availability; ?>" rid="<?php echo $value->restaurant_id; ?>" data-toggle="toggle" data-onstyle="primary" > 
            </td>
        
            <td class="center">
               <?php if($value->is_active==1){
                 echo "<span class='label label-success'>Active</span>";
               } else {
                 echo "<span class='label label-danger'>Inactive</span>";
               }
               ?>
             </td>
             <td class="center">
               <a href="<?php echo site_url('Restaurants/restaurantDishes/'.$value->restaurant_id); ?>"><i class="fa fa-cutlery" title="Dishes" onclick="setRestaurantId(<?php echo $value->restaurant_id; ?>)"> </i></a>
               |
               <a href="<?php echo site_url('Restaurants/editRestaurants/'.$value->restaurant_id); ?>"><i class="fa fa-edit"> </i></a>
                |
                <a title="Delete" data-toggle="modal" data-target="#confirmationModal" data-backdrop="static" data-keyboard="false" onclick="deleteRestaurantDetail(<?php echo $value->restaurant_id; ?>)"  id="delete"><i class="fa fa-trash"> </i></a>
            </td>
          </tr>
          <?php } } ?>
        </tbody>
      </table>
    </div>
  </div>
  </div>

  <script type="text/javascript">
      $(document).ready(function(){
        $(".avalability").bootstrapToggle({
          on: 'Yes',
          off: 'No'
         });
      })
    var deleteRestaurantDetailUrl  = "<?php echo site_url('Restaurants/deleteRestaurantDetail')?>";
    var changeAvailability         = "<?php echo site_url('Restaurants/changeAvailability')?>";
    var ownerslist                 = "";
    function setRestaurantId(id){
        $("#resId").val(id);
    }
  </script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/restaurant.js"></script>
