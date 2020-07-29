<!-- Warper Ends Here (working area) -->
        
        <footer class="container-fluid footer">
        	Copyright &copy; 2020 <a href="#"><?php echo $this->config->item('company_name'); ?></a>
            <a href="#" class="pull-right scrollToTop"><i class="fa fa-chevron-up"></i></a>
        </footer>
        
    </section>
    <!-- FROM HEADER START -->
    <!-- Bootstrap -->
    <script src="<?php echo base_url(); ?>assets/js/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap/bootstrap-toggle.min.js"></script>
    <script type="text/javascript">
        var logoutLink ="<?php echo base_url("Login/logoutUser");  ?>";
    </script>

    <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAaWqUMoAlFiTao-1cDOQTtckOyA43eWQ4&libraries=places"></script>

    <!-- DateTimePicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>

    <!-- FROM HEADER END -->

    <script src="<?php echo base_url(); ?>assets/js/footer.js"></script>
  
    <script src="<?php echo base_url(); ?>assets/js/plugins/datatables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_bootstrap.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/datatables/jquery.dataTables-conf.js"></script>
    
    <!-- Custom JQuery -->
    <script src="<?php echo base_url(); ?>assets/js/app/custom.js" type="text/javascript"></script>
    
    <!-- DateTime Picker -->
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-datetimepicker/bootstrap-datepicker.js"></script>

     <!-- Chosen -->
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-chosen/chosen.jquery.js"></script>
    
        <script type="text/javascript">
            $('#basic-datatable123').dataTable({
                "ordering": false
            });
        </script>
    </body>
</html>
<!-- <Modal for delete> -->
 
<div id="confirmationModal" class="modal fade" role="dialog" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Warning</h4>
      </div>
      <div class="modal-body">
        <p id="deleteMsg"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="delete_btn">Yes</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>

