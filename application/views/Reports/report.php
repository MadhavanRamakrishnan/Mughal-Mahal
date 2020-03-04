<style type="text/css">
   table > thead:first-child > tr > th:last-child{width:30%;}
</style>
<div class="warper container-fluid">
     <div id="loading-div-background" style="display:none">
        <div id="loading-div" class="ui-corner-all">
            <img src="http://18.216.199.131/assets/images/front-end/loading1.gif" alt="Loading..">
        </div>
    </div>
    <div class="page-header">
        <h1 class="pageTitle">Report</h1>
       
    </div>
   <div class="row">
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">List of reports</span>
                </div>
                
                <div class="panel-body">
                   
                        <div class="row">
                            <div class="col-md-3"><label>Select a report:</label></div>
                            <div class="col-md-3"><label>From:</label></div>
                            <div class="col-md-3"><label>To:</label></div>
                            <div class="col-md-3 res_div"><label>Restaurant:</label></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-control"  id="report_type" >
                                   
                                    <?php foreach ($type as $key => $value) { 
                                        if($key < 7 || $key == 9){ ?>
                                       <option value="<?php echo $key ?>" <?php echo ($key =='1')?'selected':"" ?> ><?php echo $value ?></option>
                                   <?php } } ?>
                                    
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <input type="text" class="datepicker form-control" placeholder="Start Date" name="startDate"  id="startDate">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="datepicker form-control" placeholder="End Date" name="endDate"  id="endDate">
                            </div>
                            <div class="col-md-3 res_div">
                                <select class="form-control"  id="restaurant" <?php echo ($resId !="")?"disabled":""; ?>>
                                    <option value="">All Branches</option>
                                    <?php if(count($restaurants)>0){ 
                                        foreach ($restaurants as $key => $value) {
                                    ?>
                                        <option value="<?php echo $value->restaurant_id; ?>" <?php echo ($resId == $value->restaurant_id)?"selected":""; ?> ><?php echo $value->restaurant_name; ?></option>
                                    <?php } 
                                } ?>
                                </select>
                            </div>
                            
                        </div>
                        <div class="row">
                        <div class="col-md-6">
                               <button type="button" class="btn btn-success addButtons" value="search">Search</button>
                               <button type="button" class="btn btn-success addButtons" value="export" >Export</button>
                               <input type="hidden" id="btn_type" value="">
                            </div>
                        </div>
                        <hr>
                        <h4><b><div class="report_type type_val"><?php echo $this->config->item('report_type')[1]; ?></div></h3></b>
                        <hr>
                    <table class="table table-striped table-bordered report_table" id="datatable" style="display:none;" > </table>
                    <table class="table table-striped table-bordered report_table" id="report_table">
                        <thead>
                            <th>Name</th>
                            <th>TotalOrders</th>
                            <th>TotalSales</th>
                        </thead>
                        <tbody>
                            <?php if(count($orderData)>0){
                                foreach ($orderData as $key => $value) { ?>
                                <tr>
                                <td><?php echo $value->Name; ?></td>
                                <td><?php echo $value->TotalOrders; ?></td>
                                <td><?php echo number_format($value->TotalSales,3, '.', ''); ?> KD</td>
                                </tr>
                            <?php }
                                }
                            ?>
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
    var getReport     ="<?php echo base_url('Reports/getReport'); ?>";
    var getReportdata ="<?php echo base_url('Reports/getReportData/'); ?>";
   $(document).ready(function(){
       $('#startDate,#endDate').datepicker({
            autoclose: true,
            changeYear: true,
            endDate: '+0d',
            todayHighlight: true
       });
       $('#startDate').datepicker().on('changeDate', function (e) {
            var minDate = new Date(e.date.valueOf());
            console.log(minDate);
            $('#endDate').datepicker('setStartDate', minDate);
      })
    });
</script>