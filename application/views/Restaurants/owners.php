<style type="text/css">
.update_restaurant_btn{ width:100%; float: left; padding:20px 0 0; }
.r_rating {
    padding: 10px !important;
}
.r_rating img{
    width: 60px;
    border-radius: 50%;
    height: 60px;
    position: relative;
    top: -18px;
}
.r_rating_cnt{
    width: auto;
    display: inline-block;
    padding-left: 20px;
}
.r_rating_cnt h3{
  margin-top:0px !important;
}
.checked {
    color: orange;
}
  .center{
    text-align: center;
  }

.page-header:nth-child(2){margin:0px 10px 10px;}
</style>
<div class="warper container-fluid">
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="page-header">
          <h1 class="pageTitle" style="margin: 0;">Restaurant <small>Detail of Restaurant</small></h1>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
          <div class="page-header f-right m-b-10">
            <a class="addButtons" href="<?php echo site_url('Restaurants/restaurantDishes/'.$resId); ?>"><button type="button" class="btn btn-success">Dishes</button></a>
          </div>
          <div class="page-header f-right m-b-10">
            <a class="addButtons" href="<?php echo site_url('Restaurants/addRestaurantTime/'.$resId); ?>"><button type="button" class="btn btn-success">Update opening Time</button></a>
          </div>
          <div class="page-header f-right m-b-10">
            <a class="addButtons" href="<?php echo site_url('Restaurants/editRestaurants/'.$resId); ?>"><button type="button" class="btn btn-success">Edit Restaurant</button></a>
          </div>
      </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content">
                <div class="panel panel-default tab-pane tabs-up active" id="pending" style="border-top: 1px solid #ddd;">
                    <div class="panel-body">
                        <ul class="media-list nicescroll" tabindex="5001">
                            <?php if($restaurants){
                            ?>
                                <li>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                            Branch : <?php echo $restaurants->restaurant_name; ?>
                                            </div>
                                            <div class="panel-body panelData">
                                              
                                              <div class="restOwnerBanner">
                                                <?php $a = $restaurants->banner_image;
                                                $b = 'assets/uploads/restaurants/'.$a;
                                                $c = base_url().'assets/uploads/restaurants/'.$a;

                                                if(file_exists($b) && $a ){ ?>
                                                  <img src="<?php echo base_url().$b; ?>">
                                                <?php } else { ?>
                                                  <img src="<?php echo base_url(); ?>assets/images/avtar/mughal_mahal_logo.png">
                                                <?php } ?>
                                              </div>

                                              <div class="form-group">
                                                <label>Headline :</label>
                                                <span class="custName"><?php echo ($restaurants->headline)?trim($restaurants->headline):'N/A'; ?></span>
                                              </div>

                                              <div class="form-group">
                                                  <label>Address :</label>
                                                  <span class="delAddress">
                                                      <?php echo ($restaurants->address)?trim($restaurants->address).'<br>':''; ?>
                                                  </span>
                                              </div>
                                            </div>
                                        </div>
                                    </div>
                                </li> 
                                <li>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                            Restaurants Basic Details
                                            </div>
                                            <div class="panel-body panelData">
                                              <div class="form-group">
                                                <label>Contact No:</label>
                                                <span class="contact">  (+965) <?php echo ($restaurants->contact_no)?trim($restaurants->contact_no):'N/A'; ?></span>
                                              </div>

                                              <div class="form-group">
                                                <label>Email:</label>
                                                <span class="contact"><?php echo ($restaurants->email)?trim($restaurants->email):'N/A'; ?></span>
                                              </div>

                                              <div class="form-group">
                                                  <label>Delivery Contact:</label>
                                                  <span class="contact">  (+965) <?php echo ($restaurants->delivery_contact_no)?trim($restaurants->delivery_contact_no):'N/A'; ?></span>
                                              </div>
                                              <div class="form-group">
                                                  <label>Is Ramadhan Month: </label>
                                                  <label for="switch-button-2"></label>
                                                    <input type="checkbox" id="switch-button-2"  onchange="IsRamadhanMonth('<?php echo $restaurants->restaurant_id; ?>');" <?php if($restaurants->is_ramadhan == 1){ echo "checked"; } ?>  data-toggle="toggle" data-onstyle="success" data-on="Yes" data-off="No"> 
                                              </div>
                                              <div class="form-group">
                                                <label>Rating:</label>
                                                <span class="custName">
                                                  <div class="r_rating_cnt">
                                                    <?php if(isset($rating[0]->avg_rate)) { ?>
                                                      <h3>
                                                          <?php for($i=1;$i<= $rating[0]->avg_rate;$i++) { ?>
                                                          <span  class="fa fa-star fsize checked "></span>
                                                          <?php } ?>
                                                      </h3>

                                                      <?php } else{?>
                                                          <h3><span class="fa fa-o"></span></h3>
                                                      <?php } ?>
                                                    
                                                  </div>
                                                </span>
                                              </div>
                                              
                                            </div>
                                        </div>
                                    </div>
                                </li>                                
                            <?php } else { ?>
                                <h3 style="text-align: center; font-size: 20px; font-weight: bold; line-height: 40px;" class="label label-success">No details available.</h3>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>  

</div>

<script type="text/javascript">
  var res_id       =$("#res_id").val();
  var deleteRestaurantDetailUrl  = "<?php echo site_url('Restaurants/deleteRestaurantDetail')?>";
  var deleteManagerDetailUrl     = "<?php echo site_url('Restaurants/deleteManagerDetail')?>";
  var changeAvailability         = "<?php echo site_url('Restaurants/changeAvailability')?>";
  var IsRamadhanMonthdata        = "<?php echo site_url('Restaurants/IsRamadhanMonthdata')?>";
  $(function() {
     $("#switch-button-2").bootstrapToggle({
      on: 'Yes',
      off: 'No'
     });
    })
  var ownerslist                 = "";
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/restaurant.js"></script>

