<style type="text/css">
  .center{text-align: center;}
  .childtable tr td {height: 100%;}
  .childtable_hidden{display:none;}
  .childtable.dataTable tbody th, .childtable.dataTable tbody td {padding: 0;}
  .childtable{border-width: 1px;border-style: solid;border-color: rgb(221, 221, 221);border-image: initial;background-color: transparent;}
  .childtable > thead > tr > td {border-bottom-width: 2px;}
  .childtable > thead > tr > th, .childtable > tbody > tr > th, .childtable > tfoot > tr > th, .childtable > thead > tr > td, .childtable > tbody > tr > td, .childtable > tfoot > tr > td {
      border: 1px solid #ddd;padding-top:6px !important;padding-bottom:6px !important;}
  .childtable > tbody > tr:nth-child(odd) > td, .childtable > tbody > tr:nth-child(odd) > th {
      background-color: #f9f9f9;}
  .details-control{width:102px;text-align: center;}
  .ch_name{width:202px;}
  .childtable tr:nth-child(odd) th{text-align: center;}
  .childtable tr:nth-child(2) th{width:656px;}
  #coucou{border-width: 1px;border-style: solid;border-color: rgb(221, 221, 221);border-image: initial;background-color: transparent;}
  #coucou > thead > tr > td {border-bottom-width: 2px;}
  #coucou > thead > tr > th, #coucou > tbody > tr > th, #coucou > tfoot > tr > th, #coucou > thead > tr > td, #coucou > tbody > tr > td, #coucou > tfoot > tr > td {border: 1px solid #ddd;padding-top:6px !important;padding-bottom:6px !important;}
  .childtable > tbody > tr td:nth-child(3){text-align: center !important;width:146px !important;}
  #tableSecondLevel thead tr th .dataTables_sizing{overflow: visible !important;}
  #tableSecondLevel thead:nth-child(4) tr th:nth-child(4),#tableSecondLevel tbody tr td:nth-child(4){
    width:6% !important;text-align:center !important;}
  #tableSecondLevel thead tr th {height:45px !important;}
  table thead th:nth-child(2){text-align:left !important;}
  #coucou tbody tr td:nth-child(1),#coucou thead th:nth-child(1){
    text-align:center;width:52px !important;}
  #coucou tbody tr td:nth-child(2),#coucou thead th:nth-child(2){width:150px !important;}
  #coucou tbody tr td:nth-child(3),#coucou thead th:nth-child(3){width:178px !important;}
  #coucou tbody tr td:nth-child(4),#coucou thead th:nth-child(4){text-align:center;width:102px !important;}
  .openingTime{float:right;margin-top: -7px;margin-right: -14px; margin-right:0px;}
  .time tr th{width: 10%; vertical-align:center !important;}
  .time tr td{ vertical-align: center;}
  .list-group-horizontal .list-group-item {display: inline-block;}
  .list-group-horizontal .list-group-item {padding: 1px 15px;background-color: none;border: 0px solid #ddd;}
  .table_res_dish table tr th{ text-align:center; padding:8px; vertical-align:middle; }
  .table_res_dish table tr th:nth-child(2){ text-align:left; }
  .table_res_dish table tr td{ text-align:center; padding:8px; vertical-align:middle; }
  .table_res_dish table tr td:nth-child(2){ text-align:left; }
  .table_res_dish table tr td:last-child a{ line-height:25px; padding:0 10px; background:#5cb85c; color:#fff; border-radius:2px; display:inline-block; font-size:12px; border:1px solid rgba(76,174,76,1); }
</style>
<div class="warper container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="page-header">
          <div class="row">
              <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                   <h1 class="pageTitle" style="margin: 0;">Dishes of <?php echo $restaurants->restaurant_name; ?></h1>
              </div>
              <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                  <a style="margin-top: 30px; margin-left: 85px;" href="<?php echo ($resId !="")?site_url('Restaurants/owners'):site_url('Restaurants'); ?>" class="btn btn-success"><i class="fa fa-arrow-left"></i> Back to Home</a>
              </div>
          </div>
         

        </div>
      </div>
     
    </div>
   
    <div class="col-lg-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                  <div class="panel-heading" >
                     <span style="float:left;">Dish Details</span>
                        <span style="float:right;">
                            <a href="<?php echo site_url('Restaurants/addDishes/'.$restaurants->restaurant_id); ?>" onclick="setRestaurantId(<?php echo $restaurants->restaurant_id; ?>)"><button  type="button" class="btn btn-success">Add Dishes</button></a>
                          <a href="<?php echo site_url('Restaurants/listBestDishes/'.$restaurants->restaurant_id); ?>" onclick="setRestaurantId(<?php echo $restaurants->restaurant_id; ?>)"><button  type="button" class="btn btn-success">Best Dishes</button></a>
                        </span>
                           
                  </div>   
                </div>            
                <div class="panel-body">
                  <div class="table_res_dish">
                    <table class="table table-striped table-bordered" id="basic-datatable">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th>Dish Category</th>
                                <th class="total_th">Total Dishes</th>
                                <th></th>
                                 
                            </tr>
                        </thead>
                          <tbody>
                          
                            <?php 
                              $J =0;
                              if(is_array($dishes) && count($dishes)>0){
                                foreach($dishes as $key => $value){
                            ?>
                                <tr class="center" id="order_details_dishes_<?php echo $key; ?>">
                                    <td>
                                        <?php echo ++$J; ?>
                                    </td>
                                    <td class="text-left"><?php echo $value['category_name']; ?></td>
                                    <td><?php echo $value['total_dish']; ?></td>
                                    <td class="toggleDish" isBestDish="0" pid="<?php echo $key; ?>"><a  href="javascript:void(0)">collapse</a></td>
                                </tr>
                                
                            <?php  }}?>
                        </tbody>
                    </table>
                  </div>
                </div>
            </div>
    </div>
</div>
<table id="example2" class="childtable childtable_hidden" cellspacing="0" width="100%">
  <thead><tr><th></th><th>Col 2</th><th>Col 3</th><th></th></tr></thead><tbody></tbody>
</table>    
<input type="hidden" id="res_id" value="<?php echo $restaurants->restaurant_id; ?>">
<div id="deleteDish" class="modal fade" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center !important;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4><label class="modal-title">Confirmation</label></h4>
            </div>
            <div class="modal-body" style="text-align:center !important;" >
                 <h5 id="message_text"></h5>
                 <span style="color: red; display: none;" id="errDeleteDish"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="deleteDishData">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<div id="hideDish" class="modal fade" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center !important;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4><label class="modal-title">Confirmation</label></h4>
            </div>
            <div class="modal-body" style="text-align:center !important;" >
                 <h5 id="hideResDish_message_text">Are you sure to hide this dish?</h5>
                 <span style="color: red; display: none;" id="errhideResDish"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="hideResDishBtn">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/restaurantDetails.js"></script>
<script type="text/javascript">
    var res_id          =$("#res_id").val();
    var catDishUrl      ="<?php echo site_url('Restaurants/getCategoryDish'); ?>";
    var editDishUrl     ="<?php echo site_url('Restaurants/editDish'); ?>";
    var deleteDishUrl   ="<?php echo site_url('Restaurants/deleteRestaurantDish'); ?>";
    var deleteChoiceUrl ="<?php echo site_url('Restaurants/deleteRestaurantDishChoice'); ?>";
    var setDishIdUrL    ="<?php echo site_url('Restaurants/setDishId'); ?>";
    var hideShowDishUrl ="<?php echo site_url('Restaurants/hideShowDishUrl'); ?>";
</script>


