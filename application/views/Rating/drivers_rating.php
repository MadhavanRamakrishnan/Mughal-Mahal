<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order.js"></script>
<style type="text/css">
table thead tr th, .center{
    text-align: center;
}
.checked {
color: orange;
}
.fsize{
    font-size:17px;
}
</style>

<div class="warper container-fluid">
<div class="row">
    <div class="col-lg-12">
            
        <ul class="nav nav-tabs" role="tablist">
            <?php if($userdata[0]->role_id == $this->admin_Role ||$userdata[0]->role_id == $this->sales_Role){ ?>
              <li><a href="<?php echo site_url("Rating/index"); ?>" >Restaurant Rating</a></li>
            <?php } ?>
            <li class="active"><a href="#delivery" role="tab" data-toggle="tab">Driver's Rating</a></li>
            
        </ul>

 </div>
</div>      

<div class="row">
<div class="col-md-12">
    <div class="page-header f-left">
        <h1>Driver's Rating</h1>
    </div>
</div>
</div>

<div class="row">
    <div class="col-lg-12"> 
        <div class="tab-content">
            <div class="panel panel-default tab-pane tabs-up active" id="all">
                <div class="panel-body">
                    <ul class="activities-list list-unstyled nicescroll" tabindex="5002" style="overflow: hidden; outline: none;">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr #</th>
                                            <th>Driver Name</th>
                                            <th>Driver Average Rating</th>
                                            <th>Latest Comments</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(is_array($rating) && count($rating)>0){
                                            foreach($rating as $key => $value){
                                        ?>
                                        <tr id="customers_<?php echo $value->user_id; ?>" class="customers_<?php echo $value->user_id; ?>">
                                        <td class="center"><?php echo $offset++; ?></td>
                                        <td class="center"><a href="<?php echo site_url('Rating/DriverRatingById/'.$value->user_id); ?>"><?php echo  $value->first_name .' '.$value->last_name; ?></a></td>
                                        <td class="center"><?php for($i=1;$i<= $value->avg_rate;$i++) { ?>
                                            <span class="fa fa-star checked"></span>
                                            <?php } ?>
                                        </td>
                                        <?php if ($value->reason) {?>
                                            <td class="center"><?php echo substr($value->reason, 0, 25) . '...'; ?><a href="" class="" data-toggle="modal" data-target="#myModal" onClick="setReason('<?php echo $value->reason; ?>');">Read more</a></td>
                                      
                                     <?php   }else{?>
                                                 <td class="center">N/A</td>
                                     <?php }?>
                                      
                                      
                                       </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                                    <div class="dataTables_paginate paging_simple_numbers" id="datatable-buttons_paginate"> 
                                    <ul class="pagination">
                                      <?php foreach ($links as $link) {
                                            echo "<li class='paginate_button previous' aria-controls='datatable-buttons' tabindex='0' id='datatable-buttons_previous'>". $link."</li>";
                                          } ?>
                                    </ul>
                                </div>
                                </div>
                                           
                                           
                                       
                                
                            </div>
                        </ul>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div> 

 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Comment</h4>
        </div>
        <div class="modal-body">
          <p id="reason"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

  <script type="text/javascript">
      function setReason(reason) {

         $("#reason").text(reason);
      }
  </script>