<style type="text/css">
    .addButtons {
    position: relative;
    top: 15px;
}
</style>

<div class="warper container-fluid">
    <div id="loading-div-background" style="display:none">
        <div id="loading-div" class="ui-corner-all">
            <img src="http://18.216.199.131/assets/images/front-end/loading1.gif" alt="Loading..">
        </div>
    </div>
    <div class="page-header">
        <h1 class="pageTitle">Sales Report</h1>
       
    </div>
   <div class="row">
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Sales Report</span>
                </div>
                
                <div class="panel-body">
                   
                        <div class="row">
                            <div class="col-md-3"><label>Restaurant</label></div>
                            <div class="col-md-3"><label>Payment Status</label></div>
                            <div class="col-md-3"><label>From</label></div>
                            <div class="col-md-3"><label>to</label></div>
                        </div>
                        <div class="row filterRowOfRow">
                            <div class="col-md-3 res_div">
                                <input type="hidden" id="report_type" value="7">
                                <select class="form-control"  id="restaurant" <?php echo ($resId !="")?"disabled":""; ?>>
                                    <option value="">All Branches</option>
                                    <?php if(count($restaurants)>0){ 
                                        foreach ($restaurants as $key => $value) {
                                    ?>
                                        <option value="<?php echo $value->restaurant_id; ?>" <?php echo ($resId == $value->restaurant_id)?"selected":""; ?>><?php echo $value->restaurant_name; ?></option>
                                    <?php } 
                                } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control"  id="payment_type" >
                                    <option value="">All</option>
                                    <?php foreach ($type as $key => $value) { ?>
                                       <option value="<?php echo $key ?>" ><?php echo $value ?></option>
                                   <?php } ?>
                                    
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="datepicker form-control" placeholder="Start Date" name="startDate"  id="startDate">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="datepicker form-control" placeholder="End Date" name="endDate"  id="endDate">
                            </div>
                            
                        </div>
                            
                       <div class="row">
                           <div class="col-md-6">
                               <button type="button" class="btn btn-success addButtons" onclick="$('#report_type').val(7);" value="search">Detailed report</button>
                               <button type="button" class="btn btn-success addButtons" onclick="$('#report_type').val(8);" value="search">Summary</button>

                            </div>
                       </div>
                        <hr>
                        <h4><b><div class="report_type"><?php echo $this->config->item('report_type')[7]; ?></div></h3></b>
                        <hr>
                    <table class="table table-striped table-bordered report_table" id="datatable" style="display:none;" > </table>
                    <table class="table table-striped table-bordered report_table" id="report_table">
                        <thead>
                            <th>Sr</th>
                            <th>OrderID</th>
                            <th>OrderTime</th>
                            <th>CustName</th>
                            <th>Mobile</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>AreaName</th>
                            <th>Restaurant</th>
                            <th>Status</th>
                        </thead>
                        <tbody>
                            <?php if(count($orderData)>0){
                                foreach ($orderData as $key => $value) { ?>
                                <tr>
                                <td><?php echo $offset++; ?></td>
                                <td><?php echo $value->OrderID; ?></td>
                                <td><?php echo $value->OrderTime; ?></td>
                                <td><?php echo $value->CustName; ?></td>
                                <td> (+965) <?php echo $value->Mobile; ?></td>
                                <td><?php echo $value->Amount; ?></td>
                                <td><?php echo $value->Payment; ?></td>
                                <td><?php echo $value->AreaName; ?></td>
                                <td><?php echo $value->Restaurant; ?></td>
                                <td><?php echo $value->Status; ?></td>
                                </tr>
                            <?php }
                                } ?>
                        </tbody>
                    </table>
                    <div class="dataTables_paginate paging_simple_numbers" > 
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
<script src="<?php echo base_url(); ?>assets/js/report.js"></script>
<script type="text/javascript">
    var getReport ="<?php echo base_url('Reports/getReport'); ?>";
    var getReportdata ="<?php echo base_url('Reports/getReportData/'); ?>";

    var userRole = "<?php echo $userdata[0]->role_id;?>";

    $(document).ready(function(){

        minimumDate = '';

        if(userRole == 2){
            /*minimumDate = '-1m';
            maximumDate = '+1m';*/

            var d = new Date();
            var currMonth = d.getMonth();
            var currYear = d.getFullYear();
            var startDate = new Date(currYear, currMonth-1, 1);
            minimumDate = startDate;
            var endDate = new Date(currYear, currMonth-1, 1);
        }

       $('#startDate,#endDate').datepicker({
            autoclose: true,
            changeYear: true,
            endDate: '+0d',
            todayHighlight: true,
            startDate: minimumDate
       });
       $('#startDate').datepicker().on('changeDate', function (e) {
            var minDate = new Date(e.date.valueOf());
            console.log(minDate);
            $('#endDate').datepicker('setStartDate', minDate);
      })
    });
</script>