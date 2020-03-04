<link rel="stylesheet" href="<?= base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<style type="text/css">
table thead tr th,table  tr td{
  text-align: center;
}
#deleteLocality .modal-body ,#deleteLocality .modal-header{text-align: center;}
.page-header { padding-bottom: 0px;}
</style>
<div class="warper container-fluid">
	<div class="page-header">
		<h1 class="pageTitle">Localities</h1>
	</div>
  <h5>List of Localities</h5>
 <div class="row">
  <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
    <div class="page-header f-right m-b-10">
      <a class="addButtons" href="<?= site_url('Localities/addLocality'); ?>"><button type="button" class="btn btn-info">Add Locality</button></a>
    </div>
    
  </div>
</div>

<?php $successMsg=$this->session->flashdata('success_message'); ?>
<?php $errorMsg=$this->session->flashdata('error_message'); ?>

<div class="alert alert-success alert-dismissible" id="success_notification" style="display:<?= ($successMsg)?"block":"none"; ?>">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4><i class="icon fa fa-check"></i> Success!</h4>
  <p id="success_message"><?= $successMsg; ?></p>
</div>

<div class="alert alert-danger alert-dismissible" id="error_notification" style="display:<?= ($errorMsg)?"block":"none"; ?>">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4><i class="icon fa fa-warning"></i> Failed!</h4>
  <p id="error_message"><?= $errorMsg; ?></p>
</div>

<div class="panel panel-default">
  <div class="panel-body">            
    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable">
      <thead>
        <tr>
          <th>Name</th>
          <th>Arabic Name</th>
          <th>Restaurant Name</th>
          <th>Delivery Time (Minutes)</th>
          <th>Extra Delivery Time (Minutes)</th>
          <th>Delivery Charge</th>
          <th>Minimum Amount for Order</th>
          <th>Action</th>
          
        </tr>
      </thead>
      <tbody>
       <?php if(is_array($localitylist) && count($localitylist)>0){
        foreach($localitylist as $key => $value){
         ?>
        
          <tr class="odd gradeX" id="category_details_<?= $value->locality_id; ?>">
          <td><?= $value->name; ?></td>
          <td><?= $value->name_ar; ?></td>
          <td><?= $value->restaurant_name; ?></td>
          <td><?= $value->delivered_time; ?></td>
          <td><?= $value->extra_delivery_time; ?></td>
          <td><?= $value->delivery_charge; ?></td>
          <td><?= $value->min_order_amount; ?></td>
          <td class="center"><a href="<?= site_url('Localities/editLocality/'.$value->locality_id); ?>"><i class="fa fa-edit"> </i></a> |<a title="Delete" data-toggle="modal" data-target="#deleteLocality" data-backdrop="static" data-keyboard="false" onclick="deleteLocality(<?= $value->locality_id; ?>)"  id="delete"><i class="fa fa-trash"> </i></a></td>
        </tr>
        <?php } }?>
      </tbody>
    </table>
  </div>
</div>
</div>

<div id="deleteLocality" class="modal fade" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
                <p id="deleteMsg1"><h5>Are you sure to delete this locality?</h5></p>
                <span style="color: #ff0000 !important; " class="error_reason"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="delete_loc">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
   $(".alert").alert();
     window.setTimeout(function() { $(".alert").alert('close'); }, 3000);
     
  function deleteLocality(id) {
    $("#delete_loc").unbind().click(function(){
        
        $.ajax({
            type        :      "POST",
            data        :      {id:id},
            url         :      "<?= site_url('Localities/deleteLocality'); ?>",                
            success     :      function(response)
            {
                location.reload();
            }
        });
    });
  }
</script>