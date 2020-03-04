<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<style type="text/css">
	table tr  .center{
		text-align: center;
	}
  #loading-div-background {
    top: -171px;
    z-index: 999999;
  }
  .page-header { padding-bottom: 0px;}
</style>
 <div id="loading-div-background" style="display:none">
        <div id="loading-div" class="ui-corner-all">
            <img src="<?php echo base_url('assets/images/front-end/loading1.gif'); ?>" alt="Loading..">
        </div>
  </div>
<div class="warper container-fluid">
	<div class="page-header">
		<h1 class="pageTitle">Choice Categories</h1>
	
	</div>
  <h5>List of Choice Categories</h5>
   <div class="row">
      <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
            <div class="page-header f-right m-b-10">
            <a class="addButtons" data-toggle="modal" data-target="#modal-add-category" onclick='$(".cat_name").hide();'><button type="button" class="btn btn-info">Add Category</button></a>
              </div>
               
        </div>
    </div>

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
 
    <div class="panel panel-default">
        <div class="panel-body">            
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable">
                <thead>
                    <tr>
                        <th>Choice Category</th>
                        <th class="center">Action</th>
                    </tr>
                </thead>
                <tbody>
                	<?php if(is_array($categoryList) && count($categoryList)>0){
                		foreach($categoryList as $key => $value){ ?>
                      
                    <tr class="odd gradeX" id="category_details_<?php echo $value->choice_category_id; ?>">
                        <td><?php echo ($value->choice_category_name)?stripslashes($value->choice_category_name):'N/A'; ?></td>
                        <td class="center">

                          <a class="addButtons" data-toggle="modal" data-target="#modal-edit-category" cat_name="<?php echo stripslashes($value->choice_category_name); ?>" cat_id="<?php echo $value->choice_category_id; ?>" data-backdrop="static"       data-keyboard="false" onclick="$('.edit_cat_name').hide();">
                              <i class="fa fa-edit"> </i>
                          </a> |
                          <a title="Delete" data-toggle="modal" data-target="#confirmationModal" data-backdrop="static" data-keyboard="false" onclick="deleteChoiceCategory(<?php echo $value->choice_category_id; ?>)"  id="delete">
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
<div class="modal fade" id="modal-add-category" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Choice Category</h4>
            </div>
            <div class="modal-body">
                <form role="form" method="POST">
                    <div class="form-group">
                         <label class="control-label">Choice Category Name<i class="reustarred">*</i></label>
                          <input class="form-control" name="cat_name" id="cat_name" placeholder="Enter Category Name"  type="text">
                          <div class="color-red cat_name"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="add_category">Add</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-edit-category" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Choice Category</h4>
            </div>
            <div class="modal-body">
                <form role="form" method="POST">
                    <div class="form-group">
                         <label class="control-label">Choice Category Name<i class="reustarred">*</i></label>
                          <input class="form-control" name="cat_name" id="edi_cat_name" placeholder="Enter Category Name"  type="text" >
                         <input class="form-control" id="cat_id" type="hidden" >
                          <div class="color-red edit_cat_name"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="edit_category">Add</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
     
    function deleteChoiceCategory(id){
       
        $('#deleteMsg').text('Are you sure to delete choice category details?');
        $("#delete_btn").unbind().click(function(){

           $.ajax({
              url     : "<?php echo site_url('Choice/deleteChoiceCategory')?>",
              type    : "POST",
              data    :  {choice_category_id:id},

              success : function(response){
                        console.log(response);
                        var obj = JSON.parse(response);

                        if(obj.success== "1")  {
                          $('#confirmationModal').modal('hide');
                          var table = $('#basic-datatable').DataTable();
                          table.row( $('#category_details_'+id).closest('tr') ).remove().draw();
                          $("#category_details_"+id).remove();
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
    $(".addButtons").click(function(){
          $("#edi_cat_name").val($(this).attr('cat_name'));
          $("#cat_id").val($(this).attr('cat_id'));
    });
    $("#add_category").click(function(e){
          e.preventDefault();
          var cat_name = $("#cat_name").val();
         $("#loading-div-background").show();
          $.ajax({
              url     : "<?php echo site_url('Choice/addChoiceCategory')?>/",
              type    : "POST",
              data    :  {cat_name:cat_name,id:""},

              success : function(response){
                 
                  var obj = JSON.parse(response);

                  if(obj.success ==1)  {
                     location.reload();
                  }
                  else{
                    $("#loading-div-background").hide();
                    $('.cat_name').html(obj.message);
                    $(".cat_name").show();
                  }
                    
            }
        }); 
    });
    $("#edit_category").click(function(e){
          e.preventDefault();
          var cat_name = $("#edi_cat_name").val();
          var id       = $("#cat_id").val();
         $("#loading-div-background").show();
          $.ajax({
              url     : "<?php echo site_url('Choice/editChoiceCategory')?>/" +id,
              type    : "POST",
              data    :  {cat_name:cat_name,id:id},

              success : function(response){
                
                  var obj = JSON.parse(response);

                  if(obj.success ==1)  {
                     location.reload();
                  }
                  else{
                    $("#loading-div-background").hide();
                    $('.edit_cat_name').html(obj.message);
                    $(".edit_cat_name").show();
                  }
                    
            }
        }); 
    });
//$(".alert").alert();
//window.setTimeout(function() { $(".alert").alert('close'); }, 5000);

setTimeout(function(){ $("#success_notification").hide(); },5000);
setTimeout(function(){ $("#error_notification").hide(); },5000);

</script>