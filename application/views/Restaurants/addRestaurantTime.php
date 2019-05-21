<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/easy-responsive-tabs.css " />
<script src="<?php echo base_url(); ?>assets/js/easyResponsiveTabs.js"></script>
<link rel="stylesheet" href="./css/bootstrap-material-datetimepicker.css" />

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/theme/bootstrap-datetimepicker/css/bootstrap-material-datetimepicker.css" />
<script src="<?php echo base_url(); ?>assets/theme/bootstrap-datetimepicker/js/material.min.js">
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/theme/bootstrap-datetimepicker/js/moment-with-locales.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/theme/bootstrap-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

<style type="text/css">
    .resp-vtabs .resp-tabs-container{min-height: 361px;}
    .add_fild_row{ width:100%; float:left; padding-bottom:15px; }
    .add_main .col-md-4{ padding-left:0;}
    .date_fild label{ width:auto; float:left; margin-right:10px; line-height:30px; }
    .date_fild input{ width:75%; float:left; height:30px; padding:0 10px; }
    .add_btn button{ padding:0 10px; height:30px; }
    .invalidTime{color:red;text-align:center;}
    .add_btn a{color:#c9302c;}
</style>
<div class="warper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Restaurant Time</h1>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Set restaurant opening time</span></div>
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
                       <div id="parentHorizontalTab">
                            <div>
                                <!--vertical Tabs-->

                                <div id="ChildVerticalTab_1">
                                    <ul class="resp-tabs-list ver_1">
                                        <?php foreach ($days as $key => $value) { 
                                            echo "<li>".$value."</li>";
                                         } ?>
                                    </ul>
                                    <div class="resp-tabs-container ver_1">
                                    <?php foreach($days as $key => $value) { ?>
                                        <div class="day_tab" res_id="<?php echo $res_id; ?>">
                                            <div class="allData<?php echo $key; ?>">
                                                <?php  if(isset($resData[$key]) && count($resData[$key])>0){
                                                    foreach ($resData[$key] as $k1 => $v1) {  ?>
                                                    <div class="form-group addMoreTime add_main">
                                                        <div class="add_fild_row">
                                                            <div class="col-md-4 col-sm-5 col-xs-12">
                                                                <div class="date_fild">
                                                                    <label class="control-label">From:</label>
                                                                    <input class="form-control fromTimePicker" disabled value="<?php echo $v1['from_time']; ?>" >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 col-sm-5 col-xs-12">
                                                                <div class="date_fild">
                                                                    <label class="control-label">To:</label>
                                                                    <input class="form-control toTimePicker" disabled value="<?php echo $v1['to_time']; ?>">
                                                                </div>
                                                                    
                                                                    
                                                            </div>
                                                           
                                                            <div class="col-md-4 col-sm-2 col-xs-12">
                                                                <div class="add_btn">
                                                                    <button type="button"  class="btn btn-danger editTime" day="<?php echo $key; ?>" ><i class="fa fa-pencil"></i></button>
                                                                    <button type="button"  class="btn btn-success saveTime" id="<?php echo $v1['time_id'] ?>" day="<?php echo $key; ?>" ><i class="fa fa-check"></i></button>
                                                                    <button type="button"  class="btn btn-primary addTime" day="<?php echo $key; ?>">+</button>
                                                                    <button type="button" class="btn btn-primary removeTime" day="<?php echo $key; ?>" data-toggle="modal" data-target="#deleteTime" data-backdrop="static" data-keyboard="false" title="Remove time"  id="<?php echo $v1['time_id'] ?>">-</button>
                                                                    <?php if($v1['is_approved'] !=1){ ?>
                                                                  <a href="#" data-toggle="tooltip" title="This time is not approved by admin"><i class="fa fa-question-circle is_approved"></i></a>
                                                            <?php } ?>
                                                                 </div>
                                                                  
                                                            </div>
                                                            <div class="row">
                                                                <span class="col-md-8 invalidTime"></span>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                <?php }
                                                 }else{?>
                                                    <div class="form-group addMoreTime add_main">
                                                        <div class="add_fild_row">
                                                            <div class="col-md-4 col-sm-5 col-xs-12">
                                                                <div class="date_fild">
                                                                    <label class="control-label">From:</label>
                                                                    <input class="form-control fromTimePicker" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 col-sm-5 col-xs-12">
                                                                    <div class="date_fild">
                                                                        <label class="control-label">To:</label>
                                                                        <input class="form-control toTimePicker" disabled>
                                                                    </div>
                                                            </div>
                                                            <div class="col-md-4 col-sm-2 col-xs-12">
                                                                <div class="add_btn">
                                                                    <button type="button"  class="btn btn-danger editTime" day="<?php echo $key; ?>" ><i class="fa fa-pencil"></i></button>
                                                                    <button type="button"  class="btn btn-success saveTime" day="<?php echo $key; ?>" ><i class="fa fa-check"></i></button>
                                                                    <button type="button"  class="btn btn-primary addTime" day="<?php echo $key; ?>">+</button>
                                                                    <button type="button" class="btn btn-primary removeTime" day="<?php echo $key; ?>" data-toggle="modal" data-target="#deleteTime" data-backdrop="static" data-keyboard="false" title="Remove time">-</button>
                                                                 </div>
                                                            </div>
                                                             <div class="row">
                                                                <span class="col-md-8 invalidTime"></span>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                <?php }?>
                                                
                                                <div class="addMoreTimeDiv<?php echo $key; ?>"></div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="deleteTime" class="modal fade" role="dialog" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="text-align:center !important;">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4><label class="modal-title">Confirmation</label></h4>
                    </div>
                    <div class="modal-body" style="text-align:center !important;" >
                         <h5>Are you sure to delete this time?</h5>
                         <span style="color: red; display: none;" id="errDeleteDish"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="deleteTimeData">Yes</button>
                        <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/restaurantTime.js"></script>
<script type="text/javascript">
    var  resId        ="<?php echo $resId ?>";
    var updateTime    ="<?php echo base_url('Restaurants/updateRestaurantTime'); ?>";
    var deleteTime    ="<?php echo base_url('Restaurants/deleteTimeTime'); ?>";
    var checkFromTime ="<?php echo base_url('Restaurants/checkFromTime'); ?>";
    var resDetails    ="<?php echo base_url('Restaurants/RestaurantDetails'); ?>";
</script>
    
