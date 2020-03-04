<section>
    <div class="container-fluid">
        <div class="row flex-column">
            <div class="div-category">

                <div class="cat-body">
                    <h4 class="cat-title">Order Category</h4>
                    <div class="cat-form">
                        <h5 class="cat-subtitle">Add Category</h5>
                        <input type="text" name="type_name" id="type_name" class="form-control" placeholder="Write Your Category">

                        <div class="cat-box row">

                            <?php

                            for($i = 0; $i < sizeof($result); $i++){
                                echo '<div class="col-md-3 cat-items box_'.$result[$i]->id.'">';
                                echo '<a href="javascript:void(0)" class="remove-icon" onclick="deleteTypeId('.$result[$i]->id.');">';
                                echo '<i class="fa fa-trash-o" aria-hidden="true"></i></a>'.$result[$i]->type.'</div>';
                            }

                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal start -->
<div id="cngStatusmodal" class="modal fade" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title" id="modal-title"></h4>
            </div>
            <div class="modal-body">
                <p id="statusMsg"></p>
            </div>
        </div>
    </div>
</div>
<!-- Modal end -->

<div id="deleteModal" class="modal fade" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
                <p id="msg">Are you sure to remove this order type?</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="delete_id" id="delete_id">
                <button type="button" class="btn btn-primary" id="cngOrder">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var addOrderType = "<?php echo site_url('Orders/addOrderType');?>";
    var deleteOrderType = "<?php echo site_url('Orders/deleteOrderType');?>";
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ordertype.js"></script>