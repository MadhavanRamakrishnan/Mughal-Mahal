<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<style type="text/css">
table thead tr th, .center{
  text-align: center;
}
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
.cus_switch .switch{ top:33px; }
.update_restaurant_btn{ width:100%; float: left; padding:20px 0 0; }
</style>
<div class="warper container-fluid">
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order.js"></script>
  <div class="row">
    <div class="col-lg-12">
      <ul class="nav nav-tabs" role="tablist">
        <li class=""><a href="<?php echo site_url("Setting/index"); ?>" >Delivery Time</a></li>

        <li class="active" ><a href="<?php echo site_url("Setting/RestaurantAvelibility"); ?>" role="tab" data-toggle="tab">Restaurant Avalibility</a></li>
      </ul>
      <?php $DeliveryTime = array("3000"=>"50 Minute", "3600"=>"60 Minute", "4200"=>"70 Minute","4800"=>"80 Minute", "5400"=>"90 Minute", "6000"=>"100 Minute");?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="page-header f-left">
        <h1>Update Restaurant Avalibility</h1>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <i class="fa fa-newspaper-o"></i> 
          <span class="nav-label">Update Restaurant Avalibility</span>
        </div>
        <div class="col-md-12 col-lg-12" style="margin-top:10px;">
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
        </div>
        <div class="panel-body">
          <?php  foreach ($restoruntData as $key => $value) { 
                        //echo "<pre>";print_r($value);exit;
            ?>
            <form method="post" action="<?php  echo site_url('Setting/RestaurantAvelibility/');?>" enctype="multipart/form-data">
             <input type="hidden" name="restaurant_id[]" value="<?php echo $value->restaurant_id;?>">
             <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">

                  <div class="col-lg-12"><br><br>
                    <label class="control-label"><?php echo $value->restaurant_name;?>:</label>
                    <!--  <input class="form-control" name="restaurant_name[]" id="restaurant_name" placeholder="Enter Restaurant Name"  type="text" value="<?php echo (set_value('restaurant_name'))?set_value('restaurant_name'):$value->restaurant_name; ?>"> -->
                    <div class="color-red"><?php echo form_error('restaurant_name'); ?></div>

                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="col-lg-12">
                  <div class="form-group cus_switch">
                    <label class="control-label" style="min-height: 20px;">&nbsp;</label>
                    <label class="switch">
                      <input type="checkbox" name="is_active[<?php echo $value->restaurant_id;?>]" <?php echo $value->is_availability == 1 ? "checked":"" ?>/>
                      <span class="slider round"></span>
                    </label>
                    <div class="color-red"><?php echo form_error('delivery_time'); ?></div>
                  </div>
                </div>
              </div>
            </div>


            <?php  } ?>
            <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="update_restaurant_btn">
                <div class="form-group">

                  <input type="submit" class="btn btn-primary" name="update" value="Update">
                
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>