<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<style type="text/css">
	table thead tr th, .center{
		text-align: center;
	}
</style>
<div class="warper container-fluid">
	<div class="page-header">
		<h1 class="pageTitle">Cuisines <small>List of Cuisines</small></h1>
	<!-- 	<a class="addButtons" href="<?php echo site_url('Cuisines/addCuisine'); ?>"><button type="button" class="btn btn-info">Add Cuisine</button></a> -->
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
                        <th>Name</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                	<?php if(is_array($cuisines) && count($cuisines)>0){
                		foreach($cuisines as $key => $value){
                      if($value->image != ""){

                          $cuisineImage = $value->image;
                      }
                      else{
                          $cuisineImage = 'no_image.png';
                      } ?>
                        
                    <tr class="odd gradeX" id="cuisine_details_<?php echo $value->cuisine_id; ?>">
                        <td><?php echo ($value->cuisine_name)?$value->cuisine_name:'N/A'; ?></td>
                        <td><?php echo ($value->description)?$value->description:'N/A'; ?></td>
                        <td align="center"><img style="width:80px;" src="<?php echo base_url()."assets/uploads/cuisines/".$cuisineImage; ?>"></td>
                        <td class="center"><a href="<?php echo site_url('Cuisines/editCuisine/'.$value->cuisine_id); ?>"><i class="fa fa-edit"> </i></a> |<a title="Delete" data-toggle="modal" data-target="#confirmationModal" data-backdrop="static" data-keyboard="false" onclick="deleteCuisine(<?php echo $value->cuisine_id; ?>)"  id="delete"><i class="fa fa-trash"> </i></a></td>
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    function deleteCuisine(id){
       
        $('#deleteMsg').text('Are you sure to delete cuisine details?');
        $("#delete_btn").unbind().click(function(){

           $.ajax({
              url     : "<?php echo site_url('cuisines/deleteCuisine')?>",
              type    : "POST",
              data    :  {cuisine_id:id},

              success : function(response){
                          var obj = JSON.parse(response);

                          if(obj.success==1)  {
                            $('#confirmationModal').modal('hide');
                            $("#cuisine_details_"+id).remove();

                            $("#success_message").text(obj.message);
                            $("#success_notification").show();
                          }
                          else{
                            $("#error_message").text(obj.message);
                            $("#flasherror").show();
                          }
                        }
            });
        });
    }

$(".alert").alert();
window.setTimeout(function() { $(".alert").alert('close'); }, 3000);

</script>