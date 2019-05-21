<style type="text/css">
  .panel-heading {padding: 3px 8px;margin-bottom: 11px;}
  .chosen-container{padding-right: 400px;}
  .chosen-container-single .chosen-single{width: 400px;}
</style>
<div class="warper container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                    <h1 class="pageTitle" >Dishes of <?php echo $restaurants->restaurant_name; ?></h1>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <a style="margin-top: 30px; margin-left: 85px;" href="<?php echo site_url('Restaurants/restaurantDishes/'.$restaurants->restaurant_id); ?>" class="btn btn-success"><i class="fa fa-arrow-left"></i> Back to Dishes</a>
                </div>
            </div>
        </div>
      </div>
     
    </div>
   
    <div class="col-lg-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                  <div class="panel-heading" >
                     <span style="float:left;">Best Dishes</span>
                        <span style="float:right;">
                          <a title="Add Dishes" data-toggle="modal" data-target="#addDishModel" data-backdrop="static" data-keyboard="false"><button  type="button" class="btn btn-success">Add Best Dishes</button></a>
                        </span>
                           
                  </div>   
                </div>            
                <div class="panel-body">
                  <div class="table_res_dish">
                    <table class="table table-striped table-bordered" id="basic-datatable">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th>Dish</th>
                                <th>Dish Category</th>
                                <th>Price</th>
                                <th>Action</th>
                                 
                            </tr>
                        </thead>
                          <tbody>
                            <?php 
                              $J =0;
                              if(is_array($bestDish) && count($bestDish)>0){
                                foreach($bestDish as $key => $value){
                            ?>
                                <tr class="center" id="dishRow<?php echo $value->product_id; ?>">
                                    <td>
                                        <?php echo ++$J; ?>
                                    </td>
                                    <td ><?php echo $value->product_en_name; ?></td>
                                    <td ><?php echo $value->category_name; ?></td>
                                    <td ><?php echo $value->dish_price; ?></td>
                                    <td ><a title="Delete" data-toggle="modal" data-target="#removeBestDishes" data-backdrop="static" data-keyboard="false" onclick="deleteBestDish(<?php echo $value->product_id; ?>)"  id="delete"><i class="fa fa-trash"> </i></a></td>
                                </tr>
                                
                            <?php  } } ?>
                        </tbody>
                    </table>
                  </div>
                </div>
            </div>
    </div>
</div>

<input type="hidden" id="res_id" value="<?php echo $restaurants->restaurant_id; ?>">
<div id="removeBestDishes" class="modal fade" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center !important;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4><label class="modal-title">Confirmation</label></h4>
            </div>
            <div class="modal-body" style="text-align:center !important;" >
                 <h5 id="message_text">Are sure to remove this best dish?</h5>
                 <span style="color: red;" id="errDish"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="removeBestDish">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addDishModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Add best dishes </h4>
                <div class="color-red error_add "></div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label cat_name">Dish list<i class="reustarred">*</i>&nbsp&nbsp</label>
                    <select class="form-control chosen-select" id="dishId">
                    
                        <option value="">Select Dish</option>
                        <?php 
                        foreach ($dishes as $key => $value){ ?>
                            <option  value="<?php echo $value->product_id; ?>"><?php echo $value->product_en_name; ?>
                            </option><?php 
                        } ?> 

                    </select>
                   
                </div>
                <span style="color: red;padding-left: 70px;" id="errAddDish"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addBestDishBtn">Add</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/restaurantBestDishes.js"></script>
<script type="text/javascript">
    var removeBestDishesUrl  ="<?php echo site_url('Restaurants/removeBestDish/'); ?>";
    var AddBestDishesUrl     ="<?php echo site_url('Restaurants/addBestDish/'); ?>";
    
</script>


