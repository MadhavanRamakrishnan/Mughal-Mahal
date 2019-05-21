<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/easy-responsive-tabs.css " />
<script src="<?php echo base_url(); ?>assets/js/easyResponsiveTabs.js"></script>

<style type="text/css">
    .resp-vtabs .resp-tabs-container{min-height: 361px;}
    .add_fild_row{ width:100%; float:left; padding-bottom:15px; }
    .add_main .col-md-4{ padding-left:0;}
    .date_fild label{ width:auto; float:left;  line-height:30px; }
    .date_fild input{ width:55%; float:left; height:30px; padding:0 10px; }
    .add_btn button{ padding:0 10px; height:30px; }
    .fromError ,.toError{color:red;text-align:center;padding-left:8% !important;}
    .toError{padding-left:3% !important;}
    .panel-heading{margin-bottom:10px;}
    .updateTime{float:right;}
    table tr,table tr th { text-align:center;}
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
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="col-md-9">
                        <i class="fa fa-newspaper-o"></i> 
                        <span class="nav-label">Approve restaurant opening time</span>    
                    </div>
                    <div  class="col-md-3 ">
                        <a href="<?php echo site_url('Restaurants/addRestaurantTime/'.$res_id); ?>"><button type="button" class="btn btn-success updateTime">Set opening Time</button></a>
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
                                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Current From</th>
                                                        <th>Current To</th>
                                                        <th>Updated From</th>
                                                        <th>Updated To</th>
                                                        <th>Approve Updated Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        $j=1;
                                                     if(isset($resData[$key]) && count($resData[$key])>0){
                                                        foreach ($resData[$key] as $k1 => $v1) {  ?>
                                                             <tr>
                                                                <td><?php echo $j++; ?></td>
                                                               <td><?php echo $v1['from_time']; ?></td>
                                                               <td><?php echo $v1['to_time']; ?></td>
                                                               <td><?php echo $v1['update_from_time']; ?></td>
                                                               <td><?php echo $v1['update_to_time']; ?></td>
                                                               <td>
                                                               <?php if($v1['approved'] ==0){ ?>
                                                               <button type="button"  class="btn btn-success approveTime" time_id="<?php echo $v1['time_id']; ?>" data-toggle="modal" data-target="#approveTime" data-backdrop="static" data-keyboard="false"  ><i class="fa fa-check"  title="Approve time"></i></button>
                                                               <?php } 
                                                                else{

                                                                  echo " - ";  
                                                                }
                                                               ?></td>
                                                            </tr>
                                                    <?php }
                                                      }?>
                                                </tbody>
                                            </table>

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
    <div id="approveTime" class="modal fade" role="dialog" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="text-align:center !important;">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4><label class="modal-title">Confirmation</label></h4>
                </div>
                <div class="modal-body" style="text-align:center !important;" >
                     <h5>Are you sure to approve this restaurant time?</h5>
                     <span style="color: red; display: none;" id="errapproveDish"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="approveTimeData">Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/restaurantTime.js"></script>
<script type="text/javascript">
    var approveTime="<?php echo base_url('Restaurants/approveRestaurantTime'); ?>";
</script>
    
