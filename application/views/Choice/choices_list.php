<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<style type="text/css">
	table  tr  .center{
		text-align: center;
	}
  .page-header { padding-bottom: 0px;}
</style>
<div class="warper container-fluid">
	<div class="page-header">
		<h1 class="pageTitle">Choices</h1>
	</div>
  <h5>List of Choices</h5>
   <div class="row">
      <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
            <div class="page-header f-right m-b-10">
           <a class="addButtons" data-toggle="modal" data-target="#modalAddChoice"><button type="button" class="btn btn-info">Add Choice</button></a>
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
                        <th>Choice Category</th>
                        <th>Choice Name</th>
                        <th>Choice Name (Arabic)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                	<?php if(is_array($choiceList) && count($choiceList)>0){
                		foreach($choiceList as $key => $value){ ?>
                      
                    <tr class="odd gradeX" id="choice_details_<?php echo $value->choice_id; ?>">
                        <td><?php echo ($value->choice_category_name && $value->is_active=='1')?$value->choice_category_name:'N/A'; ?></td>
                        <td><?php echo ($value->choice_name)?stripslashes($value->choice_name):'N/A'; ?></td> 
                        <td><?php echo ($value->choice_name_ar)?$value->choice_name_ar:'N/A'; ?></td> 
                        <td class="center">
                            <a class="editButtons" data-toggle="modal" data-target="#modalEditChoice" cat_id="<?php echo $value->fk_choice_category_id; ?>" ch_name="<?php echo stripslashes($value->choice_name) ; ?>" ch_ar_name="<?php echo $value->choice_name_ar ; ?>" ch_id="<?php echo $value->choice_id; ?>" data-backdrop="static"       data-keyboard="false"><i class="fa fa-edit"> </i></a>
                           |
                           <a title="Delete" data-toggle="modal" data-target="#confirmationModal" data-backdrop="static" data-keyboard="false" onclick="deleteChoice(<?php echo $value->choice_id; ?>)"  id="delete"><i class="fa fa-trash"> </i></a>
                       </td>
                    </tr>
                    <?php } }?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="modalAddChoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Choice </h4>
                <div class="color-red error_add "></div>
            </div>
            <div class="modal-body">
                <form role="form" method="POST">
                     <div class="form-group">
                        <label class="control-label cat_name">Choice Category<i class="reustarred">*</i></label>
                        <select class="form-control" name="cat_name" id="cat_name">
                        <?php 
                        if(set_value('cat_name')){$catId=set_value('cat_name');}
                        else {$catId="";} ?>
                        <option value="">Select Category Type</option>
                            <?php 
                            foreach ($categoryList as $key => $value){ ?>
                                <option  value="<?php echo $value->choice_category_id ?>" <?php echo ($catId == $value->choice_category_id)?"selected":""; ?>><?php echo $value->choice_category_name; ?>
                                </option><?php 
                            } ?> 
                        </select>
                        <div class="color-red cat_name "></div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="control-label">Choice Name<i class="reustarred">*</i></label>
                                <input class="form-control choice_name" name="choice_name" id="choice_name" placeholder="Enter Choice Name"  type="text" value="<?php echo (set_value('choice_name'))?set_value('choice_name'):""; ?>">
                                <div class="color-red choice_name"></div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="control-label">Choice Name (Arabic)<i class="reustarred">*</i></label>
                                <input class="form-control choice_name" name="choice_ar_name" id="choice__ar_name" placeholder="Enter Arabic  Choice Name"  type="text" value="<?php echo (set_value('choice_ar_name'))?set_value('choice_ar_name'):""; ?>">
                                <div class="color-red choice_ar_name"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="add_choice">Add</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditChoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Choice </h4>
                <div class="color-red error_edit "></div>
            </div>
            <div class="modal-body">
                <form id="editForm" role="form" method="POST">
                     <div class="form-group">
                        <label class="control-label cat_name">Choice Category<i class="reustarred">*</i></label>
                        <select class="form-control" name="cat_name" id="edit_cat_name">
                        <?php 
                        if(set_value('cat_name')){$catId=set_value('cat_name');}
                        else {$catId="";} ?>
                        <option value="">Select Category Type</option>
                            <?php 
                            foreach ($categoryList as $key => $value){ ?>
                                <option  value="<?php echo $value->choice_category_id ?>"><?php echo $value->choice_category_name; ?>
                                </option><?php 
                            } ?> 
                        </select>
                         
                        <div class="color-red edit_cat_name "></div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="control-label">Choice Name<i class="reustarred">*</i></label>
                                <input class="form-control choice_name" name="choice_name" id="edit_choice_name" placeholder="Enter Choice Name"  type="text" value="">
                                <input class="form-control" id="ch_id" type="hidden" >
                                <div class="color-red edit_choice_name"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="control-label">Choice Name (Arabic)<i class="reustarred">*</i></label>
                                <input class="form-control choice_name" name="choice__ar_name" id="edit_choice_ar_name" placeholder="Enter arabic Choice Name"  type="text" value="">
                                <div class="color-red edit_choice__ar_name"></div>
                              
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="edit_choice">Add</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function deleteChoice(id){
        $('#deleteMsg').text('Are you sure to delete choice details?');
        $("#delete_btn").unbind().click(function(){

           $.ajax({
              url     : "<?php echo site_url('Choice/deleteChoice')?>",
              type    : "POST",
              data    :  {choice_id:id},

              success : function(response){
                          var obj = JSON.parse(response);

                          if(obj.success==1)  {
                            var table = $('#basic-datatable').DataTable();
                            $('#confirmationModal').modal('hide');
                            table.row( $('#choice_details_'+id).closest('tr') ).remove().draw();
                            // $('#choice_details_'+id).closest('tr').remove();
                            $("#choice_details_"+id).remove();
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

     $("#add_choice").click(function(e){
   
          var cat_name        =$.trim($("#cat_name").val());
          var choice_name     =$.trim($("#choice_name").val());
          var choice__ar_name =$.trim($("#choice__ar_name").val());
          
          if(cat_name == ""){
              $(".cat_name").text('Please Select Category');
              $(".choice_name").text('');
              $(".choice_ar_name").text('');
          }
          else if(choice_name == ''){
            $(".choice_name").text('Please Enter Choice Name');
            $(".choice_ar_name").text('');
            $(".cat_name").text('');

          }
          else if(choice__ar_name == ''){
            $(".choice_ar_name").text('Please Enter Arabic Choice Name');
            $(".choice_name").text('');
            $(".cat_name").text('');
          }
          else{
              $.ajax({
                  url     : "<?php echo site_url('Choice/addChoice')?>",
                  type    : "POST",
                  data    :  {cat_name:cat_name,choice_name:choice_name,choice__ar_name:choice__ar_name},

                  success : function(response){
                    
                      var obj = JSON.parse(response);

                      if(obj.success == "1")  {
                         location.reload();
                      }else{
                         $(".error_add").text(obj.message);
                      }
                  }
              });
          }
           
    });

    $(".editButtons").click(function(){
          $("#edit_cat_name").val($(this).attr('cat_id'));
          $("#edit_choice_name").val($(this).attr('ch_name'));
          $("#edit_choice_ar_name").val($(this).attr('ch_ar_name'));
          $("#ch_id").val($(this).attr('ch_id'));
    });

    $("#edit_choice").click(function(e){

          e.preventDefault();

          var cat_name            = $.trim($("#edit_cat_name").val());
          var choice_name         = $.trim($("#edit_choice_name").val());
          var choice__ar_name     = $.trim($("#edit_choice_ar_name").val());
          var id                  = $.trim($("#ch_id").val());
       
          if(cat_name == ""){
              $(".edit_cat_name").text('Please Select Category');
              $(".edit_choice_name").text('');
              $(".edit_choice__ar_name").text('');
          }
          else if(choice_name == ''){
            $(".edit_choice_name").text('Please Enter Choice Name');
            $(".edit_choice__ar_name").text('');
            $(".edit_cat_name").text('');

          }
          else if(choice__ar_name == ''){
            $(".edit_choice__ar_name").text('Please Enter Arabic Choice Name');
            $(".edit_cat_name").text('');
            $(".edit_choice_name").text('');
          }
          else{
               $.ajax({
                    url     : "<?php echo site_url('Choice/editChoices')?>/" +id,
                    type    : "POST",
                    data    : $('#editForm').serialize(),

                    success : function(response){
                            var obj = JSON.parse(response);

                            if(obj.success == '1')  {
                               location.reload();
                            }
                            else{
                               $(".error_edit").text(obj.message);
                            }
                    }
              }); 
          }
        


          
    });
setTimeout(function(){ $("#success_notification").hide(); },5000);
setTimeout(function(){ $("#error_notification").hide(); },5000);

</script>