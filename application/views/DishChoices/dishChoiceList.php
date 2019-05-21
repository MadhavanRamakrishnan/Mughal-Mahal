 
<style type="text/css">
  .chosen-container{
    width: 100% !important;
  }
</style>
    <div class="warper container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="page-header f-left">
            <h1>Dish Choice</h1>
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
      
      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <form action="<?php echo site_url('Dishes/searchDish');?>" method="POST">
                      
              <!-- <div class="input-group">
                  <input class="form-control input-sm  form-control-flat mrg-btn" placeholder="Search by dish title" type="text" name="search" value="<?php if(isset($value)){
                  echo $value;}?>">
                  <span class="input-group-btn">
                    <button class="btn btn-info btn-sm btn-flat mrg-btn" type="submit" name='go' value="go">Search</button>
                  </span>
              </div>
              <div class="input-group">
                <?php 
                if($this->session->flashdata('validation_search') != ""){ 
                  ?>
                  <p style="font-size: 15px;color: red;">Search is required</p>
                  <?php
                }
                ?>
              </div> -->
            </form>
          </div>
          <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12">
            <div class="page-header f-right">
              <a href="#" title="add dish choices" data-toggle="modal" data-target="#addDishChoice" data-backdrop="static"  data-keyboard="false" type="button" class="btn btn-info">Add Dish Choice</a>
            </div>
            
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading">Dish Choices List</div>
              <div class="panel-body table-responsive">

                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Dish Category</th>
                      <th>Choices</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                 <tbody>
                    <?php 
                    if(count($dishChoiceList)==0){
                      ?>
                      <tr ><td colspan='6' align="center">No Dish Choice Available</td></tr>
                      <?php 
                    }else{ 
                      foreach ($dishChoiceList as $key => $value) { ?>
                      
                      <tr id="dish_details_<?php echo $value->dish_choice_id; ?>">

                        <td><?php echo $value->fk_dish_id; ?></td>
                        <td><?php echo $value->fk_choice_id; ?></td>

                        <td class="center"><a title="Edit" data-toggle="modal" data-target="#editDishChoice" data-backdrop="static" data-keyboard="false" dishChoiceID="<?php echo $value->dish_choice_id; ?>"><i class="fa fa-edit"> </i></a> |<a title="Delete" data-toggle="modal" data-target="#confirmationModal" data-backdrop="static" data-keyboard="false" onclick="deleteDishChoiceDetail(<?php echo $value->dish_choice_id; ?>)"  id="delete"><i class="fa fa-trash"> </i></a></td>
                        
                      </tr>
                      <?php
                    }
                  }
                  ?>
                  </tbody> 
              </table>
              <div class="row">
                <div class="col-sm-12"> 
                  <ul class="pagination showcase-pagination pull-right">
                    <?php foreach ($links as $link) {
                      echo "<li>".$link."</li>";
                    } ?>
                  </ul>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="addDishChoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Dish Choice </h4>
                <div class="color-red message "></div>
            </div>
            <div class="modal-body">
                <form id="addForm" role="form" method="POST">
                    <div class="form-group">
                        <label class="control-label cat_name">Choice Category<i class="reustarred">*</i></label>
                        <select class="form-control" name="catName" id="catName">
                        
                            <?php 
                            foreach ($categoryList as $key => $value){ ?>
                                <option  value="<?php echo $value->choice_category_id ?>"><?php echo $value->choice_category_name; ?>
                                </option><?php 
                            } ?> 
                        </select>
                         
                        <div class="color-red catName "></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label cat_name">Choice<i class="reustarred">*</i></label>
                        <div class="addoption">
                          <select class="form-control chosen" name="chioce[]" id="choice" multiple>
                               
                            <?php foreach ($choiceList as $key => $value) {?>
                                  <option value="<?php echo $value->choice_id ?>"><?php echo $value->choice_name; ?></option></optgroup>
                             <?php } ?> 
                          </select>
                        </div>
                        <div class="color-red choice "></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addDishChoiceData">Add</button>
            </div>
        </div>
    </div>
</div>
    <script type="text/javascript">
      
      $(document).ready(function(){
          $('.chosen').chosen();
      });
      
      var deleteDishChoiceDetailUrl    = "<?php echo site_url('Dishes/deleteDishChoiceDetail')?>";
      var addDishChoiceDetailUrl       = "<?php echo site_url('Dishes/addDishChoice')?>";
      
      $("#catName").change(function(){
          $.ajax({
            type    :'POST',
            url     :"<?php echo site_url('Dishes/getChoiceOfCategory')?>/" +$(this).val() ,
            success :function(data){
                        
                        var obj =$.parseJSON(data);
                        $('.chosen-results').children("li").remove();
                         if(obj.success =='1'){

                            $.each(obj.message,function(key,val){

                             var selectChoice ='<li class="active-result" data-option-array-index="'+val.choice_id+'" style="">'+val.choice_name+'</li>';
                             $('.chosen-results').append(selectChoice);
                            });

                         }else{
                             
                             $('.default').val("No choive available");
                         }
                        
                        
                    }
        })
      });
      $("#addDishChoiceData").click(function(e){
        e.preventDefault();
        $.ajax({
            type    :'POST',
            url     :addDishChoiceDetailUrl,
            data    :$("#addForm").serialize(),
            success :function(data){
                        console.log(data);  

                        var obj =$.parseJSON(data);
                        
                        if(obj.message != ''){
                          $('.message').html(obj.message);
                        }
                        else if(obj.catName != '')
                        {
                          $('.catName').html(obj.catName);
                        }
                        else if(obj.chioce != ''){
                          $('.choice').html(obj.chioce);
                        }
                        else{
                          location.reload();
                        }
                    }
        })
      })
    </script>
    <!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dishdetail.js"></script> -->