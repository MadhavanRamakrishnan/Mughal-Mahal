
<div class="warper container-fluid">
     <div id="loading-div-background" style="display:none">
        <div id="loading-div" class="ui-corner-all">
            <img src="http://18.216.199.131/assets/images/front-end/loading1.gif" alt="Loading..">
        </div>
    </div>
    <div class="page-header">
        <h1 class="pageTitle">Driver Report</h1>
       
    </div>
   <div class="row">
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label"></span>
                </div>
                
                <div class="panel-body">
                   
                        <div class="row">
                            <div class="col-md-3 res_div"><label>Driver</label></div>
                            <div class="col-md-3 res_div"><label>Restaurant</label></div>
                            <div class="col-md-2"><label>From</label></div>
                            <div class="col-md-2"><label>To</label></div>
                            <div class="col-md-2"><label>Payment Status</label></div>
                        </div>
                        <div class="row filterRowOfRow">
                            <div class="col-md-3 res_div">
                                <select class="form-control"  id="driverList">
                                    <option value="">All Drivers</option>
                                    <?php if(count($drivers)>0){ 
                                        foreach ($drivers as $key => $value) {
                                    ?>
                                        <option value="<?= $value->user_id; ?>"><?= $value->first_name." ".$value->last_name ; ?></option>
                                    <?php } 
                                } ?>
                                </select>
                            </div>
                            <div class="col-md-3 res_div">
                                <select class="form-control"  id="restaurantList">
                                    <option value="">All Branches</option>
                                    <?php if(count($restaurants)>0){ 
                                        foreach ($restaurants as $key => $value) {
                                    ?>
                                        <option value="<?= $value->restaurant_id; ?>"><?= $value->restaurant_name; ?></option>
                                    <?php } 
                                } ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="datepicker form-control" placeholder="Start Date" name="startDate"  id="startDate">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="datepicker form-control" placeholder="End Date" name="endDate"  id="endDate">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control"  id="paymentType" >
                                    <option value="">All</option>
                                    <?php foreach ($type as $key => $value) { ?>
                                       <option value="<?= $key ?>" ><?= $value ?></option>
                                   <?php } ?>
                                    
                                </select>
                            </div>
                            
                        </div>
                        <br>
                        <div class="row">
                             <div class="col-md-10" style="text-align:left;">
                               <button type="button" class="btn btn-info" id="searchDriverOrderCODBtn" value="export" >Report For COD</button>
                               <button type="button" class="btn btn-info exportBtn" id="exportBtn" value="export">Export</button>
                            </div>
                            <div class="col-md-2" style="text-align:right;">
                               <button type="button" class="btn btn-info" id="searchDriverOrderBtn" value="search">Search </button>
                            </div>
                        </div>

                        <hr>
                    <table class="table table-striped table-bordered report_table" id="report_table">
                        <div class="row" >
                            <div class="col-md-6 "></div>
                            <div class="col-md-6" style="text-align:right;">
                                <label class="label label-primary" style="font-size:14px;">Total Order: <strong id="totalOrders"><?= $totalOrders ?></strong></label>
                                <label class="label label-primary" style="font-size:14px;"">Total Amount: <strong id="totalAmount"><?= $totalAmount ?></strong></label>
                            </div>
                        </div>
                        <thead>
                            <th>Sr</th>
                            <th>Order Id</th>
                            <th>Driver</th>
                            <th>Amount</th>
                            <th>Order Time</th>
                            <th>Restaurant</th>
                            <th>Payment Type</th>
                            <th>Order Status</th>
                        </thead>
                        <tbody>
                        <?php if(count($repData)>0){
                                foreach ($repData as $key => $value) { ?>
                                <tr>
                                    <td><?= $offset++; ?></td>
                                    <td><?= $value->order_id; ?></td>
                                    <td><?= $value->first_name." ".$value->last_name; ?></td>
                                    <td><?= number_format($value->total_price,3,'.','')." KD"; ?></td>
                                    <td><?= date('d M Y',strtotime($value->delivered_time)); ?></td>
                                    <td><?= $value->restaurant_name; ?></td>
                                    <td><?= $this->config->item('payment_type')[$value->order_type]; ?></td>
                                    <td><?= $this->config->item('OrderStatus')[$value->order_status]; ?></td>
                                </tr>
                            <?php }
                                }
                            ?>
                        </tbody>
                    </table>
                    <div class="dataTables_paginate " > 
                        <ul class="pagination">
                         <?php foreach ($links as $link) {
                            echo "<li class='paginate_button previous' aria-controls='datatable-buttons' tabindex='0' id='datatable-buttons_previous'>". $link."</li>";
                        } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>assets/js/driverReport.js"></script>
<script type="text/javascript">
    var driverReport     ="<?= site_url('Reports/driverReport'); ?>";
    var exportReportData ="<?= site_url('Reports/exportDriverReport/'); ?>";
   $(document).ready(function(){
       $('#startDate,#endDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            changeYear: true,
            endDate: '+0d',
            todayHighlight: true
       });
       $('#startDate').datepicker().on('changeDate', function (e) {
            var minDate = new Date(e.date.valueOf());
            $('#endDate').datepicker('setStartDate', minDate);
      })
    });
</script>