
<?php
$OrderStatus = array("0"=>"Pending", "1"=>"Order Placed", "2"=>"Order Confirmed","3"=>"Cooking", "4"=>"Driver Collected The Order", "5"=>"Driver On The Way", "6"=>"Driver Near To You", "7"=>"Delivered","8"=>"Disputed","13"=>"Discarded by Customer","14"=>"Discarded By Admin");
?>
<audio id="notifySound" preload="auto" type="audio/mpeg" src="<?= base_url();?>assets/sound/notifySound.mp3"></audio>
<button id="tapButton" style="display: none;">Tap me</button>
<div class="warper container-fluid">

    <div class="page-header">
        <h1>Dashboard <!-- <small>Let's get a quick overview...</small> --></h1></div>

    <div class="row">
        <div class="col-md-3 col-sm-6">
            <a href="<?php echo site_url("Customers/index"); ?>">
              <div class="panel panel-default clearfix dashboard-stats rounded">
                  <span id="dashboard-stats-sparkline3" class="sparkline transit"></span>
                  <i class="fa fa-users bg-success transit stats-icon"></i>
                  <h3 class="transit"><?php echo $countCustomers; ?></h3>
                  <p class="text-muted transit">Customers</p>
              </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
          <a href="<?php echo site_url("Orders/index"); ?>">
            <div class="panel panel-default clearfix dashboard-stats rounded">
                <span id="dashboard-stats-sparkline1" class="sparkline transit"></span>
                <i class="fa fa-list bg-danger transit stats-icon"></i>
                <h3 class="transit"><?php echo $countOrders; ?></h3>
                <p class="text-muted transit">Orders</p>
            </div>
          </a>
        </div>
         <div class="col-md-3 col-sm-6">
            <a href="<?php echo site_url("Drivers/index"); ?>">
              <div class="panel panel-default clearfix dashboard-stats rounded">
                <span id="dashboard-stats-sparkline2" class="sparkline transit"></span>
                <i class="fa fa-users bg-info transit stats-icon"></i>
                <h3 class="transit"><?php echo $countDrivers; ?></h3>
                <p class="text-muted transit">Drivers</p>
              </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="<?php echo site_url("Vehicles/index"); ?>">
              <div class="panel panel-default clearfix dashboard-stats rounded">
                <span id="dashboard-stats-sparkline4" class="sparkline transit"></span>
                <i class="fa fa-bicycle bg-warning transit stats-icon"></i>
                <h3 class="transit"><?php echo $countVehicles; ?></h3>
                <p class="text-muted transit">Vehicles</p>
              </div>
            </a>
        </div>
    </div>   
     <div class="row">
        
        <div class="col-md-6">
          <div class="panel panel-default">
                <div class="panel-heading">
                <i class="fa fa-list"></i> 
                    <span class="nav-label">Latest Orders</span></div>
                <div class="panel-body table-responsive">
                  <table class="table table-striped no-margn">
                      <thead>
                        <tr>
                          <th>Order Id</th>
                          <th>Name</th>
                          <th>Amount</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody class="customerOrder-detail">
                        <?php 
                            if(count($customerOrder)<1){?>
                                <tr><td colspan="6"><div align="center">Orders not found !</div></td></tr>
                                <?php   
                            }else{
                                foreach ($customerOrder as $key => $value) { 
                                    $name =$value->first_name.' '.$value->last_name;
                                 ?>
                                    <tr>
                                      <td><?php echo $value->order_id; ?></td>
                                      <td><?php echo (strlen($name)>15)?substr($name,0,15)."...":$name?></td>
                                      <td><?php echo $value->total_price; ?></td>
                                       <td class="center">
                                        <?php foreach ($OrderStatus as $key => $val) {
                                          if($key==$value->order_status){
                                                echo "<span class='label label-success'>$val</span>";
                                            } 
                                        } ?>
                                        
                                        </td>
                                    </tr>
                                <?php
                                }
                            } ?>
                      </tbody>
                    </table>
                    <?php 
                        if(count($countOrders) >= 5){
                    ?>
                      <div style="margin-top">
                        <a href="<?php echo site_url('Orders/index'); ?>" ><button type="button" class="btn btn-primary m-t-30 f-right">See More</button></a>
                        </div>
                    <?php 
                        }
                    ?>
                </div>
            </div>
        </div>
          <div class="col-md-6">
          <div class="panel panel-default">
                <div class="panel-heading">
                <i class="fa fa-users"></i> 
                    <span class="nav-label">Latest Customers</span></div>
                <div class="panel-body table-responsive">
                  <table class="table table-striped no-margn">
                      <thead>
                        <tr>
                          <!-- <th>Image</th> -->
                          <th>Name</th>
                          <th>email</th>
                          <th>Contact No.</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                            if(count($customers)<1){?>
                                <tr><td colspan="6"><div align="center">Customers not found !</div></td></tr>
                                <?php   
                            }else{
                                foreach ($customers as $key => $value) { 
                                     $name= $value->first_name.' '.$value->last_name;
                                    if($value->profile_photo != ""){
                                            $photos = base_url()."assets/uploads/users/".$value->profile_photo;
                                    }
                                    else{
                                        $photos = base_url()."assets/uploads/users/no_image.png";
                                     } ?>
                                    <tr>
                                      <td><?php  echo (strlen($name)>15)?substr($name,0,15)."...":$name; ?></td>
                                      <td><?php echo (strlen($value->email)>25)?substr($value->email,0,25)."...":$value->email; ?></td>
                                      <td><?php echo (strlen($value->contact_no)>0)?"(+965) ".substr($value->contact_no,0,12)."...":'-'; ?></td>

                                    </tr>
                                <?php
                                }
                            } ?>
                      </tbody>
                    </table>
                    <?php 
                        if($countCustomers >= 5){
                    ?>
                      <div style="margin-top">
                        <a href="<?php echo site_url('Customers/index'); ?>" ><button type="button" class="btn btn-primary m-t-30 f-right">See More</button></a>
                        </div>
                    <?php 
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order_new_dashboard.js"></script>
<script type="text/javascript">
    var getNewOrderCounts = "<?php echo site_url('Orders/getNewOrderCounts'); ?>";
    var getLatestOrderList= "<?php echo site_url('Dashboard/getLatestOrder'); ?>";
</script>