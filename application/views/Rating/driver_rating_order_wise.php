<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order.js"></script>
<style>
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
        <div class="tab-content">
            <div class="panel panel-default tab-pane tabs-up active" id="all">
                <div class="panel-body">
                    <ul class="activities-list list-unstyled nicescroll" tabindex="5002" style="overflow: hidden; outline: none;"><h3><?php echo $driverName[0]->first_name.' '.$driverName[0]->last_name;?></h3>
                        <div class="panel panel-default">
                            <div class="panel-body">
                               <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr #</th>
                                            <th>Order #</th>
                                            <th>Customer Name</th>
                                            <th>Customer Rating</th>
                                            <th>Comments</th>
                                           
                                           
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php if(is_array($driverRatingById) &&  count($driverRatingById)>0){
                                          
                                            foreach($driverRatingById as $key => $value){
                                              
                                        ?>
                                        <tr id="customers_<?php echo $value->rating_id; ?>" class="customers_<?php echo $value->rating_id; ?>">
                                            <td class="center"><?php echo $offset++; ?></td>
                                        <td class="center"><?php echo str_pad($value->order_id,"6","0",STR_PAD_LEFT); ?></td>
                                        <td class="center"><?php echo  $value->first_name.' '.$value->last_name ; ?></td>
                                        
                                        <td class=""><?php for($i=1;$i<= $value->rating;$i++) { ?>
                                            <span class="fa fa-star checked"></span>
                                            <?php } ?>
                                        </td>
                                      <!--   <td class="center"><?php echo $value->reason ; ?></td> -->
                                        <td class="center"><?php echo substr($value->reason, 0, 15) . '...'; ?><a href="" class="" data-toggle="modal" data-target="#myModal<?php echo $value->rating_id ; ?>">Read more</a></td>
                                       <div class="modal fade" id="myModal<?php echo $value->rating_id ; ?>" role="dialog">
                                        <div class="modal-dialog">
                                        
                                          
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                              <h4 class="modal-title">Comment</h4>
                                            </div>
                                            <div class="modal-body">
                                              <p><?php echo $value->reason ; ?></p>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                          </div>
                                          
                                        </div>
                                      </div>

                                        </tr>
                                        <?php } } else{?>
                                        <tr><td colspan="5"><center><b>Comments not available.</b></center></td></tr>
                                     <?php   }?>
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
