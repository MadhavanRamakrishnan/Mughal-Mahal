<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<style type="text/css">
table thead tr th, .center{
  text-align: center;
}
.page-header { padding-bottom: 0px;}
</style>
<div class="warper container-fluid">
	<div class="page-header">
		<h1 class="pageTitle">Dish Categories</h1>
	</div>
  <h5>List of Dish Categories</h5>
 <div class="row">
  <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
    <div class="page-header f-right m-b-10">
      <a class="addButtons" href="<?php echo site_url('Category/addCategory'); ?>"><button type="button" class="btn btn-info">Add Category</button></a>
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
          <th>Dish Category</th>
          <th>Image</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
       <?php if(is_array($categoryList) && count($categoryList)>0){
        foreach($categoryList as $key => $value){
         $ImgLoc1="./assets/uploads/category/".$value->image;
         if(file_exists($ImgLoc1) && !empty($value->image)){

          $categoryImage = $value->image;
        }
        else{
          $categoryImage = 'no_image.png';
        } ?>
        
        <tr class="odd gradeX" id="category_details_<?php echo $value->category_id; ?>">
          <td><?php echo ($value->category_name)?stripslashes($value->category_name):'N/A'; ?></td>
          <td align="center"><div class="img-height"><img style="width:80px;" src="<?php echo base_url()."assets/uploads/category/".$categoryImage; ?>"></div></td>
          <td class="center"><a href="<?php echo site_url('Category/editCategory/'.$value->category_id); ?>"><i class="fa fa-edit"> </i></a> |<a title="Delete" data-toggle="modal" data-target="#confirmationModal" data-backdrop="static" data-keyboard="false" onclick="deleteCategory(<?php echo $value->category_id; ?>)"  id="delete"><i class="fa fa-trash"> </i></a></td>
        </tr>
        <?php } }?>
      </tbody>
    </table>
  </div>
</div>
</div>

<script type="text/javascript">

  function deleteCategory(id){
   
    $('#deleteMsg').text('Are you sure to delete category details?');
    $("#delete_btn").unbind().click(function(){

     $.ajax({
      url     : "<?php echo site_url('category/deleteCategory')?>",
      type    : "POST",
      data    :  {category_id:id},

      success : function(response){
        var obj = JSON.parse(response);

        if(obj.success==1)  {
          $('#confirmationModal').modal('hide');
          var table = $('#basic-datatable').DataTable();
          table.row( $('#category_details_'+id).closest('tr') ).remove().draw();
          $("#category_details_"+id).remove();
                            // var t =  $('#basic-datatable').dataTable();
                            // t.rows().invalidate().draw();
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