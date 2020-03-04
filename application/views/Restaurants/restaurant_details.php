<style type="text/css">
  .center{text-align: center;}
  .openingTime{float:right;margin-top: -7px;margin-right: -14px; margin-right:0px;margin-left:10px;}
  .time tr th{width: 10%; vertical-align:center !important;}
  .time tr td{ vertical-align: center;}
  .list-group-horizontal .list-group-item {display: inline-block;}
  .list-group-horizontal .list-group-item {padding: 1px 15px;background-color: none;border: 0px solid #ddd;}
  .is_ramadhan .btn{border-radius: 18px;}
  #pending{border-top: 1px solid #ddd;}
</style>
<div class="warper container-fluid">
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="page-header">
          <h1 class="pageTitle">Details of <?php echo $restaurants->restaurant_name; ?></h1>
        </div>
      </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content">
                <div class="panel panel-default tab-pane tabs-up active" id="pending" style="">
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
                                              <?php if($restaurants->address){ ?>
                                                <div class="form-group">
                                                    <label>Address :</label>
                                                    <span class="delAddress">
                                                        <?= ($restaurants->address)?trim($restaurants->address).'<br>':''; ?>
                                                    </span>
                                                </div>
                                              <?php } ?>

                                              <div class="form-group">
                                                  <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12"><label>Additional Delivery Time: </label></div> 
                                                    <div class="col-lg-6 col-md-6 col-xs-12" style="text-align: left;">
                                                      <select onChange="setAdditionalDeliveryTime(<?= $rId ?>)" id="additionalDeliveryTime" class="form-control">
                                                          <?php foreach ($deliveryTimes as $key) { ?>
                                                              <option value="<?= $key ?>" <?= ($key ==$restaurants->extra_delivery_time)?'selected':''; ?> ><?= $key ?></option>
                                                          <?php  } ?>
                                                      </select>
                                                    </div> 
                                                    <span class="col-lg-12 col-md-12 col-xs-12 additionalDeliveryTime" style="color:green;display:none">
                                                    </span>
                                                    <span class="col-lg-12 col-md-12 col-xs-12 additionalDeliveryTimeerror" style="color:red;display:none">
                                                    </span>
                                                  </div>
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
                                                  <span class="contact">  (+965)  <?php echo ($restaurants->delivery_contact_no)?trim($restaurants->delivery_contact_no):'N/A'; ?></span>
                                              </div>
                                              <div class="form-group">
                                                  <label>Available for online orders : </label>
                                                  <span>
                                                      <?php if($restaurants->is_availability == 1){ ?>
                                                      <span class="active label label-success">Yes</span>
                                                      <?php } else { ?>
                                                      <span class="active label label-danger">No</span>
                                                      <?php } ?>
                                                  </span>
                                              </div>
                                             
                                              <div class="form-group is_ramadhan">
                                                  <label>Is Ramadhan Month: </label>
                                                  <input type="checkbox" id="switch-button-2"  onchange="IsRamadhanMonth('<?php echo $restaurants->restaurant_id; ?>');" <?php if($restaurants->is_ramadhan == 1){ echo "checked"; } ?>  data-toggle="toggle" data-onstyle="success" data-on="Yes" data-off="No"> 

                                              </div>
                                              
                                              <div class="form-group">
                                                <label>Owner:</label>
                                                <span class="custName"><?php echo $restaurants->o_fname.' '.$restaurants->o_lname; ?></span>
                                              </div>
                                              
                                              <div class="form-group">
                                                 <label>Opening Time :</label>
                                               
                                                        <table class="table table-striped table-bordered time" ">
                                                          <?php foreach ($days as $key => $value) { ?>
                                                            <tr>
                                                               <th ><?php echo $value; ?></th>
                                                               <td class="list-group list-group-horizontal">
                                                                
                                                                <?php if(isset($resData[$key]) && count($resData[$key])>0){ 
                                                                    
                                                                    foreach ($resData[$key] as $k1 => $v1) {?>

                                                                      <li class="list-group-item"><?php echo $v1['from_time']."  -  ".$v1['to_time']; ?></li>
                                                                <?php }  } ?>
                                                               
                                                              </td>
                                                            </tr>
                                                          <?php } ?>
                                                         
                                                        </table>
                                                        
                                                        <a class="btn btn-success openingTime" href="<?php echo site_url('Restaurants/checkRestaurantTime/'.$restaurants->restaurant_id) ;?>" >Update restaurant time</a>
                                               </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>                                
                            <?php } else { ?>
                                <h3 class="label label-success">No details available.</h3>
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
  var changeAvailability            = "<?= site_url('Restaurants/changeAvailability') ?>";
  var IsRamadhanMonthdata           = "<?= site_url('Restaurants/IsRamadhanMonthdata') ?>";
  var setAdditionalDeliveryTimeUrl  = "<?= site_url('Restaurants/setAdditionalDeliveryTime') ?>";
  $(function() {
     $("#switch-button-2").bootstrapToggle({
      on: 'Yes',
      off: 'No'
     });
    })
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/restaurant.js"></script>



