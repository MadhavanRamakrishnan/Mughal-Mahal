
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

<?php
$OrderStatus    = $this->config->item('OrderStatus');
$panelColor     = $this->config->item('panelColor');
$labelColor     = $this->config->item('labelColor');
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<style type="text/css">
table thead tr th,td{
    text-align: center;
    vertical-align: center;
    font-size:14px;
}

.addButtons {
    top: 0px;
}
</style>
<div class="warper container-fluid">
    <div class="page-header">
        <h1 class="pageTitle">Report</h1>
       
    </div>
   <div class="row">
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">List of Customers</span>
                </div>
                
                <div class="panel-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-3">
                                <label>From</label>  
                            </div>
                             <div class="col-md-3">
                                <label>To</label> 
                            </div>
                            <div class="col-md-6">
                            </div>
                        </div>
                        <div class="row filterRowOfRow">
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Start Date" name="startDate" value="<?php echo $startDate; ?>" id="datepicker1">
                            </div>
                       
                             <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="End Date" name="endDate" value="<?php echo $endDate; ?>" id="datepicker2">
                            </div>
                            <div class="col-md-6">
                                <a class="addButtons" href="#"><button type="submit" class="btn btn-info">Search</button></a>   
                            </div>
                        </div>
                        <hr>
                    </form>
                     <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sr #</th>
                                <th>Customer Name</th>
                                <th>Contact No.</th>
                                <th>Email</th>
                                <th>Order</th>
                                <th>Amount - KD</th>
                                <th>Registered date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(is_array($customers) && count($customers)>0){
                                foreach($customers as $key => $value){
                            ?>
                            <tr id="customers_<?php echo $value->user_id; ?>" class="customers_<?php echo $value->user_id; ?>">
                                <td class="center"><?php echo $offset++; ?></td>
                                <td>
                                    <?php echo $value->first_name? trim($value->first_name.' '.$value->last_name) : 'N/A'; ?>
                                </td>
                                <td class="center"><?php echo $value->contact_no; ?></td>
                                <td class="text-left"><?php echo $value->email;?></td>
                                <td class="center">
                                    <?php 
                                        if($value->totalOrders<=10)
                                        {
                                            $class = 'bg-info';
                                        }
                                        else if($value->totalOrders>10 && $value->totalOrders<=25)
                                        {
                                            $class = 'bg-warning';
                                        }
                                        else if($value->totalOrders>25 && $value->totalOrders<=50)
                                        {
                                            $class = 'bg-success';
                                        }
                                        else if($value->totalOrders>50)
                                        {
                                            $class = 'bg-danger';
                                        } 
                                    ?>
                                    <span class="badge noRadius <?php echo $class; ?>">
                                        <?php echo $value->totalOrders; ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <?php 
                                        if($value->totalAmount<=50)
                                        {
                                            $class1 = 'bg-info';
                                        }
                                        else if($value->totalAmount>50 && $value->totalAmount<=250)
                                        {
                                            $class1 = 'bg-warning';
                                        }
                                        else if($value->totalAmount>250 && $value->totalAmount<=500)
                                        {
                                            $class1 = 'bg-success';
                                        }
                                        else if($value->totalAmount>500)
                                        {
                                            $class1 = 'bg-danger';
                                        } 
                                    ?>
                                    <span class="badge noRadius <?php echo $class1; ?>">
                                        <?php echo ($value->totalAmount)?number_format($value->totalAmount,3,'.',''):"0"; ?>
                                    </span>
                                </td>
                                <td><?php echo date('M-d-Y',strtotime($value->registered_date)); ?></td>
                                
                            </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                    <div class="dataTables_paginate paging_simple_numbers alig-right" id="datatable-buttons_paginate"> 
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

<script type="text/javascript">
    $(document).ready(function(){
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